<?php
/**
 * Printer class renders data into a single HTML string
 * It can render it to PDF too.
 *
 * Usage:
 *
 * $printer = new Printer('ModelClass', $templateForLoader);
 *
 * // loader is Twig_Loader_Database by default so $templateForLoader should be int
 * $data = array(array('a' => 1), array('a' => 2), ...);
 * $htm = $printer->render($data);
 * $pdf = $printer->renderPdf($data);
 *
 * @author Carlos Escribano <carlos@markhaus.com>
 */
class Printer
{
  protected
    $model,
    $template,
    $loader;
  
  /**
   * @param string Model name
   * @param mixed $template param needed by template loader
   */
  public function __construct($model, $template)
  {
    $this->model    = strtolower($model);
    $this->template = $template;
    $this->loader   = new Twig_Loader_Database();
  }
  
  public function render($data, $pdf = false)
  {
    $twig = new Twig_Environment($this->loader, array('cache'=>sfConfig::get('sf_cache_dir'), 'auto_reload'=>true));
    // coupled!
    $twig->addExtension(new Common_Twig_Extension());
    
    $tpl = $twig->loadTemplate($this->template);
    
    $head = null;
    $body = null;
    
    $max = count($data) - 1;;

    foreach ($data as $i => $dataUnit)
    {
      $tmp = $tpl->render(array(
        $this->model => $dataUnit,
        'settings'   => new SettingsTagsArray(), // coupled!
        'pdf'        => $pdf,
        ));

      if (!$i)
      {
        $head = preg_replace('/(<body>)(.*)(<\/body>.*<\/html>)/si', '$1', $tmp);
      }
      
      preg_match('/(<body>)(.*)(<\/body>)/si', $tmp, $matches);
      // Avoid a last blank page
      $body .= '<div class="page"'.($i < $max ? ' style="page-break-after:always"' : null).'>'.$matches[2].PHP_EOL.'</div>';
    }
    
    return $head.PHP_EOL.$body.PHP_EOL.'</body></html>';
  }
  
  public function renderPdf($data, $pageSize = 'a4', $pageOrientation = 'portrait')
  {
    $previous_error_reporting= ini_get('error_reporting');
    // to avoid the complaining for fixing DOMPDF_FONT_DIR at 
    // siwappConfiguration.class.php
    ini_set('error_reporting',E_ALL ^ E_NOTICE);
    $input_data = $this->render($data, true);
    sfCoreAutoload::getInstance()->unregister();
    sfAutoload::getInstance()->unregister();
    if (class_exists('sfAutoloadAgain'))
      sfAutoloadAgain::getInstance()->unregister();
    require_once(sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.
                 'plugins'.DIRECTORY_SEPARATOR.'sfDomPDFPlugin'.
                 DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.
                 'sfDomPDFPlugin.class.php');
    $q = new sfDomPDFPlugin($input_data);
    sfCoreAutoload::getInstance()->register();
    sfAutoload::getInstance()->register();
    if (class_exists('sfAutoloadAgain'))
      sfAutoloadAgain::getInstance()->register();
    $q->setProtocol('http://');
    $q->setHost($_SERVER['HTTP_HOST']);
    $q->setPaper($pageSize, $pageOrientation);
    $q->render();
    ini_set('error_reporting',$previous_error_reporting);

    return $q->getPdf();
  }
  
}