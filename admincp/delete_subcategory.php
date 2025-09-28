<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

//Delete Listing Images

if($ImageInfo = $mysqli->query("SELECT * FROM business WHERE cid='$del'")){

    $CountImages = $ImageInfo->num_rows;
	
	while($GetInfo = mysqli_fetch_array($ImageInfo)){
	
	$Image = $GetInfo['featured_image'];
	
	$uniqid = $GetInfo['unique_biz'];
	
if ($CountImages>0){

unlink("../uploads/$Image");

}

}
	
$ImageInfo->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

//Delete Photos

if(!empty($uniqid)){

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$uniqid'")){
	
	$CountPhotos = $Photos->num_rows;
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
		
		$GetPhotos = $PhotosRow['image'];
		

if ($CountPhotos >0){

unlink("../gallery/$GetPhotos ");

}

}

	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

//Delete Photos

$mysqli->query("DELETE FROM galleries WHERE uniq='$uniqid'");

//Delete Hours

$mysqli->query("DELETE FROM hours WHERE unique_hours='$uniqid'");

}

//delete reviews

$mysqli->query("DELETE FROM reviews WHERE b_id='$del'");

//Delete Ligtings

$mysqli->query("DELETE FROM business WHERE cid='$del'");


//Delete Catagories

$mysqli->query("DELETE FROM categories WHERE cat_id='$del'");

//Delete Bookmarks

$mysqli->query("DELETE FROM bookmarks WHERE bizid='$del'");

echo '<div class="alert alert-success" role="alert">Category successfully deleted</div>';

?>