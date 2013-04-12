<?php
use_helper('JavascriptBase', 'jQuery');
include_stylesheets_for_form($productForm);
include_javascripts_for_form($productForm);

$product = $productForm->getObject();
?>
<div id="product-container" class="content">
  
  <h2><?php echo $title ?></h2>
  <form action="<?php echo url_for("products/$action") ?>" method="post" <?php $productForm->isMultipart() and print 'enctype="multipart/form-data" ' ?> class="product">
  <?php include_partial('common/globalErrors', array('form' => $productForm)) ?>
  <?php
    echo $productForm->renderHiddenFields();
  ?>
  <div id="product-data" class="global-data block">
  <h3><?php echo __('Product info') ?></h3>
  <ul>
    <li>
      <span class="_75"><?php echo render_tag($productForm['reference'])?></span>
      <span class="_25"><?php echo render_tag($productForm['price'])?></span>
    </li>

    <li>
      <span class="_50"><?php echo render_tag($productForm['description'])?></span>     
    </li>
  </ul>
</div>
  <div id="saving-options" class="block" style="margin-top: 20px">
    <?php
    if ($product->getId()) {
      echo gButton_to(__('Delete'), "products/delete?id=" . $product->getId(), array(
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
echo javascript_tag(" $('#product-data input[type=text], #product-data textarea').SiwappFormTips();") // See invoice.js
?>