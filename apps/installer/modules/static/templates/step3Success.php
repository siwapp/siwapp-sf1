<form id="step3Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <div id="header">
    <h2><?php echo __('License Agreement') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), $prev, 'type=button id=back', 'button=true') ?>
        <?php echo gButton(__("Next"), 'type=submit id=next', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <div id="license">
      <?php 
      $licenseDir = sfConfig::get('sf_app_module_dir') . '/static/templates/';
      $license = 'mit_' . substr($sf_user->getCulture(), 0, 2) . '.html';
      
      if (file_exists($licenseDir . $license))
      {
        include_once $license;
      }
      else
      {
        include_once 'mit_en.html';
      }
      ?>
    </div>
  </div>
</form>