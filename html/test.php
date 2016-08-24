
<?php

//$newDate = Date('Y-m-d H:i:s',strtotime("-3 days"));
/*
$t = "2016-03-13";
$date1 = new DateTime($t);
$date2= $date1->format('Y-m-d'); 
$newDate = $date2.Date(' H:i:s');
echo $newDate;
echo Date('Y-m-d H:i:s',strtotime("-2 days",strtotime($newDate)));
*/
$t = "Duration : 0:26:42";
$r = "Duration : 10.40 s";
/*
$arr = explode(":", $t);

$t = $arr[1].":".$arr[2].":".$arr[3];
echo $t;
echo sizeof($arr);
*/
$arr = explode(":", $r);
$r= $arr[1];
//echo $s;
$r = explode(" ",trim($r));
echo "0:0:".$r[0];
?>
