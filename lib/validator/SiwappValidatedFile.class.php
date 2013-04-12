<?php

  /**
   * SiwappValidatedFile extends from the standard sfValidatedFile
   * it's the class that is returned for the sfValidatorFile, once that field
   * is "clear"
   * Its purpose is to be used at postValidation environment, to check
   * if the directories are writeable.
   *
   * @package    siwapp
   * @subpackage validator
   * @author     JoeZ <jzarate@gmail.com>
   * @version    SVN: $Id$
   */
class SiwappValidatedFile extends sfValidatedFile
{
  const CANT_CREATE_DIRECTORY = 1;
  const NOT_A_DIRECTORY = 2;
  const DIRECTORY_NOT_WRITABLE = 3;

  /** 
   * This method works exactly the same as the save() method, but it doesn't actually save
   * the file. It just checks if it can be "saveable"
   *
   * @author  JoeZ <jzarate@gmail.com>
   * @return  boolean true if the file "can be saved", false otherwise
   */

  public function canSave($file = null,$create = true,$dirMode = 0777)
  {
    if(is_null($file))
    {
      $file = $this->generateFilename();
    }
    if ($file[0] != '/' && $file[0] != '\\' && !(strlen($file) > 3 && ctype_alpha($file[0]) && $file[1] == ':' && ($file[2] == '\\' || $file[2] == '/')))
    {
      if (is_null($this->path))
      {
        throw new RuntimeException('You must give a "path" when you give a relative file name.');
      }

      $file = $this->path.DIRECTORY_SEPARATOR.$file;
    }

    // get our directory path from the destination filename
    $directory = dirname($file);
    $directory_show = substr($directory,strlen(sfConfig::get('sf_web_dir')));

    if (!is_readable($directory))
    {
      if ($create && !@mkdir($directory, $dirMode, true))
      {
        // failed to create the directory
        throw new Exception(sprintf('Failed to create file upload directory "%s".', $directory_show),self::CANT_CREATE_DIRECTORY);
      }

    }

    if (!is_dir($directory))
    {
      // the directory path exists but it's not a directory
      throw new Exception(sprintf('File upload path "%s" exists, but is not a directory.', $directory_show),self::NOT_A_DIRECTORY);
    }

    if (!is_writable($directory))
    {
      // the directory isn't writable
      throw new Exception(sprintf('File upload path "%s" is not writable.', $directory_show),self::DIRECTORY_NOT_WRITABLE);
    }

    return is_null($this->path) ? $file : str_replace($this->path.DIRECTORY_SEPARATOR, '', $file);
  }
}