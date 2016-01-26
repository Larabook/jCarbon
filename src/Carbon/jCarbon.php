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
}