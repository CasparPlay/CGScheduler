<?php   
	include('../session.php');
	$date= $_POST["date"];
	$date1 = new DateTime($date);
	$date2= $date1->format('Y-m-d'); 
	$date3 =  date("Y-m-d h:i:s");

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to localhost. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database of localhost');

	$Error = false;

	$eventScheduled = "SELECT * FROM `events_lshape` WHERE DATE(start_date) = '$date2' and pgm_id > 0 ";

	$eventScheduledResult = mysql_query($eventScheduled, $connection);
	$num_rows = mysql_num_rows($eventScheduledResult);

	
	if($num_rows <= 0){
		$Error = true;
		echo " No Data Found for ". $date2;
	}
	
	
	if(!$Error)
	{ 
		//Establishing Connection with CasperCG Server by passing server_name, user_id and password as a parameter 

		$connection1 = mysql_connect("172.16.10.89","root","password") or die('Could not connect to Primary Server. '.mysql_error());					
		$db1 = mysql_select_db("cgprimary", $connection1) or die('Could not select database of Primary Server.');
		
		//Establishing Connection with CasperCG secondary Server by passing server_name, user_id and password as a parameter
		$connection2 = mysql_connect("172.16.10.90", "root", "password") or die('Could not connect to Secondary Server. ' . mysql_error());;						
		$db2 = mysql_select_db("cgsecondary", $connection2) or die('Could not select database of Secondary Server.');	

		mysql_query("START TRANSACTION");

		$flag = true;
		$sql = "SELECT id,text,program,duration1,dframe,asset,pgm_id,pgmName ,(UNIX_TIMESTAMP(start_date)+(sframe*0.04))-(SELECT (UNIX_TIMESTAMP(start_date)+(sframe*0.04)) FROM `events` WHERE id = pgm_id) as time,start_date,sframe FROM `events_lshape` 
WHERE DATE(start_date) = '$date2' and pgm_id > 0 order by start_date,sframe";

		$result = mysql_query($sql, $connection);

		while ($row = mysql_fetch_assoc($result)) 
		{

			if(!$flag){
				break;
			}

		 	$id = $row["id"]; 
			$start_date = $row["start_date"];
			$end_date = $row["end_date"];
			$text = $row["text"];
			$commercial = $row["program"];
			$duration1 = $row["duration1"];
			$dframe = $row["dframe"];
			$asset_id = $row["asset"];
                	$pgm_id = $row["pgm_id"];
		        $program = $row["pgmName"];
			$lstartFrame = intval($row["sframe"]);
			$time=$row["time"];

			$frame=0;
			$frameSec=0;		
			if($dframe >= 25){
				$frame=$dframe%25;
				$dframe1=$dframe-$frame;
				$frameSec=($dframe1/25);
			}
			else{
				$frame=$dframe;
			}
			$totalDuration=$duration1+($frameSec*1000);

			$dmSec=$totalDuration%1000; 
			$totalDuration=$totalDuration-$dmSec;
			$dSec=($totalDuration/1000)%60; 
			$totalDuration=($totalDuration/1000)-$dSec;
			$dmin=($totalDuration/60)%60; 
			$totalDuration=($totalDuration/60)-$dmin;
			$dhour = ($totalDuration/60);
			
			$timecode=$dhour.":".$dmin.":".$dSec.":".$frame;

			
			//================== Primary Server ===========================

			$check_sql = "SELECT scheduler_id,state FROM `lshape` WHERE scheduler_id = '$id'" ;
			$check_result = mysql_query($check_sql, $connection1);
			$check_rows = mysql_num_rows($check_result);
				
			$scheduler_id = 0;
			$state = 0;
			$sql1  = "";
			if ($check_rows > 0) { 

				while ($playlist_row = mysql_fetch_assoc($check_result)) 
				{
					$scheduler_id = $playlist_row["scheduler_id"];
					$state = $playlist_row["state"];
				}
			}
			
			if($scheduler_id > 0 ){ 
				if($state != 1 && $state != 2){
					$sql1 = "Update lshape set type='$text', program='$program',program_id='$pgm_id', duration='$timecode',state='0',updated='$date3',updatedby='$login_session',time=$time where scheduler_id='$id'";  
				}
			}
			else{ 
				$sql1 = "INSERT INTO lshape ( program, program_id, commercial, type, state, duration, time, scheduler_id,created,createdby) VALUES ('$program', '$pgm_id', '$commercial', '$text', '0','$timecode','$time','$id','$date3','$login_session')"; 
			}
			if($sql1 != ""){ 
				if (mysql_query($sql1, $connection1) === TRUE) {
				    $msg= "New record for primary server created successfully. ";
				} 
				else {
				    $flag = false;
				    $msg= "Error: Could not create playlist for primary server.".$sql1 ;
				}
			}

			//================== Secondary Server ===========================
			

			$check_sql1 = "SELECT scheduler_id,state FROM `lshape` WHERE scheduler_id = '$id'" ;
			$check_result1 = mysql_query($check_sql1, $connection2);
			$check_rows1 = mysql_num_rows($check_result1);
				
			$scheduler_id1 = 0;
			$state1 = 0;
			$sql2  = "";
			if ($check_rows1 > 0) {

				while ($playlist_row1 = mysql_fetch_assoc($check_result1)) 
				{
					$scheduler_id1 = $playlist_row1["scheduler_id"];
					$state1 = $playlist_row1["state"];
				}
			}
		
			if($scheduler_id > 0 ){ 
				if($state != 1 && $state != 2){
					$sql2 = "Update lshape set type='$text', program='$program',program_id='$pgm_id', duration='$timecode',state='0',updated='$date3',updatedby='$login_session',time=$time where scheduler_id='$id'";  
				}
			}
			else{ 
				$sql2 = "INSERT INTO lshape ( program, program_id, commercial, type, state, duration, time, scheduler_id,created,createdby) VALUES ('$program', '$pgm_id', '$commercial', '$text', '0','$timecode','$time','$id','$date3','$login_session')"; 
			}
			
			if($sql2 != ""){ 
				if (mysql_query($sql2, $connection2) === TRUE) {
				   	$msg1= "New record for secondary server created successfully";
				} else {
					$flag = false;
				       $msg1="Error: Could not create playlist for secondary server." ;
				}
			}
		 
		}
		if ($flag) {
			mysql_query("COMMIT");
			echo 'Committed '.$msg."  AND ".$msg1;
		} else {        
			mysql_query("ROLLBACK");			
			echo 'Rolled back '.$msg."  AND ".$msg1;
		}

		mysql_close($connection1);
		mysql_close($connection2);

	}
		
	mysql_close($connection); 

	
?>
