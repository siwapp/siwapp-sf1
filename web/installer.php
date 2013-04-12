<?php
require_once('config.php');
// check if already installed
if($sw_installed && !$_REQUEST['finished_install'] &&
   !strpos($_SERVER['REQUEST_URI'],'installer.php/upgrade'))
{
  $redirect = 'http://'.$_SERVER['HTTP_HOST']
    .substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], 'installer')-1).'/index.php';
  header("Location: " .$redirect);
  exit();
 }

$included_in_installer = true;
require_once('pre_installer_code.php');

require_once($options['sf_root_dir'].DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('installer', 'prod', false);
ProjectConfiguration::getActive()->setWebDir($options['sf_web_dir']);
sfContext::createInstance($configuration)->dispatch();
