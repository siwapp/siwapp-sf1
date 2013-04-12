<?php use_helper('Number', 'I18N', 'Date') ?>

<div id="content-wrapper" class="content">

  <table id="dashboard-summary" class="dashboard-info">
    <tbody>
      <tr>
        <td><?php echo __('Receipts') ?></td>
        <td id="receipts" class="right"><?php echo format_currency($paid, $currency)?></td>
      </tr>
      <tr>
        <td><?php echo __('Due') ?><br/><small></small></td>
        <td id="due" class="totalDue right"><?php echo format_currency($due, $currency)?></td>
      </tr>
      <tr class="overdue">
        <td><?php echo __('Overdue') ?></td>
        <td id="overdue" class="right"><?php echo format_currency($odue, $currency)?></td>
      </tr>
    </tbody>
  </table>

  <table id="dashboard-balance" class="dashboard-info">
    <tbody>
      <tr>
        <td><?php echo __('Total') ?>:</td>
        <td id="dashboard-balance-total"><?php echo format_currency($gross,$currency);?></td>
      </tr>
      <tr>
        <td><?php echo __('Net') ?>:</td>
        <td id="dashboard-balance-net"><?php echo format_currency($net,$currency);?></td>
      </tr>
      <tr>
        <td><?php echo __('Taxes') ?>:</td>
        <td id="dashboard-balance-taxes"><?php echo format_currency($taxes,$currency);?></td>
      </tr>
    </tbody>
  </table>

  <table id="dashboard-taxes" class="dashboard-info">
    <tbody>
      <?php foreach($total_taxes as $ttname=>$ttvalue):?>
      <tr>
        <td><?php echo $ttname?>:</td>
        <td><?php echo format_currency($ttvalue,$currency)?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>

  <div class="clear"></div>
  <h2><?php echo __('Recent invoices') ?></h2>
  <table class="listing">
    <thead>
      <tr>
        <th class="number"><?php echo __('Number') ?></th>
        <th><?php echo __('Customer Name') ?></th>
        <th class="date"><?php echo __('Date') ?></th>
        <th class="date"><?php echo __('Due Date') ?></th>
        <th class="status"><?php echo __('Status') ?></th>
        <th class="right due"><?php echo __('Due') ?></th>
        <th class="right total"><?php echo __('Total') ?></th>
        <th class="noborder"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($recent as $i => $invoice): ?>
        <?php
          $id       = $invoice->getId();
          $parity   = ($i % 2) ? 'odd' : 'even';
          $closed   = ($invoice->getStatus() == Invoice::CLOSED);
        ?>
        <tr id="invoice-<?php echo $id ?>" class="<?php echo "$parity link invoice-$id" ?>">
          <td class="link number"><?php echo $invoice ?></td>
          <td class="link"><?php echo $invoice->getCustomerName() ?></td>
          <td class="link date"><?php echo format_date($invoice->getIssueDate()) ?></td>
          <td class="link date"><?php echo format_date($invoice->getDueDate()) ?></td>
          <td class="link">
            <span class="status <?php echo ($stat = $invoice->getStatusString()) ?>">
              <?php echo __($stat) ?>
            </span>
          </td>
          <td class="due right link"><?php if($invoice->getDueAmount() != 0) echo format_currency($invoice->getDueAmount(), $currency)?></td>
          <td class="right link">
            <?php if ($invoice->getDraft()): ?>
              <span class="draftAmount" title="<?php echo __('This amount is not reflected in the total') ?>"></span>
            <?php endif?>
            <?php echo format_currency($invoice->getGrossAmount(), $currency)?>
          </td>
          <td class="action payments">
            <?php echo gButton(__("Payments"), "id=load-payments-for-$id rel=payments:show type=button class=payment action-clear {$invoice->getStatus()}") ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <?php if ($recentCounter > $maxResults): ?>
    <tfoot>
      <tr>
        <td colspan="9">
          <small>
            <?php echo link_to(__('view all  ([1] invoices)', array('[1]' => $recentCounter)), '@invoices') ?>
          </small>
        </td>
      </tr>
    </tfoot>
    <?php endif; ?>
  </table>
  
  
  <h2><?php echo __('Past due invoices') ?></h2>
  <table class="listing">
    <thead>
      <tr>
        <th class="number"><?php echo __('Number') ?></th>
        <th><?php echo __('Customer Name') ?></th>
        <th><?php echo __('Date') ?></th>
        <th><?php echo __('Due Date') ?></th>
        <th class="right due"><?php echo __('Due') ?></th>
        <th class="right total"><?php echo __('Total') ?></th>
        <th class="noborder"></th>
      </tr>
    </thead>
    <tbody>
      <?php $total = 0; ?>
      <?php foreach ($overdue as $i => $invoice): ?>
        <?php
          $id       = $invoice->getId();
          $parity   = ($i % 2) ? 'odd' : 'even';
          $closed   = ($invoice->getStatus() == Invoice::CLOSED);
        ?>
        <tr id="overdue-<?php echo $id ?>" class="<?php echo "$parity link invoice-$id " ?>">
          <td class="number"><?php echo $invoice ?></td>
          <td><?php echo $invoice->getCustomerName() ?></td>
          <td class="date"><?php echo format_date($invoice->getIssueDate()) ?></td>
          <td class="date"><?php echo format_date($invoice->getDueDate()) ?></td>
          <td class="due right"><?php echo format_currency($invoice->getDueAmount(), $currency)?></td>
          <td class="right"><?php echo format_currency($invoice->getGrossAmount(), $currency) ?></td>
          <td class="action payments">
            <?php echo gButton(__("Payments"), "id=load-payments-for-$id rel=payments:show type=button class=payment action-clear {$invoice->getStatus()}") ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  
</div>
