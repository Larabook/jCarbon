<?php
/**
 * Created by PhpStorm.
 * User: iraitc
 * Date: 1/28/2016
 * Time: 04:29 PM
 */

include "src/jDateTime.php";
include "src/jDate.php";
$t=new Morilog\Jalali\jDate();
//$t->setTimeZone(new DateTimeZone('Asia/tehran'));
//$t->day=3;
//$t->month=3;
echo "<br>";
echo $t->forge()->format('Y-m-d h:i:s'); //->setDate(2016,2,11,12,10,11)