<?php
session_start();

include('../db.php');

if($_POST)
{	

	$Page			= $mysqli->escape_string($_POST['inputPage']);
	
	
	$mysqli->query("UPDATE pages SET page='$Page' WHERE id=3");
	
	
		die('<div class="alert alert-success" role="alert">Terms of use page updated successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>