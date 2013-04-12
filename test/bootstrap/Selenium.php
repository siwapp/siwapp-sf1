<?php
// SEE /web/js/core/selenium-api.js for reference ($this->waitForCondition methods...)
// SEE /doc/selenium-commands.txt for PHPUnit_Extensions_SeleniumTestCase methods reference

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class SiwappSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase
{
  private
    $_appBaseUrl = "http://siwapp.test/siwapp_dev.php/",
    $_loggedIn   = false;
  
  protected function setUp()
  {
    $this->setBrowser('*firefox');
    $this->setBrowserUrl($this->getAppBaseUrl());
  }
  
  protected function getAppBaseUrl()
  {
    return $this->_appBaseUrl;
  }
  
  protected function setAppBaseUrl($v)
  {
    $this->_appBaseUrl = $v;
  }
  
  protected function login()
  {
    if (!$this->_loggedIn)
    {
      try
      {
        $this->open("login");
        $this->type("signin_username", "test");
        $this->type("signin_password", "test");
        $this->click("signin_remember");
        $this->clickAndWait("signin");

        $this->_loggedIn = true;
      }
      catch(Exception $e){
        throw $e;
      }
    }
  }
  
  protected function log($message = null, $prefix = null)
  {
    if (strlen($prefix))
    {
      $prefix = "$prefix: ";
    }
    if (strlen($message))
    {
      echo "$prefix$message\n";
    }
  }
}
?>