<?php include("header.php");?>

<div class="col-desc" id="display-reviews">
<?php

$id = $mysqli->escape_string($_GET['id']);
$page = $mysqli->escape_string($_GET["page"]);
$start = ($page - 1) * 10;

if($Reviews = $mysqli->query("SELECT * FROM reviews LEFT JOIN business ON business.biz_id=reviews.b_id WHERE reviews.b_id=business.biz_id AND reviews.rev_active=1 AND reviews.u_id='$id' ORDER BY reviews.rev_id DESC LIMIT $start, 10")){
	

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

?>
      </div>
      <!--col-desc--> 

