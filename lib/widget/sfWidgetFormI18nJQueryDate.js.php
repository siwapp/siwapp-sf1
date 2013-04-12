<script type="text/javascript">
(function($){
  
  function %s_read_linked()
  {
    var control = $("#%s");
    var day     = $("#%s").val();
    var month   = $("#%s").val();
    var year    = $("#%s").val();
    var date;
    
    if ((day + month + year).length) {
      date = new Date(year, month - 1, day);
    } else {
      date = new Date();
    }
    
    control.val($.datepicker.formatDate($.datepicker.regional['%s'].dateFormat, date));
    
    return {};
  }

  function %s_update_linked(date)
  {
    var dateObj = $.datepicker.parseDate($.datepicker.regional['%s'].dateFormat,date);

    $("#%s").val(parseInt(dateObj.getDate(), 10)); // day
    $("#%s").val(parseInt(dateObj.getMonth(), 10)+1); // month
    $("#%s").val(parseInt(dateObj.getFullYear(), 10)); // year
  }

  $("#%s").datepicker($.extend({}, {
    minDate:    new Date(%s, 1 - 1, 1),
    maxDate:    new Date(%s, 12 - 1, 31),
    beforeShow: %s_read_linked,
    onSelect:   %s_update_linked,
    showOn:     "both"
    %s
  }, $.datepicker.regional["%s"], %s));
  
})(jQuery);
</script>