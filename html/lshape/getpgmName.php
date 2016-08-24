<?php

	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ;

	$a=explode(';',$pname);
	$date = $a[0];
	$bpcode = $a[1]; 

	$date15 = new DateTime($date);
	$sdate15= $date15->format('Y-m-d'); 

	$sendData = '';

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select project database.');
	

        $sql = "SELECT * FROM `events` WHERE start_date=(Select max(start_date) from events where start_date <= '$date' and (text='PGM' or text like '%LIVE%') and date(start_date)='$sdate15') and (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0";

	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
	
		while ($row = mysql_fetch_assoc($result)) 
		{
		 	  $sendData=$row["id"].'|'.$row["program"];
		}
	}	
	mysql_close($connection); 
	echo json_encode($sendData) ;

	
?>

