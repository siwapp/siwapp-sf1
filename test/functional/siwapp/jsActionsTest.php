<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();
$browser
  ->signin()
  
  ->info("Check javascript i18n")
  ->get('/js/i18n')
  ->with('request')->begin()
    ->isParameter('module', 'js')
    ->isParameter('action', 'i18n')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->matches('/var i18n/')
  ->end()
  ->info("Check javascript urls")
  ->get('/js/url?key=invoices')
  ->with('request')->begin()
    ->isParameter('module', 'js')
    ->isParameter('action', 'url')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->matches('/var siwapp_urls/')
    ->matches('/addPayment/')
  ->end()
  ;