<?php
	
	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ;
	$a=explode('.',$pname);
	$date1 = $a[0];
	$date2= $a[1]; 
	$input_type = $a[2];
	$id= $a[3]; 
	$total_com=0;

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to razuna mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database.');
	
	$sql="";
	if($input_type == "" || $input_type == "1"){
		$sql=	" SELECT coalesce(sum( case when start_date < '$date1' and end_date > '$date1' then (TIMESTAMPDIFF(SECOND,'$date1',end_date)+(eframe*.04)) when start_date < '$date2' and end_date >= '$date2' then (((TIMESTAMPDIFF(SECOND,start_date,'$date2'))-60)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$date1' and start_date <= '$date2' ) or (end_date > '$date1' and end_date <= '$date2' ) ) ";
}
else{
	if($input_type == "0"){

		$sql=	" SELECT coalesce(sum( case when start_date < '$date1' and end_date > '$date1' then (TIMESTAMPDIFF(SECOND,'$date1',end_date)+(eframe*.04)) when start_date < '$date2' and end_date >= '$date2' then (((TIMESTAMPDIFF(SECOND,start_date,'$date2'))-60)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$date1' and start_date <= '$date2' ) or (end_date > '$date1' and end_date <= '$date2' ) ) and id != $id ";
}
}


	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
	
		while ($row = mysql_fetch_assoc($result)) 
		{
		 	$total_com = $row["duration"];

		}
                 $sendData=$total_com;

	}	
	mysql_close($connection); 
	echo json_encode($sendData) ;

	
?>

