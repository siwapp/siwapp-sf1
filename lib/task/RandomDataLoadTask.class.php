<?php
/**
 * Generates fixtures directly in database
 */
class RandomDataLoadTask extends sfDoctrineBaseTask
{
  protected
    $customers,
    $tags,
    $series,
    $letters;
  
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application',null,sfCommandOption::PARAMETER_REQUIRED,'The app','siwapp'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 
        'The connection name', 'doctrine'),
      // Custom options
      new sfCommandOption('invoices', null, sfCommandOption::PARAMETER_REQUIRED, 'Number of invoices', 50),
      new sfCommandOption('recurring', null, sfCommandOption::PARAMETER_REQUIRED, 'Number of recurring invoices', 20),
      new sfCommandOption('years', null, sfCommandOption::PARAMETER_REQUIRED, 'Number of years', 5)
    ));
    
    $this->namespace = 'siwapp';
    $this->name      = 'random-data-load';
    $this->briefDescription = 'Fills your database with random data for testing purposes';
    $this->detailedDescription = <<<EOF
The [random-data-load|INFO] task fills database with random data for testing purposes. This executes 4 previous tasks:
doctrine:drop-db
doctrine:build-db
doctrine:insert-sql
doctrine:data-load --dir="data/fixtures/random"
  [php symfony siwapp:random-data-load [--invoices=NUMBER] [--years=NUMBER] [--recurring=NUMBER] |INFO] 
EOF;

    $this->inv = null;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $this->runTask('doctrine:drop-db', '--no-confirmation');
    $this->runTask('doctrine:create-db');
    $this->runTask('doctrine:insert-sql');
    $this->runTask('doctrine:data-load', 'data/fixtures/random data/fixtures/templates.yml');
    
    $this->_init_();
    
    // Generation methods
    if($options['invoices'])
    {
      $this->generateInvoices($options['invoices'], $options['years']);
      $this->logSection('siwapp', 'Invoices were succesfully created');
    }
    if($options['recurring'])
    {
      $this->generateInvoices($options['recurring'], $options['years'], 'RecurringInvoice');
      $this->logSection('siwapp', 'Recurring invoices were succesfully created');
    }
    
    $this->runTask('siwapp:calculate-totals', '--all');
  }
  
  protected function _init_()
  {
    $data = sfYaml::load(sfConfig::get('sf_lib_dir').'/task/RandomData.yml');
  
    $this->series     = SeriesTable::getChoicesForSelect();
    $this->letters    = $data['letters'];
    $this->tags       = $data['tags'];
    $this->items      = $data['short_texts'];
    $this->terms      = $data['long_texts'];
    $this->companies  = $data['company_names'];
    $this->names      = $data['names'];
    $this->lastnames  = $data['lastnames'];
    
    $this->taxes      = array();
    foreach($q = Doctrine_Query::create()->from('Tax t')->execute() as $tax)
    {
      $this->taxes[] = $tax->getId();
    }
    
    $customers = array('name' => array(), 'email' => array(), 'id' => array(), 'company' => array());
    
    for ($i = 0; $i < mt_rand(20, 100); $i++)
    {
      $customers['id'][]      = str_pad(mt_rand(11111, 99999).mt_rand(11111, 99999), 10, '0', STR_PAD_LEFT).$this->letters[array_rand($this->letters)];
      $name = $this->names[array_rand($this->names)]." ".$this->lastnames[array_rand($this->lastnames)];
      $customers['name'][]    = $name;
      $customers['email'][]   = str_replace(' ', '_', strtolower($name)).'@example.com';
      $customers['company'][] = $this->companies[array_rand($this->companies)];
    }
    
    $this->customers  = $customers;
  }
  
  protected function generateInvoices($n, $years, $model = 'Invoice')
  {
    $this->logSection('siwapp', 'generating '.$model.'s');
    $init_time = sfDate::getInstance()->subtractYear($years);
    $interval = (time() - $init_time->get())/$n;

    $period_types = array('0'=>'day', '1'=>'week', '2'=>'month', '3'=>'year');
    
    for ($i=1; $i<=$n; $i++)
    {
      // Invoice
      $this->inv = new $model();

      $cind = array_rand($this->customers['id']);
      $this->inv->setSeriesId(array_rand($this->series));
      $this->inv->setCustomerName($this->customers['company'][$cind]);
      $this->inv->setContactPerson($this->customers['name'][$cind]);
      $this->inv->setCustomerIdentification($this->customers['id'][$cind]);
      $this->inv->setCustomerEmail($this->customers['email'][$cind]);
      $this->inv->setInvoicingAddress("Fake Dir n ".mt_rand(1,999)."\nMadrid\nSpain");
      $this->inv->setShippingAddress($this->inv->getInvoicingAddress());
      $this->inv->setTerms($this->terms[array_rand($this->terms)]);
      $this->inv->setNotes($this->terms[array_rand($this->terms)]);

      if(get_class($this->inv)=='RecurringInvoice')
      {
        $this->inv->setDaysToDue(mt_rand(30,700));
        $this->inv->setMaxOccurrences(mt_rand(1,4) == 1 ? mt_rand(0,999) : null);
        $rand_time = $init_time->get() + ($interval*$i)+mt_rand(30,700)*24*60*60;
        $rand_time2 = $rand_time + mt_rand(30,3000)*24*60*60;
        
        $this->inv->setStartingDate(sfDate::getInstance($rand_time)->format('Y-m-d'));
        $this->inv->setFinishingDate(sfDate::getInstance($rand_time2)->format('Y-m-d'));
        $this->inv->setPeriodType($period_types[mt_rand(0,3)]);
        if(($timeUntilToday = time() - sfDate::getInstance($this->inv->getStartingDate())->get()*24*60*60) < 0)
        {
          $timeUntilToday = 0;
        }

        switch($this->inv->getPeriodType())
        {
          case 'day':
            $this->inv->setPeriod(mt_rand(1,180));
            $periodInSeconds = $this->inv->getPeriod()*24*60*60;
            break;
          case 'week':
            $this->inv->setPeriod(mt_rand(1,110));
            $periodInSeconds = $this->inv->getPeriod()*7*24*60*60;
            break;
          case 'month':
            $this->inv->setPeriod(mt_rand(1,24));
            $periodInSeconds = $this->inv->getPeriod()*30*24*60*60;
            break;
          case 'year':
            $this->inv->setPeriod(mt_rand(1,5));
            $periodInSeconds = $this->inv->getPeriod()*365*24*60*60;
            break;
        }
        $interval = ($timeUntilToday / $periodInSeconds) - ($timeUntilToday % $periodInSeconds) ;
        if($interval > 0)
        {
          $this->inv->setLastExecutionDate(sfDate::getInstance($this->inv->getStartingDate())->get() + $interval*$periodInSeconds);
        }
        
        $this->inv->setEnabled((mt_rand(1, 5) == 1 ? false : true));
      }

      // Tags
      for ($a = 0; $a < mt_rand(1, 10); $a++)
      {
        $this->inv->addTag($this->tags[array_rand($this->tags)]);
      }

      // InvoiceItems
      $this->generateInvoiceItems();
      
      if($model == 'Invoice')
      {
        $this->inv->setDraft((mt_rand(1, 20) == 1 ? true : false));
        if(!$this->inv->getDraft())
        {
          $init_time->addSecond($interval);
          $this->inv->setIssueDate($init_time->format('Y-m-d'));
          $this->inv->setDueDate(sfDate::getInstance($init_time)->addDay(mt_rand(10, 120))->format('Y-m-d'));
          
          $this->generatePayments();
        }
      }
      
      $this->inv->save();
      $this->inv->free(true);
    }
    
  }
  
  protected function generateInvoiceItems()
  {
    for($j = 0; $j < mt_rand(1,10); $j++)
    {
      $item = new Item();
      $item->setDescription($this->items[array_rand($this->items)]);
      $item->setUnitaryCost(mt_rand(100, 100000)/100);
      $item->setQuantity(mt_rand(1, 10));
      if(mt_rand(1, 15) == 1) $item->setDiscount(mt_rand(1, 70));
      $max_tax = mt_rand(1, 10) == 1 ? mt_rand(1, 3) : 1;
      
      for($kk = 0; $kk < $max_tax; $kk++)
      {
        $item->Taxes[] = $this->getRandomTax();
      }
      
      $this->inv->Items[] = $item;
    }
  }
  
  protected function getRandomTax()
  {
    return Doctrine_Query::create()
      ->from('Tax')
      ->where('id = ?', $this->taxes[mt_rand(1, count($this->taxes)) - 1])
      ->execute()
      ->get(0);
  }
  
  protected function generatePayments()
  {
    // Payments
    $date1 = sfDate::getInstance($this->inv->getIssueDate());
    $date2 = sfDate::getInstance($this->inv->getDueDate());
    
    $total = $this->inv->setAmounts()->getGrossAmount();

    if (mt_rand(1, 10) == 1)
    {
      $q = mt_rand(1, 5);
      $paid = 0;
      for($k = 0; ($k < $q) && ($paid < $total); $k++)
      {
        $payment = new Payment();
        $payment_date = sfDate::getInstance($date2->get() - mt_rand(1, $date2->diff($date1)));
        $payment->setDate($payment_date->format('Y-m-d'));
        $payment->setNotes($this->items[array_rand($this->items)]);
        
        $rest = $total - $paid;
        $sum = round($rest * (0.25 * mt_rand(1, 3)), 2);
        
        $paid += $sum;
        $payment->setAmount($sum);
        $this->inv->Payments[] = $payment;
      }
    }
    else
    {
      $payment = new Payment();
      $payment_date = sfDate::getInstance($date2->get() - mt_rand(1, $date2->diff($date1)));
      $payment->setDate($payment_date->format('Y-m-d'));
      $payment->setNotes($this->items[array_rand($this->items)]);
      $payment->setAmount($total);
      $this->inv->Payments[] = $payment;
    }
  }

}
