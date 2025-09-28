<?php
session_start();

include('db.php');

$id = $mysqli->escape_string($_GET['id']);

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($squ);
	
	$Active = $Settings['rev_active'];
	
	$SiteLink = $Settings['site_link'];

    $squ->close();
}else{
     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");;
}


//Get user info

$Uname = $_SESSION['username'];

if($UserSql = $mysqli->query("SELECT * FROM users WHERE username='$Uname'")){

    $UserRow = mysqli_fetch_array($UserSql);

	$Uid = $UserRow['user_id'];
	
	$UserAvatar = $UserRow['avatar'];
	
    $UserSql->close();
	
}else{
     
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
	 
}

if($_POST)
{		


//Get Reviews

$inputUniq			= $mysqli->escape_string($_POST['inputUniq']);

if($Reviews = $mysqli->query("SELECT * FROM reviews WHERE uniq='$inputUniq'")){

    $ReviewCount =  $Reviews->num_rows;
	
	$ReviewRow = mysqli_fetch_array($Reviews );
			
	$GetUniq = $ReviewRow['uniq']; 
	
	if (empty($UserAvatar)){ 
	$AvatarImg =  'http://'.$SiteLink.'/templates/'.$Settings['template'].'/images/avatar.jpg';
	}elseif (!empty($UserAvatar)){
	$AvatarImg =  'http://'.$SiteLink.'/avatars/'.$UserAvatar;
 	}
	
    $Reviews->close();

}else{

     printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");;

}



	if(!isset($_POST['inputReview']) || strlen($_POST['inputReview'])<1)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please enter your review.</div>');
	}
	
	if($ReviewCount==0)
	{
		//required variables are empty
		die('<div class="alert alert-danger" role="alert">Please rate this business.</div>');
	}
	
	$Date		        = date("F j, Y");
	
	$Review		= $mysqli->escape_string($_POST['inputReview']);
		

$mysqli->query("UPDATE reviews SET review='$Review', u_id='$Uid', rew_date='$Date', b_id='$id', rev_active='$Active' WHERE uniq='$inputUniq'");

if($Active==1){

$mysqli->query("UPDATE business SET reviews=reviews+1 WHERE biz_id='$id'");	
	
}

if($FindReviews = $mysqli->query("SELECT * FROM reviews WHERE uniq='$inputUniq'")){
	
$ReviewsRow = mysqli_fetch_array($FindReviews);

$Rew = $ReviewsRow['review'];
		

	$FindReviews->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>

<script>
$('#col-review-box').delay(1000).resetForm(1000);
$('#col-review-box').fadeOut(1000);
</script>

<script>
$(function(){
$('#rating').raty({readOnly: true, score:<?php echo $ReviewRow['avg'];?>});
});
</script>

<?php
		
		die('
		
<div class="alert alert-success" role="alert">Thank you for your submission.</div>

<div class="col-shadow">
<div class="biz-title-2"><h1>Your Review</h1></div>
<div class="col-desc">

<div class="review-box">
<img class="img-responsive img-avatar" src="'.$AvatarImg.'" width="60" height="60" alt="'.ucfirst($Uname).'" />

 
<div class="review-heading"> 
'.ucfirst($Uname).'
<span>Just Now</span>
<div class="col-rate">    
<span id="rating"></span> 
</div>

</div>

<div class="review-body">
            
<p>'.nl2br($Rew).'</p>
</div> 
        
</div>
</div>
</div> 

');
		
   
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   
}

?>