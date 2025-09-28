<?php include("db.php");

//Get Site Settings

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $Settings = mysqli_fetch_array($SiteSettings);
	
	$SiteLink = $Settings['site_link'];

	$SiteSettings->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

$id = $mysqli->escape_string($_POST['id']);
?>

<script>
$(function()
{
$('.more').on("click",function()
{
var ID = $(this).attr("id");
if(ID)
{
$("#more"+ID).html('<img src="templates/<?php echo $Settings['template'];?>/images/loader.gif"/>');

$.ajax({
type: "POST",
url: "data_reviews.php",
data: "lastmsg="+ ID +"&id="+<?php echo $id;?>,
cache: false,
success: function(html){
$("div#display-reviews").append(html);
$("#more"+ID).remove(); // removing old more button
}
});
}


return false;
});
});

$(document).ready(function()
{
$('.star-rates').raty({
	readOnly: true,
  score: function() {
    return $(this).attr('data-score');

  }
});
});
</script>

<?php
if(isset($_POST['lastmsg']))
{

$page = $mysqli->escape_string($_POST["lastmsg"]);

if($Reviews = $mysqli->query("SELECT * FROM reviews LEFT JOIN users ON users.user_id=reviews.u_id WHERE reviews.u_id=users.user_id AND reviews.rev_active=1 AND reviews.b_id='$id' AND reviews.rev_id<'$page' ORDER BY reviews.rev_id DESC LIMIT 10")){
	
	$CountReviews = $Reviews->num_rows;

    while($ReviewsRow = mysqli_fetch_array($Reviews)){
		
		$UserName = $ReviewsRow['username'];
		$UserLink = preg_replace("![^a-z0-9]+!i", "-", $UserName);
		$UserLink = urlencode(strtolower($UserLink));
		$UserAvatar = $ReviewsRow['avatar'];
		
		if (empty($UserAvatar)){ 
		$AvatarImg =  'http://'.$SiteLink.'/templates/'.$Settings['template'].'/images/avatar.jpg';
		}elseif (!empty($UserAvatar)){
		$AvatarImg =  'http://'.$SiteLink.'/avatars/'.$UserAvatar;
 		}	
		
		$RewId = $ReviewsRow['rev_id'];
?>

<div class="review-box">

<a href="profile-<?php echo $ReviewsRow['user_id'];?>-<?php echo $UserLink;?>"> 
<?php
	echo '<img class="img-avatar" src="thumbs.php?src='.$AvatarImg.'&amp;h=60&amp;w=60&amp;q=100" alt="'.ucfirst($UserName).'" />';
 ?>
</a>

<div class="review-heading"> 
<a href="profile-<?php echo $ReviewsRow['user_id'];?>-<?php echo $UserLink;?>"><?php echo ucfirst($UserName);?></a>
<span><?php echo $ReviewsRow['rew_date'];?></span>
<div class="col-rate">    
<div class="col-rate"> <span class="star-rates"  data-score="<?php echo $ReviewsRow['avg'];?>"></span> </div>
</div>

</div>

<div class="review-body">
            
<p><?php echo nl2br($ReviewsRow['review']);?></p>

</div> <!--review-body--> 
        

</div> <!--review-box-->

<?php
}

	$Reviews->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if(!empty($RewId)){
?>

<div id="more<?php echo $RewId ;?>" class="morebox">
<a href="#" class="more btn btn-lg btn-danger" id="<?php echo $RewId ;?>"><span class="fa fa-chevron-down"></span> See More</a>
</div>

<?php }else{ ?>
<script>
$( "#msg-end" ).fadeOut(4000);
</script>
<div class="morebox" id="msg-end">No more reviews to display</div>
<?php } }?>