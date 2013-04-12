<?php use_helper('JavascriptBase') ?>

<?php include_partial('configuration/navigation') ?>

<div id="settings-wrapper" class="content">
  <form action="<?php echo url_for('@settings') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php echo $form['_csrf_token'] ?>
    
    <?php include_partial('common/globalErrors', array('form' => $form));?>
    
    <fieldset class="left">
      <h3><?php echo __('Company') ?></h3>
      <ul>
        <?php echo $form['company_name']->renderRow(array('class' => 'full '.error_class($form['company_name']))) ?>
        <?php echo $form['company_address']->renderRow(array('class' => error_class($form['company_address']))) ?>
        <?php echo $form['company_phone']->renderRow(array('class' => error_class($form['company_phone']))) ?>
        <?php echo $form['company_fax']->renderRow(array('class' => error_class($form['company_fax']))) ?>
        <?php echo $form['company_email']->renderRow(array('class' => 'full '.error_class($form['company_email']))) ?>
        <?php echo $form['company_url']->renderRow(array('class' => 'full '.error_class($form['company_url']))) ?>
        <?php echo $form['company_logo']->renderRow(array('class' => error_class($form['company_logo']))) ?>
        <?php echo $form['currency']->renderRow(array('class' => error_class($form['currency']))) ?>
      </ul>
    </fieldset>
    
    <fieldset>
      <h3><?php echo __('Legal texts') ?></h3>
      <ul>
        <?php echo $form['legal_terms']->renderRow(array('class' => error_class($form['legal_terms']))) ?>
      </ul>
    </fieldset>
    
    <fieldset class="left taxes taxseries">
      <h3><?php echo __('Invoicing taxes') ?></h3>
      <div id="taxes">
        <ul class="head">
          <a href="#" class="xit"></a>
          <li class="name"><strong><?php echo __('Name')?></strong></li>
          <li class="value text-right"><strong><?php echo __('Value')?></strong></li>
          <li class="active"><strong><?php echo __('Active')?></strong></li>
          <li class="is_default"><strong><?php echo __('Default')?></strong></li>
        </ul>
        <?php foreach ($form['taxes'] as $tax): ?>
        <?php echo $tax?>
        <?php endforeach ?>
      </div>
      <div class="clear"></div>
      <small>
        <a id="addNewTax" href="#" class="to:taxes"><?php echo __('Add a new tax value') ?></a>
      </small>
    </fieldset>
    
    <fieldset class="seriess taxseries">
      <h3><?php echo __('Invoicing series') ?></h3>
      <div id="seriess">
        <ul class="head">
          <a href="#" class="xit"></a>
          <li class="name"><strong><?php echo __('Label')?></strong></li>
          <li class="value"><strong><?php echo __('Value') ?></strong></li>
          <li class="first_number"><strong><?php echo __('Initial value')?></strong></li>
        </ul>
        <?php foreach ($form['seriess'] as $s): ?>
        <?php echo $s?>
        <?php endforeach ?>
      </div>
      <div class="clear"></div>
      <small>
        <a id="addNewSeries" href="#" class="to:seriess"><?php echo __('Add a new series value') ?></a><br/>
        <?php echo __('The initial value will only be used for the first saved invoice of the series if there are no invoices assigned.') ?>
      </small>
    </fieldset>
    
    <fieldset class="left">
      <h3><?php echo __('PDF Settings') ?></h3>
      <ul>
        <?php echo $form['pdf_size']->renderRow(array('class' => error_class($form['pdf_size']))) ?>
        <?php echo $form['pdf_orientation']->renderRow(array('class' => error_class($form['pdf_orientation']))) ?>
      </ul>
    </fieldset>
    
    <?php include_partial('submit') ?>
  </form>
</div>
