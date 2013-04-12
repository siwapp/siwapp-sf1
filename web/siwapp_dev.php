<?php
require_once('config.php');
// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once($options['sf_root_dir'].DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('siwapp', 'dev', true);
ProjectConfiguration::getActive()->setWebDir($options['sf_web_dir']);
sfContext::createInstance($configuration)->dispatch();
