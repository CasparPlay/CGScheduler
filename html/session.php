<?php

//Establishing Connection with Server by passing server_name, user_id and password as a parameter 
$connection = mysql_connect("localhost", "root","password");

//Selecting Database
$db = mysql_select_db("project", $connection);   
 
session_start();// Starting Session                           

//Storing session
if($_SESSION['login_user']){
$user_check=$_SESSION['login_user'];

//SQL query to fetch complete information of user   
$ses_sql=mysql_query("select user_id from registration where user_id='$user_check'", $connection);
$row = mysql_fetch_assoc($ses_sql);

$login_session =$row['user_id'];
$login_sessionType =$_SESSION['login_type'];

if(!isset($login_session)){

//Closing Connection
mysql_close($connection);
header('Location:index.php');//Redirecting to home page 

}
}

mysql_close($connection);  
?>
