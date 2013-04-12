<?php
use_helper('JavascriptBase', 'jQuery');
include_stylesheets_for_form($estimateForm);
include_javascripts_for_form($estimateForm);

$estimate = $estimateForm->getObject();
?>
<div id="invoice-container" class="content">
  
  <h2><?php echo $title ?></h2>

  <form action="<?php echo url_for("estimates/$action") ?>" method="post" <?php $estimateForm->isMultipart() and print 'enctype="multipart/form-data" ' ?> class="invoice">
    <input type="hidden" id="send_email" name="send_email" value="0" />
    <input type="hidden" id="generate_invoice" name="generate_invoice" value="0" />
  <?php 
    echo $estimateForm['id'];
    // here draft, in case were saved as draft, the button must put 1 here
    echo $estimateForm['draft'];
    echo $estimateForm['_csrf_token'];
    echo $estimateForm['type'];
  ?>
  <ul id="status">
    <?php echo $estimateForm['status']->renderRow() ?>
    <?php echo $estimateForm['sent_by_email']->renderRow() ?>
  </ul>
  
  <?php 
    include_partial('common/globalErrors', array('form' => $estimateForm));
    include_partial('common/clientInfo', array('invoiceForm' => $estimateForm)); ?>
    
  <div id="payment-data" class="block">
    <h3><?php echo __('Estimate details') ?></h3>
    <ul class="inline">
      <?php echo $estimateForm['series_id']->renderRow() ?>
      <?php echo $estimateForm['issue_date']->renderRow() ?>
    </ul>
      
    <?php include_partial('common/items', array(
      'invoice' => $estimate,
      'invoiceForm' => $estimateForm,
      'currency' => $currency
      ));?>
  </div>  <!-- div#payment-data -->

  <?php include_partial('common/termsAndNotes', array('invoiceForm' => $estimateForm)); ?>

  <?php include_partial('common/tagsDataBlock', array('invoice' => $estimate, 'invoiceForm' => $estimateForm)) ?>
  
  <div id="saving-options" class="block">
    <?php 
    if ($estimate->getId()) {
      echo gButton_to(__('Delete'), "estimates/delete?id=" . $estimate->getId(), array(
        'class' => 'action delete', 
        'post' => true,
        'confirm' => __('Are you sure?'),
        ) , 'button=false')."&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }
    if (!$estimate->isNew())
    {
      echo gButton_to_function(__('Print'), "Tools.popup(siwapp_urls.printHtml + '?ids[]=".$estimate->getId()."')", 'class=action print')." ";
      echo gButton_to_function(__('PDF'), "window.location=siwapp_urls.printPdf + '?ids[]=".$estimate->getId()."'", 'class=action pdf')."&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    if ($estimate->isNew() || $db_draft)
    {
      echo gButton_to_function(__('Save as draft'), "\$('form.invoice').saveInvoiceAsDraft()", 'class=action save-draft');
    }
    if (!$estimate->isNew())
    {
      echo gButton_to_function(__('Save and create invoice'), "\$('form.invoice').setValueAndSendForm('#generate_invoice', 1);", 'class=action create-invoice')." ";
    }
    echo gButton_to_function(__('Save and send by e-mail'),"\$('form.invoice').saveInvoiceAndEmail();", 'class=action send save-email')."&nbsp;&nbsp;";
    echo gButton(__('Save'), 'type=submit class=action primary save', 'button=true'); 
    ?>
  </div>
  </form>
</div>