<meta charset="utf-8">
<?php

include "parsidate.class.php";

################################
//Disc : ساخت شی نمونه کلاس   

$obj = new ParsiDate;
$date=$obj->showDate("Y/m/d");

//################################
////Disc:  ParsiDate روش سریع دسترسی به کلاس
//
echo "ex.#1 quick access <br>";
echo parsidate()->showDate("Y/m/d");
echo "<br>";

//
//################################
////Disc:  ParsiDate عمليات جمع و کسر از تاريخ
echo "<br>ex..#2  add() method<br/>";
echo parsidate()->add('y',-1) // Sal-1 mishavad
                ->add('m',1)  // Mah+1 mishavad
                ->add('d',5)  // Day+5 mishavad
                ->showDate("Y/m/d");

echo '<br/>';
//################################
////Disc:  بدست آوردن اولین و آخرین روز ماه قبل
echo "<br>ex.#3<br/>";

echo "اولین روز هفته :".parsidate()->beginOfWeek()->showDate("Y/m/d - l");
echo "<br> آخرین روز هقته  :".parsidate()->endOfWeek()->showDate("Y/m/d - l");
echo "<br>";

echo "<br>اولین روز ماه جاری :".parsidate()->beginOfMonth()->showDate("Y/m/d");
echo "<br> آخرین روز ماه جاری  :".parsidate()->endOfMonth()->showDate("Y/m/d");
echo "<br>";

echo "<br>اولین روز ماه قبل :".parsidate()->beginOfLastMonth()->showDate("Y/m/d");
echo "<br>آخرین روز ماه قبل  :".parsidate()->endOfLastMonth()->showDate("Y/m/d");
echo "<br>";

echo "<br> اولین روز ماه  (۲۰ ماه گذشته) :".parsidate()->add('m',-20)->beginOfMonth()->showDate("Y/m/d");
echo "<br> آخرین روز ماه  (۲۰ ماه گذشته) :".parsidate()->add('m',-20)->endOfMonth()->showDate("Y/m/d");
echo "<br>";

echo "<br> اولین روز سال جاری :".parsidate()->beginOfYear()->showDate("Y/m/d");
echo "<br>آخرین روز ماه قبل  :".parsidate()->endOfYear()->showDate("Y/m/d");
echo "<br>";

echo "<br> اولین روز سال قبل :".parsidate()->beginOfLastYear()->showDate("Y/m/d");
echo "<br>آخرین روز سال قبل  :".parsidate()->endOfLastYear()->showDate("Y/m/d");
echo "<br>";

echo '<br/>';
//################################ 
////Disc: ورودی به صورت میلادی و دریافت همان روز و تاریخ در تقویم شمسی
echo "<br>ex.#4 setDate() method<br/>";
echo parsidate()->setDate(1999,null,5,PARSIDATE_GREGORIAN_PARAMS)
                ->showDate("Y/m/d");
echo '<br/>';

//################################
////Disc:  آیا پارسال سال کبیسه بوده است و اگر بوده آخرین روز سال چند شنبه بوده است؟
echo "<br>ex.#5 setJdate() method<br/>";
$obj=parsidate()->add('y',-1); // Sal-1 mishavad
if($obj->isKabise())
    echo $obj->endOfYear()->showDate("l");
else
    echo "was not Kabiseh!";
echo '<br/>';