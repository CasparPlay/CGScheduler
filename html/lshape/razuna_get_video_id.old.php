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
                                   $programID = array();
				   $i = 0;
				   echo $name;
				   $conn = mysql_connect($dbhost, $dbuser, $dbpass);
				   
				   if(! $conn )
				   {
					  die('Could not connect: ' . mysql_error());
				   }
				   
				   $sql = "SELECT id,text,serial_type,program,episode,segment from events where asset = '';";
				   mysql_select_db('project');
				   $retval = mysql_query( $sql, $conn );
				   
				   if(! $retval )
				   {
					  die('Could not get data: ' . mysql_error());
				   }
				   
				   while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
				   {
					//echo $row['program'].'<br>';
                                        $text = $row["text"];
                                       // echo $text;
			                $program = $row["program"];
			                $razuna_name =$text.'_'.$row["serial_type"] .'_'. $program .'_EP'.$row["episode"].'_Part_'.$row["segment"];
                                       //echo $razuna_name;
                                        
					$videoName[$i] = $razuna_name;
                                        $programID[$i] = $row["id"];
                                        $i++;
				   }
                                   //echo $videoName[0];
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
				   
				   for($i = 0;$i<count($programID);$i++)
				   {
                                    
				   $sql = "update events set asset = '$assetID[$i]' where id = '$programID[$i]' ";
				   echo $assetID[$i];
                                   echo $program[$i];
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
