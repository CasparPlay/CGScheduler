<?php 

$message = "wrong answer";
//echo "<script type='text/javascript'>alert('$message');</script>";

require_once("../codebase/connector/scheduler_connector.php");
//echo "<script type='text/javascript'>alert('$message');</script>";

$res=mysql_connect("localhost","root","password");
mysql_select_db("project"); 

$conn = new SchedulerConnector($res);
//$conn->render_table("events","id","start_date,end_date,text,program,format,remark,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment,serial_type,asset,input_type");

//$p=intval($_POST['dp']);
/*$p=$_POST['dp'];
if($p != null){
$date1 = new DateTime($p);
$sdate1= $date1->format('Y-m-d'); 
$conn->render_sql("Select * from events where (duration1+dframe) > 0  and date(start_date)='$sdate1'","id","start_date,end_date,text,program,format,remark,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment,serial_type,asset,input_type,rate_agreement,rateAgreement_Line");
$conn->sort("start_date,sframe","ASC");
}
else{*/
$conn->render_sql("Select * from events where (duration1+dframe) > 0   and cast(start_date as date) between (CURRENT_DATE - INTERVAL 3 DAY) and (CURRENT_DATE + INTERVAL 2 DAY)","id","start_date,end_date,text,program,format,remark,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment,serial_type,asset,input_type,rate_agreement,rateAgreement_Line");
$conn->sort("start_date,sframe","ASC");
//}


echo $conn;
?>
