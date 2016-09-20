<?php
	$host_database = "127.0.0.1";
	$host_primary = "172.16.10.89";
	$host_secondary = "172.16.10.90";

	$username = "root";
	$password = "password";
	$dbname = "project";
	
	
	
	$port = 5250;

	$state = $_GET["on"];

	require("CasparServerConnector.php");

	$connector_primary = new CasparServerConnector($host_primary,$port);
	$connector_secondary = new CasparServerConnector($host_secondary,$port);
	$response = FALSE;
	
	if($state == 'true'){
		$message = "play 1-24 Ifter_Timing_Card_HD_Alpha_01 loop auto";
		$response = $connector_primary->makeRequest($message);
		
		$message = "play 1-25 [html] file:///C:/Users/KaziMedia/Desktop/8division_white/index.html";
		$response = $connector_primary->makeRequest($message);
		
		$program_type 			= "IFTAR_CARD";
		$name 					= "iftar_countdown";
		$duration				= "10";
		$played_time 			=  date("Y-m-d H:i:s",time());
		// Create connection
		$conn = new mysqli($host_database, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO primary_log (`program_type`,`name`,`duration`,`played_time`)
				VALUES ('$program_type','$name','$duration',NOW())";

		$sql1 = "INSERT INTO secondary_log (`program_type`,`name`,`duration`,`played_time`)
				VALUES ('$program_type','$name','$duration',NOW())";
		
				if ($conn->query($sql) === TRUE) {
					echo "New record created successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}


				if ($conn->query($sql1) === TRUE) {
					echo "New record created successfully";
				} else {
					echo "Error: " . $sql1 . "<br>" . $conn->error;
				}		

				$conn->close();
		

		//$response = $connector_secondary->makeRequest($message);
	}

	else{
		$message = "clear 1-25";
		$response = $connector_primary->makeRequest($message);
		$message = "clear 1-24";
		$response = $connector_primary->makeRequest($message);

		//$response = $connector_secondary->makeRequest($message);

		}

	$connector_primary->closeSocket();
	//$connector_secondary->closeSocket();

	header("Location:graphics_control.php");

?>
