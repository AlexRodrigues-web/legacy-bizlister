<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

$mysqli->query("DELETE FROM reviews WHERE rev_id='$del'");


echo '<div class="alert alert-success" role="alert">Review successfully deleted.</div>';

?>