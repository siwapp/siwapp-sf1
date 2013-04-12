jQuery(function($){
  
  $('#password-trigger').click(function(e){
    e.preventDefault();
    var bd = $('#bd-login-form');
    if (bd.hasClass('with-password'))
      return false;
    bd.addClass('with-password');
    var pf = $('#password-form');
    var _m = '-' + parseInt((bd.outerHeight() + pf.outerHeight())/2) + 'px';
    bd.animate({ marginTop: _m }, 150, function(){ pf.fadeIn(150); });
  });

  $('#password-close').click(function(e){
    e.preventDefault();
    var bd = $('#bd-login-form');
    var pf = $('#password-form');
    var _m = '-' + parseInt((bd.outerHeight() + 10 - pf.outerHeight())/2) + 'px';
    pf.fadeOut(150, function(){ bd.animate({ marginTop: _m }, 150); });
    bd.removeClass('with-password');
  });
  
  var bd = $('#bd-login-form');
  bd.css({ marginTop: '-175px' }).fadeIn(150);
});