<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create PlayList</title>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="jquery-1.10.2.js"></script>
  <script src="jquery-ui.js"></script>
  <style type="text/css" media="screen">
  body
  {
	  background-color : #9F9FA9;
  }
    table 
	{ 
		border-collapse: collapse; 
		font-family: Futura, Arial, sans-serif; 
		border: 1px solid #777; 
		
		
		
	}
    caption 
	{ 
		font-size: larger; 
		margin: 1em auto; 
	}
    th, td 
	{ 
		padding: .65em; 
	}
    th, thead 
	{ 
		background: black; 
		color: #fff; 
		border: 1px solid #000; 
	}
    tr:nth-child(odd) 
	{ 
		background: #D6F1A3; 
	}
    tr:hover 
	{ 
		background: #aaa; 
	}
    td 
	{ 
		border-right: 1px solid #777;
	}
	
	#searchContainer
	{
	    	
	}
    #dateSearch
	{
		border:1px solid white;
		width:70%;
		display: inline-block;
	}
	#nameSearch
	{
		border:1px solid #CECECE;
		width:25%;
		display: inline-block;
	}
 </style>
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({
      //showButtonPanel: true;
	  dateFormat: "yy-mm-dd"
    });
	
  });
  </script>
</head>
<body>


<center>
<div id = "searchContainer">
	<div id="dateSearch">
		<form method="post" action="program_time_mismatch.php">
		
		<p style = "display: inline-block;"><font size="3">&nbsp;Date: </font><input type="text" name="date" id="datepicker"></p>
		
		
		<input type="submit" value="Search" name = "DateSearch" style=" width: 80px; display: inline-block;">
		</form>
	</div>
	
</div>
</center>
<br><br>
<div>
<div style = "float:left">
 
           
  <?php
       include 'db_connection_project.php';
	   $date_flag = 0;$type_flag = 0;$name_flag = 0;
	   $date = $prog_type = $prog_name = '';
	   if(isset($_POST['DateSearch']))
	   {
		   $count = 0;
           $date = $_POST['date']; 
		  ?>
		  
	   <table border="1">
				<tr>
				<th>program name</th>
                <th>start time</th>
                <th>end time</th>
                <th>status</th>
				</tr>
	<?php
       
		   $sql = "select id,program,start_date,end_date from events where date(start_date) ='$date' order by start_date";
	       $extra = 0;
	       $time_mismatch = "";

              
   if($query_run=  mysqli_query($con,$sql)){
      while($query_row=  mysqli_fetch_assoc($query_run)){
          
          $schedule_id = $query_row['id'];
          $program_name = $query_row['program'];
          $start_time = $query_row['start_date'];
          $end_time = $query_row['end_date'];
          $sql = "select played_time from primary_log where scheduler_id = '$schedule_id'";
          
          if($inner_query_run=  mysqli_query($con,$sql))
          {
            while($inner_query_row=  mysqli_fetch_assoc($inner_query_run))
            {
                $arr = explode(" ", $start_time);
                $arr= explode(":",$arr[1]);
                $start_time_hr = intval($arr[0])*60*60;
                $start_time_min = intval($arr[1])*60;
                $start_time_sec = intval($arr[2]);
                $total_start_time = $start_time_hr + $start_time_min + $start_time_sec;
                $arr = explode(" ",$inner_query_row['played_time']);
                $arr = $arr= explode(":",$arr[1]);
                $played_time_hr = intval($arr[0])*60*60;
                $played_time_min = intval($arr[1])*60;
                $played_time_sec = intval($arr[2]);
                $total_played_time = $played_time_hr + $played_time_min + $played_time_sec;
                $extra =  $total_played_time - $total_start_time;
                $time_mismatch = strval(intval($extra/3600)).":".strval(intval(($extra % 3600)/60)).":".strval(intval($extra % 3600)%60);
                
            }
          }
          ?>
		  

<tr>
<td><b><?php echo $program_name;?></b></td>
<td><b><?php echo $start_time;?></b></td>
<td><b><?php echo $end_time;?></b></td>
<!--
<td><b><button onclick="myFunction('<?php echo $status;?>')">show info</button></b>
<form action="videoDetails.php?FinalLink=<?php echo $FinalLink; ?>" method="get">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>
-->
<td><b> <?php echo $inner_query_row['played_time']; ?></b></td>

</tr>

<?php
      }
  }
	            
   else
       echo "<b>Sorry,No asset found !!!<b>";
	   }     
       
        ?>
</table> 
</div>

</body>
</html>
