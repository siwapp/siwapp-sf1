<?php

/**
 * products actions.
 *
 * @package    siwapp
 * @subpackage invoices
 * @author     Siwapp Team
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class productsActions extends sfActions
{
  public function preExecute()
  {
    $this->currency = $this->getUser()->getAttribute('currency');
    $this->culture  = $this->getUser()->getCulture();
  }
  
  private function getProduct(sfWebRequest $request)
  {
    $this->forward404Unless($product = Doctrine::getTable('Product')->find($request->getParameter('id')),
      sprintf('Object product does not exist with id %s', $request->getParameter('id')));
      
    return $product;
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $namespace  = $request->getParameter('searchNamespace');
    $search     = $this->getUser()->getAttribute('search', null, $namespace);
    $sort       = $this->getUser()->getAttribute('sort', array('reference', 'desc'), $namespace);
    $page       = $this->getUser()->getAttribute('page', 1, $namespace);
    $maxResults = $this->getUser()->getPaginationMaxResults();
    
    $q = ProductQuery::create()->search($search)->orderBy("$sort[0] $sort[1], reference $sort[1]");
    // totals
    $this->quantity = $q->total('quantity');
    $this->sold     = $q->total('sold');

    $this->pager = new sfDoctrinePager('Product', $maxResults);
    $this->pager->setQuery($q);
    $this->pager->setPage($page);
    $this->pager->init();
   
    $this->getUser()->setAttribute('page', $request->getParameter('page'));
    
    $this->sort = $sort;
  }

  public function executeNew(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $product = new Product();
    $product->fromArray(array(
                          'reference'=>$i18n->__('Product reference'),
                          'description'=>$i18n->__('Product description'),
                          'price'=> $i18n->__('Product price')
                          ));
    $this->productForm = new ProductForm($product, array('culture'=>$this->culture));
    $this->title       = $i18n->__('New Product');
    $this->action      = 'create';
    $this->setTemplate('edit');
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->productForm = new ProductForm(null, array('culture' => $this->culture));
    $this->title = $this->getContext()->getI18N()->__('New Product');
    $this->action = 'create';

    $this->processForm($request, $this->productForm);
    $this->setTemplate('edit');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $product = $this->getProduct($request);

    $this->productForm = new ProductForm($product, array('culture'=>$this->culture));
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Product');
    $this->action = 'update';
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $product_params = $request->getParameter('product');
    $request->setParameter('id', $product_params['id']);
    $this->forward404Unless($request->isMethod('post'));
    $product = $this->getProduct($request);
    
    $this->productForm = new ProductForm($product, array('culture'=>$this->culture));
    $this->processForm($request, $this->productForm);
    
    $i18n = $this->getContext()->getI18N();
    $this->title = $i18n->__('Edit Product');
    $this->action = 'update';
    
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $product = $this->getProduct($request);
    if(!$product->delete())
    {
      $this->getUser()->error($this->getContext()->getI18N()
                              ->__('The product could not be deleted. '
                                   .'Probably because an associated invoice exists')
                              );
    }

    $this->redirect('products/index');
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $i18n = $this->getContext()->getI18N();
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $template = 'The product was %s successfully %s.';
      $message  = $form->getObject()->isNew() ? 'created' : 'updated';
      $suffix   = null;
      $method   = 'info';
      
      $product = $form->save();
      
      $this->getUser()->$method($i18n->__(sprintf($template, $message, $suffix)));
      $this->redirect('products/edit?id='.$product->id);
    }
    else
    {
      $this->getUser()->error($i18n->__('The product has not been saved due to some errors.'));
    }
  }
  
  /**
   * batch actions
   *
   * @return void
   **/
  public function executeBatch(sfWebRequest $request)
  {
    $i18n = $this->getContext()->getI18N();
    $form = new sfForm();
    $form->bind(array('_csrf_token' => $request->getParameter('_csrf_token')));

    //TO REVIEW MCY the use of a pseudo conditionals switch looks really suspect

    if($form->isValid() || $this->getContext()->getConfiguration()->getEnvironment() == 'test')
    {
      $n = 0;
      foreach($request->getParameter('ids', array()) as $id)
      {
        if($product = Doctrine::getTable('Product')->find($id))
        {
          switch($request->getParameter('batch_action'))
          {
            case 'delete':
              if ($product->delete()) $n++;
              break;
          }
        }
      }
      switch($request->getParameter('batch_action'))
      {
        case 'delete':
          $this->getUser()->info(sprintf($i18n->__('%d products were successfully deleted.'), $n));
          break;
      }
    }

    $this->redirect('@products');
  }

  /**
   * ajax to return the description and price of a product
   *
   * @return json
   **/
  public function executeAjaxSelectProduct(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $id = $request->getParameter('product_id');
    $product = Doctrine::getTable('Product')->retrieveDescAndPrice($id);
    return $this->renderText(json_encode($product));
  }
  
  /**
   * ajax for the input autocomplete of products
   *
   **/
  public function executeAjaxProduct(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $q = $request->getParameter('q');
    $items = Doctrine::getTable('Product')->retrieveForSelect($request->getParameter('q'),
      $request->getParameter('limit'));

    return $this->renderText(json_encode($items));
  }
}
