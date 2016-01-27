<?php
/**
 * Created by PhpStorm.
 * User: Universe
 * Date: 1/26/2016
 * Time: 6:42 PM
 */

namespace Tests\jCarbon;


use Carbon\jCarbon;
use Tests\AbstractTestCase;

class jalali_gregorian_test extends AbstractTestCase
{
    public function testToJalali()
    {
        list($gy, $gm, $gd) = explode('-', date('Y-m-d'));

        $dateJ = (array)jCarbon::to_jalali($gy, $gm, $gd);
        $dateG = (array)jCarbon::to_gregorian($dateJ[0], $dateJ[1], $dateJ[2]);

        $this->assertEqual($dateG, explode('-', date('Y-m-d')));
    }

    public function testToDigits()
    {
        $jCarbon = new jCarbon();

    }
}