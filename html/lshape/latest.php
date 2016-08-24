<?php
	include('../session.php');
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
  </script>
</head>
<body>
<div style="border:0px solid red">
<div style="height:55px;width:100px;border:0px solid red;margin-left:40%;float:left;"><a href = "../profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a> </div>

<div style="height:50px;width:100px;border:0px solid red;margin-left:50%;"><a href = "../logout.php"><br><img src="imgs/logout.png" alt=""  height=70% width=80%></img></a></div>

</div>



<?php
if($login_session != ""){

?>


<form method="post" action="action.php" style="border:1px solid #CECECE;width:25%;margin-left:35%;margin-top:10%">
<br><br>
<p><font size="3">&nbsp;Date: </font><input type="text" name="date" id="datepicker"></p>
<br><br>
<input type="submit" value="Submit" style="margin-left:35%">
</form>


 
 
</body>
<?php } else { header("Location:../index.php");} 
        mysql_close($connection);
?>

