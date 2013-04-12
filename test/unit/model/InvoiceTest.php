<?php
 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(31, new lime_output_color());

include(dirname(__FILE__).'/../../testTools.php');

// checks before save
$t->diag('Totals checks');
$t->is(count($test_invoice->getItems()), 3, 'we have created an Invoice which contains 3 items');
$t->is($test_invoice->getBaseAmount(), 224.98, 'getBaseAmount()');
$t->is($test_invoice->getDiscountAmount(), 4.0895, 'getDiscountAmount()');
$t->is($test_invoice->getNetAmount(), 220.8905, 'getNetAmount()');
$t->is($test_invoice->getTaxAmount(), 17.463428, 'getTaxAmount()');
$t->is($test_invoice->getGrossAmount(), 238.35, 'getGrossAmount()');

$test_invoice->save();

// test tax_amount_<TAX_NAME> property
$invoice = Doctrine::getTable('Invoice')->find(23);
$t->is($invoice->tax_amount_iva16, 753.44, 
       '->tax_amount_iva16 == 753.44');
// checks post save
$t->diag("Testing The Extended Invoice");
$einvoice = Doctrine::getTable('Invoice')->find($test_invoice->id);
$t->is($einvoice->getBaseAmount(), 224.98, 'getBase() == 224.98');
$t->is($einvoice->getDiscountAmount(), 4.0895, 'getDiscount() == 4.0895');
$t->is($einvoice->getNetAmount(), 220.8905, 'getNet() == 220.8905');
$t->is($einvoice->getTaxAmount(), 17.463428, 'getTax() == 17.463428');
$t->is($einvoice->getGrossAmount(), 238.35, 'getGross() == 238.35');

// deleting
$invoiceItem2->delete();
$test_invoice->refresh(true)->setAmounts();
$t->diag("after deleting Item 2 of the Invoice:");
$t->is(count($test_invoice->getItems()), 2, 'the Invoice now have  2 items');
$t->is($test_invoice->getNetAmount(), 135.4737, 'getNetAmount()');

// modifying
$t->diag("after making quantity of Item3 = 2:");
$invoiceItem3->setQuantity(2);
$invoiceItem3->save();
$test_invoice->refresh(true)->setAmounts();
$t->is($test_invoice->getNetAmount(), 96.6437, 'getNetAmount()');

$test_invoice->delete();

// checking the test data

$t->diag('checking test data for Customer "Smith and Co."');
$invoice = Doctrine::getTable('Invoice')->findOneBy('CustomerName', 'Smith and Co.');
$customer_id = $invoice->customer_id;
// test if values on bbdd are ok
$t->is($invoice->getBaseAmount(), 7198.85, 'getBase()');
$t->is($invoice->getDiscountAmount(), 0, 'getDiscount()');
$t->is($invoice->getNetAmount(), 7198.85, 'getNet()');
$t->is($invoice->getTaxAmount(), 1034.7995, 'getTax()');
$t->is($invoice->getGrossAmount(), 8233.65, 'getGross()');
$t->is($invoice->getPaidAmount(), 8610.68, 'getPaid()');

// checks number generation
$t->is(Doctrine::getTable('Invoice')->getNextNumber(1), 9, 'getNextNumber of "ASET-" invoices will be 9');
$t->is(Doctrine::getTable('Invoice')->getNextNumber(2), 5, 'getNextNumber of "BSET-" invoices will be 5');
$t->is(Doctrine::getTable('Invoice')->getNextNumber(3), 6, 'getNextNumber of "CSET-" invoices will be 6');
// checks that when changing series, the number changes

$invoice->setSeriesId('1');
$invoice->save();
$t->is($invoice->number,9,'When changing number series, the inv number changes');
$invoice->setSeriesId('2');
$invoice->save();
$t->is($invoice->number, 5, 'When changing back it gets the next maximun number available');

// checks savings with modified customer data
$t->diag('customers mod available: checking that moddified cust data does not alter customer object itself');
$invoice = Doctrine::getTable('Invoice')->findOneBy('CustomerName', 'Smith and Co.');
$invoice->customer_email = 'test@testmail.com';
$invoice->save();
$t->is($invoice->customer_id, $customer_id, "customer id not changed");
$c = Doctrine::getTable('Customer')->findOneById($customer_id);
$t->is(
       $c->name.$c->email,
       'Smith and Co.jody_nichols@example.com',
       'Customer object is not modified'
       );
$t->diag('customers mod not available: checking that moddified cust data in the invoice alter customer object itself');
PropertyTable::set('siwapp_modules',array('products','estimates'));
$invoice->customer_email = 'test2@testmail.com';
$invoice->save();
$c = Doctrine::getTable('Customer')->findOneById($customer_id);
$t->is(
       $c->name.$c->email,
       'Smith and Co.test2@testmail.com',
       'Customer object is modified'
       );
PropertyTable::set('siwapp_modules', array('customers','products','estimates'));

$t->diag('checking that changing customer name changes customer');
$invoice->customer_name = 'Rouster and Sideways';
$invoice->save();
$c = Doctrine::getTable('Customer')->findOneById($invoice->customer_id);
$t->is(
       $c->name_slug, 
       CustomerTable::slugify($invoice->customer_name), 
       'A different customer object is associated'
       );
$t->diag('checking that db-new customer name creates a new customer');
$invoice->customer_name = 'New Rouster and Sideways';
$invoice->save();
$c = Doctrine::getTable('Customer')->findOneByNameSlug(CustomerTable::slugify($invoice->customer_name));
$t->is($c->name, $invoice->customer_name, 'New Customer created');



