<?php



$id = $_GET["id"];


?>
<!DOCTYPE HTML>
<html>
<head>
<style>
body
  {
	  background-color : lightblue;
  }
</style>
</head>
<body>

<?php
           include 'db_connection_project.php';
		   $sql = "select * from graphics where id=".$id;
	   

              
   if($query_run=  mysqli_query($con,$sql)){
      while($query_row=  mysqli_fetch_assoc($query_run)){
          //$id_update=$query_row['id'];
          $graphics_id = $query_row['id'];
          $graphics_name = $query_row['name'];
          $graphics_layer = $query_row['layer'];
          $graphics_loop = $query_row['loop'];
          $graphics_meta = $query_row['meta'];
          }
}
    mysqli_close($con);
          ?>



<center>
<h1> Update Graphics Settings </h1>
<hr>
<form method = "POST" action="">
<table>
<tr>
<td align = "left" >input graphics name : </td>
<td align = "right"><input type="text" name = "name" value = "<?php echo $graphics_name; ?>"> </td>
</tr>
<tr>
<td align = "left">loop : </td>
<?php
if($graphics_loop > 0)
echo '<td align = "right"><input type = "checkbox" name = "loop" checked></td>';
else
echo '<td align = "right"><input type = "checkbox" name = "loop"></td>';
?>
<tr>
<tr>
<td align = "left">layer : </td>
<td align = "right"><input type= "number" name = "layer" value = "<?php echo $graphics_layer; ?>" min="20"> </td>
<tr>

<tr>
<td align = "left">Meta information : </td>
<td align = "right"><textarea name = "meta" rows = "5" cols="40"></textarea> </td>
<tr>
</table>
<br><br>

<input type="submit" name = "update" value = "Update"> 


</form>
<br><br>
<a href = "graphics_settings.php"><-back to graphics settings page</a>
</center>
<?php

if(isset($_POST['update']))
{
include 'db_connection_project.php';
if(isset($_POST['loop']))
$loop = 1;
else
$loop = 0;
$name = $_POST['name'];
$layer = $_POST['layer'];
$meta = $_POST['meta'];
echo $name;
echo $layer;
echo $id;
echo $loop;
$sql = "update graphics set `name` = '$name',`loop`= '$loop',layer='$layer',`meta`='$meta' where `id`= '$id'";
if(mysqli_query($con,$sql))
echo '<br> <b>update successfully</b>';
else
echo '<br> <b>update failed</b>';
mysqli_close($con);

}
?>


</body>
</html>
