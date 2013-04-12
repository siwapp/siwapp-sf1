<?php 
use_helper('JavascriptBase', 'Number', 'Siwapp');

$csrf = new sfForm(); // to add csrf protection to batch actions
?>

<div class="content">
  
  <table id="recurring-summary">
    <tbody>
      <tr>
        <th colspan="10"><?php echo __('Average turnover') ?></th>
      </tr>
      <tr>
        <td><strong><?php echo format_currency($expected, $currency)?></strong> /<?php echo __('day') ?></td>
        <td><strong><?php echo format_currency($expected*7, $currency)?></strong> /<?php echo __('week') ?></td>
        <td><strong><?php echo format_currency($expected*30, $currency)?></strong> /<?php echo __('month') ?></td>
        <td><strong><?php echo format_currency($expected*365, $currency)?></strong> /<?php echo __('year') ?></td>
      </tr>
    </tbody>
  </table>
  
  <?php if (count($pager->getResults())): ?>
    
    <?php echo form_tag('recurring/batch', 'id=batch_form'); ?>
      <?php echo $csrf['_csrf_token']->render(); ?>
      <input type="hidden" name="batch_action" id="batch_action">

      <table id="listing" class="listing">
        <thead>
          <tr class="noborder">
            <td colspan="1000" class="listing-options">
              <?php echo gButton_to_function(__("Delete"), "do_batch('delete')", array('class'=>'batch delete action-clear', 'confirm'=>__('Are you sure?'))) ?>
            </td>
          </tr>

          <tr>
            <th class="check xs"><input type="checkbox" id="select_all" name="select_all" class="checkAll" rel="all" /></th>
            <?php
              // sort parameter => array (Name, default order)
              renderHeaders(array(
                'series'          => array('Series', 'desc'),
                'customer_name'   => array('Customer Name', 'asc'),
                'period_type'     => array('Period Type', 'asc'),
                'period'          => array('Frequency', 'asc'),
                'status'          => array('Status', 'asc'),
                'gross_amount'    => array('Total','desc')
                ), $sf_data->getRaw('sort'), '@recurring');
            ?>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($pager->getResults() as $i => $invoice): ?>
            <?php
              $id     = $invoice->getId();
              $url    = url_for('recurring/show?id='.$invoice->getId());
              $parity = ($i % 2) ? 'odd' : 'even';
            ?>
          <tr id="<?php echo "invoice-$id" ?>" class="<?php echo "$parity link invoice-$id ".($stat = $invoice->getStatusString()) ?>">
            <td class="check"><input type="checkbox" class="check" name="ids[]" value="<?php echo $id ?>" rel="item" /></td>
            <td><?php echo $invoice->getSeries() ?></td>
            <td><?php echo $invoice->getCustomerName() ?></td>
            <td><?php echo $invoice->period_type ? __($invoice->period_type.'ly') : '' ?></td>
            <td><?php echo $invoice->getPeriod() ?></td>
            <td class="status"><span class="status <?php echo $stat ?>"><?php echo __($stat)?></span></td>
            <td class="right"><?php echo format_currency($invoice->getGrossAmount(), $currency) ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>

        <tfoot>
          <tr class="noborder">
            <td colspan="3" class="listing-options">
              <?php echo gButton_to_function(__("Delete"), "do_batch('delete')", array('class'=>'batch delete action-clear', 'confirm'=>__('Are you sure?'))) ?>
            </td>
            <td colspan="4" class="text-right">
              <?php if ($pending) 
                      echo gButton_to(__('Generate pending invoices now'), 'recurring/generate', 'id=pendingButton class="action generate-pending"'); ?>
            </td>
          </tr>
        </tfoot>
      </table>

    </form>
    
    <?php include_partial('global/pager', array('pager' => $pager, 'route' => '@recurring')) ?>
    
  <?php else: ?>
    
    <p><?php echo __('No results') ?></p>
    
  <?php endif ?>
  
</div>
