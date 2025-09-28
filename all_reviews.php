<?php include("header.php");

$id = $mysqli->escape_string($_GET['id']);

if($ProfileSql = $mysqli->query("SELECT * FROM users WHERE user_id='$id'")){

    $ProfileInfo = mysqli_fetch_array($ProfileSql);
	
	$ProfileAuthor	 = stripslashes($ProfileInfo['username']);
	$ProfileLink = preg_replace("![^a-z0-9]+!i", "-", $ProfileAuthor);
	$ProfileLink = urlencode($ProfileLink);
	$ProfileLink = strtolower($ProfileLink);
	
	$ProfileAvatar = $ProfileInfo['avatar'];
		
	$ProfileSql->close();
	
}else{
     
	 printf("There Seems to be an issue");
	 
}

if (empty($ProfileAvatar)){ 
	$ProfilePic =  'http://'.$SiteLink.'/templates/'.$Settings['template'].'/images/avatar.jpg';
	}elseif (!empty($ProfileAvatar)){
	$ProfilePic =  'http://'.$SiteLink.'/avatars/'.$ProfileAvatar;
}

//Get Review Count

$ReviewsCount = $mysqli->query("SELECT * FROM reviews WHERE rev_active=1 AND u_id='$id'");
$NumReviews = $ReviewsCount->num_rows;

?>

  <div class="container container-main">
    
    <div class="col-md-4">
              
     <div class="col-shadow">
      <div class="right-title">
        <h1 class="pull-left"><?php echo ucfirst($ProfileAuthor);?></h1>
         
        </div>
        <div class="img-profile">
       <img src="thumbs.php?src=<?php echo $ProfilePic;?>&amp;h=200&amp;w=200&amp;q=100" alt="<?php echo ucfirst($ProfileAuthor);?>" class="img-circle">
       <p><?php echo $NumReviews;?> Reviews</p>
       </div><!--img-profile-->
      <!--col-right--> 
    </div>
    <!--col-shadow-->
    
<?php if(!empty($Ad1)){?>
<div class="col-shadow col-ads">
<?php echo $Ad1;?>
</div><!--col-shadow-->
<?php }?>
          
    </div>
    
    <!--col-md-4-->
    
<div class="col-md-8"> 
<script>     
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

      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Recent Reviews by <?php echo ucfirst($ProfileAuthor);?></h1>
      </div>
<div class="col-desc" id="display-reviews">
<?php

if($Reviews = $mysqli->query("SELECT * FROM reviews LEFT JOIN business ON business.biz_id=reviews.b_id WHERE reviews.b_id=business.biz_id AND reviews.rev_active=1 AND reviews.u_id='$id' ORDER BY reviews.rev_id DESC LIMIT 0, 10")){
	

    while($ReviewsRow = mysqli_fetch_array($Reviews)){
		
		$bizName = $ReviewsRow['business_name'];
		$BizLink = preg_replace("![^a-z0-9]+!i", "-", $bizName);
		$UserLink = urlencode(strtolower($BizLink));
	
		$RewId = $ReviewsRow['rev_id'];
	
?>
        <div class="review-box"> <a href="business-<?php echo $ReviewsRow['biz_id'];?>-<?php echo $UserLink;?>">
    	
 <img class="img-avatar" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $ReviewsRow['featured_image'];?>&amp;h=60&amp;w=60&amp;q=100" alt="<?php echo ucfirst($ReviewsRow['business_name']);?>">
          </a>
          <div class="review-heading"> <a href="business-<?php echo $ReviewsRow['biz_id'];?>-<?php echo $UserLink;?>"><?php echo ucfirst($ReviewsRow['business_name']);?></a> <span><?php echo $ReviewsRow['rew_date'];?></span>
            <div class="col-rate"> <span class="star-rates"  data-score="<?php echo $ReviewsRow['avg'];?>"></span> </div>
          </div>
          <div class="review-body">
            <p><?php echo nl2br($ReviewsRow['review']);?></p>
          </div>
          <!--review-body--> 
          
        </div>
        <!--review-box--> 
        

        <?php
}

	$Reviews->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($NumReviews==0){
?>
        <div class="col-note"><?php echo ucfirst($ProfileAuthor);?> haven’t wrote any reviews yet!</div>
        <?php } ?>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
   
    </div><!--col-md-8-->
    
<nav id="page-nav"><a href="data_all_reviews.php?page=2&amp;id=<?php echo $id;?>"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
	<script src="js/manual-trigger.js"></script>
	
	<script>
	
	
	$('#display-reviews').infinitescroll({
		navSelector  : '#page-nav',    // selector for the paged navigation 
      	nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      	itemSelector : '.review-box',     //
		loading: {
          				finishedMsg: 'End of Reviews.',
          				img: 'templates/<?php echo $Settings['template'];?>/images/loader.gif'
	}
	}, function(newElements, data, url){
		
		$('.star-rates').raty({
	readOnly: true,
    score: function() {
    return $(this).attr('data-score');
  		}
		});
		$('.star-rates').raty('reload');	
	});	

</script>        
    
  </div>
  <!--container-->
  
<?php include("footer.php");?>