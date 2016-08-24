<?php

$host_primary = "10.3.10.190";
$host_secondary = "10.3.10.191";
$port = 50007;
// No Timeout 
$message = "clear 1-10";
//set_time_limit(0);
$server = $_GET["server"];


if($server == 'primary')
{
//============================ send message to primary =====================================

$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
socket_connect($socket, $host_primary, $port) or die("Could not connect toserver\n");

socket_write($socket, $message."\r\n", strlen ($message."\r\n")) or die("Could not send data to server\n");

socket_close($socket);

//===========================================================================================
}
else if ($server == 'secondary')
{
//=========================== send message to secondary======================================
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
socket_connect($socket, $host_secondary, $port) or die("Could not connect toserver\n");

socket_write($socket, $message."\r\n", strlen ($message."\r\n")) or die("Could not send data to server\n");

socket_close($socket);

//===========================================================================================
}
header("Location:rundown.php");

?>
