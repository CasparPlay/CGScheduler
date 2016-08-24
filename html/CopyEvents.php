<?php 
	include('session.php');
	$Err= "";
	$sql="";
	$sendData="";
	$flag = true;
	echo $Err;
	if (isset($_POST['submit'])) {

	    	$date= $_POST["date"];
		$date1 = new DateTime($date);
		$date2= $date1->format('Y-m-d'); 

		$todate= $_POST["date1"];
		$todate1 = new DateTime($todate);
		$todate2= $todate1->format('Y-m-d'); 

		/*$shour= $_POST["hour"];
		$sminute= $_POST["minute"];
		$ssecond= $_POST["second"];
		$sframe= $_POST["frame"];

		$ehour= $_POST["ehour"];
		$eminute= $_POST["eminute"];
		$esecond= $_POST["esecond"];
		$eframe= $_POST["eframe"];

		if(intval($shour) < 10) { $shour='0'.$shour;}
		if(intval($sminute) < 10) { $sminute='0'.$sminute;}
		if(intval($ssecond) < 10) { $ssecond='0'.$ssecond;}
		if(intval($sframe) < 10) { $sframe='0'.$sframe;}

		if(intval($ehour) < 10) { $ehour='0'.$ehour;}
		if(intval($eminute) < 10) { $eminute='0'.$eminute;}
		if(intval($esecond) < 10) { $esecond='0'.$esecond;}
		if(intval($eframe) < 10) { $eframe='0'.$eframe;}

		$fromSDate=$date2." ".$shour.":".$sminute.":".$ssecond;
		$fromEDate=$date2." ".$ehour.":".$eminute.":".$esecond;*/
		
		//echo $fromSDate."   ".$fromEDate;

		 if($date == ""){
			$Err="   Please enter from date.";
		 }
		 if($todate == ""){
			$Err="   Please enter to date.";
		 }

		if($Err == ""){

		 	$connection = mysql_connect("localhost", "root", "password");
			$db = mysql_select_db("project", $connection);
			mysql_query("START TRANSACTION");

			$sql="";
			$csql="";
			
			$csql=" SELECT * FROM `events` WHERE date(start_date) = '$todate2'";
			$cresult = mysql_query($csql, $connection);
			$cnum_rows = mysql_num_rows($cresult);  
			if ($cnum_rows > 0) {
				$Err = "Data already exist on ".$todate2;
			}
			else{
				$sql=" SELECT * FROM `events` WHERE date(start_date) = '$date2'";

				$result = mysql_query($sql, $connection);
				$num_rows = mysql_num_rows($result);  
				if ($num_rows <= 0) {
					$Err =  "No Data Found On ".$date2." to copy." ;
				}
				else{   
					while ($row = mysql_fetch_assoc($result)){

						if(!$flag){
							break;
						}
						$event_sdate = $row["start_date"];
						$event_edate = $row["end_date"];
						$sframe = $row["sframe"];
						$eframe = $row["eframe"];
						$duration1 = $row["duration1"];
						$dframe = $row["dframe"];
						$text = $row["text"];
						$asset=$row["asset"];
						$serial_type= $row['serial_type'];
						$program=$row['program'];
						$episode=$row['episode'];
						$segment=$row['segment'];
						$format=$row['format'];
						$remark=$row['remark'];
						$input_type=$row['input_type'];
						$serial_type=$row['serial_type'];

						$arr = explode(" ",$event_sdate);
						$startDate=$arr[0];
						$startTime=$arr[1];
						$start_date=$todate2." ".$startTime;

						$arr1 = explode(" ",$event_edate);
						$endDate=$arr1[0];
						$endTime=$arr1[1];
						$end_date=$todate2." ".$endTime;

						$insertsql=" insert into events (text,serial_type,program,episode,segment,asset,format,remark,start_date,end_date,sframe,eframe,duration1,dframe,input_type) values ('$text','$serial_type','$program','$episode','$segment','$asset','$format','$remark','$start_date','$end_date',$sframe,$eframe,$duration1,$dframe,'$input_type')";

						$result2 = mysql_query($insertsql, $connection);
						if($result2 === TRUE){
							$flag = true;
						}
						else{ 
							$flag = false;
							$sendData=" Could not insert for id ".$row["id"];
						}				
					}
					if ($flag) {
						mysql_query("COMMIT");
						$sendData=$sendData.' Successfully copy data from '.$date2." to ".$todate2;
					} else {        
						mysql_query("ROLLBACK");			
						$sendData='@Error@  '.$sendData;
					}
				}
			}


			mysql_close($connection);	
			//echo $sendData;
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

	    $( "#datepicker1" ).datepicker({
	      showButtonPanel: true
	    });
	
	    document.getElementById("ehour").value="23";
	    document.getElementById("eminute").value="59";
	    document.getElementById("esecond").value="59";
	    document.getElementById("eframe").value="24";

	  });

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
	?> </br><br><?php }  ?>
</br>

 <table width="50%" border="1" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td width="50%" bgcolor="#0080FF" ><div align="center" >
          <div align="center" style="font:18px 'Orienta', sans-serif;color:#fff;"><strong>Copy Events from one date to another date</strong></div>
        </div></td>
      </tr>
</table>     <br>  

<table align="center" border="0" style="width:50%;margin-left:30%">

<tr>
		<td>From Date: </td>
	   	<td><input type="text" name="date" id="datepicker" value="" /></td></tr><tr align="center" valign="center">
	    	</td></tr>
		<!--<tr><td>From Start Time: </td>
		<td >Hour <select id='hour' name='hour' ><?php $i=0; while($i<24 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select> &nbsp; Minute <select id='minute' name='minute' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Second <select name='second' id='second' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Frame <select name='frame' id='frame' ><?php $i=0; while($i<25) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select></td>
	</tr>
		<tr><td>From End Time: </td>
		<td >Hour <select id='ehour' name='ehour'  ><?php $i=0; while($i<24 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select> &nbsp; Minute <select id='eminute' name='eminute' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Second <select name='esecond' id='esecond' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Frame <select name='eframe' id='eframe' ><?php $i=0; while($i<25) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select></td>
	</tr> -->
	<tr>
		<td>To Date:  </td>
	   	<td><input type="text" name="date1" id="datepicker1" value="" />
	    	<span id="s2" class="error" style="font-size:18px;margin-left:300px"></span></td>
	</tr>
	<tr>

	</tr>
	<tr><td></td><td> <p style="font-size:18px;align:left;margin-left:"><input id='submit' name='submit' type='submit' value='Submit'></p></td></tr> 
<tr><td colspan="2"><span id="" class="" ><font size="5px" face="bold"  color="red" ><?php echo $Err; echo $sendData; ?></font></span></td>
</tr>

</table>

</form>	
</div>
<?php

}
  ?>
</body>
</html>
