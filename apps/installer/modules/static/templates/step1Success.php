<form id="step1Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <div id="header">
    <h2><?php echo __('Choose language') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton(__("Next"), 'type=submit id=next', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <div>
      <span class="label">
        <label for="language"><?php echo __('Please choose the language to use during the installation of Siwapp') ?></label>
      </span>
      <span class="field">
        <?php
          $lang_selector = new sfWidgetFormI18nChoiceLanguage(array(
            'culture' => $sf_user->getAttribute('language', $preferred_language),
            'languages' => CultureTools::getAvailableLanguages()
            ));
            
          echo $lang_selector->render('language', $sf_user->getAttribute('language', $preferred_language), array('id' => 'config_language'));
        ?>
      </span>
    </div>
    <div id="country_container"></div>
  </div>
</form>