<?php
/**
 * Form to store key/value pairs for Property values
 */
class NameValueForm extends BaseForm
{
  public function configure()
  {
    $valueRequired  = $this->getOption('value_required', false);
    $valueValidator = $this->getOption('numeric', false) ?
                        new sfValidatorNumber(array('required' => $valueRequired)):
                        new sfValidatorString(array('required' => $valueRequired));
    $this->setWidgets(array(
      'name'  => new sfWidgetFormInputText(),
      'value' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'name'  => new sfValidatorString(array('required' => false)),
      'value' => $valueValidator,
    ));

    $this->widgetSchema->setNameFormat('kv[%s]');
    $this->widgetSchema->setFormFormatterName('listB');
  }
  
  public function save()
  {
    // Don't save me, please.
  }
}
