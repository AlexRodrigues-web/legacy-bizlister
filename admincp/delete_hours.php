<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);

$mysqli->query("DELETE FROM hours WHERE hour_id='$del'");


echo '<div class="alert alert-success" role="alert">Successfully removed.</div>';

?>