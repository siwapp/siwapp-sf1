<?php use_helper('Siwapp') ?>
<ul id="settings-menu" class="content">
  <li class="<?php settings_tab_selected('global') ?>"><?php echo link_to(__('Global settings'), '@settings') ?></li>
  <li class="<?php settings_tab_selected('profile') ?>"><?php echo link_to(__('My settings'), '@profile') ?></li>
  <li class="<?php settings_tab_selected('templates') ?>"><?php echo link_to(__('Printing templates'), '@templates') ?></li>
  <li class="<?php settings_tab_selected('siwapp_modules') ?>"><?php echo link_to(__('Modules'), '@siwapp_modules') ?></li>
</ul>