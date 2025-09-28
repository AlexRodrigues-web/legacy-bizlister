<?php
session_start();

include('../db.php');

if($_POST)
{	

	$ad1			= $mysqli->escape_string($_POST['inputAd1']);
	$ad2			= $mysqli->escape_string($_POST['inputAd2']);
	$ad3			= $mysqli->escape_string($_POST['inputAd3']);
	
	
	$mysqli->query("UPDATE advertisements SET ad1='$ad1',ad2='$ad2',ad3='$ad3' WHERE id=1");
	
	
		die('<div class="alert alert-success" role="alert">Site advertisements updated successfully.</div>');

		
   }else{
   	
		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
  
}


?>