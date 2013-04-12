<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  get('settings/templates')->
  with('request')->begin()->
    isParameter('module', 'printTemplates')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    checkElement('td:contains("Invoice Template")')->
  end()->
  
  get('settings/templates/edit?id=1')->with('request')->begin()->
    isParameter('module', 'printTemplates')->
    isParameter('action', 'edit')->
  end()->
  with('response')->begin()->
    checkElement('input[value="Invoice Template"]')->
    checkElement('textarea')->
  end()->
  
  setField('template[name]', 'testing')->
  click('Save')->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('response')->begin()->
    checkElement('input[value="testing"]')->
    checkElement('textarea')->
  end()->
  
  // reverting the value
  setField('template[name]', 'Invoice Template')->
  click('Save');  
  
  
  
