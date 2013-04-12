<?php

include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
include(dirname(__FILE__).'/../../testTools.php');

$t = new lime_test(2, new lime_output_color());

PropertyTable::set('currency_decimals', 3);

$p = new Payment();
$p->setAmount(2.1215);

$t->is($p->getAmount(), 2.122, 'rounds amount to 3 decimals');

PropertyTable::set('currency_decimals', 2);
$p->setAmount(2.123);

$t->is($p->getAmount(), 2.12, 'rounds amount to 2 decimals');
