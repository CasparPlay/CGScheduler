<?php
	
	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ; 	
	$a=explode(';',$pname);
	$sdate = $a[0];
	$edate = $a[1]; 

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to razuna mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database.');
	
	$sql="";

	$sql=" SELECT coalesce(sum( case when start_date < '$sdate' and end_date > '$sdate' 
	then (TIMESTAMPDIFF(SECOND,'$sdate',end_date)) 
	when start_date < '$edate' and end_date >= '$edate' 
	then (((TIMESTAMPDIFF(SECOND,start_date,'$edate'))-1)) 
	else ((duration1/1000 )) end ),0) as duration 
	,coalesce(sum( case when start_date < '$sdate' and end_date > '$sdate' 
	then (eframe) 
	when start_date < '$edate' and end_date >= '$edate' 
	then (25-sframe) 
	else dframe end ),0) as durationFrame 
FROM `events` 
WHERE text='COM' and ((start_date >= '$sdate' and start_date < '$edate' ) or (end_date > '$sdate' and end_date <= '$edate' ) )";
	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	if ($num_rows > 0) { 		
		while ($row = mysql_fetch_assoc($result)) {	
			$duration=$row['duration'];
			$dframe=$row['durationFrame'];
			
			$frame=0;
			$frameSec=0;
			$remainTime=0;		
			if($dframe >= 25){
				$frame=$dframe%25;
				$dframe1=$dframe-$frame;
				$frameSec=($dframe1/25);
			}
			else{
			    $frame=$dframe;
			}
			if($frame > 0){
				$totalDuration=959-($duration+($frameSec));
				$frame=25-$frame;
			}
			else{
				$totalDuration=960-($duration+($frameSec));
				$frame=0;
			}
	 		//$dmSec=$totalDuration; 
			$totalDuration=$totalDuration;
			$dSec=($totalDuration)%60; 
			$totalDuration=($totalDuration)-$dSec;
			$dmin=($totalDuration/60)%60; 
			$totalDuration=($totalDuration/60)-$dmin;
			$dhour = ($totalDuration/60);

			$sendData=$dhour." : ".$dmin." : ".$dSec." : ".$frame;
		}
	}

	
	mysql_close($connection); 
	echo json_encode($sendData); 
	
?>

