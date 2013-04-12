<?php

class TestTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('type', null, sfCommandOption::PARAMETER_REQUIRED, 'Type of tests', 'all')
    ));

    $this->namespace = 'siwapp';
    $this->name      = 'test';
    $this->briefDescription = 'Load test data and tests everything';
    $this->detailedDescription = <<<EOF
      The [test|INFO] task loads the data/fixtures/test into the database and executes all the available tests.
      
      symfony siwapp:test [--type=all | unit | functional]
EOF;
  }
      
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $this->runTask('siwapp:test-data-load', '--env=test');
    $this->runTask('test:all');
    
    $this->logSection('siwapp', 'Siwapp tests executed');
  }
    
}