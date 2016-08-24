
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
	function ticker_on(){
		//var address1 = "exitLive.php"
		window.location.assign("ticker_control.php?on=true")
		}
function ticker_off(){
		//var address1 = "exitLive.php"
		window.location.assign("ticker_control.php?on=false")
		}
</script> 
</head>
<body>
	<div class="btn-group btn-group-lg">
    <button type="button" class="btn btn-primary" onclick="ticker_on()">Play Iftar Card</button>
  </div>
<div class="btn-group btn-group-lg">
    <button type="button" class="btn btn-primary" onclick="ticker_off()">Off Iftar Card</button>
  </div>

   
</body>
</html>
