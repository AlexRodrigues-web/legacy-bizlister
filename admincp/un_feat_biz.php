<?php

include("../db.php");

$id = $mysqli->escape_string($_POST['id']);

$update = $mysqli->query("UPDATE  business SET feat='0' WHERE biz_id='$id'");

echo '<div class="alert alert-success" role="alert">Business listing successfully updated</div>';

?>