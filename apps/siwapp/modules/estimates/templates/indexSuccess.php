<?php
use_helper('JavascriptBase', 'Number', 'Siwapp', 'Date');

$invoices = $pager->getResults();
$csrf     = new sfForm();
?>

<div class="content">
  
  <?php if (count($invoices)): ?>
    
    <?php echo form_tag('estimates/batch', 'id=batch_form class=batch') ?>
      <?php echo $csrf['_csrf_token']->render(); ?>
      <input type="hidden" name="batch_action" id="batch_action">

      <table id="listing" class="listing">
        
        <thead>
          
          <tr>
            <td colspan="4" class="listing-options noborder">
              <?php include_partial('invoices/batchActions')?>
            </td>
            <td class="strong noborder"><?php echo __('Total') ?></td>
            <td class="strong noborder right"><?php echo format_currency($gross, $currency) ?></td>
          </tr>

          <tr class="empty noborder">
            <td colspan="1000"></td>
          </tr>

          <tr>
            <th class="xs"><input id="select_all" rel="all" type="checkbox" name="select_all"></th>
            <?php
              // sort parameter => array (Name, default order)
              renderHeaders(array(
                'number'        => array('Number', 'desc'),
                'customer_name' => array('Customer Name', 'asc'),
                'issue_date'    => array('Date', 'desc'),
                'status'        => array('Status', 'asc'),
                'gross_amount'  => array('Total', 'desc')
                ), $sf_data->getRaw('sort'), '@estimates');
            ?>
          </tr>
        </thead>

        <tbody>
          
          <?php foreach ($invoices as $i => $invoice): ?>
            <?php
              $id       = $invoice->getId();
              $parity   = ($i % 2) ? 'odd' : 'even';
              $closed   = ($invoice->getStatus() == Invoice::CLOSED);
            ?>
            <tr id="invoice-<?php echo $id ?>" class="<?php echo "$parity link invoice-$id ".($closed ? 'show' : 'edit') ?>">
              <td class="check"><input rel="item" type="checkbox" value="<?php echo $id ?>" name="ids[]"></td>
              <td><?php echo $invoice ?></td>
              <td class="<?php echo $invoice->getSentByEmail() ? 'sent' : null ?>"><?php echo $invoice->getCustomerName() ?></td>
              <td><?php echo format_date($invoice->getIssueDate()) ?></td>
              <td>
                <span class="status <?php echo ($stat = $invoice->getStatusString()) ?>">
                  <?php echo __($stat) ?>
                </span>
              </td>
              <td class="right">
                <?php if ($invoice->getDraft()): ?>
                  <span class="draftAmount" title="<?php echo __('This amount is not reflected in the total') ?>"></span>
                <?php endif?>
                <?php echo format_currency($invoice->getGrossAmount(), $currency)  ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>

        <tfoot>
          <tr class="noborder">
            <td colspan="10" class="listing-options">
              <?php include_partial('invoices/batchActions'); ?>
            </td>
          </tr>
        </tfoot>

      </table>
    </form>

    <?php include_partial('global/pager', array('pager' => $pager, 'route' => '@estimates')) ?>
    
  <?php else: ?>
    <p><?php echo __('No results') ?></p>
  <?php endif ?>
  
</div>
