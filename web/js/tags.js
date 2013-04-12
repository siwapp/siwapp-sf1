/*
Requirements:

- An input hidden tag to apply the function
- An autocompletionUrl
- A tagsContainer ID
- A tagTemplate string with #{tag} to replace

*/

(function($){
  
  $.fn.tagSelector = function () {
    var options = $.extend({
      autocompletionUrl : false,
      tagsContainer     : null,
      tagTemplate       : ''
    }, arguments[0]||{});
    var o  = $(this);
    if (!o.is(':hidden') || (o.attr('id') == undefined))
      throw "You need a hidden input control with an ID to create a tag selection control.";
    
    var id = o.attr('id');
    var t  = $('<input id="' + id + '_input" name="' + id + '_input" type="text" class="disableHotKeys" />');
    
    t.insertBefore(o);
    t.keydown(function(e) { if (e.keyCode == 13) e.preventDefault(); })
      .bind('ComputeTags', function(e) {
        var data = (arguments[1]||$(this).val()).replace(';', ',').split(',');
        var tag, tags = o.val().length ? o.val().split(',') : [];
        var r;
        for (var i = 0; i < data.length; i++) {
          tag = $.trim(data[i].toLowerCase());
          if (tag.length) {
            r = new RegExp('^' + tag + ',|[^a-z]' + tag + ',|,' + tag + '$');
            if (!o.val().match(r)) {
              tags.push(tag);
              o.val(tags.length > 1 ? tags.join(',') : tags[0]);
              $('#' + options.tagsContainer).append(options.tagTemplate.replace(/#{tag}/g, tag));
            }
          }
        }
        $(this).val('');
      });
    
    // Autocompletion
    if (options.autocompletionUrl)
    {
      var acOptions = $.extend({}, {
        dataType : 'json',
        parse    : function(data) {
          var parsed = [];
          for (key in data) {
            parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: data[key] };
          }
          return parsed;
        }
      });
      
      t.autocomplete(options.autocompletionUrl, acOptions, { minChars: 2, matchContains: true })
       .result(function(e, item) { t.trigger('ComputeTags', item[0]); });
    }
    
    // Delete
    $('#' + options.tagsContainer + ' .tag a.remove').live('click', function(){
      var tag = $($(this).parents('.tag').get());
      var val = tag.find(':hidden').val();
      o.val($.grep(o.val().split(','), function(n){ return (n != val); }).join(','));
      tag.remove();
    });
  };
  
})(jQuery);