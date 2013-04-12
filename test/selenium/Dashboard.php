<?php
require_once(dirname(__FILE__).'/../bootstrap/Selenium.php');

class Dashboard extends SiwappSeleniumTest
{
  public function testShowPayments()
  {
    $this->login();
    
    $this->log("::testShowPayments()");
    $this->open("dashboard");
    
    $this->log("show payment details for the most recent invoice (top table).", 'click');
    $tr_id = $this->getAttribute('//*/table[@class="listing"][1]/tbody/tr[1]/@id');
    $id = substr($tr_id,strrpos($tr_id,'-')+1);

    $this->click("load-payments-for-$id");
    $form_xpath = "//*/table[@class=\"listing\"][1]/tbody/tr[2]/td[1]/form[@class=\"payments-form\"]";
    $ret = $this->waitForCondition("selenium.isElementPresent('$form_xpath') == true");
    
    $this->log("Submit a new payment");
    
    $this->click("$form_xpath//a[contains(@class,'add-payment')]");

    $ret = $this->waitForCondition("selenium.isElementPresent('payments_new_1_amount') == true");
    $this->type("payments_new_1_amount", "33");
    $this->type("payments_new_1_notes", "test");
    $this->click("$form_xpath//button[@type='submit']");

    $this->waitForPageToLoad("30000");

    $this->click("load-payments-for-$id");
    $ret = $this->waitForCondition("selenium.isElementPresent('$form_xpath') == true");
    
    $pid = $this->getValue($form_xpath.'/ul/li[2]//input[contains(@name,"[id]")]');

    try {
      $this->assertEquals("33", $this->getValue("payments_old_".$pid."_amount"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
      array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertEquals("test", $this->getValue("payments_old_".$pid."_notes"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }

    $this->log("show payment details for the most recent overdue invoice (bottom table).", 'click');
    
    // Bottom Table
    $this->click("//*/table[@class=\"listing\"][2]/tbody/tr[1]/td[7]/button");
    $this->waitForCondition("selenium.isElementPresent('//*/table[@class=\"listing\"][2]/tbody/tr[2]/td[1]/form[@class=\"payments-form\"]') == true");
  }
}
?>