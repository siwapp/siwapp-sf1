<?php 

// this file can not ve viewed unless it's included from install.php or 
// install_dev.php
session_start();
if(!isset($included_in_installer))
{
  die('You\'re attempting to access this file the wrong way.');
}

if(isset($_REQUEST['sf_root_dir']))
{
  $_SESSION['sf_root_dir'] = $_REQUEST['sf_root_dir'];
}

$options['sf_root_dir'] = isset($_SESSION['sf_root_dir']) ? 
  $_SESSION['sf_root_dir'] : $options['sf_root_dir'];

$checks_results = array();

// if this is called out of the symfony framework, do some checkings
if(strpos($_SERVER['REQUEST_URI'],'/static/')===false &&
   strpos($_SERVER['REQUEST_URI'], '/step')===false)
{
  $checks_results = pre_checks();
}

if(in_array(true,array_values($checks_results)))
{
  $included_in_pre_installer = true;
  include_once($options['sf_web_dir'].DIRECTORY_SEPARATOR.
               'pre_installer_instructions.php');
  exit;
}


function pre_checks(){

  global $options;  
  $wrong = array();

  // check the php version
  $wrong['version'] =  !version_compare(PHP_VERSION, '5.2.4', '>=');
  // PDO dtabase access module
  $wrong['pdo'] = !class_exists('PDO');
  // PDO mysql driver
  $wrong['pdo_mysql'] = !in_array('mysql', array_map(
                                                     'strtolower',
                                                     PDO::getAvailableDrivers()
                                                     ));
  // access to config directory
  $wrong['config'] = !is_dir($options['sf_root_dir'].DIRECTORY_SEPARATOR.
                                'config');
  // cache write permissions
  $wrong['cache'] = !is_writable($options['sf_root_dir'].DIRECTORY_SEPARATOR.
                                 'cache');
  // check for mod_rewrite. test_rewrite1.txt should be rewrited to
  // test_rewrite2.txt
  $installer_url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
  $rewrite_url = substr($installer_url,0,strrpos($installer_url,'/')+1).
    'test_rewrite1.txt';
  $wrong['rewrite'] = strpos(file_get_contents($rewrite_url), 'test_rewrite2') 
    === false;

  return $wrong;
}

