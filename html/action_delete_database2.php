<?php 
	include('session.php');
	$date= $_POST["date"];
	$date1 = new DateTime($date);
	$date2= $date1->format('Y-m-d'); 
	$date3 =  date("Y-m-d h:i:s");
 	//Establishing Connection with locaslhost Server by passing server_name, user_id and password as a parameter 

	$connection = mysql_connect("10.3.10.197", "root", "password") or die('Could not connect to localhost. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database of localhost');

	$Error = false;

	//===================== Check 24 hours schedule is done or not by Shusmita ===========

	$eventScheduled = "SELECT sum(duration1) duration,sum(dframe) dframe FROM `events` WHERE DATE(start_date) = '$date2' ";

	$eventScheduledResult = mysql_query($eventScheduled, $connection);
	//$num_rows = mysql_num_rows($eventScheduledResult);

	while ($eventScheduledRow = mysql_fetch_assoc($eventScheduledResult)) 
	{
	 	$duration = $eventScheduledRow["duration"];
		$dframe =   $eventScheduledRow["dframe"];
		
		if($duration != NULL && $dframe != NULL)
		{
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
			$totalDuration=$duration+($frameSec*1000);

			if($totalDuration != 86400000){
		 		$dmSec=$totalDuration%1000; 
				$totalDuration=$totalDuration-$dmSec;
				$dSec=($totalDuration/1000)%60; 
				$totalDuration=($totalDuration/1000)-$dSec;
				$dmin=($totalDuration/60)%60; 
				$totalDuration=($totalDuration/60)-$dmin;
				$dhour = ($totalDuration/60);
				//echo " Could not create playlist for ".$date2." . 24 hours should be scheduled to create palylist. Only ".$dhour." : ".$dmin." : ".$dSec." : ".$frame." is scheduled for ". $date2;
				//$Error = true;

			}
		}
		else{
			$Error = true;
			echo " No Data Found for ". $date2;
		}
	}
	//===================== End Check 24 hours schedule is done or not by Shusmita ===========
	
	if(!$Error)
	{ 
		//Establishing Connection with CasperCG Server by passing server_name, user_id and password as a parameter 
		$connection1 = mysql_connect("10.3.10.190","root","password") or die('Could not connect to Primary Server. '.mysql_error());					
		$db1 = mysql_select_db("casparcg", $connection1) or die('Could not select database of Primary Server.');

		//Establishing Connection with CasperCG secondary Server by passing server_name, user_id and password as a parameter
		$connection2 = mysql_connect("10.3.10.191", "root", "password") or die('Could not connect to Secondary Server. ' . mysql_error());;						
		$db2 = mysql_select_db("casparcg", $connection2) or die('Could not select database of Secondary Server.');	

		mysql_query("START TRANSACTION");
		$flag = true;

       //==================================== delete playlist data=====================================

       		$delete_date = Date('Y-m-d H:i:s',strtotime("-2 days"));
       		$sql_delete = "delete from playlist_1 where starttime < '$delete_date' and state = '2'"; 
			
		if($sql_delete != ""){ 
			if (mysql_query($sql_delete, $connection1) === TRUE) {
			    echo "primary data deleted\n";
			} 
			else{
			    $flag = false;
			    echo "primary data not deleted\n" ;
				
			}
		        if (mysql_query($sql_delete, $connection2) === TRUE) {
				    echo "secondary data deleted\n";
			}
			else{
			    $flag = false;
			    echo "secondary data not deleted\n" ;				
			}
		}

       

      //================================================================================================

		$sql = "SELECT * FROM `events` WHERE DATE(start_date) = '$date2' order by start_date";

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
			$program = $row["program"];
			$duration1 = $row["duration1"];
			$dframe = $row["dframe"];
			$asset_id = $row["asset"];
			//$razuna_name =$text.'_'.$row["serial_type"] .'_'. $program .'_EP'.$row["episode"].'_Part_'.$row["segment"];
			//echo $asset_id;
			//=================================================
                 
                        $razuna_name = $program;
		        	
            //=================================================
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
			$check_sql = "SELECT scheduler_id,state FROM `playlist` WHERE scheduler_id = '$id'" ;
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
					$sql1 = "Update playlist set label='$text', name='$razuna_name',timecode='$timecode', starttime='$start_date',state='0',updated='$date3',updatedby='$login_session',asset_id='$asset_id' where scheduler_id='$id'";  
				}
			}
			else{ 
				$sql1 = "INSERT INTO playlist (type, devicename, label, name, channel, videolayer, delay, allowgpi, allowremotetriggering, remotetriggerid, storyid, transition, transitionDuration, tween, direction, seek, length, freezeonload, triggeronnext, autoplay, color, timecode, starttime, state, pushtime, duration, useauto,asset_id,scheduler_id,created,createdby) VALUES ('MOVIE', 'primary', '$text', '$razuna_name', '1', '10', '0', 'false', 'false', '', '', 'CUT', '1', 'Linear', 'RIGHT', '0', '0', 'false', 'false', 'false', 'Transparent', '$timecode', '$start_date', '9', '$start_date', '0', null,'$asset_id','$id','$date3','$login_session')"; 
			}
			if($sql1 != ""){ 
				if (mysql_query($sql1, $connection1) === TRUE) {
				    $msg= "New record for primary server created successfully";
				} 
				else {
				    $flag = false;
				    $msg= "Error: Could not create playlist for primary server." ;
				}
			}

			//================== Secondary Server ===========================
			

			$check_sql1 = "SELECT scheduler_id,state FROM `playlist` WHERE scheduler_id = '$id'" ;
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
		
			if($scheduler_id1 > 0 ){ 
				if($state1 != 1 && $state1 != 2){
					$sql2 = "Update playlist set label='$text', name='$razuna_name',timecode='$timecode', starttime='$start_date',state='0',updated='$date3',updatedby='$login_session',asset_id='$asset_id' where scheduler_id='$id'"; 
				}
			}
			else{
				$sql2 = "INSERT INTO playlist (type, devicename, label, name, channel, videolayer, delay, allowgpi, allowremotetriggering, remotetriggerid, storyid, transition, transitionDuration, tween, direction, seek, length, freezeonload, triggeronnext, autoplay, color, timecode, starttime, state, pushtime, duration, useauto,asset_id,scheduler_id,created,createdby) VALUES ('MOVIE', 'primary', '$text', '$razuna_name', '1', '10', '0', 'false', 'false', '', '', 'CUT', '1', 'Linear', 'RIGHT', '0', '0', 'false', 'false', 'false', 'Transparent', '$timecode', '$start_date', '9', '$start_date', '0', null,'$asset_id','$id','$date3','$login_session')"; 
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
