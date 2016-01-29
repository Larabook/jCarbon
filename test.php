<?php
echo "<meta charset=\"UTF-8\">";

date_default_timezone_set('Asia/tehran');

function showTest($testName,$test,$carbon)
{
	echo "<div style=\" background:rgba(243, 243, 243, 0.7); padding: 10px; margin: 5px 0px;border-radius: 7px; \">";
	echo "Test : <font style='color:gray'>".(is_string($testName)?$testName:$test)."</font>";
	echo "<p>Result : <span style='color:#469B11;'>".(is_callable($test)?$test():eval('return $carbon'.$test.';')) .'</span></p>';
	echo "</div>";
}

require __DIR__ . '/vendor/autoload.php';

use carbon\jCarbon;
/*
$t=new Carbon\jCarbon();
echo "<br>";
print_r($carbon->addDay(22)->format('Y-m-d A',\Carbon\jCarbon::MODE_GREGORIAN));


echo "<br>";
printf("Right now is %s", jCarbon::now()->toDateTimeString());

echo "<br>";
printf("Right now in Vancouver is %s", jCarbon::now('America/Vancouver'));  //implicit __toString()
*/

date_default_timezone_set('Asia/tehran');
// Make for timezone

$carbon=new Carbon\jCarbon('now','Asia/tehran');

//$carbon->digitsType(jCarbon::FARSI_DIGITS); ;
//$carbon->month=3;
//$carbon->rtl(true);
//echo $carbon->toDayDateTimeString(); //->setDate(2016,2,11,12,10,11)

//echo $carbon->setDate(1394,1,1)->format('l'); // شنبه


$tests=[
	"Carbon->day=3"=> function() use($carbon){
		$carbon->day=3;
		return $carbon->format();
	},
	"->format('l')",
	"->setDate(1394,1,1)->format('l')",
	"->copy()->firstOfMonth()",
	"->getWeekStartsAt()",
	"->getWeekEndsAt()",
	"->toDateString()",
	"->setWeekStartsAt(3)"=>function()use($carbon){
	     $carbon->setWeekStartsAt(3);
		return $carbon->getWeekStartsAt();
	},
	"->isToday()"=> function() use($carbon){
//		$carbon->day=3;
		return ($carbon->copy()->isToday()?'today':'not');
	},
	"->isYesterday()"=> function() use($carbon){
//		$carbon->day=3;
		return ($carbon->copy()->setDate(1394,11,8)->isYesterday()?'Yesterday':'not');
	},
	"->isTomorrow()"=> function() use($carbon){
//		$carbon->day=3;
		return ($carbon->copy()->setDate(1394,11,10)->isTomorrow()?'Tomorrow':'not');
	},
];

$newLine='';
foreach($tests as $k=>$test) {
	showTest($k,$test,$carbon);
}

/*
$d = new DateTime();
$xe='first Sunday of '.$d->format('F').' '.$d->format('Y');
echo $d->modify($xe)->format('Y-m-d');*/
