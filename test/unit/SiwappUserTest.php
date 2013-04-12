<?php
 
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

$t = new lime_test(9, new lime_output_color());

$dispatcher = new sfEventDispatcher();
$request = new sfWebRequest($dispatcher);
$guard_user = Doctrine::getTable('sfGuardUser')->findOneBy('Username', 'test');

$user = new SiwappUser($dispatcher, new sfSessionTestStorage(array('session_path' => '/tmp')));
$user->signin($guard_user);

$request->setParameter('searchNamespace', 'invoices');

$t->diag('->updateSearch()');
$user->updateSearch($request);

$request->setParameter('page', 2);
$user->updateSearch($request);
$t->is($user->getAttribute('page', null, 'invoices'), 2, '->updateSearch() sets the page to request page parameter');

$request->setParameter('search', array('query' => 'galaxy'));
$user->updateSearch($request);
$t->is($user->getAttribute('page', null, 'invoices'), 1, '->updateSearch() sets the page to 1 if the search changes');

$request->setParameter('page', 4);
$user->updateSearch($request);
$request->setParameter('sort', array('customer_name', 'asc'));
$user->updateSearch($request);
$t->is($user->getAttribute('page', null, 'invoices'), 1, '->updateSearch() sets the page to 1 if the sort changes');

$t->diag('Testing the default search filter settings');
$user->getProfile()->setSearchFilter('last_month');
$user->updateSearch($request);
$search = $user->getAttribute('search', null, 'invoices');
$today = new sfDate();
$t->is($search['to']['year'], $today->getYear(), 'sets right the to_year');
$t->is($search['to']['month'], $today->getMonth(), 'sets right the to_month');
$t->is($search['to']['day'], $today->getDay(), 'sets right the to_day');
$t->is($search['from']['year'], $today->subtractMonth(1)->getYear(), 'sets right the from_year');
$t->is($search['from']['month'], $today->getMonth(), 'sets right the from_month');
$t->is($search['from']['day'], $today->getDay(), 'sets right the from_day');
