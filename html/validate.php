<?php
	include('session.php');
	$fname=$lname=$name =$username= $email = $contact = $address = $password = $reg_id= $role_roleid= $menu_id= $r_read= $r_write="";
	//Establishing Connection with Server by passing server_name, user_id and password as a parameter 
          $connection = mysql_connect("localhost", "root", "password");
								
	//Selecting Database
	$db = mysql_select_db("project", $connection);
	$sql = "select * from registration where user_id = '$login_session'";

			$result = mysql_query($sql, $connection);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) {
				 while ($row = mysql_fetch_assoc($result)) {				
				 	$fname = $row["fname"];
					$lname = $row["lname"];
					$usrname = $row["user_id"];
					$password = $row["password"];
					$email = $row["email_id"];
					$employee_id = $row["employee_id"];
					$address = $row["address"];
					$reg_id  = $row["reg_id"];
					$r_roleid = $row["r_roleid"];

					}
			} else {
				$err = "Invalid user name or Password.";
			}
			$sql1 = "select * from role_rights where role_roleid = '$r_roleid'";
			$result1 = mysql_query($sql1, $connection);
			
			while ($row1 = mysql_fetch_assoc($result1)) {
			
				 	$role_roleid = $row1["role_roleid"];
					$menu_id = $row1["menu_id"];
					$r_read = $row1["r_read"];
					$r_write = $row1["r_write"];

					} //echo $role_roleid;
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create PlayList</title>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="jquery-1.10.2.js"></script>
  <script src="jquery-ui.js"></script>
  <style type="text/css" media="screen">

	body {
	    font-family: "Trebuchet MS","Helvetica","Arial","Verdana","sans-serif";
	    font-size: 62.5%;
	}

 </style>
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({
      showButtonPanel: true
    });
  });

function checkComValidation(){

			var date1 = new Date(document.getElementById('datepicker').value); 
			var day=date1.getDate(); // alert(day);
			var month=date1.getMonth()+1;
			if(date1.getDate() < 10) { day='0'+date1.getDate();}
			if(month < 10) { month='0'+month;} 
			var hour_limit=document.getElementById('checkHourLimit').value;
			var hour_limit1=parseInt(hour_limit)+1;
			if(hour_limit != null && hour_limit != ""){
				
			}
			else{
				alert("Please Give Validate Hour."); return;
			}
			if( hour_limit1 < 10) {  hour_limit1='0'+ hour_limit1;} 
			var startDate= date1.getFullYear()+ '-' +month+ '-' + day+' '+hour_limit1+':00:00';
			var startDate1= date1.getFullYear()+ '-' +month+ '-' + day+' '+'00:00:00';
			var sendDate=startDate+";"+startDate1; 
			//alert("Check Validity "+sendDate+"====="+startDate1);

			 $.post(                             
				"CheckCommercialRate.php",      
				{
				    field: "name",
				    name: sendDate
				}                              
			    ).done(                           
				function(data)
				{ //alert("Check Validity ");
				   var obj = JSON.parse(data); //alert(obj);
				   
				   if(obj != "" && obj != null && obj.contains('@Error@')){ 					
					//alert(obj);
					var splitObj=obj.split("@Error@");
					var splitInfo="-------Error Message------- "; //alert(splitObj.length);
					for(var i=0;i<splitObj.length;i++){
						splitInfo=splitInfo+"\n"+splitObj[i];
					}
					alert(splitInfo);
				    }
				   else{
					alert("Validated Successfully. (From 0 to "+hour_limit1+")");
				
				   }
				}
			    );
		}
  </script>
</head>
<body>
<div style="border:0px solid red">
<div style="height:55px;width:100px;border:0px solid red;margin-left:40%;float:left;"><a href = "profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a> </div>

<div style="height:50px;width:100px;border:0px solid red;margin-left:50%;"><a href = "logout.php"><br><img src="imgs/logout.png" alt=""  height=70% width=80%></img></a></div>

</div>



<?php
if($login_session != ""){

?>


<form method="post" action="action.php" style="border:1px solid #CECECE;width:50%;height:100%;margin-left:25%;margin-top:10%">
<br><br>
<p><font size="3">&nbsp;Date: </font><input type="text" name="date" id="datepicker">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="height:30px;width:80px;color:black;font-weight:bold;font-size:14px">Validate Hour: </span>
<input type="number" name="checkHourLimit" id="checkHourLimit" min="1" max="23" style="height:30px;width:80px;color:black;font-weight:bold;">
</p><br><br><input name="check" type="button" id="check" value="Validate Rate Agreement" onclick="checkComValidation();" style="height:30px;width:200px;color:black;font-weight:bold;margin-left:25%;"/><br><br>
</form>


 
 
</body>
<?php } else { header("Location:index.php");} 
        mysql_close($connection);
?>

