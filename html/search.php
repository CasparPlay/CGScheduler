	<?php
	$connection = mysql_connect('localhost', 'root', 'password');
	$db = mysql_select_db('userdata', $connection);	
	$term = strip_tags(substr($_POST['searchit'],0, 100));
	$term = mysql_escape_string($term); // Attack Prevention
	if($term=="")	echo "Enter Something to search";
	else{
	$query = mysql_query("select * from registration where fname like '%$term%'", $connection);
	$string = '';
	 
	if (mysql_num_rows($query)){
	while($row = mysql_fetch_assoc($query)){
	$string .= "<b>".$row['fname']."</b> - ";
	$string .= $row['lname']."</a>";
	$string .= "<br/>\n";
}
 
	}else{
	$string = "No matches found!";
	}
	 
	echo $string;
	}
        mysql_close($connection); 
	?>
