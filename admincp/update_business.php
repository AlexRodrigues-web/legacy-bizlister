<?php

include('../db.php');

$id = $mysqli->escape_string($_GET['id']);

if($Biz = $mysqli->query("SELECT * FROM business WHERE biz_id='$id'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$FeatImage = stripslashes($BizRow['featured_image']);
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

$UploadDirectory	= '../uploads/';
 

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
	
	if(isset($_FILES['inputImage']))
	{
		
	if($_FILES['inputImage']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputImage']['error']));
	}
	
	$FileName			= strtolower($_FILES['inputImage']['name']); 
	$ImageExt			= substr($FileName, strrpos($FileName, '.')); 
	$FileType			= $_FILES['inputImage']['type']; 
	$FileSize			= $_FILES['inputImage']["size"]; 
	$RandNumber   		= rand(0, 9999999999);
	
	
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
	
  
	$NewFileName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($BizName));
	$NewFileName = clean($NewFileName);
	$NewFileName = $NewFileName.'_'.$RandNumber.$ImageExt;


   if(move_uploaded_file($_FILES['inputImage']["tmp_name"], $UploadDirectory . $NewFileName ))
   {
	
	   
	unlink("../uploads/".$FeatImage);


$mysqli->query("UPDATE business SET business_name='$BizName', description='$Description',address_1='$Addy1', address_2='$Addy2', city='$City', phone='$Phone', website='$SiteURL', email='$Email', menu='$Menu', featured_image='$NewFileName', facebook='$Facebook', twitter='$Twitter', pinterest= '$Pinterest', cid='$Category', sid='$Sub', tags='$Tags' WHERE biz_id=$id");


}



}else{
	   
$mysqli->query("UPDATE business SET business_name='$BizName', description='$Description', address_1='$Addy1', address_2='$Addy2', city='$City', phone='$Phone', website='$SiteURL', email='$Email', menu='$Menu', facebook='$Facebook', twitter='$Twitter', pinterest='$Pinterest', cid='$Category', sid='$Sub', tags='$Tags' WHERE biz_id=$id");  	   


	   
 }


	die('<div class="alert alert-success" role="alert">Basic details updated successfully.</div>');

		
   }else{
	   
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   }


if(!isset($_FILES['inputImage']))
	{
//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Missing a temporary folder'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Failed to write file to disk'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'File upload stopped by extension'; 
        default: 
            return 'Unknown upload error'; 
    } 
} 
	}
?>