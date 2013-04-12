<?php

/**
 * common actions.
 *
 * @package    siwapp
 * @subpackage common
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commonActions extends sfActions
{
  /**
   * Receives the Invoice form data and calculates each row total amount and
   * full invoice totals. Returns a json
   *
   * @param 'invoice' from Request
   * @return JSON through Response
   */
  public function executeCalculate(sfWebRequest $request)
  {
    $currency = PropertyTable::get('currency');
    $format   = new sfNumberFormat($this->culture);
    
    $data = $request->getParameter('invoice');
    $this->getResponse()->setHttpHeader('Content-Type', 'application/json; charset=utf-8');

    $invoice = new Invoice();
    
    $items  = array();
    $totals = array();
    
    if (isset($data['Items']))
    {
      foreach ((array) $data['Items'] as $itemId => $itemData)
      {
        if($itemData['remove'])
        {
          continue;
        }
        $item = new Item();
        $item->setUnitaryCost($itemData['unitary_cost']);
        $item->setQuantity($itemData['quantity']);
        $item->setDiscount($itemData['discount']);
        
        if (isset($itemData['taxes_list']))
        {
          $taxes = Doctrine::getTable('Tax')->createQuery()->whereIn('id', $itemData['taxes_list'])->execute();
          $item->Taxes = $taxes;
        }
        
        $items[$itemId] = $format->format($item->getGrossAmount(), 'c', $currency);

        $invoice->Items[] = $item;
      }
      
      $totals['base']     = $format->format($invoice->calculate('base_amount',true), 'c', $currency);
      $totals['discount'] = $format->format($invoice->calculate('discount_amount',true), 'c', $currency);
      $totals['net']      = $format->format($invoice->calculate('net_amount',true), 'c', $currency);
      $totals['taxes']    = $format->format($invoice->calculate('tax_amount',true), 'c', $currency);
      $totals['gross']    = $format->format($invoice->calculate('gross_amount',true), 'c', $currency);
    }
    else
    {
      $zero = $format->format(0, 'c', $currency);
      
      $totals['base']     = $zero;
      $totals['discount'] = $zero;
      $totals['net']      = $zero;
      $totals['taxes']    = $zero;
      $totals['gross']    = $zero;
    }
    
    return $this->renderText(json_encode(array('items' => $items, 'totals' => $totals)));
  }
  
  /**
   * AJAX action to add new invoice items
   * @param sfWebRequest $request
   * @return unknown_type
   */
  public function executeAjaxAddInvoiceItem(sfWebRequest $request)
  {
    $index = 'new_item_invoice_'.time();
    $item = new Item();
    $item->common_id = $request->getParameter('invoice_id');
    $form = new sfForm();
    $form->getWidgetSchema()->setNameFormat('invoice[%s]');
    $form->embedForm('Items', new FormsContainer(array($index=>new ItemForm($item)), 'ItemForm'));
    $params = array(
                    'invoiceItemForm' => $form['Items'][$index],
                    'item'            => $item,
                    'isNew'           => true,
                    'rowId'           => $index
                    );
    return $this->renderPartial('invoiceRow', $params);
  }
  
  public function executeAjaxAddInvoiceItemTax($request)
  {
    $taxIndex = $request->getParameter('item_tax_index');
    $invoiceItemKey = $request->getParameter('invoice_item_key');
    $selected_tax   = $request->getParameter('selected_tax',null);
    $this->taxKey = $selected_tax ? $selected_tax : 'new_'.$taxIndex;

    $this->rowId  = $invoiceItemKey;
    $this->setTemplate('_tax');
    
    return 'Span';
  }
  
  /**
   * ajax action for invoice items autocompletion
   *
   * @return JSON
   * @author Enrique Martinez
   **/
  public function executeAjaxInvoiceItemsAutocomplete(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $items = Doctrine::getTable('Item')
      ->retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));

    return $this->renderText(json_encode($items));
  }

  
  
  /**
   * ajax action for customer name autocompletion
   *
   * @return JSON
   * @author Enrique Martinez
   **/
  public function executeAjaxCustomerAutocomplete(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $q = $request->getParameter('q');
    $items = Doctrine::getTable('Customer')
      ->retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));

    return $this->renderText(json_encode($items));
  }
  
  /**
   * ajax action for tags autocompletion
   *
   * @return JSON
   * @author Enrique Martinez
   **/
  public function executeAjaxTagsAutocomplete(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit'); 
    $items = Doctrine::getTable('Tag')->createQuery()->where('name like ?', "%$q%")->limit($limit)->execute();
    
    $res = array();
    foreach ($items as $item)
    {
      $res[$item->getName()] = $item->getName();
    }
    
    return $this->renderText(json_encode($res));
  }
  
}
