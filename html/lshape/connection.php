<?php
	$link = mysql_connect('localhost', 'root', 'password')
		or die('Could not connect: ' . mysql_error());
	mysql_select_db('project') or die('Could not select database');	
	
	//echo "Connected to Database..";
?>
