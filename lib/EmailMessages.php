<?php
class SiwappMessage extends Swift_Message
{
  public function __construct()
  {
    parent::__construct();
    $this->setFrom(PropertyTable::get('company_email'), PropertyTable::get('company_name'));
  }
}


class CustomerMessage extends SiwappMessage
{
  public function __construct($customer)
  {
    parent::__construct();
    $this->setTo($customer->getEmail(), $customer->getName());
  }
}

/**
 * Emails that are sended in the
 * 'password recovery' process
 *
 * @package siwapp
 * @author JoeZ99
 */
class PasswordMessage extends SiwappMessage
{
  public function __construct($profile,$i18n,$activation_link = null,$password = null)
  {
    parent::__construct();
    $body = array();

    $body[]  = $i18n->__("Dear %1%",array('%1%'=>$profile->first_name));
    $body[]  = $i18n->__("You claim to have lost your password");
    // if activation_link, then this is the activation message
    if($activation_link)
    {
      $body[] = $i18n->__("Please click the link below to activate the process and have a new password sent to you");
      $body[] = $activation_link;
      $body[] = $i18n->__("If you can not click that link, please copy/paste it into a browser's location bar");
    }
    // if password, then this is the password recovery message
    else if($password)
    {
      $body[] = $i18n->__("This is your login info");
      $body[] = "Username: ".$profile->User->username;
      $body[] = "Password: ".$password;
      $body[] = $i18n->__("Once you've logged in, you can change your password in the \"Settings / My settings\" section");
    }
    else
    {
      $body[] = $i18n->__("Sorry, some error has occurred.");
    }


    $this
      ->setTo($profile->email,$profile->first_name.' '.$profile->last_name)
      ->setSubject(PropertyTable::get('company_name').': '.$i18n->__('Siwapp Invoice System').$i18n->__('Password recovery'))
      ->setBody(implode("\r\n",$body));
  }
}


/**
 * An email message containing the invoice/estimate formatted in html and 
 * with the pdf as attachment.
 *
 * @package siwapp
 * @author Enrique Martinez
 **/
class InvoiceMessage extends SiwappMessage
{

  /** this variable indicates if the message is ready to be sent or not */
  private $ready = false;

  public function __construct($invoice)
  {
    parent::__construct();
    $this->setTo($invoice->customer_email, $invoice->customer_name);

    // To get all the properties loaded for the template
    foreach($invoice->Items as $it)
    {
      $it->refreshRelated();
    }
    $data[] = $invoice;
    $model = get_class($invoice);
    $printer = new Printer($model, TemplateTable::getTemplateForModel($model)->getId());

    try
    {
      $body = $printer->render($data);
      $pdf = $printer->renderPdf($data)->output();
      $attachment = new Swift_Attachment(
                            $pdf,
                            $model.'-'.$invoice->getId().'.pdf',
                            'application/pdf'
                            );
      $this
        ->setSubject(PropertyTable::get('company_name').' ['.$model.': '.$invoice.']')
        ->setBody($printer->render($data), 'text/html')
        ->attach($attachment);
      $this->setReadyState(true);
      
    }
    catch(LogicException $e)
    {
      $this->setReadyState(false);
    }
  }

  private function setReadyState($value)
  {
    $this->ready = $value ? true : false;
    return $this;
  }

  public function getReadyState()
  {
    return $this->ready;
  }
}
