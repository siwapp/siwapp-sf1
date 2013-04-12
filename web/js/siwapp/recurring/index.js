jQuery(function($){
  
  $('#pendingButton').click(function(e){
    $(this).before($('<div>').addClass('ajaxBar')).remove();
  });
  
});