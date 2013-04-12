<?php
class RecurringInvoiceSearchForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'query'     => new sfWidgetFormInputText(),
      'series_id' => new sfWidgetFormChoice(array('choices' => 
        array(''=>'') + SeriesTable::getChoicesForSelect(false))),
      'period_type' => new sfWidgetFormChoice(array('choices' => 
        array(''=>'') + RecurringInvoiceTable::$period_types)),
      'tags'      => new sfWidgetFormInputHidden(),
      'status'    => new sfWidgetFormInputHidden(),
    ));
    
    $this->widgetSchema->setLabels(array(
      'query'  => 'Search',
      'series' => 'Series',
    ));
    
    $dateRangeValidatorOptions = array(
      'required'  => false
    );
    
    $this->setValidators(array(
      'query'   => new sfValidatorString(array('required' => false, 'trim' => true)),
      'series'  => new sfValidatorString(array('required' => false, 'trim' => true)),
      'tags'    => new sfValidatorString(array('required' => false, 'trim' => true)),
      'status'  => new sfValidatorString(array('required' => false, 'trim' => true)),
    ));
    
    $this->widgetSchema->setNameFormat('search[%s]');
    $this->widgetSchema->setFormFormatterName('list');
  }
}