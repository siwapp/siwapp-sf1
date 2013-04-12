<?php
use_helper('JavascriptBase', 'jQuery');
include_stylesheets_for_form($invoiceForm);
include_javascripts_for_form($invoiceForm);

$invoice = $invoiceForm->getObject();
?>
<div id="invoice-container" class="content">
  
  <h2><?php echo $title ?></h2>

  <form action="<?php echo url_for("invoices/$action") ?>" method="post" <?php $invoiceForm->isMultipart() and print 'enctype="multipart/form-data" ' ?> class="invoice">
    <input type="hidden" id="send_email" name="send_email" value="0" />
  <?php 
    echo $invoiceForm['id'];
    // here draft, in case were saved as draft, the button must put 1 here
    echo $invoiceForm['draft'];
    echo $invoiceForm['_csrf_token'];
    echo $invoiceForm['type'];
    echo $invoiceForm['recurring_invoice_id'];
    echo $invoiceForm['customer_id'];
  ?>
  <ul id="status">
    <li><?php echo __('Status')?>: <span class="status <?php echo ($stat = $invoice->getStatusString()) ?>"><?php echo __($stat)?></span></li>
    <?php echo $invoiceForm['closed']->renderRow(); ?>
    <?php echo $invoiceForm['sent_by_email']->renderRow() ?>
  </ul>
  
  <?php 
    include_partial('common/globalErrors', array('form' => $invoiceForm));
    include_partial('common/clientInfo', array('invoiceForm' => $invoiceForm)); ?>
    
  <div id="payment-data" class="block">
    <h3><?php echo __('Payment details') ?></h3>
    <ul class="inline">
      <?php echo $invoiceForm['series_id']->renderRow() ?>
      <?php echo $invoiceForm['issue_date']->renderRow() ?>
      <?php echo $invoiceForm['due_date']->renderRow() ?>
    </ul>
      
    <?php include_partial('common/items', array(
      'invoice' => $invoice,
      'invoiceForm' => $invoiceForm,
      'currency' => $currency
      ));?>
  </div>  <!-- div#payment-data -->

  <?php include_partial('common/termsAndNotes', array('invoiceForm' => $invoiceForm)); ?>

  <?php include_partial('common/tagsDataBlock', array('invoice' => $invoice, 'invoiceForm' => $invoiceForm)) ?>
  
  <div id="saving-options" class="block">
    <?php 
    if ($invoice->getId()) {
      echo gButton_to(__('Delete'), "invoices/delete?id=" . $invoice->getId(), array(
        'class' => 'action delete', 
        'post' => true,
        'confirm' => __('Are you sure?'),
        ) , 'button=false')."&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }
    if (!$invoice->isNew())
    {
      echo gButton_to_function(__('Print'), "Tools.popup(siwapp_urls.printHtml + '?ids[]=".$invoice->getId()."')", 'class=action print')." ";
      echo gButton_to_function(__('PDF'), "window.location=siwapp_urls.printPdf + '?ids[]=".$invoice->getId()."'", 'class=action pdf')."&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    if ($invoice->isNew() || $db_draft)
    {
      echo gButton_to_function(__('Save as draft'), "\$('form.invoice').saveInvoiceAsDraft()", 'class=action save-draft', 'button=true')."&nbsp;&nbsp;";
    }
    echo gButton_to_function(__('Save and send by e-mail'),"\$('form.invoice').saveInvoiceAndEmail();", 'class=action send save-email', 'button=true')."&nbsp;&nbsp;";
    
    echo gButton(__('Save'), 'type=submit class=action primary save', 'button=true'); 
    ?>
  </div>
  </form>
</div>