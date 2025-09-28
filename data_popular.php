<?php include("header.php");?>

<div class="container" id="display-posts">

<?php

$page = $mysqli->escape_string($_GET["page"]);
$start = ($page - 1) * 12;

if($PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.star5 DESC LIMIT $start, 12")){

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
	
	$CName = $PostRow['category'];
	$CLink = preg_replace("![^a-z0-9]+!i", "-", $CName);
	$CLink = urlencode($CLink);
	$CLink = strtolower($CLink);
	

?>

<div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
 
<div class="grid wow fadeInUp">
 
<a class="over-label" href="category-<?php echo $PostRow['cid'];?>-<?php echo $CLink;?>"><?php echo $PostRow['category'];?></a> 
        
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
?>


</div><!--container-->



<?php include("footer.php");?>