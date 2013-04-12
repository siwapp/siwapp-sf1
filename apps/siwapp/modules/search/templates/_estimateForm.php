<?php 
use_helper('Siwapp', 'JavascriptBase');
echo javascript_tag("var customer_name_autocomplete = '".$customer_name."'");
?>
<form id="searchForm" name="searchForm" class="searchform" action="" method="post">
  <?php echo $form->renderHiddenFields() ?>
  <div id="searchFilters">
    <div class="searchSection">
      <ul>
        <?php
        echo $form['query']->renderRow();
        echo $form['from']->renderRow(); 
        echo $form['to']->renderRow();
        echo $form['quick_dates']->renderRow();
        ?>
      </ul>
    </div>
  
    <hr class="searchSeparator">
  
    <div class="searchSection">
      <ul class="filters">
        <li><?php echo __('Status');?>: </li>
        <li><?php echo filter_by_status(__('Pending'), Estimate::PENDING, $status = $form['status']->getValue()) ?> |</li>
        <li><?php echo filter_by_status(__('Rejected'), Estimate::REJECTED, $status) ?> |</li>
        <li><?php echo filter_by_status(__('Approved'), Estimate::APPROVED, $status) ?> |</li>
        <li><?php echo filter_by_status(__('Drafts'), Estimate::DRAFT, $status) ?></li>
      </ul>
      <ul class="series">
      <?php
      echo $form['sent']->renderRow();
      echo $form['series_id']->renderRow();
      echo $form['customer_id']->renderRow();
      ?>
      </ul>
      <?php include_partial('search/tagsSwitch', array('selected_tags' => $selected_tags)); ?>
    </div>
  </div>
  <span class="buttons">
    <?php
      echo
        gButton(__('Search'), 'id=search-form-submit type=submit').
        gButton(__('Reset'), 'id=search-form-reset type=reset');
    ?>
  </span>
</form>

<?php include_partial('search/tags', array('tags' => $tags, 'selected_tags' => $selected_tags)); ?>