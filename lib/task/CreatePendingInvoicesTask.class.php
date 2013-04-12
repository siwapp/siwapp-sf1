<?php
  /**
   * Checks pending jobs on the recurring_invoice table
   * @author JoeZ  <jzarate@gmail.com>
   */
class CreatePendingInvoicesTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // Custom options
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_REQUIRED, 'Date', sfDate::getInstance()->dump())
      ));

      $this->namespace           = 'siwapp';
      $this->name                = 'create-pending-invoices';
      $this->briefDescription    = 'Generates all pending invoices';
      $this->detailedDescription = <<<EOF

The [create-pending-invoices|INFO] task checks the database and generates all pending invoices.
EOF;
  }
      
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    RecurringInvoiceTable::createPendingInvoices();
    
    $this->logSection('siwapp', 'Done');
  }
}