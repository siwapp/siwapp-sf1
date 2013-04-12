<?php

/**
 * RecurringInvoice form base class.
 *
 * @method RecurringInvoice getObject() Returns the current form's model object
 *
 * @package    siwapp
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRecurringInvoiceForm extends CommonForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('recurring_invoice[%s]');
  }

  public function getModelName()
  {
    return 'RecurringInvoice';
  }

}
