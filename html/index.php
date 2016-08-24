<?php 
	include('session.php');	
	include('login.php');//includes login script
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">

<title>Scheduler</title>

</head>


<body>

<!-- <div class="fix sidebar" style="background-color:;">

<ul>
<li><a href = "regis.php"> Registration </a> </li>
</ul>
</div>  -->



<!-- end header -->
<!-- start page -->

<?php
if($login_session == "" ) {
?>

<div id="content"  style="border:0px solid red;width:100%">
<div class="post" style="border:0px solid blue">

<div class="entry">
<div class="f">
<br> <br>
<form method="post" action="" > 
 <table width="35%" border="1" align="center" cellpadding="0" cellspacing="0" >
      <tr>
        <td width="1080px" bgcolor="#0080FF" ><div align="center" >
          <div align="center" style="font:25px 'Orienta', sans-serif;color:#fff;"><strong>Deepto Scheduler Login</strong></div>
        </div></td>
      </tr>
</table>     <br>  

<table align="center" border="0" style="width:45%;margin-left:40%">

	<tr>
		<td>Login Type  </td>
	   	<td><select name="type" size="1"  style="width:200px;height:35px;"  id="type">
		      	<option value="select" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----Select----</option>
		      	<option value="news">News</option>
			    <option value="scheduler">Scheduler</option>
                <option value="graphics">Graphics</option>
        	</select>
	    	</td>
	</tr>
	<tr>
		<td>Username  </td>
	   	<td><input type='text' name='usrname' id='usrname'> 
	    	<span id="s2" class="error" style="font-size:18px;margin-left:300px"></span></td>
	</tr>
	<tr>
		<td>Password</td>
	   	<td><input type='password' name='pass' id='pass'>
	    	<span id="s2" class="error" style="font-size:18px;margin-left:300px"></span></td>
	</tr>
	<tr><td></td><td> <p style="font-size:18px;align:left;margin-left:"><input id='submit' name='submit' type='submit' value='LOG IN'></p></td></tr> 
<tr><td colspan="2"><span id="" class="" ><font face="bold"  color="red" ><?php echo $error; ?></font></span></td>
</tr>

</table>
</form>
</div>

</div>

</div>

</div>
<!-- end content -->
</div>

<?php } 
     else 
        { 
         if($login_sessionType == "news") 
            header("Location:/news_graphics/news_graphics_menu.php"); 
         else if ($login_sessionType == "scheduler") 
            header("Location:profile.php"); 
         else
            header("Location:/Graphics/graphics_settings.php");
        }
 ?>

</body></html>
