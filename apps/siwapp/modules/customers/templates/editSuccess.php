<?php
use_helper('JavascriptBase', 'jQuery');
include_stylesheets_for_form($customerForm);
include_javascripts_for_form($customerForm);

$customer = $customerForm->getObject();
?>
<div id="customer-container" class="content">
  
  <h2><?php echo $title ?></h2>
  <form action="<?php echo url_for("customers/$action") ?>" method="post" <?php $customerForm->isMultipart() and print 'enctype="multipart/form-data" ' ?> class="customer">
  <?php include_partial('common/globalErrors', array('form' => $customerForm)) ?>
  <?php
  echo $customerForm->renderHiddenFields();
  ?>
  <div id="customer-data" class="global-data block">
  <h3><?php echo __('Client info') ?></h3>
  <ul>
    <li>
      <span class="_75">
        <?php echo render_tag($customerForm['name'])?>
        <?php echo $customerForm['name_slug']->renderError()?>
      </span>
      <span class="_25"><?php echo render_tag($customerForm['identification'])?></span>
    </li>
    <li>
      <span class="_50"><?php echo render_tag($customerForm['contact_person'])?></span>
      <span class="_50"><?php echo render_tag($customerForm['email'])?></span>
    </li>
    <li>
      <span class="_50"><?php echo render_tag($customerForm['invoicing_address'])?></span>
      <span class="_50"><?php echo render_tag($customerForm['shipping_address'])?></span>
    </li>
  </ul>
</div>
  <div id="saving-options" class="block">
    <?php
    if ($customer->getId()) {
      echo gButton_to(__('Delete'), "customers/delete?id=" . $customer->getId(), array(
        'class' => 'action delete',
        'post' => true,
        'confirm' => __('Are you sure?'),
        ) , 'button=false')." ";
    }
    
    echo gButton(__('Save'), 'type=submit class=action primary save', 'button=true');
    ?>
  </div>
  </form>
</div>
<?php
echo javascript_tag(" $('#customer-data input[type=text], #customer-data textarea').SiwappFormTips();") // See invoice.js
?>