<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript">
	function submit_Form() {
	if ( !((/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/).test(document.getElementById('email').value)))  
  	{  
    	alert("You have entered an invalid email address!")  
    	return (false)  
  	}  
    return confirm('Do you want to save?'); 
}
</script>
<title>Scheduler</title>

</head>
<body>

<?php
 	include("connection.php");

$fnameErr=$lnameErr=$usernameErr = $emailErr = $employeeErr = $addressErr = $passErr = $repassErr=$msg=$err=$roleidErr= "";
$fname=$lname=$name =$username= $email = $employee_id = $address = $password =$repassword= $r_roleid= "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
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
     $name = test_input($_POST["username"]);
     
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
     
   if (empty($_POST["employee"]))
     {$employeeErr = "Employee Id is required"; $err="Error";}
   else
     {
     $employee_id = test_input($_POST["employee"]);
   
     if (!ctype_digit($employee_id))
       {
		    $employee_id = "";
			$employeeErr = "Employee should contain number"; 
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
	if (empty($_POST["role"]))
	     {$roleidErr = "Role  is required"; $roleidErr="";}
	   else
	     {$r_roleid = test_input($_POST["role"]);}
if (isset($_POST['submit'])) {
	if($err==""){
			$sql = "select * from registration where user_id = '$name'";
			$result = mysql_query($sql, $link);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) {
				 $msg = "User name already exist."; 
			}
			else{
			$password=md5($password);
			$sql_save = "insert into registration(
								fname,lname,address,employee_id,email_id,user_id,password,r_roleid							
								
							) values(
								'$fname','$lname','$address','$employee_id','$email','$name','$password','$r_roleid'	
							)"; 
		
			mysql_query($sql_save) or die("falied to insert.."); 
			$msg = "Record saved successfully."; 
			$fname=$lname=$name = $email = $employee_id = $address = $password =$repassword=$r_roleid= "";
			}
		}
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

<div id="content1">
<form id="register_form" method="post" name="register_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onSubmit="return (validate_register_form());" >
<br>
 <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="600px" bgcolor="#0080FF" ><div align="center" >
          <div align="center" style="font:25px 'Orienta', sans-serif;color:#fff;"><strong>Member Registration</strong></div>
        </div></td>
      </tr>
	  <tr><td> <span id="name-format" class="error" ><font face="bold"  color="red" style="margin-left:450px;font-size:18px" ><?php echo 	$msg;?></font></span> </td> </tr>
</table>     <br>    
<table align="center" style="width:1000px; height:0px; cellpadding:0;" class="regT">
<tr>
	<td style="width: 300px;"><label for="author">First Name</label></td> 
	<td ><input type="text" id="fname" name="fname" placeholder="Your First Name" value="<?php echo $fname; ?>" title="Enter your First name" style="width:370px;height:		25px;" class="required input_field" onKeyUp="checkfname();" />
		<label for="author" id="fname_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo 	$fnameErr;?></font></span>
	</td>
</tr>

<tr>
	<td style="width: 200px;"><label for="author">Last Name</label></td> 
	<td><input type="text" id="lname" name="lname" placeholder="Your Last Name" value="<?php echo $lname; ?>" title="Enter your Last name" style="width:370px;height:		25px;" class="required input_field" onKeyUp="checklname();" />
		<label for="author" id="fname_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo 	$lnameErr;?></font></span>
	</td>
</tr>

                                                  
<tr >
	<td style="width: 200px;"><label for="author">Email</label></td> 
	<td><input type="email" id="email" name="email"  placeholder="Your E-mail address" value="<?php echo $email; ?>" title="Enter your e-mail address" style="width:370px;height:25px;" class="required input_field" onKeyUp="checkEmail();" />
<label for="author" id="email_error"></label>
<div class="cleaner h10"></div></td>
	<td>	  <span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $emailErr;?></font></span></td>
</tr>
          
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="LLL"></div>  
                   
<tr>
	<td style="width: 200px;"><label for="author">Employee Id</label></td>
	<td><input type="text" id="employee" name="employee"  placeholder="Type your Employee Id" value="<?php echo $employee_id; ?>" title="Enter your Employee No" style="width:370px;height:25px;"class="required input_field" /><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $employeeErr;?></font></span></td>
</tr>
                    
 <div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="WWW"></div>
 <tr>
   	<td style="width: 200px;"><label for="author">Address</label></td> 
	<td><input type="textarea" id="address" name="address" value="<?php echo $address; ?>" placeholder="Enter your address " title="Enter your address" style="width:370px;height:35px;"class="required input_field" /><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $addressErr;?></font></span></td>
</tr>
                    
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="EEE"></div>   
<tr>
<td style="width: 200px;"><label for="author">Username</label></td> 
<td><input type="text" id="username" name="username" placeholder="Your User Name" value="<?php echo $name; ?>" title="Enter your User name" style="width:370px;height:25px;" class="required input_field" onKeyUp="checkUsername();" />
</td>
<td><label for="author" id="username_error"></label><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:80px;font-size:14px" ><?php echo $usernameErr;?></font></span></td>
</tr>
                        
<tr>
 	<td style="width: 200px;"><label for="author">Password</label></td>
	<td><input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Enter Password"style="width:370px;height:25px;" title="Enter minimum four character"  class="required input_field" /><div class="cleaner h10"></div></td>
          
	<td><span id="name-format" class="error"><font face="bold"  color="red" style="margin-left:80px;font-size:14px" ><?php echo $passErr;?></font></span></td>
</tr>
                    
<div style=" text-align: justify; color: red; height: 10px; font-size: 10pt;  margin-top: -1px; margin-left: 250px;" id="PPP"></div>
                   
<tr>
 	<td style="width: 200px;"> <label for="author">Confirm Password</label></td>
	<td><input type="password" id="repassword" name="repassword" placeholder="Confirm your password" title="Re-type your password" style="width:370px;height:25px;" class="required input_field" onKeyUp="checkPass();" />
	<label for="author" id="pass_error"></label><div class="cleaner h10"></div></td>
	<td><span id="name-format" class="help"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $repassErr;?></font></span></td>
 </tr>
<tr>
 	<td style="width: 200px;"> <label for="author">Role</label></td>
	<td>

		<select name="role" size="1"  style="width:200px;height:25px"  id="role">
		        <option value="SCHEDULER">SCHEDULER</option>
		        <option value="MCR">MCR</option>
			    <option value="NEWS">NEWS</option>
                <option value="GRAPHICS">GRAPHICS</option>
		        <option value="SUPERADMIN">SUPERADMIN</option>
        	</select>

</td>
	<td><span id="name-format" class="help"><font face="bold"  color="red" style="margin-left:1px;font-size:14px" ><?php echo $r_roleid;?></font></span></td>
 </tr>
 <tr> <td></td></tr>
 </table>
<div style="margin-left: 450px; margin-top: 15px;"> <input style="width:120px;height:35px;font-weight:bold" id="submit" name="submit" type="submit" value="Create User" onClick="return submit_Form()"/> </div>

<div style="border:0px solid red;width:80px;margin-left:350px;margin-top:-34px"><a href ="index.php"><img src="imgs/login.png" alt=""  height=100% width=100%></img></a></div> 

<div style="height:50px;width:100px;border:0px solid red;float:right;margin-top:-50px;margin-right:32%"><a href = "profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a></div>
</div>
</form>
				
</body>
</html>
