<?php

class sfWidgetFormI18nDateDMY extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('days',parent::generateTwoCharsRange(1,31));
    $this->addOption('months',parent::generateTwoCharsRange(1,12));
    $years = range(date('Y') - 5, date('Y') + 5);
    $this->addOption('years',array_combine($years,$years));
    $this->addOption('can_be_empty',true);
    $this->addOption('empty_value','');
    $this->addRequiredOption('culture');
    $this->addOption('use','months');
    $this->addOption('format');
    $culture = isset($options['culture']) ? $options['culture'] : 'en' ;
    $format = isset($options['format']) ? $options['format'] : 'name' ;
    $this->setOption('months',$this->getMonthFormat($culture,$format));
    $this->setOption('days',$this->getDayFormat($culture,$format));
  }

  protected function getMonthFormat($culture, $monthFormat)
  {
    switch ($monthFormat)
    {
      case 'name':
        return array_combine(range(1, 12), sfDateTimeFormatInfo::getInstance($culture)->getMonthNames());
      case 'short_name':
        return array_combine(range(1, 12), sfDateTimeFormatInfo::getInstance($culture)->getAbbreviatedMonthNames());
      case 'number':
        return $this->getOption('months');
      default:
        throw new InvalidArgumentException(sprintf('The month format "%s" is invalid.', $monthFormat));
    }
  }

  protected function getDayFormat($culture,$dayFormat)
  {
    switch($dayFormat)
    {
      case 'name':
        return array_combine(range(1,7),sfDateTimeFormatInfo::getInstance($culture)->getDayNames());
      case 'short_name':
        return array_combine(range(1,7),sfDateTimeFormatInfo::getInstance($culture)->getAbbreviatedDayNames());
      case 'number':
        return $this->getOption('days');
      default:
        throw new InvalidArgumentException(sprintf('The day format "%s" is invalid.',$dayFormat));
    }
  }
  public function render($name,$value = null,$attributes = array(),$errors =array())
  {
    $emptyValue = $this->getOption('empty_value');

    if($this->getOption('use') == 'days')
    {
      $widget = new sfWidgetFormSelect(array('choices'=>$this->getOption('can_be_empty') ? array('' => $emptyValue) + $this->getOption('days') : $this->getOption('days')), array_merge($this->attributes,$attributes));
      $result = $widget->render($name,$value);
    }
    if($this->getOption('use') == 'months')
    {
      $widget = new sfWidgetFormSelect(array('choices' => $this->getOption('can_be_empty') ? array('' => $emptyValue) + $this->getOption('months') : $this->getOption('months')), array_merge($this->attributes, $attributes));
      $result = $widget->render($name, $value);
    }
    if($this->getOption('use') == 'years')
    {
      $widget = new sfWidgetFormSelect(array('choices' => $this->getOption('can_be_empty') ? array('' => $emptyValue) + $this->getOption('years') : $this->getOption('years')), array_merge($this->attributes, $attributes));
      $result = $widget->render($name, $value);
    }
    return $result;
  }
}