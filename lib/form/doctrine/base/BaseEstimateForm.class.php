<?php

/**
 * Estimate form base class.
 *
 * @method Estimate getObject() Returns the current form's model object
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEstimateForm extends CommonForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('estimate[%s]');
  }

  public function getModelName()
  {
    return 'Estimate';
  }

}
