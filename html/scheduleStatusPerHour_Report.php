<?php 
	include('session.php');
	$Err= "";
	$sql="";
	if (isset($_POST['submit'])) {

	    	$date= $_POST["date"];
		$date1 = new DateTime($date);
		$date2= $date1->format('Y-m-d'); 
		 
		 if($date == ""){
			$Err="   Please enter date.";
		 }

		if($Err == ""){

		 	$connection = mysql_connect("localhost", "root", "password");
			$db = mysql_select_db("project", $connection);

			$sql="";

			$sql=" SELECT * FROM `events` WHERE DATE(start_date) = '$date2' ";

			$result = mysql_query($sql, $connection);
			$result1 = mysql_query($sql, $connection);
			$num_rows = mysql_num_rows($result1);  
			if ($num_rows <= 0) {
				$Err = "No Data Found.";
			}
		}
	}
?>

<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="jquery-ui.css">
<script src="jquery-1.10.2.js"></script>
<script src="jquery-ui.js"></script>
<script>
	  $(function() {
	    $( "#datepicker" ).datepicker({
	      showButtonPanel: true
	    });
	  });
	function printDiv(divName) {
		 var printContents = document.getElementById('printArea').innerHTML;
		 w=window.open();
		 w.document.write(printContents);
		 w.print();
		 w.close();
	}
</script>
<title>Scheduler list</title>
</head>

<body>

<div style="border:0px solid red;height:60px;width:50%;margin-left:20%">
	<div style="height:55px;width:100px;border:0px solid black;margin-left:30%;float:left;"><a href = "profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a> </div>
	<div style="height:55px;width:100px;border:0px solid red;margin-left:80%;"><a href = "logout.php"><br><img src="imgs/logout.png" alt=""  height=60% width=80% style="margin-top:-8px"></img></a></div>
</div>

<?php
if($login_session != "") {
?>
<div id="content">
<form id="form1" name="form1" method="post" action="">
	<?php
	if($Err == "") {
	?> </br><?php }  ?>
	<table width="98%" border="1" align="center" cellpadding="1" cellspacing="1" bgcolor='#D9BDBD' >
	<tr align="center" valign="center">
 	 	<td width="5%" align="center">
			<font size="3">&nbsp;Date: &nbsp;&nbsp;&nbsp;</font><input type="text" name="date" id="datepicker" value="<?php if($Err == '')echo $date2; ?>" />
  			<input name="submit" type="submit" id="submit" value="Show" style="height:30px;width:100px;color:black;font-weight:bold"/> 
			<input name="print" type="button" id="print" value="Print" onclick=" printDiv('printArea');" style="height:30px;width:100px;color:black;font-weight:bold"/><span style="font-family: verdana; font-weight: bold; font-size: 14px;color:red">&nbsp;&nbsp;&nbsp;<?php echo $Err; ?></span>
   		</td>
	</tr>
	</table>
	</br>
	<div id='printArea'><p align="center" style="font-family: verdana; font-weight: bold; font-size: 14px;color:black">Schedule Status for <?php echo $date2; ?> </p>
	<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr bgcolor='#AAA7A7'>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Slot</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">PGM</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">LIVE</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">COM</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">PROMO</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">FILLER</span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Duration</strong></span></td>
	</tr>
	<?php 
	if ($result <> null) {
	$grandTotalDuration=0;
	$grandTotalFrame=0;
	$pgmgrandTotalDuration=0;
	$pgmgrandTotalFrame=0;
	$livegrandTotalDuration=0;
	$livegrandTotalFrame=0;
	$comgrandTotalDuration=0;
	$comgrandTotalFrame=0;
	$promograndTotalDuration=0;
	$promograndTotalFrame=0;
	$fillergrandTotalDuration=0;
	$fillergrandTotalFrame=0;
	for($i=0;$i<24;$i++){
			$hour=$i;
			$hour2=$i+1;
			if($i < 10) { $hour='0'.$i;}
			if($hour2 < 10) { $hour2='0'.$hour2;} 
			
			$sdate= $date2." ".$hour.":"."00".":00";
			$edate= $date2." ".$hour2.":"."00".":00"; 
 			if($hour2 > 23){
				$date = DateTime::createFromFormat('Y-m-d', $date2);
				$date->modify('+1 day');
				$date=$date->format('Y-m-d');
				$edate= $date." "."00".":"."00".":00"; 
			}

	$sql=" Select 	sum(case when text='PGM' then duration else 0 end) pgmduration
	,sum(case when text='LIVE' then duration else 0 end) liveduration
	,sum(case when text='COM' then duration else 0 end) comduration
	,sum(case when text='PROMO' then duration else 0 end) promoduration
	,sum(case when text='FILLER' then duration else 0 end) fillerduration
	,sum(duration) duration
	,sum(case when text='PGM' then durationFrame else 0 end) pgmdurationFrame
	,sum(case when text='LIVE' then durationFrame else 0 end) livedurationFrame
	,sum(case when text='COM' then durationFrame else 0 end) comdurationFrame
	,sum(case when text='PROMO' then durationFrame else 0 end) promodurationFrame
	,sum(case when text='FILLER' then durationFrame else 0 end) fillerdurationFrame
	,sum(durationFrame) durationFrame

	From(
		SELECT text,coalesce(sum( case when start_date < '$sdate' and ((end_date = '$sdate' and eframe > 0) or end_date > '$sdate' )
		then (TIMESTAMPDIFF(SECOND,'$sdate',end_date)) 
		when start_date < '$edate' and end_date >= '$edate' 
		then (((TIMESTAMPDIFF(SECOND,start_date,'$edate'))-1)) 
		else ((duration1/1000 )) end ),0) as duration 
		,coalesce(sum( case when start_date < '$sdate' and ((end_date = '$sdate' and eframe > 0) or end_date > '$sdate' )
		then (eframe) 
		when start_date < '$edate' and end_date >= '$edate' 
		then (25-sframe) 
		else dframe end ),0) as durationFrame 
	FROM `events` 
	WHERE ((start_date >= '$sdate' and start_date < '$edate' ) or ((end_date = '$sdate' and eframe > 0) or end_date > '$sdate' and end_date <= '$edate' ) ) 
	group by text)a"; //echo $sql;

	$result = mysql_query($sql, $connection);  

	if ($result <> null) { 

	while ($row = mysql_fetch_assoc($result)) {

		$text=$row["text"];
		$duration=$row['duration'];
		$dframe=$row['durationFrame'];
		$pgmduration=$row['pgmduration'];
		$pgmdframe=$row['pgmdurationFrame'];
		$liveduration=$row['liveduration'];
		$livedframe=$row['livedurationFrame'];
		$comduration=$row['comduration'];
		$comdframe=$row['comdurationFrame'];
		$promoduration=$row['promoduration'];
		$promodframe=$row['promodurationFrame'];
		$fillerduration=$row['fillerduration'];
		$fillerdframe=$row['fillerdurationFrame'];

		//==================for Total=================
		$grandTotalDuration=$grandTotalDuration+$duration;
		$grandTotalFrame=$grandTotalFrame+$dframe;
		$pgmgrandTotalDuration=$pgmgrandTotalDuration+$pgmduration;
		$pgmgrandTotalFrame=$pgmgrandTotalFrame+$pgmdframe;
		$livegrandTotalDuration=$livegrandTotalDuration+$liveduration;
		$livegrandTotalFrame=$livegrandTotalFrame+$livedframe;
		$comgrandTotalDuration=$comgrandTotalDuration+$comduration;
		$comgrandTotalFrame=$comgrandTotalFrame+$comdframe;
		$promograndTotalDuration=$promograndTotalDuration+$promoduration;
		$promograndTotalFrame=$promograndTotalFrame+$promodframe;
		$fillergrandTotalDuration=$fillergrandTotalDuration+$fillerduration;
		$fillergrandTotalFrame=$fillergrandTotalFrame+$fillerdframe;

		//==============End

		$timecode= calc($duration,$dframe);
		$pgmtimecode= calc($pgmduration,$pgmdframe); 
		$livetimecode= calc($liveduration,$livedframe);
		$comtimecode= calc($comduration,$comdframe);
		$promotimecode= calc($promoduration,$promodframe);
		$fillertimecode= calc($fillerduration,$fillerdframe);
	?>
	      <tr>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"><?php echo $i.' - '.($i+1); ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;color:black"><?php echo $pgmtimecode ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;color:black"><?php echo $livetimecode ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;color:black"><?php echo $comtimecode ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;color:black"><?php echo $promotimecode ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;color:black"><?php echo $fillertimecode ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $timecode ; ?></span></td>

	      </tr>

	<?php 
		}
	     }
	}
		//=================== Slot total ========================

		$total_timecode= calc($grandTotalDuration,$grandTotalFrame);
		$total_pgmtimecode= calc($pgmgrandTotalDuration,$pgmgrandTotalFrame); 
		$total_livetimecode= calc($livegrandTotalDuration,$livegrandTotalFrame);
		$total_comtimecode= calc($comgrandTotalDuration,$comgrandTotalFrame);
		$total_promotimecode= calc($promograndTotalDuration,$promograndTotalFrame);
		$total_fillertimecode= calc($fillergrandTotalDuration,$fillergrandTotalFrame);

		//=========================== End =============================
		?>
	<tr>
		<td align="center"><span  style="font-family: verdana;font-size: 12px;font-weight:bold">Total</span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_pgmtimecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_livetimecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_comtimecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_promotimecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_fillertimecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $total_timecode; ?></span></td>
	      </tr>
	<?php	
 }
	?>

	</table>
	</div>
</form>	
</div>
<?php
} 
function calc($duration,$dframe){
	$frame=0;
	$frameSec=0;
	$remainTime=0;	
	
	//if($dframe != null){
		if($dframe > 24){ 
			$frame=$dframe%25;
			$dframe1=$dframe-$frame;
			$frameSec=($dframe1/25);
		}
		else{
		    $frame=$dframe;
		}
	//}

	$totalDuration=$duration+($frameSec);
	$dSec=($totalDuration)%60; 
	$totalDuration=($totalDuration)-$dSec;
	$dmin=($totalDuration/60)%60; 
	$totalDuration=($totalDuration/60)-$dmin;
	$dhour = ($totalDuration/60);
 return  $dhour." : ".$dmin." : ".$dSec." : ".$frame;;
}
mysql_close($connection);  ?>
</body>
</html>
