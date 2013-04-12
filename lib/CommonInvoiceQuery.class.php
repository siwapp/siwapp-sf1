<?php

class CommonInvoiceQuery extends Doctrine_Query
{
  
  protected $_model;
  
  public static function create($conn = null, $class = null)
  {
    return new CommonInvoiceQuery($conn);
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
      if(isset($search['query']))       $this->textSearch($search['query']);
      if(isset($search['series_id']))   $this->series($search['series_id']);
      if(isset($search['customer_id'])) $this->customer($search['customer_id']);
      if(isset($search['tags']))        $this->withTags($search['tags']);
      if(isset($search['status']))      $this->status($search['status']);
      if(isset($search['period_type']) && $search['period_type']) 
        $this->andWhere("i.period_type = ?", $search['period_type']);
    }
    
    return $this;
  }
  
  public function textSearch($text)
  {
    $text = trim($text);
    if($text)
    {
      // find the ids of the invoices with items that contains the text
      $items = Doctrine_Query::create()
        ->select('common_id')
        ->from('item')
        ->where('Description LIKE ?', "%$text%")
        ->execute();
        
      $ids = array();
      foreach ($items as $item)
      {
        if (!in_array($item->getCommonId(), $ids))
          $ids[] = $item->getCommonId();
      }
        
      $itemsOr = $ids ? " OR i.id IN (".implode(',', $ids).")" : null;

      $this
        ->orWhere("(i.customer_name LIKE '%$text%'"
          ." OR i.customer_identification LIKE '%$text%'"
          ." OR i.customer_email LIKE '%$text%'"
          ." OR i.notes LIKE '%$text%'"
          ." OR i.terms LIKE '%$text%'"
          ." OR i.contact_person LIKE '%$text%'"
          .$itemsOr
          .")"
          );
    }
    
    return $this;
  }
  
  public function series($series_id = null)
  {
    if($series_id)
    {
      $this->andWhere("i.series_id = ?", $series_id);
    }
    
    return $this;
  }
  
  public function customer($customer_id = null)
  {
    if($customer_id)
    {
      $this->andWhere("i.customer_id = ?", $customer_id);
    }
    
    return $this;
  }
  
  public function withTags($tags)
  {
    if ($tags)
    {
      $taggings = TagTable::getTaggings($tags, array('model' => $this->_model));
      $cmp_tags = isset($taggings[$this->_model]) ? $taggings[$this->_model] : array(0);
      $this->andWhereIn('i.id', $cmp_tags);
    }
    
    return $this;
  }
  
  /**
   * adds conditions to status. The parameter $status can be an array
   * of integers status (see Invoice.php)
   *
   * @param $status array with status
   * @param $is boolean to negate or not the conditions
   * @return $this
   **/
  public function status($status, $is = true)
  {
    if($status !== null && $status != "")
    {
      $status = (array) $status;
      $statusString = implode(", ", (array) $status);
      if($is)
      {
        $this->andWhere(sprintf('i.status IN (%s)', $statusString));
      }
      else
      {
        $this->andWhere(sprintf('i.status NOT IN (%s)', $statusString));
      }
    }
        
    return $this;
  }
  
  public function total($field)
  {
    $other = clone($this);

    switch($field)
    {
      case 'due_amount':
        $sum = 'SUM(gross_amount - paid_amount) as total';
        break;
      default:
        $sum = sprintf('SUM(%s) as total', $field);
        break;
    }

    $other->select($sum)->orderBy('total');
      
    return $other;
  }
  
}