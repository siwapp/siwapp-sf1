<?php

class Twig_Loader_Database implements Twig_LoaderInterface
{
  public function getSource($name)
  {
    return $this->findTemplate($name)->getTemplate();
  }
  
  public function getCacheKey($name)
  {
    return $this->findTemplate($name)->getName();
  }
  
  public function isFresh($name, $time)
  {
    return strtotime($this->findTemplate($name)->getUpdatedAt()) < $time;
  }
  
  protected function findTemplate($name)
  {
    if (is_numeric($name))
    {
      $template = Doctrine::getTable('Template')->find($name);
    }
    else
    {
      $template = Doctrine::getTable('Template')->findOneBy('Slug', $name);
    }
    
    if (!$template)
    {
      throw new LogicException(sprintf('Template "%s" is not defined.', $name));
    }
    
    return $template;
  }
}
