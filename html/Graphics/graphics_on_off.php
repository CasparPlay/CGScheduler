<?php

$id = $_GET["id"];
$name = $_GET["name"];
$layer = $_GET["layer"];
$loop = $_GET["loop"];
$state = $_GET["on"];


$host_primary = "172.16.10.89";
$host_secondary = "172.16.10.90";

$port = 5250;

require("CasparServerConnector.php");

    $connector_primary = new CasparServerConnector($host_primary,$port);
	$connector_secondary = new CasparServerConnector($host_secondary,$port);
	$response = FALSE;
	
	if($state == 'true'){
       if($loop == 1)
		$message = "play 1-".$layer." ".$name." loop auto";
       else
        $message = "play 1-".$layer." ".$name." auto";
	$response = $connector_primary->makeRequest($message);

	$response = $connector_secondary->makeRequest($message);
	}

	else{
		$message = "clear 1-".$layer;
		$response = $connector_primary->makeRequest($message);

		$response = $connector_secondary->makeRequest($message);

		}

	$connector_primary->closeSocket();
	$connector_secondary->closeSocket();

	header("Location:graphics_control.php");
?>
