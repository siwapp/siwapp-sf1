<?php use_helper('jQuery', 'JavascriptBase')?>
<span id="tax_<?php echo $rowId?>_<?php echo $taxKey?>"><?php 
echo jq_link_to_function('',
"$('#tax_".$rowId."_".$taxKey."').remove(); $(document).trigger('GlobalUpdateEvent');")?>
<select class="observable tax" id="item_taxes_list_<?php echo $rowId?>_<?php echo $taxKey?>" name="invoice[Items][<?php echo $rowId?>][taxes_list][]">
  <?php 
    $taxes = Doctrine::getTable('Tax')->createQuery()->where('id = ?', $taxKey)->orWhere('active = ?', 1)->execute();
    foreach($taxes as $o_tax):?>
  <option value="<?php echo $o_tax->id?>" <?php echo $o_tax->id == $taxKey ? 'selected="selected"':''?>>
    <?php echo $o_tax->name?>
  </option>
  <?php endforeach?>
</select>
</span>
