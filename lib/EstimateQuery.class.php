<?php

class EstimateQuery extends InvoiceQuery
{
  public static function create($conn = null, $class = null)
  {
    $q = new EstimateQuery($conn);
    $q->from('Estimate i')->orderBy('i.customer_name asc');
    $q->_model = 'Estimate';
    
    return $q;
  }
  
}