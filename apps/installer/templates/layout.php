<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <script type="text/javascript" charset="utf-8">
      var siwapp_urls = {
        getCountries : '<?php echo url_for('static/ajaxGetCountries') ?>'
      };
    </script>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
<body class="<?php echo semantic_body_classes() ?>">
  
  <div id="hd"></div>

  <div id="bd">
    <div id="bd-content">
      <?php echo $sf_content ?>
    </div>
  </div>

  <div style="text-align: center;">
    <a href="http://www.siwapp.org/">Siwapp</a> <?php echo __('is Free Software released under the MIT license') ?>
  </div>
</body>
</html>
