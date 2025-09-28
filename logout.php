<?php  
session_start();
session_destroy();

include('db.php');

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $settings = mysqli_fetch_array($squ);
	
	$SiteUrl = "http://".$settings['site_link'];
	
	$squ->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}

header("Location:$SiteUrl");
?>