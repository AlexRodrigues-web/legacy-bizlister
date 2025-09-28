<?php
include ("db.php");

if($_POST)
{	
	if(!isset($_POST['inputYourname']) || strlen($_POST['inputYourname'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please let us know your name.</div>');
	}
	if(!isset($_POST['inputEmail']) || strlen($_POST['inputEmail'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please provide us with a valid email address.</div');
	}
	
	$email_address = $_POST['inputEmail'];
	
	if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger" role="alert">Please provide a valid email address.</div>');
	}
	
	if(!isset($_POST['inputSubject']) || strlen($_POST['inputSubject'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">inputSubject cannot be blank.</div>');
	}
	if(!isset($_POST['inputMessage']) || strlen($_POST['inputMessage'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Message cannot be blank.</div>');
	}
if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$SiteSettings->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

$SiteName		 	 = $Settings['site_title'];
$SiteContact	 	 = $Settings['site_email'];
$FromName		 	 = $mysqli->escape_string($_POST['inputYourname']);
$FromEmail		 	 = $mysqli->escape_string($_POST['inputEmail']);
$FrominputSubject	 = $mysqli->escape_string($_POST['inputSubject']);
$FromMessage	 	 = $mysqli->escape_string($_POST['inputMessage']);

require('include/class.phpmailer.php');

$mail             = new PHPMailer(); ;

$mail->AddReplyTo($FromEmail, $FromName);

$mail->SetFrom($FromEmail, $FromName);

$mail->AddReplyTo($FromEmail, $FromName);

$mail->AddAddress($SiteContact, $SiteName);

$mail->Subject = $FrominputSubject;

$mail->MsgHTML($FromMessage);

if(!$mail->Send()) {?>

<div class="alert alert-danger" role="alert">Error sending mail</div>

<?php } else {?>

<div class="alert alert-success" role="alert">Message sent. We will contact you back as soon as possible.</div>

<?php }

}

?>
