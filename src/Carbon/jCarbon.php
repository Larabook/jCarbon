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
	const MODE_JALALI='fa';
	const MODE_GREGORIAN='en';
	const DEFAULT_FORMAT='Y/m/d';

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
	 * Names of days of the week.
	 *
	 * @var array
	 */
	protected static $days = array(
			self::SATURDAY => 'Saturday',
			self::SUNDAY => 'Sunday',
			self::MONDAY => 'Monday',
			self::TUESDAY => 'Tuesday',
			self::WEDNESDAY => 'Wednesday',
			self::THURSDAY => 'Thursday',
			self::FRIDAY => 'Friday',
	);

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

	/**
	 * return result of division two numbers
	 * @param $a
	 * @param $b
	 * @return int
	 */
	private static function div($a, $b)
	{
		return (int)($a / $b);
	}
	/**
	 * convert to persian number vise versa
	 * @param $str
	 * @return mixed
	 */
	private static function to_digits($str,$digist=self::MODE_GREGORIAN,$jalali_float_symbol='٫')
	{
		$num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
		$key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $jalali_float_symbol);
		return ($digist == self::MODE_JALALI) ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
	}

	/**
	 * return name of weekday
	 * @param $day
	 * @param bool|false $tiny
	 * @return string
	 */
	private function to_persian_weekday($day, $tiny = false)
	{
		switch ($day) {
			case 6:
				if ($tiny)
					return 'ش';
				else
					return 'شنبه';
				break;
			case 0:
				if ($tiny)
					return 'ی';
				else
					return 'يكشنبه';
				break;
			case 1:
				if ($tiny)
					return 'د';
				else
					return 'دوشنبه';
				break;
			case 2:
				if ($tiny)
					return 'س';
				else
					return 'سه شنبه';
				break;
			case 3:
				if ($tiny)
					return 'چ';
				else
					return 'چهارشنبه';
				break;
			case 4:
				if ($tiny)
					return 'پ';
				else
					return 'پنجشنبه';
				break;
			case 5:
				if ($tiny)
					return 'ج';
				else
					return 'جمعه';
				break;
		}
	}

	/**
	 * return name of month number
	 * @param $month
	 * @param bool|false $tiny
	 * @return string
	 */
	private function to_persian_month($month, $tiny = false)
	{
		switch ($month) {
			case 1:
				if ($tiny)
					return "فرو";
				else
					return "فروردین";
				break;
			case 2:
				if ($tiny)
					return "ارد";
				else
					return "اردیبهشت";
				break;
			case 3:
				if ($tiny)
					return "خرد";
				else
					return "خرداد";
				break;
			case 4:
				if ($tiny)
					return "تیر";
				else
					return "تير";
				break;
			case 5:
				if ($tiny)
					return "مرد";
				else
					return "مرداد";
				break;
			case 6:
				if ($tiny)
					return "شهر";
				else
					return "شهریور";
				break;
			case 7:
				return "مهر";
				break;
			case 8:
				if ($tiny)
					return "آبا";
				else
					return "آبان";
				break;
			case 9:
				return "آذر";
				break;
			case 10:
				return "دى";
				break;
			case 11:
				if ($tiny)
					return "بهم";
				else
					return "بهمن";
				break;
			case 12:
				if ($tiny)
					return "اصف";
				else
					return "اسفند";
				break;
		}
	}

	/**
	 * is the given year leap year or not
	 * @param $yearValue
	 * @return mixed
	 */
	private function is_leap_year($year)
	{
		return array_search((($year + 2346) % 2820) % 128, array(
				5, 9, 13, 17, 21, 25, 29,
				34, 38, 42, 46, 50, 54, 58, 62,
				67, 71, 75, 79, 83, 87, 91, 95,
				100, 104, 108, 112, 116, 120, 124, 0
		));
	}

	/**
	 * return day of year
	 * @param $month
	 * @param $day
	 * @return int
	 */
	private function day_of_year($month, $day)
	{
		return $month <= 6 ?
				($month - 1 * 31 + $day) :
				186 + (($month - 6 - 1) * 30 + $day);
	}

	/**
	 * Returns date formatted according to given format.
	 * @param string $format
	 * @param string $digist
	 * @return mixed|null|string
	 */
	public function format($format=self::DEFAULT_FORMAT,$digist=self::MODE_GREGORIAN)
	{
		$timestamp=parent::getTimestamp();
		list($gy, $gm, $gd) = explode('-', date('Y-m-d', $timestamp));
		list($jy, $jm, $jd) = self::to_jalali($gy, $gm, $gd);

		$i = 0;
		$lastchar = null;
		$out = null;
		while (($ch = substr($format, $i, 1)) !== false) {
			//--unformat chars
			if ($ch == '\\') {
				$out .= substr($format, $i + 1, 1);
				$i += 2;
				continue;
			}

			switch ($ch) {
				case 'A':
					if (date('A', $timestamp) == 'PM')
						$out .= 'بعد از ظهر';
					else
						$out .= 'قبل از ظهر';
					break;
				case 'a':
					if (date('A', $timestamp) == 'PM')
						$out .= "ب.ظ";
					else
						$out .= "ق.ظ";
					break;
				case 'd':
					$out .= sprintf('%02d', $jd); // day
					break;
				case 'D':
					$out .= $this->to_persian_weekday(date('w', $timestamp), true);// Persian Shorted day of week ex:  ش
					break;
				case 'F':
					$out .= $this->to_persian_month($jm);// Persian Month ex: فروردین
					break;
				case 'g':
					$out .= date('g', $timestamp);// 12-hour format of an hour without leading zeros(1 through 12)
					break;
				case 'G':
					$out .= date('G', $timestamp);// 24-hour format of an hour without leading zeros(0 through 23)
					break;
				case "h":
					$out .= date("h", $timestamp);// 12-hour format of an hour with leading zeros(01 through 12)
					break;
				case "H":
					$out .= date("H", $timestamp);// 24-hour format of an hour with leading zeros(00 through 23)
					break;
				case "i":
					$out .= date("i", $timestamp);// Minutes with leading zeros(00 to 59)
					break;
				case "j":
					$out .= intval($jd);// intval(day)
					break;
				case "l":
					$out .= $this->to_persian_weekday(date('w', $timestamp), false);// Persian  day of week ex:  شنبه
					break;
				case "m":
					$out .= sprintf('%02d', $jm); // month
					break;
				case "M":
					$out .= $this->to_persian_month($jm, true); // Persian  Shorted Month ex : فرو , ارد
					break;
				case "n":
					$out .= intval($jm); // intval(month)
					break;
				case "L":
					$out .= $this->is_leap_year($jy) ? 1 : 0; // year is Leap (Kabiseh)
					break;
				case "s":
					$out .= date("s", $timestamp); // Seconds, with leading zeros	00 through 59
					break;
				case "S":
					$out .= 'ام';
					break;
				case "t":
					$is_leap = $this->is_leap_year($jy) ? 1 : 0;
					if ($jm <= 6)
						$jds_in_month = 31;
					else if ($jm > 6 && $jm < 12)
						$jds_in_month = 30;
					else if ($jm == 12)
						$jds_in_month = $is_leap ? 30 : 29;
					$out .= $jds_in_month; // last day of month
					break;
				case "U" :
					$out .= $timestamp; // Unix TimeStamp
					break;
				case "w":
					$jd_of_week = array(6 => 0, 0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6);
					$out .= $jd_of_week[date("w", $timestamp)]; // day of week
					break;
				case "W":
					$out .= ceil($this->day_of_year($jm, $jd) / 7); // number of weeks
					break;
				case "y":
					$out .= substr($jy, 2); // short year ex : 1391  =>  91
					break;
				case "Y":
					$out .= $jy; // Full Year ex : 1391
					break;
				case "z":
					$out .= $this->day_of_year($jm, $jd); // the day of the year ex: 280  or 365
					break;

				default :
					$out .= $ch;
			}
			$i++;
		}
		if ($digist == self::MODE_JALALI)
			return self::to_digits($out, self::MODE_JALALI,'.');
		return $out;
	}

	/**
	 * Sets current DataTime object to the given jalali date.
	 * Calls modify as a workaround for a php bug
	 *
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 *
	 * @return Carbon
	 */
	public function setDate($year, $month, $day,$mode=self::MODE_JALALI)
	{
		if($mode==self::MODE_GREGORIAN){
			return parent::setDate($gy,$gm,$gd);
		}

		list($gy,$gm,$gd)=self::to_gregorian($year, $month, $day);
		return parent::setDate($gy,$gm,$gd);
	}

	/**
	 * Sets current DataTime object to the given jalali date and time.
	 * @param int $year
	 * @param int $month
	 * @param int $day
	 * @param int $hour
	 * @param int $minute
	 * @param int $second
	 *
	 * @return static
	 */
	public function setDateTime($year, $month, $day, $hour, $minute, $second=0,$mode=self::MODE_JALALI)
	{
		if($mode == self::MODE_GREGORIAN) {
			return parent::setDateTime($gy, $gm, $gd, $hour, $minute, $second);
		}
		list($gy,$gm,$gd)=self::to_gregorian($year, $month, $day);
		return parent::setDate($gy,$gm,$gd)->setTime($hour, $minute, $second);
	}


}