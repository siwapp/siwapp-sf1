<div id="customer-data" class="global-data block">
  <h3><?php echo __('Client info') ?></h3>
  <ul>
    <li>
      <span class="_75"><?php echo render_tag($invoiceForm['customer_name'])?></span>
      <span class="_25"><?php echo render_tag($invoiceForm['customer_identification'])?></span>
    </li>
    <li>
      <span class="_50"><?php echo render_tag($invoiceForm['contact_person'])?></span>
      <span class="_50"><?php echo render_tag($invoiceForm['customer_email'])?></span>
    </li>
    <li>
      <span class="_50"><?php echo render_tag($invoiceForm['invoicing_address'])?></span>
      <span class="_50"><?php echo render_tag($invoiceForm['shipping_address'])?></span>
    </li>
  </ul>
</div>
<?php
use_helper('JavascriptBase');

$urlAjax = url_for('common/ajaxCustomerAutocomplete');
echo javascript_tag("
  $('#".$invoiceForm['customer_name']->renderId()."')
    .autocomplete('".$urlAjax."', jQuery.extend({}, {
      dataType: 'json',
      parse:    function(data) {
        var parsed = [];
        for (key in data) {
          parsed[parsed.length] = { data: [ data[key].customer, 
            data[key].customer_identification, 
            data[key].contact_person,
            data[key].customer_email,
            data[key].invoicing_address,
            data[key].shipping_address
          ], value: data[key].customer, result: data[key].customer };
        }
        return parsed;
      },
      minChars: 2,
      matchContains: true
    }))
    .result(function(event, item) {
      $('#".$invoiceForm['customer_identification']->renderId()."').val(item[1]);
      $('#".$invoiceForm['contact_person']->renderId()."').val(item[2]);
      $('#".$invoiceForm['customer_email']->renderId()."').val(item[3]);
      $('#".$invoiceForm['invoicing_address']->renderId()."').val(item[4]);
      $('#".$invoiceForm['shipping_address']->renderId()."').val(item[5]);
    });
");

$isnew = $invoiceForm->isNew()?'true':'false';
echo javascript_tag(" $('#customer-data input[type=text], #customer-data textarea, #recurring-data input[type=text], #recurring-data select').SiwappFormTips({is_new:$isnew});") // See invoice.js

?>