<?php

include("../db.php");

$del = $mysqli->escape_string($_POST['id']);


$mysqli->query("DELETE FROM city WHERE city_id='$del'");

echo '<div class="alert alert-success" role="alert">City successfully deleted</div>';

?>