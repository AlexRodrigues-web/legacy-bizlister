<?php

include("../db.php");

$id = $mysqli->escape_string($_POST['id']);

if($Reviws = $mysqli->query("SELECT * FROM reviews WHERE rev_id='$id'")){
	
	$ReviwsRow = mysqli_fetch_array($Reviws);

	$BizId = $ReviwsRow['b_id'];
	
	$Reviws->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


$mysqli->query("UPDATE business SET reviews=reviews-1 WHERE biz_id='$BizId'");

$mysqli->query("UPDATE reviews SET rev_active=0 WHERE rev_id='$id'");



echo '<div class="alert alert-success" role="alert">Review updated successfully.</div>';

?>