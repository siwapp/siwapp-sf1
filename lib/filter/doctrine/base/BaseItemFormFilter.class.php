<?php

/**
 * Item filter form base class.
 *
 * @package    siwapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseItemFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'quantity'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'discount'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'common_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Common'), 'add_empty' => true)),
      'product_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Product'), 'add_empty' => true)),
      'description'  => new sfWidgetFormFilterInput(),
      'unitary_cost' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'taxes_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Tax')),
    ));

    $this->setValidators(array(
      'quantity'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'common_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Common'), 'column' => 'id')),
      'product_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Product'), 'column' => 'id')),
      'description'  => new sfValidatorPass(array('required' => false)),
      'unitary_cost' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'taxes_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Tax', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('item_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTaxesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.ItemTax ItemTax')
      ->andWhereIn('ItemTax.tax_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Item';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'quantity'     => 'Number',
      'discount'     => 'Number',
      'common_id'    => 'ForeignKey',
      'product_id'   => 'ForeignKey',
      'description'  => 'Text',
      'unitary_cost' => 'Number',
      'taxes_list'   => 'ManyKey',
    );
  }
}
