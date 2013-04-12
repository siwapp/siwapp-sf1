<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  info('Recurring Searching')->
  call('/recurring', 'POST', array('search'=>array(
    'query'=>'plow'
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Plow King')->
  end()->
  
  call('/recurring', 'POST', array('search'=>array(
    'series_id'=>3 // others series
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Krustyco')->
  end()->
  
  call('/recurring', 'POST', array('search'=>array(
    'status'=>1 // finished status
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/No results/')->
  end()->

  call('/recurring', 'POST', array('search'=>array(
    'period_type' => 'month'
    ),
  ))->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('table.listing tbody tr.link td:nth-child(3)', 'Plow King')->
  end()
;
