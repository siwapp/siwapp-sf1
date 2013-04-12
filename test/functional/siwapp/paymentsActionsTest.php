<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$invoice = Doctrine::getTable('Invoice')->findOneBy('CustomerName', 'Initech');
$invoice_array = $invoice->toArray();
$payment = $invoice->getPayments()->getFirst();
$payment_revert = $payment->toArray();

$browser->signin()->
  info('Test that form loads right')->
  setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')->
  get('/payments/form', array('invoice_id'=>$invoice->getId()))->
  with('response')->begin()->
    checkElement('form.payments-form')->
    checkElement(sprintf('input[value=%d]', $invoice->getId()))->
    checkElement('input.notes')->
    checkElement('input.remove')->
  end()->
  info('Testing the post of the form')->
  setField(sprintf('payments[old_%s][amount]', $payment->getId()), 1)->
  setField(sprintf('payments[old_%s][notes]', $payment->getId()), 'this has changed')->
  click('Save')->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'dashboard')->
    isParameter('action', 'index')->
  end()->
  info('Checking the changed value')->
  with('doctrine')->begin()->
    check('Payment', array(
      'notes' => 'this has changed',
      'amount' => 1,
      'invoice_id' => $invoice->getId()
    ))->
  end()->
  
  info('Now check that sending the form in invoices module, we go to invoices module again')->
  get('invoices')->
  get(sprintf('/payments/form?invoice_id=%s', $invoice->getId()))->
  info('Testing the post of the form, and reset original values')->
  setField(sprintf('payments[old_%s][amount]', $payment->getId()), 8110.14)->
  setField(sprintf('payments[old_%s][notes]', $payment->getId()), 'Ut enim ad minim')->
  click('Save')->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'index')->
  end()->
  info('Checking the changed value')->
  with('doctrine')->begin()->
    check('Payment', array(
      'id'     => $payment->getId(),
      'notes'  => 'Ut enim ad minim',
      'amount' => '8110.140000000000000%', // 15 decimal positions as in schema / % forces LIKE in SQL
      'invoice_id' => $invoice->getId()
    ), 1)->
  end()->
  
  info('Checking the add payment')->
  setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')->
  get('/payments/add', array(
    'invoice_id' => $invoice->getId(),
    'index' => 'fakeIndex'
    ))->
  with('response')->begin()->
    checkElement('.xit')->
    checkElement('input#payments_new_fakeIndex_id')->
    checkElement(sprintf('input[value=%d]', $invoice->getId()))->
    checkElement('input.notes')->
  end()->
  
  info('Checking the deletion')->
  setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')->
  get('/payments/form', array('invoice_id' => $invoice->getId()))->
  setField(sprintf('payments[old_%s][remove]', $payment->getId()), 1)->
  click('Save')->
  info('Checking there is no payment in the database')->
  with('doctrine')->begin()->
    check('Payment', array(
      'invoice_id' => $invoice->getId(),
      'notes'  => 'Ut enim ad minim',
    ), false)->
  end()
;
// now revert the payment
$payment = new Payment();
$payment->fromArray($payment_revert);
$payment->save();

$invoice->refresh(true);
$invoice->setAmounts()->save();
