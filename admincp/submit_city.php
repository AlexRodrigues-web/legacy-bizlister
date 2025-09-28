<?php

include('../db.php');

if($_POST)
{	

		
	if(!isset($_POST['inputCity']) || strlen($_POST['inputCity'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter city name.</div>');
	}
	
		
	$City			= $mysqli->escape_string($_POST['inputCity']);
	
	
	$mysqli->query("INSERT INTO city(city) VALUES ('$City')");
	
	
		die('<div class="alert alert-success" role="alert">New city added successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>