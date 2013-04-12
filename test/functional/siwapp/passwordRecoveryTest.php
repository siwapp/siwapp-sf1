<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');
include(dirname(__FILE__).'/../../testTools.php');

$browser = new SiwappTestBrowser();

$browser->
  get('/login')->
  with('request')->begin()->
    isParameter('module','sfGuardAuth')->
    isParameter('action','signin')->
  end()->
  info('  1.1 - Testing sending the activation link email')->
  with('response')->begin()->
    checkElement('input[name="username_email"]')->
  end()->
  setHttpHeader('X_REQUESTED_WITH','XMLHttpRequest')->
  get('/password_recovery',array('username_email'=>'test'))->
  with('request')->begin()->
    isParameter('module', 'sfGuardAuth')->
    isParameter('action', 'passwordRecovery')->
  end()->
  info('  1.1 - Testing the received activation link email')->
  with('mailer')->begin()->
    info('Testing if the activation email has been sent')->
    hasSent()->
    checkBody('/http.*password_reset\/\w{32}\b/')->
  end();

$logger  = $browser->getContext()->getMailer()->getLogger();

if($logger->countMessages())
{
  $messages = $logger->getMessages();
 }
$message = $messages[0];

preg_match('/http.*password_reset\/[a-z,A-Z,0-9]{32}/',$message->getBody(),$matches);
$recovery_url = $matches[0];

$browser->
  info('  1.1 - Testing clicking on the activation link')->
  get($recovery_url)->
  with('request')->begin()->
    isParameter('module','sfGuardAuth')->
    isParameter('action','passwordReset')->
  end()->
  info('  1.1 - Testing the received password email')->
  with('mailer')->begin()->
    info('Testing if the password email has been sent')->
    hasSent()->
    checkBody('/Password:\s*\S{8}\b/')->
  end();

$logger = $browser->getContext()->getMailer()->getLogger();
if($logger->countMessages())
{
  $messages = $logger->getMessages();
 }
$message = $messages[0];

preg_match('/Password:\\s*(\\S{8})\\b/',$message->getBody(),$matches);
$password = $matches[1];

$browser->
  info('  1.1 - Signing in with the new password')->
  signin('test',$password)->
  with('request')->begin()->
    isParameter('module','dashboard')->
    isParameter('action','index')->
  end();
  
$user = Doctrine::getTable('sfGuardUser')->findOneBy('username','test');

$user->setPassword('test');

$user->save();

$browser->
  info('  1.1 - Logging out')->
  get('/logout');

$browser->
  info('  1.1 - Signing in with the old password')->
  signin();
