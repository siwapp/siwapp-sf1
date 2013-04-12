<?php

$dirname	= dirname(__FILE__).'/../..';
include($dirname.'/../../test/bootstrap/unit.php');

// TODO find a cleaner way to include all the plugin instead of all the classes by hand
require_once($dirname.'/lib/exception/sfDateTimeException.class.php');
require_once($dirname.'/lib/sfDate.class.php');
require_once($dirname.'/lib/sfDateTimeToolkit.class.php');
require_once($dirname.'/lib/sfTime.class.php');

$t = new lime_test(62, new lime_output_color());

// strtolower()
$t->diag('sfDate()');

// test finalDayOfMonth
$t->is(sfDate::getInstance('2009-04-01')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth'	);
$t->is(sfDate::getInstance('2009-04-14')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth'	);
// test all boundary finalDayOfMonth

$t->is(sfDate::getInstance('2009-01-31')->finalDayOfMonth()->o2h()	, "2009-01-31", 'finalDayOfMonth january boundary'	);
$t->is(sfDate::getInstance('2009-02-28')->finalDayOfMonth()->o2h()	, "2009-02-28", 'finalDayOfMonth february boundary'	);
$t->is(sfDate::getInstance('2009-03-31')->finalDayOfMonth()->o2h()	, "2009-03-31", 'finalDayOfMonth march boundary'	);
$t->is(sfDate::getInstance('2009-04-30')->finalDayOfMonth()->o2h()	, "2009-04-30", 'finalDayOfMonth april boundary'	);
$t->is(sfDate::getInstance('2009-05-31')->finalDayOfMonth()->o2h()	, "2009-05-31", 'finalDayOfMonth may boundary'	);
$t->is(sfDate::getInstance('2009-06-30')->finalDayOfMonth()->o2h()	, "2009-06-30", 'finalDayOfMonth june  boundary'	);
$t->is(sfDate::getInstance('2009-07-31')->finalDayOfMonth()->o2h()	, "2009-07-31", 'finalDayOfMonth july boundary'	);
$t->is(sfDate::getInstance('2009-08-31')->finalDayOfMonth()->o2h()	, "2009-08-31", 'finalDayOfMonth August boundary'	);
$t->is(sfDate::getInstance('2009-09-30')->finalDayOfMonth()->o2h()	, "2009-09-30", 'finalDayOfMonth september boundary'	);
$t->is(sfDate::getInstance('2009-10-31')->finalDayOfMonth()->o2h()	, "2009-10-31", 'finalDayOfMonth october boundary'	);
$t->is(sfDate::getInstance('2009-11-30')->finalDayOfMonth()->o2h()	, "2009-11-30", 'finalDayOfMonth november boundary'	);
$t->is(sfDate::getInstance('2009-12-31')->finalDayOfMonth()->o2h()	, "2009-12-31", 'finalDayOfMonth december boundary'	);

//test with bisextil year
$t->is(sfDate::getInstance('2008-02-29')->finalDayOfMonth()->o2h()	, "2008-02-29", 'finalDayOfMonth bisextil boundary'	);



$t->is(sfDate::getInstance('2009-04-01')->dayOfWeek()	, sfTime::WEDNESDAY, 'test dayOfWeek()'	);

$t->is(sfDate::getInstance('1970-06-25')->getDay()	, 25	, 'test getDay()'	);
$t->is(sfDate::getInstance('1970-06-25')->getMonth()	, 06	, 'test getMonth()'	);
$t->is(sfDate::getInstance('1970-06-25')->getYear()	, 1970	, 'test getYear()'	);

$t->is(sfDate::getInstance('1982-01-01')->isHolidayFR()	, true	, "premier de lan"	);
$t->is(sfDate::getInstance('1982-01-02')->isHolidayFR()	, false	, "pas premier de lan"	);

$t->is(sfDate::getInstance('2020-05-21')->isHolidayFR()	, true	, "ascention 2020"	);
$t->is(sfDate::getInstance('2013-04-01')->isHolidayFR()	, true	, "lundi paques 2013"	);

$t->is(sfDate::getInstance('2008-04-01')->isBisextil()	, true	, "2008 is bisextil"	);
$t->is(sfDate::getInstance('2009-04-01')->isBisextil()	, false	, "2009 is not"	);
$t->is(sfDate::getInstance('2009-04-01')->isBisextil()	, false	, "1998 is not"	);

//test special differences
$t->is(sfDate::getInstance('2010-01-01 00:00:01')->diffSecond('2010-01-01 00:00:00'), 1, 'diffSecond');
$t->is(sfDate::getInstance('2010-01-01 00:00:00')->diffSecond('2010-01-01 00:00:01'), -1, 'diffSecond inverse');
$t->is(sfDate::getInstance('2010-01-01 00:01:00')->diffSecond('2010-01-01 00:00:00'), 60, 'diffSecond 60');
$t->is(sfDate::getInstance('2010-01-01 00:00:00')->diffSecond('2010-01-01 00:01:00'), -60, 'diffSecond -60');
$t->is(sfDate::getInstance('2010-01-01 00:01:00')->diffMinute('2010-01-01 00:00:00'), 1, 'diffMinute');
$t->is(sfDate::getInstance('2010-01-01 00:00:00')->diffMinute('2010-01-01 00:01:00'), -1, 'diffMinute inverse');
$t->is(sfDate::getInstance('2010-01-01 00:01:59')->diffMinute('2010-01-01 00:00:00'), 1, 'diffMinute almost 2 minutes but 1');
$t->is(sfDate::getInstance('2010-01-01 00:00:00')->diffMinute('2010-01-01 00:01:59'), -1, 'diffMinute almost 2 minutes but 1 inverse');
$t->is(sfDate::getInstance('2010-01-01 01:01:00')->diffHour('2010-01-01 00:00:00'), 1, 'diffHour');
$t->is(sfDate::getInstance('2010-01-01 00:00:00')->diffHour('2010-01-01 01:01:00'), -1, 'diffHour inverse');
$t->is(sfDate::getInstance('2010-01-01 01:59:00')->diffHour('2010-01-01 00:00:00'), 1, 'diffHour almost 2');
$t->is(sfDate::getInstance('2010-01-02')->diffDay('2010-01-01'), 1, 'diffDay');
$t->is(sfDate::getInstance('2010-01-01')->diffDay('2010-01-02'), -1, 'diffDay inverse');
$t->is(sfDate::getInstance('2010-01-01 23:59:59')->diffDay('2010-01-01 00:00:00'), 0, 'diffDay almost 2');
$t->is(sfDate::getInstance('2010-01-08')->diffWeek('2010-01-01'), 1, 'diffWeek');
$t->is(sfDate::getInstance('2010-01-01')->diffWeek('2010-01-08'), -1, 'diffWeek inverse');

// addCalendarMonth and subtractCalendarMonth
$t->is(sfDate::getInstance('2010-01-21')->addCalendarMonth(1)->format('Ymd'), '20100221', 'add calendar month simple');
$t->is(sfDate::getInstance('2010-01-30')->addCalendarMonth(1)->format('Ymd'), '20100228', 'add calendar month special');
$t->is(sfDate::getInstance('2010-01-31')->addCalendarMonth(3)->format('Ymd'), '20100430', 'add calendar month special adding 3');
$t->is(sfDate::getInstance('2010-11-30')->addCalendarMonth(3)->format('Ymd'), '20110228', 'add calendar month special changing year');
$t->is(sfDate::getInstance('2010-02-21')->subtractCalendarMonth(1)->format('Ymd'), '20100121', 'subtract calendar month simple');
$t->is(sfDate::getInstance('2010-03-30')->subtractCalendarMonth(1)->format('Ymd'), '20100228', 'subtract calendar month special');
$t->is(sfDate::getInstance('2010-07-31')->subtractCalendarMonth(3)->format('Ymd'), '20100430', 'subtract calendar month special subtracting 3');
$t->is(sfDate::getInstance('2011-02-28')->subtractCalendarMonth(3)->format('Ymd'), '20101128', 'subtract calendar month special changing year');

// diffMonth and diffYear
$t->is(sfDate::getInstance('2010-02-01')->diffMonth('2010-01-01'), 1, 'diffMonth');
$t->is(sfDate::getInstance('2010-01-01')->diffMonth('2010-02-01'), -1, 'diffMonth inverse');
$t->is(sfDate::getInstance('2010-03-31')->diffMonth('2010-01-01'), 2, 'diffMonth almost 3 but 2');
$t->is(sfDate::getInstance('2010-01-01')->diffMonth('2010-03-31'), -2, 'diffMonth almost 3 but 2 inverse');
$t->is(sfDate::getInstance('2011-03-22')->diffMonth('2010-01-01'), 14, 'diffMonth 14');
$t->is(sfDate::getInstance('2011-01-01')->diffYear('2010-01-01'), 1, 'diffYear exactly 1');
$t->is(sfDate::getInstance('2010-01-01')->diffYear('2011-01-01'), -1, 'diffYear exactly 1 inversed');
$t->is(sfDate::getInstance('2010-01-01')->diffYear('2010-12-31'), 0, 'diffYear almost 1 year');
$t->is(sfDate::getInstance('2011-01-01')->diffYear('2010-01-02'), 0, 'diffYear almost 1 year');
$t->is(sfDate::getInstance('2010-01-02')->diffYear('2011-01-01'), 0, 'diffYear almost 1 year inverse');
$t->is(sfDate::getInstance('2010-01-02')->diffYear('2014-03-15'), -4, 'diffYear 4 years and some months');
$t->is(sfDate::getInstance('2014-03-15')->diffYear('2010-01-02'), 4, 'diffYear 4 years and some months inversed');
