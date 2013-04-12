<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  info('Test the send email action')->
  post('/invoices/batch', array('ids'=> array(23, 21), 'batch_action' => 'email'))->
  with('mailer')->begin()->
    hasSent(2)->
    checkHeader('Subject', '/Invoicer LTD \[Invoice: ASET-8\]/')->
    checkBody('/\.*Invoicer LTD/')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'invoices')->
    isParameter('action', 'index')->
  end()
;

// create to fake invoices to delete them
$inv1 = new Invoice();
$inv2 = new Invoice();
$inv1->fromArray($fake_invoice_array);
$inv2->fromArray($fake_invoice_array);
$inv1->save();
$inv2->save();

$browser->
  info('Test the batch delete action')->
  post('/invoices/batch', array('ids'=> array($inv1->id, $inv2->id), 'batch_action' => 'delete'))->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('doctrine')->begin()->
    check('Invoice', array('id' => $inv1->id), false)->
    check('Invoice', array('id' => $inv2->id), false)->
  end();