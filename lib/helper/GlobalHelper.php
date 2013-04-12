<?php
/**
 * Returns semantic css classes for an sfFormField field (or a wrapper for it) that has errors.
 * @param sfFormField or wrapper
 * @return string
 * @author Carlos Escribano <carlos@markhaus.com>
 */
function error_class($field)
{
  return $field->hasError() ? 'error wrong' : null;
}

/**
 * returns semantyc class names for the body tag
 *
 * @return string
 * @author Carlos Escribano <carlos@markhaus.com>
 **/
function semantic_body_classes()
{
  $sf_context   = sfContext::getInstance();
  $sf_semantics = sfConfig::get('app_semantics',false);
  $classes      = array();
  
  in_array($tmp = strtolower($sf_context->getModuleName()), $sf_semantics ? $sf_semantics['forbidden']['modules'] : array()) or $classes[] = $tmp;
  in_array($tmp = strtolower($sf_context->getActionName()), $sf_semantics ? $sf_semantics['forbidden']['actions'] : array()) or $classes[] = $tmp;
  
  return implode(' ', array_unique($classes));
}

/** 
 * Returns a sfFormField rendered according to Siwapp specs. that is:
 * @param sfFormField or wrapper
 * @param array html attributes
 * @return string
 * @author JoeZ <jzarate@gmail.com>
 */
function render_tag($field, $attrs = array())
{
  $attrs['class'] =  isset($attrs['class']) ? $attrs['class'].' '.error_class($field) : error_class($field);
  $attrs['title']  = $field->renderHelp();
  return $field->render($attrs).$field->renderError();
}
