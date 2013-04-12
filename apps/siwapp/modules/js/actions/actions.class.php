<?php

/**
 * js actions.
 *
 * @package    siwapp
 * @subpackage js
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class jsActions extends sfActions
{
  public function preExecute()
  {
    sfConfig::set('sf_web_debug', false);
  }
  
  public function executeI18n(sfWebRequest $request)
  {
  }
  
  public function executeUrl(sfWebRequest $request)
  {
    sfProjectConfiguration::getActive()->loadHelpers('Url');
    
    $urls = array();
    if ($module = $request->getParameter('key'))
    {
      $urls = $this->loadUrls($module);
    }
    
    $this->urls = implode(",".PHP_EOL, $urls);
  }
  
  private function loadUrls($module, &$included = array())
  {
    $urls = array();
    $included[] = $module;
    $path = sfConfig::get('sf_app_module_dir')."/$module/config/module.yml";
    if (file_exists($path) && is_file($path))
    {
      $config = sfYaml::load($path);
      foreach ($config['all']['urls']['variables'] as $key => $value)
      {
        $urls[] = "  $key : '".url_for($value)."'";
      }
      if (array_key_exists('include', $config['all']['urls']))
      {
        foreach ((array) $config['all']['urls']['include'] as $module)
        {
          if (!in_array($module, $included))
          {
            $urls = array_merge($urls, $this->loadUrls($module, $included));
          }
        }
      }
    }
    
    return $urls;
  }
}
