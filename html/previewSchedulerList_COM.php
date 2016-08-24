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
			$sql = "SELECT * FROM `events` WHERE DATE(start_date) = '$date2' and text in ('COM','COM_GFX','BUMPER') order by start_date" ;
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
	<div id='printArea'><p align="center" style="font-family: verdana; font-weight: bold; font-size: 14px;color:black">Scheduled Commercial List for <?php echo $date2; ?> </p>
	<table width="98%" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr bgcolor='#AAA7A7'>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Serial No</span></td>
		<td align="center"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;color:black">Server Reference</span></td>
		<td align="center"><span align="left" align="left" style="font-family:verdana;font-weight: bold;font-size: 12px;color:black">Program Name</span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Duration</strong></span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Start Date</strong></span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>End Date</strong></span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Rate Agreement</strong></span></td>
		<td align="center"><span align="left" style="font-family: verdana;font-family: verdana; font-size: 12px;color:black"><strong>Validated</strong></span></td>
	</tr>
	<?php 
	if ($result <> null) { 
	$count=0;
	while ($row = mysql_fetch_assoc($result)) {
		$text=$row["text"];
		//$razuna_name =$text.'_'.$row["serial_type"] .'_'.$row["program"] .'_EP'.$row["episode"].'_Part_'.$row["segment"];
		$razuna_name = $row["program"];
                $totalDuration = $row["duration1"];
		$dframe = $row["dframe"];
		$dmSec=$totalDuration%1000; 
		$totalDuration=$totalDuration-$dmSec;
		$dSec=($totalDuration/1000)%60; 
		$totalDuration=($totalDuration/1000)-$dSec;
		$dmin=($totalDuration/60)%60; 
		$totalDuration=($totalDuration/60)-$dmin;
		$dhour = ($totalDuration/60);
			
		$timecode=$dhour.":".$dmin.":".$dSec.":".$dframe; 
		$count=$count+1;		
	?>
	      <tr>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"><?php echo $count; ?></span></td>
		<td align="center"><span  style="font-family: verdana;font-size: 11px;"><?php echo $text; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $razuna_name; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $timecode; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $row["start_date"].':'.$row["sframe"];?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $row["end_date"].':'.$row["eframe"]; ?></span></td>

		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;"><?php echo $row["rate_agreement"]; ?></span></td>
		<td align="center"><span align="center" style="font-family: verdana; font-size: 11px;">
		<?php if($row["rateValidated"] == 1)  echo 'Y'; else echo 'N' ?>
		</span></td>
	      </tr>

	<?php 
		}
	}
	?>
	</table></div>
</form>	
</div>
<?php
} mysql_close($connection);  ?>
</body>
</html>
