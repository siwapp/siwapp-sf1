<?php

/**
 * Main configuration form for the installer application.
 *
 * @package    siwapp
 * @subpackage form
 * @author     Enrique Martinez
 */
class MainConfigurationForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'admin_email'        => new sfWidgetFormInputText(),
      'admin_username'     => new sfWidgetFormInputText(),
      'admin_password'     => new sfWidgetFormInputPassword(),
      'admin_password_bis' => new sfWidgetFormInputPassword(),
      'preload'            => new sfWidgetFormInputCheckbox()
    ));
    
    $this->widgetSchema->setLabels(array(
      'admin_email'        => 'Administrator Email',
      'admin_username'     => 'Username',
      'admin_password'     => 'Password',
      'admin_password_bis' => 'Password again',
      'preload'            => 'Load sample data'
    ));
    
    $this->setValidators(array(
      'admin_email'        => new sfValidatorEmail(),
      'admin_username'     => new sfValidatorString(),
      'admin_password'     => new sfValidatorString(),
      'admin_password_bis' => new sfValidatorString(),
      'preload'            => new sfValidatorPass()
    ));
    
    $this->widgetSchema->setNameFormat('config[%s]');
    
    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare('admin_password', '==', 'admin_password_bis',
        array(), 
        array('invalid' => "Passwords don't match")
        )
      );
  }
}
