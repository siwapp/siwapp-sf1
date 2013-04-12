<?php
  $tag_cloud_visible = $sf_user->isTagCloudVisible() ? 'tags-selected' : null;
  $tagged = count($selected_tags) ? 'tagged' : null;
?>
<a id="tags-form-filter-trigger" href="#" class="toggleTagCloud <?php echo "$tag_cloud_visible $tagged" ?>"><?php echo __('Tags').  
   image_tag('icons/'.($sf_user->isTagCloudVisible() ? 'contract' : 'expand').'.png');
?></a>
