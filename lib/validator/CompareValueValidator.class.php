<?php
class CompareValueValidator extends sfValidatorBase
{
  public function __construct($options = array(), $messages = array())
  {
    $this->addOption('value', null);
    parent::__construct($options, $messages);
  }
  
  public function doClean($value)
  {
    if ($value != $this->getOption('value'))
    {
      throw new sfValidatorErrorSchema($this, array(
        'invalid' => new sfValidatorError($this, "Don't try to hack this. You are not allowed to modify other user's profile!")
        ));
    }
    
    return $value;
  }
}