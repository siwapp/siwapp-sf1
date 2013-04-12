<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  get('/products')->

  with('request')->begin()->
    isParameter('module', 'products')->
    isParameter('action', 'index')->
  end()->
  info('Products listing results')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '/Reference/')->
    checkElement('table#listing tr.link', 6)->
  end()->
  info('Products filtered listings: only sold products')->
  post('/products', array('search'=>array('to'=>array(
                                                      'month'=>'5',
                                                      'day'=>'1',
                                                      'year'=>'2011'
                                                      )
                                          )))->
  with('response')->begin()->
    checkElement('table#listing tr.link',3)->
  end()->
  info('Products filtered listings: only until 2008')->
  post('/products', array('search'=>array('to'=>array(
                                                      'month'=>'1',
                                                      'day'=>'1',
                                                      'year'=>'2008'
                                                      )
                                          )))->
  with('response')->begin()->
    checkElement('table#listing tr.link',2)->
    checkElement('table#listing tr#product-2 td:nth-child(5)','/8/')->
    checkElement('table#listing tr#product-2 td:nth-child(6)','/1,656.64/')->
  end();
