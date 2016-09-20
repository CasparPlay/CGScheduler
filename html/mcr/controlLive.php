<?php

$host_primary = "172.16.10.89";
$host_secondary = "172.16.10.90";
$port = 50007;
// No Timeout 
$message = "clear 1-10";
//set_time_limit(0);




//============================ send message to primary =====================================

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
socket_connect($socket, $host_primary, $port) or die("Could not connect toserver\n");

socket_write($socket, $message."\r\n", strlen ($message."\r\n")) or die("Could not send data to server\n");

socket_close($socket);

//===========================================================================================


//=========================== send message to secondary======================================
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
socket_connect($socket, $host_secondary, $port) or die("Could not connect toserver\n");

socket_write($socket, $message."\r\n", strlen ($message."\r\n")) or die("Could not send data to server\n");

socket_close($socket);

//===========================================================================================


header("Location:rundown.php");


/*
$host = "121.0.0.1";
$port = 50007;
// No Timeout 
$message = "clear 1-16\r\n";
//set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
//$msgsock = socket_accept($socket);
socket_connect($socket, $host, $port) or die("Could not connect toserver\n");

socket_write($socket, $message, strlen ($message)) or die("Could not send data to server\n");
echo $message;
//socket_close($socket);
header("Location:liveOff.php");*/
?>
