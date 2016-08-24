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


		 if($date == ""){
			$Err="   Please enter from date.";
		 }


		if($Err == ""){

		 	$connection = mysql_connect("localhost", "root", "password");
			$db = mysql_select_db("project", $connection);
			//mysql_query("START TRANSACTION");

			$sql="";
			$csql="";
			
			$csql=" SELECT * FROM `events` WHERE date(start_date) = '$date2'";
			$cresult = mysql_query($csql, $connection);
			$cnum_rows = mysql_num_rows($cresult);  
			if ($cnum_rows <= 0) {
				$Err = "No data found to delete on ".$date2;
			}
			else{
				$insertsql=" Delete FROM `events` WHERE date(start_date) = '$date2'";

				$result2 = mysql_query($insertsql, $connection);
				if($result2 === TRUE){
					$sendData = "Deleted Successfully.";
				}
				else{ 
					$sendData=" Could not delete. ";
				}							
				
			}


			mysql_close($connection);	

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
	  
	  function deleteConfirm(){
		//return confirm("Do you want to delete");
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
	?> </br><br><?php }  ?>
</br>

 <table width="50%" border="1" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td width="50%" bgcolor="#0080FF" ><div align="center" >
          <div align="center" style="font:18px 'Orienta', sans-serif;color:#fff;"><strong>Delete Events of specific date </strong></div>
        </div></td>
      </tr>
</table>     <br>  

<table align="center" border="0" style="width:50%;margin-left:30%">

<tr>
		<td>From Date: </td>
	   	<td><input type="text" name="date" id="datepicker" value="" /></td></tr><tr align="center" valign="center">
	    	</td></tr>

	<tr>

	</tr>
	<tr><td></td><td> <p style="font-size:18px;align:left;margin-left:"><input id='submit' name='submit' type='submit' value='Delete' onclick="deleteConfirm()"/>
	</p></td></tr> 
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
