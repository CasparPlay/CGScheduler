<?php
session_start();
if(session_destroy()) //Destroying all sessions
{
header("Location: index.php"); //Redirecting to home page
}
?>
