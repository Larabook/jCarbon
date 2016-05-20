<?php

/**
 * Class JDateTime
 *
 * @author      Sina Sharifzade <sharifzadesina@gmail.com>
 * @see         http://php.net/manual/en/class.datetime.php Php DateTime class for documention
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @copyright   2015 Sina Sharifzade
 * @copyright   2003-2012 Sallar Kaboli
 * @copyright   2000 Roozbeh Pournader and Mohammad Toossi
 */
class JDateTime extends DateTime
{
    /** @var array Jalali year day number */
    protected $jDaysInYear = ['normal' => 365, 'leap' => 366];

    /** @var string[] Jalali days */
    protected $jDays = [
        'شنبه', 'یکشنبه',
        'دوشنبه', 'سه‌شنبه',
        'چهارشنبه', 'پنجشنبه',
        'جمعه'
    ];

    /** @var string[] Jalali days short format */
    protected $jDaysShort = [
        'ش', 'ی',
        'د', 'س',
        'چ', 'پ',
        'ج'
    ];

    /** @var array Gregorian days offset To Jalali day offset */
    protected $gDayTojDay = [
        1 => 3, 2 => 4,
        3 => 5, 4 => 6,
        5 => 7, 6 => 1,
        7 => 0
    ];

    /** @var string[] Jalali months */
    protected $jMonths = [
        'فروردین', 'اردیبهشت', 'خرداد',
        'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر',
        'دی', 'بهمن', 'اسفند'
    ];

    /** @var string[] Jalali days short format */
    protected $jMonthsShort = [
        'فرو', 'ارد', 'خرد',
        'تیر', 'مرد', 'شهر',
        'مهر', 'آبا', 'آذر',
        'دی', 'بهم', 'اسف'
    ];

    /** @var array Jalali Ante and Post meridiem */
    protected $jAntePostMeridiem = [
        'am' => 'ق.ظ', 'AM' => 'قبل از ظهر',
        'pm' => 'ب.ظ', 'PM' => 'بعد از ظهر'
    ];

    /** @var string Jalali Day Suffix */
    protected $jDaySuffix = 'ام';

    /**
     * @todo insert jalali conversation here
     *
     * @inheritdoc
     *
     * @return $this
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
    }

    /**
     * @inheritdoc
     */
    public function format($format)
    {
        list($jYear, $jMonth, $jDay) = $this->toJalali(
            parent::format('Y'),
            parent::format('n'),
            parent::format('j')
        );

        $out = '';
        $len = strlen($format);

        for ($i = 0; $i < $len; $i++) {
            $sub = substr($format, $i, 1);
            if ('\\' === $sub) {
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
                    $out .= $this->getDayName($this->gDayTojDay[parent::format('N')], true);
                    break;
                case 'j':
                    $out .= $jDay;
                    break;
                case 'l':
                    $out .= $this->getDayName($this->gDayTojDay[parent::format('N')]);
                    break;
                case 'N':
                    $out .= $this->gDayTojDay[parent::format('N')];
                    break;
                case 'S':
                    $out .= $this->jDaySuffix;
                    break;
                case 'w':
                    $out .= $this->gDayTojDay[parent::format('N')] - 1;
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
                    $out .= $this->getMonthName($jMonth);
                    break;
                case 'm':
                    $out .= sprintf('%02d', $jMonth);
                    break;
                case 'M':
                    $out .= $this->getMonthName($jMonth, true);
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
                    $out .= $this->jAntePostMeridiem[parent::format('a')];
                    break;
                case 'A':
                    $out .= $this->jAntePostMeridiem[parent::format('A')];
                    break;

                // Full Dates
                case 'c':
                    $out .= sprintf(
                        '%04d-%02d-%02dT%02d:%02d:%02d%s',
                        $jYear,
                        $jMonth,
                        $jDay,
                        parent::format('H'),
                        parent::format('i'),
                        parent::format('s'),
                        parent::format('P')
                    );
                    break;
                case 'r':
                    $out .= sprintf(
                        '%s، %02d %s %04d %02d:%02d:%02d %s',
                        $this->getDayName($this->gDayTojDay[parent::format('N')], true),
                        $jDay,
                        $jMonth,
                        $jYear,
                        parent::format('H'),
                        parent::format('i'),
                        parent::format('s'),
                        parent::format('P')
                    );
                    break;

                default:
                    $out .= $sub;
            }
        }

        return $out;
    }

    /**
     * Sets the current date of the JDateTime object to a different date.
     *
     * @param int $jYear
     * @param int $jMonth
     * @param int $jDay
     *
     * @return $this
     */
    public function setDate($jYear, $jMonth, $jDay)
    {
        list($year, $month, $day) = $this->toGregorian($jYear, $jMonth, $jDay);

        parent::setDate($year, $month, $day);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return $this
     *
     * @copyright Special thanks to Alireza Tofighi for this method completion
     */
    public function setISODate($jYear, $jWeek, $jDay = 1)
    {
        $dayOffsetInYear = (($jWeek * 7) - 7) + $jDay;

        reset:
        $isLeapYear = $this->isLeapYear($jYear);
        $dayInYear = $isLeapYear ? $this->jDaysInYear['leap'] : $this->jDaysInYear['normal'];

        if ($dayOffsetInYear > $dayInYear) {
            $jYear++;
            $dayOffsetInYear -= $dayInYear;
            goto reset;
        }

        if ($dayOffsetInYear <= 31 * 6) {
            // for all years first 6 month
            $month = ceil($dayOffsetInYear / 31);
            $day = $dayOffsetInYear % 31;
            if ($day === 0) {
                $day = 31;
            }
        } elseif ($dayOffsetInYear <= (30 * 5) + (31 * 6)) {
            // for year from month 7 to 11
            $month = 6;
            $dayOffsetInYear -= 31 * 6;

            $month += ceil($dayOffsetInYear / 30);
            $day = $dayOffsetInYear % 30;
            if ($day === 0) {
                $day = 30;
            }
        } else {
            // for last month (12)
            $month = 12;
            $daysInMonth = $isLeapYear ? 30 : 29;
            $dayOffsetInYear -= (31 * 6) + (30 * 5);

            $day = $dayOffsetInYear % $daysInMonth;
            if ($day === 0) {
                $day = $daysInMonth;
            }
        }

        $this->setDate($jYear, $month, $day);

        return $this;
    }

    /**
     * Parse a string into a new JDateTime object according to the specified format
     *
     * @todo make this better
     *
     * @param string       $format   Format accepted by date().
     * @param string       $time     String representing the time.
     * @param DateTimeZone $timezone A DateTimeZone object representing the desired time zone.
     *
     * @return static
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        $dateTime = new static('now', $timezone);

        // parse date
        $dt = $dateTime->parseFormat($format, $time);

        // if we have timestamp
        if (isset($dt['U'])) {
            // this is unix timestamp so like php change timezone and timestamp
            $dateTime->setTimezone(new DateTimeZone('+00:00'));
            $dateTime->setTimestamp($dt['U']);

            return $dateTime;
        }

        // default date
        isset($dt['year']) or $dt['year'] = $dateTime->format('Y');
        isset($dt['month']) or $dt['month'] = $dateTime->format('m');
        isset($dt['day']) or $dt['day'] = $dateTime->format('d');

        // default time
        if (! isset($dt['hour'], $dt['minute'], $dt['second'])) {
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
     * @param int $gYear  Gregorian year
     * @param int $gMonth Gregorian month
     * @param int $gDay   Gregorian day
     *
     * @return array an array of jalali year month day
     *
     * @copyright 2015 JDF.SCR.IR
     */
    protected function toJalali($gYear, $gMonth, $gDay)
    {
        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $jy = ($gYear <= 1600) ? 0 : 979;
        $gYear -= ($gYear <= 1600) ? 621 : 1600;
        $gy2 = ($gMonth > 2) ? ($gYear + 1) : $gYear;
        $days = (365 * $gYear) + ((int) (($gy2 + 3) / 4)) - ((int) (($gy2 + 99) / 100)) + ((int) (($gy2 + 399) / 400)) - 80 + $gDay + $g_d_m[$gMonth - 1];
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
     * @param int $jYear  Jalali year
     * @param int $jMonth Jalali month
     * @param int $jDay   Jalali day
     *
     * @return array an array of jalali year month day
     *
     * @copyright 2015 JDF.SCR.IR
     */
    protected function toGregorian($jYear, $jMonth, $jDay)
    {
        $gy = ($jYear <= 979) ? 621 : 1600;
        $jYear -= ($jYear <= 979) ? 0 : 979;
        $days = (365 * $jYear) + (((int) ($jYear / 33)) * 8) + ((int) ((($jYear % 33) + 3) / 4)) + 78 + $jDay + (($jMonth < 7) ? ($jMonth - 1) * 31 : (($jMonth - 7) * 30) + 186);
        $gy += 400 * ((int) ($days / 146097));
        $days %= 146097;
        if ($days > 36524) {
            $gy += 100 * ((int) (--$days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }
        $gy += 4 * ((int) (($days) / 1461));
        $days %= 1461;
        $gy += (int) (($days - 1) / 365);
        if ($days > 365) {
            $days = ($days - 1) % 365;
        }
        $gd = $days + 1;
        foreach ([0, 31, (($gy % 4 === 0 and $gy % 100 != 0) or ($gy % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31] as $gm => $v) {
            if ($gd <= $v) {
                break;
            }
            $gd -= $v;
        }

        return [$gy, $gm, $gd];
    }

    /**
     * Returns correct names for week days
     *
     * @param int  $jDay Jalali day number start from one
     * @param bool $shorten
     *
     * @return string|int if int 0 (for Sunday) through 6 (for Saturday)
     */
    protected function getDayName($jDay, $shorten = false)
    {
        return $shorten ? $this->jDaysShort[$jDay - 1] : $this->jDays[$jDay - 1];
    }

    /**
     * Returns correct names for months
     *
     * @param int  $jMonth Jalali month number start from one
     * @param bool $shorten
     *
     * @return string
     */
    protected function getMonthName($jMonth, $shorten = false)
    {
        return $shorten ? $this->jMonthsShort[$jMonth - 1] : $this->jMonths[$jMonth - 1];
    }

    /**
     * Get current day offset in year
     *
     * @param int $jDay   Jalali day number (today)
     * @param int $jMonth Jalali month number (this month)
     *
     * @return int
     */
    protected function getDayOfYear($jDay, $jMonth)
    {
        if ($jMonth > 6) {
            return 186 + (($jMonth - 6 - 1) * 30) + $jDay;
        } else {
            return (($jMonth - 1) * 31) + $jDay;
        }
    }

    /**
     * Get current week offset in year
     *
     * @param int $jDay   Jalali day number (today)
     * @param int $jMonth Jalali month number (this month)
     *
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
     * @todo make this better
     *
     * @param $format string Format accepted by date with some extras.
     * @param $date   string String representing the date.
     *
     * @return array|bool false on fail
     */
    protected function parseFormat($format, $date)
    {
        $jMonthsStr = implode('|', array_merge($this->jMonths, $this->jMonthsShort));
        $jDaysStr = implode('|', array_merge($this->jDays, $this->jDaysShort));
        $jAntePostMeridiemStr = implode('|', $this->jAntePostMeridiem);

        // reverse engineer date formats
        $intactKeys = ['S'];
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
            'z' => '36[0-6]|3[0-5][0-9]|[12][0-9][0-9]|[1-9][0-9]|[0-9]',

            // time
            'G' => '\d+',
            'H' => '\d+',
            'g' => '1[0-2]|0?[0-9]',
            'h' => '1[0-2]|0?[0-9]',
            'a' => $jAntePostMeridiemStr,
            'A' => $jAntePostMeridiemStr,
            'i' => '\d+',
            's' => '\d+',
            'u' => '\d{1,6}',

            // Full Date/Time
            'U' => '\d+'
        ];

        // convert format string to regex
        $regex = '';
        $chars = array_diff(str_split($format), $intactKeys);
        foreach ($chars as $c => $char) {
            $bChar = isset($chars[$c - 1]) ? $chars[$c - 1] : '';
            if ('\\' !== $bChar and isset($keys[$char])) {
                $regex .= "(?P<$char>$keys[$char])";
            } elseif ('\\' === $char) {
                $regex .= $char;
            } else {
                $regex .= preg_quote($char, '#');
            }
        }

        // now try to match it
        if (preg_match('#^' . $regex . '$#', $date, $matches)) {
            $dt = [];
        } else {
            return false;
        }

        // set year
        if (isset($matches['Y'])) {
            $dt['year'] = (int) $matches['Y'];
        } elseif (isset($matches['y'])) {
            // todo: complete here
        }

        // set month
        if (isset($matches['m'])) {
            $dt['month'] = (int) $matches['m'];
        } elseif (isset($matches['n'])) {
            $dt['month'] = (int) $matches['n'];
        } elseif (isset($matches['M'])) {
            if (false !== $key = array_search($matches['M'], $jMonthsShort)) {
                $dt['month'] = $key + 1;
            } elseif (false !== $key = array_search($matches['M'], $this->jMonths)) {
                $dt['month'] = $key + 1;
            }
        } elseif (isset($matches['F'])) {
            if (false !== $key = array_search($matches['F'], $this->jMonths)) {
                $dt['month'] = $key + 1;
            } elseif (false !== $key = array_search($matches['F'], $jMonthsShort)) {
                $dt['month'] = $key + 1;
            }
        }

        // set day
        if (isset($matches['d'])) {
            $dt['day'] = (int) $matches['d'];
        } elseif (isset($matches['j'])) {
            $dt['day'] = (int) $matches['j'];
        }

        // set hour
        if (isset($matches['G'])) {
            $dt['hour'] = (int) $matches['G'];
        } elseif (isset($matches['H'])) {
            $dt['hour'] = (int) $matches['H'];
        } elseif (isset($matches['h']) or isset($matches['g'])) {
            // set main format and time
            if (isset($matches['h'])) {
                $format = 'h';
                $time = $matches['h'];
            } else {
                $format = 'g';
                $time = $matches['g'];
            }

            // set new meridiem if isset
            if (isset($matches['a'])) {
                $jMeridiem = $matches['a'];
            } elseif (isset($matches['A'])) {
                $jMeridiem = $matches['A'];
            } else {
                goto hour;
            }

            // convert meridiem
            if (false !== $meridiem = array_search($jMeridiem, $this->jAntePostMeridiem)) {
                $format .= 'a';
                $time .= $meridiem;
            }

            hour:

            $dt['hour'] = parent::createFromFormat($format, $time, $this->getTimezone())->format('G');
        }

        // set minutes
        if (isset($matches['i'])) {
            $dt['minute'] = $matches['i'];
        }

        // set seconds
        if (isset($matches['s'])) {
            $dt['second'] = $matches['s'];
        }

        return $dt;
    }

    /**
     * Is leap year?
     *
     * @see https://fa.wikipedia.org/wiki/%D8%B3%D8%A7%D9%84_%DA%A9%D8%A8%DB%8C%D8%B3%D9%87
     *
     * @param int $year
     *
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
}
