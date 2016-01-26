<meta charset="UTF-8">
<?php

require __DIR__ . '/vendor/autoload.php';

use carbon\jCarbon;
/*
$t=new Carbon\jCarbon();
echo "<br>";
print_r($t->addDay(22)->format('Y-m-d A',\Carbon\jCarbon::MODE_GREGORIAN));


echo "<br>";
printf("Right now is %s", jCarbon::now()->toDateTimeString());

echo "<br>";
printf("Right now in Vancouver is %s", jCarbon::now('America/Vancouver'));  //implicit __toString()
*/

date_default_timezone_set('Asia/tehran');

$t=new Carbon\jCarbon();
echo $t->format('Y/m/d H:i:s'); //->setDate(2016,2,11,12,10,11)