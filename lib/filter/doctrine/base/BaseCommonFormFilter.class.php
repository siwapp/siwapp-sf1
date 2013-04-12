<?php

/**
 * Common filter form base class.
 *
 * @package    siwapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCommonFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'series_id'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Series'), 'add_empty' => true)),
      'customer_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Customer'), 'add_empty' => true)),
      'customer_name'           => new sfWidgetFormFilterInput(),
      'customer_identification' => new sfWidgetFormFilterInput(),
      'customer_email'          => new sfWidgetFormFilterInput(),
      'invoicing_address'       => new sfWidgetFormFilterInput(),
      'shipping_address'        => new sfWidgetFormFilterInput(),
      'contact_person'          => new sfWidgetFormFilterInput(),
      'terms'                   => new sfWidgetFormFilterInput(),
      'notes'                   => new sfWidgetFormFilterInput(),
      'base_amount'             => new sfWidgetFormFilterInput(),
      'discount_amount'         => new sfWidgetFormFilterInput(),
      'net_amount'              => new sfWidgetFormFilterInput(),
      'gross_amount'            => new sfWidgetFormFilterInput(),
      'paid_amount'             => new sfWidgetFormFilterInput(),
      'tax_amount'              => new sfWidgetFormFilterInput(),
      'status'                  => new sfWidgetFormFilterInput(),
      'type'                    => new sfWidgetFormFilterInput(),
      'draft'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'closed'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sent_by_email'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'number'                  => new sfWidgetFormFilterInput(),
      'recurring_invoice_id'    => new sfWidgetFormFilterInput(),
      'issue_date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'due_date'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'days_to_due'             => new sfWidgetFormFilterInput(),
      'enabled'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'max_occurrences'         => new sfWidgetFormFilterInput(),
      'must_occurrences'        => new sfWidgetFormFilterInput(),
      'period'                  => new sfWidgetFormFilterInput(),
      'period_type'             => new sfWidgetFormFilterInput(),
      'starting_date'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'finishing_date'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'last_execution_date'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'series_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Series'), 'column' => 'id')),
      'customer_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Customer'), 'column' => 'id')),
      'customer_name'           => new sfValidatorPass(array('required' => false)),
      'customer_identification' => new sfValidatorPass(array('required' => false)),
      'customer_email'          => new sfValidatorPass(array('required' => false)),
      'invoicing_address'       => new sfValidatorPass(array('required' => false)),
      'shipping_address'        => new sfValidatorPass(array('required' => false)),
      'contact_person'          => new sfValidatorPass(array('required' => false)),
      'terms'                   => new sfValidatorPass(array('required' => false)),
      'notes'                   => new sfValidatorPass(array('required' => false)),
      'base_amount'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'discount_amount'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'net_amount'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gross_amount'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid_amount'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'tax_amount'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'status'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'type'                    => new sfValidatorPass(array('required' => false)),
      'draft'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'closed'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sent_by_email'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'number'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'recurring_invoice_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'issue_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'due_date'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'days_to_due'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'enabled'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'max_occurrences'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'must_occurrences'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'period'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'period_type'             => new sfValidatorPass(array('required' => false)),
      'starting_date'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'finishing_date'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'last_execution_date'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('common_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Common';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'series_id'               => 'ForeignKey',
      'customer_id'             => 'ForeignKey',
      'customer_name'           => 'Text',
      'customer_identification' => 'Text',
      'customer_email'          => 'Text',
      'invoicing_address'       => 'Text',
      'shipping_address'        => 'Text',
      'contact_person'          => 'Text',
      'terms'                   => 'Text',
      'notes'                   => 'Text',
      'base_amount'             => 'Number',
      'discount_amount'         => 'Number',
      'net_amount'              => 'Number',
      'gross_amount'            => 'Number',
      'paid_amount'             => 'Number',
      'tax_amount'              => 'Number',
      'status'                  => 'Number',
      'type'                    => 'Text',
      'draft'                   => 'Boolean',
      'closed'                  => 'Boolean',
      'sent_by_email'           => 'Boolean',
      'number'                  => 'Number',
      'recurring_invoice_id'    => 'Number',
      'issue_date'              => 'Date',
      'due_date'                => 'Date',
      'days_to_due'             => 'Number',
      'enabled'                 => 'Boolean',
      'max_occurrences'         => 'Number',
      'must_occurrences'        => 'Number',
      'period'                  => 'Number',
      'period_type'             => 'Text',
      'starting_date'           => 'Date',
      'finishing_date'          => 'Date',
      'last_execution_date'     => 'Date',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
    );
  }
}
