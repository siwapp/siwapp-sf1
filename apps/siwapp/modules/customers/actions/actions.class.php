<?php

/**
 * customers actions.
 *
 * @package    siwapp
 * @subpackage invoices
 * @author     Siwapp Team
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class customersActions extends sfActions
{
  public function preExecute()
  {
    $this->currency = $this->getUser()->getAttribute('currency');
    $this->culture  = $this->getUser()->getCulture();
  }
  
  private function getCustomer(sfWebRequest $request)
  {
    $this->forward404Unless($customer = Doctrine::getTable('Customer')->find($request->getParameter('id')),
      sprintf('Object customer does not exist with id %s', $request->getParameter('id')));
      
    return $customer;
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $namespace  = $request->getParameter('searchNamespace');
    $search     = $this->getUser()->getAttribute('search', null, $namespace);
    $sort       = $this->getUser()->getAttribute('sort', array('name', 'desc'), $namespace);
    $page       = $this->getUser()->getAttribute('page', 1, $namespace);
    $maxResults = $this->getUser()->getPaginationMaxResults();
    
    $q = CustomerQuery::create()->search($search)->orderBy("$sort[0] $sort[1], name $sort[1]");
    $date_range = array();
    $date_range['from'] = isset($search['from']) ? $search['from'] : null;
    $date_range['to']   = isset($search['to'])   ? $search['to']   : null;
    $this->date_range = $date_range;
    // totals
    $this->gross = $q->total('gross_amount');
    $this->due   = $q->total('due_amount');

    $this->pager = new sfDoctrinePager('Customer', $maxResults);
    $this->pager->setQuery($q);
    $this->pager->setPage($page);
    $this->pager->init();
   
    $this->getUser()->setAttribute('page', $request->getParameter('page'));
    
    $this->sort = $sort;
  }

  public function executeNew(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $customer = new Customer();
    $customer->fromArray(array(
                          'name'=>$i18n->__('Client Name'),
                          'identification'=>$i18n->__('Client Legal Id'),
                          'contact_person'=> $i18n->__('Contact Person'),
                          'invoicing_address'=> $i18n->__('Invoicing Address'),
                          'shipping_address'=> $i18n->__('Shipping Address'),
                          'email'=> $i18n->__('Client Email')
                          ));
    $this->customerForm = new CustomerForm($customer, array('culture'=>$this->culture));
    $this->title       = $i18n->__('New Customer');
    $this->action      = 'create';
    $this->setTemplate('edit');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->customerForm = new CustomerForm(null, array('culture' => $this->culture));
    $this->title = $this->getContext()->getI18N()->__('New Customer');
    $this->action = 'create';

    $this->processForm($request, $this->customerForm);
    $this->setTemplate('edit');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $customer = $this->getCustomer($request);

    $this->customerForm = new CustomerForm($customer, array('culture'=>$this->culture));
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Customer');
    $this->action = 'update';
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $customer_params = $request->getParameter('customer');
    $request->setParameter('id', $customer_params['id']);
    $this->forward404Unless($request->isMethod('post'));
    $customer = $this->getCustomer($request);
    
    $this->customerForm = new CustomerForm($customer, array('culture'=>$this->culture));
    $this->processForm($request, $this->customerForm);
    
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Customer');
    $this->action = 'update';
    
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $customer = $this->getCustomer($request);
    try
    {
      $customer->delete();
    }
    catch(siwappIntegrityException $ex)
    {
      $this->getUser()->error($this->getContext()->getI18N()
                              ->__('The customer could not be deleted. '
                                   .'Probably because an associated invoice exists')
                              );
    }
    
    $this->redirect('customers/index');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $i18n = $this->getContext()->getI18N();
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $template = 'The customer was %s successfully %s.';
      $message  = $form->getObject()->isNew() ? 'created' : 'updated';
      $suffix   = null;
      $method   = 'info';
      
      $customer = $form->save();
      
      $this->getUser()->$method($i18n->__(sprintf($template, $message, $suffix)));
      $this->redirect('customers/edit?id='.$customer->id);
    }
    else
    {
      foreach($form->getErrorSchema()->getErrors() as $k=>$v)
      {
        $this->getUser()->error(sprintf('%s: %s', $k, $v->getMessageFormat()));
      }
      $this->getUser()->error($i18n->__('The customer has not been saved due to some errors.'));
    }
  }
  
  /**
   * batch actions
   *
   * @return void
   **/
  public function executeBatch(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $form = new sfForm();
    $form->bind(array('_csrf_token' => $request->getParameter('_csrf_token')));
    
    if($form->isValid() || 
       $this->getContext()->getConfiguration()->getEnvironment() == 'test')
    {
      $n = 0;
      $e = 0;
      foreach($request->getParameter('ids', array()) as $id)
      {
        if($customer = Doctrine::getTable('Customer')->find($id))
        {
          switch($request->getParameter('batch_action'))
          {
            case 'delete':
              try
              {
                $customer->delete();
                $n++;
              }
              catch (siwappIntegrityException $ex)
              {
                $e++;
              }
              break;
          }
        }
      }
      switch($request->getParameter('batch_action'))
      {
        case 'delete':
          if ($n > 0)
            $this->getUser()->info(sprintf($i18n->__('%d customers were successfully deleted.'), $n));
          if ($e > 0)
            $this->getUser()->warn(sprintf($i18n->__('%d customers could not be deleted because they have associated data.'), $e));
          break;
      }
    }
    
    $this->redirect('@customers');
  }
  
  
}
