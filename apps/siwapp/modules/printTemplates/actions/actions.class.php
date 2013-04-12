<?php

/**
 * templates actions.
 *
 * @package    siwapp
 * @subpackage templates
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class printTemplatesActions extends sfActions
{
 /**
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $finder = Doctrine::getTable('Template')->createQuery()->orderBy('Name', 'asc');
    $this->_csrf = new BaseForm();
    $this->templates = $finder->execute();
  }
  
  //----------------------------------------------------------------------------
  
  public function executeEdit(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $this->form = new TemplateForm(Doctrine::getTable('Template')->find($request->getParameter('id')));
    
    if ($request->isMethod('post') || $request->isMethod('put'))
    {
      $this->form->bind($request->getParameter('template'));
      if ($this->form->isValid())
      {
        $message = 'The template was %s successfully.';
        $updated = $this->form->getObject()->isNew() ? 'created' : 'updated';
        $this->getUser()->info($i18n->__(sprintf($message, $updated)));
        $template = $this->form->save();
        $this->redirect('@templates?action=edit&id='.$template->getId());
      }
      else 
      {
        $this->getUser()->error($i18n->__('The template has not been saved due to some errors.'));
      }
    }
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($request->isMethod('post'));
    
    if (count($ids = (array) $request->getParameter('ids', array())))
    {
      $rows = Doctrine::getTable('Template')->createQuery()->whereIn('id', $ids)->execute()->delete();
    }
    
    $this->redirect('@templates');
  }
  
  //----------------------------------------------------------------------------
  
  public function executeSave(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $invoices = (array) $request->getParameter('invoices', array());
    $estimates = (array) $request->getParameter('estimates', array());
    // check that there is only one template for each one
    if (count($invoices) > 1 || count($estimates) > 1)
    {
      $this->getUser()->error($this->getContext()->getI18N()->__('There must be only one template for model.'));
      $this->redirect('@templates');
    }
    
    $templates = Doctrine::getTable('Template')->createQuery()->execute();
    foreach ($templates as $t)
    {
      $models = array();
      if (in_array($t->getId(), $invoices)) $models[] = 'Invoice';
      if (in_array($t->getId(), $estimates)) $models[] = 'Estimate';
      $t->setModels(implode(',', $models));
      $t->save();
    }
    $this->getUser()->info($this->getContext()->getI18N()->__('Successfully saved.'));
    $this->redirect('@templates');
  }
}
