<?php

	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ;

	$a=explode(';',$pname);
	$date = $a[0];
	$rate_agreement = $a[1]; 

	$sendData = '';

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select project database.');
	

        $sql = "SELECT distinct rate_agreementNo,startDate,endDate,agent_name,agent_code FROM `rate_agreement` where rate_agreementNo=$rate_agreement and ('$date' between startDate and endDate)";

	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	$contractNo="";

	if($num_rows > 0){
	
		while ($row = mysql_fetch_assoc($result)) 
		{
			$contractNo=$row["rate_agreementNo"];
			$startDate=$row["startDate"];
			$endDate=$row["endDate"];
			$sendData=$row['agent_code']." | ".$row['agent_name'];
		}
	}
	else{
		$sendData="@Error@ Please give a valid rate agreement number.". $sql;
	}	
	mysql_close($connection); 
	echo json_encode($sendData) ;

	
?>

