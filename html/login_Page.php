<?php 
	include('login.php');//includes login script
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">

<title>
Homepage
</title>

</head>


<body>

<div class="fix sidebar" style="background-color:green;">

<ul>

<li><a href = "index.php"> Home </a> </li>

<li><a href = "login.php"> Log In </a> </li>
<li><a href = "regis.php"> Registration </a> </li>
</ul>
</div>

<img id="i1"  src="head.jpg"/>
<h1 class = "maintitle"><b>Amazing Bangladesh!!</b></h1>

</div>

<!-- end header -->
<!-- start page -->

<div id="content" >
<div class="post">

<div class="entry">
<div class="f">
<br> <br>
<form method="post" action="" > 
 <table width="1218px" border="0" align="left" cellpadding="0" cellspacing="0" >
      <tr>
        <td width="1080px" bgcolor="green" ><div align="center" >
          <div align="center" style="font:25px 'Orienta', sans-serif;color:#fff;"><strong>User Login</strong></div>
        </div></td>
      </tr>
</table>     <br>  
<p style="font-size:22px;margin-left:270px"><b>Still not a member?Go to <a href="regis.php">Registration</b></a></p>

<p style="font-size:18px;margin-left:300px">Username : &nbsp;&nbsp;&nbsp;
<input type='text' name='usrname' id='usrname'>
<span id="s2" class="error" style="font-size:18px;margin-left:300px"></span>
  <br/><br/>
Password :  &nbsp;&nbsp;&nbsp;
<input type='password' name='pass' id='pass'>
<span id="s2" class="error"></span>
  <br/></p>
				
      <p style="font-size:18px;margin-left:450px"><input id='submit' name='submit' type='submit' value='LOG IN'> </p>
</form>
</div>
<span id="name-format" class="error" ><font face="bold"  color="red" style="margin-left:320px;font-size:18px" ><?php echo $error; ?></font></span>
<p>&nbsp;</p>
</div>

</div>

</div>
<!-- end content -->
</div>

 

<div id="menu" style="background-color:green;"></div>

<!-- end page -->

</body></html>
