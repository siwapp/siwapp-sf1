<?php

class dbConnectionValidator extends sfValidatorBase
{
  public function configure($options = array(), $messages = array())
  {
  }

  protected function doClean($values)
  {
    $i18n = sfContext::getInstance()->getI18N();
    
    $link = @mysql_connect($values['host'], $values['username'], $values['password']);
    
    if(!$link)
    {
      throw new sfValidatorErrorSchema($this, array(
        'connection' => new sfValidatorError($this, 
          $i18n->__('Can\'t connect to the database. Please review the data.'))));
    }
    
    // create the database if not exists
    $sql_create = "CREATE DATABASE IF NOT EXISTS `".$values['database']."` DEFAULT CHARACTER SET UTF8";
    if(!mysql_query($sql_create, $link))
    {
      throw new sfValidatorErrorSchema($this, array(
        'connection' => new sfValidatorError($this, 
          $i18n->__('Can\'t create the database.'))));
    }
    
    // check we can select the database
    $db_select = mysql_select_db($values['database']);
    
    if(!$db_select)
    {
      throw new sfValidatorErrorSchema($this, array(
        'connection' => new sfValidatorError($this, 
          $i18n->__('Can\'t select the database.'))));
    }
    
    // check we can create and drop tables
    $sql_tables = "CREATE TABLE test (`id` INTEGER) ENGINE=InnoDB";
    $res_tables = mysql_query($sql_tables, $link);
    if(!$res_tables)
    {
      throw new sfValidatorErrorSchema($this, array(
        'connection' => new sfValidatorError($this, 
          $i18n->__('Can\'t create tables in the database. Please review privileges of the user.'))));
    }
    
    $sql_drop = "DROP TABLE test";
    $res_drop = mysql_query($sql_drop, $link);
    if(!$res_drop)
    {
      throw new sfValidatorErrorSchema($this, array(
        'connection' => new sfValidatorError($this, 
          $i18n->__('Can\'t drop tables in the database. Please review privileges of the user.'))));
    }

    // check we can overwrite in case it's needed
    if($this->isPreviousInstall($link) && 
       !isset($values['overwrite']))
    {
      throw new sfValidatorErrorSchema($this,array(
        'connection' => new sfValidatorError($this,
                                             $i18n->__("There are some tables at your database that will be overwritten by the Siwapp installation process").'.'.$i18n->__('You need to click the checkbox to allow the tables to be overwritten'))));

    }
    
    mysql_close($link);

    return $values;
  }

  /** 
   * check if there is a previous siwapp database
   * @param $link database connection
   * @return true if there are some tables on the database that are about to be overwritten
   *              by the new installation
   *
   */
  private function isPreviousInstall($link)
  {
    $res = @ mysql_query("show tables",$link);
    $db_tables = array();
    while($data = mysql_fetch_array($res))
    {
      $db_tables[] = strtolower($data[0]);
    }

    $dir = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'sql';
    $sql = file_get_contents($dir.DIRECTORY_SEPARATOR.'schema.sql');

    preg_match_all('/create\s+table\s+(\S+)/i',$sql,$matches);
    $model_tables = array();
    $model_tables = array_map('strtolower',$matches[1]);

    if(count(array_intersect($model_tables,$db_tables)))
    {
      return true;
    }
    return false;
  }
}
