<?php use_helper('JavascriptBase'); ?>
<table class="listing">
  <thead>
    <tr>
      <?php if($sf_user->has_module('products')):?>
      <th><?php echo __('Product') ?></th>
      <?php endif?>
      <th><?php echo __('Description') ?></th>
      <th class="right"><?php echo __('Unit Cost') ?></th>
      <th class="right"><?php echo __('Qty') ?></th>
      <th class="right"><?php echo __('Taxes') ?></th>
      <th class="right"><?php echo __('Discount') ?></th>
      <th class="right"><?php echo __('Price') ?></th>
    </tr>
  </thead>

  <tbody id="tbody_invoice_items">
    <?php $invoiceItemsGlobalErrors = array(); ?>
    <?php foreach($invoiceForm['Items'] as $rowId => $invoiceItemForm):?>
    <?php if($invoiceItemForm['remove']->getValue() != '1'):?>
    <?php if (strlen($tmp = $invoiceItemForm->renderError())) $invoiceItemsGlobalErrors[] = $tmp; ?>
    <?php include_partial('common/invoiceRow', array(
      'invoiceItemForm' => $invoiceItemForm,
      'rowId'           => $rowId
    ))?>
    <?php endif?>
    <?php endforeach ?>
  </tbody>

  <tfoot id="global_calculations">
    <tr>
      <td colspan="<?php echo ($sf_user->has_module('products'))?'5':'4'?>" rowspan="5" class="noborder top">
        <div id="addItem">
          <?php 
            $addItemOptions = array(
              'url'      => 'common/ajaxAddInvoiceItem',
              'position' => 'bottom',
              'method'   => 'post',
              'with'     => "{invoice_id: $('#invoice_id').val()}",
              'success'  => "$('#tbody_invoice_items').append(data);
                  $('textarea.resizable:not(.processed)').TextAreaResizer();"
              );
            echo jq_link_to_remote(__("Add Item"), $addItemOptions);
            // if invoice has no items, we add one by an ajax call
            if(!count($invoiceForm['Items']))
            {
              $first = jq_remote_function($addItemOptions);
              echo javascript_tag("$first");
            }
          ?>
        </div>
        <?php if (count($invoiceItemsGlobalErrors)): ?>
          <div id="invoiceItemsGlobalErrors" class="errorBox">
            <p><?php echo __('Please check these errors and correct them before saving:') ?></p>
            <?php echo implode("\n", $invoiceItemsGlobalErrors) ?>
          </div>
        <?php endif ?>
      </td>
      <td><?php echo __('Base') ?></td>
      <td class="base right">
        <?php echo format_currency($invoice->getRoundedAmount('base'), $currency) ?>
      </td>
    </tr>
    <tr>
      <td><?php echo __('Discount') ?></td>
      <td class="discount right">
        <?php echo format_currency($invoice->getRoundedAmount('discount'), $currency)?>
      </td>
    </tr>
    <tr>
      <td><?php echo __('Subtotal') ?></td>
      <td class="net right">
        <?php echo format_currency($invoice->getRoundedAmount('net'), $currency)?>
      </td>
    </tr>
    <tr>
      <td><?php echo __('Taxes') ?></td>
      <td class="taxes right">
        <?php echo format_currency($invoice->getRoundedAmount('tax'), $currency)?>
      </td>
    </tr>
    <tr class="strong">
      <td><?php echo __('Total') ?></td>
      <td class="gross right">
        <?php echo format_currency($invoice->getRoundedAmount('gross'), $currency) ?>
      </td>
    </tr>
  </tfoot>
</table>
<?php
echo javascript_tag("
// resizable text area
$(document).ready(function() {
  $('textarea.resizable:not(.processed)').TextAreaResizer();
});
");
?>