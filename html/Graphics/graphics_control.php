<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Graphics Control</title>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="jquery-1.10.2.js"></script>
  <script src="jquery-ui.js"></script>
  <script>
	function ticker_on(url){
		//var address1 = "exitLive.php"
		window.location.assign(url)
		}
  function ticker_off(url){
		//var address1 = "exitLive.php"
		window.location.assign(url)
		}
</script> 
  <style type="text/css" media="screen">
  body
  {
	  background-color : lightblue;
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
 
</head>
<body>


<center>

     <h1> Graphics Control </h1>
     <hr>      
  
       
     <p id="demo"></p>
	   
		  
	   <table border="1">
				<tr>
				<th>graphics ID</th>
				<th>graphics Name</th>
				<th>Layer</th>
                <th>loop</th>
                <th>Meta</th>
                <th>Play</th>
                <th>Off</th>
                
				</tr>
	
      <?php
           include 'db_connection_project.php';
		   $sql = "select * from graphics";
	   

              
   if($query_run=  mysqli_query($con,$sql)){
      while($query_row=  mysqli_fetch_assoc($query_run)){
          //$id_update=$query_row['id'];
          $graphics_id = $query_row['id'];
          $graphics_name = $query_row['name'];
          $graphics_layer = $query_row['layer'];
          $graphics_loop = $query_row['loop'];
          $graphics_meta = $query_row['meta'];
          $url_on = "graphics_on_off.php?id=".$graphics_id."&&name=".$graphics_name."&&layer=".$graphics_layer."&&loop=".$graphics_loop."&&on=true";
          $url_off = "graphics_on_off.php?id=".$graphics_id."&&name=".$graphics_name."&&layer=".$graphics_layer."&&loop=".$graphics_loop."&&on=false";
          ?>
		  
<tr>
<td><b><?php echo $graphics_id;?></b></td>
<td><b><?php echo $graphics_name;?></b></td>
<td><b><?php echo $graphics_layer;?></b></td>
<td><b><?php echo $graphics_loop;?></b></td>
<td><b><?php echo $graphics_meta;?></b></td>
<td>
<button type="button" onclick="ticker_on('<?php echo $url_on;?>')">Play</button>
</td>
<td><button type="button" onclick="ticker_off('<?php echo $url_off;?>')">Off</button></td>
<!--<td><a href="<?php echo 'graphics_update.php?id='.$graphics_id; ?>">Update</a></td>-->
</tr>

<?php
      }
  }
	            
   else
       echo "<b>Sorry,No asset found !!!<b>";
	       
       
        ?>
</table> 
</center>
</body>
</html>
