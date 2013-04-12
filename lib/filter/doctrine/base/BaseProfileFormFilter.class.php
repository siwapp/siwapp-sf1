<?php

/**
 * Profile filter form base class.
 *
 * @package    siwapp
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'sf_guard_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'first_name'         => new sfWidgetFormFilterInput(),
      'last_name'          => new sfWidgetFormFilterInput(),
      'email'              => new sfWidgetFormFilterInput(),
      'nb_display_results' => new sfWidgetFormFilterInput(),
      'language'           => new sfWidgetFormFilterInput(),
      'country'            => new sfWidgetFormFilterInput(),
      'search_filter'      => new sfWidgetFormFilterInput(),
      'series'             => new sfWidgetFormFilterInput(),
      'hash'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sf_guard_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'first_name'         => new sfValidatorPass(array('required' => false)),
      'last_name'          => new sfValidatorPass(array('required' => false)),
      'email'              => new sfValidatorPass(array('required' => false)),
      'nb_display_results' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'language'           => new sfValidatorPass(array('required' => false)),
      'country'            => new sfValidatorPass(array('required' => false)),
      'search_filter'      => new sfValidatorPass(array('required' => false)),
      'series'             => new sfValidatorPass(array('required' => false)),
      'hash'               => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'sf_guard_user_id'   => 'ForeignKey',
      'first_name'         => 'Text',
      'last_name'          => 'Text',
      'email'              => 'Text',
      'nb_display_results' => 'Number',
      'language'           => 'Text',
      'country'            => 'Text',
      'search_filter'      => 'Text',
      'series'             => 'Text',
      'hash'               => 'Text',
    );
  }
}
