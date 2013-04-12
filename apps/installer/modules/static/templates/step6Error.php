<form id="step6Form" class="installerForm" action="<?php echo url_for('@step6') ?>" method="post">
  <div id="header">
    <h2><?php echo __('Finish installation') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), '@step5', 'type=button id=back', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <h3><?php echo __('Oops, there are some problems') ?></h3>
    <p><?php __("Please review the following error messages carefully and try to correct them before you continue.") ?></p>
    
    <ul class="error_list">
      <?php foreach ($messages as $msg): ?>
        <li><?php echo __($msg) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</form>
