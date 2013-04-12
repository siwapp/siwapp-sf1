<?php

class SiwappModulesForm extends BaseForm
{

  public function configure()
  {
    $optional_mods = sfConfig::get('app_modules_optional');
    foreach(sfConfig::get('app_modules_optional') as $mod => $opts)
    {
      $choices[$mod] = $opts['verbose_name'];
    }
    $this->setWidgets(
             array(
                   'siwapp_modules'=> 
                   new sfWidgetFormSelectCheckbox(
                     array('choices'=> $choices),
                     array('class'=>'siwapp_modules')
                           )
                   ));
    $this->widgetSchema->setLabels(array('siwapp_modules'=>'Siwapp Modules'));
    $this->setDefaults(array('siwapp_modules'=>PropertyTable::get('siwapp_modules')));
    $this->widgetSchema->setNameFormat('config[%s]');
    $this->setValidators(array('siwapp_modules'=>new sfValidatorPass()));
  }
}