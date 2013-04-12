<?php

/**
 * ItemTax form base class.
 *
 * @method ItemTax getObject() Returns the current form's model object
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseItemTaxForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'item_id' => new sfWidgetFormInputHidden(),
      'tax_id'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'item_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('item_id')), 'empty_value' => $this->getObject()->get('item_id'), 'required' => false)),
      'tax_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('tax_id')), 'empty_value' => $this->getObject()->get('tax_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('item_tax[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ItemTax';
  }

}
