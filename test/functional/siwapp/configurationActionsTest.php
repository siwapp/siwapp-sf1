<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();
$browser->
  signin()->
  info('>> CONFIGURATION: Global')->
  get('configuration/settings')->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'settings')->
  end()->
  with('response')->begin()->
    checkElement('h3:contains("Company")')->
  end()->
  info('Set an invalid email and send')->
  setField('config[company_email]', 'this is not an email')->
  click('Save')->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'settings')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul.error_list')->
    info('Set a valid email and send')->
    setField('config[company_email]', 'test@test.org')->
    click('Save')->
  end()->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'settings')->
    isMethod('post')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'settings')->
  end()->
  with('response')->
  begin()->
    isStatusCode(200)->
    checkElement('input[value*="test@test.org"]')->
  end()->
  
  
  info('>> CONFIGURATION: My settings (profile)')->
  get('configuration/profile')->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'profile')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#config_first_name')->
    info('Set first name')->
    setField('config[first_name]', 'The Test')->
    click('Save')->
  end()->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'profile')->
    isMethod('post')->
  end()->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'profile')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value*="The Test"]')->
    info('Trying to modify the sf_guard_user_id')->
    setField('config[config_sf_guard_user_id]', 3456)->
    click('Save')->
  end()->
  with('request')->begin()->
    isParameter('module', 'configuration')->
    isParameter('action', 'profile')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul.error_list')->
  end()
;