<?php 
// this file can not ve viewed unless it's included from pre_installer_code.php
if(!isset($included_in_pre_installer))
{
  die('You\'re attempting to access this file the wrong way.');
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="siwapp - Installer" />
    <title>siwapp - Preinstall</title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" type="text/css" media="all" href="css/tripoli/tripoli.css" />
    <!--[if ie]><link rel="stylesheet" type="text/css" media="all" href="css/tripoli/tripoli.ie.css" /><![endif]-->
    <link rel="stylesheet" type="text/css" media="all" href="css/siwapp/layout.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/siwapp/typography.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/siwapp/buttons.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/siwapp/theme.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/siwapp/controls.css" />
    <link rel="stylesheet" type="text/css" media="print" href="css/siwapp/print.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/ui-orange/ui.all.css" />
    <link rel="stylesheet" type="text/css" media="all" href="css/siwapp/installer.css" />
  </head>
  <body class="static step0">
    <div id="hd"></div>

    <div id="bd">
      <div id="bd-content">
        <form id="step0Form" class="installerForm" action="" method="get">
        <div id="header">
          <h2>Pre-installation instructions</h2>
          <ul>
            <li class="buttons">
              <button type="submit" id="finish" class="btn"><span><span>Start</span></span></button>  
            </li>
          </ul>
        </div>
        
        <div id="content">
          <p>
            SIWAPP is based on the symfony framework, and it needs to have 
            access to certain special files and directories to work.

          <p>
            We strongly encourage you to read the 
            <a href="http://dev.siwapp.org/projects/siwapp/wiki/deployment_strategies">siwapp deployment strategies</a>
            wiki, to find out more about some concepts we are going to talk
            about here.
          
          <?php if(!is_dir($options['sf_root_dir'].'/config')):?>

          <p>
            The webpage you're seeing right now is located at: <br/>
            <code><?php echo dirname(__FILE__)?></code><br/><br/>

            <?php if(strlen($options['sf_root_dir'])) : ?>

            And SIWAPP expects to find the symfony root directory at:<br/>
            <code><?php echo $options['sf_root_dir']?></code><br/>
            <p>However, <strong>SIWAPP can't find that directory</strong>. 
            This can be due to:
            <ol>
             <li>
               <emph>You have those files elsewhere.</emph> 
               In that case, please type in the path on the input field below
             </li>
              <li>
                <emph>Your web server doesn't have permissions to access them.</emph>
                In that case, make sure the user your web server is running as
                (usually 'www-data', or 'apache', or 'wwwrun' or the like) 
                has read access
                to the files and read and execution access to the directories
              </li>
            </ol>
            Please type here the path of the symfony root directory:</p>

            <?php else: ?>

            <p>SIWAPP can't access the symfony root directory where it is 
            supposed to be. This is probably because it's located on a place 
            your php code can't access due to apache restrictions set by your 
            hosting provider.
            <ol>
              <li>
                If you're sure your symfony root directory is readable by your
                php code, then type on the input form its path.
              </li>
              <li>
                If your php can't access the symfony root directory, then move 
                the symfony root directory to a place where your php has access
             to, reload this page, and indicate on the input form the new path.
              </li>
            </ol>

            <?php endif ?>

          </p>

          <p><input name="sf_root_dir" size="50"/></p>

          <?php elseif($checks_results['cache']): ?>

          <input name="sf_root_dir" type="hidden" value="<?php echo $options['sf_root_dir']?>"/>
          <p><strong>SIWAPP can't write to the "cache" directory.</strong><br/> 
             The "cache" directory should be located at: <br/>
             <code><?php echo $options['sf_root_dir']?>/cache</code><br/>
              Please make sure it exists and the web server can write to it.
          </p>

          <?php else:?>

          <input name="sf_root_dir" type="hidden" value="<?php echo $options['sf_root_dir']?>"/>

          <?php endif?>

          <?php if($checks_results['pdo']):?>

          <p><strong>Your php distribution doesn't support PDO</strong>. SIWAPP , as being based on the symfony framework, relies heavily on PDO. You need to enable PDO support in you PHP.

           <?php endif?>

           <?php if($checks_results['pdo_mysql']):?>
          
           <p><strong>Your php distribution doesn't have the PDO mysql driver</strong>. SIWAPP uses mysql database. You need to get the mysql pdo driver.
           <?php endif?>

          <?php if($checks_results['version']):?>
          <p><strong>Your php version is not greater or equal than 5.2</strong>. SIWAPP needs php &gt;= 5.2 . Please upgrade your php engine
          <?php endif?>

          <?php if($checks_results['rewrite']):?>
          <p><strong>Your web server doesn't support url rewriting</strong>. Please activate mod_rewrite module in your web server.
          <?php endif?>

          <p>Once you've solved the indicated problems, reload this page, or just click on the "start" button.</p>
          <div style="text-align: center;">
            <a href="http://www.siwapp.org/">Siwapp</a> is Free Software released under the MIT license  </div>
          </div>
        </div>
        </form>
      </div> <!-- div#bd-content -->
    </div> <!-- div#bd -->
  </body>
</html>
