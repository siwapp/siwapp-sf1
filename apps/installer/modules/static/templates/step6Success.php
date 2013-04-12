<form id="step6Form" class="installerForm" action="<?php echo url_for($thisPage) ?>" method="post">
  <?php echo '<input type="hidden" name="finished_install" id="finished_install" value="1" />'; ?>
  <div id="header">
    <h2><?php echo __('Finish installation') ?></h2>
    <ul>
      <li class="buttons">
        <?php echo gButton_to(__("Back"), $prev, 'type=button id=back', 'button=true') ?>
        <?php echo gButton(__("Finish"), 'type=submit id=finish', 'button=true') ?>
      </li>
    </ul>
  </div>
  
  <?php include_partial('sidebar', array('step' => $step)) ?>
  
  <div id="content">
    <?php if(count($downloads) == 0):?>
    <h3><?php echo __('Congratulations! Siwapp is now installed') ?></h3>
    <p>
      <?php echo __('Click the "Finish" button to start the Siwapp application.') ?>
    </p>
    <?php else:?>
    <h3><?php echo __('Siwapp is almost installed')?></h3>
    <?php endif?>

    <?php if(count($downloads) > 0):?>
    <p><?php echo __('Please read these instructions carefully:')?></p>
    <ul class="formField instructions">
      <?php foreach($downloads as $key => $download):?>
      <li><?php echo __("We couldn't write to %1% file. Please download it %2%here%3% and replace it at %4% by yourself",array('%1%'=>$download['short_file'],'%2%'=>'<a href="'.$download['url'].'">','%3%'=>'</a>','%4%'=>'<code>'.$download['file'].'</code>')) ?></li>  
      <?php endforeach?>
    </ul>
    <?php endif?>
    
    <?php if(count($warnings) > 0):?>
    <p><?php echo __("PLEASE TAKE THE FOLLOWING SECURITY MEASURES:")?></p>
    <?php endif?>
    <ul class="formField instructions">
      <?php foreach($warnings as $file):?>
      <li>
        <?php echo __('Remove the write permissions of the %1% file',
                      array('%1%'=>'<br/><code>'.$file.'</code><br/>'))?>
      </li>
      <?php endforeach?>
    </ul>

    <?php if(count($downloads)):?>
    <p><?php echo __('Once you\'ve checked out the instructions above, click the "Finish" button to finish the installation process.') ?></p>
    <?php endif?>

    <p><?php echo __('Enjoy!') ?></p>

  </div>
</form>
