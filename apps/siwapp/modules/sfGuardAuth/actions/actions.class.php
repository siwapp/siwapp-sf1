<?php

/*
 */

require_once(sfConfig::get('sf_plugins_dir').'/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

/**
 *
 * @package    symfony
 * @subpackage login
 * @author     Enrique Martinez
 */
class sfGuardAuthActions extends BasesfGuardAuthActions
{
  /**
   * override the signin, to check for a database connection
   * and warn the user in case there is not one
   * @author  JoeZ <jzarate@gmail.com>
   * @package siwapp
   */
  public function executeSignin($request)
  {
    $this->errors = array();
    if(!$this->getUser()->isAuthenticated())
    {
      if($request->isMethod('get') && !Tools::checkDB($this->getContext()->getConfiguration()))
      {
        $this->errors[] = $this->getContext()->getI18N()->__("Can't connect to database");
      }
    }
    
    // check if load sample data
    $this->loadSampleData();
    
    // check if calculate totals
    $this->checkIfUpdateTotals();
    
    parent::executeSignin($request);
  }

  public function executePasswordRecovery(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());


    $user_email = $this->getRequestParameter('username_email',null);
    if($user_email)
    {
      $userObject = Doctrine::getTable('sfGuardUser')->createQuery()->
        where('sfGuardUser.Username = ?', $user_email)->
        orWhere('Profile.Email LIKE ?', $user_email)->
        fetchOne();

      if($userObject)
      {
        $profile = $userObject->Profile;
        $userObject->Profile->hash = md5($userObject->Profile->email.time());
        $userObject->save();
        $this->sendEmail($userObject->Profile,null);
      }
    }
    return sfView::SUCCESS;
  }

  public function executePasswordReset(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $hash = $this->getRequestParameter('hash');
    if($this->getUser()->isAuthenticated())
    {
      $this->getUser()->getGuardUser()->Profile->hash = '';
      $this->getUser()->getGuardUser()->save();
      $this->redirect('@homepage');
    }

    $userObject = Doctrine::getTable('sfGuardUser')->createQuery()->
      where('Profile.Hash != ?', '')->
      where('Profile.Hash = ?', $hash)->fetchOne();
    $new_password = '';

    if($userObject)
    {
      for($i = 0;$i<8;$i++)
      {
        if($i % 2 == 0)
        {
          $new_password .= chr(rand(48,90));
        }
        else
        {
          $new_password .= chr(rand(97,122));
        }
      }
      $userObject->setPassword($new_password);
      $userObject->save();
      if($this->sendEmail($userObject->Profile,$new_password))
      {
        $userObject->Profile->hash = '';
        $userObject->save();
        $this->renderText($i18n->__('Check your e-mail for your new password.'));
        return sfView::NONE;
      }
      $this->renderText($i18n->__('There have been problems with the messaging system. Please try again later'));
      return sfView::NONE;
    }
    $this->renderText($i18n->__('Wrong hash code'));
    return sfView::NONE;

  }

  private function sendEmail($profile,$password)
  {
    $i18n = $this->getContext()->getI18N();
    $activation_link = $password ? null : $this->generateUrl('password_reset',array('hash'=>$profile->hash),true);
    
    $message = new PasswordMessage($profile,$i18n,$activation_link,$password);
    return ($this->getMailer()->send($message) > 0);
  }

  /**
   * loads the test data. 
   * This is executed only if the user has set this option during installation.
   *
   * @return void
   **/
  private function loadSampleData()
  {
    if(PropertyTable::get('sample_data_load'))
    {
      Doctrine_Core::loadData(sfConfig::get('sf_data_dir').'/fixtures/test', false);
      CommonTable::calculateTotals(true);
      
      $i18n = $this->getContext()->getI18N();
      $this->getUser()->info($i18n->__("Sample data has been loaded."));
      PropertyTable::set('sample_data_load', 0);
    }
  }
  
  /**
   * this checks if recalculation of totals and status is needed
   * of the opened invoices
   *
   * @return void
   **/
  private function checkIfUpdateTotals()
  {
    // if the property is not set, we set it here
    if(!PropertyTable::get('last_calculation_date'))
    {
      PropertyTable::set('last_calculation_date', '1970-01-01');
    }
    
    $last = new sfDate(PropertyTable::get('last_calculation_date'));
    $today = new sfDate();
    
    if($today->diff($last, sfTime::DAY) > 0)
    {
      CommonTable::calculateTotals();
      PropertyTable::set('last_calculation_date', $today->format('Y-m-d'));
    }
  }

}
