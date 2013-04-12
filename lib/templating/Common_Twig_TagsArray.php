<?php
/**
 * Extend this class and fill $tags array programatically in the __constructor method.
 * Publish the variable in your template.
 */
abstract class Common_Twig_TagsArray implements Countable, ArrayAccess
{
  protected $tags = array();
  
  // Countable
  
  public function count()
  {
    return count($this->tags);
  }
  
  // ArrayAccess READONLY
  
  public function offsetExists($offset)
  {
    return isset($this->tags[$offset]);
  }
  
  public function offsetGet($offset)
  {
    return $this->offsetExists($offset) ? $this->tags[$offset] : null;
  }
  
  public function offsetSet($offset, $value)
  {
    return new sfException(__CLASS__." class is read only.");
  }
  
  public function offsetUnset($offset)
  {
    return new sfException(__CLASS__." class is read only.");
  }
}