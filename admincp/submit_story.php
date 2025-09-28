<?php include('../db.php');

$UploadDirectory	= '../uploads/'; //Upload Directory, ends with slash & make sure folder exist


if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die('<div class="alert alert-danger">Make sure Upload directory exist!</div>');
}

if($_POST)
{	
	
	if(!isset($_POST['inputCategory']) || strlen($_POST['inputCategory'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select a topic</div>');
	}
	
	if(!isset($_POST['inputTitle']) || strlen($_POST['inputTitle'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please add a post title</div>');
	}
	
	if(!isset($_POST['inputDescription']) || strlen($_POST['inputDescription'])<12)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter post description</div>');
	}
	
	if(!isset($_FILES['inputfile']))
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please select a image file</div>');
	}
	
	if($_FILES['inputfile']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputfile']['error']));
	}
	
	if(!isset($_POST['inputSource']) || strlen($_POST['inputSource'])>1)
	{
	$CheckSource = $mysqli->escape_string($_POST['inputSource']);

	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $CheckSource)) {
  		//do nothing
	}else {
  	
	die('<div class="alert alert-danger" role="alert">Please enter full source url.</div>');
	
	}
	}
	
		
	$ImageName			= strtolower($_FILES['inputfile']['name']); //uploaded file name
	$PostTitle			= $mysqli->escape_string($_POST['inputTitle']); // file title
	$PostDescription    = $mysqli->escape_string($_POST['inputDescription']); // file title
	$CatId			    = $mysqli->escape_string($_POST['inputCategory']); // file title
	$ImageExt			= substr($ImageName, strrpos($ImageName, '.')); //file extension
	$FileType			= $_FILES['inputfile']['type']; //file type
	$FileSize			= $_FILES['inputfile']["size"]; //file size
	$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
	$Source		        = $mysqli->escape_string($_POST['inputSource']);
	$AddedOn			= date("F j, Y");
	$Type               ="1";
	$Active 			="1"; 
	
	switch(strtolower($FileType))
	{
		//allowed file types
		case 'image/png': //png file
		case 'image/gif': //gif file
		case 'image/jpeg': //jpeg file
			break;
		default:
			die('<div class="alert alert-danger" role="alert">Unsupported File! Please upload JPEG, Gif or PNG photos.</div>'); //output error
	}
	
	function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	
  
	//File Title will be used as new File name
	$NewImageName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($PostTitle));
	$NewImageName = clean($NewImageName);
	$NewImageName = $NewImageName.'_'.$RandNumber.$ImageExt;
   //Rename and save uploded file to destination folder.
   if(move_uploaded_file($_FILES['inputfile']["tmp_name"], $UploadDirectory . $NewImageName ))
   {
				
// Insert info into database table.. do w.e!
		$mysqli->query("INSERT INTO posts(title, desciption, catid, image, source, date, type, active) VALUES ('$PostTitle','$PostDescription','$CatId','$NewImageName','$Source','$AddedOn','$Type','$Active')");

		die('<div class="alert alert-success" role="alert">Your post submitted successfully.</div>');

		
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   }
}

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
?>