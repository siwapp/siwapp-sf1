<?php
require_once(dirname(__FILE__).'/../bootstrap/Selenium.php');
include(dirname(__FILE__).'/../bootstrap/Doctrine.php');

class Invoice extends SiwappSeleniumTest
{
  public function testAddItem()
  {

    $default_taxes  = count(Doctrine::getTable('Tax')->createQuery()
      ->where('Active', true)
      ->where('is_default',true));
    
    $this->login();

    $this->log("::testAddItem()");
    $this->open("invoices");
    $id = $this->getAttribute("//table[contains(@class,'listing')][1]/tbody/tr[td[6]/span[contains(@class,'opened')]]/td[1]/input/@value");

    $this->open("invoices/edit/$id");

    # test that fields are not filled with example data
    $client_name = $this->getValue("//input[@id='invoice_customer_name']");
    $this->assertEquals($this->getValue("//input[@id='invoice_customer_name']"),
                        'Sample, inc');
    $this->log("click on customer name input field");
    $this->click("//input[@id='invoice_customer_name']");
    $this->log("check that nothing changes on input field");
    $this->assertEquals($this->getValue("//input[@id='invoice_customer_name']"),
                        'Sample, inc');

    $number_of_rows_xpath = '//table[contains(@class,"listing")][1]/tbody/tr[not(contains(translate(@style," ",""),"display:none;"))]';
    $number_of_rows = $this->getXpathCount($number_of_rows_xpath);
    $this->log("add an item to the recurring invoice.", 'click');
    $this->click("css=table.listing div#addItem a");
    $ret = $this->waitForCondition('selenium.isElementPresent("//table[contains(@class,\"listing\")][1]/tbody/tr['.($number_of_rows +1).']/td[5]/span/span['.$default_taxes.']")');
    $number_of_rows = $this->getXpathCount($number_of_rows_xpath);
    $number_of_taxes = $this->getXpathCount('//table[contains(@class,"listing")][1]/tbody/tr['.$number_of_rows.']/td[5]/span//span');
    $this->log('Add a Tax to the newly created item','click');
    $this->click("css=table.listing  tr:last-child > td.taxes_td a");
    $ret = $this->waitForCondition('selenium.isElementPresent("//table[contains(@class,\"listing\")][1]/tbody/tr['.$number_of_rows.']/td[contains(@class,\"taxes_td\")]/span/span['.($number_of_taxes+1).']")');
    $new_number_of_taxes = $this->getXpathCount('//table/tbody/tr['.$number_of_rows.']/td[5]/span/span');
    $this->assertEquals($number_of_taxes + 1,$new_number_of_taxes);

    $this->log('Remove the recently created tax');
    $old_taxes = $new_number_of_taxes;
    $this->click("css=table.listing tr:last-child > td.taxes_td span.taxes span:last-child a");
    $test = $this->getXpathCount('//table/tbody/tr['.$number_of_rows.']/td[5]');
    $new_taxes = $this->getXpathCount('//table/tbody/tr['.$number_of_rows.']/td[5]/span/span');
    $this->assertEquals($old_taxes - 1 , $new_taxes);
    $this->log("before removing an item: $number_of_rows");
    $this->log('Remove an item of the recurring invoice','click');
    $this->click('//table[contains(@class,"listing")]//a[contains(@class,"remove-item")][1]');
    $new_number_of_rows = $this->getXpathCount($number_of_rows_xpath);
    $this->assertEquals($number_of_rows - 1 ,$new_number_of_rows);
    $this->log("The rows after removing should be 1 less");

    $this->log("::testHelpTexts");
    $this->log("opening a new invoice form");
    $this->open("invoices/new");
    $this->log("testing customer's name help text");
    $this->assertEquals($this->getValue("//input[@id='invoice_customer_name']"),
                        'Client Name');
    $this->log("clicking on input");
    $this->click("//input[@id='invoice_customer_name']");
    $this->log("testing the input field has been emptied");
    $this->assertEquals($this->getValue("//input[@id='invoice_customer_name']"),
                        "");

    
  }
}
?>