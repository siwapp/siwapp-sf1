<?php
/**
 * These tests are only for the edition of estimates
 *
 **/
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

//save action
$browser->signin()->
  get('estimates')->
  with('request')->begin()->
    isParameter('module','estimates')->
    isParameter('action','index')->
  end()->
  info('Testing creating a new Estimate')->
  with('response')->begin()->
    checkElement('a#new-invoice-button')->
  end()->
  click('New Estimate',array())->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'new')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('Save', array('invoice' => $fake_estimate_array))->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'create')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the changed value')->
  with('doctrine')->begin()->
    check('Estimate', array(
      'customer_name' => $fake_estimate_array['customer_name'],
      'due_date'      => $fake_estimate_array['due_date']
      ))->
    check('Item', array(
      'description'  => $fake_estimate_array['Items'][0]['description'],
      'unitary_cost' => $fake_estimate_array['Items'][0]['unitary_cost']
      ))->
  end()->
  info('Checking the created Customer')->
  with('doctrine')->begin()->
  check('Customer', array(
                        'name' => $fake_estimate_array['customer_name'],
                        'email'=> $fake_estimate_array['customer_email']
                        ))->
end();

$estimate = Doctrine::getTable('Common')->findOneBy('customer_name', $fake_estimate_array['customer_name']);

// change some value to edit:
$fake_estimate_array['issue_date'] = '2011-01-01';
$fake_estimate_array['terms'] = 'new test terms';
$fake_estimate_array['customer_email'] = 'mod@test.com';

//edit action
$browser->
  info('Testing editing an Estimate')->
  get('/estimates/edit/'.$estimate->id)->
  with('request')->begin()->
    isParameter('module','estimates')->
    isParameter('action','edit')->
  end()->
  click('Save', array('invoice' => $fake_estimate_array))->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'update')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the changed value in the database')->
  with('doctrine')->begin()->
    check('Estimate', array(
                      'id'        => $estimate->id,
                      'issue_date'  => $fake_estimate_array['issue_date'],
                      'terms'     => $fake_estimate_array['terms']
                      ))->
  end()->
  info('Checking the modified customer values in the estimate doesnt affect customer')->
  with('doctrine')->
    begin()->
      check('Customer',array(
                             'name'=>$fake_estimate_array['customer_name'],
                             'email'=>$fake_estimate_array['customer_email']
                             ),false)->
end();


//delete
$browser->
  info('Estimate Deleting')->
  call('/estimates/delete', 'POST', array('id' => $estimate->id))->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'delete')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'estimates')->
    isParameter('action', 'index')->
  end()->
  with('doctrine')->begin()->
    check('Estimate', array('id' => $estimate->id), false)->
  end()
;
