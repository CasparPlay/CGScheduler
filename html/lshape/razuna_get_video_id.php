<html>
<head>
<meta http-equiv="refresh" content="5; url=razuna_get_video_id.php">
</head>
<body>
				
				<?php
				   ob_start();
				   
				   $dbhost = 'localhost';
				   $dbuser = 'root';
				   $dbpass = 'password';
				   
				   $videoName = array();
				   $assetID = array();
				   $i = 0;
				   echo $name;
				   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
				   
				   if(! $conn )
				   {
					  die('Could not connect: ' . mysql_error());
				   }
				   
				   $sql = "SELECT program from events where asset = '';";
				   mysql_select_db('project');
				   $retval = mysql_query( $sql, $conn );
				   
				   if(! $retval )
				   {
					  die('Could not get data: ' . mysql_error());
				   }
				   
				   while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
				   {
					//echo $row['program'].'<br>';
					$videoName[$i++] = $row['program'];
				   }
				   for($i = 0;$i<count($videoName);$i++)
				   {
					   echo $videoName[$i].'<br>';
				   }
				   mysql_close($conn);
				   //all done for scheduler 
				   
				   //===================================================================
				   $dbhost = 'localhost';
				   $dbuser = 'root';
				   $dbpass = 'password';
				   //$name = $_POST['v_name'];
				   //echo $name;
				   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
				   
				   if(! $conn )
				   {
					  die('Could not connect: ' . mysql_error());
				   }
				   mysql_select_db('razuna');
				    for($i = 0;$i<count($videoName);$i++)
					{
				   $sql = "SELECT VID_ID from raz1_videos where vid_filename='$videoName[$i]'";
				   
				   $retval = mysql_query( $sql, $conn );
				   
				   if(! $retval )
				   {
					  die('Could not get data: ' . mysql_error());
				   }
				   
				   while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
				   {
					//echo $row['program'].'<br>';
					$assetID[$i] =  $row['VID_ID'];
				   }
				   
				   }
				   mysql_close($conn);
				   
				   // all done for razuna 
				   //=======================================================================
				   $dbhost = 'localhost';
				   $dbuser = 'root';
				   $dbpass = 'password';
				   
				   
				   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
				   
				   if(! $conn )
				   {
					  die('Could not connect: ' . mysql_error());
				   }
				   mysql_select_db('project');
				   
				   for($i = 0;$i<count($videoName);$i++)
				   {
				   $sql = "update events set asset = '$assetID[$i]' where program = '$videoName[$i]' ";
				   
				  if (mysql_query( $sql, $conn ) === TRUE) {
						echo "New record created successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					}
				   }
				   
				  mysql_close($conn);
				   
				  ob_end_flush(); 
				  
				   
				?>
		</body>
</html>		