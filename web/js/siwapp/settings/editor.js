(function($){
  
  window.editarea_save_callback = function(id, text)
  {
    $('#'+id).val(text).closest('form').submit();
  }
  
})(jQuery);