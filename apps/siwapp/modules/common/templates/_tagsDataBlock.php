<?php use_helper('JavascriptBase') ?>

<div id="tags_data" class="block">
  <h3><?php echo __('Tags') ?></h3>
  
  <?php
    echo $invoiceForm['tags'];
    echo '&nbsp;'.gButton_to_function(__('Add'), "$('#invoice_tags_input').trigger('ComputeTags')", 'class=action-clear addTag');
    echo $invoiceForm['tags']->renderError();
    
    $tagTemplate = esc_js_no_entities(get_partial('common/tagSpan', array('tag' => '#{tag}')));
    
    echo javascript_tag("
      $('#invoice_tags').tagSelector({
        autocompletionUrl : '".url_for('common/ajaxTagsAutocomplete')."',
        tagsContainer     : 'the_tags_div',
        tagTemplate       : '$tagTemplate'
      });
    ");
  ?>
  
  <div id="the_tags_div" class="taglist">
    <?php foreach($invoice->getTags() as $tag): ?>
      <?php include_partial('common/tagSpan', array('tag' => $tag)) ?>
    <?php endforeach; ?>
  </div>
</div>