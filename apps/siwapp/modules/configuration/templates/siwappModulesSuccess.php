<?php use_helper('JavascriptBase')?>

<?php include_partial('configuration/navigation')?>

<div id="settings-wrapper" class="content">
  <form action="<?php echo url_for('@siwapp_modules')?>" method="post" >
  <?php include_partial('common/globalErrors', array('form' => $form))?>
  <?php echo $form['_csrf_token']?>
  <fieldset class="left">
  <p><?php echo __('Select the modules you want to be activated in you siwapp application')?></p>
  <?php echo $form['siwapp_modules']?>
  </fieldset>
  <?php include_partial('submit')?>
</div>