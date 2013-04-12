<?php

class siwappConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
    if (!defined('DOMPDF_FONT_DIR'))
      define("DOMPDF_FONT_DIR", sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'fonts/');
    
    if (!defined('DOMPDF_FONT_CACHE'))
      define("DOMPDF_FONT_CACHE", sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.'pdf_fonts_cache');
    
    if (!defined('DOMPDF_ENABLE_REMOTE'))
      define("DOMPDF_ENABLE_REMOTE", true); // needed to load logo images in pdfs
    
    if (!defined('DOMPDF_LOG_OUTPUT_FILE'))
      define("DOMPDF_LOG_OUTPUT_FILE", false); // disable logging
  }
}
