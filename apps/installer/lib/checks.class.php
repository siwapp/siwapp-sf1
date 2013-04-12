<?php

class Checks
{
  public static function getRequired()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    
    return array(
      array(version_compare(phpversion(), '5.2.4', '>='), 
        __('PHP version is at least 5.2.4'),
        __('Current PHP version is [1]', array('[1]' => phpversion()))
      ),
      array(!ini_get('zend.ze1_compatibility_mode'),
        __('php.ini has zend.ze1_compatibility_mode set to off'),
        __('Set zend.ze1_compatibility_mode to off in php.ini ([1])', array('[1]' => get_cfg_var('cfg_file_path')))
      ),
      array(class_exists('PDO'),
        __('PDO is installed'),
        __('Install PDO (mandatory for Propel and Doctrine)')
      ),
      array(class_exists('PDO') && count(PDO::getAvailableDrivers()),
        __('PDO has some drivers installed: [1]', array('[1]' => implode(', ', PDO::getAvailableDrivers()))),
        __('Install PDO drivers (mandatory for Propel and Doctrine)')
      ),
      array(class_exists('DomDocument'),
        __('PHP-XML module is installed'),
        __('Install the PHP-XML module (required by Propel)')
      ),
      array(function_exists('iconv'),
        __('The iconv() function is available'),
        __('Install iconv() function')
      ),
      array(function_exists('utf8_decode'),
        __('The utf8_decode() function is available'),
        __('Install utf8_decode() function')
      ),
      array(function_exists('mb_strlen'),
        __('The mb_strlen() function is available'),
        __('Install mb_strlen() function')
      )
    );
  }
  
  public static function getRecommended()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    
    return array(
      array(class_exists('XSLTProcessor'),
        __('XSL module is installed'),
        __('Install the XSL module (recommended for Propel)')
      ),
      array(function_exists('token_get_all'),
        __('The token_get_all() function is available'),
        __('Install token_get_all() function (highly recommended)')
      ),
      array((function_exists('apc_store') && ini_get('apc.enabled'))
        || (function_exists('eaccelerator_put') && ini_get('eaccelerator.enable'))
        || function_exists('xcache_set'),
        __('A PHP accelerator is installed'),
        __('Install a PHP accelerator like APC (highly recommended)')
      ),
      array(!ini_get('safe_mode'),
        __('php.ini has safe_mode set to off'),
        __('Set safe_mode to off in php.ini')
      ),
      array(!ini_get('short_open_tag'),
        __('php.ini has short_open_tag set to off'),
        __('Set short_open_tag to off in php.ini')
      ),
      array(!ini_get('magic_quotes_gpc'),
        __('php.ini has magic_quotes_gpc set to off'),
        __('Set magic_quotes_gpc to off in php.ini')
      ),
      array(!ini_get('register_globals'),
        __('php.ini has register_globals set to off'),
        __('Set register_globals to off in php.ini')
      ),
      array(!ini_get('session.auto_start'),
        __('php.ini has session.auto_start set to off'),
        __('Set session.auto_start to off in php.ini')
      )
    );
  }
  
  public static function getFilePerms()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    
    return array(
      array(is_writable(sfConfig::get('sf_upload_dir')),
        __('The %1% directory exists and is writable', array('%1%'=>'uploads')),
        __("Make sure the %1% directory is writable (%2%)", array('%1%'=>'uploads', '%2%'=>sfConfig::get('sf_upload_dir')))
      ),
      array(is_writable(sys_get_temp_dir()),
        __('The %1% directory exists and is writable', array('%1%'=>'temp')),
        __('Make sure the %1% directory is writable (%2%)',array('%1%'=>'temp', '%2%'=>sys_get_temp_dir()))
      ),
    );
  }
}