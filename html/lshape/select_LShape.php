<?php
	include('../session.php');
	
	$fname=$lname=$name =$username= $email = $contact = $address = $password = $reg_id= $role_roleid= $menu_id= $r_read= $r_write="";

        $connection = mysql_connect("localhost", "root", "password") or die('Could not connect to localhost. ' . mysql_error());
	$db = mysql_select_db("project", $connection) or die('Could not select database of localhost');

	$dayDate=""; 

	$sql = "select * from registration where user_id = '$login_session'";

	$result = mysql_query($sql, $connection);
	$num_rows = mysql_num_rows($result);
	if ($num_rows > 0) {
		 while ($row = mysql_fetch_assoc($result)) {				
		 	$fname = $row["fname"];
			$lname = $row["lname"];
			$usrname = $row["user_id"];
			$password = $row["password"];
			$email = $row["email_id"];
			$employee_id = $row["employee_id"];
			$address = $row["address"];
			$reg_id  = $row["reg_id"];
			$r_roleid = $row["r_roleid"];

			} //echo $r_roleid;
	} else {
		$err = "Invalid user name or Password.";
	}
	$sql1 = "select * from role_rights where role_roleid = '$r_roleid'";
	$result1 = mysql_query($sql1, $connection);
	if (mysql_num_rows($result) > 0) {
	while ($row1 = mysql_fetch_assoc($result1)) {
	 	$role_roleid = $row1["role_roleid"];
		$menu_id = $row1["menu_id"];
		$r_read = $row1["r_read"];
		$r_write = $row1["r_write"];
	} 
	echo "<p hidden >". $role_roleid ."</div>";
	}
	if (isset($_GET['submit'])) { 

	if (empty($_GET['schedulerDayDate']) ) {
			$error = "Date not Found."; echo $error;
             } 
	     else 
	     {  		
    		$scheduledTime='<table border=1px solid red><tr>';                                       
		$dayDate=$_GET['schedulerDayDate']; 
		$eventScheduled = "SELECT text,sum(duration1) duration,sum(dframe) dframe FROM `events` WHERE DATE(start_date) = '$dayDate' group by text ";
		$eventScheduledResult = mysql_query($eventScheduled, $connection);
		$gtotalDuration=0;
		$gframe=0;

		while ($eventScheduledRow = mysql_fetch_assoc($eventScheduledResult)) 
		{
			$prog= $eventScheduledRow["text"];
		 	$duration = $eventScheduledRow["duration"];
			$dframe =   $eventScheduledRow["dframe"];
		
			$frame=0;
			$frameSec=0;
			$remainTime=0;		
			if($dframe >= 25){
				$frame=$dframe%25;
				$dframe1=$dframe-$frame;
				$frameSec=($dframe1/25);
			}
			else{
			    $frame=$dframe;
			}
			$totalDuration=$duration+($frameSec*1000);
			$gtotalDuration=$gtotalDuration+$totalDuration;
			$gframe=$gframe+$frame;			

	 		$dmSec=$totalDuration%1000; 
			$totalDuration=$totalDuration-$dmSec;
			$dSec=($totalDuration/1000)%60; 
			$totalDuration=($totalDuration/1000)-$dSec;
			$dmin=($totalDuration/60)%60; 
			$totalDuration=($totalDuration/60)-$dmin;
			$dhour = ($totalDuration/60);

			$scheduledTime=$scheduledTime.'<td>'.$prog.'</td><td>'.$dhour." : ".$dmin." : ".$dSec." : ".$frame.'</td>';

		} 
			
		$aGframe=0;
		$aGframeSec=0;
		if($gframe >= 25){
			$aGframe=$gframe%25;
			$gframe1=$gframe-$aGframe;
			$aGframeSec=($gframe1/25);
		}
		else{
			    $aGframe=$gframe;
		}
		$gtotalDuration=$gtotalDuration+($aGframeSec*1000);
		$rtotalDuration=86399000-$gtotalDuration;
		$rframe = 25-$aGframe;
 		$gdmSec=$gtotalDuration%1000; 
		$gtotalDuration=$gtotalDuration-$gdmSec;
		$gdSec=($gtotalDuration/1000)%60; 
		$gtotalDuration=($gtotalDuration/1000)-$gdSec;
		$gdmin=($gtotalDuration/60)%60; 
		$gtotalDuration=($gtotalDuration/60)-$gdmin;
		$gdhour = ($gtotalDuration/60);

		$rdmSec=$rtotalDuration%1000; 
		$rtotalDuration=$rtotalDuration-$rdmSec;
		$rdSec=($rtotalDuration/1000)%60; 
		$rtotalDuration=($rtotalDuration/1000)-$rdSec;
		$rdmin=($rtotalDuration/60)%60; 
		$rtotalDuration=($rtotalDuration/60)-$rdmin;
		$rdhour = ($rtotalDuration/60);

		$scheduledTime=$scheduledTime.''; 
		$scheduledTime=$scheduledTime.'<td>Total</td><td>'.$gdhour." : ".$gdmin." : ".$gdSec." : ".$aGframe.'</td><td>Remaining</td><td>'.$rdhour." : ".$rdmin." : ".$rdSec." : ".$rframe.'</td></tr>';


		$scheduledTime=$scheduledTime.'</table>';           

             }
	}			
?>


<!doctype html>
<?php 
if($login_session != ""){

?>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>Scheduler</title>

	<link rel="stylesheet" href="dhtmlxscheduler.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="stylesheet" type="text/css" href="dhtmlxcombo.css">

	<script src="dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxscheduler_editors.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxcombo.js" type="text/javascript" charset="utf-8"></script>
	<!--<script src="dhtmlxscheduler_collision.js" type="text/javascript" charset="utf-8"></script>-->
	<script src="dhtmlxscheduler_recurring.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxscheduler_readonly.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxscheduler_serialize.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxscheduler_tooltip.js" type="text/javascript" charset="utf-8"></script>
	<script src='dhtmlxscheduler_minical.js' type="text/javascript"></script>
	<script src="dhtmlxscheduler_key_nav.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlxscheduler_limit.js" type="text/javascript" charset="utf-8"></script>
	<script src="dhtmlx.js" type="text/javascript" charset="utf-8"></script>  
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<!-- <link rel="stylesheet" type="text/css" href="dhtmlx.css"> -->
	<style type="text/css" media="screen">
		html, body {
			margin: 0px; 
			padding: 0px;
			height: 100%;
			overflow: hidden;
			
		}
		.dhx_scale_hour{ 
			line-height:50px;
		}
		.dhx_cal_event div.dhx_footer,
		.dhx_cal_event.past_event div.dhx_footer,
		.dhx_cal_event.event_english div.dhx_footer,
		.dhx_cal_event.event_math div.dhx_footer,
		.dhx_cal_event.event_science div.dhx_footer{
			background-color: transparent !important;
		}
		.dhx_cal_event .dhx_body{
			-webkit-transition: opacity 0.1s;
			transition: opacity 0.1s;
			opacity: 0.7;
		}
		.dhx_cal_event .dhx_title{
			line-height: 12px;
		}
		.dhx_cal_event_line:hover,
		.dhx_cal_event:hover .dhx_body,
		.dhx_cal_event.selected .dhx_body,
		.dhx_cal_event.dhx_cal_select_menu .dhx_body{
			opacity: 1;
		}

		.dhx_cal_event.event_PGM div, .dhx_cal_event_line.event_PGM{
			background-color: orange !important;
			border-color: #a36800 !important;
		}
		.dhx_cal_event_clear.event_PGM{
			color:orange !important;
		}

		.dhx_cal_event.event_PROMO div, .dhx_cal_event_line.event_PROMO{
			background-color: #36BD14 !important;
			border-color: #698490 !important;
		}
		.dhx_cal_event_clear.event_PROMO{
			color:#36BD14 !important;
		}
		.dhx_cal_event.event_LIVE div, .dhx_cal_event_line.event_LIVE{
			background-color: #FF0000 !important;
			border-color: #839595 !important;
		}
		.dhx_cal_event_clear.event_LIVE{
			color:#B82594 !important;
		}

		.dhx_cal_event.event_LSHAPE div, .dhx_cal_event_line.event_LSHAPE{
			background-color: #000080 !important;
			border-color: #483D8B !important;
		}
		.dhx_cal_event_clear.event_LSHAPE{
			color: 	#000080 !important;
		}
		.dhx_cal_event.event_POPUP div, .dhx_cal_event_line.event_POPUP{
			background-color:  	#C71585  !important;
			border-color: #483D8B !important;
		}
		.dhx_cal_event_clear.event_POPUP{
			color: 	 	#C71585 !important;
		}
		.dhx_cal_event.event_DOGGY div, .dhx_cal_event_line.event_DOGGY{
			background-color: 	#3CB371  !important;    
			border-color: #483D8B !important;
		}
		.dhx_cal_event_clear.event_DOGGY{
			color:  	#3CB371 !important;
		}
		.dhx_cal_event {
			z-index: 1;
			cursor: pointer;
		}
		.highlighted_timespan {
			background-color: #87cefa;
			opacity:0.5;
			filter:alpha(opacity=50);
			cursor: pointer;
			z-index: 0;
		}
		

	</style>

	<script type="text/javascript" charset="utf-8">

		function status() {

			setInterval('document.getElementById("submit").click()',1000);
				  	
		}

//==== get Order Reference List ======= 

	function getOrderRefList(){ 

		var rate_agreement = document.getElementById('rate_agreement').value; 
		var date1 = scheduler.getState().date; 
		var day=date1.getDate();
		var month=date1.getMonth()+1;
		if(date1.getDate() < 10) { day='0'+date1.getDate();}
		if(month < 10) { month='0'+month;} 
		var startDate= date1.getFullYear()+ '-' +month+ '-' + day;
		var sendDate=startDate+";"+rate_agreement; 

		 $.post(                             
			"getOrderRefList.php",      
			{
			    field: "name",
			    name: sendDate
			}                              
		    ).done(                           
			function(data)
			{ 
			   var obj = JSON.parse(data);
			   
			   if(obj != "" && obj != null && obj.contains('@Error@')){
				alert(obj);
				document.getElementById('rate_agreement').value="";
				document.getElementById('agencyName').value="";
			    }
			   else{
				document.getElementById('agencyName').value=obj;
				
			   }
			}
		    );
		}

		function getpgmName(){

		var date1 = scheduler.getState().date; 
		var day=date1.getDate();
		var month=date1.getMonth()+1;
		if(date1.getDate() < 10) { day='0'+date1.getDate();}
		if(month < 10) { month='0'+month;} 
		var startDate= date1.getFullYear()+ '-' +month+ '-' + day;
		var hour = document.getElementById("hour").value;
		var minute = document.getElementById("minute").value;
		var second = document.getElementById("second").value;
		var mdsecond = document.getElementById("millisecond").value;
		if(parseInt(hour) < 10 ) hour = '0'+hour ;
		if(parseInt(minute) < 10 ) minute = '0'+minute ;
		if(parseInt(second) < 10 ) second = '0'+second ;

		startDate = startDate+" "+hour+":"+minute+":"+second;

		var sendDate=startDate+";"+mdsecond; 
			
		 $.post(                             
		        "getpgmName.php",      
		        {
		            field: "name",
		            name: sendDate
		        }                               
		    ).done(                             
		        function(data)
		        {
			     var obj = JSON.parse(data); //alert(obj);
			     if(obj != "" && obj != null){
				 var splitObj=obj.split("|"); 
				 var eventID=splitObj[0];
				 var program=splitObj[1];
				document.getElementById("pgm_id").value = eventID;
				document.getElementById("pgmName").value = program;
			    }
				else{ 
					//alert ("No Program Found before this lshape start time");
					//return;
				}
			   
		        }
		    );
		}

               //==== get Asset ID from Razuna Server using server ref,serialType,program,episode & segment ======= 
		function getAssetID(){
		
		 var e = scheduler._lightbox_out({}, scheduler._lame_copy(scheduler.getEvent(scheduler._lightbox_id)));
		
		// var razuna_name =e.text +'_'+ e.serial_type +'_'+ e.program +'_EP'+ e.episode +'_Part_'+ e.segment;
		 var razuna_name =e.program;
			
		 $.post(                             //call the server
		        "getRazunaAssetID.php",      //At this url
		        {
		            field: "name",
		            name: razuna_name
		        }                               //And send this data to it
		    ).done(                             //And when it's done
		        function(data)
		        {

			    var obj = JSON.parse(data);
			     if(obj != "" && obj != null){
				 var splitObj=obj.split("||"); 
				 var assetID=splitObj[0];
				 var duration=splitObj[1].split(":");
				 var hour=duration[0];
  				 var mint=duration[1];
				 var secnd=duration[2]; 
                                 var frame = Math.ceil((secnd - Math.floor(secnd))*25);
				 scheduler.formSection('Asset ID').setValue(assetID);
				 document.getElementById("dhour").value=parseInt(hour);
				 document.getElementById("dminute").value=parseInt(mint);
				 document.getElementById("dsecond").value=parseInt(secnd);
                                 document.getElementById("dmillisecond").value=frame;
				 durationhour();
			    }
			    else{
				 //alert("Asset ID not Found. Please give a valid Event.");
				 scheduler.formSection('Asset ID').setValue(obj);
				 document.getElementById("dhour").value=parseInt("0");
				 document.getElementById("dminute").value=parseInt("0");
				 document.getElementById("dsecond").value=parseInt("0");
				 durationhour();
			    }
		        }
		    );
		}

		function test(){ 
                                   
                        //alert("hello");
			setInterval('document.getElementById("submit").click()',1000);
			var date1 = scheduler.getState().date; 
			var day=date1.getDate();
			var month=date1.getMonth()+1;
			if(date1.getDate() < 10) { day='0'+date1.getDate();}
			if(month < 10) { month='0'+month;} alert(month);
			var ss= date1.getFullYear()+ '-' +month+ '-' + day;
			document.getElementById("schedulerDayDate").value=ss;
                        
		}
		function show_minical(){
		    if (scheduler.isCalendarVisible()){
			scheduler.destroyCalendar();
		    } else {
			scheduler.renderCalendar({
			    position:"dhx_minical_icon",
			    date:scheduler._date,
			    navigation:true,
			    handler:function(date,calendar){
				var date1=new Date(date); 
				var date11 = new Date(date1.getFullYear(),date1.getMonth(),date1.getDate(),0,0,0);
				var hour = document.getElementById("hour").value;
				var minute = document.getElementById("minute").value;
				var second = document.getElementById("second").value;
				var mdsecond = document.getElementById("millisecond").value;
				var day=date1.getDate();
				if(date1.getDate() < 10){ day='0'+date1.getDate();}
				var ss= date1.getFullYear()+ '-' +(date1.getMonth() + 1) + '-' + day;
				document.getElementById("date1").value=ss;
				durationhour(document.getElementById("date1").id);
				scheduler.destroyCalendar()
			    }
			});
		    }
		}
		
		function durationhour(event){
				
			var date1 = document.getElementById("date1").value;
			var hour = document.getElementById("hour").value;
			var minute = document.getElementById("minute").value;
			var second =document.getElementById("second").value;
			var mdsecond = document.getElementById("millisecond").value;
			var stime = (parseInt(second)*1000)+(parseInt(minute)*60*1000)+(parseInt(hour)*60*60*1000);

			var dhour = document.getElementById("dhour").value;
			var dminute = document.getElementById("dminute").value;
			var dsecond = document.getElementById("dsecond").value;
			var dmsecond = document.getElementById("dmillisecond").value;
			var duration = (parseInt(dsecond)*1000)+(parseInt(dminute)*60*1000)+(parseInt(dhour)*60*60*1000);
			var endDuration=parseInt(stime)+parseInt(duration);

			var totalFrame=parseInt(mdsecond)+parseInt(dmsecond);
			if(totalFrame >= 25){
				totalFrame=totalFrame-25;
				endDuration=parseInt(endDuration)+1000;
			}
			
			if( endDuration > 86400000 ){
				alert("You have to schedule within 24 hours of a day");
				document.getElementById(event.id).value="0";
				duration = (parseInt(document.getElementById("dsecond").value)*1000)+(parseInt(document.getElementById("dminute").value)*60*1000)+(parseInt(document.getElementById("dhour").value)*60*60*1000);
				endDuration=parseInt(stime)+parseInt(duration);

				totalFrame=parseInt(mdsecond)+parseInt(document.getElementById("dmillisecond").value);
				if(totalFrame >= 25){
					totalFrame=totalFrame-25;
					endDuration=parseInt(endDuration)+1000;
				}
			}
			
			var endmSec=parseInt(endDuration)%1000; 
			endDuration=parseInt(endDuration)-parseInt(endmSec);
			var endSec=(parseInt(endDuration)/1000)%60; 
			endDuration=(parseInt(endDuration)/1000)-parseInt(endSec);
			var endmin=(parseInt(endDuration)/60)%60; 
			endDuration=(parseInt(endDuration)/60)-parseInt(endmin);
			var endhour = (parseInt(endDuration)/60);
			var duration1 = endhour + ":" + endmin+ ":" + endSec+"   Frame: "+totalFrame;  
			var ss= date1+" "+ duration1 ;
			document.getElementById("date2").value=ss;
			getpgmName();
								
		}

		if (!window.dhtmlXCombo)
			alert("You need to have dhtmlxCombo files, to see full functionality of this sample.");

		function init(role) { 
			//alert(role);
			
			//var mygrid = new dhtmlXGridObject('products_grid');
			document.getElementById("schedulerDayDate").value="";
	                scheduler.config.multi_day = true;
			//scheduler.config.event_duration = 1;
			//scheduler.config.auto_end_date = true; 
			scheduler.config.wide_form = true;
			scheduler.config.details_on_create = true;
			scheduler.config.details_on_dblclick = true;
                        scheduler.config.separate_short_events = true;
			scheduler.locale.labels.section_text = "text";
			scheduler.config.drag_create = false;
			scheduler.config.hour_size_px = 200;
			//scheduler.xy.scale_height = 100;
			scheduler.xy.scale_width = 100;
			//scheduler.config.delay_render = 30;


			dhtmlXTooltip.config.className = 'dhtmlXTooltip tooltip'; 
			dhtmlXTooltip.config.timeout_to_display = 50; 
			dhtmlXTooltip.config.delta_x = 15; 
			dhtmlXTooltip.config.delta_y = -20;

			scheduler.templates.event_class=function(start, end, event){
				var css = "";

				if(event.text) // if event has subject property then special class should be assigned
					css += "event_"+event.text;

				if(event.id == scheduler.getState().select_id){
					css += " selected";
				}
				return css; // default return

			};


			var text = [
				{key:"PGM", label:"PGM"},
				{key:"PROMO", label:"PROMO"},
			        {key:"LIVE", label:"LIVE"},
			        {key:"COM", label:"COM"},
				{key:"FILLER", label:"FILLER"}
			];

			scheduler.form_blocks["my_editor"]={
			    render:function(sns){

var aa="<div class='dhx_cal_ltext' style='height:150px;border:1px solid red;color:black'>Start Time :<div style='border:0px solid blue;margin-left:70px;width:50px;height:30px;margin-top:-10px' class='dhx_cal_navline' ><div class='dhx_cal_date' style='border:0px solid green;margin-top:-15px'></div><div style='border:0px solid yellow;margin-left:-210px;margin-top:-35px'  class='dhx_minical_icon' id='dhx_minical_icon' onclick='show_minical()'></div></div><input style='border:1px solid blue;margin-top:10px;margin-left:40px;height:40px;width:100px' type='text' id='date1' name='date1' readOnly = true> &nbsp; Hour <select id='hour' name='hour' onchange='durationhour(this)'><?php $i=0; while($i<24 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select> &nbsp; Minute <select id='minute' name='minute' onchange='durationhour(this)' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Second <select name='second' id='second'onchange='durationhour(this)' ><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Frame <select name='millisecond' id='millisecond' onchange='durationhour(this)'  ><?php $i=0; while($i<25) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select></br> </br>  Duration : &nbsp; &nbsp; Hour &nbsp; <select id='dhour' name='dhour' onchange='durationhour(this)' ><?php $i=0; while($i<24 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Minute &nbsp;<select id='dminute' name='dminute' onchange='durationhour(this)'><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Second &nbsp;<select name='dsecond' id='dsecond' onchange='durationhour(this)'><?php $i=0; while($i<60 ) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select>&nbsp; Frame &nbsp;<select name='dmillisecond' id='dmillisecond' onchange='durationhour(this)'><?php $i=0; while($i<25) {echo '<option value='.$i.'>'.$i. '</option>';  $i++;} ?></select> </br> </br>End Time :<input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:200px' type='text' id='date2' name='date2' readOnly = true> <input type='checkbox' id='serial_type' name='serial_type' value='serial_type' style='margin-top:10px;margin-left:120px;'> <font size='3px'> BONUS</font></div>";
				
return aa ;
			    },
			    set_value:function(node,value,ev){

			    },
			    get_value:function(node,ev){
				var date1 = document.getElementById("date1").value;
				var hour = document.getElementById("hour").value;
				var minute = document.getElementById("minute").value;
				var second =document.getElementById("second").value;
				var mdsecond = document.getElementById("millisecond").value;
				var stime = (parseInt(second)*1000)+(parseInt(minute)*60*1000)+(parseInt(hour)*60*60*1000);
			
				var date1 = new Date(date1);
				var date11 = new Date(date1.getFullYear(),date1.getMonth(),date1.getDate());
    				var localOffset = 6*60*60*1000;
				var aa=parseInt(date1.getTime())+parseInt(stime);	
				ev.start_time=aa-localOffset;
				ev.durationtt= node.childNodes[15].value+':'+node.childNodes[17].value+':'+node.childNodes[19].value+':'+node.childNodes[21].value;
				//===============End Time
				
				var dhour = document.getElementById("dhour").value;
				var dminute = document.getElementById("dminute").value;
				var dsecond = document.getElementById("dsecond").value;
				var dmsecond = document.getElementById("dmillisecond").value;
				var duration = (parseInt(dsecond)*1000)+(parseInt(dminute)*60*1000)+(parseInt(dhour)*60*60*1000);
				var endDuration=parseInt(stime)+parseInt(duration);
				var endTime=parseInt(date1.getTime())+parseInt(endDuration);
				var totalFrame=parseInt(mdsecond)+parseInt(dmsecond);
				if(totalFrame >= 25){
					totalFrame=totalFrame-25;
					endTime=parseInt(endTime)+1000;
				}
	
				ev.end_time=endTime-localOffset;
				//alert(endTime-localOffset);
				ev.duration1=duration; //===millisec = Frame
				ev.dframe=dmsecond;
				ev.sframe=mdsecond;
				ev.eframe=totalFrame; 
				if(!document.getElementById("serial_type").checked) ev.serial_type=""; else ev.serial_type="BONUS";
				//ev.serial_type=node.childNodes[22].value;
				return node.childNodes[12].value;
			    },
			    focus:function(node){
				var a=node.childNodes[2]; a.select(); a.focus(); 
			    }
			}


			scheduler.form_blocks["program_info"]={

			    render:function(sns){

var p="<div class='dhx_cal_ltext' style='height:80px;border:1px solid red;color:black;'>Commercial Name :<div style='border:0px solid blue;margin-left:70px;width:50px;height:30px;margin-top:-10px' class='dhx_cal_navline'></div><input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:80%' type='text' id='prog' name='prog' onchange='getAssetID()' >&nbsp; Program Name : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:58%' type='text' id='pgmName' name='pgmName' disabled >&nbsp;&nbsp; Repeat : <input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:50px' type='text' id='segment' name='segment' > <input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:50px' type='text' id='pgm_id' name='pgm_id' hidden ></div>";
				
			return p ;
			},
	   		set_value:function(node,value,ev){


			    },
			get_value:function(node,ev){
				ev.program=document.getElementById("prog").value;
				ev.pgmName=document.getElementById("pgmName").value;
				ev.segment=document.getElementById("segment").value;
				ev.pgm_id=document.getElementById("pgm_id").value;
				return node.childNodes[2].value;
			    },
			focus:function(node){
				var a=node.childNodes[2]; a.select(); a.focus(); 
			    }
						    
			}

			scheduler.form_blocks["order_info"]={

			render:function(sns){

			var p="<div class='dhx_cal_ltext' style='height:50px;border:1px solid red;color:black;'>Rate Agreement No :<div style='border:0px solid blue;margin-left:70px;width:50px;height:30px;margin-top:-10px' class='dhx_cal_navline'></div><input style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:20%' type='text' id='rate_agreement' name='rate_agreement' onchange='getOrderRefList()'  >&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; Agency Name : <input id='agencyName' style='border:1px solid blue;margin-top:10px;margin-left:10px;height:30px;width:40%' disabled></div>";
				
			return p ;
			},
	   		set_value:function(node,value,ev){


			    },
			get_value:function(node,ev){

				ev.rate_agreement=document.getElementById("rate_agreement").value;
				//ev.order_ref=document.getElementById("order_ref").value;
				return node.childNodes[2].value;
			    },
			focus:function(node){
				var a=node.childNodes[2]; a.select(); a.focus(); 
			    }
						    
			}
			
			scheduler.config.lightbox.sections = [
				{ name: "Type", height: 30, map_to: "text", type: "select", focus: true, options:[
						{key:"LSHAPE", label:"LSHAPE"},
						{key:"POPUP", label:"POPUP"},
						{key:"DOGGY", label:"DOGGY"},

				] , filtering : true},
				{ name:"Rate Agreement Info", height:100, map_to:"order_ref", type:"order_info" , focus:true},
 				{ name:"Time period", height:450, map_to:"description", type:"my_editor" , focus:true},
				//{ name: "Commercial Type", height: 30, map_to: "serial_type", type: "select", focus: true, options:[
				//		{key:"", label:""},
				//		{key:"PEAK", label:"PEAK"},
				//		{key:"OFF PEAK", label:"OFF PEAK"},
				//		{key:"BONUS", label:"BONUS"},
				//] , filtering : true},

				{ name:"Program Name ", height:100, map_to:"prog", type:"program_info" , focus:true},
				{ name: "Asset ID", height: 25, type: "textarea", map_to: "asset",focus: true},
                                //{ name: "Formats", height: 30, type: "select", map_to: "format", focus: true, options:[
				//		{key:"Now", label:"Now"},
				//		{key:"CTDN", label:"CTDN"},
				//		{key:"Now, Next", label:"Now, Next"}
				//]},

                                { name: "Remarks", height: 25, type: "textarea", map_to: "remark", focus: true},
				//{ name:"recurring", height:115, type:"recurring", map_to:"rec_type", button:"recurring"},
				{ name: "Input", height: 20, type: "textarea", map_to: "input_type", focus: false},


			];
			
			var step = 60;  //=======Changed for 1 hour Slot
			var format = scheduler.date.date_to_str("%H:%i:%s");
			scheduler.config.hour_size_px=(60/step)*200;
			var id=0;
			scheduler.templates.hour_scale = function(date){
				html="";
			for (var i=0; i<60/step; i++){
				if (id >= 24){ id=0; //=======Changed for 1 hour Slot
					id=id+1;
				}
				else id=id+1;	

				html+="<div id='"+'slot'+id+"' style='height:100px;line-height:15px;border-bottom:1px solid #CECECE;color:black;font-size:14px'>"+format(date)+"<br><br><span id='"+'slott'+id+"' style='color:#B82594;font-size:14px,font-weight:bold'></span> <br><span id='"+'pslott'+id+"' style='color:orange;font-size:14px,font-weight:bold'></span></div>";
				date = scheduler.date.add(date,step,"minute");

			}
			return html;
			}
			//var unix = Math.round(+new Date()/1000);
			//var milliseconds = new Date().getTime();alert(milliseconds);

			scheduler.config.xml_date = "%Y-%m-%d %H:%i:%s";
			scheduler.templates.tooltip_date_format=scheduler.date.date_to_str("%Y-%m-%d %H:%i:%s");
			scheduler.config.hour_date = "%H:%i:%s";
			scheduler.config.calendar_hour = "%H:%i:%s";
			scheduler.config.repeat_date = "%d/%m/%Y";
			scheduler.config.include_end_by = true;
			//scheduler.config.readonly = true;


			scheduler.attachEvent("onLightbox", function(){
			   var section = scheduler.formSection("Asset ID");
			   section.control.disabled = true;
			   //=================Asset ID ==============
			    scheduler.formSection("Server Reference").control.onchange=function(){getAssetID();};
			    scheduler.formSection("Serial Type").control.onchange=function(){getAssetID();};
			});
			scheduler.attachEvent("onLightbox", function(){
			   var section = scheduler.formSection("Input");
			   section.control.disabled = true;
			});
			scheduler.attachEvent('onBeforeDrag', function(id){ //alert("onBeforeDrag");
			       return false;  
			});
			/*scheduler.attachEvent("onBeforeEventChanged", function(event_object, native_event, is_new){
			    return false;
			});*/
			scheduler.attachEvent("onTemplatesReady", function() { 
			var fix_date = function(date) {  // 17:48:56 -> 17:30:00
				date = new Date(date);
				//=======blocked for 1 hour Slot
				/*if (date.getMinutes() > 30)
					date.setMinutes(30);
				else
					date.setMinutes(0);*/
				date.setMinutes(0); //=======added for 1 hour Slot
				date.setSeconds(0);
				return date;
			};

			var marked = null;
			var marked_date = null;
			var event_step = 60; //=======Changed for 1 hour Slot
 			scheduler.attachEvent("onEmptyClick", function(date, native_event){
				scheduler.unmarkTimespan(marked);
				marked = null;

				var fixed_date = fix_date(date);
				//scheduler.addEventNow(fixed_date, scheduler.date.add(fixed_date, event_step, "minute"));
			});

			scheduler.attachEvent("onMouseMove", function(event_id, native_event) { //alert("Mouse Move");
				var date = scheduler.getActionData(native_event).date;
				var fixed_date = fix_date(date);

				if (+fixed_date != +marked_date) {
					scheduler.unmarkTimespan(marked);

					marked_date = fixed_date;
					marked = scheduler.markTimespan({
						start_date: fixed_date,
						end_date: scheduler.date.add(fixed_date, event_step, "minute"),
						css: "highlighted_timespan"
					});
				}
			});

		});

			//scheduler.config.delay_render = 30;
			scheduler.config.show_quick_info = false;
			scheduler.config.show_loading = true;
			scheduler.init('scheduler_here', new Date(), "day");			
			scheduler.load("data/connector.php", function(){
				//show lightbox
				//scheduler.showLightbox("1261150549");
			});
   
			var dp = new dataProcessor("data/connector.php");
			dp.init(scheduler);
			
		}

	</script>

</head>

<body onload="init('<?php echo $role_roleid;?>');">

<center><a target = "_blank" href = "getAssetInfo.php"> <b>Search Asset Info </b></a></center>
<form  method="get" action="" id="myForm" >

<div id="scheduler_here" class="dhx_cal_container" style="width:100%; height:100%;">
	<div class="dhx_cal_navline">
		<div class="dhx_cal_prev_button">&nbsp;</div>
		<div class="dhx_cal_next_button">&nbsp;</div>
		<div class="dhx_cal_today_button"></div>
              <!--  <div style="height:;width:;border:0px solid red;float:right;margin-left:20%;margin-top:2px;display:block"><input type="submit"  name="submit" value="Schedule Status" id="submit" onclick="test()" /></div> -->
                <div style="height:;width:;border:0px solid red;float:right;margin-left:35%;margin-top:2px;display:block"><input type="text" id="schedulerDayDate" name="schedulerDayDate" value="<?php echo $dayDate ?>" hidden></div> 
                <div style="height:;width:;border:0px solid red;float:right;margin-left:95%;margin-top:2px;display:block"><a href = "../logout.php"><img src="imgs/logout.png" alt=""  height=100% width=100%></img></a></li></div>
		<div style="height:50px;width:90px;border:0px solid red;float:right;margin-left:70%;margin-top:-10px;display:block"><a href = "../profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a></div>
		<div class="dhx_cal_date"></div>
		<!--<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
		<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
		<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>-->
		
</div>

	<div class="dhx_cal_header">
	</div>
	<div style="border:0px solid red" class="dhx_cal_data">
	</div>

</div>
</body></form>
<?php } else { header("Location: ../index.php");} 

 mysql_close($connection); 

?>
