  <div id="sidebar">
    <ol>
      <li><?php echo link_to_step(1, $step, __('Language'), '@step1') ?></li>
      <li><?php echo link_to_step(2, $step, __('Pre-Installation Check'), '@step2') ?></li>
      <li><?php echo link_to_step(3, $step, __('License'), '@step3') ?></li>
      <li><?php echo link_to_step(4, $step, __('Database'), '@step4') ?></li>
      <li><?php echo link_to_step(5, $step, __('Configuration'), '@step5') ?></li>
      <li><?php echo link_to_step(6, $step, __('Finish'), '@step6') ?></li>
    </ol>
  </div>