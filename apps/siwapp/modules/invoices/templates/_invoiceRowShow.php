<?php use_helper('Number') ?>
<tr>
  <td>
    <?php echo $item->getDescription() ?>
  </td>
  <td class="right ucost">
    <?php echo format_currency($item->getUnitaryCost(), $currency) ?>
  </td>
  <td class="right quantity">
    <?php echo $item->getQuantity() ?>
  </td>
  <td class="right taxes_td">
    <?php foreach ($item->Taxes as $tax):?>
      <span><?php echo $tax->getName() ?></span>
    <?php endforeach?>
  </td>
  <td class="right discount">
    <?php echo ($item->getDiscount() > 0) ? $item->getDiscount()."%" : null; ?>
  </td>
  <td class="right price">
    <?php echo format_currency($item->getGrossAmount(), $currency) ?>
  </td>
</tr>
