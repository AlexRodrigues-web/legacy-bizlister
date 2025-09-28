<?php

include('db.php');

if($_POST)
{	
	
	
	$id	    		= $mysqli->escape_string($_POST['id']);
	$Lat			= $mysqli->escape_string($_POST['lat']);
	$Long    		= $mysqli->escape_string($_POST['lng']);
	
	   
$mysqli->query("UPDATE business SET latitude='$Lat', longitude='$Long' WHERE biz_id=$id");  	   




	die('<div class="alert alert-success" role="alert">Map data updated successfully.</div>');

		
   }else{
	   
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   }


?>