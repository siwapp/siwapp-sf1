<?php

/**
 * estimates actions.
 *
 * @package    siwapp
 * @subpackage estimates
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class estimatesActions extends sfActions
{
  public function preExecute()
  {
    $this->currency = $this->getUser()->getAttribute('currency');
    $this->culture  = $this->getUser()->getCulture();
  }
  
  private function getEstimate(sfWebRequest $request)
  {
    $this->forward404Unless($estimate = Doctrine::getTable('Estimate')->find($request->getParameter('id')),
      sprintf('Object estimate does not exist with id %s', $request->getParameter('id')));
      
    return $estimate;
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $namespace  = $request->getParameter('searchNamespace');
    $search     = $this->getUser()->getAttribute('search', null, $namespace);
    $sort       = $this->getUser()->getAttribute('sort', array('issue_date', 'desc'), $namespace);
    $page       = $this->getUser()->getAttribute('page', 1, $namespace);
    $maxResults = $this->getUser()->getPaginationMaxResults();
    
    $q = EstimateQuery::create()->search($search)->orderBy("$sort[0] $sort[1], number $sort[1]");
    // totals
    $this->gross = $q->total('gross_amount');

    $this->pager = new sfDoctrinePager('Estimate', $maxResults);
    $this->pager->setQuery($q);
    $this->pager->setPage($page);
    $this->pager->init();
    
    // this is for the redirect of the payments forms
    $this->getUser()->setAttribute('module', $request->getParameter('module'));
    $this->getUser()->setAttribute('page', $request->getParameter('page'));
    
    $this->sort = $sort;
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $estimate = new Estimate();
    $estimate->fromArray(array(
                          'customer_name'=>$i18n->__('Client Name'),
                          'customer_identification'=>$i18n->__('Client Legal Id'),
                          'contact_person'=> $i18n->__('Contact Person'),
                          'invoicing_address'=> $i18n->__('Invoicing Address'),
                          'shipping_address'=> $i18n->__('Shipping Address'),
                          'customer_email'=> $i18n->__('Client Email Address')
                          ));
    $this->estimateForm = new EstimateForm($estimate, array('culture'=>$this->culture));
    $this->title       = $i18n->__('New Estimate');
    $this->action      = 'create';
    $this->setTemplate('edit');
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->estimateForm = new EstimateForm(null, array('culture' => $this->culture));
    $this->title = $this->getContext()->getI18N()->__('New Estimate');
    $this->action = 'create';

    $this->processForm($request, $this->estimateForm);
    $this->setTemplate('edit');
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $estimate = $this->getEstimate($request);

    // save the original draft state
    $this->db_draft = $estimate->getDraft();
    // set draft=0 by default always
    $estimate->setDraft(false);
    $this->estimateForm = new EstimateForm($estimate, array('culture'=>$this->culture));
    
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Estimate').' '.$estimate;
    $this->action = 'update';
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $estimate_params = $request->getParameter('invoice');
    $request->setParameter('id', $estimate_params['id']);
    $this->forward404Unless($request->isMethod('post'));
    $estimate = $this->getEstimate($request);
    $this->db_draft = $estimate->getDraft();
    
    $this->estimateForm = new EstimateForm($estimate, array('culture'=>$this->culture));
    $this->processForm($request, $this->estimateForm);
    
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Estimate');
    $this->action = 'update';
    
    $this->setTemplate('edit');
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $estimate = $this->getEstimate($request);
    $estimate->delete();

    $this->redirect('estimates/index');
  }
  
  public function executeSend(sfWebRequest $request)
  {
    $estimate = $this->getEstimate($request);

    if($this->sendEmail($estimate))
    {
      $this->getUser()->info($this->getContext()->getI18N()->__('The estimate was successfully sent.'));
    }
    else
    {
      $this->getUser()->error($this->getContext()->getI18N()->__('The estimate could not be sent due to an error.'));
    }
    $dest = $request->getReferer() ? $request->getReferer() : 'estimates/edit?id='.$estimate->id;
    $this->redirect($dest);
  }
  
  protected function sendEmail(Estimate $estimate)
  {
    $i18n = $this->getContext()->getI18N();
    $result  = false;
    try {
      $message = new InvoiceMessage($estimate);
      if($message->getReadyState())
      {
        $result = $this->getMailer()->send($message);
        if($result)
        {
          $estimate->setSentByEmail(true);
          $estimate->save();
        }
      }
    } catch (Exception $e) {
      $message = sprintf($i18n->__('There is a problem with estimate %s'), $estimate).': '.$e->getMessage();
      $this->getUser()->error($message);
    }
    
    return $result;
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $i18n = $this->getContext()->getI18N();
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $estimate = $form->save();
      // update totals with saved values
      $estimate->refresh(true)->setAmounts()->save();
      
      if ($request->getParameter('send_email'))
      {
        if ($this->sendEmail($estimate))
        {
          $this->getUser()->info($i18n->__('The estimate was successfully sent.'));
        }
        else
        {
          $this->getUser()->warn($i18n->__('The estimate could not be sent due to an error.'));
        }
      }
      if ($request->getParameter('generate_invoice'))
      {
        if ($estimate->generateInvoice())
        {
          $this->getUser()->info($i18n->__('The invoice was successfully created.'));
        }
        else
        {
          $this->getUser()->warn($i18n->__('The invoice could not be created due to an error.'));
        }
      }
      $this->getUser()->info($i18n->__('The estimate was successfully saved.'));
      $this->redirect('estimates/edit?id='.$estimate->id);
    }
    else
    {
      foreach($form->getErrorSchema()->getErrors() as $k=>$v)
      {
        $this->getUser()->error(sprintf('%s: %s', $k, $v->getMessageFormat()));
      }
      $this->getUser()->error($i18n->__('The estimate has not been saved due to some errors.'));
    }
  }
  
  public function executeBatch(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $form = new sfForm();
    $form->bind(array('_csrf_token' => $request->getParameter('_csrf_token')));
    
    if($form->isValid() || $this->getContext()->getConfiguration()->getEnvironment() == 'test')
    {
      $n = 0;
      foreach($request->getParameter('ids', array()) as $id)
      {
        if($estimate = Doctrine::getTable('Estimate')->find($id))
        {
          switch($request->getParameter('batch_action'))
          {
            case 'delete':
              if ($estimate->delete()) $n++;
              break;
            case 'email':
              if ($this->sendEmail($estimate)) $n++;
              break;
          }
        }
      }
      switch($request->getParameter('batch_action'))
      {
        case 'delete':
          $this->getUser()->info(sprintf($i18n->__('%d estimates were successfully deleted.'), $n));
          break;
        case 'email':
          $this->getUser()->info(sprintf($i18n->__('%d estimates were successfully sent.'), $n));
          break;
      }
    }

    $this->redirect('@estimates');
  }
}
