<?php 

$message = "wrong answer";
//echo "<script type='text/javascript'>alert('$message');</script>";

require_once("../codebase/connector/scheduler_connector.php");
//echo "<script type='text/javascript'>alert('$message');</script>";

$res=mysql_connect("localhost","root","password");
mysql_select_db("project"); 

$conn = new SchedulerConnector($res);
$conn->render_table("events_lshape","id","start_date,end_date,text,program,format,remark,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment,serial_type,asset,input_type");

//$conn->sort("start_date,sframe","ASC");
//$conn->render_sql("Select start_date,end_date,text,program,format,remark,rec_type,event_pid,event_length,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment from events where cast(start_date as date) between CONCAT_WS('-', year(NOW()),month(NOW()),'01') and cast(NOW() as date)","id","start_date,end_date,text,program,format,remark,rec_type,event_pid,event_length,order_ref,bp_code,duration1,sframe,dframe,eframe,episode,segment");


//echo "<script type='text/javascript'>alert('$conn');</script>";
?>
