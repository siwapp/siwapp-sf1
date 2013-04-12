<?php

function settings_tab_selected($tab)
{
  if (strtolower($tab) == strtolower(sfContext::getInstance()->getRequest()->getParameter('tab')))
  {
    echo 'selected';
  }
}

function renderHeaders($headers, $sort, $route)
{
  foreach ($headers as $k => $v) {
    echo '<th class="'.strtolower(sfInflector::underscore($k)).'">';
    $change_sort_type = ($sort[0] == $k && $sort[1] == $v[1]) ? toggleStatus($v[1]) : $v[1];
    $class = ($sort[0] == $k)? toggleStatus($change_sort_type) : null;
    echo link_to(__($v[0]), 
        $route . '?sort[0]=' . $k . '&sort[1]=' . $change_sort_type, 
        array('class' => $class)
    );
    echo '</th>';
  }
}

function toggleStatus($sortStatus)
{
  return ($sortStatus == 'asc')? 'desc' : 'asc';
}

function filter_by_status($name, $selectedStatus, $currentStatus = null)
{
  $selected = ($currentStatus != null && $currentStatus == $selectedStatus) ? 'selected' : null;

  return content_tag('a', $name, "href=# class=$selected status #$selectedStatus#"); // See siwapp/searchform.js ~L45 (approximately)
}