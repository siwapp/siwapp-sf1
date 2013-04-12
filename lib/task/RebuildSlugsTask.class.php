<?php

class RebuildSlugsTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));
    
    $this->namespace = 'siwapp';
    $this->name      = 'rebuild-slugs';
    $this->briefDescription = 'Rebuilds slugs for sensible models';
    $this->detailedDescription = <<<EOF
      The [rebuild-slugs|INFO] task is useful if you changed your system's PHP version and you get UNIQUE INDEX errors.
      
      symfony siwapp:rebuild-slugs
EOF;
  }
      
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    
    $total = CustomerTable::rebuildSlugs();
    $this->logSection('siwapp', "$total Customer slugs rebuilt.");
  }
}