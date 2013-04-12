<form id="step4Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <?php echo $form['_csrf_token']; ?>
  <div id="header">
    <h2><?php echo __('Database Configuration') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), $prev, 'type=button id=back', 'button=true') ?>
        <?php echo gButton(__("Next"), 'type=submit id=next', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <h3><?php echo __('Connection Settings') ?></h3>

    <p>
      <?php echo __('Enter your database connection details below.') ?>
      <?php echo __("If you're not sure about these, contact your host.") ?>
    </p>
      
    <ul class="formField">
      <li>
        <span class="label"><?php echo $form['database']->renderLabel() ?></span>
        <span><?php echo $form['database']->render(
            $form['database']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['database']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['username']->renderLabel() ?></span>
        <span><?php echo $form['username']->render(
            $form['username']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['username']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['password']->renderLabel() ?></span>
        <span><?php echo $form['password']->render(
            $form['password']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['password']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['host']->renderLabel() ?></span>
        <span><?php echo $form['host']->render(
            $form['host']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['host']->renderError() ?>
      </li>
      <li>
        <span class="label"><?php echo $form['overwrite']->renderLabel() ?></span>
        <span><?php echo $form['overwrite']->render(
            $form['overwrite']->hasError()? array('class' => 'error') : array()
        ) ?></span>
        <?php echo $form['overwrite']->renderError() ?>
      </li>
    </ul>
    
    <span class="global-errors"><?php echo $form->renderGlobalErrors() ?></span>
  </div>
</form>