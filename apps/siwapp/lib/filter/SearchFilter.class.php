<?php
/**
 * 
 */
class SearchFilter extends sfFilter
{
  public function execute($filterChain)
  {
    $user    = $this->context->getUser();
    $request = $this->context->getRequest();
    
    if ($request->getParameter('searchForm'))
    {
      $user->updateSearch($request);
    }
    
    $filterChain->execute();
  }
}
