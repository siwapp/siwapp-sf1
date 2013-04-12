<form id="step2Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <div id="header">
    <h2><?php echo __('Pre-Installation Check') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), $prev, 'type=button id=back', 'button=true') ?>
        <?php echo gButton(__("Next"), 'type=submit id=next', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    
    <div>
      <h3><?php echo __('Checking Siwapp requirements') ?></h3>
      <span class="label">
        <?php echo __('If any of these items are highlighted in red then please take actions to correct them') ?>.
        <?php echo __('Failure to do so could lead to your Siwapp installation not functioning correctly') ?>.
      </span>
      <div class="field">
      <?php
        $blocking_error = false;
        foreach ($checks_required as $check)
        {
          check($check[0], $check[1], $check[2], true);
          if(!$check[0]) $blocking_error = true;
        }
        
        if($blocking_error) echo '<input type="hidden" name="error" id="error" value="1" />';
      ?>
      </div>
    </div>
    
    <div>
      <h3><?php echo __('File Permissions') ?></h3>
      <span class="label">
        <?php echo __('Siwapp needs to have write permissions over certain files and directories') ?>.
      </span>
      <div class="field">
      <?php 
        foreach ($checks_fileperms as $check)
        {
          check($check[0], $check[1], $check[2], true);
        }
      ?>
      </div>
    </div>
    
    <div>
      <h3><?php echo __('Recommended settings') ?></h3>
      <span class="label">
        <?php echo __('These settings are recommended for PHP in order to ensure full compatibility with Siwapp') ?>.
        <?php echo __('However, Siwapp will still operate if your settings do not quite match the recommended') ?>.
      </span>
      <div class="field">
      <?php 
        foreach ($checks_recommended as $check)
        {
          check($check[0], $check[1], $check[2]);
        }
      ?>
      </div>
    </div>
    
  </div>
</form>
