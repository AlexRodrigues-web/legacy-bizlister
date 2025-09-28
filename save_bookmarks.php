<?php
session_start();

include("db.php");

if($_POST['id'])
{
$id=$_POST['id'];

//User Details

if(isset($_SESSION['username'])){
	
$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['user_id'];

    $UserSql->close();
}else{
	
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");

}

//End User Details


$id = $mysqli->escape_string($id);

//Verify IP address in favip table

$user_sql=$mysqli->query("SELECT * FROM bookmarks WHERE bizid='$id' AND user_id='$Uid'");

$count = $user_sql->num_rows; 

if($count==0)
{
// Update Vote.
$mysqli->query("UPDATE business SET bookmarks=bookmarks+1 WHERE biz_id='$id'");

// Insert IP address and Message Id in favip table.
$mysqli->query("INSERT INTO bookmarks (bizid, user_id) values ('$id','$Uid')");

//disply results
$result=$mysqli->query("SELECT * FROM business WHERE biz_id='$id'");
$row=mysqli_fetch_array($result);
$TotalSaves=$row['bookmarks'];

echo '<span class="fa fa-bookmark"></span> Bookmarked ('.$TotalSaves.')</span>';

}else {

// Update Vote.
$mysqli->query("UPDATE business SET bookmarks=bookmarks-1 WHERE biz_id='$id'");

// Insert IP address and Message Id in favip table.
$mysqli->query("DELETE FROM bookmarks WHERE bizid='$id' AND user_id='$Uid'");

//disply results
$result=$mysqli->query("SELECT * FROM business WHERE biz_id='$id'");
$row=mysqli_fetch_array($result);
$TotalSaves=$row['bookmarks'];

echo '<span class="fa fa-bookmark"></span> Bookmark ('.$TotalSaves.')</span>';


}

}

}
?>