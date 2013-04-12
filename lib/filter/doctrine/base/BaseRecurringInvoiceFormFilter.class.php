<?php

/**
 * RecurringInvoice filter form base class.
 *
 * @package    siwapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseRecurringInvoiceFormFilter extends CommonFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('recurring_invoice_filters[%s]');
  }

  public function getModelName()
  {
    return 'RecurringInvoice';
  }
}
