var Tools = {};

(function($){
  
  /*
  Looks for a value matching <string>-<integer>, like in "invoice-74"
  and gets the numerical part (74).
  */
  Tools.getStringId = function(s) {
    var ok = true, id = s.match(/-(\d+)/);
    try {
      id = id.pop();
    } catch(e) {
      ok = false;
    }
    if (!ok || isNaN(id))
      throw __("Numeric id not found within") + ' "' + s + '"';
    return id;
  };
  
  /*
  Opens a popup window with the specified URL in it.
  As second argument you can set an object with the popup properties.
  */
  Tools.popup = function(url) {
    var settings = $.extend({
      name        : 'popup',
      width       : 960,
      height      : 700,
      menubar     : 'no',
      status      : 'no',
      location    : 'no',
      directories : 'no',
      copyhistory : 'no',
      scrollbars  : 'yes'
    }, arguments[1] || {});
    
    var options = [];
    for (key in settings)
      if (key != 'name')
        options.push(key + '=' + settings[key]);
    var w = window.open(url, settings.name, options.join(','));
    if (w && !w.closed)
      w.focus();
    return w;
  };
  
  /*
  Resets all the <selector> descendants. Optionally you can pass as second argument
  a "not" selector to exclude items from the reset action.
  */
  Tools.resetFields = function(selector) {
    var not = arguments[1] || false;
    var items = $(selector).find(':input, :text, :password, :radio, :checkbox, :image, :file');
    if (not)
      items = items.not(not);
    items.val(null);
  };
  
  /**
   * SelectableTag
   * Carlos Escribano Rey <carlos@markhaus.com>
   *
   * $('my_selector_to_get_all_tag_nodes').SelectableTag({
   *   output    : 'input_tag_id',
   *   classname : 'selected_status_CSS_class'
   * });
   *
   * <input type="hidden" id="tags" name="tags" value="" />
   * ...
   * <span class="tag">value1</span>
   * <span class="tag">value2</span>
   * ...
   * $('span.tag').SelectableTag();
   * ...
   *
   * If you click on tags with "value1" and "value2" values:
   * <input type="hidden" id="tags" name="tags" value="value1,value2" />
   * <span class="tag selected">value1</span>
   * <span class="tag selected">value2</span>
   */
  $.fn.SelectableTag = function() {
    var opt = $.extend({
      output    : '#tags',
      classname : 'selected'
    }, (arguments[0]||{}));

    $(this).click(function() {
      var r = $(opt.output);
      var t = $(this);
      var v = r.attr('value');

      if (t.toggleClass(opt.classname).hasClass(opt.classname)) {
        v = v + ',' + t.html();
      } else {
        v = v.replace(t.html(), '');
      }
      v = v.replace(/^,,*|,,*$/, '').replace(',,', ',');
      r.attr('value', v);
    });
  };
  
  /**
   * formFixEnterKeyBehavior
   * Carlos Escribano Rey <carlos@markhaus.com>
   */
  $.fn.formFixEnterKeyBehavior = function () {
    if ($(this).is('form') && window.hotkeys) {
      var options = $.extend({
        exclude: null
      }, arguments[0]||{});
      
      var selector = ':input:not(button):not(textarea)';
      
      if (options.exclude) {
        selector = selector + ':not(' + options.exclude + ')';
      }
      
      // When the user press ENTER on a field she "confirms" it (although is not actually saved) instead of submitting the whole form.
      $(this).find(selector).bind('keydown', 'return', function(e){
        $(this).blur().moveNext();
        return false;
      });
      
      $(this).find('textarea').bind('click', function(){
        $(this).attr('clicked_on', 1);
      });
      
      $(this).find('textarea').bind('focus, blur', function() {
        $(this).attr('ftek_changed', 0);
      }).bind('keydown', function(e) {
        if (0 > $.inArray(e.keyCode, [16, 17, 27, 9, 13, 145, 20, 144, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123])) {
          $(this).attr('ftek_changed', 1);
        } else if (e.keyCode == 13) {
          if (!$(this).attr('ftek_changed') && !$(this).attr('clicked_on')) {
            // If the field did not change, and the user didn't clicked on it, 
            // the ENTER key moves to the next field; otherwise insert a new line.
            $(this).blur().moveNext();
            return false;
          }
        }
      });
      
    } else {
      throw "formFixEnterKeyBehavior should be applied in a form element and requires jquery.hotkeys plugin";
    }
  };
  
})(jQuery);
