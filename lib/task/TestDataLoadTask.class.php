<?php

class TestDataLoadTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace = 'siwapp';
    $this->name      = 'test-data-load';
    $this->briefDescription = 'Loads the data/fixtures/test';
    $this->detailedDescription = <<<EOF
      The [test-data-load|INFO] task loads the data/fixtures/test into the database.
EOF;
  }
      
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $this->runTask('doctrine:drop-db', '--no-confirmation');
    $this->runTask('doctrine:create-db');
    // ATTENTION: HACK TO MAKE THIS WORK UNDER UBUNTU LINUX
    Doctrine::loadModels(sfConfig::get('sf_lib_dir').'/model/doctrine');
    $this->runTask('doctrine:insert-sql');
    $this->runTask('doctrine:data-load', 'data/fixtures/user.yml');
    $this->runTask('doctrine:data-load', 'data/fixtures/templates.yml');
    $this->runTask('doctrine:data-load', 'data/fixtures/test');
    $this->runTask('siwapp:calculate-totals', '--all');
    
    $this->logSection('siwapp', 'Test data succesfully loaded');
  }
    
}