<?php

/**
 * upgrade actions.
 *
 * @package    siwapp
 * @subpackage upgrade
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class upgradeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $migration = new Doctrine_Migration(sfConfig::get('sf_lib_dir').'/migration/doctrine');
    
    if ($migration->getCurrentVersion() < $migration->getLatestVersion())
    {
      try
      {
        $migration->migrate($migration->getLatestVersion());
      }
      catch (Exception $e)
      {
      }
      
      $this->errors = array_merge(
        array_map(create_function('$e', 'return \' - \'.$e->getMessage();'), $migration->getErrors())
      );
    }
  }
}
