<?php

/**
 * Profile form.
 *
 * @package    form
 * @subpackage Profile
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileForm extends BaseProfileForm
{
  public function configure()
  {
    $user = $this->getOption('user');
    $this->widgetSchema['sf_guard_user_id'] = new sfWidgetFormInputHidden();
      
    $this->widgetSchema['language']         = new sfWidgetFormI18nChoiceLanguage(
                                                    array(
                                                      'culture' => $user->getLanguage(),
                                                      'languages' => 
                                                        CultureTools::getAvailableLanguages(),
                                                      )
                                                    );
    $this->widgetSchema['country']          = new sfWidgetFormI18nChoiceCountry(
                                                    array(
							  'add_empty' => true,
                                                      'culture' => $user->getLanguage(),
                                                      'countries' => 
                                                        CultureTools::getCountriesForLanguage(
                                                          $this->getOption('language')
                                                          ),
                                                      )
                                                    );
    $this->widgetSchema['search_filter']    = new sfWidgetFormSelect(
                                                    array(
                                                      'choices'   => InvoiceSearchForm::getQuickDates()
                                                      )
                                                    );
    $this->widgetSchema['series'] = new sfWidgetFormSelect(
                                          array(
                                            'choices' => 
                                              SeriesTable::getChoicesForSelect()
                                            ));
    
    $this->widgetSchema['old_password']     = new sfWidgetFormInputPassword();
    $this->widgetSchema['new_password']     = new sfWidgetFormInputPassword();
    $this->widgetSchema['new_password2']    = new sfWidgetFormInputPassword();

    $this->validatorSchema['sf_guard_user_id'] = new sfValidatorAnd(
                                                       array(
                                                         new sfValidatorDoctrineChoice(
                                                               array(
                                                                 'model' => 'sfGuardUser',
                                                                 'required' => true
                                                                 ), 
                                                               array(
                                                                 'invalid' => "The user does not exist!"
                                                                 )
                                                               ),
                                                         new CompareValueValidator(
                                                               array(
                                                                 'value' => $user->getGuardUser()->getId()
                                                                 )
                                                               )
                                                         )
                                                       );
    $this->validatorSchema['language']         = new sfValidatorI18nChoiceLanguage(
                                                       array('required' => true)
                                                       );
    $this->validatorSchema['country']          = new sfValidatorI18nChoiceCountry(
                                                       array('required' => false)
                                                       );
    $this->validatorSchema['series']           = new sfValidatorDoctrineChoice(
                                                       array(
                                                             'model'=>'Series',
                                                             ),
                                                       array(
                                                             'required' 
                                                               => 'The default invoicing series is mandatory'
                                                             )
                                                       );
    $this->validatorSchema['search_filter']    = new sfValidatorChoice(
                                                       array(
                                                         'required' => false,
                                                         'choices'  => 
                                                           array_keys(
                                                             InvoiceSearchForm::getQuickDates()
                                                             )
                                                         )
                                                       );
    $this->validatorSchema['email']            = new sfValidatorEmail(
                                                       array(
                                                         'max_length' => 100, 
                                                         'required' => true
                                                         )
                                                       );
    $this->validatorSchema['old_password']   = new sfValidatorPass();

    $vdPassword                              = new sfValidatorCallback(
                                                       array(
                                                         'callback' => array($this,'checkPassword')
                                                         ),
                                                       array(
                                                         'invalid'  => 'Wrong password',
                                                         'required' => 'Old password required'
                                                         )
                                                       );

    $passwd_min_length = sfConfig::get('app_password_min_length',4);
    $this->validatorSchema['new_password']     = new sfValidatorPass();
    $vdNewPassword                             = new sfValidatorString(
                                                       array(
                                                             'min_length' => 1,
                                                         'required'=>false
                                                         ),
                                                       array(
                                                         'min_length' => 'Password length must be '.
                                                           "greater than $passwd_min_length"
                                                         )
                                                       );

    $this->validatorSchema['new_password2']    = new sfValidatorPass();

    $vd = new sfValidatorSchema(
                array(
                      'old_password' => $vdPassword,
                      'new_password' => $vdNewPassword,
                      'new_password2'=> new sfValidatorPass()
                      )
                );
    
    $vd->setPostValidator(
            new sfValidatorSchemaCompare(
                  'new_password','==','new_password2',
                  array(),
                  array('invalid' => "Passwords don't match")
                  )
            );


    $this->validatorSchema->setPostValidator(
                              new SiwappConditionalValidator(
                                    array(
                                      'control_field'    => 'new_password',
                                      'validator_schema' => $vd,
                                      'callback'         => array('Tools','checkLength')
                                      )
                                    )
                              );

    
    $this->widgetSchema->setLabels(array(
        'nb_display_results'  => 'Results to display in listings',
        'language'            => 'Interface language',
        'series'              => 'Default invoicing series',
        'old_password'        => 'Old password',
        'new_password'        => 'New password',
        'new_password2'       => 'New password (confirmation)',
        'first_name'          => 'First Name',
        'last_name'          => 'Last Name'
      ));
      
    $this->setDefaults(array(
        'nb_display_results'  => 10,
        'language'            => $user->getLanguage(),
        'country'             => $user->getCountry()
      ));
    


      
    $this->widgetSchema->setNameFormat('config[%s]');
  }

  public function save($con = null)
  {
    if(strlen($this->values['new_password']))
    {
      $this->getOption('user')->setPassword($this->values['new_password']);
    }
    parent::save($con);
  }

  public function checkPassword(sfValidatorCallback $validator,$password)
  {
    if(!$this->getOption('user')->checkPassword($password))
    {
      throw new sfValidatorError($validator,'invalid',array('value'=>$password));
    }
    return true;
  }

}