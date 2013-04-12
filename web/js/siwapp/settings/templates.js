jQuery(function($){
  
  var tb = $('table#listing').selecTable();
  
  if (window.siwapp_urls.editRow)
  {
    tb.find('tr.template').rowClick(function(e){
      var id = Tools.getStringId($(this).attr('id'));
      document.location.href = window.siwapp_urls.editRow + '?id=' + id;
    });
    
    tb.find('[rel=templates:add]').click(function(e){
      e.preventDefault();
      document.location.href = window.siwapp_urls.editRow;
    });
  }
  
  if (window.siwapp_urls.deleteAction)
  {
    tb.find('[rel=templates:delete]').click(function(e){
      e.preventDefault();
      var frm = $(this).closest('form');
      if (frm.find('input:checkbox[rel=item]:checked').length)
        frm.attr('action', window.siwapp_urls.deleteAction).submit();
    });
  }
  
});