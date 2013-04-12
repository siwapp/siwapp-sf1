<?php
/**
 * @author Carlos Escribano <carlos@markhaus.com>
 */
class sfWidgetFormI18nJQueryDate extends sfWidgetFormI18nDate
{
  /**
   * Configures the current widget
   *
   * @param array options
   *
   *        Available options:
   *        - culture >> the user culture
   *        - image   >> image file absolute web path to use as trigger
   *        - config  >> javascript object notation complain object with datepicker configuration
   *
   * @param array html attributes
   * @return void
   * @author Carlos Escribano <carlos@markhaus.com>
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    parent::configure($options, $attributes);
  }
  
  /**
   * Render html and javascript for current datepicker
   *
   * @param string datepicker name
   * @param mixed default value
   * @param array html attributes
   * @param array errors
   * @return string html and javascript
   * @author Carlos Escribano <carlos@markhaus.com>
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $sfContext  = sfContext::getInstance();
    $sfResponse = $sfContext->getResponse();
    
    $html = array();
    
    $culture = explode('_', $this->getOption('culture'));
    $culture = array_shift($culture);
    
    if ($culture != 'en')
    {
      $sfResponse->addJavascript("i18n/ui.datepicker-$culture.js", 'last');
    }
    
    $ids = array(
      'id'      => $this->generateId($name),
      'control' => $this->generateId($name).'_jquery_control',
      'day'     => $this->generateId($name."[day]"),
      'month'   => $this->generateId($name."[month]"),
      'year'    => $this->generateId($name."[year]")
    );
    
    $image = (false !== $this->getOption('image')) ? sprintf("showOn: 'button', buttonImage: '%s', buttonImageOnly: true", $this->getOption('image')) : null;
    $dp_culture = ($culture == 'en') ? '' : $culture;
    $uiJs  = file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.basename(__FILE__, ".php").".js.php");
    
    $html[] = parent::render($name, $value, $attributes, $errors);
    $html[] = $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $ids['control'], 'disabled' => 'disabled'));
    $html[] = sprintf($uiJs,
      $ids['id'], $ids['control'],
      $ids['day'], $ids['month'], $ids['year'], $dp_culture,
                      $ids['id'],$dp_culture,
      $ids['day'], $ids['month'], $ids['year'],
      $ids['control'],
      min($this->getOption('years')),
      max($this->getOption('years')),
      $ids['id'], $ids['id'],
      ", $image",
      $culture,
      $this->getOption('config')
    );
    
    return implode("\n", $html);
  }
  
}