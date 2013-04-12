<?php


class EstimateTable extends CommonTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Estimate');
    }
    
    //TODO: this is exactly the same method as in InvoiceTable, refactor this
    public function getNextNumber($series_id)
    {
      $found = $this->createQuery()
        ->where('Draft = ?', 0)
        ->andWhere('series_id = ?', $series_id)
        ->execute()
        ->count();

      if ($found > 0)
      {
        $rs = $this->createQuery()
          ->select('MAX(number) AS max_number')
          ->where('Draft = ?', 0)
          ->andWhere('series_id = ?', $series_id)
          ->fetchOne();
        return intval($rs->getMaxNumber()) + 1;
      }
      else
      {
        return Doctrine::getTable('Series')->find($series_id)->getFirstNumber();
      }
    }
}