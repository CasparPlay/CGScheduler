<?php 
 include('session.php');
 $fname=$lname=$name =$username= $email = $contact = $address = $password =$Err= "";
$sql="";
if (isset($_POST['submit'])) {
    $searchBy=$_POST['search'];
	 $searchvalue=$_POST['key'];
	 
	 if($searchBy == "All"){
	 	$sql = "select * from registration";
	 }
	 else{
	 	 if($searchvalue == ""){
		 	$Err="Enter Your Search Value";
		 }
		 else{
		 	$sql = "select * from registration where $searchBy like '%$searchvalue%'";
		 }
	 }

if($Err == ""){
 		 //Establishing Connection with Server by passing server_name, user_id and password as a parameter 
          $connection = mysql_connect("localhost", "root", "password");
								
		//Selecting Database
		$db = mysql_select_db("project", $connection);
		
			//$sql = "select * from registration";
			$result = mysql_query($sql, $connection);
			$result1 = mysql_query($sql, $connection);
			$num_rows = mysql_num_rows($result1);
			if ($num_rows > 0) {
				 while ($row = mysql_fetch_assoc($result1)) {				
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
<li><a style="color:black;margin-right:200px;font-weight:bold;">Welcome &nbsp;<?php echo $fname; ?>&nbsp;</a></li>
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
<form id="form1" name="form1" method="post" action="">
 <table width="1215px" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="1000" bgcolor="green" ><div align="center" >
          <div align="center" style=" color:white"><strong>Admin&nbsp;Panel</strong></div>
        </div></td>
      </tr>  
	  <?php
if($Err != "") {
?>	  <tr><td> <div align="center"><font face="bold"  color="red" style="font-size:18px" ><?php echo $Err; ?></font></div></td></tr>
<?php
}
?>
</table>
	  <?php
if($Err == "") {
?> </br><?php }  ?>
  <table width="75%" border="1" align="center" cellpadding="1" cellspacing="1"  bgcolor="#00CC99">
    <tr align="left" valign="center">
      <td width="10%" align="left" ><div align="center" class="style42">
        <div align="center" style="font-size:22px">Search By </div>
      </div>
        <span class="style42">
        <label>        </label>
        </span>      <span class="style42">
        <label>        </label>
        </span>        <span class="style42">
        <label></label>
        </span>        <span class="style42">
        <label></label>
        </span>        <span class="style42">
        <label></label>
        </span>        <span class="style42">
        <label></label>
      </span></td>
      <td width="1%" align="left"><label>
       
		<select name="search" size="1"  style="width:200px;height:25px"  id="search">
              <option value="All">All</option>
              <option value="fname">First Name</option>
			   <option value="lname">Last Name</option>
  			  <option value="address">Address</option>
			  <option value="Mobile">Mobile No</option>
			   <option value="email_id">Email_ID</option>
			  <option value="user_id">User ID</option>
        </select>

		
      </label></td>
      <td width="10%" align="center" class="style45"><label class="style47" style="font-size:22px">Search Value</label></td>
      <td width="5%" align="left">
          <input name="key" id="key" type="text" size="35" style="height:20px" value=""  placeholder="Enter your search key"></td>
      <td width="7%" align="left">        
        <div align="center" class="style42">
          <input name="submit" type="submit" id="submit" value="Show" style="height:30px;width:100px;background-color:#009933;color:white;font-weight:bold"  />
        </div></td>
    </tr>
  </table>
   
	  </br>
	<table width="1070" border="1" align="center" cellpadding="0" cellspacing="0">
	    <tr>
			 <td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">First Name</span></td>
			 <td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">Last Name</span></td>
			 <td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">Address</span></td>
			 <td><span style="font-family: verdana;font-family: verdana; font-size: 14px;"><strong>Phone</strong></span></td>
			 <td style="font-family: verdana; font-size: 12px;"><strong>Email</strong></td>
			 <td><span align="left" style="font-family: verdana; font-weight: bold; font-size: 14px;">View</span></td>
			 <td><span style="font-family: verdana;font-family: verdana; font-size: 14px;"><strong>Edit</strong></span></td>
			 <td style="font-family: verdana; font-size: 12px;"><strong>Delete</strong></td>
       </tr>
    <?php 
		if ($result <> null) { 
			while ($row = mysql_fetch_assoc($result)) {			
	?>
      <tr>
        <td><span align="left" style="font-family: verdana;font-size: 14px;"><?php echo $row["fname"]; ?></span></td>
        <td><span align="left" style="font-family: verdana;font-size: 14px;"><?php echo $row["lname"]; ?></span></td>
        <td><span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $row["address"]; ?></span></td>
        <td><span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $row["mobile"]; ?></span></td>
        <td><span align="left" style="font-family: verdana; font-size: 14px;"><?php echo $row["email_id"]; ?></span></td>
		<td><a href="ViewProfile.php?reg_id=<?php echo $row["reg_id"]; ?>&user_id=<?php echo $row["user_id"]; ?>" class="style24"><span align="left" style="font-family: verdana; font-size: 14px;">View</span></td>
		<td><a href="EditUser.php?reg_id=<?php echo $row["reg_id"]; ?>&user_id=<?php echo $row["user_id"]; ?>" class="style24"><span align="left" style="font-family: verdana; font-size: 14px;">Edit</span></td>       
				<td><a href="deleteUser.php?reg_id=<?php echo $row["reg_id"]; ?>&user_id=<?php echo $row["user_id"]; ?>" class="style24"><span align="left" style="font-family: verdana; font-size: 14px;">Delete</span></td>
      </tr>

      <?php 
			}
		}
	?>
    </table>
</form>	
</div>


<div id="menu" style="background-color:green;"></div>

<?php mysql_close($connection);  ?>
</body>
</html>
