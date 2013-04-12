<?php

class InvoiceQuery extends CommonInvoiceQuery
{
  
  public static function create($conn = null, $class = null)
  {
    $q = new InvoiceQuery($conn);
    $q->from('Invoice i')
      ->orderBy('i.issue_date desc, i.number desc')
      ;
    
    $q->_model = 'Invoice';
    
    return $q;
  }
  
  public function search($search = null)
  {
    parent::search($search);
    
    if($search)
    {
      if(isset($search['from'])) $this->fromDate($search['from']);
      if(isset($search['to'])) $this->toDate($search['to']);
      if(isset($search['sent'])) $this->sent($search['sent']);
    }

    return $this;
  }
  
  public function orderBy($order)
  {
    // if $order contains due_amount
    if(strlen(strstr($order, 'due_amount')) > 0)
      $this->addSelect("i.*, i.gross_amount-i.paid_amount AS due_amount");
    
    return parent::orderBy($order);
  }
  
  public function total($field)
  {
    $other = parent::total($field);
    $result = $other->andWhere('i.draft = 0')->fetchOne()->getTotal();

    return $result;
  }
  
  public function total_tax($tax_id)
  {
    $other = clone($this);
    return $other->select("sum(it.quantity*it.unitary_cost*(1-it.discount/100)*tx.value/100) as total_tax")->addFrom('i.Items it,it.Taxes tx')->addWhere('tx.id = ?', $tax_id)->addWhere('i.draft = 0')->limit(0)->fetchOne()->getTotalTax();
  }

  
  public function sent($sent)
  {
    if(trim($sent)=='') return $this;
    $this->andWhere('i.sent_by_email = ?', $sent);
    
    return $this;
  }
  
  /**
   * Limits the results to those invoices issued in a date greater or equal than that
   * one passed as parameter.
   * @param mixed date value
   * @return InvoiceQuery the same instance
   * @author Carlos Escribano <carlos@markhaus.com>
   */
  public function fromDate($date = null)
  {
    if (!($date = $this->filterDate($date)))
    {
      return $this;
    }
    else
    {
      return $this->andWhere('i.issue_date >= ?', sfDate::getInstance($date)->to_database());
    }
  }
  
  /**
   * Limits the results to those invoices issued in a date smaller or equal than that
   * one passed as parameter.
   * @param mixed date value
   * @return InvoiceFinder the same instance
   * @author Carlos Escribano <carlos@markhaus.com>
   */
  public function toDate($date = null)
  {
    if (!($date = $this->filterDate($date)))
    {
      return $this;
    }
    else
    {
      return $this->andWhere('i.issue_date < ?', sfDate::getInstance($date)->addDay(1)->to_database());
    }
  }
  
  /**
   * Internal method to deduce a correct or null date value.
   * @param mixed date; if it is an array it must have the 'year', 'month' and 'day' keys.
   * @return mixed date string or whatever passed from outside if not "strictly invalid".
   * @author Carlos Escribano <carlos@markhaus.com>
   */
  protected function filterDate($date)
  {
    switch (true)
    {
      case (!$date || !strlen(trim(implode('', (array) $date)))):
      case (is_array($date) && (!isset($date['year']) || !isset($date['month']) || !isset($date['day']))):
        // $date is null or is an array with empty or zero string elements
        // or one of its components is not set (year, month, day)
        return null;
      case (is_array($date)):
        // is an array and is not bad formed
        return $date['year'].'-'.$date['month'].'-'.$date['day'];
      default:
        // is not an array
        return $date;
    }
  }
  
}