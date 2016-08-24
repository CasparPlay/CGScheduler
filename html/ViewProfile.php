<?php 
 include('session.php');
 $fname=$lname=$name =$username= $email = $contact = $address = $password = "";
 $regN0=0;

if (($_SERVER['REQUEST_METHOD'] == 'GET') || ($_SERVER['REQUEST_METHOD'] == 'POST')){ 

			$regN0 = $_GET['reg_id'];

 		 //Establishing Connection with Server by passing server_name, user_id and password as a parameter 
          $connection = mysql_connect("localhost", "root", "password");
								
		//Selecting Database
		$db = mysql_select_db("project", $connection);
		
			$sql = "select * from registration where reg_id='$regN0'";
			$result = mysql_query($sql, $connection);
			$num_rows = mysql_num_rows($result);
			if ($num_rows > 0) {
				 while ($row = mysql_fetch_assoc($result)) {				
				 	$fname = $row["fname"];
					$lname = $row["lname"];
					$usrname = $row["user_id"];
					$password = $row["password"];
					$email = $row["email_id"];
					$contact = $row["mobile"];
					$address = $row["address"];
					}
			} else {
				$err = "Invalid user name or Password.";
			}

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
	  </br>
	<table width="970" border="0" align="center" cellpadding="0" cellspacing="0">
	    <tr>
			 <td width="100px"><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">First Name </span></td>
			  <td align="left"><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			  <span align="left" style="font-family: verdana;font-size: 14px;"><?php echo $fname; ?></span></td>
		</tr><tr>
			 <td align="left"><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">Last Name</span></td>
			 <td><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			 <span align="left" style="font-family: verdana;font-size: 14px;"><?php echo $lname; ?></span></td>
		</tr><tr>
			 <td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">Address</span></td>
			  <td><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			  <span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $address; ?></span></td>
		</tr><tr>
			 <td><span style="font-family: verdana;font-family: verdana; font-size: 14px;"><strong>Phone</strong></span></td>
			 <td><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			 <span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $contact; ?></span></td>
	</tr><tr>
			 <td style="font-family: verdana; font-size: 12px;"><strong>Email</strong></td>
			 <td><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			 <span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $email; ?></span></td>
	</tr>
	<tr>
			 <td style="font-family: verdana; font-size: 12px;"><strong>User Name </strong></td>
			 <td><span align="right" style="font-family: verdana; font-weight: bold; font-size: 14px;">: &nbsp;&nbsp; </span>
			 <span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $usrname; ?></span></td>
	</tr>
    </table>
</div>


<div id="menu" style="background-color:green;"></div>

<?php mysql_close($connection);  ?>
</body>
</html>
