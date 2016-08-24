<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>log</title>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="jquery-1.10.2.js"></script>
  <script src="jquery-ui.js"></script>
  <style type="text/css" media="screen">
  body
  {
	  background-color : lightblue;
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
		<form method="post" action="">
		
		<p style = "display: inline-block;"><font size="3">&nbsp;Date: </font><input type="text" name="date" id="datepicker"></p>
		
		<p style = "display: inline-block;"><font size="3">&nbsp;Program Type: </font><select name="prog_type">
           <option></option>      
           <option value="PGM">PGM</option>
           <option value="COM">COM</option>
           <option value="FILLER">FILLER</option>
           <option value="PROMO">PROMO</option>
           <option value="LIVE">LIVE</option>
        </select></p>
	
		<p style = "display: inline-block;"><font size="3">&nbsp;Program Name: </font><input type="text" name="prog_name"></p>
		
		<input type="submit" value="Generate Log file" name = "create_log" style=" width: 80px; display: inline-block;">
		</form>
	</div>
	
</div>

 <br><br>
   <?php

       include 'db_connection_project.php';

	   $date_flag = 0;$type_flag = 0;$name_flag = 0;
	   $date = $prog_type = $prog_name = '';
	   if(isset($_POST['create_log']))
	   {
		   $count = 0;
		   array_map('unlink',glob("/u01/log/*.csv"));
	
       if($_POST['date']!='')
	   {
		   $date_flag = 1;
		   $date = $_POST['date'];
		   
	   }
	   if($_POST['prog_type']!='')
	   {
		   $type_flag = 3;
		   $prog_type = $_POST['prog_type'];
	   }
	   if($_POST['prog_name']!='')
	   {
		   $name_flag = 5;
		   $prog_name = $_POST['prog_name'];
	   }
		
	   $count = $date_flag + $type_flag + $name_flag;
	   
	   
	   if($count == 1)
	   {
		   $sql = "select program_type,name,round(duration/25) as duration,date(played_time),time(played_time) from primary_log where date(played_time) ='$date' order by played_time into outfile '/u01/log/casparcg_log.csv' fields terminated by ',' enclosed by '\\\"' Lines terminated by '\\n'";
	   }
	   
	   else if ($count == 5)
	   {
           $sql = "select program_type,name,round(duration/25) as duration,date(played_time),time(played_time) from primary_log where name COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by played_time into outfile '/u01/log/casparcg_log.csv' fields terminated by ',' enclosed by '\\\"' Lines terminated by '\\n'";
		   
	   }

	   else if ($count == 4)
	   {
           $sql = "select program_type,name,round(duration/25) as duration,date(played_time),time(played_time) from primary_log where date(played_time) ='$date' and name COLLATE UTF8_GENERAL_CI like '$prog_type%' order by played_time into outfile '/u01/log/casparcg_log.csv' fields terminated by ',' enclosed by '\\\"' Lines terminated by '\\n'";
		   
	   }

	   else if ($count == 6)
	   {
           $sql = "select program_type,name,round(duration/25) as duration,date(played_time),time(played_time) from primary_log where date(played_time) ='$date' and name COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by played_time into outfile '/u01/log/casparcg_log.csv' fields terminated by ',' enclosed by '\\\"' Lines terminated by '\\n'";
		  
	   }
	   
	   else
	   {
          $sql = "select program_type,name,round(duration/25) as duration,date(played_time),time(played_time) from primary_log where date(played_time) ='$date' and name COLLATE UTF8_GENERAL_CI like '%$prog_name%' and name COLLATE UTF8_GENERAL_CI like '$prog_type%' order by played_time into outfile '/u01/log/casparcg_log.csv' fields terminated by ',' enclosed by '\\\"' Lines terminated by '\\n'";
		  
	   }


             
   if(mysqli_query($con,$sql))
   {
    echo "<b>Log file generated </b><br>";
    echo "<a href='download.php?nama=/u01/log/casparcg_log.csv'>download</a> ";
   }
   else
    echo "<b>Log file generated </b>";

    mysqli_close($con); 
  
       }
        ?>        
  

</center>
</body>
</html>
