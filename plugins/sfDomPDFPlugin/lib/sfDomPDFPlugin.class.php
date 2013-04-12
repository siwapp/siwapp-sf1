<?php
/**
 * The sfDomPDFPlugin uses the domPDF library to convert HTML documents to PDF
 * Documentation is located at http://trac.symfony-project.com/trac/wiki/sfDomPDFPlugin
 *
 */
class sfDomPDFPlugin
{
  /**
   * The relative path to the dompdf configuration file
   *
   * @var string
   */
  private $config_file = 'dompdf/dompdf_config.inc.php';
  
  /**
   * The variable that will hold our dompdf object
   *
   * @var object
   */
  private $dompdf = null;
      
  /**
   * Instantiates our domPDF class.  
   * This constructor will check for the presence of domPDF
   * You can optionally pass your HTML to this constructor
   *
   * @param string $input
   */
  public function __construct($input = null)
  {
    // If the configuration cannot be found, throw an error
    if (!file_exists($this->getConfigFile()))
    {
      throw new sfException('The domPDF configuration file could not be found in ' . $this->getConfigFile());
    }  
    
    // Include the configuration file

    require_once ($this->getConfigFile());

    // Instantiate the new DOMPDF class
    $this->setPDF(new DOMPDF());
    
    // If input is passed in the constructor, set it here
    if (!empty($input)) $this->setInput($input);
    
    // Set the initial paper settings
    $this->setPaper();
  }

  /**
   * Set the path to the domPDF configuration file
   *
   * @param string $config_file
   */
  public function setConfigFile($config_file)
  {
    $this->config_file = $config_file;
  }
  
  /**
   * Check to see if this system is running a Windows platform
   *
   * @return bool
   */
  public function isWindows()
  {
  	return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? true : false;
  }
  
  /**
   * Gets the absolute path to the configuration file
   *
   * @return string
   */  
  public function getConfigFile()
  {
  	$isWin = $this->isWindows();
  	
    if ((!$isWin && substr($this->config_file, 0, 1) !== '/') || ($isWin && substr($this->config_file, 1, 1) !== ':'))
    {
      $this->setConfigFile(realpath(dirname(__FILE__)) . '/' . $this->config_file);
    }
    
    return $this->config_file;
  }  	
    
  /**
   * Sets the dompdf object
   *
   * @param object $object
   */
  public function setPDF($object)
  {
    $this->dompdf = $object;
  }
  
  /**
   * Returns the domPDF object
   *
   * @return object
   */
  public function getPDF()
  {
    return $this->dompdf;
  }
  
  /**
   * The HTML input that will be converted to PDF
   * You can optionally include an actual file instead of a string
   *
   * @param string $input
   * @param boolean $is_string
   */
  public function setInput($input, $is_string = true)
  {
    if ( (bool) $is_string === true)
    {
      $this->getPDF()->load_html($input);
    }
    else 
    {
      $this->getPDF()->load_html_file($input);
    }
  }
  
  /**
   * Define either http:// or https:// to access your host
   *
   * @param string $protocol
   */
  public function setProtocol($protocol)
  {
    $this->getPDF()->set_protocol($protocol);
  }
  
  /**
   * The URL to the site which hosts your CSS and images
   * Do not include http:// or https://
   *
   * @param string $host
   */
  public function setHost($host)
  {
    $this->getPDF()->set_host($host);
  }
  
  /**
   * Sets the base path for which to look for CSS files and images
   *
   * @param string $base_path
   */
  public function setBasePath($base_path)
  {
    $this->getPDF()->set_base_path($base_path);
  }

  /**
   * Define the type of paper and orientation for domPDF to use
   *
   * @param string $paper
   * @param string $orientation
   */
  public function setPaper($paper = 'letter', $orientation = 'portrait')
  {
    $this->getPDF()->set_paper($paper, $orientation);
  }
    
  /**
   * Converts the HTML string into a PDF
   *
   * @return binary
   */
  public function render()
  {
    return $this->getPDF()->render();
  }
  
  /**
   * Generates the PDF file based on our input
   *
   * @return binary
   */
  public function execute()
  {
    // Render the output to PDF    
    $this->render();
    
    return $this->getPDF()->output();
  }
}