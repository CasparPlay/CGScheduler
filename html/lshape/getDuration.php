<?php

$asset_id = '951C40163E3F4B53BC19464014387B40';
$vid_link = '/u01/razuna_tomcat_1_7/tomcat/webapps/razuna/raz2/dam/incoming/api';
$vid_name = 'FILLER_Ki_Jadu_ImranPuja_04m53s.mxf';

$FinalLink = $vid_link.$asset_id."/".$vid_name;
echo $FinalLink;
//$file=sample.avi;
$time = exec("ffmpeg -i ".$FinalLink." 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//");
//ffmpeg -i /u01/razuna_tomcat_1_7/tomcat/webapps/razuna/raz2/dam/incoming/api951C40163E3F4B53BC19464014387B40/FILLER_Ki_Jadu_ImranPuja_04m53s.mxf 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//
echo $time;

?>
