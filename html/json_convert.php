<?php
   /*class Emp {
      public $headline1 = "";
      public $message1  = "";
	  
	  public $headline2 = "";
      public $message2  = "";
	  
	  public $headline3 = "";
      public $message3  = "";
	  
	  public $headline4 = "";
      public $message4  = "";
   }
	$headline_xml1 = $_POST[headline1];
	$message_xml1 = $_POST[message1];
	
	$headline_xml2 = $_POST[headline2];
	$message_xml2 = $_POST[message2];
	
	$headline_xml3 = $_POST[headline3];
	$message_xml3 = $_POST[message3];
	
	$headline_xml4 = $_POST[headline4];
	$message_xml4 = $_POST[message4];
	
	//$date_time = $_POST[date_time];
   $e = new Emp();
   $e->headline1 = $headline_xml1;
   $e->message1  = trim($message_xml1," ");
   
   $e->headline2 = $headline_xml2;
   $e->message2  = trim($message_xml2," ");
   
   $e->headline3 = $headline_xml3;
   $e->message3  = trim($message_xml3," ");
   
   $e->headline4 = $headline_xml4;
   $e->message4  = trim($message_xml4," ");
   $date_time = date('Y_m_d_H_i_s');
   
   
   
   file_put_contents('news.json', json_encode($e));
   
   echo "json file created";*/
   
   
   $news = array(
    array(
        "headline" => "$_POST[headline1]",
        "message" => "$_POST[message1]"
    ),
    array(
        "headline" => "$_POST[headline2]",
        "message" => "$_POST[message2]"
    ),
    array(
        "headline" => "$_POST[headline3]",
        "message" => "$_POST[message3]"
    ),
	array(
        "headline" => "$_POST[headline4]",
        "message" => "$_POST[message4]"
    )
);
file_put_contents('news.json', json_encode($news));

   
?>