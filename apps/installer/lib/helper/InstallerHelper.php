<?php

/**
 * Checks a configuration.
 */
function check($boolean, $message, $help = '', $fatal = false)
{
  echo '<div>';
  if ($boolean)
  {
    echo '<span class="status ok">' . __('CORRECT') . '</span>';
    echo '<span class="message">' . $message . '</span><br>';
  }
  else
  { 
    if ($fatal)
    {
      echo '<span class="status error">' . __('ERROR') . '</span>';
    }
    else
    { 
      echo '<span class="status warning">' . __('WARNING') . '</span>';
      
    }
    echo '<span class="message">' . $help . '</span><br>';
  }
  echo '</div>';
}


function draw_requirement_result($result)
{
  $html = '<span class="field ' . $result . '">' 
  . ucfirst($result) 
  . '</span>';
  
  return $html;
}

function draw_recommendation_result($recommended, $result)
{
  $cssClass = ($recommended == $result)? 'yes' : 'no';
  $html = '<span class="field">'
  . ucfirst($recommended)
  . '<span class="' . $cssClass . '">' . ucfirst($result) . '</span>'
  . '</span>';
  
  return $html;
}

function link_to_step($linkStep, $currentStep, $title, $route)
{
  if ($linkStep == $currentStep)
  {
    return '<strong>' . $title . '</strong>';
  } 
  elseif ($linkStep < $currentStep)
  {
    $params = isset($_REQUEST['sf_root_dir'])? '?sf_root_dir='.$_REQUEST['sf_root_dir'] : null;

    return link_to($title, $route .$params);
  }
  else 
  {
    return $title;
  }
}

