<?php
/**
 * These tests are only for the edition/batch actions of customers
 *
 **/
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

//save action
$browser->signin()->
  post('customers/batch',array('batch_action'=>'delete','ids'=>array(6)))->
  with('request')->begin()->
    isParameter('module','customers')->
    isParameter('action','batch')->
  end()->
  info('Testing deleting a customer with invoices')->
  with('response')->begin()->
      isRedirected()->
      followRedirect()->
  end();


$browser->
  info('Trying to insert customer with same name slug')->
  get('customers/new')->
  with('request')->begin()->
    isParameter('module','customers')->
    isParameter('action','new')->
  end()->
  click('Save', array('customer'=>array(
                                        'name'=>'Smi th andCo!',
                                        'email'=>'test@test.com'
                                        )))->
  with('request')->begin()->
    isParameter('module', 'customers')->
    isParameter('action', 'create')->
  end()->
  with('response')->begin()->
    matches('/Name too close/')->
  end();
