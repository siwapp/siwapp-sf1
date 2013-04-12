jQuery(function($){
  
  /**
   * Language selector
   * Requirements:
   * - Language select tag with id "config_language"
   * - Container with id "country_container"
   * - siwapp_urls global variable with getCountries attribute with a valid URL
   */
  $('#config_language').change(function(e) {
    $('#country_container').load(window.siwapp_urls.getCountries + '?language=' + $(this).val());
  }).change();
  
});
