<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  info('Invoice Searching')->
  
  call('/invoices', 'POST', array(
  'search' => array(
    'query' => 'initech',
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Initech')->
  end()->

  call('/invoices', 'POST', array(
  'search' => array(
    'from' => array('year'=>'2009', 'month'=>'4', 'day'=>'16'),
    'to' => array('year'=>'2009', 'month'=>'6', 'day'=>'16')
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Sample, inc')->
  end()->
  
  call('/invoices', 'POST', array(
  'search' => array(
    'series_id' => 2 // design series
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Big Kahuna Burger')->
  end()->
  
  call('/invoices', 'POST', array(
  'search' => array(
    'status' => 3, // overdue status
    'series_id' => 1 // internet series
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Allied Biscuit')->
  end()->
  
  call('/invoices', 'POST', array(
  'search' => array(
    'tags' => 'do',
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Tessier-Ashpool')->
  end()
;
