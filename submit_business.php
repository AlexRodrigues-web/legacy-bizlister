<?php
session_start();

include('db.php');

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $settings = mysqli_fetch_array($squ);
	
	$Active = $settings['active'];

    $squ->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");;
}


//Get user info

$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['user_id'];
	
    $UserSql->close();
	
}else{
     
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
	 
}

$UploadDirectory	= 'uploads/';
 

if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die("Make sure Upload directory exist!");
}

if($_POST)
{		
	if(!isset($_POST['inputBizname']) || strlen($_POST['inputBizname'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter your business name.</div>');
	}
	
	if(!isset($_POST['inputDescription']) || strlen($_POST['inputDescription'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter small description.</div>');
	}
	
	if(!isset($_POST['inputLineOne']) || strlen($_POST['inputLineOne'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Address line 1 cannot be blank.</div>');
	}
	
	if(!isset($_POST['inputCity']) || strlen($_POST['inputCity'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select the city your business is located.</div>');
	}
	
	if(!isset($_POST['inputWeb']) || strlen($_POST['inputWeb'])>1)
	{
	
	$CheckLink = $mysqli->escape_string($_POST['inputWeb']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckLink)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full website link.</div>');
	
	}
	}
	
	if(!isset($_POST['inputEmail']) || strlen($_POST['inputEmail'])>1)
	{
	$ValidateEmail = $_POST['inputEmail'];
	
	if (filter_var($ValidateEmail, FILTER_VALIDATE_EMAIL)) {
  	// The email address is valid
	} else {
  		die('<div class="alert alert-danger">Please enter a valid email address.</div>');
	}
}
	
	
	if(!isset($_POST['inputMenu']) || strlen($_POST['inputMenu'])>1)
	{
	
	$MenuLink = $mysqli->escape_string($_POST['inputMenu']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $MenuLink)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full link to your menu.</div>');
	
	}
	}
	
	if(!isset($_FILES['inputImage']))
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please add a image.</div>');
	}
	
	if($_FILES['inputImage']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputImage']['error']));
	}
	
	if(!isset($_POST['inputCategory']) || strlen($_POST['inputCategory'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select your business category.</div>');
	}
	
	if(!isset($_POST['inputFacebook']) || strlen($_POST['inputFacebook'])>1)
	{
	
	$FacebookLink = $mysqli->escape_string($_POST['inputFacebook']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $FacebookLink)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full link to your Facebook.</div>');
	
	}
	}
	
	if(!isset($_POST['inputTwitter']) || strlen($_POST['inputTwitter'])>1)
	{
	
	$TwitterLink = $mysqli->escape_string($_POST['inputTwitter']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $TwitterLink)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full link to your Twitter.</div>');
	
	}
	}
	
	if(!isset($_POST['inputPinterest']) || strlen($_POST['inputPinterest'])>1)
	{
	
	$PinterestLink = $mysqli->escape_string($_POST['inputPinterest']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $PinterestLink)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full link to your Pinterest.</div>');
	
	}
	}
	

	$FileName			= strtolower($_FILES['inputImage']['name']); 
	$ImageExt			= substr($FileName, strrpos($FileName, '.')); 
	$FileType			= $_FILES['inputImage']['type']; 
	$FileSize			= $_FILES['inputImage']["size"]; 
	$RandNumber   		= rand(0, 9999999999); 
	$Date		        = date("F j, Y");
	
	$BizName			= $mysqli->escape_string($_POST['inputBizname']);
	$Description		= $mysqli->escape_string($_POST['inputDescription']);
	$Addy1		        = $mysqli->escape_string($_POST['inputLineOne']); 
	$Addy2	            = $mysqli->escape_string($_POST['inputLineTwo']);
	$City	            = $mysqli->escape_string($_POST['inputCity']);
	$Phone              = $mysqli->escape_string($_POST['inputPhone']);
	$Website            = $mysqli->escape_string($_POST['inputWeb']);
	$Email              = $mysqli->escape_string($_POST['inputEmail']);
	$Menu               = $mysqli->escape_string($_POST['inputMenu']);
	$Category           = $mysqli->escape_string($_POST['inputCategory']);
	$Sub	            = $mysqli->escape_string($_POST['inputSubcategory']);
	
	$Facebook           = $mysqli->escape_string($_POST['inputFacebook']);
	$Twitter            = $mysqli->escape_string($_POST['inputTwitter']);
	$Pinterest          = $mysqli->escape_string($_POST['inputPinterest']);
	
	$Tags               = $mysqli->escape_string($_POST['inputTags']);
	$Uniqid 			= $mysqli->escape_string($_GET['id']);
	
	
	if(!isset($_POST['inputWeb']) || strlen($_POST['inputWeb'])>1)
	{
	
	if(strpos($Website, 'http://') !== 0) {
 	 $SiteURL = 'http://' . $Website;
	}else if(strpos($Website, 'https://') !== 0) {
 	 $SiteURL = 'http://' . $Website; 
	} else {
 	 $SiteURL =  $Website;
	}	
	}else{
	
	$SiteURL =  $Website;
		
	}
		switch(strtolower($FileType))
	{
		//allowed file types
		case 'image/jpeg': //jpeg file
			break;
		default:
			die('<div class="alert alert-danger" role="alert">Unsupported Image File. Please upload JPEG files</div>'); //output error
	}
	
	function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
  
	//Image File Title will be used as new File name
	$NewFileName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($BizName));
	$NewFileName = clean($NewFileName);
	$NewFileName = $NewFileName.'_'.$RandNumber.$ImageExt;

 //Rename and save uploded image file to destination folder.
   if(move_uploaded_file($_FILES['inputImage']["tmp_name"], $UploadDirectory . $NewFileName ))
   {
	
		
// Insert info into database table.. do w.e!
$mysqli->query("INSERT INTO business(business_name, description, address_1, address_2, city, phone, website, email, menu, featured_image, facebook, twitter, pinterest, cid, sid, tags, date, active, avg, unique_biz, biz_user) VALUES ('$BizName','$Description', '$Addy1','$Addy2','$City','$Phone','$SiteURL','$Email','$Menu','$NewFileName','$Facebook','$Twitter','$Pinterest','$Category','$Sub','$Tags','$Date','$Active','0','$Uniqid','$Uid')");

?>

<script>
$('#SubmitForm').delay(1000).resetForm(1000);
$('#SubmitForm').delay(1000).slideUp(1000);
$(document).ready(function()
{
$("#SubmitHours #submitButton").prop('disabled', false);
$("#imageform #submitButton").prop('disabled', false);
});
</script>

<?php		
		die('<div class="alert alert-success" role="alert">Basic details added successfully.</div>');
		
   
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   } 
}

function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return '<div class="alert alert-danger" role="alert">Image file size is too big. Please try a smaller image</div>'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return '<div class="alert alert-danger" role="alert">Image file size is too big. Please try a smaller image</div>'; 
        case UPLOAD_ERR_PARTIAL: 
            return '<div class="alert alert-danger" role="alert">Product listing submitted but product image did not uploaded properly.</div>'; 
        case UPLOAD_ERR_NO_FILE: 
            return '<div class="alert alert-danger" role="alert">Product listing submitted but product image did not uploaded.</div>'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        case UPLOAD_ERR_EXTENSION: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
        default: 
            return '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>'; 
    }  
} 
?>