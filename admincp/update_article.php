<?php 

include('../db.php');

$id = $mysqli->escape_string($_GET['id']);

//Get Photo Info

if($Post = $mysqli->query("SELECT * FROM articles WHERE art_id='$id'")){

    $PostRow = mysqli_fetch_array($Post);
	
	$PostFile = $PostRow['image'];
	
    $Post->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

$UploadDirectory	= '../uploads/'; //Upload Directory, ends with slash & make sure folder exist


if (!@file_exists($UploadDirectory)) {
	//destination folder does not exist
	die('<div class="alert alert-danger">Make sure Upload directory exist!</div>');
}

if($_POST)
{	
	
	if(!isset($_POST['inputTopic']) || strlen($_POST['inputTopic'])<1)
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

		
	$PostTitle			= $mysqli->escape_string($_POST['inputTitle']); // file title
	$PostDescription    = $mysqli->escape_string($_POST['inputDescription']); // file title
	$TopicId		    = $mysqli->escape_string($_POST['inputTopic']); // file title
	$SubTopicId		    = $mysqli->escape_string($_POST['inputSubtopic']); // file title
	$AddedOn			= date("F j, Y");
	
	
	if(isset($_FILES['inputfile']))
	{
		
	
	if($_FILES['inputfile']['error'])
	{
		//File upload error encountered
		die(upload_errors($_FILES['inputfile']['error']));
	}
	
		
	$ImageName			= strtolower($_FILES['inputfile']['name']); //uploaded file name
	$ImageExt			= substr($ImageName, strrpos($ImageName, '.')); //file extension
	$FileType			= $_FILES['inputfile']['type']; //file type
	$FileSize			= $_FILES['inputfile']["size"]; //file size
	$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
	
		
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
		

$mysqli->query("UPDATE articles SET title='$PostTitle', description='$PostDescription', topic_id='$TopicId', sub_topic_id='$SubTopicId', image='$NewImageName' WHERE art_id='$id'");	
		
	
	unlink($UploadDirectory . $PostFile);
	
	die('<div class="alert alert-success" role="alert">Your post submitted successfully.</div>');

		
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   
   }
   
   }else{
	   
	$mysqli->query("UPDATE articles SET title='$PostTitle', description='$PostDescription', topic_id='$TopicId', sub_topic_id='$SubTopicId' WHERE art_id='$id'");	
	
	die('<div class="alert alert-success" role="alert">Your post submitted successfully.</div>');   
	   
   }


}

//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file size exceeded.'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file size exceeded.'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded.'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded.'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'There seems to be a problem. please try again.'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'There seems to be a problem. please try again.'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'Unsupported File! Please upload JPEG, Gif or PNG image.'; 
        default: 
            return 'Unknown upload error'; 
    } 
}
?>