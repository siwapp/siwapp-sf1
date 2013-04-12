<form action="<?php echo url_for('payments/form') ?>" method="post" class="payments-form">
  <ul class="payments">
    <?php echo $form; ?>
  </ul>
  <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $invoice_id ?>">
  <div class="buttons text-right">
    <?php echo gButton(__('Add payment'), "class=action add-payment rel=payments:add") ?>
    <?php echo gButton(__('Cancel'), 'class=action cancel rel=payments:cancel'); ?>
    <?php echo gButton(__('Save'), "type=submit class=action save rel=payments:save") ?>
  </div>
</form>