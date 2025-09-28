<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

if($Reviws = $mysqli->query("SELECT * FROM reviews WHERE rev_id='$del'")){
	
	$ReviwsRow = mysqli_fetch_array($Reviws);

	$BizId = $ReviwsRow['b_id'];
	
	$Reviws->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


$mysqli->query("UPDATE business SET reviews=reviews-1 WHERE biz_id='$BizId'");

$mysqli->query("DELETE FROM reviews WHERE rev_id='$del'");


echo '<div class="alert alert-success" role="alert">Review successfully deleted.</div>';

?>