<?php

class CalculateTotalsTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_NONE, 'Process all the invoices'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace = 'siwapp';
    $this->name      = 'calculate-totals';
    $this->briefDescription = 'Calculate totals for every invoice.';
    $this->detailedDescription = <<<EOF
      The [calculate-totals|INFO] task makes a loop over all the opened and overdue invoices, calculates total amounts and saves them, updating their status.
      If you want to process all the invoices, including recurring ones, then add the option --all
      
      symfony siwapp:calculate-totals
EOF;
  }
      
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    if($options['all'])
    {
      CommonTable::calculateTotals(true);
    }
    else
    {
      CommonTable::calculateTotals();
    }
    
    $this->logSection('siwapp', 'Calculated totals invoices');
  }
    
}