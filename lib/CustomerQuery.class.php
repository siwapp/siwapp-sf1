<?php

class CustomerQuery extends Doctrine_Query
{
  public static function create($conn = null, $class = null)
  {
    $q = new CustomerQuery($conn);
    $q->from("Customer c, c.Commons i WITH i.type= 'Invoice'")
      ->orderBy('c.name asc')
      ->groupBy('id');
    
    return $q;
  }

  public function getClone()
  {
    $other = clone($this);
    return $other;
  }

  public function search($search = null)
  {
    if($search)
    {
      if(isset($search['query']))
      {
        $this->textSearch($search['query']);
      }
      if(isset($search['from']))
      {
        $this->fromDate($search['from']);
      }
      if(isset($search['to']))
      {
        $this->toDate($search['to']);
      }
    }
    return $this;
  }
  
  public function textSearch($text)
  {
    $text = trim($text);
    if($text)
    {
      $this
        ->addWhere("(c.name LIKE '%$text%'".
                   "OR c.identification LIKE '%$text%' ".
                   "OR c.contact_person LIKE '%$text%')");

    }
    return $this;
  }

  public function orderBy($order)
  {
    if(strlen(strstr($order, 'due_amount')) > 0)
    {
      $this->addSelect("c.id, SUM(i.gross_amount-i.paid_amount) AS due_amount");
    }
    if(strlen(strstr($order, 'gross_amount')) >0)
    {
      $this->addSelect("c.id, SUM(if(i.gross_amount is null,0,i.gross_amount)) as gross_amount");
    }

    return parent::orderBy($order);
  }
  
  public function total($field)
  {
    $other = clone($this);

    switch($field)
    {
      case 'due_amount':
        $sum = 'SUM(if(i.gross_amount is null,0,i.gross_amount) '.
          '- if(i.paid_amount is null,0,i.paid_amount)) as total';
        break;
      default:
        $sum = sprintf('SUM(if(i.%s is null,0,i.%s)) as total',$field,$field);
        break;
    }
    $other->select($sum)->orderBy('total')
      ->addSelect("'t' AS true_column")
      ->addWhere("i.draft = ?",0)
      ->groupBy('true_column');

    return $other->fetchOne() ? $other->fetchOne()->getTotal() : 0;
  }

  /**
   * Limits the results to those customers whose invoices are issued in a date greater or equal than that
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
      return $this->andWhere('i.customer_id = id')
        ->andWhere('i.issue_date >= ?', sfDate::getInstance($date)->to_database());
    }
  }
  
  /**
   * Limits the results to those customer whose invoices are issued in a date smaller or equal than that
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
      return $this->andWhere('i.customer_id = id')
        ->andWhere('i.issue_date < ?', sfDate::getInstance($date)->addDay(1)->to_database());
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