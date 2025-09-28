<?php
session_start();
ob_start();

include('db.php');

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$SiteLink = $Settings['site_link'];
	
	$SiteSettings->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if(!isset($_SESSION['username'])){

if($_POST)
{	
	
	$username =	$mysqli->escape_string($_POST['inputUsername']); 
	$password = $mysqli->escape_string($_POST['inputPassword']);
	$gpassword=md5($password);
	
	if($UserCheck = $mysqli->query("SELECT * FROM users WHERE username ='$username' and password ='$gpassword'")){

   	$VdUser = mysqli_fetch_array($UserCheck);
	
	$Count= mysqli_num_rows($UserCheck);

   	$UserCheck->close();
   
	}else{
   
     printf("There Seems to be an issue");

	}
	
	if ($Count == 1)
	{
		//required variables are empty
		$_SESSION["username"] = $username;
		//header("location:index.html");
?>
<script type="text/javascript">
function leave() {
  window.location = "http://<?php echo $SiteLink;?>";
}
setTimeout("leave()", 1000);
</script>
<?php

	die('<div class="alert alert-success" role="alert">Login You on. Please Wait...</div>');
	
	
   }else{
	   
	   die('<div class="alert alert-danger" role="alert">Wrong username or password.</div>');
   		
   } 
}
}
ob_end_flush();
 
?>