<?php
 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(8, new lime_output_color());

$r = new RecurringInvoice();

$t->diag("Testing checkMustOccurrences");

$r->setStartingDate('2005-01-01');
$r->setPeriodType('month');
$r->setPeriod(1);
$r->checkMustOccurrences(new sfDate('2005-12-01'));
$t->is($r->getMustOccurrences(), 12, '12 month must occurrences');

$r->setPeriodType('week');
$r->checkMustOccurrences(new sfDate('2005-02-01'));
$t->is($r->getMustOccurrences(), 5, '5 week must occurrences');

$r->setPeriodType('day');
$r->checkMustOccurrences(new sfDate('2005-01-02'));
$t->is($r->getMustOccurrences(), 2, '2 day must occurrences');

$t->diag("Testing checkMustOccurrences with a finishing date");

$r->setFinishingDate('2005-06-05');
$r->setPeriodType('month');
$r->setPeriod(1);
$r->checkMustOccurrences(new sfDate('2006-01-01'));
$t->is($r->getMustOccurrences(), 6, '6 month must occurrences with finishing date');

$t->diag("Testing checkMustOccurrences with max occurrences");

$r->setMaxOccurrences(3);
$r->checkMustOccurrences(new sfDate('2006-01-01'));
$t->is($r->getMustOccurrences(), 3, '3 as max occurrences');

$t->diag("Testing the status");
$r->setEnabled(false);
$t->is($r->checkStatus()->getStatus(), RecurringInvoice::DISABLED, 'status disabled');

$r->setEnabled(true);
$r->Invoices[] = new Invoice();
$r->Invoices[] = new Invoice();
$r->Invoices[] = new Invoice();
$t->is($r->checkStatus()->getStatus(), RecurringInvoice::FINISHED, 'if occurrences >= max occurrences then finished');

$r->setMaxOccurrences(null);
$t->is($r->checkStatus()->getStatus(), RecurringInvoice::PENDING, 'if no max occurrences then pending');

