<?php

/**
 * payments actions.
 *
 * @package    siwapp
 * @subpackage payments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class paymentsActions extends sfActions
{
  /**
   * Returns and manage the list of payments
   */
  public function executeForm(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $i18n = $this->getContext()->getI18N();
      $data = $request->getParameter('payments');
      
      $form = new PaymentsForm($request->getParameter('invoice_id'));
      $form->bind($data);
      
      if ($form->isValid())
      {
        $form->save();
        // now recalculate totals
        $invoice = Doctrine::getTable('Invoice')->find($request->getParameter('invoice_id'));
        $invoice->refresh(true)->setAmounts()->save();
        $this->getUser()->info($i18n->__('Payments were saved successfully.'));
      }
      else
      {
        $this->getUser()->error($i18n->__('Payments could not be saved because of an unknown error.'));
      }
      
      $module = $this->getUser()->getAttribute('module');
      $page = $this->getUser()->getAttribute('page');

      $this->redirect($module.'/index?page='.$page);
    }
    else
    {
      $this->forward404Unless($invoice = Doctrine::getTable('Invoice')->find($request->getParameter('invoice_id')));
      
      $form = new PaymentsForm($request->getParameter('invoice_id'), array(
        'culture' => $this->getUser()->getCulture()
        ));

      return $this->renderPartial('payments/form', array(
        'form' => $form, 
        'invoice_id' => $request->getParameter('invoice_id')
        ));
    }
  }
  
  public function executeAdd(sfWebRequest $request)
  {
    $this->forward404Unless($index = $request->getParameter('index'));
    $payment = new Payment();
    $payment->setInvoiceId($request->getParameter('invoice_id'));
    
    // insert a PaymentForm with csrf protection disabled 
    $form = new PaymentForm($payment, array('culture'=>$this->getUser()->getCulture()), false);
    $form->getWidgetSchema()->setNameFormat('payments[new_'.$index.'][%s]');
    
    return $this->renderText('<li><ul><a href="#" class="xit"/>'.$form.'</ul></li>');
  }
  
}
