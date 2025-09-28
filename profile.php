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
    
      <div class="col-shadow col-profile">
      <div class="right-title">
        <h1 class="pull-left">About <?php echo ucfirst($ProfileAuthor);?></h1>
      </div>
       <p><?php echo stripslashes($ProfileInfo['about']);?></p>
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

if($Reviews = $mysqli->query("SELECT * FROM reviews LEFT JOIN business ON business.biz_id=reviews.b_id WHERE reviews.b_id=business.biz_id AND reviews.rev_active=1 AND reviews.u_id='$id' ORDER BY reviews.rev_id DESC LIMIT 10")){
	

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
        <?php } if($NumReviews>5){?>
        <a href="all_reviews-<?php echo $id;?>-<?php echo $ProfileLink;?>"><span class="fa fa-arrow-right"></span> See All (<?php echo $NumReviews;?>)</a>
        <?php }?>
      </div>
      <!--col-desc--> 
      <!--col-desc--> 
    </div>
    <!--col-shadow-->

 <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Bookmarks by <?php echo ucfirst($ProfileAuthor);?></h1>
      </div>
<div class="col-desc" id="display-reviews">    
 
 
<?php

//Get bookmark Count

$BookmarkCount = $mysqli->query("SELECT * FROM bookmarks WHERE user_id='$id'");
$NumBookmark = $BookmarkCount->num_rows;

if($PostSql = $mysqli->query("SELECT * FROM bookmarks LEFT JOIN business ON bookmarks.bizid=business.biz_id WHERE bookmarks.user_id=$id ORDER BY bookmarks.bm_id DESC LIMIT 0, 6")){


$CountRows = mysqli_num_rows($PostSql);	

while ($PostRow = mysqli_fetch_array($PostSql)){
	
	$longTitle = stripslashes($PostRow['business_name']);
	$strTitle = strlen ($longTitle);
	if ($strTitle > 25) {
	$PostTitle = substr($longTitle,0,23).'...';
	}else{
	$PostTitle = $longTitle;}
	
	$PostLink = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
	$PostLink = urlencode(strtolower($PostLink));
	
	$longDescription = stripslashes($PostRow['description']);
	$strDescription = strlen ($longDescription);
	if ($strDescription > 70) {
	$Description = substr($longDescription,0,67).'...';
	}else{
	$Description = $longDescription;}
	
	$Tel = stripslashes($PostRow['phone']);
	$City = stripslashes($PostRow['city']);
	$Site = stripslashes($PostRow['website']);
	
	if(!empty($Tel)){
		$Telephone = $Tel;
	}else{
		$Telephone = "N/A";		
	}
?>

<div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
 
 <div class="grid wow fadeInUp">
 
        
        <a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=300&amp;w=500&amp;q=100" alt="<?php echo $PostTitle;?>"></a>
    
    <h2><a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><?php echo $PostTitle;?></a></h2>
    <p><?php echo $Description;?></p>
    
    <div class="post-info-bottom">
<div class="col-rate">    
<span class="star-rates"  data-score="<?php echo $PostRow['avg'];?>"></span> <?php echo $PostRow['reviews'];?> Reviews
</div>

<div class="info-row"><span class="fa fa-home"></span> <?php echo $City;?></div>
<div class="info-row"><span class="fa fa-phone"></span> <?php echo $Telephone;?></div>
<?php if(!empty($Site)){?>
<div class="info-row"><span class="fa fa-link"></span> <a href="<?php echo $Site;?>" target="_blank">Website</a></div>
<?php }else{?>
<div class="info-row"><span class="fa fa-link"></span> N/A</div>
<?php }?>
</div>
 
  </div><!-- /.grid -->  
    
</div><!-- /.col-sm-12 col-xs-12 col-md-4 col-lg-4 -->

<?php     
	}
$PostSql->close();
}else{
     printf("There Seems to be an issue");
}
if($NumBookmark==0){
?>
<div class="col-note"><?php echo ucfirst($ProfileAuthor);?> haven’t bookmarked any business listings yet!.</div>
<?php }if($NumBookmark>6){?>
 <a href="all_bookmarks-<?php echo $id;?>-<?php echo $ProfileLink;?>"><span class="fa fa-arrow-right"></span> See All (<?php echo $NumBookmark;?>)</a>
<?php }?>    
   
    
  </div> <!--col-shadow--> 
   </div>     
    
</div><!--col-md-8-->    
    
  </div>
  <!--container-->
  
<?php include("footer.php");?>