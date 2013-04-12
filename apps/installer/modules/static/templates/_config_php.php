<?php echo "<?php".PHP_EOL; ?>
$sw_installed = true;
$options['sf_web_dir'] = dirname(__FILE__);
$options['sf_root_dir'] = realpath(<?php echo $sf_data->getRaw('sf_root_dir') ?>);