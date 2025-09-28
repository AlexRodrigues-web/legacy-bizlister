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

	$UPID = $UserRow['user_id'];
	
    $UserSql->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

}
//
	if(!isset($_POST['uEmail']) || strlen($_POST['uEmail'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger">Please let us know your email adress.</div>');
	}
	
	$email_address = $_POST['uEmail'];
	
	if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger">Please enter a valid email address.</div>');
	}
	
	
		
	$Email  			= $mysqli->escape_string($_POST['uEmail']); // Email
	$About              = $mysqli->escape_string($_POST['about']); // About
	$Sex				= $mysqli->escape_string($_POST['sex']); // Gender
	$Country			= $mysqli->escape_string($_POST['country']); // Gender 
	$Birthday			= $mysqli->escape_string($_POST['birthday']); // Birthday 
	
		
// Update info into database table.. do w.e!
		$mysqli->query("UPDATE users SET email='$Email',country='$Country', gender='$Sex', birthday='$Birthday', about='$About' WHERE user_id='$UPID'");
		
		
		die('<div class="alert alert-info">Your profile updated successfully.</div>');
		

   }else{
   		die('<div class="alert alert-danger">There seems to be a problem. Please try again.</div>');
   } 

?>