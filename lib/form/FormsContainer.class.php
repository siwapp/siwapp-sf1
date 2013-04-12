<?php

  /** Forms container.
   *
   *  Form only to be meant as a "container" for other forms.
   *  The Form can also be inherited.
   *  If it acts as a "container":
   *    - The idea is contain repetitions of instances of sfFormDoctrine forms
   *      and another containers.
   *    - The sfFormDoctrine repetitions must be of the same class.
   *    - This Form must be embedded in the "master" form
   *    - On the master form bind method, the "fixEmbeddedd" method of this 
   *      form must be called. 
   *    - If there is some fields of the forms contained in this "container" 
   *      that must be setted before the contained forms are saved, use
   *      the "addFixed(field_name,field_value)" method.
   *
   *  If it's inherited.
   *    - On the form that inherits it, a "parent::save()" call must be inside
   *      its save() method.
   *    - If the form that inherits it have a "bind()" method, it needs to call
   *      "parent::bind()". (If it doesn't have one, this form's "bind" method
   *      is called automatically).
   *
   *  @package     siwapp
   *  @subpackage  form
   *  @author      Enrique Martinez  <enrique@markhaus.com>
   */

class FormsContainer extends sfFormObject
{
  /* the classname of sfFormDoctrine repetitions */
  protected $formClass;

  protected $fixed = array();

  /**
   * constructor
   *
   * @param array $forms          An array with forms to embed into this one
   * @param string $formClass     The classname of the sfFormDoctrine forms
   **/
  public function __construct($forms = array(), $formClass = null, $options = array(), $CSRFSecret = null)
  {
    $this->formClass = $formClass;
    
    parent::__construct(array(), $options, $CSRFSecret);

    foreach($forms as $name => $form)
    {
      $this->embedForm($name, $form);
    }
  }

  /**
   *  To be called when a form inherits this form
   */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->fixEmbedded($taintedValues);
    parent::bind($taintedValues,$taintedFiles);
  }

  /**
   * Just to make sure every time a form is embedded, the 'remove' widgets
   * are added. They are needed in the deleting process
   */
  public function embedForm($name,sfForm $form,$decorator = null)
  {
    if($form instanceof sfFormDoctrine)
    {
      $form->setWidget(
                       'remove',
                       new sfWidgetFormInputHidden(array(),array('class'=>'remove'))
                       );
      $form->setValidator(
                          'remove',
                          new sfValidatorPass()
                          );
    }
    parent::embedForm($name,$form,$decorator);
  }

  /**
   *  This method should be called everytime a "bind" is going on.
   *  It prevents ajax-loaded forms to be wrongly perceived as invalid
   *  data (since they weren't when the form was rendered from the server)
   *
   *  @param array $taintedValues  
   *  @param sfForm $form   (optional) the form to "fix"
   */
  public function fixEmbedded(array $taintedValues = null, $form = null)
  {  
    if(null === $form)
    {
      $form = $this;
    }
    if($form instanceof FormsContainer)
    {
      foreach($taintedValues as $key => $object)
      {
        if(!isset($form[$key]))
        {
          if($formClass = $form->getFormClass())
          {
            $f = new $formClass(null, $this->options, false);
            $form->embedForm($key, $f);
          }
        }
      }
    }
    foreach($form->getEmbeddedForms() as $name => $eForm)
    {
      if(isset($taintedValues[$name]))
      {
        $this->fixEmbedded($taintedValues[$name],$eForm); 
      }
      $form->embedForm($name,$eForm);
    }
  }

  /**
   *   method intended to be automatically called in the save process
   *   of a sfFormObject (which this form inherits)
   */
  public function doUpdateObject($values)
  {  
    // updateamos los objetos embebidos
    foreach($this->embeddedForms as $name => $form)
    {
      if($form instanceof sfFormDoctrine && $form->getObject() !== null)
      {
        if($values[$name]['remove'])
        {
          $form->getObject()->delete();
          unset($this->embeddedForms[$name]);
        }
        else
        {
          $form->updateObject($values[$name]);
        }
      }
    }
    
  }


  /**
   *   method intended to be automatically called in the save process
   *   of a sfFormObject (which this form inherits)
   */
  public function updateObjectEmbeddedForms($values,$forms = null)
  {
    if(!$forms)
    {
      $forms = $this->embeddedForms;
    }

    foreach($forms as $name => $form)
    {
      if(!isset($values[$name]) || !is_array($values[$name]))
      {
        continue;
      }
      if($form instanceof FormsContainer)
      {
        $form->updateObject($values[$name]);
      }
      else if(!($form instanceof sfFormDoctrine))
      {
        $this->updateObjectEmbeddedForms($values[$name],$form->getEmbeddedForms());
      }
    }
  }

  /**
   *   method intended to be automatically called in the save process
   *   of a sfFormObject (which this form inherits)
   */
  public function saveEmbeddedForms($con = null,$forms = null)
  {
    if(null === $forms)
    {
      $forms = $this->embeddedForms;
    }
    foreach($forms as $form)
    {
      if($form instanceof sfFormDoctrine)
      {
        foreach($this->fixed as $field => $value)
        {
          if($form->getObject()->contains($field))
          {
            call_user_func(array($form->getObject(),'set'),$field,$value);
          }
        }
        $form->getObject()->save($con);
      }
      else if($form instanceof FormsContainer)
      {
        foreach($this->fixed as $field => $value)
        {
          $form->addFixed($field,$value);
        }
        $form->saveEmbeddedForms($con);
      }
    }
  }

  /**
   *  Called when a value of a field of one ot the embedded forms in this
   *  container needs to be setted before the form is saved
   */
  public function addFixed($field,$value)
  {
    $this->fixed[$field] = $value;
  }

  public function removeFixed($field)
  {
    if(isset($this->fixed[$field]))
    {
      unset($this->fixed[$field]);
    }
  }

  /**
   *  "Fake" methods needed because of this inheriting the sfFormObject
   */
  
  public function getObject()
  {
    return new fakeObject();
  }

  public function getFormClass()
  {
    return $this->formClass;
  }

  public function getModelName()
  {
    foreach($this->embeddedForms as $name => $form)
    {
      if($form instanceof sfFormDoctrine)
      {
        return $form->getModelName();
      }
    }
    reset($this->embeddedForms);
    $first_son = each($this->embeddedForms);
    return $first_son ? $first_son[1]->getModelName() : 'Fake';
  }

  public function getConnection()
  {
    return Doctrine_Manager::getInstance()->getConnectionForComponent($this->getModelName());
  }

  public function processValues($values){return $values;}

  
}

class fakeObject 
{
  public function save($con = null)
  {
    return true;
  }
}