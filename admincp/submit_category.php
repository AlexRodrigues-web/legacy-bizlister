<?php

include('../db.php');

if($_POST)
{	

		
	if(!isset($_POST['inputTitle']) || strlen($_POST['inputTitle'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter desired category.</div>');
	}
	
	if(!isset($_POST['inputDescription']) || strlen($_POST['inputDescription'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter description for your new category.</div>');
	}
	
	
	$CategoryTitle			= $mysqli->escape_string($_POST['inputTitle']);
	
	$CategoryDescription	= $mysqli->escape_string($_POST['inputDescription']);
	
	
	$mysqli->query("INSERT INTO categories(category, cat_description) VALUES ('$CategoryTitle', '$CategoryDescription')");
	
	
		die('<div class="alert alert-success" role="alert">New category added successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>