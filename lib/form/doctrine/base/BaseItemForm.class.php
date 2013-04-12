<?php

/**
 * Item form base class.
 *
 * @method Item getObject() Returns the current form's model object
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'quantity'     => new sfWidgetFormInputText(),
      'discount'     => new sfWidgetFormInputText(),
      'common_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Common'), 'add_empty' => true)),
      'product_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'description'  => new sfWidgetFormInputText(),
      'unitary_cost' => new sfWidgetFormInputText(),
      'taxes_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tax')),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'quantity'     => new sfValidatorNumber(array('required' => false)),
      'discount'     => new sfValidatorNumber(array('required' => false)),
      'common_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Common'), 'required' => false)),
      'product_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'required' => false)),
      'description'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'unitary_cost' => new sfValidatorNumber(array('required' => false)),
      'taxes_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Tax', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Item';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['taxes_list']))
    {
      $this->setDefault('taxes_list', $this->object->Taxes->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveTaxesList($con);

    parent::doSave($con);
  }

  public function saveTaxesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['taxes_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Taxes->getPrimaryKeys();
    $values = $this->getValue('taxes_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Taxes', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Taxes', array_values($link));
    }
  }

}
