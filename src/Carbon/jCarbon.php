<?php
/**
 * Created by PhpStorm.
 * User: iraitc
 * Date: 1/20/2016
 * Time: 08:14 PM
 */

namespace Carbon;


class jCarbon extends Carbon
{
	/**
	 * The day constants.
	 */
	const SATURDAY = 0;
	const SUNDAY = 1;
	const MONDAY = 2;
	const TUESDAY = 3;
	const WEDNESDAY = 4;
	const THURSDAY = 5;
	const FRIDAY = 6;

	/**
	 * First day of week.
	 *
	 * @var int
	 */
	protected static $weekStartsAt = self::SATURDAY;

	/**
	 * Last day of week.
	 *
	 * @var int
	 */
	protected static $weekEndsAt = self::FRIDAY;

	/**
	 * Days of weekend.
	 *
	 * @var array
	 */
	protected static $weekendDays = array(
		self::FRIDAY,
	);


	/**
	 * تابع تبدل تاریخ میلادی به جلالی
	 * Authors : Roozbeh Pournader and Mohammad Toosi
	 * @param $g_year
	 * @param $g_month
	 * @param $g_day
	 * @return array خروجی آرایه به صورت تاریخ جلالی
	 */
	public static function to_jalali($g_year, $g_month, $g_day)
	{
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $g_year - 1600;
		$gm = $g_month - 1;
		$gd = $g_day - 1;
		$g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);
		for ($i = 0; $i < $gm; ++$i)
			$g_day_no += $g_days_in_month[$i];
		if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
			$g_day_no++; /* leap and after Feb */
		$g_day_no += $gd;
		$j_day_no = $g_day_no - 79;
		$j_np = self::div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;
		$jy = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461;
		if ($j_day_no >= 366) {
			$jy += self::div($j_day_no - 1, 365);
			$j_day_no = ($j_day_no - 1) % 365;
		}
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
			$j_day_no -= $j_days_in_month[$i];
		$jm = $i + 1;
		$jd = $j_day_no + 1;
		return array($jy, $jm, $jd);
	}

	/**
	 * تابع تبدیل تاریخ جلالی به میلادی
	 * Authors : Roozbeh Pournader and Mohammad Toosi
	 * @param $j_year
	 * @param $j_month
	 * @param $j_day
	 * @return array خروجی آرایه به صورت تاریخ میلادی
	 */
	public static function to_gregorian($j_year, $j_month, $j_day)
	{
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$jy = $j_year - 979;
		$jm = $j_month - 1;
		$jd = $j_day - 1;
		$j_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
		for ($i = 0; $i < $jm; ++$i)
			$j_day_no += $j_days_in_month[$i];
		$j_day_no += $jd;
		$g_day_no = $j_day_no + 79;
		$gy = 1600 + 400 * self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097;
		$leap = true;
		if ($g_day_no >= 36525) { /* 36525 = 365*100 + 100/4 */
			$g_day_no--;
			$gy += 100 * self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524;
			if ($g_day_no >= 365)
				$g_day_no++;
			else
				$leap = false;
		}
		$gy += 4 * self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$g_day_no %= 1461;
		if ($g_day_no >= 366) {
			$leap = false;
			$g_day_no--;
			$gy += self::div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}
		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
			$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i + 1;
		$gd = $g_day_no + 1;
		return array($gy, $gm, $gd);
	}

}