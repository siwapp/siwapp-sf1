<?php
/**
 * These tests are only for the edition of invoices
 *
 **/
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

//save action
$browser->signin()->
  get('invoices')->
  with('request')->begin()->
    isParameter('module','invoices')->
    isParameter('action','index')->
  end()->
  info('Testing creating a new Invoice')->
  with('response')->begin()->
    checkElement('a#new-invoice-button')->
  end()->
  click('New Invoice',array())->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'new')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('Save', array('invoice' => $fake_invoice_array))->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'create')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the changed value')->
  with('doctrine')->begin()->
    check('Invoice', array(
      'customer_name' => $fake_invoice_array['customer_name'],
      'due_date'      => $fake_invoice_array['due_date']
      ))->
    check('Item', array(
      'description'  => $fake_invoice_array['Items'][0]['description'],
      'unitary_cost' => $fake_invoice_array['Items'][0]['unitary_cost']
      ))->
  end()->
  info('Checking the created Customer')->
with('doctrine')->begin()->
check('Customer', array(
                        'name' => $fake_invoice_array['customer_name'],
                        'email'=>$fake_invoice_array['customer_email']
                        ))->
end();

$invoice = Doctrine::getTable('Common')->findOneBy('customer_name', $fake_invoice_array['customer_name']);

// change some value to edit:
$fake_invoice_array['due_date'] = '2011-01-01';
$fake_invoice_array['terms'] = 'new test terms';
$fake_invoice_array['customer_email'] = 'mod@test.com';

//edit action
$browser->
  info('Testing editing an Invoice')->
  get('/invoices/edit/'.$invoice->id)->
  with('request')->begin()->
    isParameter('module','invoices')->
    isParameter('action','edit')->
  end()->
  click('Save', array('invoice' => $fake_invoice_array))->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'update')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the changed value in the database')->
  with('doctrine')->begin()->
    check('Invoice', array(
                      'id'        => $invoice->id,
                      'due_date'  => $fake_invoice_array['due_date'],
                      'terms'     => $fake_invoice_array['terms']
                      ))->
  end()->
  info('Checking the modified customer values in the invoice doesnt affect customer')->
  with('doctrine')->
    begin()->
      check('Customer',array(
                             'name'=>$fake_invoice_array['customer_name'],
                             'email'=>$fake_invoice_array['customer_email']
                             ),false)->
end();
  
//delete
$browser->
  info('Invoice Deleting')->
  call('/invoices/delete', 'POST', array('id' => $invoice->id))->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'delete')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'index')->
  end()->
  with('doctrine')->begin()->
    check('Invoice', array('id' => $invoice->id), false)->
  end()
;

$browser->
  info('Test that a closed invoice goes to show action')->
  get('/invoices/edit/21')->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'edit')->
  end()->
  with('response')->begin()->
    matches('/Initech/')->
  end()
;
