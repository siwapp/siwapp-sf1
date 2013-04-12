<h2><?php echo __('Siwapp Upgrade')?></h2>
<?php
use_helper('Tag');
if (isset($errors))
{
  if (count($errors))
  {
    echo content_tag('p', __('The following errors occurred'));
    echo '<ul>';
    foreach($errors as $error)
    {
      echo content_tag('li', $error);
    }
    echo '</ul>';
  }
  else
  {
    echo content_tag('p', __('Upgrade successfully completed'));
  }
}
else
{
  echo content_tag('p', __('No upgrade needed'));
}
?>