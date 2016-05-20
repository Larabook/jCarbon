<?php
namespace Tests\jCarbon;

/*
 * This file is part of the jCarbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Tests\AbstractTestCase;
use \Carbon\jCarbon;


class AddTest extends AbstractTestCase
{
	public function testAddYearsPositive()
	{
		$this->assertSame(1395, jCarbon::createFromDate(1394)->addYears(1)->year);
	}

	public function testAddYearsZero()
	{
		$this->assertSame(1395, jCarbon::createFromDate(1395)->addYears(0)->year);
	}


	public function testAddYearsNegative()
	{
		$this->assertSame(1394, jCarbon::createFromDate(1395)->addYears(-1)->year);
	}

	public function testAddYear()
	{
		$this->assertSame(1395, jCarbon::createFromDate(1394)->addYear()->year);
	}


	public function testAddMonthsPositive()
	{
		$this->assertSame(1, jCarbon::createFromDate(1394, 12)->addMonths(1)->month);
	}

	public function testAddMonthsZero()
	{
		$this->assertSame(12, jCarbon::createFromDate(1394, 12)->addMonths(0)->month);
	}

	public function testAddMonthsNegative()
	{
		$this->assertSame('1394-11-01', jCarbon::createFromDate(1394, 12, 1)->setTime(0, 0, 0)->addMonths(-1)->format('Y-m-d'));
		$this->assertSame(29, jCarbon::createFromDate(1394, 1, 1)->addMonths(-1)->daysInMonth);
		$this->assertSame('1393-12-29', jCarbon::createFromDate(1394, 12, 29)->addMonths(-12)->format('Y-m-d'));
		$this->assertSame('1393-01-01', jCarbon::createFromDate(1394, 1, 1)->addMonths(-12)->format('Y-m-d'));
		$this->assertSame('1393-01-01', jCarbon::createFromDate(1395, 1, 1)->addMonths(-24)->format('Y-m-d'));
		$this->assertSame('1395-12-01', jCarbon::createFromDate(1395, 1, 1)->addMonths(11)->format('Y-m-d'));
		$this->assertSame('1396-01-01', jCarbon::createFromDate(1395, 1, 1)->addMonths(12)->format('Y-m-d'));
		$this->assertSame('1392-01-05', jCarbon::createFromDate(1394, 1, 5)->addMonths(-24)->format('Y-m-d'));
		$this->assertSame(11, jCarbon::createFromDate(1394, 12, 1)->addMonths(-1)->month);
		$this->assertSame(2, jCarbon::createFromDate(1390, 1, 31)->addMonth()->month);

	}

	public function testAddMonth()
	{
		$this->assertSame(1, jCarbon::createFromDate(1394, 12)->addMonth()->month);
	}

	public function testAddMonthWithOverflow()
	{
		$this->assertSame(2, jCarbon::createFromDate(1390, 1, 31)->addMonth()->month);
	}

	public function testAddMonthsNoOverflowPositive()
	{
		$this->assertSame('1390-02-31', jCarbon::createFromDate(1390, 1, 31)->addMonthNoOverflow()->format('Y-m-d'));
		$this->assertSame('1390-03-31', jCarbon::createFromDate(1390, 1, 31)->addMonthsNoOverflow(2)->format('Y-m-d'));
		$this->assertSame('1390-03-29', jCarbon::createFromDate(1390, 2, 29)->addMonthNoOverflow()->format('Y-m-d'));
		$this->assertSame('1391-03-02', jCarbon::createFromDate(1390, 12, 31)->addMonthsNoOverflow(2)->format('Y-m-d'));
	}

	public function testAddMonthsNoOverflowZero()
	{
		$this->assertSame(12, jCarbon::createFromDate(1394, 12)->addMonths(0)->month);
	}


	public function testAddMonthsNoOverflowNegative()
	{
		$this->assertSame('1390-01-29', jCarbon::createFromDate(1390, 2, 29)->addMonthsNoOverflow(-1)->format('Y-m-d'));
		$this->assertSame('1390-01-31', jCarbon::createFromDate(1390, 3, 31)->addMonthsNoOverflow(-2)->format('Y-m-d'));
		$this->assertSame('1390-02-31', jCarbon::createFromDate(1390, 3, 31)->addMonthsNoOverflow(-1)->format('Y-m-d'));

		$this->assertSame('1390-01-02', jCarbon::createFromDate(1390, 1, 31)->addMonths(-1)->format('Y-m-d'));
		$this->assertSame('1389-12-29', jCarbon::createFromDate(1390, 1, 31)->addMonthsNoOverflow(-1)->format('Y-m-d'));
		$this->assertSame('1389-12-02', jCarbon::createFromDate(1390, 1, 31)->addMonths(-2)->format('Y-m-d'));
		$this->assertSame('1389-11-30', jCarbon::createFromDate(1390, 1, 31)->addMonthsNoOverflow(-2)->format('Y-m-d'));
	}

	public function testAddDaysPositive()
	{
		$this->assertSame(1, jCarbon::createFromDate(1394, 5, 31)->addDays(1)->day);
	}

	public function testAddDaysZero()
	{
		$this->assertSame(31, jCarbon::createFromDate(1394, 5, 31)->addDays(0)->day);
	}

	public function testAddDaysNegative()
	{
		$this->assertSame(30, jCarbon::createFromDate(1394, 5, 31)->addDays(-1)->day);
	}

	public function testAddDay()
	{
		$this->assertSame(1, jCarbon::createFromDate(1394, 5, 31)->addDay()->day);
	}

	public function testAddWeekdaysPositive()
	{
		$dt = jCarbon::create(1390, 1, 4, 13, 2, 1)->addWeekdays(9);

		$this->assertSame(15, $dt->day);

		// test for https://bugs.php.net/bug.php?id=54909
		$this->assertSame(13, $dt->hour);
		$this->assertSame(2, $dt->minute);
		$this->assertSame(1, $dt->second);
	}

	public function testAddWeekdaysZero()
	{
		$this->assertSame(4, jCarbon::createFromDate(1390, 1, 4)->addWeekdays(0)->day);
	}

	public function testAddWeekdaysNegative()
	{
		$this->assertSame(21, jCarbon::createFromDate(1390, 1, 31)->addWeekdays(-9)->day);
	}

	public function testAddWeekday()
	{
		$this->assertSame(7, jCarbon::createFromDate(1390, 1, 6)->addWeekday()->day);
	}

	public function testAddWeekdayDuringWeekend()
	{
		$this->assertSame(8, jCarbon::createFromDate(1390, 1, 7)->addWeekday()->day);
	}

	public function testAddWeeksPositive()
	{
		$this->assertSame(28, jCarbon::createFromDate(1394, 5, 21)->addWeeks(1)->day);
	}

	public function testAddWeeksZero()
	{
		$this->assertSame(21, jCarbon::createFromDate(1394, 5, 21)->addWeeks(0)->day);
	}

	public function testAddWeeksNegative()
	{
		$this->assertSame(14, jCarbon::createFromDate(1394, 5, 21)->addWeeks(-1)->day);
	}

	public function testAddWeek()
	{
		$this->assertSame(28, jCarbon::createFromDate(1394, 5, 21)->addWeek()->day);
	}

	public function testAddHoursPositive()
	{
		$this->assertSame(1, jCarbon::createFromTime(0)->addHours(1)->hour);
	}

	public function testAddHoursZero()
	{
		$this->assertSame(0, jCarbon::createFromTime(0)->addHours(0)->hour);
	}

	public function testAddHoursNegative()
	{
		$this->assertSame(23, jCarbon::createFromTime(0)->addHours(-1)->hour);
	}

	public function testAddHour()
	{
		$this->assertSame(1, jCarbon::createFromTime(0)->addHour()->hour);
	}

	public function testAddMinutesPositive()
	{
		$this->assertSame(1, jCarbon::createFromTime(0, 0)->addMinutes(1)->minute);
	}

	public function testAddMinutesZero()
	{
		$this->assertSame(0, jCarbon::createFromTime(0, 0)->addMinutes(0)->minute);
	}

	public function testAddMinutesNegative()
	{
		$this->assertSame(59, jCarbon::createFromTime(0, 0)->addMinutes(-1)->minute);
	}

	public function testAddMinute()
	{
		$this->assertSame(1, jCarbon::createFromTime(0, 0)->addMinute()->minute);
	}

	public function testAddSecondsPositive()
	{
		$this->assertSame(1, jCarbon::createFromTime(0, 0, 0)->addSeconds(1)->second);
	}

	public function testAddSecondsZero()
	{
		$this->assertSame(0, jCarbon::createFromTime(0, 0, 0)->addSeconds(0)->second);
	}

	public function testAddSecondsNegative()
	{
		$this->assertSame(59, jCarbon::createFromTime(0, 0, 0)->addSeconds(-1)->second);
	}

	public function testAddSecond()
	{
		$this->assertSame(1, jCarbon::createFromTime(0, 0, 0)->addSecond()->second);
	}

	/**
	 * Test non plural methods with non default args.
	 */

	public function testAddYearPassingArg()
	{
		$this->assertSame(1396, jCarbon::createFromDate(1394)->addYear(2)->year);
	}

	public function testAddMonthPassingArg()
	{
		$this->assertSame(7, jCarbon::createFromDate(1394, 5, 1)->addMonth(2)->month);
	}

	public function testAddMonthNoOverflowPassingArg()
	{

		$dt = jCarbon::createFromDate(1388, 12, 29)->addMonthsNoOverflow(2);
		$this->assertSame(1389, $dt->year);
		$this->assertSame(2, $dt->month);
		$this->assertSame(29, $dt->day);

		$dt = jCarbon::createFromDate(1388, 12, 31 /* ! 2days overflow */)->addMonthsNoOverflow(2);
		$this->assertSame(1389, $dt->year);
		$this->assertSame(3, $dt->month);
		$this->assertSame(2, $dt->day);
	}

	public function testAddDayPassingArg()
	{
		$this->assertSame(12, jCarbon::createFromDate(1394, 5, 10)->addDay(2)->day);
	}

	public function testAddHourPassingArg()
	{
		$this->assertSame(2, jCarbon::createFromTime(0)->addHour(2)->hour);
	}

	public function testAddMinutePassingArg()
	{
		$this->assertSame(2, jCarbon::createFromTime(0)->addMinute(2)->minute);
	}

	public function testAddSecondPassingArg()
	{
		$this->assertSame(2, jCarbon::createFromTime(0)->addSecond(2)->second);
	}

}
