
<!DOCTYPE HTML>
<html>
<head>
<style>
body
  {
	  background-color : lightblue;
  }
table#table_style 
	{ 
		border-collapse: collapse; 
		font-family: Futura, Arial, sans-serif; 
		border: 1px solid #777; 
        width : 80%;
				
	}
 caption#table_style
	{ 
		font-size: larger; 
		margin: 1em auto; 
	}
    td#table_style
	{ 
		padding: .65em; 
	}
th#table_style
	{ 
        padding: .65em;
		background: black; 
		color: #fff; 
		border: 1px solid #000; 
	}
  tr:nth-child(odd)#table_style
	{ 
		background: #D6F1A3; 
	}
    tr:hover#table_style
	{ 
		background: #aaa; 
	}
    td#table_style 
	{ 
		border-right: 1px solid #777;
	}
</style>
</head>
<body>
<center>
<h1>Graphics Control Settings</h1>



<form method = "POST" action="">
<table>
<tr>
<td align = "left" >input graphics name : </td>
<td align = "right"><input type="text" name = "name"> </td>
</tr>
<tr>
<td align = "left">loop : </td>
<td align = "right"><input type = "checkbox" name = "loop" value = "1"> </td>
<tr>
<tr>
<td align = "left">layer : </td>
<td align = "right"><input type= "number" name = "layer" min="20"> </td>
<tr>

<tr>
<td align = "left">Meta information : </td>
<td align = "right"><textarea name = "meta" rows = "5" cols="40"></textarea> </td>
<tr>
</table>
<br><br>

<input type="submit" name = "submit" value = "Submit"> 


</form>
<ul>
<li><a href="http://10.3.10.190/Graphics/file.php" target="_blank">Upload To Primary Playout server</a></li>
<li><a href="http://10.3.10.191/Graphics/file.php">Upload To Secondary Playout server</a></li>
</ul>
<hr><br>

<?php
include 'db_connection_project.php';

if(isset($_POST['submit']))
{

$loop = 0;
$name = $_POST['name'];
if($_POST['loop'] > 0)
$loop = $_POST['loop'];
$layer = $_POST['layer'];
$meta = $_POST['meta'];
$sql = "INSERT INTO graphics (`name`,`layer`,`loop`,`meta`) VALUES ('$name','$layer','$loop','$meta')";
				   
mysqli_query( $con, $sql );
 
}
?>


<table id="table_style">
				<tr id="table_style">
				<th id="table_style">graphics Name</th>
				<th id="table_style">Layer</th>
                <th id="table_style">loop</th>
                <th id="table_style">Meta</th>
                <th id="table_style">Update</th>
				</tr>
	
      <?php
           
		   $sql = "select * from graphics";
	   

              
   if($query_run=  mysqli_query($con,$sql)){
      while($query_row=  mysqli_fetch_assoc($query_run)){
          //$id_update=$query_row['id'];
          $graphics_id = $query_row['id'];
          $graphics_name = $query_row['name'];
          $graphics_layer = $query_row['layer'];
          $graphics_loop = $query_row['loop'];
          $graphics_meta = $query_row['meta'];
         
          ?>
		  
<tr id="table_style">

<td id="table_style"><b><?php echo $graphics_name;?></b></td>
<td id="table_style"><b><?php echo $graphics_layer;?></b></td>
<td id="table_style"><b><?php echo $graphics_loop;?></b></td>
<td id="table_style"><b><?php echo $graphics_meta;?></b></td>
<td id="table_style"><a href="<?php echo 'graphics_update.php?id='.$graphics_id; ?>">Update</a></td>
</tr>

<?php
      }
  }
	            
   else
       echo "<b>Sorry,No asset found !!!<b>";
	       
   mysqli_close($con);    
        ?>
</table> 

</center>
</body>
</html>
