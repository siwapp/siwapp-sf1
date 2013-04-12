<?php

/**
 * gButton
 * Returns a Google Styled Button (<button/> or <a/>);
 * 
 * @author Carlos Escribano
 *
 * @param string Button text
 * @param array  Classic HTML options
 * @param array  Button options
 * 
 * Button options:
 * - button:  boolean; if true uses a button tag (<button/>)
 * - primary: boolean; if true the text appears bolded
 * - pill:    left|center|right; Button belongs to a group with "pill" (<button/> buttons).
 */
function gButton($text, $html_options = null, $button_options = array())
{
  $class = array('btn');
  
  $html_options   = _parse_attributes($html_options);
  $button_options = _parse_attributes($button_options);
  
  if (isset($button_options['button']) && $button_options['button'] == true)
  {
    $tag = 'button';
    
    if (!isset($html_options['type']))
    {
      $html_options['type'] = 'button';
    }
  }
  elseif (isset($html_options['type']))
  {
    $tag = 'button';
  }
  else
  {
    $tag = 'a';
  }
  
  if (isset($button_options['pill']))
  {
    $old = $tag;
    $tag = 'button';
    
    switch($button_options['pill'])
    {
      case 'left':
        $class[] = 'pill-l';
        break;
      case 'center':
        $class[] = 'pill-c';
        break;
      case 'right':
        $class[] = 'pill-r';
        break;
      default:
        $tag = $old;
        break;
    }
  }
  
  if (isset($button_options['primary']) && $button_options['primary'] == true)
  {
    $class[] = 'primary';
  }
  
  if (isset($html_options['class']))
  {
    $class = array_merge($class, (array) $html_options['class']);
  }
  
  $html_options['class'] = implode(' ', $class);
  
  if (isset($html_options['href']))
  {
    if ($tag != 'button')
    {
      $html_options['href'] = url_for($html_options['href']);
    }
    else
    {
      $html_options['onclick'] = "document.location.href='".url_for($html_options['href'])."';";
      $html_options['href']    = '#';
    }
  }
  else if (isset($html_options['onclick']) && $tag == 'a')
  {
    $html_options['href'] = '#';
  }

  $html = content_tag($tag, content_tag('span', content_tag('span', $text)), $html_options);
  
  return $html;
}

function gButton_to($text, $link = null, $html_options = null, $button_options = array())
{
  $html_options = _parse_attributes($html_options);
  $html_options['href'] = $link;
  if(array_key_exists('confirm', $html_options))
  {
    $txt = $html_options['confirm'];
    $html_options['onclick'] = "return confirm('$txt');";
  }

  return gButton($text, $html_options, $button_options);
}

function gButton_to_function($text, $javascript = null, $html_options = null, $button_options = array())
{
  $html_options = _parse_attributes($html_options);
  if(array_key_exists('confirm', $html_options))
  {
    $txt = $html_options['confirm'];
    $javascript = sprintf("if(confirm('$txt')){ %s; }", $javascript ? $javascript : "return true");
  }
  $html_options['onclick'] = $javascript.";return false;";

  return gButton($text, $html_options, $button_options);
}

?>