window.Payments = {
  nextIndex : 1
};

jQuery(function($){
  
  if (window.siwapp_urls.paymentsForm)
  {
    
    // Show/Hide payments row
    $('tr.link [rel=payments:show]').click(function(e){
      e.preventDefault();
      var tr = $(this).closest('tr.link');    // row
      var id = Tools.getStringId(tr.attr('id')); // row ID

      if (tr.next('tr').hasClass('payments-row'))
      {
        tr.next('tr').remove();
      }
      else
      {
        var td = $('<td class="payments-form-container">')
          .attr('colspan', tr.children('td').length)
          .append('<div class="ajaxBar">');
        tr.after($('<tr class="payments-row" style="display:none;">').append(td).show());
        td.load(window.siwapp_urls.paymentsForm + '?invoice_id=' + id);
      }
    });
    
    if (window.siwapp_urls.addPayment)
    {
      
      // "Add payment" button
      $('.payments-row [rel=payments:add]').live('click', function(e) {
        e.preventDefault();
        
        // find the layer with payments
        var tr         = $(this).closest('.payments-row');
        var container  = $(this).closest('.payments-row').find('.payments');
        var invoice_id = tr.find('input.invoice_id').val();

        $.post(window.siwapp_urls.addPayment, { index: window.Payments.nextIndex, invoice_id: invoice_id },
          function (data, status) {
            container.append(data);
            window.Payments.nextIndex++;
          }
        );
      });
      
    }
    
    /* "Cancel" button */
    $('.payments-row [rel=payments:cancel]').live('click', function(e) {
      e.preventDefault();
      $(this).closest('tr.payments-row').remove();
    });

    /* Remove payment link*/
    $('.payments-row a.xit').live('click', function(e) {
      e.preventDefault();
      var p = $(this).closest('ul');
      p.find('input.remove').val(1);
      p.parent().hide();
    })
  }
  
});