<?php
/**
 * Global Settings Form
 * @author Carlos Escribano <carlos@markhaus.com>
 */
class GlobalSettingsForm extends FormsContainer
{
  protected static
    $paper_sizes = array(
      "4a0" => "4A0", "2a0" => "2A0", "a0" => "A0", "a1" => "A1", "a2" => "A2", "a3" => "A3", "a4" => "A4", "a5" => "A5", "a6" => "A6", "a7" => "A7", "a8" => "A8", "a9" => "A9", "a10" => "A10", "b0" => "B0", "b1" => "B1", "b2" => "B2", "b3" => "B3", "b4" => "B4", "b5" => "B5", "b6" => "B6", "b7" => "B7", "b8" => "B8", "b9" => "B9", "b10" => "B10", "c0" => "C0", "c1" => "C1", "c2" => "C2", "c3" => "C3", "c4" => "C4", "c5" => "C5", "c6" => "C6", "c7" => "C7", "c8" => "C8", "c9" => "C9", "c10" => "C10", "ra0" => "RA0", "ra1" => "RA1", "ra2" => "RA2", "ra3" => "RA3", "ra4" => "RA4", "sra0" => "SRA0", "sra1" => "SRA1", "sra2" => "SRA2", "sra3" => "SRA3", "sra4" => "SRA4", "letter" => "Letter", "legal" => "Legal", "ledger" => "Ledger", "tabloid" => "Tabloid", "executive" => "Executive", "folio" => "Folio", "commerical #10 envelope" => "Commercial #10 Envelope", "catalog #10 1/2 envelope" => "Catalog #10 1/2 Envelope", "8.5x11" => "8.5x11", "8.5x14" => "8.5x14", "11x17" => "11x17"
    );
  
  public function configure()
  {
    $culture = $this->getOption('culture', sfConfig::get('sf_default_culture'));
    
    $this->setWidgets(array(
      'company_name'     => new sfWidgetFormInputText(),
      'company_address'  => new sfWidgetFormTextarea(),
      'company_phone'    => new sfWidgetFormInputText(),
      'company_fax'      => new sfWidgetFormInputText(),
      'company_email'    => new sfWidgetFormInputText(),
      'company_url'      => new sfWidgetFormInputText(),
      'currency'         => new sfWidgetFormI18nChoiceCurrency(array('culture' => $culture)),
      'legal_terms'      => new sfWidgetFormTextarea(array(), array('cols' => '30', 'rows' => '7')),
      'pdf_size'         => new sfWidgetFormSelect(array('choices' => self::$paper_sizes)),
      'pdf_orientation'  => new sfWidgetFormSelect(array('choices' => array('portrait', 'landscape')))
    ));
    
    $this->widgetSchema->setLabels(array(
      'company_name'     => 'Name',
      'company_address'  => 'Address',
      'company_phone'    => 'Phone',
      'company_fax'      => 'FAX',
      'company_email'    => 'Email',
      'company_url'      => 'Web',
      'currency'         => 'Currency',
      'legal_terms'      => 'Terms & Conditions',
      'pdf_size'         => 'Page size',
      'pdf_orientation'  => 'Page orientation'
    ));
    
    $this->setDefaults(array(
      'company_name'     => PropertyTable::get('company_name'),
      'company_address'  => PropertyTable::get('company_address'),
      'company_phone'    => PropertyTable::get('company_phone'),
      'company_fax'      => PropertyTable::get('company_fax'),
      'company_email'    => PropertyTable::get('company_email'),
      'company_url'      => PropertyTable::get('company_url'),
      'currency'         => PropertyTable::get('currency', 'USD'),
      'legal_terms'      => PropertyTable::get('legal_terms'),
      'pdf_size'         => PropertyTable::get('pdf_size', 'a4'),
      'pdf_orientation'  => PropertyTable::get('pdf_orientation', 'portrait')
    ));

    $this->widgetSchema['company_logo'] = new sfWidgetFormInputFileEditable(array(
      'label'     => 'Logo',
      'file_src'  => self::getUploadsDir().'/'.PropertyTable::get('company_logo'),
      'is_image'  => true,
      'edit_mode' => is_file(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.PropertyTable::get('company_logo')),
      'template'  => '<div id="company_logo_container"><div>%file%<br/>%input%</div><div class="dl">%delete% %delete_label%</div><div>'
    ));

    $this->setValidators(array(
      'company_name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'company_address'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'company_phone'       => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'company_fax'         => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'company_email'       => new sfValidatorEmail(array('max_length' => 255, 'required' => false)),
      'company_url'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'company_logo'        => new sfValidatorFile(array(
                                     'mime_types' => 'web_images', 
                                     'required' => false, 
                                     'validated_file_class'=>'SiwappValidatedFile',
                                     'path'      => sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR
                                     )),
      'company_logo_delete' => new sfValidatorPass(),
      'currency'            => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'legal_terms'         => new sfValidatorString(array('required' => false)),
      'pdf_size'            => new sfValidatorString(array('required' => false)),
      'pdf_orientation'     => new sfValidatorString(array('required' => false))
    ));
    
    
    /* TAXES & SERIES*/
    $this->embedForm('taxes',new TaxesForm());
    $this->embedForm('seriess',new SeriessForm());

    $this->validatorSchema->setPostValidator(new sfValidatorAnd(
        array(
          new sfValidatorCallback(array(
              'callback' => array($this, 'validateTaxes')
            ), array(
              'invalid' => 'Some taxes have not been deleted because they are currently in use: <strong>%invalid_taxes%</strong>.'
            )),
          new sfValidatorCallback(array(
              'callback' => array($this, 'validateSeries')
            ), array(
              'invalid' => 'Some series have not been deleted because they are currently in use: <strong>%invalid_series%</strong>.'
            )),
          new sfValidatorCallBack(
              array('callback'  => array($this,'checkLogo')),
              array('invalid'   => "Can't upload the logo")
              )
          )
        ));

    $this->widgetSchema->setNameFormat('config[%s]');
    $this->widgetSchema->setFormFormatterName('listB');

  }
  
  public function addNewForm($key, $where, $form)
  {
    $this->embeddedForms[$where]->embedForm($key, $form);
    $this->embedForm($where, $this->embeddedForms[$where]);
  }
  
  /**
   * @return void
   * @author Carlos Escribano <carlos@markhaus.com>
   **/
  public function save($con = null)
  {
    parent::save();
    $currency_decimals = sfConfig::get('app_currency_decimals', array());
    
    foreach ($this->getValues() as $key => $value)
    {
      switch ($key)
      {
        case 'company_logo':
          if (("on" == $this->getValue('company_logo_delete')) && is_file($old = sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.PropertyTable::get('company_logo')))
          {
            @ unlink($old);
            PropertyTable::set('company_logo', null);
          }
          
          if ($value)
          {
            $fname = $value->generateFilename();
            $value->save(sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.$fname);
            PropertyTable::set('company_logo', $fname);
          }
          break;

        case 'company_logo_delete':
          break;

        case 'currency':
          PropertyTable::set('currency_decimals', (array_key_exists($value, $currency_decimals) ? $currency_decimals[$value] : 2));
          PropertyTable::set('currency', $value);
          break;
        case 'seriess':
        case 'taxes':
          break;
        default:
          PropertyTable::set($key, $value);
          break;
      }
    }
  }
  
  public static function getUploadsDir()
  {
    $root_path = substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/'));
    $web_dir = str_replace(DIRECTORY_SEPARATOR,'/',sfConfig::get('sf_web_dir'));
    $upload_dir = str_replace(DIRECTORY_SEPARATOR,'/',sfConfig::get('sf_upload_dir'));
    return $root_path.str_replace($web_dir, null, $upload_dir);
  }
  
  /**
   * Finds the taxes to be deleted and if they are still linked to items throws
   * a global error to tell it to the user.
   */
  public function validateTaxes(sfValidatorBase $validator, $values, $arguments)
  {
    $deleted_ids = array();
    foreach($values['taxes'] as $key => $tax)
    {
      if($tax['remove'])
      {
        $deleted_ids[] = $tax['id'];
      }
    }
    if(!count($deleted_ids))
    {
      return $values;
    }

    $toDelete = Doctrine_Core::getTable('Tax')
      ->createQuery()
      ->from('Tax t')
      ->innerJoin('t.Items it')
      ->addWhere('t.id IN (?)',implode(',',$deleted_ids))->execute();

    if(count($toDelete))
    {
      $invalid = array();
      foreach($toDelete as $k => $tax)
      {
        $this->taintedValues['taxes']['old_'.$tax->id]['remove'] = '';
        $invalid[] = $tax->name;
      }
      throw new sfValidatorErrorSchema($validator, 
                                       array(
                                             new sfValidatorError($validator, 
                                                                  'invalid',
                                                                  array(
                                                                    'invalid_taxes'=>
                                                                      implode(', ',$invalid)))));
    }
    
    return $values;
  }
  
  /**
   * Finds the series to be deleted and if they are still linked to Common instances throws
   * a global error to tell it to the user.
   */
  public function validateSeries(sfValidatorBase $validator, $values, $arguments)
  {
    $deleted_ids = array();
    foreach($values['seriess'] as $key => $serie)
    {
      if($serie['remove'])
      {
        $deleted_ids[] = $serie['id'];
      }
    }
    if(!count($deleted_ids))
    {
      return $values;
    }

    $toDelete = Doctrine_Core::getTable('Series')
      ->createQuery()
      ->from('Series s')
      ->innerJoin('s.Common c')
      ->addWhere('s.id IN (?)',implode(',',$deleted_ids))->execute();

    if(count($toDelete))
    {
      $invalid = array();
      foreach($toDelete as $k => $serie)
      {
        $this->taintedValues['seriess']['old_'.$serie->id]['remove'] = '';
        $invalid[] = $serie->name;
      }
      throw new sfValidatorErrorSchema($validator,
                                       array(
                                         new sfValidatorError($validator,
                                                              'invalid',
                                                              array(
                                                                'invalid_series'=>
                                                                implode(', ',$invalid)))));
    }

    return $values;
  }

  public function checkLogo(sfValidatorBase $validator, $values)
  {
    if(!$values['company_logo'])
    {
      return $values;
    }
    $logoObject = $values['company_logo'];
    try
    {
      $logoObject->canSave();
    }
    catch(Exception $e)
    {
      $validator->setMessage('invalid',$validator->getMessage('invalid').': '.$e->getMessage());
      throw new sfValidatorError($validator,'invalid');
    }
    return $values;
  }
}