<?php

$connection1 = mysql_connect("10.3.10.191","root","password") or die('Could not connect to Primary Server. '.mysql_error());	
$db1 = mysql_select_db("casparcg", $connection1) or die('Could not select database of Primary Server.');
$sql = "SELECT name FROM `playlist` WHERE id = 25452";
$result = mysql_query($sql, $connection1);
while ($row = mysql_fetch_assoc($result)) 
		{
			
		 	
			$name = $row["name"];
			echo $name;

		}
		
?>
