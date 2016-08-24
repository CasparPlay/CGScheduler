<?php
	require_once('../../common/connector/scheduler_connector.php');
	include ('../../common/config.php');

	$list = new OptionsConnector($res, $dbtype);
	$list->render_table("types","typeid","typeid(value),name(label)");
	
	$scheduler = new schedulerConnector($res, $dbtype);
	//$scheduler->enable_log("log.txt",true);
	
	$scheduler->set_options("type", $list);
	$scheduler->render_table("tevents","event_id","start_date,end_date,event_name,type");
?>