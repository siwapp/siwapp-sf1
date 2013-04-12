<?php

/**
 * dashboard actions.
 *
 * @package    siwapp
 * @subpackage dashboard
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class dashboardActions extends sfActions
{
  public function preExecute()
  {
    $this->currency = $this->getUser()->getAttribute('currency');
    $this->namespace = 'invoices';
  }
  
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $namespace = $request->getParameter('searchNamespace');
    $search = $this->getUser()->getAttribute('search', null, $namespace);
    $this->maxResults = sfConfig::get('app_dashboard_max_results');
    
    $q = InvoiceQuery::create()->search($search)->limit($this->maxResults);

    // for the overdue unset the date filters, to show all the overdue
    unset($search['from'], $search['to']);
    $overdueQuery = InvoiceQuery::create()->search($search)->status(Invoice::OVERDUE);

    // totals
    $this->gross  = $q->total('gross_amount');
    $this->due    = $q->total('due_amount');
    $this->paid   = $q->total('paid_amount');
    $this->odue   = $overdueQuery->total('due_amount');
    $this->taxes  = $q->total('tax_amount');
    $this->net    = $q->total('net_amount');

    $taxes = Doctrine_Query::create()->select('t.id, t.name')
      ->from('Tax t')->execute();

    $total_taxes = array();

    foreach($taxes as $t)
    {
      if($value = $q->total_tax($t->id))
      {
        $total_taxes[$t->name] = $q->total_tax($t->id);
      }
    }
    $this->total_taxes = $total_taxes;

    // this is for the redirect of the payments forms
    $this->getUser()->setAttribute('module', $request->getParameter('module'));
    
    // link counters
    $this->recentCounter  = $q->count();
    $this->overdueCounter = $overdueQuery->count();
    // recent & overdue invoices
    $this->recent         = $q->execute();
    $this->overdue        = $overdueQuery->execute();
  }
  
}
