<form id="step5Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <?php echo $form['_csrf_token']; ?>
  <div id="header">
    <h2><?php echo __('Main Configuration') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), $prev, 'type=button id=back', 'button=true') ?>
        <?php echo gButton(__("Next"), 'type=submit id=next', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <h3><?php echo __('Administrator user details') ?></h3>
    <p><?php echo __('Enter the following information for the super administrator user:')?></p>

    <ul class="formField">
      <li>
        <span class="label"><?php echo $form['admin_email']->renderLabel() ?></span>
        <span><?php echo $form['admin_email']->render(
            $form['admin_email']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['admin_email']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['admin_username']->renderLabel() ?></span>
        <span><?php echo $form['admin_username']->render(
            $form['admin_username']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['admin_username']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['admin_password']->renderLabel() ?></span>
        <span><?php echo $form['admin_password']->render(
            $form['admin_password']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['admin_password']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['admin_password_bis']->renderLabel() ?></span>
        <span><?php echo $form['admin_password_bis']->render(
            $form['admin_password_bis']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['admin_password_bis']->renderError() ?>
      </li>
    </ul>
    
    <h3><?php echo __('Application initial data') ?></h3>
    <p><?php echo __('If you want to preload the database with sample data, check the following checkbox.')?></p>
    <ul class="formField">
      <li>
        <span class="label"><?php echo $form['preload']->renderLabel() ?></span>
        <span><?php echo $form['preload']->render() ?></span>
      </li>
    </ul>
    <?php echo $form->renderGlobalErrors() ?>
  </div>
</form>