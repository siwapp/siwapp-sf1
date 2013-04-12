<div id="terms-data" class="block">
  <h3><?php echo __('Terms & Conditions') ?></h3>
  <?php echo $invoiceForm['terms']->render(array('class'=>'terms '.error_class($invoiceForm['terms'])))?>

  <h3><?php echo __('Notes') ?></h3>
  <?php echo $invoiceForm['notes']->render(array('class'=>'notes '.error_class($invoiceForm['notes'])))?>
</div>
