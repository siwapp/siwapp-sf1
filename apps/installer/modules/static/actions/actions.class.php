<?php

/**
 * static actions.
 *
 * @package    siwapp
 * @subpackage static
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class staticActions extends sfActions
{

  public function preExecute()
  {
    if ($sfRootDirParameter = $this->getRequestParameter('sf_root_dir'))
    {
      $sfRootDirParameter = "?sf_root_dir=$sfRootDirParameter";
    }
    
    $step = substr($this->getActionName(), -1);
    
    // if the user has not reached the step redirect him
    if($step > $this->getUser()->getAttribute('step', 1))
    {
      $this->redirect("@step".$this->getUser()->getAttribute('step', 1).$sfRootDirParameter);
    }
    
    $prev = $step - 1;
    $next = $step + 1;
    
    $this->thisPage = "@step".$step.$sfRootDirParameter;
    $this->prev = "@step".$prev.$sfRootDirParameter;
    $this->next = "@step".$next.$sfRootDirParameter;
    $this->step = $step;
    
    $this->db_file     = sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'databases.yml';
    $this->config_file = sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'config.php';
  }
  
  
  /**
   * Step #1: Choose language and country
   *
   * If the language has country alternatives in the i18n dir, like es_ES/es_AR then
   * the user can select country option also
   *
   * @param $request
   */
  public function executeStep1(sfWebRequest $request)
  {
    $this->preferred_language  = CultureTools::getPreferredLanguage($request);
    $this->getUser()->setAttribute('step', 1);
    
    if(!$this->getUser()->getAttribute('language'))
    {
      $this->getUser()->setCulture($this->preferred_language);
    }
    
    if ($request->isMethod('post'))
    {
      $this->getUser()->setAttribute('language', $this->getRequestParameter('language'));
      $this->getUser()->setAttribute('country', $this->getRequestParameter('country'));
      
      $culture = $this->getRequestParameter('language');
      if($this->getRequestParameter('country'))
      {
        $culture .= '_'.$this->getRequestParameter('country');
      }
      $this->getUser()->setCulture($culture);
      $this->getUser()->setAttribute('step', 2);
      
      $this->redirect($this->next);
    }
  }
  
  /**
   * ajax function to get a select with the countries available with language
   *
   **/
  public function executeAjaxGetCountries(sfWebRequest $request)
  {
    $this->preferred_country = CultureTools::getPreferredCountry($request);
    $this->lang = $this->getRequestParameter('language');
    
    if (CultureTools::getCountriesForLanguage($this->lang))
    {
      return sfView::SUCCESS;
    }
    else
    {
      return sfView::NONE;
    }
  }
  
  /**
   * Step #2: Pre-Installation Check
   * @param $request
   */
  public function executeStep2(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      // if there is a blocking error return to the same page
      if($this->getRequestParameter('error'))
      {
        return sfView::SUCCESS;
      }
      $this->getUser()->setAttribute('step', 3);
      $this->redirect($this->next);
    }
    $this->checks_required = Checks::getRequired();
    $this->checks_recommended = Checks::getRecommended();
    $this->checks_fileperms = Checks::getFilePerms();
  }
  
  /**
   * Step #3: License
   * @param $request
   */
  public function executeStep3(sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      $this->getUser()->setAttribute('step', 4);
      $this->redirect($this->next);
    }
  }
  
  /**
   * Step #4: Database
   * The validator of this form checks database connection
   * and creates the database if it doesn\'t exists
   * See lib/validator/dbConnectionValidator.class.php
   *
   * @param $request
   */
  public function executeStep4(sfWebRequest $request)
  {
    $this->form = new DatabaseConfigurationForm();
    
    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('db');
      $this->form->bind($params);
      if($this->form->isValid())
      {
        $u = $this->getUser();
        $u->setAttribute('database', $params['database']);
        $u->setAttribute('username', $params['username']);
        $u->setAttribute('password', $params['password']);
        $u->setAttribute('host', $params['host']);
        $u->setAttribute('step', 5);
        
        $this->redirect($this->next);
      }
    }
  }
  
  /**
   * Step #5: Configuration
   * @param $request
   */
  public function executeStep5(sfWebRequest $request)
  {
    $u = $this->getUser();
    
    $this->form = new MainConfigurationForm(array(
      'admin_email'        => $u->getAttribute('admin_email'),
      'admin_username'     => $u->getAttribute('admin_username'),
      'preload'            => $u->getAttribute('preload', false)
    ));
    
    if ($request->isMethod('post'))
    {
      $params = $request->getParameter('config');
      $this->form->bind($params);
      if($this->form->isValid())
      {
        $u->setAttribute('admin_email', $params['admin_email']);
        $u->setAttribute('admin_username', $params['admin_username']);
        $u->setAttribute('admin_password', $params['admin_password']);
        $u->setAttribute('preload', isset($params['preload']) ? true : false);
        $u->setAttribute('step', 6);
        
        $this->redirect($this->next);
      }
    }
  }
  
  /**
   * Step #6: Finish
   * @param $request
   */
  public function executeStep6(sfWebRequest $request)
  {
    $user = $this->getUser();
    $this->messages  = array(); // array to save error messages
    $this->downloads = array(); // array to save the downloads
    $this->warnings  = array(); // array to save the "remove permissions!" warnings

    if ($request->isMethod('post'))
    {
      $dbhost     = $user->getAttribute('host');
      $dbname     = $user->getAttribute('database');
      $dbusername = $user->getAttribute('username');
      $dbpassword = $user->getAttribute('password');
      
      $this->redirectIf($this->checkConfigFiles(), 'http://'.$_SERVER['HTTP_HOST']
        .substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], 'installer')-1).'/index.php');
    }
    else
    {
      /*
        1. write databases.yml     >> WARNING: download
        2. write config.php        >> WARNING: download
        3. connect to the database >> ERROR: bad parameters
        4. insert the model schema >> ERROR: sql errors
        5. insert data             >> ERROR: sql errors
      */
      
      if (!$this->writeDatabaseConfig())
      {
        $this->downloads['database'] = $this->getDownloadArray('database');
      }
      else if (is_writable($tmp = $this->db_file))
      {
        $this->warnings[] = $tmp;
      }
      
      if(!$this->writeConfig())
      {
        $this->downloads['config'] = $this->getDownloadArray('config');
      }
      else if (is_writable($tmp = $this->config_file))
      {
        $this->warnings[] = $tmp;
      }
      
      if (!$link = $this->getDatabaseConnection())
      {
        $this->messages[] = $this->getContext()->getI18N()->__("Can't connect to the database. Please review the data.");
        return sfView::ERROR;
      }
      
      foreach ($this->getDataQuery() as $query)
      {
        // skip the delimiter
        if(trim(strtolower($query)) == 'delimiter') continue;
        $res = @ mysql_query($query, $link);
        if(!$res) $this->messages[] = mysql_error().' :: '.$query;
      }
      
      if ($this->messages)
      {
        array_unshift($this->messages, $this->getContext()->getI18N()->__("There were some sql errors creating the database."));
        return sfView::ERROR;
      }
    }
  }
  
  
  /**
   * undocumented function
   *
   * @return void
   **/
  public function executeDownload(sfWebRequest $request)
  {
    $user     = $this->getUser();
    $response = $this->getResponse();
    $response->clearHttpHeaders();
    switch($request->getParameter('file'))
    {
      case 'databases':
        $response->setContentType('text/yml');
        $response->setHttpHeader('Content-Disposition','attachment;filename=databases.yml');
        header('Content-Disposition: attachment;filename=databases.yml');
        $this->renderText($this->generateDatabaseConfig());
        break;
      case 'config':
        $response->setContentType('application/x-php');
        $response->setHttpHeader('Content-Disposition','attachment;filename=config.php');
        $this->renderText($this->generateConfig());
        break;
    }

    return sfView::NONE;
  }
  
  /**
   * returns an array with information of the file to download
   *
   * @return array
   **/
  private function getDownloadArray($type)
  {
    $web_folder = substr(sfConfig::get('sf_web_dir'),
      strlen(realpath(sfConfig::get('sf_web_dir').DIRECTORY_SEPARATOR.'..'))+1);
      
    switch($type)
    {
      case 'database':
        $download = array(
          'short_file'  => 'config'.DIRECTORY_SEPARATOR.'databases.yml',
          'url'         => $this->generateUrl('download_database_file',
            array('sf_root_dir' => $this->getRequestParameter('sf_root_dir'), 'finished_install' => 1)),
          'file'        => $this->db_file);
        break;
      case 'config':
        $download = array(
          'short_file'  => $web_folder.DIRECTORY_SEPARATOR.'config.php',
          'url'         => $this->generateUrl('download_config_file',
            array('sf_root_dir' => $this->getRequestParameter('sf_root_dir'), 'finished_install' => 1)),
          'file'        => $this->config_file);
        break;
    }
    
    return $download;
  }
  
  /**
   * checks that both configuration files are ok (exists, readable, and with the right data)
   *
   * @return Boolean    true, if both ok
   **/
  private function checkConfigFiles()
  {
    $cond1 = $this->checkApplicationConfigFile();
    $cond2 = $this->checkDatabaseConfigFile();
    
    if ($cond1 && $cond2)
    {
      return true;
    }
    
    if (!$cond1)
    {
      $this->downloads['config']   = $this->getDownloadArray('config');
    }
    if (!$cond2)
    {
      $this->downloads['database'] = $this->getDownloadArray('database');
    }
    
    return false;
  }
  
  /**
   * returns link to the database
   *
   * @return mixed
   **/
  private function getDatabaseConnection()
  {
    $u = $this->getUser();
    $link   = @ mysql_connect($u->getAttribute('host'), $u->getAttribute('username'), $u->getAttribute('password'));
    $opened = @ mysql_select_db($u->getAttribute('database'));
    
    return ($link && $opened) ? $link : false;
  }
  
  /**
   * Returns an array of sql queries, containing schema.sql,
   * and properties defined
   *
   * @return array of sql queries
   * @author Carlos Escribano <carlos@markhaus.com>
   **/
  private function getDataQuery()
  {
    $dir = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'sql';
    $sql = explode(';', file_get_contents($dir.DIRECTORY_SEPARATOR.'schema.sql'), -1);
    $sql[] = file_get_contents($dir.DIRECTORY_SEPARATOR.'migration.schema.sql');
    
    if ($this->getUser()->getAttribute('preload'))
    {
      $sql[] = "INSERT INTO property VALUES ('sample_data_load', '1')";
    }
    // if no data preload, insert a default invoice serie
    else
    {
      $sql[] = "INSERT INTO series(name, value, first_number, enabled) VALUES ('Default', '', '1', '1')";
    }
    
    $sql[] = $this->getGuardUserQuery();
    $sql[] = $this->getProfileQuery();
    $sql = array_merge($sql, $this->getDefaultTemplateQuery());
    $sql[] = $this->getMigrationVersionQuery();
    // we add  "drop table if exists" statements in case there is already a db with tables
    $nsql = array();
    $nsql[] = "SET foreign_key_checks = 0";

    foreach($sql as $sq)
    {
      $result = preg_replace('/(create\s+table\s+)(\S+)/i','DROP TABLE IF EXISTS \\2 CASCADE %%;%%\\1\\2',$sq);
      $temp_array = explode('%%;%%',$result);
      $nsql = array_merge($nsql,$temp_array);
    }
    $nsql[] = "SET foreign_key_checks = 1";
    return $nsql;
  }
  
  /**
   * returns the sql that inserts the super_admin user
   *
   * @return string
   **/
  private function getGuardUserQuery()
  {
    $u = $this->getUser();
    $name = $u->getAttribute('admin_username');
    $salt = md5(rand(100000, 999999) . $name);
    $pass = sha1($salt . $u->getAttribute('admin_password'));
    
    return "INSERT INTO sf_guard_user (id, username,algorithm,salt,password,is_active,is_super_admin,created_at,updated_at) VALUES (1, '$name', 'sha1', '$salt', '$pass', 1, 1,now(),now())";
  }
  
  /**
   * returns the sql query that inserts the profile data of the super_admin user.
   *
   * @return string The sql
   **/
  private function getProfileQuery()
  {
    $u = $this->getUser();
    
    return sprintf("INSERT INTO sf_guard_user_profile (id, sf_guard_user_id, email, language, country, search_filter) "
      ."VALUES (1, 1, '%s', '%s', '%s', '%s')",
      $u->getAttribute('admin_email'),
      $u->getAttribute('language'),
      $u->getAttribute('country'),
      $this->getUser()->getAttribute('preload') ? '' : 'last_month'
      );
  }
  
  /**
   * Load templates in <root_dir>/data/fixtures/templates.yml
   *
   * @return string
   * @author Carlos
   **/
  private function getDefaultTemplateQuery()
  {
    // mysql_real_escape_string requires a previously opened connection
    $sql  = array();
    $data = sfYaml::load(sfConfig::get('sf_data_dir').'/fixtures/templates.yml');
    $data = $data['Template'];

    if (count($data))
    {
      foreach ($data as $tdata)
      {
        $name = $tdata['name'];
        $slug = $tdata['slug'];
        $mod = $tdata['models'];
        $temp = $tdata['template'];
        
        if (get_magic_quotes_gpc())
        {
          $name = stripslashes($name);
          $slug = stripslashes($slug);
          $temp = stripslashes($temp);
          $mod = stripslashes($mod);
        }

        $name = mysql_real_escape_string($name);
        $slug = mysql_real_escape_string($slug);
        $temp = mysql_real_escape_string($temp);
        $mod = mysql_real_escape_string($mod);

        $sql[] = "INSERT INTO template (name, slug, models, template, created_at, updated_at)"
          ." VALUES ('$name', '$slug', '$mod', '$temp', now(), now())";
      }
      
      return $sql;
    }
    
    return array();
  }

  /**
   * Get the migration version of this release, and insert it into the database
   *
   * @return string
   * @author JoeZ99 <jzarate@gmail.com>
   **/
  private function getMigrationVersionQuery()
  {
    $migration = new Doctrine_Migration(sfConfig::get('sf_lib_dir').'/migration/doctrine');
    $sql[] = "INSERT INTO migration_version VALUES (".$migration->getLatestVersion().")";
    return implode("; ",$sql).";";
  }
  
  /**
   * renders _databases_yml partial with the data given by the user
   *
   * @return string
   **/
  private function generateDatabaseConfig()
  {
    $user = $this->getUser();
    
    $this->host     = $user->getAttribute('host');
    $this->database = $user->getAttribute('database');
    $this->username = $user->getAttribute('username');
    $this->password = $user->getAttribute('password');
    
    return $this->getPartial('static/databases_yml');
  }
  
  /**
   * writes the databases.yml configuration file
   *
   * @return mixed
   **/
  private function writeDatabaseConfig()
  {
    return @file_put_contents($this->db_file, $this->generateDatabaseConfig());
  }
  
  /**
   * renders _config_php partial with the sf_root_dir
   * specified by the user, or the default one
   *
   * @return string
   **/
  private function generateConfig()
  {
    $sf_root_dir = $this->getRequestParameter('sf_root_dir');
    
    $this->sf_root_dir = $sf_root_dir ?
      "'".$sf_root_dir."'" :
      '$options[\'sf_web_dir\'].DIRECTORY_SEPARATOR.\'..\'';
      
    return $this->getPartial('static/config_php');
  }
  
  /**
   * writes the config.php file
   *
   * @return mixed
   **/
  private function writeConfig()
  {
    return @file_put_contents($this->config_file, $this->generateConfig());
  }
  
  /**
   * checks that application configuration file have the right data
   *
   * @return Boolean    true, if ok
   **/
  private function checkApplicationConfigFile ()
  {
    $cond0 = file_exists($this->config_file) && is_readable($this->config_file);
    
    if ($cond0) {
      
      if (require($this->config_file))
      {
        $sf_root_dir = $this->getRequestParameter('sf_root_dir');
    
        $root_dir_path = $sf_root_dir ?
        $sf_root_dir :
        $options['sf_web_dir'].DIRECTORY_SEPARATOR.'..';
        
        $cond1 = $sw_installed;
        $cond2 = realpath($root_dir_path) == $options['sf_root_dir'];
      }
      else {
        return false;
      }
    }
    
    if ($cond1 && $cond2)
    {
      return true;
    }
    return false;
  }
  
  /**
   * checks that database configuration file have the right data
   *
   * @return Boolean    true, if ok
   **/
  private function checkDatabaseConfigFile ()
  {
    $cond0 = file_exists($this->db_file) && is_readable($this->db_file);
    
    if ($cond0)
    {
      // We call this to populate the databse config variables
      $tmpDatabaseConfigFile = $this->generateDatabaseConfig();
      $databaseArray = sfYaml::load($this->db_file);
      
      if ($databaseArray)
      {
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->database;
        $cond1 = $databaseArray['all']['doctrine']['param']['dsn'] == $dsn;
        $cond2 = $databaseArray['all']['doctrine']['param']['username'] == $this->username;
        $cond3 = $databaseArray['all']['doctrine']['param']['password'] == $this->password;
        
        if ($cond1 && $cond2 && $cond3) {
          return true;
        }
      }
    }
    
    return false;
  }
  
}
