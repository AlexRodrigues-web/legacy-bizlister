<?php

include('../db.php');

if($_POST)
{	

	
	if(!isset($_POST['inputCategory']) || strlen($_POST['inputCategory'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please select a parent category.</div>');
	}
		
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
	
	
	$ParentCategory			= $mysqli->escape_string($_POST['inputCategory']);	
	$CategoryTitle			= $mysqli->escape_string($_POST['inputTitle']);	
	$CategoryDescription	= $mysqli->escape_string($_POST['inputDescription']);
	
	
	$mysqli->query("INSERT INTO categories(category, cat_description, parent_id) VALUES ('$CategoryTitle', '$CategoryDescription', '$ParentCategory')");
	
	
		die('<div class="alert alert-success" role="alert">New subcategory added successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>