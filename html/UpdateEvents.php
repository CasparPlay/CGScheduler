<?php
	include('session.php');
	
	$pname= json_encode($_POST['name']); 
	$pname= json_decode($pname) ; 	//echo "ppppp";
	$a=explode(';',$pname);
	$sdate = $a[0];
	$edate = $a[1]; 
	$sframe = $a[2];
	$eframe = $a[3]; 
	$id = $a[4]; 
	$input_type = $a[5]; 
	$duration1 = $a[6];
	$dframe = $a[7];
	$text=$a[8];
	$program = $a[9];
	$asset = $a[10];
	$serial_type = $a[11];
	$remark = $a[12];
	$episode = $a[13];
	$segment = $a[14];
	$rate_agreement = $a[15];
	$rec_type=$a[16];

	$date15 = new DateTime($sdate);
	$sdate15= $date15->format('Y-m-d'); 

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to razuna mysql. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database.');
	mysql_query("START TRANSACTION");


	//========================= Calculate StartTime and endTime of inserted event =============================
	$test_sql="SELECT * FROM `events` where date(start_date)='$sdate15'";
	$test_result = mysql_query($test_sql, $connection);
	$num_test_check = mysql_num_rows($test_result);

	if($num_test_check > 0){
	if($input_type == ""){
		$startSql = " Select max(end_date) esdate,max(eframe) esframe from events where start_date = (SELECT max(start_date) FROM `events` where date(start_date)='$sdate15') ";
	}
	if($input_type == "1"){
		$startSql = " Select end_date as esdate,eframe as esframe from events where id=$segment ";
	}
	$result_startSql = mysql_query($startSql, $connection);

	while ($row_startSql = mysql_fetch_assoc($result_startSql)) { 
					
			$event_startdate =new DateTime($row_startSql["esdate"]);//echo json_encode($event_startdate);
			$event_startframe =intval($row_startSql["esframe"]);
			
			//if($event_startdate != null && $event_startframe != null)
			//{
				//$event_startdate = new DateTime($event_startdate1);

				$event_startDateTime = $event_startdate->getTimestamp();
				$event_endDateTime = $event_startDateTime + intval($duration1/1000);
				$event_endFrame = intval($event_startframe) + intval($dframe);
			
				$euframe1=0;
				$euframeSec=0;
				if($event_endFrame>24)
				{
					$euframe1 = $event_endFrame%25;
					$eudframe1 = $event_endFrame-$euframe1;
					$euframeSec = ($eudframe1/25);
				}
				else
				{
					$euframe1 = $event_endFrame;
				}

				$event_enddate =$event_endDateTime +($euframeSec);
				$event_endframe=$euframe1;
			
				$sdate = date("Y-m-d H:i:s", $event_startDateTime);
				$edate = date("Y-m-d H:i:s", $event_enddate);
				$sframe = $event_startframe;
				$eframe=intval($event_endframe);
			}
	//}
	//}
	}

	$date1 = new DateTime($sdate);
	$date2= $date1->format('Y-m-d H:i:s'); 
	$sdate1= $date1->format('Y-m-d'); 
	$edate1 = new DateTime($edate);
	$edate2= $edate1->format('Y-m-d H:i:s'); 
	$eventData=$date2.'|'.$sframe.'|'.$edate2.'|'.$eframe;
	$isError=false;
	//=====================Commercial Checking ======================
	$isComCheck1=1;
	$isComCheck2=1;
	$com_msg1="";
	$com_msg2="";
	if($text == 'COM'){  //============Check max commercial time for all


		//========================== Checking Rate Agreement ===================

		$cs_hour= intval($date1->format('H')); 
		$cs_min= intval($date1->format('i')); 
		$cs_sec= intval($date1->format('s')); 

		$ce_hour= intval($edate1->format('H')); 
		$ce_min= intval($edate1->format('i')); 
		$ce_sec= intval($edate1->format('s')); 
		
		$timeslot="Off Peak Hour";
		$timeband="";

		if( $duration1 > 0 || $dframe > 0){

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
	
		$bSql="SELECT distinct adtype FROM `rate_agreement` where rate_agreementNo=$rate_agreement  and adtype='Branding'";

		$b_check = mysql_query($bSql, $connection);
		$num_rows_b = mysql_num_rows($b_check);
		$branding = '';

		if($num_rows_b > 0){
	
			while ($b_row = mysql_fetch_assoc($b_check)) 
			{

		 		$branding = $b_row['adtype'];	
				
			}
		}

		//===========================Checking For Branding Rate ====================================


		if( $serial_type == 'BONUS') 
			$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0";
			//$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$timeslot' and rate=0";
		else{		
			$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement  and rate>0";
			//$orderSql="SELECT distinct timeslot FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$timeslot' and rate>0";
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
						//$phour = $program_row['shour'];	
						//$pmin = $program_row['smin'];
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
from events where start_date=(
SELECT max(start_date) FROM `events` where  (text='PGM' or text like '%LIVE%') and start_date < '$date2' and date(start_date)='$sdate1' and (duration1+dframe) > 0 
and SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'PART_',2)),'.',1)),'_',1)='01' ";

		if($pgmType == 'PGM'){
			$pgmTimeSql=$pgmTimeSql."and ((SPLIT_STR((SPLIT_STR((SPLIT_STR(upper(program),'EP_',2)),'.',1)),'_',1)))= '$repisode'
) and  (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0 ";	
		}
		else{
			$pgmTimeSql=$pgmTimeSql.") and  (text='PGM' or text like '%LIVE%') and (duration1+dframe) > 0 ";	
		}

				$pgmTime_check = mysql_query($pgmTimeSql, $connection);
				$num_rows_pgmTime = mysql_num_rows($pgmTime_check);
				if($num_rows_pgmTime > 0){	
					while ($pgmTime_row = mysql_fetch_assoc($pgmTime_check)) 
					{
				 		//$rprogram = $program_row['program'];
						$phour = $pgmTime_row['shour'];	
						$pmin = $pgmTime_row['smin'];
						//$rsegment = intval($program_row['segment']);
						//$repisode = intval($program_row['episode']);
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
				 			//break;
						}
						else{
							$isError=true;
							$sendData=$sendData.'@Error@'.' This rate agreement is for Agency : '.$agencyName.'. But the commercial is not for this agency.Please check.' ;
						}
					}
				}

				}

				//=================================================End Agency Name Checking=========================
				
				//============Check Program in Rate Agreement========
				if( ($branding == 'Branding' || $serial_type == 'BONUS') && !$isError) 
					$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0 and (LOCATE(program,'$rprogram') > 0 or program ='Any') ";
					//$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and (LOCATE(program,'$rprogram') > 0 or program ='Any') ";
				else
					$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and (LOCATE(program,'$rprogram') > 0 or program ='Any')";
					//$pgmSql="SELECT distinct timeslot,program FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and (LOCATE(program,'$rprogram') > 0 or program ='Any')";


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
						if(($branding == 'Branding' || $serial_type == 'BONUS')) 
							$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0 and program='$pgmName' and (timeband='$tb' or timeband ='') ";
							//$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and (timeband='$tb' or timeband ='') ";
						else
							$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName' and (timeband='$tb' or timeband ='')";
							//$tbSql="SELECT distinct timeslot,program,timeband FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (timeband='$tb' or timeband ='')";

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
									if($phour == $time_hour && $pmin == $time_min) {
										break;
									}
								}
							}
						}
						else{
							//$isError=true;
							//$sendData=$sendData.'@Error@'.' There is no '.$dtimeslot.' '.$serial_type.' for '.$pgmName.' on '.$tb.'  or any time in this rate agreement.' ;
						}
					}
					//===============End time band Checking =======================	

					//============Check Episode No in Rate Agreement========
					if($pgmName != 'Any' && $branding == 'Branding' && !$isError && $pgmType == 'PGM')
					{ 
						$ep=0;

						$epSql="SELECT distinct timeslot,program,timeband,episode_no FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName'  and adtype='Branding' "; // and episode_no=$repisode
						//$epSql="SELECT distinct timeslot,program,timeband,episode_no FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband' and episode_no=$repisode and adtype='Branding' ";				

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
							$sendData=$sendData.'@Error@'.' There is no '.$dtimeslot.' '.$serial_type.' '. ' Branding for '.$pgmName.' - Episode '.$repisode.' on '.$timeband.' or any time in this rate agreement.'.'   '.$epSql;
						}
					}
					//===============End Episode Checking =======================	

					//===================Check position ==========================
					if(!$isError){
					if($branding == 'Branding' || $serial_type == 'BONUS') 
						$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0 and program='$pgmName'  and (positionName = '$rsegment' or  positionName = '0')  "; //and (episode_no=0 or episode_no=$repisode)
						//$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and (positionName = '$rsegment' or  positionName = '0') and (episode_no=0 or episode_no=$repisode) ";
					else
						$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName' and (positionName = '$rsegment' or  positionName = '0')";
						//$psSql="SELECT distinct timeslot,program,timeband,positionName FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (timeband='$timeband') and (positionName = '$rsegment' or  positionName = '0')";

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
							$sendData=$sendData.'@Error@'.' There is no '.' '.$serial_type.' for '.$pgmName.' in '.$rsegment.' or any position in this rate agreement.' ;
							//$sendData=$sendData.'@Error@'.' There is no '.$dtimeslot.' '.$serial_type.' for '.$pgmName.' on '.$timeband.' in '.$rsegment.' or any position in this rate agreement.' ;
						}
					}
					
					//=====================End position checking=================
					
					//===================Check Ad Type ==========================
					if(!$isError){
					if( ($branding == 'Branding' || $serial_type == 'BONUS')) 
						$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0 and program='$pgmName' and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All') "; //and (episode_no=0 or episode_no=$repisode)
						//$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All') and (episode_no=0 or episode_no=$repisode)";
					else
						$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName' and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All')";
						//$adsSql="SELECT distinct timeslot,program,timeband,positionName,adType FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and (timeband='$timeband') and positionName = '$positionName'  and (adType = 'TVC' or  adType = 'All')";

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
							$sendData=$sendData.'@Error@'.' There is no '.' '.$serial_type.' for : '.$pgmName.' in position '.$positionNam.' of adtype TVC or All in this rate agreement.' ;
							//$sendData=$sendData.'@Error@'.' There is no '.$dtimeslot.' '.$serial_type.' for : '.$pgmName.' on '.$timeband.' in position '.$positionNam.' of adtype TVC or All in this rate agreement.' ;
						}
					}
					//=====================End Ad Type=================

					
				}
				else{
					$isError=true;
					$sendData=$sendData.'@Error@'.' There is no agreement'.' '.$serial_type.' for '.$rprogram.' or any in this rate agreement.' ;
				}
				//============Check Program in Rate Agreement========	
			}
		}
		else{
			$isError=true;
			$sendData=$sendData.'@Error@'.' There is no '.' '.$serial_type.' '.$branding.' in this rate agreement.' ;
		}
		if(!$isError){ 
			$rateLine_id=0;
			if( $serial_type == 'BONUS') 
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate=0 and program='$pgmName' and  positionName='$positionName' and adtype='$adType' ";
				//$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate=0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adtype='$adType' ";
			else if ($branding == 'Branding')
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName'  and positionName='$positionName' and adType='Branding' "; // and episode_no=$repisode 
				//$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adType='Branding' and episode_no=$repisode ";
			else
				$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and rate>0 and program='$pgmName' and positionName='$positionName' and adType='$adType' ";
				//$rateLineSql="SELECT rate_agreementLine_ID FROM `rate_agreement` where rate_agreementNo=$rate_agreement and timeslot='$dtimeslot' and rate>0 and program='$pgmName' and timeband='$timeband' and positionName='$positionName' and adType='$adType' ";

			$rateLine_check = mysql_query($rateLineSql, $connection);
			$num_rows_rateLine = mysql_num_rows($rateLine_check);
			if($num_rows_rateLine > 0){	
				while ($rateLine_row = mysql_fetch_assoc($rateLine_check)) 
				{
					$rateLine_id = $rateLine_row['rate_agreementLine_ID'];	
				}
			}
		}
		}
		//========================== End Checking Rate Agreement ===================	
		if(!$isError){
		$sendDate =  $date1;
		$sendEDate = $edate1;
		
		$sendDate_1 =$sdate1." ".$sendDate->format('H').":00:00";// echo json_encode($sendDate_1);
		$sendDate_2 =$sdate1." ".(intval($sendDate->format('H'))+1).":00:00";// echo json_encode($sendDate_2);
		
		$sql_check="";
		if($input_type == "" || $input_type == "1"){
			$sql_check=	" SELECT coalesce(sum( case when start_date < '$sendDate_1' and end_date > '$sendDate_1' then (TIMESTAMPDIFF(SECOND,'$sendDate_1',end_date)+(eframe*.04)) when start_date < '$sendDate_2' and end_date >= '$sendDate_2' then (((TIMESTAMPDIFF(SECOND,start_date,'$sendDate_2'))-1)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$sendDate_1' and start_date <= '$sendDate_2' ) or (end_date > '$sendDate_1' and end_date <= '$sendDate_2' ) ) ";
		}
		else{
			if($input_type == "0"){
				$sql_check=	" SELECT coalesce(sum( case when start_date < '$sendDate_1' and end_date > '$sendDate_1' then (TIMESTAMPDIFF(SECOND,'$sendDate_1',end_date)+(eframe*.04)) when start_date < '$sendDate_2' and end_date >= '$sendDate_2' then (((TIMESTAMPDIFF(SECOND,start_date,'$sendDate_2'))-1)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$sendDate_1' and start_date <= '$sendDate_2' ) or (end_date > '$sendDate_1' and end_date <= '$sendDate_2' ) ) and id != $id ";
			}
		}

		$result_check = mysql_query($sql_check, $connection);
		$num_rows_check = mysql_num_rows($result_check);

		if($num_rows_check > 0){
	
			while ($row_check = mysql_fetch_assoc($result_check)) 
			{
		 		$total_com = $row_check["duration"];

			}
			$com_duration=$total_com; 
			$d = intval($duration1/1000)+($dframe*0.04); 
			if($sendDate->format('H') != $sendEDate->format('H')){
				$d2 =strtotime($sendDate_2);
				$d3=date("Y-m-d H:i:s", $d2);
				$d4=new DateTime($d3);
				$interval =intval($d2)-intval($sendDate->getTimestamp()); //in second

				$d = ($interval);
				if($sframe>0) 
					$d = (($interval))+((25-intval($sframe))*0.04)-1; 
				}
				$total_com_duration=$com_duration+$d; 
			
				if($total_com_duration > 1020){
					$com_msg1="Commercial is not allowed more than 17 min within 1 hour [".$sendDate_1." - ".$sendDate_2."]".$total_com_duration." interval= ".$interval."  com_duration= ".$com_duration."   d= ".$d;
					$isComCheck1=0;
				}
			}
		//}
		if($sendDate->format('H') != $sendEDate->format('H') && $isComCheck1 == 1 ){
			$sendEDate_1 =$sendEDate->format('Y-m-d')." ".$sendEDate->format('H').":00:00";
			$sendEDate_2 =$sendEDate->format('Y-m-d')." ".(intval($sendEDate->format('H'))+1).":00:00";

			$sql="";
			if($input_type == "" || $input_type == "1"){
				$sql_check=	" SELECT coalesce(sum( case when start_date < '$sendEDate_1' and end_date > '$sendEDate_1' then (TIMESTAMPDIFF(SECOND,'$sendEDate_1',end_date)+(eframe*.04)) when start_date < '$sendEDate_2' and end_date >= '$sendEDate_2' then (((TIMESTAMPDIFF(SECOND,start_date,'$sendEDate_2'))-1)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$sendEDate_1' and start_date <= '$sendEDate_2' ) or (end_date > '$sendEDate_1' and end_date <= '$sendEDate_2' ) ) ";
			}
			else{
				if($input_type == "0"){
					$sql_check=	" SELECT coalesce(sum( case when start_date < '$sendEDate_1' and end_date > '$sendEDate_1' then (TIMESTAMPDIFF(SECOND,'$sendEDate_1',end_date)+(eframe*.04)) when start_date < '$sendEDate_2' and end_date >= '$sendEDate_2' then (((TIMESTAMPDIFF(SECOND,start_date,'$sendEDate_2'))-1)+((25-sframe)*.04)) else ((duration1/1000 )+(dframe*0.04)) end ),0) as duration FROM `events`  WHERE text='COM' and ((start_date >= '$sendEDate_1' and start_date <= '$sendEDate_2' ) or (end_date > '$sendEDate_1' and end_date <= '$sendEDate_2' ) ) and id != $id ";
				}
			}

			$result_check1 = mysql_query($sql_check, $connection);
			$num_rows_check1 = mysql_num_rows($result_check1);

			if($num_rows_check1 > 0){
	
				while ($row_check1 = mysql_fetch_assoc($result_check1)) 
				{
			 		$total_com = $row_check1["duration"];

				}
				$com_duration=$total_com; 
				$d2 =strtotime($sendEDate_2);
				$interval =intval($sendEDate->getTimestamp())-intval($d2); //in second
				//$interval = $sendEDate->diff($d2);
				$d = ($interval);
				if($eframe>0) 
					$d = (($interval))+((25-intval($eframe))*0.04); 
		
				$total_com_duration=$com_duration+$d; 

				if($total_com_duration > 1020){
					$com_msg2="Commercial is not allowed more than 17 min within 1 hour [".$sendDate_1." - ".$sendDate_2."]";
					$isComCheck2=0;
				}
			}                             
		       
		}
			


		if(intval($isComCheck1) == 0 || intval($isComCheck2) == 0 ){ 
			$sendData='@Error@'." ".$com_msg1."".$com_msg2;
		}
	}
	}
	//=====================End

	if(intval($isComCheck1) != 0 && intval($isComCheck2) != 0 && !$isError){

	$sql="";
 	$sendData="";
	$flag = true;
	$insertsql = "";
	$newID=0;
	if($text != 'COM' ) {$rate_agreement=0;$rateLine_id=0;}
	if($text == 'COM' && $duration1 == 0 && $dframe == 0) {$rate_agreement=0;$rateLine_id=0;}
	if( $input_type == "1" || $input_type == ""){ //$input_type == "" || 
		$insertsql=" insert into events (text,serial_type,program,episode,segment,asset,format,remark,start_date,end_date,sframe,eframe,duration1,dframe,input_type,created,createdby,rate_agreement,rateAgreement_Line,rateValidated) values ('$text','$serial_type','$program','$episode','$segment','$asset','$format','$remark','$date2','$edate2',$sframe,$eframe,$duration1,$dframe,'0',now(),'$login_session',$rate_agreement,0,0)";
	}
	if( $input_type == "0"){
		$insertsql=" Update events set text = '$text',  serial_type= '$serial_type', program='$program',episode='$episode', segment='$segment', asset='$asset', format='$format', remark='$remark', start_date='$date2', end_date='$edate2', sframe=$sframe, eframe=$eframe, duration1=$duration1, dframe=$dframe, input_type='0',updated=now(),updatedby='$login_session',rate_agreement=$rate_agreement ,rateValidated=0  where id=$id";
	}
		$result2 = mysql_query($insertsql, $connection);

		if($result2 === TRUE){
			$flag = true; 
			$newID=mysql_insert_id(); //$sendData=$insertsql;
		}
		else{ 
			$flag = false;
			//$sendData=$insertsql;
			//$sendData=$insertsql;
		}
	//}

	

	$sql=" Select * from  `events`  where  start_date >= '$date2' and DATE(start_date) = '$sdate1' and (id != $id and id != $newID) order by start_date,sframe";
	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	$global_enddate = -1;
	$global_endframe = -1;

	$i=0;
	if ($num_rows > 0) { 		
		while ($row = mysql_fetch_assoc($result)) {					
			if(!$flag){
				break;
			}
			$i=$i+1;
			$event_sdate = new DateTime($row["start_date"]);
			$event_edate = new DateTime($row["end_date"]);
			$event_sframe = $row["sframe"];
			$event_eframe = $row["eframe"];
			$event_duration = $row["duration1"];
			$event_dframe = $row["dframe"];
			$event_id = $row["id"];

			$update_startTime = $event_sdate->getTimestamp();
			$update_endTime = $event_sdate->getTimestamp();

			$update_startFrame = $event_sframe;
			$update_endFrame = $event_eframe;

			
			if(($input_type == "1") || ($input_type == "0"))
			{

				if($global_enddate == -1)
				{
					$global_enddate = $edate1;
				}
				if($global_endframe == -1)
				{
					$global_endframe = $eframe;
				}

				$update_startTime = $global_enddate->getTimestamp(); 

				$update_endTime = $update_startTime + intval($event_duration/1000);
				
				$update_startFrame = intval($global_endframe) ;
				$update_endFrame = $update_startFrame + intval($event_dframe);
				
				$uframe1=0;
				$uframeSec=0;
				if($update_startFrame>24)
				{
					$uframe1 = $update_startFrame%25;
					$udframe1 = $update_startFrame-$uframe1;
					$uframeSec = ($udframe1/25);
				}
				else
				{
					$uframe1 = $update_startFrame;
				}

				$ueframe1=0;
				$ueframeSec=0;
				if(intval($update_endFrame)>24)
				{
					$ueframe1 =$update_endFrame%25;
					$uedframe1=$update_endFrame-$ueframe1;
					$ueframeSec=($uedframe1/25);
				}
				else
				{
					$ueframe1 =$update_endFrame;
				}
				
				$update_startTime=$update_startTime+($uframeSec);
				$update_endTime=$update_endTime+($ueframeSec);
				
				$update_start_date = date("Y-m-d H:i:s", $update_startTime);
				$update_end_date = date("Y-m-d H:i:s", $update_endTime);
				$update_sframe=$uframe1;
				$update_eframe=$ueframe1;

				$sql1="";		
				if($event_id > 0){
				$sql1=" Update `events` set start_date='$update_start_date', end_date='$update_end_date',sframe=$update_sframe,eframe=$update_eframe,updated=now(),updatedby='$login_session' , rateValidated=0  where id=$event_id";
				//echo json_encode($sql1);
				}

				$result1 = mysql_query($sql1, $connection);

				if($result1 === TRUE){
					 $flag = true;
				}
				else{ 
					$flag = false;
				}

				$global_endframe = $ueframe1;
				$global_enddate = new DateTime($update_end_date);
			}
		}
	}

	if ($flag) {
		mysql_query("COMMIT");
		$sendData=$sendData. 'COMMIT';
	} else {        
		mysql_query("ROLLBACK");			
		$sendData=$sendData.'@Error@';
	}
	}
	mysql_close($connection);
	$sendData='----'.$sendData.'?'.$newID.';'.$eventData;
	echo json_encode($sendData); //date_create($sendDate_2)->getTimestamp());
	
?>

