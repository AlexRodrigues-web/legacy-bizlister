<?php
session_start();

include('../db.php');


if($_POST)
{	
	

if($GetAdmin = $mysqli->query("SELECT * FROM administrator WHERE id=1")){

    $AdminInfo = mysqli_fetch_array($GetAdmin);

	$AdminPassword = $AdminInfo['password'];
	
    $GetAdmin->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


	if(!isset($_POST['inputUsername']) || strlen($_POST['inputUsername'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Administrator username cannot be blank.</div>');
	}
	
	$CurrentPassword = $_POST['inputCurrentPassword'];
	
	$EncryptCurrentPassword = md5($CurrentPassword);
	
	if ($EncryptCurrentPassword !== $AdminPassword)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Existing password doesn&acute;t match.</div>');
	}
	
	if(!isset($_POST['inputPassword']) || strlen($_POST['inputPassword'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please provide a password.</div>');
	}
	
	if(!isset($_POST['inputPassword']) || strlen($_POST['inputPassword'])<5)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">New password must be least 6 characters long.</div>');
	}
		if(!isset($_POST['inputConfirmPassword']) || strlen($_POST['inputConfirmPassword'])< 1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter the same password as above.</div>');
	}
	
	if ($_POST['inputPassword']!== $_POST['inputConfirmPassword'])
 	{
		//required variables are empty
     	die('<div class="alert alert-danger" role="alert">Conform Password did not match! Try again.</div>');
 	
	}
	
	
	$Username					= $mysqli->escape_string($_POST['inputUsername']); // Password	
	$Password  					= $mysqli->escape_string($_POST['inputPassword']); // Password
	$EncryptNewPassword         = md5($Password); // Encript Password
		
		

		$mysqli->query("UPDATE administrator SET username='$Username', password='$EncryptNewPassword' WHERE id=1");
		
		
		die('<div class="alert alert-success" role="alert">Your administrator credentials updated successfully.</div>');
		

   }else{
	   
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. Please try again.</div>');
   }
    


?>