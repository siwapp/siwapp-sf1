<?php

class sfGuardFormSignin extends BasesfGuardFormSignin
{
  public function bind(array $taintedValues = null,array $taintedFiles = null)
  {
    $dbError = false;
    try
    {
      parent::bind($taintedValues,$taintedFiles);
    }
    catch(sfDatabaseException $e)
    {
      $dbError = true;
    }
    catch(Doctrine_Connection_Exception $de)
    {
      $dbError = true;
    }
    if($dbError)
    {
      $this->values = array();
      $dummyVal = new sfValidatorPass(array(),array('invalid'=>"Can't connect to database"));
      $ve = new sfValidatorError($dummyVal,'invalid');
      $this->errorSchema = new sfValidatorErrorSchema($dummyVal,array($ve));
    }
  }

}