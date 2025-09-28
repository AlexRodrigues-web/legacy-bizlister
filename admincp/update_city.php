<?php
session_start();

include('../db.php');

if($_POST)
{	
	
	$id = $mysqli->escape_string($_GET['id']);
	
		
	if(!isset($_POST['inputCity']) || strlen($_POST['inputCity'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter city name.</div>');
	}
	
		
	$City			= $mysqli->escape_string($_POST['inputCity']);
	
	
	
	$mysqli->query("UPDATE city SET city='$City' WHERE city_id='$id'");
	
	
		die('<div class="alert alert-success" role="alert">City updated successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>