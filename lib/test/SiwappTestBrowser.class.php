<?php
class SiwappTestBrowser extends sfTestBrowser
{
  public function signin($username = 'test', $password = 'test')
  {
    $signin = array('username' => $username, 'password' => $password);
    
    // adding the doctrine tester
    $this->setTester('doctrine', 'sfTesterDoctrine');
    
    return $this->
      get('/login')->
      info(sprintf('Signin user using username "%s" and password "%s"', $username, $password))->
      click('signin', array('signin' => $signin))->
      with('response')->begin()->
        isRedirected()->
      end()->
      followRedirect()
    ;
  }

}

