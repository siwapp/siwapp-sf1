<?php

  /** Container form.
   *  Form only to be meant as a "container" for other forms.
   *  @package     siwapp
   *  @subpackage  form
   *  @author      JoeZ99  <jzarate@gmail.com>
   *  @version     SVN: $Id$
   */

class ContainerForm extends BaseForm
{
  public function getEmbeddedForm($name)
  {
    return isset($this->embeddedForms[$name]) ? $this->embeddedForms[$name] : null ;
  }

  public function removeEmbeddedForm($name)
  {
    if(isset($this->embeddedForms[$name]))
    {
      unset($this->embeddedForms[$name]);
    }
  }
}