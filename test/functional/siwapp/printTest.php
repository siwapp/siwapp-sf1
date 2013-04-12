<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  info('Get the print page')->
  get('/invoices/print/print', array('ids'=>array(23)))->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/Company: Invoicer LTD/')->
    matches('/Excepteur sint occaecat/')->
  end()->
  get('/estimates/print/print', array('ids'=>array(24)))->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/Company: Invoicer LTD/')->
    matches('/Estimate/')->
  end()
;

