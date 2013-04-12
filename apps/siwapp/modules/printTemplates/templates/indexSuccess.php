<?php use_helper('Date') ?>
<?php include_partial('configuration/navigation') ?>

<div id="settings-wrapper" class="content">
  
  <form method="post" action="<?php echo url_for('@templates?action=save') ?>">
    <?php echo $_csrf->renderHiddenFields() ?>
    <table id="listing" class="listing">
      <thead>
        <tr>
          <th class="xs">
            <input type="checkbox" name="select_all" id="select_all" class="selectAll" rel="all" />
          </th>
          <th class="xs"><?php echo __('Use for Invoices')?></th>
          <th class="xs"><?php echo __('Use for Estimates')?></th>
          <th><?php echo __('Name') ?></th>
          <th class="medium"><?php echo __('Updated at') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($templates as $i => $template): ?>
          <tr id ="<?php echo "template-{$template->getId()}" ?>" class="template <?php echo "template-{$template->getId()}" ?>">
            <td class="check">
              <input type="checkbox" name="ids[]" class="check" value="<?php echo $template->getId() ?>" rel="item" />
            </td>
            <td><input type="checkbox" name="invoices[]" value="<?php echo $template->getId() ?>" <?php echo $template->isFor('Invoice')?'checked="checked"':'' ?> /></td>
            <td><input type="checkbox" name="estimates[]" value="<?php echo $template->getId() ?>" <?php echo $template->isFor('Estimate')?'checked="checked"':'' ?> /></td>
            <td><?php echo $template->getName() ?></td>
            <td><?php echo format_date($template->getUpdatedAt()) ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
      <tfoot>
        <tr class="noborder">
          <td colspan="5" class="listing-options">
            <?php echo gButton(__('Create new template'), 'id=createNew type=button class=action create rel=templates:add') ?>
            <?php echo gButton(__('Delete selected'), 'id=deleteSelected type=button class=action delete rel=templates:delete') ?>
            <?php echo gButton(__('Save'), 'id=save type=submit class=action save rel=templates:save') ?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  
</div>