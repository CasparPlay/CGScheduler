<?php
	require_once('../../common/connector/scheduler_connector.php');	
	include ('../../common/config.php');

	ConnectorSecurity::$security_key = true;
	$scheduler = new schedulerConnector($res, $dbtype);
	$scheduler->render_table("events","event_id","start_date,end_date,event_name,details");
?>