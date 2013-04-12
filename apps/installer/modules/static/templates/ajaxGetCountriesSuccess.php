<span class="label">
  <label for="country"><?php echo __('Choose your country') ?></label>
</span>
<span class="field">
  <?php 
  $country_selector = new sfWidgetFormI18nChoiceCountry(array(
    'culture'=> $lang, 
    'countries' => CultureTools::getCountriesForLanguage($lang)
    ));

  echo $country_selector->render('country', $sf_user->getAttribute('country', $preferred_country));
  ?>
</span>
