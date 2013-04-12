<?php

include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(7, new lime_output_color());

include(dirname(__FILE__).'/../../testTools.php');

// begin testing
$t->comment('Property class test');

$p = new Property();

$p->hydrate(array('keey'=>'testKey', 'value'=>'{"a":1,"b":"geo"}'));

$t->comment('getRawValue()');

$t->is($p->getRawValue(), $p->rawGet('value'), 'getRawValue is really raw');

$t->comment('->getValue()');

$t->is($p->getValue(), array('a'=>1,'b'=>'geo'), 'json conversion works');
$p->hydrate(array('value'=>'{"a":1,"b":"g€e\'ñ"}'));
$t->is($p->getValue(), array('a'=>1,'b'=>"g€e'ñ"), 'json works with weird chars');

$p->hydrate(array('value'=>'{"abcd ñep\"ab\"":1}'));
$t->is($p->getValue(), array('abcd ñep"ab"'=>1), 'Special chars untouched');

$t->comment('->setValue()');

$arr = array('a'=>1,'b'=>2);

$p->setValue($arr);
$t->is($p->getValue(), $arr, 'json conversion works fine both ways');

$t->comment('test that changing the currency_decimals property changes the view');
PropertyTable::set('currency_decimals', 2);
$test_invoice->setAmounts();
$t->is($test_invoice->getGrossAmount(), 238.35, 'checking 2 decimals');

PropertyTable::set('currency_decimals', 3);
$test_invoice->setAmounts();
$t->is($test_invoice->getGrossAmount(), 238.354, 'checking 3 decimals');

PropertyTable::set('currency_decimals', 2);

