<?php
/**
 * See app.yml for allowed tags for settings
 */
class SettingsTagsArray extends Common_Twig_TagsArray
{
  public function __construct()
  {
    foreach (Doctrine::getTable('Property')->findAll() as $property)
    {
      $value = $property->value;
      
      switch ($property->keey)
      {
        case 'company_logo':
          $value = str_replace("\\", "/", GlobalSettingsForm::getUploadsDir()) . "/$value";
          break;
      }
      
      $this->tags[$property->keey] = $value;
    }
  }
}