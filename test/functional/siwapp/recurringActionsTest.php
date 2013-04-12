<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$deleteQuery = Doctrine_Query::create()->delete('Invoice i')->where('i.recurring_invoice_id IS NOT NULL');
$countQuery  = RecurringInvoiceQuery::create();

$browser = new SiwappTestBrowser();

$browser->signin()
  ->info("Recurring Invoices listing")
  ->get('recurring')
  ->with('request')->begin()
    ->isParameter('module', 'recurring')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
  ;

$browser->test()->is($countQuery->countPending(), 3, "There are 3 pending invoices.");

$browser
  ->info("Pending invoices generation")
  ->with('response')->begin()
    ->checkElement('#pendingButton')
  ->end()
  ->click('Generate pending invoices now')
  ->with('request')->begin()
    ->isParameter('module', 'recurring')
    ->isParameter('action', 'generate')
    ->end()
  ->with('response')->begin()
    ->isRedirected()
  ->end()
  ->followRedirect()
  ->with('request')->begin()
    ->isParameter('module', 'recurring')
    ->isParameter('action', 'index')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
  ->end()
  ;

$browser->test()->is($countQuery->countPending(), 0, "No pending invoices.");

// delete generated invoices and reset recurrings status
$deleteQuery->execute();
foreach (RecurringInvoiceQuery::create()->execute() as $r)
{
  $r->refresh(true);
  $r->checkStatus()->save();
}

$browser->
  info('Testing creating a new Invoice')->
  get('recurring')->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('New Recurring Invoice', array())->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'new')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('Save', array('invoice' => $fake_recurring_array))->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'create')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the created recurring exists in the db')->
  with('doctrine')->begin()->
    check('RecurringInvoice', array('customer_name'=>$fake_recurring_array['customer_name']))->
  end()
;

$recurring = Doctrine::getTable('Common')->findOneBy('customer_name', $fake_recurring_array['customer_name']);

// change some value to edit:
$fake_recurring_array['period'] = '10';
$fake_recurring_array['terms'] = 'new test terms';

$browser->
  info('Testing the editing of a recurring invoice')->
  get('recurring/edit/'.$recurring->id)->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'edit')->
  end()->
  click('Save', array('invoice' => $fake_recurring_array))->
  with('request')->begin()->
    isParameter('module','recurring')->
    isParameter('action','update')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the changed value in the database')->
  with('doctrine')->begin()->
    check('RecurringInvoice',array(
                               'id'        => $recurring->id,
                               'period' => $fake_recurring_array['period']
                               ))->
  end()
  ;

$browser->
  info('Recurring Invoice Deleting')->
  call('/recurring/delete', 'POST', array('id' => $recurring->id))->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'delete')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'recurring')->
    isParameter('action', 'index')->
  end()->
  with('doctrine')->begin()->
    check('Invoice',array('id' => $recurring->id), false)->
  end()
  ;

