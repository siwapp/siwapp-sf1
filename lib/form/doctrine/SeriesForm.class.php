<?php

/**
 * Series form.
 *
 * @package    form
 * @subpackage Series
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SeriesForm extends BaseSeriesForm
{
  public function configure()
  {
    $this->widgetSchema['enabled'] = new sfWidgetFormInputHidden();
    
    $this->validatorSchema['first_number']->setOption('min', 1);
    $this->validatorSchema['first_number']->setMessage('min', 'The initial value should be greater or equal than 1.');
    $this->widgetSchema['name']->setAttribute('class','name');
    $this->widgetSchema['value']->setAttribute('class','value');
    $this->widgetSchema['first_number']->setAttribute('class','first_number');
    $this->widgetSchema->setFormFormatterName('Xit');
  }
}