(function($){
  
  // ---------- SiwappFormTips ------------------------------
  
  /*
    Adds a label tag with a 'tip' class before any element with an id and a title attributes and,
    if wrapper tag provided, wraps both into a relative positioned tag. If not, their parent tag
    is positioned relative. You can set not to position parent if you want. You can also change
    default tip class name.
    
    Options & defaults:
    - wrapper   : null  (no wrapper)
    - relative  : true  (parent will be positioned as 'relative')
    - classname : 'tip' (label class name)
    - test      : false (if true, sets a title for all items to test behavior)
    - is_new    : false (if true, the form is not for editing db object)
    
    <carlos@markhaus.com>
  */
  $.fn.SiwappFormTips = function(options) {
    var parent, options = $.extend({
      wrapper: null,
      relative: true,
      classname: 'tip',
      test: false,
      is_new: false
    }, options || {});
    
    if (options.test)
    {
      this.attr('title', 'Lorem ipsum et dolor sit amet');
    }
    
    return this.each(function (i) {
      if (this.id && this.title)
      {
        if (options.wrapper)
        {
          parent = $(this).wrap('<' + options.wrapper.toLowerCase() + '></' + options.wrapper.toLowerCase() + '>').parent();
        }
        else
        {
          parent = $(this).parent();
        }
        
        if (options.relative)
        {
          parent.css('position', 'relative');
        }
        
        var label = $('<label id="' + this.id + '_label" for="' + this.id + '" class="tip" style="display:none;">' + this.title + '</label>');
        label.css({left : '0px', top : $(this).height() + 'px'});
        $(this).before(label);
        
        $(this).bind('focus', function(e) {
          //$(this).select(); // select text (to work also in textareas)
          $('#' + this.id + '_label').fadeIn('fast');
            if(options.is_new && $(this).val()==this.title)
            {
                $(this).val('');
            }
        }).bind('blur', function(e){
          //$(this).val($(this).val()); // Unselect text (the same as before)
          $('#' + this.id + '_label').fadeOut('fast');
        });
      }
    });
  };
  
  $.fn.saveInvoiceAsDraft = function() {
    if ($(this).is('form.invoice'))
    {
      $(this).find('input[name*=draft]:first').val(1);
      return this.submit();
    }
  };
  
  $.fn.saveInvoiceAndEmail = function() {
    if ($(this).is('form.invoice'))
    {
      $('#send_email').val(1);
      return this.submit();
    }
  };
  
  $.fn.setValueAndSendForm = function(selector, value) {
    if ($(this).is('form.invoice'))
    {
      $(selector).val(value);
      return this.submit();
    }
  };
  
  // ---------- Invoicing interactions ----------------------
  
  $(document).ready(function() {
    
    // We unbind all 'observable' events at page unload.
    $(window).bind("unload", function() {$('.observable').die('change');});
    // Listen for interface changes
    $(document).bind('GlobalUpdateEvent', function() {
      var form = $('form.invoice:first');
      $.post(
       //common/calculate
        window.siwapp_urls.calculateInvoice,  // url
        form.serialize(),    // data
        function(data, status) {
          if ('success' == status) {
            var tr;
            for (id in data.items) { // Update the "price" cell for the row
              form.find('tr[id$=' + id + ']:first td.price:first').html(data.items[id]);
            }
            for (classname in data.totals) { // Update totals: base, discount, net, taxes, gross
              form.find('tfoot td.' + classname + ':first').html(data.totals[classname]);
            }
          }
        },
        'json'
      );
      if (!form.find('tbody tr').length)
      {
        form.find('tbody').append('<tr class="fake"><td colspan="6"></td></tr>');
      }
    });
    
    // Listen for a change event triggered for 'observable' elements
    // (it binds also for dynamically generated 'observable' elements)
    $('.observable').live('change', function() {
      $(document).trigger('GlobalUpdateEvent');
    });

    
    
    // Select text on focus and unselect it on blur
      $('form.invoice').filter(':input:not(button)').bind('focus', function(e) {$(this).select();}).bind('blur', function(e) {$(this).val($(this).val());});
    
    if (window.hotkeys)
    {
      // On AJAX completion refresh events
      $(document).bind('ajaxComplete', function(){
        
        $('form.invoice').formFixEnterKeyBehavior({exclude: '.disableHotKeys'}); // core/tools.js
        
      }).trigger('ajaxComplete');
    }
  });
  
})(jQuery);
