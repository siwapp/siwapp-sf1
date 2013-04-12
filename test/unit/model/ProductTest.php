<?php
 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(8, new lime_output_color());

$t->diag("ProductTable class tests");

$prodTable = ProductTable::getInstance();

$prod_desc_price = $prodTable->retrieveDescAndPrice(1);
$t->is($prod_desc_price, array('test product 1 description','179.54'), '->retrieveDescAndPrice(1)');

$t->is($prodTable->getReference(1),'test product 1','->getReference(1)');
// the way items are obtained , only relevant values are adquired
$item1 = array(
               'id'=>'1','quantity'=>7,'unitary_cost'=>'179.54',
               'discount'=>null,'common_id'=>null,'product_id'=>null,'description'=>null,
               'unitary_cost'=>179.54);
$res = $prodTable->getInvoicedItems(1)->toArray();
$res = $res[0];
$t->is(array_diff_assoc($item1,$res),array(),'->getInvoicedItems(1)');
$date_range = array('from'=>array('day'=>11,'month'=>8,'year'=>2007));

$res = $prodTable->getInvoicedItems(1,$date_range)->toArray();
$res = $res[0];

$t->is(array_diff_assoc($item1,$res),array(),'->getInvoicedItems(1,2007-8-11)');

$date_range['from']['day'] = 12;

$t->is(
       $prodTable->getInvoicedItems(1,$date_range)->toArray(),
       array(),
       '->getInvoicedItems(1,2007-8-12)');


$t->diag("Product class tests");

$prod = ProductTable::getInstance()->findOneById(1);

$t->is($prod->getInvoicedQuantity(),7,'->getInvoicedQuantity()');
$t->is($prod->getInvoicedSold(),1256.78,'->getInvoicedSold()');

$t->is($prod->getInvoicedQuantity(
                                  array(
                                        'from'=>array(
                                                      'month'=>1,
                                                      'day'=>1,
                                                      'year'=>2008
                                                      )
                                        )
                                  ),
       0,
       '->getInvoicedQuantity(2008-01-01)'
       );
