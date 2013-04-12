<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new SiwappTestBrowser();

$browser->signin()->
  get('dashboard')->
  with('request')->begin()->
    isParameter('module', 'dashboard')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    // check totals
    checkElement('#dashboard-balance-total', '/262,149\.22/')-> 
    checkElement('#dashboard-balance-net', '/247,223\.88/')-> 
    checkElement('#dashboard-balance-taxes', '/14,925\.33/')->
    # IVA 16%
    checkElement('#dashboard-taxes td:nth-child(2)','/20,268\.28/')->
    # IVA 4%
    checkElement('#dashboard-taxes tr:nth-child(2) td:nth-child(2)','/1,900\.06/')->
    # IVA 7%
    checkElement('#dashboard-taxes tr:nth-child(3) td:nth-child(2)','/2,621\.06/')->
    # IRPF
    checkElement('#dashboard-taxes tr:nth-child(4) td:nth-child(2)','/9,864\.08/')->
    checkElement('#receipts', '/259,335\.25/')->                
    checkElement('#due', '/2,813\.97/')->                       
    checkElement('#overdue', '/637\.47/')->
  end()->
  // check payments
  get('/payments/form?invoice_id=23')->
  with('request')->begin()->
    isParameter('module', 'payments')->
    isParameter('action', 'form')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[class="amount"][value="12566.94"]', true)->
end()
;
