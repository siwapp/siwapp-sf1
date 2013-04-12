<?php if ($form->hasGlobalErrors()): ?>
  <fieldset id="global-errors">
    <p><?php echo __('Please check these errors and correct them before saving:') ?></p>
    <?php echo $form->renderGlobalErrors()?>
  </fieldset>
<?php endif ?>
