<?php

/**
 * RecurringInvoice form.
 *
 * @package    form
 * @subpackage RecurringInvoice
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RecurringInvoiceForm extends CommonForm
{

  public function configure()
  {
    unset($this['created_at'],$this['updated_at'],$this['occurrences'],$this['last_execution_date'],
          $this['desync'], $this['closed']);

    $this->widgetSchema['starting_date']   = new sfWidgetFormI18nJQueryDate($this->JQueryDateOptions);
    $this->widgetSchema['finishing_date']  = new sfWidgetFormI18nJQueryDate($this->JQueryDateOptions);
    $this->widgetSchema['period']          = new sfWidgetFormInputText(array(), array('size'=>3));
    $this->widgetSchema['period_type']     = new sfWidgetFormSelect(array('choices' => RecurringInvoiceTable::$period_types));
    $this->widgetSchema['max_occurrences'] = new sfWidgetFormInputText(array(), array('size'=>3));
    $this->widgetSchema['days_to_due']     = new sfWidgetFormInputText(array(), array('size'=>3));
    $this->widgetSchema['year']            = new sfWidgetFormI18nDateDMY(array(
                                                                           'use'=>'years',
                                                                           'culture'=>$this->culture,
                                                                           'empty_value'=>'any')
                                                                         );
    $this->widgetSchema['month']           = new sfWidgetFormI18nDateDMY(array(
                                                                           'use'=>'months',
                                                                           'culture'=>$this->culture,
                                                                           'empty_value'=>'any')
                                                                         );

    $this->widgetSchema->setDefault('enabled','0');
    $this->widgetSchema->setNameFormat("invoice[%s]");
    
    $this->validatorSchema['starting_date'] = new sfValidatorDate(array('required' => true));

    $this->validatorSchema['period']->setMessage('invalid', 'Invalid Period');
    $this->validatorSchema['period']->setMessage('required', 'Period required');

    $this->setValidator('period_type',new sfValidatorChoice(array('choices'=>array_keys(RecurringInvoiceTable::$period_types))));
    $vd = new sfValidatorSchema(array(
                                  'period_type'=> $this->getValidator('period_type'),
                                  'period' => $this->getValidator('period')));

    $this->validatorSchema->setPostValidator(
                              new SiwappConditionalValidator(
                                    array(
                                      'control_field'    =>'enabled',
                                      'validator_schema' => $vd
                                      )
                                    )
                              );
    $this->widgetSchema->setLabels(array(
      'max_occurrences'         =>'Maximum number of occurrences',
      'period'                  =>'Create every',
      'period_type'             => ' '
      ));
    
    parent::configure();
  }

  public function getModelName()
  {
    return 'RecurringInvoice';
  }
}