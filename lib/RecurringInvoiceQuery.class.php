<?php

class RecurringInvoiceQuery extends CommonInvoiceQuery
{
  public static function create($conn = null, $class = null)
  {
    $q = new RecurringInvoiceQuery($conn);
    $q->from('RecurringInvoice i')->orderBy('i.customer_name asc');
    $q->_model = 'RecurringInvoice';
    
    return $q;
  }
  
  public function total($field)
  {
    $other = parent::total($field);
    $result = $other->andWhere('i.enabled = 1')->fetchOne()->getTotal();;

    return $result;
  }
  
  public function countPending()
  {
    $other = clone($this);
    return $other
      ->select("COUNT(*) AS pending")
      ->status(RecurringInvoice::PENDING)
      ->fetchOne()->getPending();
  }
  
  /**
   * Returns the average incomes/day from all active recurring invoices
   *
   * @return float
   **/
  public function getAverageDayAmount()
  {
    $other = clone($this);
      $select = "SUM( gross_amount / (period*(case period_type 
          when 'year' then 365
          when 'month' then 30
          when 'week' then 7
          when 'day' then 1 
          end))) AS average";

    return $other->select($select)
      ->status(array(RecurringInvoice::ENABLED, RecurringInvoice::PENDING))
      ->execute()->get(0)->getAverage();
  }
}