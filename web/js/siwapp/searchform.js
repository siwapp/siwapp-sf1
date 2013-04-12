(function($){
  
  $.fn.SearchForm = function() {
    var settings = $.extend({
      
    }, arguments[0]||{});
    
    $(this).each(function(){
      var f  = $(this);
      var id = f.attr('id');
      
      // Toggle tag cloud trigger
      f.find('.toggleTagCloud:first').bind('click', { form: f }, function(e){
        e.preventDefault();
        var btn = $(this);
        var img = btn.find('img');
        var frm = e.data.form;
        
        $.get(siwapp_urls.toggleTagCloud);
        btn.toggleClass('tags-selected');
        frm.parent().find('.tagselect').toggle();
        img.attr('src', img.attr('src').replace(/contract|expand/, btn.hasClass('tags-selected') ? 'contract' : 'expand'));
      });
      
      // Selectable tags
      f.parent().find('.tagselect:first span.tag')
        .SelectableTag({
          output: '#' + id + ' input[name=search[tags]]'
        });
      
      // Form reset button
      f.find('button[type=reset]:first').bind('click', { form: f }, function(e){
        e.preventDefault();
        var frm = e.data.form;
        Tools.resetFields(frm);
        frm.prepend($('<input type="hidden" name="reset" value="1" />'));
        frm.parent().find('.tagselect span').removeClass('selected');
        frm.submit();
      });
      
      // Status filters
      f.find('ul.filters a.status').bind('click', { form: f }, function(e){
        e.preventDefault();
        var frm = e.data.form;
        var status = $(this).attr('class').match(/#(.*)#/).pop();
        frm.find('input[name=search[status]]').val(status);
        frm.submit();
      });
      
      // Quick Dates
      f.find('select[name=search[quick_dates]]').bind('change', { form: f }, function(e){
        var frm = e.data.form;
        var val = $(this).val().toLowerCase();
        var mod, to, from;
        
        // function to get the monday date of the week
        function getMonday(d) {
          var day = d.getDay(),
              diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
          return new Date(d.setDate(diff));
        }
        
        
        if (!val) {
            frm.find('#search_to_year').val('');
            frm.find('#search_to_month').val('');
            frm.find('#search_to_day').val('');
            
            frm.find('#search_from_year').val('');
            frm.find('#search_from_month').val('');
            frm.find('#search_from_day').val('');
        }
        
        else {
          to   = $('#search_to_jquery_control').datepicker('setDate', new Date()).datepicker('getDate');
          
          switch(val) {
            case 'last_week'    : mod = '-7';  break;
            case 'last_month'   : mod = '-1m'; break;
            case 'last_year'    : mod = '-1y'; break;
            case 'last_5_years' : mod = '-5y'; break;
            case 'this_week':
                mod = getMonday(new Date()); 
                break;
            case 'this_month':
                mod = new Date();
                mod.setDate(1);
                break;
            case 'this_year':
                mod = new Date();
                mod.setDate(1);
                mod.setMonth(0);
                break;
            default: 
                mod = null; 
                break;
          }
          from = $('#search_from_jquery_control').datepicker('setDate', mod).datepicker('getDate');
          
          to   = $.datepicker.formatDate('yy-mm-dd', to).split('-');
          from = $.datepicker.formatDate('yy-mm-dd', from).split('-');
          
          to[1]   = to[1].replace(/^0{0,1}/, '');
          from[1] = from[1].replace(/^0{0,1}/, '');
          
          // Temporary solution while I find how to update them directly.
          frm.find('#search_to_year').val(parseInt(to[0]));
          frm.find('#search_to_month').val(parseInt(to[1]));
          frm.find('#search_to_day').val(to[2]);
          
          frm.find('#search_from_year').val(parseInt(from[0]));
          frm.find('#search_from_month').val(parseInt(from[1]));
          frm.find('#search_from_day').val(from[2]);
        }
      });
      
    });
  };
  
  $(function(){
    $('form.searchform').SearchForm();
  });
})(jQuery);

// this is to replace the customer_id value with the customer name in autocomplete_search_customer_id
$(document).ready(function() {
  if(typeof(window.customer_name_autocomplete) != 'undefined')
  {
    $('#autocomplete_search_customer_id').val(customer_name_autocomplete);
  }
});



