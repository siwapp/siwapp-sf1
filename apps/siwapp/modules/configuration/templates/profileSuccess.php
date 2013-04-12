<?php include_partial('configuration/navigation') ?>

<div id="settings-wrapper" class="content">
  <form action="<?php echo url_for('@profile') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php echo $form->renderHiddenFields() ?>
    
    <?php include_partial('common/globalErrors', array('form' => $form));?>

    <fieldset class="left">
      <h3><?php echo __('About you') ?></h3>
      <ul>
        <?php
          echo $form['first_name']->renderRow(array('class' => error_class($form['first_name'])));
          echo $form['last_name']->renderRow(array('class' => error_class($form['last_name'])));
          echo $form['email']->renderRow(array('class' => error_class($form['email'])));
        ?>
      </ul>
    </fieldset>

    <fieldset class="right">
      <h3><?php echo __('Change your password')?></h3>
      <ul>
        <?php echo $form['old_password']->renderRow(array('class'=>error_class($form['old_password'])))?>
        <?php echo $form['new_password']->renderRow(array('class'=>error_class($form['new_password'])))?>
        <?php echo $form['new_password2']->renderRow(array('class'=>error_class($form['new_password2'])))?>
      </ul>
    </fieldset>
    
    <fieldset class="left">
      <h3><?php echo __('Translate the application') ?></h3>
      <ul>
        <?php
          echo $form['language']->renderRow(array('class' => error_class($form['language'])));
        ?>
      </ul>
      <ul id="country_container"></ul>
    </fieldset>
    
    <fieldset class="left">
      <h3><?php echo __('Make it easy') ?></h3>
      <ul>
        <?php
          echo $form['nb_display_results']->renderRow(array('class' => error_class($form['nb_display_results'])));
          echo $form['search_filter']->renderRow(array('class' => error_class($form['search_filter'])));
          echo $form['series']->renderRow(array('class' => error_class($form['series'])));
        ?>
      </ul>
    </fieldset>
    
    <?php include_partial('submit') ?>
  </form>
</div>