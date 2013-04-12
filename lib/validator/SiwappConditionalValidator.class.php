<?php

/**
 * myConditionalValidator checks provided schema_validator 
 * according to a control-field.
 * If control-field is true, then it runs the check. otherwise not.
 * If the option 'callback' is specified, then that function us run against the 
 * control-field , instead of the sfValidatorBoolean.
 *
 * @package    siwapp
 * @subpackage validator
 * @author     JoeZ <jzarate@gmail.com>
 * @version    SVN: $Id:$
 */
class SiwappConditionalValidator extends sfValidatorSchema
{

  /**
   * Constructor.
   *
   * Validator Options
   *
   *  * control_field:      (string) The control field name.
   *                        it will be "cleaned" by a sfValidatorBoolean. 
   *                        According to the result of this,
   *                        the validator specified in the "validator_schema" option will be applied or not.
   *
   *  * validator_schema:   (sfValidatorSchema) The validator_schema which is to be applied 
   *                        in case the sfValidatorBoolean applied to the control field returns true.
   *
   *  * callback            (string)  [optional] a function to be called with the control_field as an argument.
   *                        If present, sfValidatorBoolean will not be applied to the control_field.
   *                        the validator defined in the "validator_schema" option
   *                        will be applied if this function returns true.
   *
   * @param array  $options           An array of options
   * @param array  $messages          An array of error messages
   *
   * @see sfValidatorBase
   */
  public function __construct($options = array(), $messages = array())
  {
    $this->addOption('control_field');
    $this->addOption('validator_schema');
    $this->addOption('throw_global_error', false);
    $this->addOption('callback');
    parent::__construct(null, $options, $messages);
  }

  protected function configure($options = array(),$messages = array())
  {
    $this->addRequiredOption('validator_schema');
    $this->addRequiredOption('control_field');
    parent::configure($options,$messages);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    if (is_null($values))
    {
      $values = array();
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }
    
    $c_field          = $this->getOption('control_field');
    $c_value          = isset($values[$c_field]) ? 
                          $values[$c_field] : null;
    $validator_schema = $this->getOption('validator_schema') ? 
                          $this->getOption('validator_schema') : null;

    $validator_schema->setOption('allow_extra_fields',true);

    foreach($validator_schema->getFields() as $field_name => $validator)
    {
      $validator_schema[$field_name]->setOption('required',true);
    }
    
    $boolVal   = new sfValidatorBoolean();

    $errorSchema = new sfValidatorErrorSchema($this);
    try
    {
      if($this->getOption('callback'))
      {
        $values[$c_field] = call_user_func($this->getOption('callback'),$c_value);
      }
      else
      {
        $values[$c_field] = $boolVal->clean($c_value);
      }
    }
    catch(sfValidatorError $e)
    {
      throw new sfValidatorErrorSchema($this,array($c_field=>$e));
    }
    
    if(!$values[$c_field])
    {
      return $values;
    }
    else
    {
      try
      {
        $clean = $validator_schema->clean($values);
      }
      catch(sfValidatorErrorSchema $e)
      {
        $errorSchema->addErrors($e);
      }
      catch(sfValidatorError $e)
      {
        $errorSchema->addError($e);
      }
      if(count($errorSchema))
      {
        throw $errorSchema;
      }
    }
    return $values;
  }

  /**
   * @see sfValidatorBase
   */
  public function asString($indent = 0)
  {
    $options = $this->getOptionsWithoutDefaults();
    $messages = $this->getMessagesWithoutDefaults();
    unset($options['control_field'], $options['validator_schema']);

    $arguments = '';
    if ($options || $messages)
    {
      $arguments = sprintf('(%s%s)',
        $options ? sfYamlInline::dump($options) : ($messages ? '{}' : ''),
        $messages ? ', '.sfYamlInline::dump($messages) : ''
      );
    }

    return sprintf('%s%s %s%s %s',
      str_repeat(' ', $indent),
      $this->getOption('control_field'),
      $this->getOption('validator_schema'),
      $arguments
    );
  }
}
