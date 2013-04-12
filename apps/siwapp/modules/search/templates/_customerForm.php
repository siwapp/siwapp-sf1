<?php 
use_helper('Siwapp', 'JavascriptBase');
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
  </div>
  <span class="buttons">
    <?php
      echo
        gButton(__('Search'), 'id=search-form-submit type=submit').
        gButton(__('Reset'), 'id=search-form-reset type=reset');
    ?>
  </span>
</form>
