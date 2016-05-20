<?php

/*
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\jCarbon;

use Tests\AbstractTestCase;
use \Carbon\jCarbon;


class ComparisonTest extends AbstractTestCase
{
    public function testEqualToTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->eq(jCarbon::createFromDate(1378, 1, 1)));
    }

    public function testEqualToFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->eq(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testEqualWithTimezoneTrue()
    {
        $this->assertTrue(jCarbon::create(1378, 1, 1, 12, 0, 0, 'America/Toronto')->eq(jCarbon::create(1378, 1, 1, 9, 0, 0, 'America/Vancouver')));
    }

    public function testEqualWithTimezoneFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1, 'America/Toronto')->eq(jCarbon::createFromDate(1378, 1, 1, 'America/Vancouver')));
    }

    public function testNotEqualToTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->ne(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testNotEqualToFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->ne(jCarbon::createFromDate(1378, 1, 1)));
    }

    public function testNotEqualWithTimezone()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1, 'America/Toronto')->ne(jCarbon::createFromDate(1378, 1, 1, 'America/Vancouver')));
    }

    public function testGreaterThanTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->gt(jCarbon::createFromDate(1359, 12, 31)));
    }

    public function testGreaterThanFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->gt(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testGreaterThanWithTimezoneTrue()
    {
        $dt1 = jCarbon::create(1378, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = jCarbon::create(1378, 1, 1, 8, 59, 59, 'America/Vancouver');
        $this->assertTrue($dt1->gt($dt2));
    }

    public function testGreaterThanWithTimezoneFalse()
    {
        $dt1 = jCarbon::create(1378, 1, 1, 12, 0, 0, 'America/Toronto');
        $dt2 = jCarbon::create(1378, 1, 1, 9, 0, 1, 'America/Vancouver');
        $this->assertFalse($dt1->gt($dt2));
    }

    public function testGreaterThanOrEqualTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->gte(jCarbon::createFromDate(1359, 12, 31)));
    }

    public function testGreaterThanOrEqualTrueEqual()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->gte(jCarbon::createFromDate(1378, 1, 1)));
    }

    public function testGreaterThanOrEqualFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->gte(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testLessThanTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->lt(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testLessThanFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->lt(jCarbon::createFromDate(1359, 12, 31)));
    }

    public function testLessThanOrEqualTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->lte(jCarbon::createFromDate(1378, 1, 2)));
    }

    public function testLessThanOrEqualTrueEqual()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 1)->lte(jCarbon::createFromDate(1378, 1, 1)));
    }

    public function testLessThanOrEqualFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->lte(jCarbon::createFromDate(1359, 12, 31)));
    }

    public function testBetweenEqualTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 15)->between(jCarbon::createFromDate(1378, 1, 1), jCarbon::createFromDate(1378, 1, 31), true));
    }

    public function testBetweenNotEqualTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 15)->between(jCarbon::createFromDate(1378, 1, 1), jCarbon::createFromDate(1378, 1, 31), false));
    }

    public function testBetweenEqualFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1359, 12, 31)->between(jCarbon::createFromDate(1378, 1, 1), jCarbon::createFromDate(1378, 1, 31), true));
    }

    public function testBetweenNotEqualFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->between(jCarbon::createFromDate(1378, 1, 1), jCarbon::createFromDate(1378, 1, 31), false));
    }

    public function testBetweenEqualSwitchTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 15)->between(jCarbon::createFromDate(1378, 1, 31), jCarbon::createFromDate(1378, 1, 1), true));
    }

    public function testBetweenNotEqualSwitchTrue()
    {
        $this->assertTrue(jCarbon::createFromDate(1378, 1, 15)->between(jCarbon::createFromDate(1378, 1, 31), jCarbon::createFromDate(1378, 1, 1), false));
    }

    public function testBetweenEqualSwitchFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1359, 12, 31)->between(jCarbon::createFromDate(1378, 1, 31), jCarbon::createFromDate(1378, 1, 1), true));
    }

    public function testBetweenNotEqualSwitchFalse()
    {
        $this->assertFalse(jCarbon::createFromDate(1378, 1, 1)->between(jCarbon::createFromDate(1378, 1, 31), jCarbon::createFromDate(1378, 1, 1), false));
    }

    public function testMinIsFluid()
    {
        $dt = jCarbon::now();
        $this->assertInstanceOfCarbon($dt->min());
    }

    public function testMinWithNow()
    {
        $dt = jCarbon::create(1390, 1, 1, 0, 0, 0)->min();
        $this->assertCarbon($dt, 1390, 1, 1, 0, 0, 0);
    }

    public function testMinWithInstance()
    {
        $dt1 = jCarbon::create(2013, 12, 31, 23, 59, 59);
        $dt2 = jCarbon::create(1390, 1, 1, 0, 0, 0)->min($dt1);
        $this->assertCarbon($dt2, 1390, 1, 1, 0, 0, 0);
    }

    public function testMaxIsFluid()
    {
        $dt = jCarbon::now();
        $this->assertInstanceOfCarbon($dt->max());
    }

    public function testMaxWithNow()
    {
        $dt = jCarbon::create(1400, 6, 31, 23, 59, 59)->max();
        $this->assertCarbon($dt, 1400, 6, 31,23, 59, 59);
    }

    public function testMaxWithInstance()
    {
        $dt1 = jCarbon::create(1390, 1, 1, 0, 0, 0);
        $dt2 = jCarbon::create(1395, 06, 31, 23, 59, 59)->max($dt1);
        $this->assertCarbon($dt2, 1395, 06, 31, 23, 59, 59);
    }
    public function testIsBirthday()
    {
        $dt = jCarbon::now();
        $aBirthday = $dt->subYear();
        $this->assertTrue($aBirthday->isBirthday());
        $notABirthday = $dt->subDay();
        $this->assertFalse($notABirthday->isBirthday());
        $alsoNotABirthday = $dt->addDays(2);
        $this->assertFalse($alsoNotABirthday->isBirthday());

        $dt1 = jCarbon::createFromDate(1987, 4, 23);
        $dt2 = jCarbon::createFromDate(1392, 9, 26);
        $dt3 = jCarbon::createFromDate(1392, 4, 23);
        $this->assertFalse($dt2->isBirthday($dt1));
        $this->assertTrue($dt3->isBirthday($dt1));
    }

    public function testClosest()
    {
        $instance = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt1 = jCarbon::create(1393, 5, 28, 11, 0, 0);
        $dt2 = jCarbon::create(1393, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
    }

    public function testClosestWithEquals()
    {
        $instance = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt1 = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt2 = jCarbon::create(1393, 5, 28, 14, 0, 0);
        $closest = $instance->closest($dt1, $dt2);
        $this->assertEquals($dt1, $closest);
    }

    public function testFarthest()
    {
        $instance = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt1 = jCarbon::create(1393, 5, 28, 11, 0, 0);
        $dt2 = jCarbon::create(1393, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $Farthest);
    }

    public function testFarthestWithEquals()
    {
        $instance = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt1 = jCarbon::create(1393, 5, 28, 12, 0, 0);
        $dt2 = jCarbon::create(1393, 5, 28, 14, 0, 0);
        $Farthest = $instance->farthest($dt1, $dt2);
        $this->assertEquals($dt2, $Farthest);
    }
}
