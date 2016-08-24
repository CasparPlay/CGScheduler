<?php
session_start();//starting session
$error=''; //variable to store error message
 if (isset($_POST['submit'])) {
	$loginType=$_POST['type'];
	if($loginType == 'select'){
                                    $error = "Please select Login Type"; 
         }
	 else{

                            if (empty($_POST['usrname']) || empty($_POST['pass'])) {
								$error = "Username or Password is invalid"; 
                            } 
			    else 
			    {   
                                                              
				// Define $username and $password 
				$username=$_POST['usrname']; 
				$password=$_POST['pass']; 
                                //Establishing Connection with Server by passing server_name, user_id and password as a parameter 
                                $connection = mysql_connect("localhost", "root", "password");
								
				//Selecting Database
				$db = mysql_select_db("project", $connection);
                                $password=md5($password);
				
				//SQL query to fetch information of registerd users and finds user match.
                                $query = mysql_query("select * from registration where password='$password' AND user_id='$username'", $connection);
                                $rows = mysql_num_rows($query);
								
								//######################
								$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
								"Attempt: ".($result[0]['success']=='1'?'Success':'Failed').PHP_EOL.
								"Type: ".$loginType.PHP_EOL.
								"User: ".$username.PHP_EOL.
								"Pass: ".$password.PHP_EOL.
								"-------------------------".PHP_EOL;
								//Save string to log, use FILE_APPEND to append.
								file_put_contents('log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
								

                                if ($rows == 1) {
                                    $_SESSION['login_user']=$username;//Initializing Session
				    $_SESSION['login_type']=$loginType;//Initializing Session
				    if($loginType == 'scheduler')
                                    	header("location: profile.php");//Redirecting to other page
				    if($loginType == 'news')
                                    	header("location:/news_graphics/news_graphics_menu.php");//Redirecting to other page
                    if($loginType == 'graphics')
                                    	header("location:/Graphics/graphics_settings.php");//Redirecting to other page               
                                } else {
                                    $error = "Username or Password is invalid"; 
                                }
								
				//Closing Connection
				mysql_close($connection);                           
                            }
                        }
		}
?>
