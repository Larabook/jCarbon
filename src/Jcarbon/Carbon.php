<?php
/**
 * Created by PhpStorm.
 * User: iraitc
 * Date: 1/20/2016
 * Time: 08:14 PM
 */


//require __DIR__ . '/../../vendor/autoload.php';

namespace Jcarbon;
use \Carbon\Carbon as BaseCarbon;

class Carbon extends BaseCarbon
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

}