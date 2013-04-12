<?php
sfContext::getInstance()->getConfiguration()->loadHelpers('JavascriptBase');

class sfWidgetFormEditArea extends sfWidgetFormTextarea
{
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addRequiredOption('editarea_options');
  }
  
  /**
   * Don't forget to include <?php include_javascripts_for_form($form) ?> in your template!
   */
  public function getJavascripts()
  {
    return array('/js/core/editarea/edit_area_compressor.php', '/js/core/editarea.jquery.js');
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = $this->generateId($name);
    
    $options = array();
    foreach ((array) $this->getOption('editarea_options') as $key => $val)
    {
      if (is_bool($val))
      {
        $options[] =  "$key: ".($val ? 'true' : 'false');
      }
      else
      {
        $options[] =  "$key: '$val'";
      }
    }
    $options = '{'.implode(', ', $options).'}';
    
    $contentTag = parent::render($name, $value, $attributes, $errors);
    $contentTag.= javascript_tag("
      if($.fn.editArea)
        $('#$id').editArea($options);
      else
        throw 'EditArea JS not included!';
    ");
    
    return $contentTag;
  }
}
