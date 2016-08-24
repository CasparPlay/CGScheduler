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
		<form method="post" action="getAssetInfo.php">
		
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
		
		<input type="submit" value="Search" name = "DateSearch" style=" width: 80px; display: inline-block;">
		</form>
	</div>
	<!--
	<div id="nameSearch">
		<form method="post" action="getAssetInfo.php">
		<br>
		<p><font size="3">&nbsp;Program Type: </font><select name="prog_type">
           <option></option>      
           <option value="PGM">PGM</option>
           <option value="COM">COM</option>
           <option value="FILLER">FILLER</option>
           <option value="PROMO">PROMO</option>
           <option value="LIVE">LIVE</option>
        </select></p>
		<p><font size="3">&nbsp;Program Name: </font><input type="text" name="prog_name" id="datepicker"></p>
		
		
		<br><br>
		<input type="submit" value="Search" name = "NameSearch" style="margin-left:35%;">
		</form>
	</div>	
	-->
</div>

 <br><br>
           
  <?php
       include 'db_connection.php';
	   $date_flag = 0;$type_flag = 0;$name_flag = 0;
	   $date = $prog_type = $prog_name = '';
	   if(isset($_POST['DateSearch']))
	   {
		   $count = 0;
		  ?>
		  
	   <table border="1">
				<tr>
				<th>program name</th>
				<th>ingest date</th>
				<th>duration</th>
				</tr>
	<?php
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
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_CREATE_DATE ='$date' order by VID_FILENAME";
	   }
	   else if ($count == 3)
	   {
		  $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_FILENAME like '%$prog_type%' order by VID_FILENAME";
	   }
	   else if ($count == 5)
	   {
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_FILENAME COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by VID_FILENAME";
	   }
	   else if ($count == 4)
	   {
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_CREATE_DATE ='$date' and VID_FILENAME like '%$prog_type%' order by VID_FILENAME";
	   }
	   else if ($count == 6)
	   {
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_CREATE_DATE ='$date' and VID_FILENAME COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by VID_FILENAME";
	   }
	   else if ($count == 8)
	   {
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_FILENAME like '%$prog_type%' and VID_FILENAME COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by VID_FILENAME";
	   }
	   else
	   {
		   $sql = "select VID_FILENAME,VID_CREATE_TIME,VID_META from raz1_videos where VID_CREATE_DATE ='$date' and VID_FILENAME like '%$prog_type%' and VID_FILENAME COLLATE UTF8_GENERAL_CI like '%$prog_name%' order by VID_FILENAME";
	   }

              
   if($query_run=  mysqli_query($con,$sql)){
      while($query_row=  mysqli_fetch_assoc($query_run)){
          //$id_update=$query_row['id'];
          $vid_filename = $query_row['VID_FILENAME'];
          $vid_create_time = $query_row['VID_CREATE_TIME'];
          $vid_meta = $query_row['VID_META'];
		  
          $arr = explode("\n", $vid_meta);
		  $content="";
		  
			for($i=0;$i<sizeof($arr);$i++)
			{
				$content=$arr[$i];
				$a=stristr($content, 'Duration');
				if($a){break;}
			}
			
          ?>
		  
<tr>
<td><b><?php echo $vid_filename;?></b></td>
<td><b><?php echo $vid_create_time;?></b></td>
<td><b><?php echo $content;?></b></td>

</tr>
<?php
      }
  }
	            
   else
       echo "<b>Sorry,No asset found !!!<b>";
	   }     
       
        ?>
</table> 
</center>
</body>
</html>
