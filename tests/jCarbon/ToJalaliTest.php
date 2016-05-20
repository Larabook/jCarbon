<?php
/**
 * Created by PhpStorm.
 * User: Universe
 * Date: 1/26/2016
 * Time: 6:42 PM
 */

namespace Tests\jCarbon;


use Carbon\Carbon;
use Carbon\Jcarbon;
use Tests\AbstractTestCase as AbstractTestCase;

class jalali_gregorian_test extends AbstractTestCase
{
    public function testToJalali()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, 1, 12);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }

   public function testToJalaliTwoDigitYearFormat()
    {
        $dateJ = (array)Jcarbon::to_jalali(16, 1, 12);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }

    public function testToJalaliInvalidMonth()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, 13, 12);
        $this->assertSame($dateJ, null);
    }

    public function testToJalaliInvalidDay()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, 1, 32);
        $this->assertSame($dateJ, null);
    }

    public function testToJalaliTwoDigitForOneDigitMonths()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, 01, 12);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }

    public function testToJalaliDefaultYear()
    {
        $dateJ = (array)Jcarbon::to_jalali(null, 1, 12);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }

    public function testToJalaliDefaultMonth()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, null, 12);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }

    public function testToJalaliDefaultDay()
    {
        $dateJ = (array)Jcarbon::to_jalali(2016, 01, null);
        $this->assertSame($dateJ, array(1394, 10, 22));
    }
}