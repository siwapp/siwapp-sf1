<?php

/**
 * Product form.
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProductForm extends BaseProductForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);
    $decorator = new myFormSchemaFormatter($this->getWidgetSchema());
    $this->widgetSchema->addFormFormatter('custom', $decorator);
    $this->widgetSchema->setFormFormatterName('custom');
    $common_defaults = array(
                             'reference' => 'Product reference',
                             'description'=>'Product description',
                             'price'=> 'Product price'
                             );

    $this->widgetSchema->setHelps($common_defaults);

  }
}


