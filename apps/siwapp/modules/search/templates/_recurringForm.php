<?php use_helper('Siwapp') ?>

<form id="searchForm" name="searchForm" class="searchform" action="" method="post">
  <div id="searchFilters">
    <div class="searchSection">
      <ul>
        <?php echo $form ?>
      </ul>
    </div>
    <hr class="searchSeparator">
    <div class="searchSection">
      <ul class="filters">
        <li><?php echo __('Status') ?>: </li>
        <li><?php echo filter_by_status(__('Enabled'), RecurringInvoice::ENABLED, $status = $form['status']->getValue()) ?> |</li>
        <li><?php echo filter_by_status(__('Disabled'), RecurringInvoice::DISABLED, $status) ?> |</li>
        <li><?php echo filter_by_status(__('Pending'), RecurringInvoice::PENDING, $status) ?> |</li>
        <li><?php echo filter_by_status(__('Finished'), RecurringInvoice::FINISHED, $status) ?></li>
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
