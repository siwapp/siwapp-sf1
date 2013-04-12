<div id="bd-login-form" class="<?php if (count($errors)) echo 'with-errors'; ?>" style="display:none;">
  <form id="login-form" class="login-form" action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <?php echo $form['_csrf_token']?>
    <ul>
      <li>
        <label for="username"><?php echo __('Username') ?></label>
        <?php echo $form['username']->render() ?>
        <?php echo $form['username']->renderError() ?>
      </li>
      <li class="row">
        <label for="password"><?php echo __('Password') ?></label>
        <?php echo $form['password'] ?>
        <?php echo $form['password']->renderError() ?>
      </li>
      <li id="remember-me-option" class="row">
        <?php echo $form['remember'] ?><span><label for="signin_remember"><?php echo __('Remember me') ?></label></span>
      </li>
      <li class="buttons">
        <?php echo gButton(__("Sign in"), 'type=submit id=signin', 'button=true') ?>
      </li>
    </ul>
    
    <a id="password-trigger" href="#"><?php echo __('Forgot your password?') ?></a>
  </form>
  
<?php if($form->hasGlobalErrors())
      {
        $errors  = $form->getGlobalErrors();
      }?>

  <?php if(count($errors)):?>
  <div id="errors-form" class="login-form">
    <label>Error!</label>
    <ul>
      <?php foreach($errors as $key => $error):?>
      <li><?php echo $error?></li>
      <?php endforeach?>
    </ul>
  </div>
  <?php endif?>
  
  <?php
    echo jq_form_remote_tag(array(
      'update'  => 'password-form-contents',
      'url'     => '@password_recovery',
      'loading' =>"$('#send').fadeOut();$('#formContent .indicator').fadeIn();"
    ), array(
      'id'    => 'password-form',
      'class' => 'login-form',
      'style' => 'display:none;'
    ));
  ?>
    <a id="password-close" href="#"><?php echo image_tag('icons/delete', 'alt='.__('close')) ?></a>
    
    <div id="password-form-contents">
      <p><?php echo __('Please type your username or email and we will send you an email with the activation link.')?></p>
      <ul>
        <li>
          <label for="username_email"><?php echo __('Username or email') ?></label>
          <input type="text" name="username_email" id="username_email">
        </li>
        <li class="buttons">
        <?php echo gButton(__("Send"),'type=submit id=send','button=true')?>
        <?php echo image_tag('ajax-bar.gif','class=indicator style=display:none;')?>
        </li>
      </ul>
      <?php echo image_tag('ajax-bar.gif','class=indicator style=display:none;')?>
    </div>
    
  </form>
  <a id="siwapp_site_link" href="http://www.siwapp.org" target="_new">Siwapp Invoice System</a>
</div>