(function($){
  
  // "select all" checkboxes should have rel="all"
  // "select this row" checkboxes should have rel="item"
  // Apply to table elements or it will throw an exception
  $.fn.selecTable = function(){
    var options = $.extend({
      classname: 'selected'
    }, arguments[0]||{});
    
    // Check/Unckeck all clicked: add/remove "selected" class to items
    $(this).find('input:checkbox[rel=all]').click(function(e){
      var t = $(this).closest('table');
      var tr = t.find('input:checkbox[rel=item]').closest('tr');
      t.find('input:checkbox[rel=item], input:checkbox[rel=all]').attr('checked', this.checked);
      if (this.checked)
        tr.addClass(options.classname);
      else
        tr.removeClass(options.classname);
    });
    
    // Check/Uncheck item clicked: add/remove "selected" class to it.
    $(this).find('input:checkbox[rel=item]').click(function(e){
      var t = $(this).closest('table');
      var tr = $(this).closest('tr');
      var n = t.find('input:checkbox[rel=item]:not(:checked)').length;
      t.find('input:checkbox[rel=all]').attr('checked', n == 0);
      if (this.checked)
        tr.addClass(options.classname);
      else
        tr.removeClass(options.classname);
    });
    
    return $(this);
  };
  
  // Apply to TR elements only
  $.fn.rowClick = function(f){
    var options = $.extend({
      preventOn: 'a, button, input'
    }, arguments[1]||{});
    var tr = $(this).filter('tr');
    tr.click(f).find(options.preventOn).click(function(e){ e.stopPropagation(); });
    return $(this);
  };
  
  // Apply to ANY elements
  $.fn.hoverize = function(){
    var options = $.extend({
      classname: 'hover'
    }, arguments[0]||{});
    
    return $(this).mouseover(function(){
      $(this).addClass(options.classname);
    }).mouseout(function(){
      $(this).removeClass(options.classname);
    });
  };
  
})(jQuery);

function do_batch(action)
{
  var n = $('.listing input:checkbox[rel=item]:checked')
    .map(function(){ return $(this).val(); })
    .get();
  
  if (n.length) {
    $('#batch_action').val(action);
    $('#batch_form').submit();
  } else {
    alert(__('No selection. Nothing to do.'));
  }
}

jQuery(function($){
  // Link to edit in every row of the table
  if (window.siwapp_urls.editRow)
  {
    tr = $('.listing').find('tbody tr.link');
    tr.rowClick(function(e){
      var id = Tools.getStringId($(this).attr('id'));
      document.location.href = window.siwapp_urls.editRow + '/' + id;
    });
  }
  
  var tb = $('.listing');

  // Selection interaction through checkboxes
  // :checkbox[rel=all]  --> select all
  // :checkbox[rel=item] --> select current row
  tb.selecTable();
  
  function getSelected()
  {
    n = $('.listing input:checkbox[rel=item]:checked')
      .map(function(){ return $(this).val(); })
      .get();
      
    return n;
  }
  
  // Print or generate pdf for selected rows (:checkbox[rel=item]:checked)
  if (window.siwapp_urls.printHtml)
  {
    tb.find('[rel=print:html]').click(function(e){
      e.preventDefault();
      var u = window.siwapp_urls.printHtml;
      var n = getSelected();
      
      if (n.length)
        Tools.popup(u + '?' + 'ids[]=' + n.join('&ids[]='));
      else
        alert(__('No selection. Nothing to do.'));
    });
  }
  
  if (window.siwapp_urls.printPdf)
  {
    tb.find('[rel=print:pdf]').click(function(e){
      e.preventDefault();
      var u = window.siwapp_urls.printPdf;
      var n = getSelected();
      
      if (n.length)
        window.location=u + '?' + 'ids[]=' + n.join('&ids[]=');
      else
        alert(__('No selection. Nothing to do.'));
    });
  }
  
  // Generic batch actions
  tb.find('[rel^=batch:]').click(function(e){
    e.preventDefault();
    do_batch($(this).attr('rel').split(':')[1].toLowerCase());
  });
});