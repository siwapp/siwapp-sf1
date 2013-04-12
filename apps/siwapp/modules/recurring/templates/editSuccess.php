<?php
use_helper('JavascriptBase', 'jQuery');
include_stylesheets_for_form($invoiceForm);
include_javascripts_for_form($invoiceForm);
$invoice = $invoiceForm->getObject();
?>

<div id="invoice-container" class="content">
  
  <h2><?php echo $title ?></h2>

  <form action="<?php echo url_for("recurring/$action") ?>" method="post" <?php $invoiceForm->isMultipart() and print 'enctype="multipart/form-data" ' ?>class="invoice">
    <?php 
    echo $invoiceForm['id'];
    echo $invoiceForm['_csrf_token'];
    echo $invoiceForm['type'];
    ?>

    <ul id="status">
      <li><?php echo __('Status')?>:&nbsp;<span class="status <?php echo ($stat = $invoice->getStatusString()) ?>"><?php echo __($stat)?></span></li>
      <?php echo $invoiceForm['enabled']->renderRow(); ?>
    </ul>
    
    <?php include_partial('common/globalErrors', array('form' => $invoiceForm)); ?>
    
    <div id="recurring-data" class="global-data">
      <div class="block">
        <h3><?php echo __('Invoice Execution Time')?></h3>
        <div>
          <h4><?php echo __('Starting')?></h4>
          <ul class="group">
            <?php echo $invoiceForm['starting_date']->renderRow()?>
          </ul>
          <div id="periodicity">
            <?php 
              echo $invoiceForm['period']->renderError();
              echo $invoiceForm['period']->renderLabel().' '.$invoiceForm['period']->render().' '; 
              echo $invoiceForm['period_type']->render();
            ?>
          </div>
          <h4><?php echo __('Finishing')?></h4>
          <ul class="group">
            <?php echo $invoiceForm['finishing_date']->renderRow(); ?>
            <?php echo $invoiceForm['max_occurrences']->renderRow(); ?>
          </ul>
        </div>
      </div>
    </div>

    <?php include_partial('common/clientInfo', array('invoiceForm' => $invoiceForm)); ?>

    <div id="payment-data" class="block">
      <h3><?php echo __('Payment details') ?></h3>
      <ul class="inline">
        <?php echo $invoiceForm['series_id']->renderRow()?>
        <?php echo $invoiceForm['days_to_due']->renderRow()?>
      </ul>
      <?php include_partial('common/items', array(
        'invoice' => $invoice,
        'invoiceForm' => $invoiceForm,
        'currency' => $currency
        ));?>
    </div>

    <?php include_partial('common/termsAndNotes', array('invoiceForm' => $invoiceForm)); ?>
  
    <?php include_partial('common/tagsDataBlock', array('invoice' => $invoice, 'invoiceForm' => $invoiceForm)) ?>

    <div id="saving-options">
      <?php if ($invoice->getId()) {
        echo gButton_to(__('Delete'), "recurring/delete?id=" . $invoice->getId(), array(
            'class' => 'action delete', 
            'post' => true,
            'confirm' => __('Are you sure?'),
          ) , 'button=false'); 
      }?>
       <?php echo gButton(__('Save'), 'type=submit class=action primary save', 'button=true') ?>
    </div>
  </form>
  
</div>