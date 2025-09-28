<?php

include("db.php");

$del = $mysqli->escape_string($_POST['id']);

if($Biz = $mysqli->query("SELECT * FROM business WHERE biz_id='$del'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$Feat = $BizRow['featured_image'];
	$Uniq = $BizRow['unique_biz'];
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if(!empty($Feat)){
	
unlink("uploads/$Feat");

}

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$Uniq'")){
	
	$CheckImage = $Photos->num_rows;
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
	
	if($CheckImage>0){	
		
		$PhotosDel = $PhotosRow['image'];
		
		unlink("gallery/$PhotosDel");

	}
}

	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}



$mysqli->query("DELETE FROM business WHERE biz_id='$del'");

$mysqli->query("DELETE FROM galleries WHERE uniq='$Uniq'");

$mysqli->query("DELETE FROM reviews WHERE b_id='$del'");


echo '<div class="alert alert-success" role="alert">Business listing successfully deleted.</div>';

?>