<?php

/**
 * Common form base class.
 *
 * @method Common getObject() Returns the current form's model object
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseCommonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'series_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Series'), 'add_empty' => true)),
      'customer_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Customer'), 'add_empty' => true)),
      'customer_name'           => new sfWidgetFormInputText(),
      'customer_identification' => new sfWidgetFormInputText(),
      'customer_email'          => new sfWidgetFormInputText(),
      'invoicing_address'       => new sfWidgetFormTextarea(),
      'shipping_address'        => new sfWidgetFormTextarea(),
      'contact_person'          => new sfWidgetFormInputText(),
      'terms'                   => new sfWidgetFormTextarea(),
      'notes'                   => new sfWidgetFormTextarea(),
      'base_amount'             => new sfWidgetFormInputText(),
      'discount_amount'         => new sfWidgetFormInputText(),
      'net_amount'              => new sfWidgetFormInputText(),
      'gross_amount'            => new sfWidgetFormInputText(),
      'paid_amount'             => new sfWidgetFormInputText(),
      'tax_amount'              => new sfWidgetFormInputText(),
      'status'                  => new sfWidgetFormInputText(),
      'type'                    => new sfWidgetFormInputText(),
      'draft'                   => new sfWidgetFormInputCheckbox(),
      'closed'                  => new sfWidgetFormInputCheckbox(),
      'sent_by_email'           => new sfWidgetFormInputCheckbox(),
      'number'                  => new sfWidgetFormInputText(),
      'recurring_invoice_id'    => new sfWidgetFormInputText(),
      'issue_date'              => new sfWidgetFormDate(),
      'due_date'                => new sfWidgetFormDate(),
      'days_to_due'             => new sfWidgetFormInputText(),
      'enabled'                 => new sfWidgetFormInputCheckbox(),
      'max_occurrences'         => new sfWidgetFormInputText(),
      'must_occurrences'        => new sfWidgetFormInputText(),
      'period'                  => new sfWidgetFormInputText(),
      'period_type'             => new sfWidgetFormInputText(),
      'starting_date'           => new sfWidgetFormDate(),
      'finishing_date'          => new sfWidgetFormDate(),
      'last_execution_date'     => new sfWidgetFormDate(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'series_id'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Series'), 'required' => false)),
      'customer_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Customer'), 'required' => false)),
      'customer_name'           => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'customer_identification' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'customer_email'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'invoicing_address'       => new sfValidatorString(array('required' => false)),
      'shipping_address'        => new sfValidatorString(array('required' => false)),
      'contact_person'          => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'terms'                   => new sfValidatorString(array('required' => false)),
      'notes'                   => new sfValidatorString(array('required' => false)),
      'base_amount'             => new sfValidatorNumber(array('required' => false)),
      'discount_amount'         => new sfValidatorNumber(array('required' => false)),
      'net_amount'              => new sfValidatorNumber(array('required' => false)),
      'gross_amount'            => new sfValidatorNumber(array('required' => false)),
      'paid_amount'             => new sfValidatorNumber(array('required' => false)),
      'tax_amount'              => new sfValidatorNumber(array('required' => false)),
      'status'                  => new sfValidatorInteger(array('required' => false)),
      'type'                    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'draft'                   => new sfValidatorBoolean(array('required' => false)),
      'closed'                  => new sfValidatorBoolean(array('required' => false)),
      'sent_by_email'           => new sfValidatorBoolean(array('required' => false)),
      'number'                  => new sfValidatorInteger(array('required' => false)),
      'recurring_invoice_id'    => new sfValidatorInteger(array('required' => false)),
      'issue_date'              => new sfValidatorDate(array('required' => false)),
      'due_date'                => new sfValidatorDate(array('required' => false)),
      'days_to_due'             => new sfValidatorInteger(array('required' => false)),
      'enabled'                 => new sfValidatorBoolean(array('required' => false)),
      'max_occurrences'         => new sfValidatorInteger(array('required' => false)),
      'must_occurrences'        => new sfValidatorInteger(array('required' => false)),
      'period'                  => new sfValidatorInteger(array('required' => false)),
      'period_type'             => new sfValidatorString(array('max_length' => 8, 'required' => false)),
      'starting_date'           => new sfValidatorDate(array('required' => false)),
      'finishing_date'          => new sfValidatorDate(array('required' => false)),
      'last_execution_date'     => new sfValidatorDate(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(),
      'updated_at'              => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('common[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Common';
  }

}
