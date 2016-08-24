<html>
	<head>
		<title>Graphics Control</title>
		<link rel="stylesheet" type="text/css" href="styleng.css">
		
	</head>
	<body>
		<center>
			<form action = "json_convert.php" method = "post" name = "myForm">
				<mark>Graphics Control</mark>
				<div style="height:50px;width:90px;border:0px solid red;float:right;margin-left:70%;margin-top:-10px;display:block"><a href = "profile.php"><img src="imgs/home.jpg" alt=""  height=100% width=100%></img></a></div>
				<div class = "outer_div">
					<div class = "inner_div2">
						<input type = "text" placeholder = "headline" name = "headline1" id = "headline1" style="width: 330px; height : 40px;" >
					</div>
					
					<div class = "inner_div3">
						<textarea rows="10" cols="40" placeholder = "Message" name = "message1" id = "message1" style="width: 330px; height : 160px;">
						 
						</textarea>
					</div>
					
					<div class = "inner_div4">
						<input type = "text" placeholder = "headline" name = "headline2" id = "headline2" style="width: 330px; height : 40px;" >
					</div>
					
					<div class = "inner_div5">
						<textarea rows="10" cols="40" placeholder = "Message" name = "message2" id = "message2" style="width: 330px; height : 160px;">
						 
						</textarea>
					</div>
					
					
					<div class = "inner_div6">
						<input type = "text" placeholder = "headline" name = "headline3" id = "headline3" style="width: 330px; height : 40px;" >
					</div>
					
					<div class = "inner_div7">
						<textarea rows="10" cols="40" placeholder = "Message" name = "message3" id = "message3" style="width: 330px; height : 160px;">
						 
						</textarea>
					</div>
					
					<div class = "inner_div8">
						<input type = "text" placeholder = "headline" name = "headline4" id = "headline4" style="width: 330px; height : 40px;" >
					</div>
					
					<div class = "inner_div9">
						<textarea rows="10" cols="40" placeholder = "Message" name = "message4" id = "message4" style="width: 330px; height : 160px;">
						 
						</textarea>
					</div>
					<div class = "inner_div10">
						<label><input type="checkbox" name="date_time" value="<?php echo date('Y/m/d H:i:s') ?>" checked >Now</label>
					</div>
					
					<div class = "inner_div11">
						<input type = "submit" value = "Upload" >
					</div>
				</div>
			</form>
		</center>
	</body>
</html>

