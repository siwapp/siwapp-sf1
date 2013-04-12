<?php
 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(8, new lime_output_color());

$t->diag("CustomerTable class tests");

$slugged = CustomerTable::slugify('Some Text with 5 SpACes');
$t->is($slugged, 'sometextwith5spaces', '->slugify(\'Some Text with 5 SpACes\')');

$slugged = CustomerTable::slugify('  Text with -ç-€-®-ñ-©- and , and_ & chars');
$t->is($slugged, 'textwithceurrncandand_chars', '->slugify(\'  Text with -ç-€-®-ñ-©- and , and_ & chars\')');

$t->diag("Checking with test data.");

$test = Doctrine::getTable('Customer')->matchName('  spring -- shi eld');
$t->is($test->getNameSlug(), 'springshield', '->matchName()');

$invoice = new Invoice();
$invoice->setCustomerName(' Sonky !rubber Goods');

$test = Doctrine::getTable('Customer')->getCustomerMatch($invoice);
$t->is($test->getIdentification(), '40487600161', '->getCustomerMatch() with name');


$invoice->setCustomerName(' Sonky !rubberaa Goods');
$invoice->setCustomerIdentification('40487600161');

$test = Doctrine::getTable('Customer')->getCustomerMatch($invoice);

$t->is($test->state(), Doctrine_Record::STATE_TCLEAN, '->getCustomerMatch() return new object if name doesn\'t match and identification matchs');

$invoice->setCustomerName(' Sonky !rubber Goods');
$invoice->setCustomerIdentification('GGD');

$test = Doctrine::getTable('Customer')->getCustomerMatch($invoice);

$t->is($test->state(), Doctrine_Record::STATE_CLEAN, '->getCustomerMatch() matchs if identification doesn\'t match and name matchs');
$t->is($test->getEmail(), 'olivia_mirren@example.com', 'and gets the right customer');

$test = Doctrine::getTable('Customer')->simpleRetrieveForSelect('Rouster and Sideways',2);

$t->is(count($test),1,'::simpleRetrieveForSelect');
