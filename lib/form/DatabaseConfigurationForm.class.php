<?php

/**
 * Database configuration form for the installer application.
 *
 * @package    siwapp
 * @subpackage form
 * @author     Enrique Martinez
 */
class DatabaseConfigurationForm extends BaseForm
{
  public function configure()
  {
    $user = sfContext::getInstance()->getUser();
    
    $this->setWidgets(array(
      'database' => new sfWidgetFormInputText(),
      'username' => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputText(),
      'host'     => new sfWidgetFormInputText(),
      'overwrite'=> new sfWidgetFormInputCheckbox()
    ));
       
    $this->widgetSchema->setLabels(array(
      'database' => 'Database Name',
      'username' => 'User Name',
      'password' => 'Password',
      'host'     => 'Database Host',
      'overwrite'=> 'Overwrite previous Siwapp installations'
    ));
    
    $this->setDefaults(array(
      'database' => $user->getAttribute('database' ,'siwapp'),
      'username' => $user->getAttribute('username' ,'siwapp'),
      'host'     => $user->getAttribute('host', 'localhost')
    ));
    
    $this->setValidators(array(
      'database' => new sfValidatorString(),
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
      'host'     => new sfValidatorString(),
      'overwrite'=> new sfValidatorPass()
    ));
    
    $this->validatorSchema->setPostValidator(new dbConnectionValidator());

    $this->widgetSchema->setNameFormat('db[%s]');
  }
}
