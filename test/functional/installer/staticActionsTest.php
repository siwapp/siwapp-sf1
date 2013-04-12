<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

try
{
  // first drop database
  $this->runTask('doctrine:drop-db', '--no-confirmation');

  $database_file = sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'databases.yml';
  $database_file_content = file_get_contents($database_file);
  $config_file = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'config.php';
  $config_file_content = file_get_contents($config_file);

  $browser = new sfTestBrowser();
  $browser->setTester('doctrine', 'sfTesterDoctrine');

  $browser->
    info('Test the first page')->
    get('/')->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step1')->
    end()->
    info('Trying to jump steps redirect to my step')->
    get('/step3')->
    with('response')->begin()->
      isRedirected()->
    end()->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step1')->
    end()->
    setField('language', 'en')->
    click('next')->
    with('response')->begin()->
      isRedirected()->
    end()->
    // jump the requirements checks
    post('/step2')->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step3')->
    end()->
    click('next')->
    with('response')->begin()->
      isRedirected()->
    end()->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step4')->
    end()->
    setField('db[database]', 'siwapp_test')->
    setField('db[username]', 'siwapp')->
    setField('db[password]', 'wappis')->
    setField('db[host]', 'bbdd')->
    click('next')->
    with('response')->begin()->
      isRedirected()->
    end()->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step5')->
    end()->
    setField('config[admin_email]', 'e@test.org')->
    setField('config[admin_username]', 'test')->
    setField('config[admin_password', 'test')->
    setField('config[admin_password_bis]', 'test')->
    setField('config[preload]', true)->
    click('next')->
    with('response')->begin()->
      isRedirected()->
    end()->
    followRedirect()->
    with('request')->begin()->
      isParameter('module', 'static')->
      isParameter('action', 'step6')->
    end()->
    with('response')->begin()->
      info('Testing there were no sql errors')->
      checkElement('ul.error_list li:first-child', false)->
    end()
  ;

  $browser->
    info('Test the data inserted')->
    with('doctrine')->begin()->
      check('sfGuardUser', array(
        'username' => 'test',
        'is_super_admin' => 1
        ))->
      check('Profile', array(
        'email' => 'e@test.org',
        'language' => 'en'
        ))->
    end()
  ;
  // this is to revert always the files modified, because 
  // with errors there is no 'Finish' button
  $browser->
    info('Test the redirection to the application')->
    click('Finish')->
    with('response')->begin()->
      isRedirected()->
    end()
  ;
}
catch (Exception $e)
{
  echo $e->getMessage().PHP_EOL;
}

$browser->info('Reverting modified files');
@file_put_contents($database_file, $database_file_content);
@file_put_contents($config_file, $config_file_content);

$browser->info('Reverting cache and database');
$this->runTask('cc');
$this->runTask('siwapp:test-data-load', '--env=test');

