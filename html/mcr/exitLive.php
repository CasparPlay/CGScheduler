
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script>
	function exit_live(){
		//var address1 = "exitLive.php"
		window.location.assign("controlLive.php")
		}
    function exit_live_secondary(){
		//var address1 = "exitLive.php"
		window.location.assign("exitParticulerLive.php?server=secondary")
		}
    function exit_live_primary(){
		//var address1 = "exitLive.php"
		window.location.assign("exitParticulerLive.php?server=primary")
		}
</script> 
</head>
<body>
<center>
	<div class="btn-group btn-group-lg">
    <button type="button" class="btn btn-primary" onclick="exit_live()">Exit Live</button>
  </div>
   <div class="btn-group btn-group-lg">
    <button type="button" class="btn btn-primary" onclick="exit_live_primary()">Exit primary Live</button>
  </div>
   <div class="btn-group btn-group-lg">
    <button type="button" class="btn btn-primary" onclick="exit_live_secondary()">Exit Secondary Live</button>
  </div>
</center>

   
</body>
</html>
