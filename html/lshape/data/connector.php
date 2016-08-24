<?php 

$message = "wrong answer";
//echo "<script type='text/javascript'>alert('$message');</script>";

require_once("../codebase/connector/scheduler_connector.php");
//echo "<script type='text/javascript'>alert('$message');</script>";

$res=mysql_connect("localhost","root","password");
mysql_select_db("project"); 

$conn = new SchedulerConnector($res);
$conn->render_table("events_lshape","id","start_date,end_date,text,program,format,remark,order_ref,bp_code,duration1,sframe,dframe,eframe,pgmName,segment,serial_type,asset,input_type,pgm_id,rate_agreement,rateAgreement_Line");

$conn->sort("start_date,sframe","ASC");

?>
