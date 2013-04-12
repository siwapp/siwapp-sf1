<?php

/**
 * Common form.
 *
 * @package    form
 * @subpackage Common
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CommonForm extends BaseCommonForm
{
  public function setup()
  {
    parent::setup();
  }

  public function configure()
  {
    $decorator = new myFormSchemaFormatter($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('custom', $decorator);
    $this->widgetSchema->setFormFormatterName('custom');
    $common_fields = Doctrine::getTable(self::getModelName())->getColumnNames();
    $model_fields = Doctrine::getTable($this->getModelName())->getColumnNames();
    foreach(array_diff($common_fields,$model_fields) as $extra)
    {
      unset($this[$extra]);
    }
    $this->widgetSchema['customer_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['recurring_invoice_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['tags']  = new sfWidgetFormInputHidden();

    $this->widgetSchema['type']  = new sfWidgetFormInputHidden(array(),
      array('value'=>$this->getModelName()));
    
    $this->widgetSchema['series_id'] = new sfWidgetFormSelect(array(
      'choices' => SeriesTable::getChoicesForSelect()));
    $this->widgetSchema['series_id']->setDefault(
      sfContext::getInstance()->getUser()->getProfile()->getSeries());
    
    $this->widgetSchema['invoicing_address'] = new sfWidgetFormTextarea();
    $this->widgetSchema['shipping_address'] = new sfWidgetFormTextarea();
    $this->widgetSchema['terms'] = new sfWidgetFormTextarea();

    $common_defaults = array(
                             'customer_name' => 'Client Name',
                             'customer_identification'=>'Client Legal Id',
                             'contact_person'=> 'Contact Person',
                             'invoicing_address'=> 'Invoicing Address',
                             'shipping_address'=> 'Shipping Address',
                             'customer_email'=> 'Client Email Address'
                             );

    $this->widgetSchema->setHelps(array_merge($this->widgetSchema->getHelps(),$common_defaults));


    $this->setDefault('tags', implode(',',$this->object->getTags()));
    $this->setDefault('terms', PropertyTable::get('legal_terms'));
    
    // validators
    $this->validatorSchema['tags']           = new sfValidatorString(array('required'=>false));
    $this->validatorSchema['customer_email'] = new sfValidatorEmail(
                                                     array(
                                                       'max_length' => 100, 
                                                       'required' => false
                                                       ),
                                                     array(
                                                       'invalid' => 'Invalid client email address'
                                                       )
                                                     );
    $this->validatorSchema['customer_name']  = new sfValidatorString(array('required' => true));

    $this->validatorSchema['series_id']  = new sfValidatorString(array('required'=>true),array('required'=>'The invoice serie is mandatory'));
    
    $iforms = array();

    foreach($this->object->getItems() as $item)
    {
      $iforms[$item->id] = new ItemForm($item);
    }

    $itemForms = new FormsContainer($iforms,'ItemForm');
    
    $this->embedForm('Items', $itemForms);
  }


  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['Items']))
    {
      $this->embeddedForms['Items']->fixEmbedded($taintedValues['Items']);
      $this->embedForm('Items',$this->embeddedForms['Items']);
    }
    parent::bind($taintedValues, $taintedFiles);
  }

  public function doUpdateObject($values)
  {
    parent::doUpdateObject($values);
    $this->getObject()->setTags($values['tags']);
    $this->getObject()->clearRelated();
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    $this->embeddedForms['Items']->addFixed('common_id',$this->object->id);
    parent::saveEmbeddedForms($con,$forms);
  }

}