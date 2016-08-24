<?php

	$pname= json_encode($_POST['name']); 
	
	$asset_id="";
	$pname= json_decode($pname) ;
	$meta="";

	$connection = mysql_connect("localhost", "root", "password") or die('Could not connect to razuna mysql. ' . mysql_error());
	$db = mysql_select_db("razuna", $connection) or die('Could not select razuna database.');
	
	//===================== Check Asset ID in Razuna Server for Scheduler Event ===========
        /*
	$sql = "SELECT VID_ID,FOLDER_ID_R,VID_NAME_ORG FROM `raz1_videos` WHERE LOWER(REPLACE(SUBSTRING(SUBSTRING_INDEX( VID_FILENAME, '.', 1),LENGTH(SUBSTRING_INDEX(VID_FILENAME, '.', 1-1)) + 1),'.', '')) = LOWER('$pname') ";*/
	//=========================== Tauwab ====================================================================
        $sql = "SELECT VID_ID,FOLDER_ID_R,VID_NAME_ORG FROM `raz1_videos` WHERE VID_FILENAME = '$pname' ";
        //=======================================================================================================
	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);

	if($num_rows > 0){
	
		while ($row = mysql_fetch_assoc($result)) 
		{
		 	$asset_id = $row["VID_ID"];
			$vid_name = $row["VID_NAME_ORG"];
                        $vid_folder = $row["FOLDER_ID_R"];
		}
                /*
		$arr = explode("\n", $meta);
		$content="";
		if(sizeof($arr) > 0){
			for($i=0;$i<sizeof($arr);$i++){
				$content=$arr[$i];
				$a=stristr($content, 'Duration');
				if($a){break;}
			}
			$sendData=$asset_id.'||'.$content;	
		}
                */
                //=============================tauwab=====================
                  
                 
                 $vid_link = '/u01/razuna_tomcat_1_7/tomcat/webapps/razuna/kmlassets/2/'.$vid_folder.'/vid/';
                 $FinalLink = $vid_link.$asset_id."/".$vid_name;
                 //echo $FinalLink;
                 $content = exec("ffmpeg -i ".$FinalLink." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
                 /*ffmpeg -i /u01/razuna_tomcat_1_7/tomcat/webapps/razuna/raz2/dam/incoming/
                 api951C40163E3F4B53BC19464014387B40/FILLER_Ki_Jadu_ImranPuja_04m53s.mxf 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//*/
                 //echo $time;

                 //=========================================================
                 $sendData=$asset_id.'||'.$content;

	}	
	mysql_close($connection); 
	echo json_encode($sendData) ;

	
?>

