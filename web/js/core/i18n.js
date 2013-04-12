function __(s)
{
  var key = '_' + md5(s) + '_';
  if (window.i18n && window.i18n[key]) {
    s = window.i18n[key];
  }
  
  return s;
}