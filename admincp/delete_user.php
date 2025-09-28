<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($Biz = $mysqli->query("SELECT * FROM business WHERE biz_user='$del'")){
	
	while($BizRow = mysqli_fetch_array($Biz)){
	
	$Feat = $BizRow['featured_image'];
	$Uniq = $BizRow['unique_biz'];
	$BizId = $BizRow['biz_id'];
	
	$mysqli->query("DELETE FROM hours WHERE unique_hours='$Uniq'");
	$mysqli->query("DELETE FROM reviews WHERE b_id='$BizId'");
	
	unlink("../uploads/$Feat");

}

	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

//Delete Business

$mysqli->query("DELETE FROM articles WHERE biz_user='$del'");

//Delete Photos

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uid='$del'")){
	
	$CheckImage = $Photos->num_rows;
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
	
	if($CheckImage>0){	
		
		$PhotosDel = $PhotosRow['image'];
		
		unlink("../gallery/$PhotosDel");

	}
}

	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

//Delete Reviews

$mysqli->query("DELETE FROM reviews WHERE u_id='$del'");

//Delete User

$mysqli->query("DELETE FROM users WHERE user_id='$del'");


echo '<div class="alert alert-success" role="alert">User successfully deleted</div>';

?>