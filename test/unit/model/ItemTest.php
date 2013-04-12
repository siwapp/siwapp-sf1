<?php
 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(6, new lime_output_color());
 
$t->diag('Item class tests');

$item = new Item();
$item->setUnitaryCost(1234.214);
$item->setQuantity(3);
$item->setDiscount(13);

$tax1 = new Tax();
$tax2 = new Tax();
$tax1->setValue(16);
$tax2->setValue(4);
$item->Taxes[] = $tax1;
$item->Taxes[] = $tax2;
$base = 1234.214 * 3;
$discount = $base * 13/100;
$taxAmount = ($base - $discount) * ($tax1->value + $tax2->value) / 100;

$t->is($item->getBaseAmount(), $base, 'getBaseAmount()');
$t->is($item->getNetAmount(), $base - $discount, 'getNetAmount()');
$t->is($item->getDiscountAmount(), $discount, 'getDiscountAmount()');
$t->is($item->getTaxAmount(), $taxAmount, 'getTaxAmount()');
$t->is($item->getGrossAmount(), $base - $discount + $taxAmount, 'getGrossAmount()');
$t->is($item->getTaxesPercent(), $tax1->value + $tax2->value, 'getTaxesPercent()');
