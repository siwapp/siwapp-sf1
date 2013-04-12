<?php

class PaymentsForm extends FormsContainer
{
  public function __construct($invoice_id = null, $options = array(), $CSRFSecret = null)
  {
    if ($invoice_id)
    {
      $this->old_payments = Doctrine::getTable('Payment')->createQuery()
        ->where('invoice_id = ?', $invoice_id)
        ->orderBy('date')
        ->execute();
      
      $forms = array();
      foreach ($this->old_payments as $payment)
      {
        $forms['old_'.$payment->getId()] = new PaymentForm($payment, $options, false);
      }
    }
    
    parent::__construct($forms, 'PaymentForm', $options, $CSRFSecret);
  }
  
  public function configure()
  {
    $this->widgetSchema->setNameFormat('payments[%s]');
    $this->widgetSchema->setFormFormatterName('noLabels');
  }
  
}
