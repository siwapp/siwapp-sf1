<?php

class searchComponents extends sfComponents
{
  
  public function executeForm(sfWebRequest $request)
  {
    $this->getStuff($request);
    $this->form = new InvoiceSearchForm($this->search, array('culture'=>$this->getUser()->getCulture()));
  }

  public function executeRecurringForm(sfWebRequest $request)
  {
    $this->getStuff($request);
    $this->form = new RecurringInvoiceSearchForm($this->search);
  }

  public function executeCustomerForm(sfWebRequest $request)
  {
    $this->getStuff($request);
    $this->form = new CustomerSearchForm($this->search, array('culture'=>$this->getUser()->getCulture()));
  }

  public function executeProductForm(sfWebRequest $request)
  {
    $this->getStuff($request);
    $this->form = new ProductSearchForm($this->search, array('culture'=>$this->getUser()->getCulture()));
  }
  
  public function executeEstimateForm(sfWebRequest $request)
  {
    $this->executeForm($request);
  }
  
  private function getStuff(sfWebRequest $request)
  {
    $this->namespace     = $request->getParameter('searchNamespace');
    $this->search        = $this->getUser()->getAttribute('search', null, $this->namespace);
    $this->tags          = TagTable::getAllTagName();
    $this->selected_tags = $this->getUser()->getSelectedTags($this->search);
    
    if(isset($this->search['customer_name']))
      $this->customer_name = $this->search['customer_name'];
    else
      $this->customer_name = null;
  }
  
}