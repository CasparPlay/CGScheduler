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

			$sql=    " Select a.slot,a.text,a.duration,a.frame,b.totalDuration,b.totalFrame,a.hr from "
				." (Select text,count(text),sum(duration1) duration,sum(dframe) frame, "
				." case 	when minute(`start_date`) < 30 then CONCAT(hour(`start_date`),':00:00') "
            			." when minute(`start_date`) >= 30 then CONCAT(hour(`start_date`),':30:00') "
   				." end as slot , hour(`start_date`) as hr "
				." from events "
				." WHERE DATE(start_date) = '$date2' "
				." group by text,slot "
				." order by slot,text)a "
				." join "
				." (Select 	sum(duration1) totalDuration,sum(dframe) totalFrame,"
				." case 	when minute(`start_date`) < 30 then CONCAT(hour(`start_date`),':00:00') "
            			." when minute(`start_date`) >= 30 then CONCAT(hour(`start_date`),':30:00') "
    				." end as slot , hour(`start_date`) as hr "
				." from events "
				." WHERE DATE(start_date) = '$date2' "
				." group by slot "
				." order by slot)b on (a.slot=b.slot and a.hr=b.hr) "
				." order by a.hr,a.slot,a.text ";

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
	<div id='printArea'><p align="center" style="font-family: verdana; font-weight: bold; font-size: 14px;color:black">Schedule Summary for <?php echo $date2; ?> </p>
	<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr bgcolor='#AAA7A7'>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Slot</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Slot Total Duration</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Server Reference</span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Duration</strong></span></td>
	</tr>
	<?php 
	if ($result <> null) { 
	$count=0;
	$oldSlot="";
	$grandTotalDuration=0;
	$grandTotalFrame=0;

	while ($row = mysql_fetch_assoc($result)) {
		$slot=$row["slot"];
		$text=$row["text"];
		$totalDuration = $row["duration"];
		$dframe = $row["frame"];
		$gtotalDuration = $row["totalDuration"];
		$gframe = $row["totalFrame"];
		$grandTotalDuration=$grandTotalDuration+$totalDuration;
		$grandTotalFrame=$grandTotalFrame+$dframe;

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
		$totalDuration=$totalDuration+($frameSec*1000);
		
		$dmSec=$totalDuration%1000; 
		$totalDuration=$totalDuration-$dmSec;
		$dSec=($totalDuration/1000)%60; 
		$totalDuration=($totalDuration/1000)-$dSec;
		$dmin=($totalDuration/60)%60; 
		$totalDuration=($totalDuration/60)-$dmin;
		$dhour = ($totalDuration/60);
			
		$timecode=$dmin.":".$dSec.":".$frame; 

		//=================== Slot total ========================

		$frame1=0;
		$frameSec1=0;
		if($gframe >= 25){
			$frame1=$gframe%25;
			$gframe1=$gframe-$frame1;
			$frameSec1=($gframe1/25);
		}
		else{
		    $frame1=$gframe;
		}
		$gtotalDuration=$gtotalDuration+($frameSec1*1000);
		
		$dmSec1=$gtotalDuration%1000; 
		$gtotalDuration=$gtotalDuration-$dmSec1;
		$dSec1=($gtotalDuration/1000)%60; 
		$gtotalDuration=($gtotalDuration/1000)-$dSec1;
		$dmin1=($gtotalDuration/60)%60; 
		$gtotalDuration=($gtotalDuration/60)-$dmin1;
		$dhour1 = ($gtotalDuration/60);
			
		$gtimecode=$dmin1.":".$dSec1.":".$frame1; 

		//=========================== End =============================
		
		$count=$count+1;
		
		if($slot != $oldSlot){
			$oldSlot= $slot;
		}
		else{
			$slot="";
		}	
	?>
	      <tr>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"><?php echo $slot; ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;font-weight:bold"><?php if($slot != ""){echo $gtimecode;} ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"><?php echo $text; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $timecode; ?></span></td>
	      </tr>

	<?php 
		}
		//=================== Slot total ========================

		$grandTotalDuration=$grandTotalDuration+$totalDuration;
		$grandTotalFrame=$grandTotalFrame+$dframe;
		$frame11=0;
		$frameSec11=0;
		if($grandTotalFrame >= 25){
			$frame11=$grandTotalFrame%25;
			$gframe11=$grandTotalFrame-$frame11;
			$frameSec11=($gframe11/25);
		}
		else{
		    $frame11=$grandTotalFrame;
		}
		$grandTotalDuration=$grandTotalDuration+($frameSec11*1000);
		
		$dmSec11=$grandTotalDuration%1000; 
		$grandTotalDuration=$grandTotalDuration-$dmSec11;
		$dSec11=($grandTotalDuration/1000)%60; 
		$grandTotalDuration=($grandTotalDuration/1000)-$dSec11;
		$dmin11=($grandTotalDuration/60)%60; 
		$grandTotalDuration=($grandTotalDuration/60)-$dmin11;
		$dhour11 = ($grandTotalDuration/60);
			
		$grandtimecode=$dhour11.":".$dmin11.":".$dSec11.":".$frame11; 

		//=========================== End =============================
		?>
		<tr>
		<td align="center"><span  style="font-family: verdana;font-size: 12px;font-weight:bold">Total</span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 12px;font-weight:bold"><?php echo $grandtimecode; ?></span></td>
	      </tr>
	<?php	
	}
	?>

	</table>
	</div>
</form>	
</div>
<?php
} mysql_close($connection);  ?>
</body>
</html>
