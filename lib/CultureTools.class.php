<?php
/**
 * CultureTools class
 *
 * Provides static functions useful for culture management
 *
 * @author Enrique Martinez
 **/
class CultureTools
{
  /**
   * Gets an array with the available languages of the application.
   * The english is added by default.
   *
   * @return array languages
   **/
  public static function getAvailableLanguages()
  {
    $i18nDir = sfConfig::get('sf_app_i18n_dir');
    $languages = array('en');
    foreach(new DirectoryIterator($i18nDir) as $fileInfo)
    {
      if($fileInfo->isFile() && preg_match('~messages\..*\.xml$~', $fileInfo->getFilename()))
      {
        list($name, $culture, $extension) = explode('.', $fileInfo->getFilename());
        $culture_array = explode('_', $culture);
        if(!in_array($culture_array[0], $languages))
        {
          $languages[] = $culture_array[0];
        }
      }
    }
    
    return $languages;
  }

  /**
   * Gets an array with the available countries for a specific language.
   *
   * @return array countries
   **/
  public static function getCountriesForLanguage($language)
  {
    $i18nDir = sfConfig::get('sf_app_i18n_dir');
    $countries = array();
    foreach(new DirectoryIterator($i18nDir) as $fileInfo)
    {
      if($fileInfo->isFile() && preg_match('~messages\..*\.xml$~', $fileInfo->getFilename()))
      {
        list($name, $culture, $extension) = explode('.', $fileInfo->getFilename());
        $culture_array = explode('_', $culture);
        if($culture_array[0] == $language && isset($culture_array[1]))
        {
          $countries[] = $culture_array[1];
        }
      }
    }
    
    return $countries;
  }
  
  /**
   * Gets the preferred language of the user based on the browser request.
   *
   * @return string language
   **/
  public static function getPreferredLanguage(sfWebRequest $request)
  {
    $culture = explode('_', $request->getPreferredCulture());

    return $culture[0];
  }

  /**
   * Gets the preferred country of the user based on the browser request.
   *
   * @return string country
   **/
  public static function getPreferredCountry(sfWebRequest $request)
  {
    $culture = explode('_', $request->getPreferredCulture());

    return count($culture)>1 ? $culture[1] : $culture[0];
  }
}