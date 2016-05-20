<?php
/**
 * Class JDateTime
 *
 * @package     JDateTime
 * @author      Sina Sharifzade <sharifzadesina@gmail.com>
 * @copyright   2015 Sina Sharifzade
 * @copyright   2003-2012 Sallar Kaboli
 * @copyright   2000 Roozbeh Pournader and Mohammad Toossi
 * @see         DateTime
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @version     2.3.1
 */
class JDateTime extends DateTime
{
	/**
	 * convert numbers to farsi
	 *
	 * @var string
	 */
	protected $numbersMod = 'en';

	/**
	 * Gregorian month day number
	 *
	 * @var array
	 */
	protected $gDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

	/**
	 * Jalali month day number
	 *
	 * @var array
	 */
	protected $jDaysInMonth = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

	/**
	 * Jalali year day number
	 *
	 * @var array
	 */
	protected $jDaysInYear = ['normal' => 365, 'leap' => 366];

	/**
	 * days
	 *
	 * @var array
	 */
	protected $days = [
		'sat' => [1, 'شنبه'],
		'sun' => [2, 'یکشنبه'],
		'mon' => [3, 'دوشنبه'],
		'tue' => [4, 'سه شنبه'],
		'wed' => [5, 'چهارشنبه'],
		'thu' => [6, 'پنجشنبه'],
		'fri' => [7, 'جمعه']
	];

	/**
	 * length of short type of days
	 *
	 * @var int
	 */
	protected $shortDayLen = 1;

	/**
	 * Jalali months
	 *
	 * @var array
	 */
	protected $jMonths = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];

	/**
	 * length of short type of months
	 *
	 * @var int
	 */
	protected $shortMonthLen = 3;

	/**
	 * jalali Ante meridiem
	 *
	 * @var array
	 */
	protected $anteMeridiem = ['am' => 'ق.ظ', 'AM' => 'قبل از ظهر'];

	/**
	 * jalali Post meridiem
	 *
	 * @var array
	 */
	protected $postMeridiem = ['pm' => 'ب.ظ', 'PM' => 'بعد از ظهر'];

	/**
	 * strftime function format codes
	 *
	 * @var array
	 */
	protected $strftimeFormatCodes = [
		'%a', '%A', '%d', '%e', '%j', '%u', '%w',
		'%U', '%V', '%W',
		'%b', '%B', '%h', '%m',
		'%C', '%g', '%G', '%y', '%Y',
		'%H', '%I', '%l', '%M', '%p', '%P', '%r', '%R', '%S', '%T', '%X', '%z', '%Z',
		'%c', '%D', '%F', '%s', '%x',
		'%n', '%t', '%%'
	];

	/**
	 * for convert Strftime format codes to date format codes
	 *
	 * @var array
	 */
	protected $strftimeToDateCodes = [
		'D', 'l', 'd', 'j', 'z', 'N', 'w',
		'W', 'W', 'W',
		'M', 'F', 'M', 'm',
		'y', 'y', 'y', 'y', 'Y',
		'H', 'h', 'g', 'i', 'A', 'a', 'h:i:s A', 'H:i', 's', 'H:i:s', 'h:i:s', 'H', 'H',
		'D j M H:i:s', 'd/m/y', 'Y-m-d', 'U', 'd/m/y',
		'\n', '\t', '%'
	];

	/**
	 * persian Day Suffix
	 *
	 * @var string
	 */
	protected $persianDaySuffix = 'ام';

	/**
	 * @param DateTimeZone $timezone
	 * @return JDateTime
	 */
	public function __construct(DateTimeZone $timezone = null)
	{
		// TODO: insert jalali conversation here

		parent::__construct('now', $timezone);

		return $this;
	}

	/**
	 * Returns date formatted according to given format.
	 *
	 * @param string $format
	 * @return string
	 */
	public function format($format)
	{
		// Change Strftime format to Date format
		$format = str_replace($this->strftimeFormatCodes, $this->strftimeToDateCodes, $format);

		list($jYear, $jMonth, $jDay) = $this->toJalali(parent::format('Y'), parent::format('n'), parent::format('j'));

		$out = '';
		$len = strlen($format);
		for ($i = 0; $i < $len; $i++) {
			$sub = substr($format, $i, 1);
			if ($sub == '\\') {
				$out .= substr($format, ++$i, 1);
				continue;
			}

			switch ($sub) {
				// Intact Date Format Codes
				case 'B':
				case 'h':
				case 'H':
				case 'g':
				case 'G':
				case 'i':
				case 's':
				case 'I':
				case 'U':
				case 'u':
				case 'Z':
				case 'O':
				case 'P':
				case 'e':
				case 'T':
					$out .= parent::format($sub);
					break;

				// Day
				case 'd':
					$out .= sprintf('%02d', $jDay);
					break;
				case 'D':
					$out .= $this->getDayNames(parent::format('D'), true);
					break;
				case 'j':
					$out .= $jDay;
					break;
				case 'l':
					$out .= $this->getDayNames(parent::format('l'));
					break;
				case 'N':
					$out .= $this->getDayNames(parent::format('l'), false, true);
					break;
				case 'S':
					$out .= $this->persianDaySuffix;
					break;
				case 'w':
					$out .= $this->getDayNames(parent::format('l'), false, true) - 1;
					break;
				case 'z':
					$out .= $this->getDayOfYear($jDay, $jMonth);
					break;

				// Week
				case 'W':
					$out .= $this->getWeekOfYear($jDay, $jMonth);
					break;

				// Month
				case 'F':
					$out .= $this->getMonthNames($jMonth);
					break;
				case 'm':
					$out .= sprintf('%02d', $jMonth);
					break;
				case 'M':
					$out .= $this->getMonthNames($jMonth, true);
					break;
				case 'n':
					$out .= $jMonth;
					break;
				case 't':
					if ($jMonth >= 1 and $jMonth <= 6) {
						$out .= 31;
					} elseif ($jMonth >= 7 and $jMonth <= 11) {
						$out .= 30;
					} elseif ($this->isLeapYear($jYear)) {
						$out .= 30;
					} else {
						$out .= 29;
					}
					break;

				// Year
				case 'L':
					$out .= (int) $this->isLeapYear($jYear);
					break;
				case 'o':
				case 'Y':
					$out .= sprintf('%04d', $jYear);
					break;
				case 'y':
					$out .= $jYear % 100;
					break;

				// Time
				case 'a':
					$out .= (parent::format('a') == 'am') ? $this->anteMeridiem['am'] : $this->postMeridiem['pm'];
					break;
				case 'A':
					$out .= (parent::format('A') == 'AM') ? $this->anteMeridiem['AM'] : $this->postMeridiem['PM'];
					break;

				// Full Dates
				case 'c':
					$out .= sprintf('%04d', $jYear) . '-' . sprintf('%02d', $jMonth) . '-' . sprintf('%02d', $jDay) . 'T' . parent::format('H') . ':' . parent::format('i') . ':' . parent::format('s') . parent::format('P');
					break;
				case 'r':
					$out .= $this->getDayNames(parent::format('l'), true) . '، ' . sprintf('%02d', $jDay) . ' ' . $this->getMonthNames($jMonth, true) . ' ' . sprintf('%04d', $jYear) . ' ' . parent::format('H') . ':' . parent::format('i') . ':' . parent::format('s') . ' ' . parent::format('P');
					break;

				default:
					$out .= $sub;
			}
		}

		if ('en' !== $mod = $this->getNumbersMod()) {
			$out = $this->replaceNumbers($out, $mod);
		}

		return $out;
	}

	/**
	 * Convert numbers to chosen mod
	 * default is en
	 *
	 * @param string $mod support "fa" and "en" for now
	 * @return $this
	 */
	public function setNumbersMod($mod)
	{
		static $availableMode = ['fa', 'en'];

		if (! in_array($mod, $availableMode)) {
			throw new OutOfRangeException(sprintf(
				'%s(): Mode "%s" does not support for now, supported modes: %s.',
				__METHOD__, $mod, implode(', ', $availableMode)
			));
		}

		$this->numbersMod = strtolower($mod);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getNumbersMod()
	{
		return $this->numbersMod;
	}

	/**
	 * Sets the current date of the JDateTime object to a different date.
	 *
	 * @param int $jYear
	 * @param int $jMonth
	 * @param int $jDay
	 * @return DateTime
	 */
	public function setDate($jYear, $jMonth, $jDay)
	{
		list($year, $month, $day) = $this->toGregorian($jYear, $jMonth, $jDay);
		parent::setDate($year, $month, $day);

		return $this;
	}

	/**
	 * Set a date according to the ISO 8601 standard - using weeks and day offsets rather than specific dates.
	 *
	 * @param int $jYear
	 * @param int $jWeek
	 * @param int $jDay
	 * @return DateTime
	 * @copyright special thanks to AliReza Tofighi for this method completion
	 */
	public function setISODate($jYear, $jWeek, $jDay = 1)
	{
		$dayOffInYear = (($jWeek * 7) - 7) + $jDay;

		// current year vars
		$isLeapYear = $this->isLeapYear($jYear);
		$daysInYear = $isLeapYear ? $this->jDaysInYear['leap'] : $this->jDaysInYear['normal'];

		// day offset in year is bigger than one year?
		while ($dayOffInYear > $daysInYear) {
			$jYear++;

			// mute this year all days from day offset
			$dayOffInYear -= $daysInYear;

			// new year vars
			$isLeapYear = $this->isLeapYear($jYear);
			$daysInYear = $isLeapYear ? $this->jDaysInYear['leap'] : $this->jDaysInYear['normal'];
		}

		if ($dayOffInYear <= 31 * 6) {
			// for all years first 6 month
			$month = ceil($dayOffInYear / 31);
			$day = $dayOffInYear % 31;
			if ($day == 0) {
				$day = 31;
			}
		} elseif ($dayOffInYear <= (30 * 5) + (31 * 6)) {
			// for year from month 7 to 11
			$month = 6;
			$dayOffInYear -= 31 * 6;

			$month += ceil($dayOffInYear / 30);
			$day = $dayOffInYear % 30;
			if ($day == 0) {
				$day = 30;
			}
		} else {
			// for last month (12)
			$month = 12;
			$daysInMonth = $isLeapYear ? 30 : 29;
			$dayOffInYear -= (31 * 6) + (30 * 5);

			$day = $dayOffInYear % $daysInMonth;
			if ($day == 0) {
				$day = $daysInMonth;
			}
		}

		$this->setDate($jYear, $month, $day);

		return $this;
	}

	/**
	 * Parse a string into a new JDateTime object according to the specified format
	 * TODO: make this better
	 *
	 * @param string       $format   Format accepted by date().
	 * @param string       $time     String representing the time.
	 * @param DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
	 * @return DateTime
	 */
	public static function createFromFormat($format, $time, $timezone = null)
	{
		$dateTime = new static($timezone);

		// parse date
		$dt = $dateTime->parseFormat($format, $time);

		// if format doesn't match date
		if (empty($dt)) {
			return false;
		}

		// if we have timestamp
		if (isset($dt['U'])) {
			$dateTime->setTimestamp($dt['U']);

			return $dateTime;
		}

		// defaults
		isset($dt['year']) or $dt['year'] = $dateTime->format('Y');
		isset($dt['month']) or $dt['month'] = $dateTime->format('m');
		isset($dt['day']) or $dt['day'] = $dateTime->format('d');
		if (! isset($dt['hour']) and ! isset($dt['minute']) and ! isset($dt['second'])) {
			$dt['hour'] = $dateTime->format('G');
			$dt['minute'] = $dateTime->format('i');
			$dt['second'] = $dateTime->format('s');
		}

		$dateTime->setDate($dt['year'], $dt['month'], $dt['day']);
		$dateTime->setTime(@$dt['hour'], @$dt['minute'], @$dt['second']);

		return $dateTime;
	}

	/**
	 * Gregorian to Jalali Conversion
	 *
	 * @param $year  int Gregorian year
	 * @param $month int Gregorian month
	 * @param $day   int Gregorian day
	 * @return array an array of jalali year month day
	 * @copyright 2015 JDF.SCR.IR
	 */
	protected function toJalali($year, $month, $day)
	{
		$g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
		$jy = ($year <= 1600) ? 0 : 979;
		$year -= ($year <= 1600) ? 621 : 1600;
		$gy2 = ($month > 2) ? ($year + 1) : $year;
		$days = (365 * $year) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100))
			+ ((int) (($gy2 + 399) / 400)) - 80 + $day + $g_d_m[$month - 1];
		$jy += 33 * ((int) ($days / 12053));
		$days %= 12053;
		$jy += 4 * ((int) ($days / 1461));
		$days %= 1461;
		$jy += (int) (($days - 1) / 365);
		if ($days > 365) $days = ($days - 1) % 365;
		$jm = ($days < 186) ? 1 + (int) ($days / 31) : 7 + (int) (($days - 186) / 30);
		$jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));

		return [$jy, $jm, $jd];
	}

	/**
	 * Jalali to Gregorian Conversion
	 *
	 * @param $jYear  int Jalali year
	 * @param $jMonth int Jalali month
	 * @param $jDay   int Jalali day
	 * @return array an array of jalali year month day
	 * @copyright 2015 JDF.SCR.IR
	 */
	protected function toGregorian($jYear, $jMonth, $jDay)
	{
		$gy = ($jYear <= 979) ? 621 : 1600;
		$jYear -= ($jYear <= 979) ? 0 : 979;
		$days = (365 * $jYear) + (((int) ($jYear / 33)) * 8) + ((int) ((($jYear % 33) + 3) / 4))
			+ 78 + $jDay + (($jMonth < 7) ? ($jMonth - 1) * 31 : (($jMonth - 7) * 30) + 186);
		$gy += 400 * ((int) ($days / 146097));
		$days %= 146097;
		if ($days > 36524) {
			$gy += 100 * ((int) (--$days / 36524));
			$days %= 36524;
			if ($days >= 365) $days++;
		}
		$gy += 4 * ((int) (($days) / 1461));
		$days %= 1461;
		$gy += (int) (($days - 1) / 365);
		if ($days > 365) $days = ($days - 1) % 365;
		$gd = $days + 1;
		foreach ([0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] as $gm => $v) {
			if ($gd <= $v) break;
			$gd -= $v;
		}

		return [$gy, $gm, $gd];
	}

	/**
	 * Substring helper
	 *
	 * @param $str
	 * @param $start
	 * @param $length
	 * @return string
	 */
	protected function subStr($str, $start, $length = null)
	{
		if (function_exists('mb_substr')) {
			return mb_substr($str, $start, $length, 'UTF-8');
		}

		return substr($str, $start, ($length === null ? null : $length * 2));
	}

	/**
	 * Returns correct names for week days
	 *
	 * @param string $day Gregorian day in short format
	 * @param bool   $shorten
	 * @param bool   $numeric
	 * @return mixed
	 */
	protected function getDayNames($day, $shorten = false, $numeric = false)
	{
		$day = substr(strtolower($day), 0, 3);
		$day = $this->days[$day];

		return ($numeric) ? $day[0] : (($shorten) ? $this->subStr($day[1], 0, $this->shortDayLen) : $day[1]);
	}

	/**
	 * Returns correct names for months
	 *
	 * @param string $month
	 * @param bool   $shorten
	 * @return string
	 */
	protected function getMonthNames($month, $shorten = false)
	{
		$ret = $this->jMonths[$month - 1];

		return ($shorten) ? $this->subStr($ret, 0, $this->shortMonthLen) : $ret;
	}

	/**
	 * get current day position in year
	 *
	 * @param $jDay   int jalali day number (today)
	 * @param $jMonth int jalali month number (this month)
	 * @return int
	 */
	protected function getDayOfYear($jDay, $jMonth)
	{
		if ($jMonth > 6) {
			return 186 + (($jMonth - 6 - 1) * 30) + $jDay;
		}

		return (($jMonth - 1) * 31) + $jDay;
	}

	/**
	 * get current week position in year
	 *
	 * @param $jDay   int jalali day number (today)
	 * @param $jMonth int jalali month number (this month)
	 * @return int
	 */
	protected function getWeekOfYear($jDay, $jMonth)
	{
		$z = $this->getDayOfYear($jDay, $jMonth);

		return is_int($z / 7) ? ($z / 7) : intval($z / 7 + 1);
	}

	/**
	 * Get info about given date
	 *
	 * @param $format string Format accepted by date with some extras.
	 * @param $date   string String representing the date.
	 * @return array|bool false on fail
	 */
	protected function parseFormat($format, $date)
	{
		// convert numbers to english
		$date = $this->replaceNumbers($date, 'en');

		// create jalali month formats
		$jMonthsShort = array_map(function ($m) {
			return $this->subStr($m, 0, $this->shortMonthLen);
		}, $this->jMonths);
		$jMonthsStr = implode('|', array_merge($this->jMonths, $jMonthsShort));

		// create jalali day formats
		$jDays = array_values(array_map(function ($d) {
			return $d[1];
		}, $this->days));
		$jDaysShort = array_map(function ($d) {
			return $this->subStr($d, 0, $this->shortDayLen);
		}, $jDays);
		$jDaysStr = implode('|', array_merge($jDays, $jDaysShort));

		// meridiem
		$meridiemStr = $this->anteMeridiem['am'] . '|' . $this->postMeridiem['pm'] . '|' . $this->anteMeridiem['AM'] . '|' . $this->postMeridiem['PM'];

		// reverse engineer date formats
		$IntactKeys = ['S'];
		$keys = [
			// year
			'Y' => '\d{1,4}',
			'y' => '\d{2}',

			// month
			'm' => '\d{1,2}',
			'n' => '\d{1,2}',
			'M' => $jMonthsStr,
			'F' => $jMonthsStr,

			// day
			'd' => '\d{1,2}',
			'j' => '\d{1,2}',
			'D' => $jDaysStr,
			'l' => $jDaysStr,
			'S' => $this->persianDaySuffix,
			'z' => '36[0-6]|3[0-5][0-9]|[12][0-9][0-9]|[1-9][0-9]|[0-9]',

			// time
			'G' => '\d+',
			'H' => '\d+',
			'g' => '1[0-2]|0?[0-9]',
			'h' => '1[0-2]|0?[0-9]',
			'a' => $meridiemStr,
			'A' => $meridiemStr,
			'i' => '\d+',
			's' => '\d+',
			'u' => '\d{1,6}',

			// Full Date/Time
			'U' => '\d{' . strlen(time()) . '}'
		];

		// convert format string to regex
		$regex = '';
		$chars = array_diff(str_split($format), $IntactKeys);
		foreach ($chars as $n => $char) {
			$lastChar = isset($chars[$n - 1]) ? $chars[$n - 1] : '';
			$skipCurrent = '\\' == $lastChar;

			if (! $skipCurrent and isset($keys[$char])) {
				$regex .= '(?P<' . $char . '>' . $keys[$char] . ')';
			} elseif ('\\' == $char) {
				$regex .= $char;
			} else {
				$regex .= preg_quote($char);
			}
		}

		// now try to match it
		if (preg_match('#^' . $regex . '$#', $date, $dt)) {
			foreach ($dt as $k => $v) {
				if (is_int($k)) {
					unset($dt[$k]);
				}
			}
		} else {
			return false;
		}

		// set year
		if (isset($dt['Y'])) {
			$dt['year'] = (int) $dt['Y'];
		} elseif (isset($dt['o'])) {
			$dt['year'] = (int) $dt['o'];
		} elseif (isset($dt['y'])) {
			// TODO: make this better
			$year = $this->format('Y');
			$thisYear = (int) substr($year, -2);
			$thisYearPrefix = (int) substr($year, 0, 2);

			if ($dt['y'] > $thisYear) {
				$thisYearPrefix--;
			}

			$dt['year'] = ($thisYearPrefix * 100) + $dt['y'];
		}

		// set month
		if (isset($dt['m'])) {
			$dt['month'] = (int) $dt['m'];
		} elseif (isset($dt['n'])) {
			$dt['month'] = (int) $dt['n'];
		} elseif (isset($dt['M'])) {
			if (($key = array_search($dt['M'], $jMonthsShort)) !== false) {
				$dt['month'] = ($key + 1);
			} elseif (($key = array_search($dt['M'], $this->jMonths)) !== false) {
				$dt['month'] = ($key + 1);
			}
		} elseif (isset($dt['F'])) {
			if (($key = array_search($dt['F'], $this->jMonths)) !== false) {
				$dt['month'] = ($key + 1);
			} elseif (($key = array_search($dt['F'], $jMonthsShort)) !== false) {
				$dt['month'] = ($key + 1);
			}
		}

		// set day
		if (isset($dt['d'])) {
			$dt['day'] = (int) $dt['d'];
		} elseif (isset($dt['j'])) {
			$dt['day'] = (int) $dt['j'];
		}

		// set hour
		if (isset($dt['G'])) {
			$dt['hour'] = (int) $dt['G'];
		} elseif (isset($dt['H'])) {
			$dt['hour'] = (int) $dt['H'];
		} elseif (isset($dt['h']) or isset($dt['g'])) {
			// set main format and time
			if (isset($dt['h'])) {
				$format = 'h';
				$time = $dt['h'];
			} else {
				$format = 'g';
				$time = $dt['g'];
			}

			// set new meridiem if isset
			if (isset($dt['a'])) {
				$jMeridiem = $dt['a'];
			} elseif (isset($dt['A'])) {
				$jMeridiem = $dt['A'];
			} else {
				goto hour;
			}

			// convert meridiem
			if ($jMeridiem == $this->postMeridiem['pm'] or $jMeridiem == $this->postMeridiem['PM']) {
				$meridiem = 'pm';
			} elseif ($jMeridiem == $this->anteMeridiem['am'] or $jMeridiem == $this->anteMeridiem['AM']) {
				$meridiem = 'am';
			}

			if (isset($meridiem)) {
				$format .= 'a';
				$time .= $meridiem;
			}

			hour:

			$dt['hour'] = parent::createFromFormat($format, $time, $this->getTimezone())->format('G');
		}

		// set minutes
		if (isset($dt['i'])) {
			$dt['minute'] = $dt['i'];
		}

		// set seconds
		if (isset($dt['s'])) {
			$dt['second'] = $dt['s'];
		}

		return $dt;
	}

	/**
	 * is leap year?
	 *
	 * @param $year
	 * @return bool
	 */
	protected function isLeapYear($year)
	{
		$a = 0.025;
		$b = 266;

		if ($year > 0) {
			$leapDays0 = (($year + 38) % 2820) * 0.24219 + $a;
			$leapDays1 = (($year + 39) % 2820) * 0.24219 + $a;
		} elseif ($year < 0) {
			$leapDays0 = (($year + 39) % 2820) * 0.24219 + $a;
			$leapDays1 = (($year + 40) % 2820) * 0.24219 + $a;
		} else {
			return false;
		}

		$frac0 = (int) (($leapDays0 - (int) $leapDays0) * 1000);
		$frac1 = (int) (($leapDays1 - (int) $leapDays1) * 1000);

		if ($frac0 <= $b and $frac1 > $b) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Convert en numbers in string to persian numbers.
	 *
	 * @param string $subject String to convert number.
	 * @param string $mod fa convert to persian number - en convert to english number.
	 * @return string converted string
	 */
	protected static function replaceNumbers($subject, $mod = 'fa')
	{
		static $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
		static $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

		return $mod === 'fa' ? str_replace($en_num, $fa_num, $subject) : str_replace($fa_num, $en_num, $subject);
	}
}
