<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  get('/products')->
  info('Product creation')->
  click('New Product')->
  click('Save', array('product'=>array(
                                       'reference'=>'New reference',
                                       'description'=>'New description',
                                       'price'=>'33.3'
                                       )
                      ))->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'products')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the created product')->
  with('doctrine')->begin()->
    check('Product',array(
                          'description'=>'New description',
                          'reference'=>'New reference',
                          'price'=>'33.3'
                          ))->
  end()->
  info('Product modification')->
click('Save', array('product'=>array(
                                     'reference'=>'NEWMOD reference',
                                     'description'=>'NEWMOD description',
                                     'price'=>'99.9'
                                     )
                    ))->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'products')->
    isParameter('action', 'edit')->
  end()->
  info('Checking the updated product')->
  with('doctrine')->begin()->
    check('Product', array(
                           'description'=> 'NEWMOD description',
                           'reference'=> 'NEWMOD reference',
                           'price'=>'99.9'
                           ))->
    
  end()->
  info('Product deleting')->
  click('Delete')->
  with('response')->begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'products')->
    isParameter('action', 'index')->
  end()->
  info('Checking product was deleted')->
  with('doctrine')->begin()->
    check('Product', array(
                           'description'=> 'NEWMOD description',
                           'reference'=> 'NEWMOD reference',
                           'price'=>'99.9'
                           ),false)->
  end()
;

