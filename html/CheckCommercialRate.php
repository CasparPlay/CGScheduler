<?php
	include('session.php');
	
	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ;
	$a=explode(';',$pname);
	$sdate = $a[0];
	$edate = $a[1]; 

	$edate15 = new DateTime($edate);
	$date15 = new DateTime($sdate);
	$sdate15= $edate15->format('Y-m-d');  	
	$sdate16= $date15->format('Y-m-d H:i:s');  	

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to razuna mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database.');
	mysql_query("START TRANSACTION");

	//========================= Calculate StartTime and endTime of inserted event =============================
	$test_sql="SELECT * FROM `events` where date(start_date)='$sdate15' and start_date < '$sdate16' and text='COM' and (duration1+dframe) > 0 order by start_date ";
	$test_result = mysql_query($test_sql, $connection);
	$num_test_check = mysql_num_rows($test_result);

	if($num_test_check > 0){

		while ($row_startSql = mysql_fetch_assoc($test_result)) { 
			$rateLine_id=0;

		$sdate = $row_startSql["start_date"];
		$edate = $row_startSql["end_date"];
		$sframe = $row_startSql["sframe"];
		$eframe = $row_startSql["eframe"];
		$id = $row_startSql["id"];
		$input_type = $row_startSql["input_type"];
		$duration1 =  $row_startSql["duration1"];
		$dframe =  $row_startSql["dframe1"];
		$text= $row_startSql["text"];
		$program =  $row_startSql["program"];
		$asset =  $row_startSql["asset"];
		$serial_type =  $row_startSql["serial_type"];
		$remark =  $row_startSql["remark"];
		$episode =  $row_startSql["episode"];
		$segment =  $row_startSql["segment"];
		$rate_agreement =  $row_startSql["rate_agreement"];

		$date15 = new DateTime($sdate);
		$sdate15= $date15->format('Y-m-d'); 
					

		$date1 = new DateTime($sdate);
		$date2= $date1->format('Y-m-d H:i:s'); 
		$sdate1= $date1->format('Y-m-d'); 
		$edate1 = new DateTime($edate);
		$edate2= $edate1->format('Y-m-d H:i:s'); 
		$eventData=$date2.'|'.$sframe.'|'.$edate2.'|'.$eframe;
		$isError=false;
		$onlyTime=$date1->format('H:i:s');
		//=====================Commercial Checking ======================
		$isComCheck1=1;
		$isComCheck2=1;
		$com_msg1="";
		$com_msg2="";

		//========================== Checking Rate Agreement ===================

		$cs_hour= intval($date1->format('H')); 
		$cs_min= intval($date1->format('i')); 
		$cs_sec= intval($date1->format('s')); 

		$ce_hour= intval($edate1->format('H')); 
		$ce_min= intval($edate1->format('i')); 
		$ce_sec= intval($edate1->format('s')); 
		
		$timeslot="Off Peak Hour";
		$timeband="";

		if($rate_agreement > 0) {


		if($cs_hour >= 18 && $cs_hour <= 23) {
			if(($ce_hour >= 18 && $ce_hour <= 23) || ($ce_hour == 0 && $ce_min == 0 && $ce_sec == 0 && $eframe == 0)) {
				$timeslot="Peak Hour";
			}
		}
		else{
			if($cs_hour == 0){
				if(($ce_hour == 0) || ($ce_hour == 1 && $ce_min == 0 && $ce_sec == 0 && $eframe == 0)) {
					$timeslot="Peak Hour";
				}
			}
		}

		//===========================Checking For Branding Rate ====================================
		
		$bSql="SELECT distinct adtype,timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement  and adtype='Branding'";

		$b_check = mysql_query($bSql, $connection);
		$num_rows_b = mysql_num_rows($b_check);
		$branding = '';

		if($num_rows_b > 0){
	
			while ($b_row = mysql_fetch_assoc($b_check)) 
			{
		 		$branding = $b_row['adtype'];
				$timeslot = $b_row['timeslot'];	// not checking timeslot for branding
			}
		}

		//===========================Checking For Branding Rate ====================================
		if( $serial_type == 'BONUS') 
			$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$timeslot' and rate=0";
		else{		
			$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$timeslot' and rate>0";
		}

		if($branding != '' && $branding == 'Branding'){
			$orderSql=$orderSql." and adtype='Branding'";
		}

		$order_check = mysql_query($orderSql, $connection);
		$num_rows_order = mysql_num_rows($order_check);
		if($num_rows_order > 0){
			while ($order_row = mysql_fetch_assoc($order_check)) 
			{
				
		 		$dtimeslot = $order_row['timeslot'];	
				
				//==============Get Program Name for this COM======
				$programSql="Select text,program,start_date,hour(`start_date`) shour,minute(`start_date`) smin,SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'PART_',2)),'.',1)),'_',1) segment,(SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'EP_',2)),'.',1)),'_',1)) episode,dayname(date(start_date)) as dname from events where start_date=(SELECT max(start_date) FROM `events` where  (text='PGM' or text like '%LIVE%') and start_date < '$date2' and date(start_date)='$sdate1' and (duration1+dframe) > 0 ) and  (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0 ";


				$program_check = mysql_query($programSql, $connection);
				$num_rows_program = mysql_num_rows($program_check);
				if($num_rows_program > 0){	
					while ($program_row = mysql_fetch_assoc($program_check)) 
					{
						$pgmType=$program_row['text'];
				 		$rprogram = $program_row['program'];
						$rsegment = intval($program_row['segment']);
						$epn=$program_row['episode'];
						/*if($epn != null && $epn != '')
							$repisode = intval($epn);
						else $repisode = 0;*/
						$repisode=$epn;
                                                $dayName=$program_row['dname'];
						if($dayName == 'Friday' && (strpos($rprogram, 'RUSS1') !== false) && $rate_agreement==1000001){
							$rprogram=str_replace("RUSS1","SLMN1",$rprogram);
						}
					}
								
				}

				//===================Get Time Band For the program============

$pgmTimeSql="Select program,start_date,hour(`start_date`) shour,minute(`start_date`) smin
,SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'PART_',2)),'.',1)),'_',1) segment
,(SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'EP_',2)),'.',1)),'_',1)) episode   
from events where start_date=(";
            // update 21072016 ===================
		if($pgmType == 'PGM'){
			$pgmTimeSql=$pgmTimeSql."SELECT max(start_date) FROM `events` where (text='PGM') and start_date < '$date2' and date(start_date)='$sdate1' and (duration1+dframe) > 0 and SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'PART_',2)),'.',1)),'_',1) ='01' and ((SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'EP_',2)),'.',1)),'_',1)))= '$repisode'
) and  (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0 ";	
		}
		else{
			$pgmTimeSql=$pgmTimeSql."SELECT max(start_date) FROM `events` where (text like '%LIVE%') and start_date < '$date2' and date(start_date)='$sdate1' and (duration1+dframe) > 0 and SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'PART_',2)),'.',1)),'_',1) ='01') and  (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0 ";	
		}	

				$pgmTime_check = mysql_query($pgmTimeSql, $connection);
				$num_rows_pgmTime = mysql_num_rows($pgmTime_check);
				if($num_rows_pgmTime > 0){	
					while ($pgmTime_row = mysql_fetch_assoc($pgmTime_check)) 
					{
						$phour = $pgmTime_row['shour'];	
						$pmin = $pgmTime_row['smin'];
					}
								
				}

				//=====================================Checking Agency Name =======================================

				if(!$isError){ //agent_code
				$agencySql="SELECT distinct lower(agent_code) bpcode FROM `rate_agreement` where rate_agreementNo=$rate_agreement ";
				
				$agency_check = mysql_query($agencySql, $connection);
				$num_rows_agency = mysql_num_rows($agency_check);

				if($num_rows_agency > 0){	
					while ($agency_row = mysql_fetch_assoc($agency_check)) 
					{
				 		$agencyName = $agency_row['bpcode'];	

						if(strpos(strtolower($program), $agencyName) !== false) {
				 			break;
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@ '.$onlyTime.'------'.$program .' --- '.'Agency Code mismatched (RA : '.$rate_agreement.')';
						}
					}
				}

				}

				//=================================================End Agency Name Checking=========================
				
				//============Check Program in Rate Agreement========
				if( ($branding == 'Branding' || $serial_type == 'BONUS') && !$isError) 
					$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and (LOCATE(program,'$rprogram') > 0 or program ='Any') ";
				else
					$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and (LOCATE(program,'$rprogram') > 0 or program ='Any')";

				$pgm_check = mysql_query($pgmSql, $connection);
				$num_rows_pgm = mysql_num_rows($pgm_check);

				if($num_rows_pgm > 0){	
					while ($pgm_row = mysql_fetch_assoc($pgm_check)) 
					{
				 		$pgmName = $pgm_row['program'];	

						if(strpos($rprogram, $pgmName) !== false) {
				 			break;
						}
					}
					//============Check Time Band in Rate Agreement========
					if($pgmName != 'Any')
					{ 
						$tb=$phour.':'.$pmin;
						/*$tb1=$phour.':'.($pmin+1);
						$tb2=$phour.':'.($pmin-1);	
						
						if(($pmin+1) == 60 ) $tb1=($phour+1).':0';
						else $tb1=$phour.':'.($pmin+1);
						
						if($pmin == 0) 	$tb2=($phour-1).':59';
						else $tb2=$phour.':'.($pmin-1);*/

						//============================ 30 min less or greater than time band ==============

						if(($pmin+30) >= 60 ) 
						{


							if($phour == 23) 
							{
								if(($pmin+30) >= 60) {
									$checkhour=$phour+1;
									$checkMin = 0;
									$tb1=$checkhour.':'.$checkMin;
								}
								else{
									$checkhour=$phour;
									$checkMin = ($pmin+30);
									$tb1=$checkhour.':'.$checkMin;
									
								}
							}
							else{
								$pmin1=($pmin+30)%60;
								$tb1=($phour+1).':'.$pmin1;
								$checkhour=$phour+1;
								$checkMin = $pmin1;
							}
						}
						else 
						{
							$tb1=$phour.':'.($pmin+30);
							$checkhour=$phour;
							$checkMin = $pmin+30;
						}

						if(($pmin-30) < 0 ) 
						{
							if($phour == 0) 
							{
								if(($pmin-30) <= 0) {
									$checkhour2=$phour;
									$checkMin2 = 0;
									$tb2=$checkhour2.':'.$checkMin2;
								}
								else{
									$checkhour2=$phour;
									$checkMin2 = ($pmin-30);
									$tb2=$checkhour2.':'.$checkMin2;
								}
							}
							else{
								$checkhour2=$phour-1;
								$checkMin2 = 60+($pmin-30);
								$tb2=$checkhour2.':'.$checkMin2;
							}
						}
						else 
						{
							$checkhour2=$phour;
							$checkMin2 = $pmin-30;
							$tb2=$checkhour2.':'.$checkMin2;
						}

						$hourCheck_min=($checkhour*60)+$checkMin;
						$hourCheck_min2=($checkhour2*60)+$checkMin2;

						//=============================================
									
						if(($branding == 'Branding' || $serial_type == 'BONUS')) 
							$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and (
(((cast(SPLIT_STR(timeband,':',1) as SIGNED INTEGER)*60)+(cast(SPLIT_STR(timeband,':',2) as SIGNED INTEGER))) >= $hourCheck_min2
and ((cast(SPLIT_STR(timeband,':',1) as SIGNED INTEGER)*60)+(cast(SPLIT_STR(timeband,':',2) as SIGNED INTEGER))) < ($hourCheck_min+1))
or timeband ='') ";
						else
							$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (
(((cast(SPLIT_STR(timeband,':',1) as SIGNED INTEGER)*60)+(cast(SPLIT_STR(timeband,':',2) as SIGNED INTEGER))) >= $hourCheck_min2
and ((cast(SPLIT_STR(timeband,':',1) as SIGNED INTEGER)*60)+(cast(SPLIT_STR(timeband,':',2) as SIGNED INTEGER))) < ($hourCheck_min+1))
or timeband ='')";



						$tb_check = mysql_query($tbSql, $connection);
						$num_rows_tb = mysql_num_rows($tb_check);
						if($num_rows_tb > 0){	
							while ($tb_row = mysql_fetch_assoc($tb_check)) 
							{
								$timeband = $tb_row['timeband'];	
								if($timeband != "" && $timeband != null){
									$t=explode(':',$timeband);
									$time_hour=intval($t[1]);
									$time_min=intval($t[2]);

									$timeBandCheck_min=($time_hour*60)+$time_min;

									if($timeBandCheck_min >=  $hourCheck_min2 && $timeBandCheck_min < ($hourCheck_min+1)) 
									{
										break;
										
									}
								}
							}
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@ '.$onlyTime.'---'.$program .' --- '.'Timeband mismatched (RA : '.$rate_agreement.')======='.$rprogram; 
						}
					}
					//===============End time band Checking =======================	

					//============Check Episode No in Rate Agreement========
					if($pgmName != 'Any' && $branding == 'Branding' && !$isError)
					{ 
						$ep=0;

						$epSql="SELECT distinct timeslot,program,timeband,episode_no FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband'  and adtype='Branding' ";

//and episode_no=$repisode
						

						$ep_check = mysql_query($epSql, $connection);
						$num_rows_ep = mysql_num_rows($ep_check);
						if($num_rows_ep > 0){	
							while ($ep_row = mysql_fetch_assoc($ep_check)) 
							{
								$ep = $ep_row['episode_no'];		 						
							}
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@ '.$onlyTime.'------'.$program .' --- '.'Branding Episode mismatched(RA : '.$rate_agreement.')';
						}
					}
					//===============End Episode Checking =======================	

					//===================Check position ==========================
					if(!$isError){
					if($branding == 'Branding' || $serial_type == 'BONUS') 
						$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and (positionName = '$rsegment' or  positionName = '0') "; //and (episode_no=0 or episode_no=$repisode) ";
					else
						$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (timeband='$timeband') and (positionName = '$rsegment' or  positionName = '0')";

						$ps_check = mysql_query($psSql, $connection);
						$num_rows_ps = mysql_num_rows($ps_check);
						if($num_rows_ps > 0){	
							while ($ps_row = mysql_fetch_assoc($ps_check)) 
							{
								$positionName = $ps_row['positionName'];	
								if($positionName == $rsegment) break;
							}
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@ '.$onlyTime.'------'.$program .' --- '.'Position mismatched(RA : '.$rate_agreement.')';
						}
					}
					
					//=====================End position checking=================
					
					//===================Check Ad Type ==========================
					if(!$isError){
					if( ($branding == 'Branding' || $serial_type == 'BONUS')) 
						$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All') "; //and (episode_no=0 or episode_no=$repisode)";
					else
						$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (timeband='$timeband') and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All')";

						$ad_check = mysql_query($adsSql, $connection);
						$num_rows_ad = mysql_num_rows($ad_check);
						if($num_rows_ad > 0){	
							while ($ad_row = mysql_fetch_assoc($ad_check)) 
							{
								$adType = $ad_row['adType'];	
								if($adType == 'TVC') break;
							}
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@  '.$onlyTime.'------'.$program .' --- '.'Ad  mismatched (RA : '.$rate_agreement.')';
						}
					}
					//=====================End Ad Type=================

					
				}
				else{
					$isError=true;
					$sendData=$sendData.'@Error@ '.$onlyTime.'------'.$program .' --- '.'Program mismatched (RA : '.$rate_agreement.')';
				}
				//============Check Program in Rate Agreement========	
			}
			}
		else{
			$isError=true;
			$sendData=$sendData.'@Error@ '.$onlyTime.' ---- '.$program .' --- '.'Timeslot mismatched (RA : '.$rate_agreement.')';
		}
		if(!$isError){ 
			if ($branding == 'Branding')
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adType='Branding' ";
 //and episode_no=$repisode ";
			else if( $serial_type == 'BONUS') 
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adtype='$adType' ";
			 
			else
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adType='$adType' ";

			$rateLine_check = mysql_query($rateLineSql, $connection);
			$num_rows_rateLine = mysql_num_rows($rateLine_check);
			if($num_rows_rateLine > 0){	
				while ($rateLine_row = mysql_fetch_assoc($rateLine_check)) 
				{
					$rateLine_id = $rateLine_row['rate_agreementLine_ID'];	
				}
			}
		}
		//========================== End Checking Rate Agreement ===================	

		if(!$isError && $rateLine_id > 0 ) //
		{
			$sql="";
		 	//$sendData="";
			$flag = true;
			$insertsql = "";
			$newID=0;

			if($text == 'COM' && $duration1 == 0 && $dframe == 0) {$rate_agreement=0;$rateLine_id=0;}

			if( $input_type == "0"){
				$insertsql=" Update events set updated=now(),updatedby='$login_session',rate_agreement=$rate_agreement,rateAgreement_Line=$rateLine_id,rateValidated=1,episode='$repisode'  where id=$id";
			}

			$result2 = mysql_query($insertsql, $connection);

			if($result2 === TRUE){
				$flag = true; 
			}
			else{ 
				$flag = false;
			}

			if ($flag) {
				mysql_query("COMMIT");
				//$sendData=$sendData. 'COMMIT';
			} else {        
				mysql_query("ROLLBACK");			
				$sendData=$sendData.'@Error@';
			}
		}
		else{ 
			continue;
		}

		}
	else{
		$sendData=$sendData.'@Error@ '.$onlyTime.'------'.$program .' --- '.'No Rate Agreement (RA : '.$rate_agreement.')';	
	}
	}
	}

	if($sendData == ""){
	
		$updateOther_sql="Update `events` set updated=now(),updatedby='$login_session',rateValidated=1 where date(start_date) = '$sdate15' and start_date < '$sdate16' and (text !='COM' or (text ='COM' and (duration1+dframe) = 0)) order by start_date ";

		$updateOther_result = mysql_query($updateOther_sql, $connection);

		if($updateOther_result === TRUE){
			mysql_query("COMMIT");
		}
		else{ 
			mysql_query("ROLLBACK");			
			$sendData=$sendData.'@Error@'.' Other events not updated.';
		}
	}

	mysql_close($connection);
	$sendData='----'.$sendData;

	echo json_encode($sendData); 
	
?>

