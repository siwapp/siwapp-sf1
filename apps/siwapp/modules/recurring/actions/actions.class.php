<?php

/**
 * recurring actions.
 *
 * @package    siwapp
 * @subpackage recurring
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class recurringActions extends sfActions
{
  public function preExecute()
  {
    $this->currency = $this->getUser()->getAttribute('currency');
    $this->culture  = $this->getUser()->getCulture();
  }
  
  private function getRecurringInvoice(sfWebRequest $request)
  {
    $this->forward404Unless($invoice = Doctrine::getTable('RecurringInvoice')->find($request->getParameter('id')), 
      sprintf('Object recurring_invoice does not exist with id %s', $request->getParameter('id')));
      
    return $invoice;
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $namespace  = $request->getParameter('searchNamespace');
    $search     = $this->getUser()->getAttribute('search', null, $namespace);
    $sort       = $this->getUser()->getAttribute('sort', array('customer_name', 'asc'), $namespace);
    $page       = $this->getUser()->getAttribute('page', 1, $namespace);
    $maxResults = $this->getUser()->getPaginationMaxResults();
    
    // Warn the user if there are pending invoices waiting for being generated
    
    if ($this->pending = RecurringInvoiceQuery::create()->countPending())
    {
      $i18n = $this->getContext()->getI18N();
      $this->getUser()->warn(sprintf($i18n->__("There are %d recurring invoices that were not executed."), $this->pending));
    }
    
    $q = RecurringInvoiceQuery::create()->search($search)->orderBy("$sort[0] $sort[1]");
    // totals
    $this->gross = $q->total('gross_amount');
    // expected totals
    $this->expected = $q->getAverageDayAmount();
    
    $this->pager = new sfDoctrinePager('RecurringInvoice', $maxResults);
    $this->pager->setQuery($q);
    $this->pager->setPage($page);
    $this->pager->init();
    
    $this->sort = $sort;
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $recurring = new RecurringInvoice();
    $recurring->fromArray(array(
                            'customer_name'=>$i18n->__('Client Name'),
                            'customer_identification'=>$i18n->__('Client Legal Id'),
                            'contact_person'=> $i18n->__('Contact Person'),
                            'invoicing_address'=> $i18n->__('Invoicing Address'),
                            'shipping_address'=> $i18n->__('Shipping Address'),
                            'customer_email'=> $i18n->__('Client Email Address')
                            ));

    $this->invoiceForm = new RecurringInvoiceForm($recurring,array('culture'=>$this->culture));
    $this->title = $i18n->__('New Recurring Invoice');
    $this->action = 'create';
    $this->setTemplate('edit');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    
    $this->invoiceForm = new RecurringInvoiceForm(null, array('culture'=>$this->culture));
    $this->title       = $this->getContext()->getI18N()->__('New Recurring Invoice');
    $this->action      = 'create';
    $this->processForm($request, $this->invoiceForm);
    $this->setTemplate('edit');
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $invoice = $this->getRecurringInvoice($request);

    $this->invoiceForm = new RecurringInvoiceForm($invoice, array('culture'=>$this->culture));
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Recurring Invoice');
    $this->action = 'update';
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $invoice_params = $request->getParameter('invoice');
    $request->setParameter('id', $invoice_params['id']);
    $invoice = $this->getRecurringInvoice($request);
    $this->invoiceForm = new RecurringInvoiceForm($invoice, array('culture'=>$this->culture));
    $this->processForm($request, $this->invoiceForm);
    
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Invoice');
    $this->action = 'update';
    
    $this->setTemplate('edit');
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $invoice = $this->getRecurringInvoice($request);
    $invoice->delete(); // TODO Delete cron jobs
    
    $this->redirect('recurring/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $updated = $form->getObject()->isNew() ? 'created' : 'updated';
      $invoice = $form->save();
      // update totals with saved values
      $invoice->refresh(true)->setAmounts()->save();
      
      $this->getUser()->info("The recurring invoice was $updated successfully.");
      $this->redirect('recurring/edit?id='.$invoice->id);
    }
    else
    {
      foreach($form->getErrorSchema()->getErrors() as $k=>$v)
      {
        $this->getUser()->error(sprintf('%s: %s', $k, $v->getMessageFormat()));
      }
      $this->getUser()->error('The recurring invoice has not been saved due to some errors.');
    }
  }

  public function executeBatch(sfWebRequest $request)
  {
    $form = new sfForm();
    $form->bind(array('_csrf_token' => $request->getParameter('_csrf_token')));
    
    if($form->isValid())
    {
      if($ids = $request->getParameter('ids'))
      {
        $finder = RecurringInvoiceQuery::create()->whereIn('id', $ids)->execute();
      
        switch($request->getParameter('batch_action'))
        {
          case 'delete':
            $finder->delete();
            break;
          case 'print':
            break;
        }
      }
    }
    
    $this->redirect('@recurring');
  }

  /**
   * undocumented function
   *
   * @return void
   * @author Carlos Escribano <carlos@markhaus.com>
   **/
  public function executeGenerate(sfWebRequest $request)
  {
    if ($t1 = RecurringInvoiceQuery::create()->countPending())
    {
      RecurringInvoiceTable::createPendingInvoices();
      
      $i18n = $this->getContext()->getI18N();
      $this->getUser()->info(sprintf($i18n->__("All %d recurring invoices were processed."), $t1));
    }
    
    $this->redirect('@recurring');
  }
}
