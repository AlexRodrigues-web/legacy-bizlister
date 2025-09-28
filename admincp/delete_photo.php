<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE img_id='$del'")){
	
$PhotosRow = mysqli_fetch_array($Photos);

$Image = $PhotosRow['image']; 


	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if(!empty($Image)){

unlink("../gallery/$Image");
	
}


$mysqli->query("DELETE FROM galleries WHERE img_id='$del'");


echo '<div class="alert alert-success" role="alert">Photo deleted successfully.</div>';

?>