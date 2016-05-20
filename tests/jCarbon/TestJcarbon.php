<?php
/**
 * Created by PhpStorm.
 * User: iraitc
 * Date: 3/4/2016
 * Time: 11:59 AM
 */

namespace Tests\jCarbon;

use Tests\AbstractTestCase;
use \Carbon\jCarbon;

class TestJcarbon extends AbstractTestCase
{
	public function test_weekdays_in_carbon(){

		$jcarbon =    jCarbon::create(1394,12,28);
		$this->assertEquals($jcarbon->isFriday(),true);
		$this->assertEquals($jcarbon->isSaturday(),false);
		$this->assertEquals($jcarbon->next()->isFriday(),true);
		$this->assertEquals($jcarbon->addDays(2)->isSunday(),true);
	}


	public function test_set_date_method(){
		$jcarbon =    jCarbon::create();
		$jcarbon->setDate(1394,12,28);
		echo "\n".$jcarbon->format(),"\n";
		echo "\n".$jcarbon->addDays(2)->format();
		$this->assertEquals($jcarbon->isFriday(),true);
		$this->assertEquals($jcarbon->isSaturday(),false);
		$this->assertEquals($jcarbon->next()->isFriday(),true);
		$this->assertEquals($jcarbon->addDays(2)->isSunday(),true);
		$this->assertEquals($jcarbon->format('Y/n/d H:i:s'),'1394/12/28 00:00:00');

		$jcarbon =    jCarbon::create();
		$jcarbon->setDate(2016,03,18,jCarbon::MODE_GREGORIAN);
		$this->assertEquals($jcarbon->isFriday(),true);
		$this->assertEquals($jcarbon->isSaturday(),false);
		$this->assertEquals($jcarbon->next()->isFriday(),true);
		$this->assertEquals($jcarbon->addDays(2)->isSunday(),true);
	}

	public function test_create_method(){

		date_default_timezone_set('Asia/tehran');

		$jcarbon =    jCarbon::create(1394,12,28);
		$this->assertEquals($jcarbon->isFriday(),true);

		$jcarbon =    jCarbon::create(2016,03,18,0,0,0,null,jCarbon::MODE_GREGORIAN);
		$this->assertEquals($jcarbon->isFriday(),true);

		$jcarbon =    jCarbon::createFromDate(1394,12,28);
		$this->assertEquals($jcarbon->isFriday(),true);

		$jcarbon =    jCarbon::createFromDate(2016,03,18,null,jCarbon::MODE_GREGORIAN);
		$this->assertEquals($jcarbon->isFriday(),true);
	}

	public function test_get(){
		$jcarbon =    jCarbon::create(1394,12,28);

		$this->assertEquals($jcarbon->year,1394);
		$this->assertEquals($jcarbon->day,28);
		$this->assertEquals($jcarbon->month,12);
		$this->assertEquals($jcarbon->dayOfWeek,6);
		$this->assertEquals($jcarbon->dayOfYear,364);
		$this->assertEquals($jcarbon->weekOfYear,52);
		$this->assertEquals($jcarbon->daysInMonth,29);
	}

	public function test_set(){
		$jcarbon =    jCarbon::create();

		$jcarbon->year=1393;
		$jcarbon->month=12;
		$jcarbon->day=28;

		$jcarbon->hour=12;
		$jcarbon->minute=45;
		$jcarbon->second=40;

		$this->assertEquals($jcarbon->year,1393);
		$this->assertEquals($jcarbon->day,28);
		$this->assertEquals($jcarbon->month,12);
		$this->assertEquals($jcarbon->dayOfWeek,5);
		$this->assertEquals($jcarbon->dayOfYear,364);
		$this->assertEquals($jcarbon->weekOfYear,53);
		$this->assertEquals($jcarbon->daysInMonth,29);
		$this->assertEquals($jcarbon->format('Y/n/d H:i:s'),'1393/12/28 12:45:40');


		$jcarbon->year(1394);
		$jcarbon->month(12);
		$jcarbon->day(27);

		$jcarbon->hour(12);
		$jcarbon->minute(10);
		$jcarbon->second(10);

		$this->assertEquals($jcarbon->year,1394);
		$this->assertEquals($jcarbon->day,27);
		$this->assertEquals($jcarbon->month,12);
		$this->assertEquals($jcarbon->dayOfWeek,5);
		$this->assertEquals($jcarbon->dayOfYear,363);
		$this->assertEquals($jcarbon->weekOfYear,52);
		$this->assertEquals($jcarbon->daysInMonth,29);
		$this->assertEquals($jcarbon->format('Y/n/d H:i:s'),'1394/12/27 12:10:10');
	}
}