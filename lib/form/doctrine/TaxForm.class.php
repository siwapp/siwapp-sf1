<?php

/**
 * Tax form.
 *
 * @package    form
 * @subpackage Tax
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class TaxForm extends BaseTaxForm
{
  public function configure()
  {
    unset($this['items_list']);
    $this->widgetSchema['value'] = new sfWidgetFormInputText(array(), array('class'=>'value text-right','size'=>'5'));
    $this->widgetSchema['is_default']->setAttribute('class', 'is_default');
    $this->widgetSchema['name']->setAttribute('class', 'name');
    $this->widgetSchema['active']->setAttribute('class', 'active');
    $this->widgetSchema->setFormFormatterName('Xit');
  }
}