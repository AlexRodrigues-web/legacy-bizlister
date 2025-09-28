<?php
include("db.php");
session_start();

$id=$_GET['id'];
$score=$_GET['score'];
$uniq=$_GET['uniq'];


$sql = $mysqli->query("SELECT * FROM reviews WHERE uniq='$uniq'");
$CountRates = $sql->num_rows;
$row = mysqli_fetch_array($sql);

if($CountRates==0){
$mysqli->query("INSERT INTO reviews(avg, uniq, rev_active) VALUES('$score','$uniq','3')");
}else{
$mysqli->query("UPDATE reviews SET avg=$score");	
}

if ($score == 1){
	
$mysqli->query("UPDATE business SET star1=star1+1, tot=tot+1 WHERE biz_id=$id");

} else if ($score == 2){
	
$mysqli->query("UPDATE business SET star2=star2+1, tot=tot+1 WHERE biz_id=$id");

} else if ($score == 3){
	
$mysqli->query("UPDATE business SET star3=star3+1, tot=tot+1 WHERE biz_id=$id");

} else if ($score == 4){
	
$mysqli->query("UPDATE business SET star4=star4+1, tot=tot+1 WHERE biz_id=$id");

} else if ($score == 5){
	
$mysqli->query("UPDATE business SET star5=star5+1, tot=tot+1 WHERE biz_id=$id");

}

$sqln = $mysqli->query("SELECT * FROM business WHERE biz_id='$id'");
$rown = mysqli_fetch_array($sqln);

$votes = $rown['tot'];
 
//Get star ratings
$str1 = $rown['star1'];
$str2 = $rown['star2'];
$str3 = $rown['star3'];
$str4 = $rown['star4'];
$str5 = $rown['star5'];


$cal = ($str1*1 + $str2*2 + $str3*3 + $str4*4 + $str5*5)/$votes;

$avg = number_format($cal, 2);

$mysqli->query("UPDATE business SET avg='$avg' WHERE biz_id=$id");



echo "Your rate is ".$score .".";

?>