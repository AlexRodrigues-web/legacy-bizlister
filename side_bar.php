<?php
$GetFetSql = $mysqli->query("SELECT * FROM business WHERE active=1 AND feat=1 ORDER BY biz_id DESC LIMIT 6");

$CountFeat = $GetFetSql->num_rows;	

if($CountFeat>0){	
?>

<div class="col-shadow">
<div class="right-title">
        <h1 class="pull-left">Featured</h1>
</div>

<script>
$(document).ready(function()
{
$('.feat-rate').raty({
	readOnly: true,
    score: function() {
    return $(this).attr('data-score');

  }
});
});
</script>

<?php
if($FetSql = $mysqli->query("SELECT * FROM business WHERE active=1 AND feat=1 ORDER BY biz_id DESC LIMIT 6")){ 

while ($FeatRow = mysqli_fetch_array($FetSql)){ 

	$longFeat = stripslashes($FeatRow['business_name']);
	$strFeat = strlen ($longFeat);
	if ($strFeat > 20) {
	$FeatTitle = substr($longFeat,0,17).'...';
	}else{
	$FeatTitle = $longFeat;}
	
	$FeatLink = preg_replace("![^a-z0-9]+!i", "-", $longFeat);
	$FeatLink = urlencode(strtolower($FeatLink));

?>
	
<div class="img-thumbs">

    <div class="right-caption span4">
      <img class="img-remove" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $FeatRow['featured_image'];?>&amp;h=90&amp;w=120&amp;q=100" alt="<?php echo $longFeat;?>">
      <div class="col-caption">
        <a href="business-<?php echo $FeatRow['biz_id'];?>-<?php echo $FeatLink;?>"><h4><?php echo $FeatTitle;?></h4></a>
        <p><span class="feat-rate" data-score="<?php echo stripslashes($FeatRow['avg']);?>"></span></p>
        <p><?php echo stripslashes($FeatRow['reviews']);?> Reviews</p>
      </div>
    </div>
 </div>
 
<?php     
	
	}
$FetSql->close();

}else{
     printf("There Seems to be an issue");
}


?>
<a class="pull-link" href="featured_businesses"><span class="fa fa-arrow-right"></span> See All Featured</a>
</div><!--col-shadow-->
<?php }?>

<?php if(!empty($FaceBook)){?>
<div class="col-shadow">
<div class="fb-page" data-href="<?php echo $FaceBook;?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
</div><!--col-shadow-->
<?php } if(!empty($Ad1)){?>
<div class="col-shadow col-ads">
<?php echo $Ad1;?>
</div><!--col-shadow-->
<?php }?>