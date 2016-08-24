<?php 
 include('session.php');
 include("connection.php");
 $fname=$lname=$name =$username= $email = $contact = $address = $password =$msg=$reg_id= "";

if (isset($_POST['submit'])) {

   if (empty($_POST["fname"]))
     {$fnameErr = "First Name is required"; $err="Error";}
   else
     {$fname = test_input($_POST["fname"]);}
   if (empty($_POST["lname"]))
      {$lnameErr = "Last Name is required"; $err= "Error";}
   else
   {$lname = test_input($_POST["lname"]); }
   if (empty($_POST["username"]))
     {$usernameErr = "Username is required"; $err="Error";}
   else
     {
     $username = test_input($_POST["username"]);
     
     if (!preg_match("/^[a-zA-Z ]*$/",$username))
       {
			$username = "";
			$usernameErr = "Only letters and white space allowed"; 
			$err="Error";
       }
     }
   
   if (empty($_POST["email"]))
     {$emailErr = "Email is required"; $err="Error";}
   else
     {
		 $email = test_input($_POST["email"]);
		 
		 if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
		   {
			    $email = "";
				$emailErr = "Invalid Format!"; 
				$err="Error";
		   }
     }
     
   if (empty($_POST["contact"]))
     {$contactErr = "Contact No is required"; $err="Error";}
   else
     {
     $contact = test_input($_POST["contact"]);
   
     if (!ctype_digit($contact))
       {
		    $contact = "";
			$contactErr = "Contact should contain number"; 
			$err="Error";
       }
     }

   if (empty($_POST["address"]))
     {$addressErr = "Address is required"; $err="Error";}
   else
     {$address = test_input($_POST["address"]);}

   if (empty($_POST["password"]))
     {$passErr = "Password is required"; $err="Error";}
   else
     {$password = test_input($_POST["password"]);}
	if (empty($_POST["repassword"]))
     {$repassErr = "Confirm Your Password"; $err="Error";}
   else
     { if ($_POST["repassword"] !=$password )
	 	{$repassErr = "Password not matched."; $err="Error";}
	 	
	 }
	$reg_id=$_POST["reg_id"];

	if($err==""){
			$sql = "select * from registration where user_id = '$name' and reg_id !=' $reg_id' ";
			$result = mysql_query($sql, $link);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) {
				 $msg = "User name already exist  ."; 
			}
			else{
			$sql_save = "update registration set fname='$fname',lname='$lname'
			,address='$address',mobile='$contact',email_id='$email',user_id='$username',password='$password'
			where reg_id='$reg_id' "; 
		
			mysql_query($sql_save) or die("falied to update.."); 
			$msg = "Record updated successfully
			."; 

			}
		}
}

if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SERVER['REQUEST_METHOD'] == 'POST')){ 

			$regN0 = $_GET['reg_id'];

 		 //Establishing Connection with Server by passing server_name, user_id and password as a parameter 
          $connection = mysql_connect("localhost", "root", "password");
								
		//Selecting Database
		$db = mysql_select_db("project", $connection);
                $password=md5($password);
		
			$sql = "select * from registration where reg_id='$regN0'";
			$result = mysql_query($sql, $connection);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) {
				 while ($row = mysql_fetch_assoc($result)) {				
				 	$fname = $row["fname"];
					$lname = $row["lname"];
					$username = $row["user_id"];
					$password = $row["password"];
					$email = $row["email_id"];
					$contact = $row["mobile"];
					$address = $row["address"];
					$reg_id=$row["reg_id"];
					}
			} else {
				$err = "Invalid user name or Password.";
			}
	}
	function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">

<script src="jquery-1.11.0.min.js"></script>
<script>

jQuery(document).ready(function ($) {

  $('#checkbox').ready(function(){
    setInterval(function () {
        moveRight();
    }, 3000);
  });
  
	var slideCount = $('#slider ul li').length;
	var slideWidth = $('#slider ul li').width();
	var slideHeight = $('#slider ul li').height();
	var sliderUlWidth = slideCount * slideWidth;

	$('#slider').css({ width: slideWidth, height: slideHeight });

	$('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });
	
    $('#slider ul li:last-child').prependTo('#slider ul');

    function moveLeft() {
        $('#slider ul').animate({
            left: + slideWidth
        }, 200, function () {
            $('#slider ul li:last-child').prependTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };

    function moveRight() {
        $('#slider ul').animate({
            left: - slideWidth
        }, 200, function () {
            $('#slider ul li:first-child').appendTo('#slider ul');
            $('#slider ul').css('left', '');
        });
    };

    $('a.control_prev').click(function () {
        moveLeft();
    });

    $('a.control_next').click(function () {
        moveRight();
    });

});    


</script>

<script type="text/javascript">
	function submit_Form() {
		return confirm('Do you want to save?'); 
	}
</script>
<title>
user profile
</title>

</head>
<body>
<div class="fix sidebar" style="background-color:green;">
<ul>

<li><a href = "profile.php"> Home </a> </li>

<li><a href = "cox.html"> Cox's Bazar </a> </li>
<li><a href = "bandor.html"> Bandarban</a> </li>
<li><a href = "sundor.html"> Sundarban </a> </li>
<li><a href = "logout.php">Log Out</a></li>
<?php
if($login_session != "") {
?>
<li><a style="color:black;margin-right:200px;font-weight:bold;">Welcome  &nbsp;<?php echo $fname; ?>&nbsp;</a></li>
<?php
}
?>
</ul>

</div>

<div id="slider">
  <a href="#" class="control_next">></a>
  <a href="#" class="control_prev"><</a>
  <ul>
    <li><img src="1.jpg" alt="pic1" width="1330px" height="300px"></li>
    <li style="background: #aaa;"><img src="2.jpg" alt="pic1" width="1330px" height="300px"></li>
    <li><img src="3.jpg" alt="pic1" width="1330px" height="300px"></li>
    <li style="background: #aaa;"><img src="4.jpg" alt="pic1" width="1330px" height="300px"></li>
  </ul> 
</div>



<h1>Amazing Bangladesh!!</h1>


<div id="content">
</br>
    <table width="1215px" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="1000" bgcolor="green" ><div align="center" >
          <div align="center" style=" color:white"><strong>User&nbsp;Profile</strong></div>
        </div></td>
      </tr></table>
<form id="update_form" method="post" name="update_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onSubmit="return (validate_register_form());" >

 <table width="1080px" border="0" align="left" cellpadding="0" cellspacing="0">
 <?php
if( $msg !="")
{
?>
	  <tr><td> <span id="name-format" class="error" ><font face="bold"  color="red" style="margin-left:450px;font-size:18px" ><?php echo 	$msg;?></font></span> </td> </tr>
	  <?php
}
?>
</table>  
<table align="center" style="width:1000px; height:0px; cellpadding:0;" class="regT">
<tr>
	<td style="width: 300px;"><label style="font-size: 20px; " for="author">First Name</label></td> 
	<td ><input type="text" id="fname" name="fname" placeholder="Your First Name" value="<?php echo $fname; ?>" title="Enter your First name" style="width:370px;height:20px;" class="required input_field" onKeyUp="checkfname();" />
		<label for="author" id="fname_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo 	$fnameErr;?></font></span>
	</td>
</tr>

<tr>
	<td style="width: 200px;"><label style="font-size: 20px; " for="author">Last Name</label></td> 
	<td><input type="text" id="lname" name="lname" placeholder="Your Last Name" value="<?php echo $lname; ?>" title="Enter your Last name" style="width:370px;height:		20px;" class="required input_field" onKeyUp="checklname();" />
		<label for="author" id="fname_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo 	$lnameErr;?></font></span>
	</td>
</tr>

                                                  
<tr >
	<td style="width: 200px;"><label style="font-size: 20px; " for="author">Email</label></td> 
	<td><input type="email" id="email" name="email"  placeholder="Your E-mail address" value="<?php echo $email; ?>" title="Enter your e-mail address" style="width:370px;height:20px;" class="required input_field" onKeyUp="checkEmail();" />
<label for="author" id="email_error"></label>
<div class="cleaner h10"></div></td>
<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $emailErr;?></font></span></td>

</tr>
          
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="LLL"></div>  
                   
<tr>
	<td style="width: 200px;"><label style="font-size: 20px; " for="author">Contact No</label></td>
	<td><input type="text" id="contact" name="contact"  placeholder="Type your Contact No" value="<?php echo $contact; ?>" title="Enter your Contact No" style="width:370px;height:20px;"class="required input_field" /><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $contactErr;?></font></span></td>
</tr>
                    
 <div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="WWW"></div>
 <tr>
   	<td style="width: 200px;"><label style="font-size: 20px; " for="author">Address</label></td> 
	<td><input type="textarea" id="address" name="address" value="<?php echo $address; ?>" placeholder="Enter your address " title="Enter your address" style="width:370px;height:35px;"class="required input_field" /><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $addressErr;?></font></span></td>
</tr>
                    
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="EEE"></div>   
<tr>
<td style="width: 200px;"><label style="font-size: 20px; " for="author">Username</label></td> 
<td><input type="text" id="username" name="username" placeholder="Your User Name" value="<?php echo $username; ?>" title="Enter your User name" style="width:370px;height:20px;" class="required input_field" onKeyUp="checkUsername();" />
</td>
<td><label for="author" id="username_error"></label><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:80px;font-size:14px" ><?php echo $usernameErr;?></font></span></td>
</tr>
                        
<tr>
 	<td style="width: 200px;"><label style="font-size: 20px; " for="author">Password</label></td>
	<td><input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Enter Password"style="width:370px;height:20px;" title="Enter minimum four character"  class="required input_field" /><div class="cleaner h10"></div></td>
          
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:80px;font-size:14px" ><?php echo $passErr;?></font></span></td>
</tr>
                    
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="PPP"></div>
                   
<tr>
 	<td style="width: 200px;"> <label style="font-size: 20px; " for="author">Confirm Password</label></td>
	<td><input type="password" id="repassword" name="repassword" placeholder="Confirm your password" title="Re-type your password" style="width:370px;height:20px;" class="required input_field" onKeyUp="checkPass();" value="<?php echo $password; ?>" />
	<label for="author" id="pass_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="help"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $repassErr;?></font></span></td>
 </tr>
 <tr> 	<td><input id="reg_id" name="reg_id" value="<?php echo $reg_id; ?>" type="hidden" /><div class="cleaner h10"></div></td>
</tr>
 </table>
<div style="margin-left: 450px; margin-top: 15px;"> <input style="width:120px;height:35px;font-weight:bold" id="submit" name="submit" type="submit" value="Save" onClick="return submit_Form()"/> </div>
</form>
</div>


<div id="menu" style="background-color:green;"></div>


</body>
</html>
