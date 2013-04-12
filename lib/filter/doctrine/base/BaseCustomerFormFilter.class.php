<?php

/**
 * Customer filter form base class.
 *
 * @package    siwapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCustomerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(),
      'name_slug'         => new sfWidgetFormFilterInput(),
      'identification'    => new sfWidgetFormFilterInput(),
      'email'             => new sfWidgetFormFilterInput(),
      'contact_person'    => new sfWidgetFormFilterInput(),
      'invoicing_address' => new sfWidgetFormFilterInput(),
      'shipping_address'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'name_slug'         => new sfValidatorPass(array('required' => false)),
      'identification'    => new sfValidatorPass(array('required' => false)),
      'email'             => new sfValidatorPass(array('required' => false)),
      'contact_person'    => new sfValidatorPass(array('required' => false)),
      'invoicing_address' => new sfValidatorPass(array('required' => false)),
      'shipping_address'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('customer_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Customer';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'name_slug'         => 'Text',
      'identification'    => 'Text',
      'email'             => 'Text',
      'contact_person'    => 'Text',
      'invoicing_address' => 'Text',
      'shipping_address'  => 'Text',
    );
  }
}
