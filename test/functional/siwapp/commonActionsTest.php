<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  get('common/ajaxAddInvoiceItem', array('invoice_id'=>2))->
  with('request')->begin()->
    isParameter('module', 'common')->
    isParameter('action', 'ajaxAddInvoiceItem')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value*="2"]')-> 
    checkElement('span.taxes')->
  end()->
  
  get('common/ajaxAddInvoiceItemTax')->
  with('request')->begin()->
    isParameter('module', 'common')->
    isParameter('action', 'ajaxAddInvoiceItemTax')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('select.tax')-> 
  end()->
  
  get('common/ajaxInvoiceItemsAutocomplete', array('q'=>'illo'))->
  with('request')->begin()->
    isParameter('module', 'common')->
    isParameter('action', 'ajaxInvoiceItemsAutocomplete')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/Ab illo inventore veritatis/')-> 
  end()->

  get('common/ajaxTagsAutocomplete', array('q'=>'veniam', 'limit'=>2))->
  with('request')->begin()->
    isParameter('module', 'common')->
    isParameter('action', 'ajaxTagsAutocomplete')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/{\"veniam\":\"veniam\"}/')-> 
  end()
;