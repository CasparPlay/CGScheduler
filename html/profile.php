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
					$role_roleid = $r_roleid;
					}
			} else {
				$err = "Invalid user name or Password.";
			}
			$sql1 = "select * from role_rights where role_roleid = '$r_roleid'";
			$result1 = mysql_query($sql1, $connection);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<script src="jquery.min.js"></script>
<script type="text/javascript" src="lib/jquery.ntm/js/jquery.ntm.js"></script>
<link rel="stylesheet" href="css/style.css" />
<link href="css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="lib/jquery.ntm/themes/default/css/theme.css" />
<script type="text/javascript">
            $(document).ready(function() {
                $('.demo').ntm();
            });
</script>
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

<title>Scheduler</title>

</head>
<body>
<?php
if($login_session != "") {
?>
<div class="fix sidebar" style="background-color:;">
<ul>
<li><a href = "logout.php"><img src="imgs/logout.png" alt=""  height=100% width=100%></img></a></li>
<?php
if($login_session == "admin") {
?>
<li><a href = "adminPage.php"> Admin Panel </a> </li>
<?php
}
?>
<?php
if($login_session != "") {
?>

<?php
}

?>
</ul>
</div>



<div style="border:0px solid red;height:100%;width:100%;margin-top:20px">

	 <?php 
		while ($row1 = mysql_fetch_assoc($result1)) {
			
				 	$role_roleid = $row1["role_roleid"];
					$menu_id = $row1["menu_id"];
					$r_read = $row1["r_read"];
					$r_write = $row1["r_write"];

					} //echo $role_roleid;//echo $menu_id;
			

?>

	<div class="wrapper" style="border:0px solid green;margin-left:50px;width:40%">
		  <?php

	if($role_roleid == "ADVERTISEMENT"){ ?>


		<div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			      <li><a href="#">Create</a>
				<ul><li><a href="select.php">Create Schedule</a></li></ul>
			      </li>
			      <li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>

			    </ul>
			  </div>
	<?php }


	else if($role_roleid == "PROGRAM"){ ?>



			<div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			      <li><a href="#">Create</a>
				<ul><li><a href="select.php">Create Schedule</a></li></ul>
			      </li>
			     <li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>
			    </ul>
			  </div>
	<?php }

	else if($role_roleid == "NEWS"){ ?>



			<div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			     <li><a href="#">News Graphics</a>
			     <ul>
				          <li class="selected"><a href="#">Headline</a></li>
				          <li><a href="#">News Ticker</a></li>
					  <li><a href="#">Current News</a></li>
					  <li><a href="#">Breaking News</a></li>
 					
			    </ul></li>
			    <li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>
			    </ul>
			  </div>
	<?php }
	else if ($role_roleid == "SCHEDULER"){ ?>

		<div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			      <li><a href="#">Create</a>
				<ul>
			          <li><a href="select.php">Create Schedule</a></li>
				  <li><a href="latest.php">Create Playlist</a></li>
				  <li><a href="/lshape/select_LShape.php">Create LShape Schedule</a></li> 
				  <li><a href="/lshape/latest.php">Create LShape Playlist</a></li>
				  <li><a href="validate.php">Validate Rate Agreement</a></li>
                  		  <li><a href="CopyEvents.php">Copy Events</a></li>
				  <li><a href="DeleteEvents.php">Delete Events</a></li>
                   
				</ul></li>

				<li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="previewSchedulerList_COM.php">Preview Commercial List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>
				<ul><li><a href="scheduleStatusPerHour_Report.php">Daily Schedule Status</a></li></ul>
			    </ul>
	      </div>

	<?php }
    else if ($role_roleid == "MCR"){ ?>
       <div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			      <li><a href="#">Create</a>
				<ul>
			          <li><a href="select.php">Create Schedule</a></li>
				  <li><a href="latest.php">Create Playlist</a></li>
				  <li><a href="/lshape/select_LShape.php">Create LShape Schedule</a></li> 
				  <li><a href="/lshape/latest.php">Create LShape Playlist</a></li>
                  		  <li><a href="/Graphics/graphics_control.php">Graphics Control</li>
                 		  <li><a href="/mcr/rundown.php">MCR</a></li>
                   
				</ul></li>

				<li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="previewSchedulerList_COM.php">Preview Commercial List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>
				<ul><li><a href="scheduleStatusPerHour_Report.php">Daily Schedule Status</a></li></ul>
			    </ul>
	      </div>
    
    <?php }
	else{ ?>

		<div class="tree-menu demo" id="tree-menu" style="border:0px solid red;">
			    <ul>
			      <li><a href="#">Create</a>
				<ul>
				          <li class="selected"><a href="regis.php">Create User</a></li>
				          <li><a href="select.php">Create Schedule</a></li>
					  <li><a href="latest.php">Create Playlist</a></li>
 					  <li><a href="/lshape/select_LShape.php">Create LShape Schedule</a></li> 
				  	  <li><a href="/lshape/latest.php">Create LShape Playlist</a></li> 
					  <li><a href="validate.php">Validate Rate Agreement</a></li>
					  <li><a href="CopyEvents.php">Copy Events</a></li>
					  <li><a href="DeleteEvents.php">Delete Events</a></li>
                      <li><a href="/Graphics/graphics_control.php">Graphics Control</li>
                      <li><a href="/mcr/rundown.php">MCR</a></li>
				</ul></li>
				<li><a href="#">News Graphics</a>
				<ul>
				          <li class="selected"><a href="#">Headline</a></li>
				          <li><a href="#">News Ticker</a></li>
					  <li><a href="#">Current News</a></li>
					  <li><a href="#">Breaking News</a></li>
				</ul></li>
				<li><a href="#">Presentation</a></li>
				<ul><li><a href="previewSchedulerList.php">Preview Scheduler List</a></li></ul>
				<ul><li><a href="previewSchedulerList_COM.php">Preview Commercial List</a></li></ul>
				<ul><li><a href="scheduleSummary.php">Daily Schedule Summary</a></li></ul>
				<ul><li><a href="scheduleStatusPerHour_Report.php">Daily Schedule Status</a></li></ul>
			    </ul>
	      </div>



	<?php }

	?> 
	</div>  


	<div id="" style="margin-top:-90px;border:0px solid red;float:right;margin-right:50px;">

	       <table width="200px" border="0" align="center" cellpadding="0" cellspacing="0">
		    <tr>
			<td width="" bgcolor="green" border="0" ><div align="center" >
		  		<div align="center" style="color:white"><strong>User&nbsp;Profile</strong></div>
			</td>
	      	    </tr>
		</table>
		 
		<table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
	      	     <tr>
			<td></br><span align="left" style="font-family: verdana; font-weight: bold;font-size:12px;">Full Name :</span>
			<span style="font-family: verdana;font-size: 14px;"><?php echo "$fname"." "."$lname";?> </span>
			</td>

	      	      </tr>
	      	      <tr>
			<td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 12px;">Address :</span>

			     <span style="font-family: verdana;font-size: 14px;"><?php echo $address;?> </span></td>
		       </tr>
	   
	      		<tr>
	       		 <td><span style="font-family: verdana;font-family: verdana; font-size: 12px;"><strong>Employee Id:</strong> <?php echo $employee_id;?>  </span></td>
	      		</tr>
<tr>
	       		 <td><span style="font-family: verdana;font-family: verdana; font-size: 12px;"><strong>Role:</strong> <?php echo $r_roleid;?>  </span></td>
	      		</tr>
	      		<tr><td style="font-family: verdana; font-size: 12px;"><strong>Email: </strong><?php echo $email;?> </td></tr>
		  </table>
	</div>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php } header("Location: index.php"); mysql_close($connection);  ?>
</body>
</html>
