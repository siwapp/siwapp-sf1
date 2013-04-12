<div id="tags-form-filter" class="tagselect" style="display:<?php echo $sf_user->isTagCloudVisible() ? 'block' : 'none' ?>;">
  <div id="tags-form-filter-content">
    <?php foreach ($tags as $tag): ?>
      <span class="tag <?php echo in_array($tag, $selected_tags->getRawValue()) ? 'selected' : '' ?>"><?php echo $tag ?></span>
    <?php endforeach ?>
  </div>
</div>
