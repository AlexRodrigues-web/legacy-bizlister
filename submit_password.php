<?php
session_start();

include('db.php');


if($_POST)
{	
	
	if(!isset($_SESSION['username'])){
	//Do Nothing
}else{
	
$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$UPW = $UserRow['password'];
	
	$UPID = $UserRow['user_id'];
	
    $UserSql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

}
//
	$ExtPassword = $_POST['nPassword'];
	$EnexPassword = md5($ExtPassword);
	
	if ($EnexPassword !== $UPW)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Existing password doesn&acute;t match.</div>');
	}
	
	if(!isset($_POST['uPassword']) || strlen($_POST['uPassword'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please provide a password.</div>');
	}
	
	if(!isset($_POST['uPassword']) || strlen($_POST['uPassword'])<6)
	{
		//required variables are empty
		die('<div class="alert alert-danger">New password must be least 6 characters long.</div>');
	}
		if(!isset($_POST['cPassword']) || strlen($_POST['cPassword'])< 1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please enter the same password as above.</div>');
	}
	
	if ($_POST['uPassword']!== $_POST['cPassword'])
 	{
		//required variables are empty
     	die('<div class="alert alert-danger">Conform Password did not match! Try again.</div>');
 	
	}
	
		
	$Password  			= $mysqli->escape_string($_POST['uPassword']); // Password
	$EnPassword         = md5($Password); // Encript Password
		
		
// Update info into database table.. do w.e!
		$mysqli->query("UPDATE users SET password='$EnPassword' WHERE user_id='$UPID'");
		
		
		die('<div class="alert alert-info">Your password updated successfully.</div>');
		

   }else{
   		die('<div class="alert alert-danger">There seems to be a problem. Please try again.</div>');
   } 

?>