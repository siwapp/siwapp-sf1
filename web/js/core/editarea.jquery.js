(function($){
  
  // editAreaLoader jQuery wrapper
  $.fn.editArea = function (options) {
    return $(this).each(function(){
      if ('undefined' != $(this).attr('id')) {
        editAreaLoader.init($.extend(options, { id: $(this).attr('id') }));
      }
    });
  };
  
})(jQuery);