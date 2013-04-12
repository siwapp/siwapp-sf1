<?php //dummy
$tax1 = Doctrine::getTable('Tax')->find(1);
$tax2 = Doctrine::getTable('Tax')->find(2);

$fake_item_1 = array(
  'description'   => 'test item 1',
  'unitary_cost' => '21.33',
  'quantity'      => '1',
  'discount'      => '11',
  'taxes_list'    => array($tax1->id, $tax2->id)
  );
$fake_item_2 = array(
  'description'   => 'test item 2',
  'unitary_cost' => '43.58',
  'quantity'      => '2',
  'discount'      => '2',
  'taxes_list'    => array($tax1->id)
  );
$fake_item_3 = array(
  'description'   => 'test item 3',
  'unitary_cost' => '38.83',
  'quantity'      => '3',
  'discount'      => '0'
  );

$fake_invoice_array = array (
  'draft' => '0',
  'customer_name' => 'test customer',
  'customer_identification' => '11test22',
  'contact_person' => 'Jody Nichols',
  'customer_email' => 'jody_nichols@example.com',
  'invoicing_address' => 'Test Invoicing Address',
  'shipping_address' => 'Test Shipping Address',
  'series_id' => 1,
  'issue_date' => '2009-09-06',
  'due_date' => '2009-10-06',
  'terms'    => 'test terms',
  'notes'    => 'test notes',
  'tags'     => 'commodo, do, exercitation, sit,velit',
  'Items'    => array($fake_item_1, $fake_item_2, $fake_item_3)
  );
  
$fake_estimate_array = array (
  'draft' => '0',
  'customer_name' => 'test customer',
  'customer_identification' => '11test22',
  'contact_person' => 'Jody Nichols',
  'customer_email' => 'jody_nichols@example.com',
  'invoicing_address' => 'Test Invoicing Address',
  'shipping_address' => 'Test Shipping Address',
  'series_id' => 1,
  'issue_date' => '2009-09-06',
  'terms'    => 'test terms',
  'notes'    => 'test notes',
  'tags'     => 'commodo, do, exercitation, sit,velit',
  'Items'    => array($fake_item_1, $fake_item_2, $fake_item_3)
  );


$fake_recurring_array = $fake_invoice_array;
unset(
  $fake_recurring_array['issue_date'],
  $fake_recurring_array['due_date'],
  $fake_recurring_array['draft']
);
$fake_recurring_array['starting_date']         = '2010-10-10';
$fake_recurring_array['enabled']               = '1';
$fake_recurring_array['max_occurrences']       = '90';
$fake_recurring_array['period']                = '9';
$fake_recurring_array['period_type']           = 'day';


// another test invoice 
$test_invoice = new Invoice();
$invoiceItem1 = new Item();
$invoiceItem2 = new Item();
$invoiceItem3 = new Item();
$invoiceItem1->fromArray($fake_item_1);
$invoiceItem2->fromArray($fake_item_2);
$invoiceItem3->fromArray($fake_item_3);
$invoiceItem1->Taxes[] = $tax1;
$invoiceItem1->Taxes[] = $tax2;
$invoiceItem2->Taxes[] = $tax1;

$test_invoice->Items[] = $invoiceItem1;
$test_invoice->Items[] = $invoiceItem2;
$test_invoice->Items[] = $invoiceItem3;
$test_invoice->setCustomerName('fake customer');
$test_invoice->setAmounts();
