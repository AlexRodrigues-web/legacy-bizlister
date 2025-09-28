<?php include("header.php");?>


<div class="col-desc" id="display-posts">

<div id="output"></div>

<?php

$page = $mysqli->escape_string($_GET["page"]);
$start = ($page - 1) * 12;

if($PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 AND biz_user='$UserId' ORDER BY business.biz_id DESC LIMIT $start, 12")){

$CountRows = mysqli_num_rows($PostSql);	

while ($PostRow = mysqli_fetch_array($PostSql)){
	
	$longTitle = stripslashes($PostRow['business_name']);
	
	$PostLink = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
	$PostLink = urlencode(strtolower($PostLink));
	
	$longDescription = stripslashes($PostRow['description']);
	$strDescription = strlen ($longDescription);
	if ($strDescription > 70) {
	$Description = substr($longDescription,0,67).'...';
	}else{
	$Description = $longDescription;}
	

?>
	
<div class="img-thumbs">

    <div class="right-caption span4">
      <img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=110&amp;w=140&amp;q=100" alt="<?php echo $longFeat;?>">
      <div class="col-caption" data-id="<?php echo $PostRow['biz_id'];?>">
        <a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><h2><?php echo $longTitle;?></h2></a>
        <p><span class="biz-rate" data-score="<?php echo stripslashes($PostRow['avg']);?>"></span> <?php echo stripslashes($PostRow['reviews']);?> Reviews</p>
        <p><?php echo $Description;?></p>
        <p>
        <a class="edit-links" href="edit_basic-<?php echo $PostRow['biz_id'];?>"><span class="fa fa-edit"></span> Edit Basic Info</a> 
        <a class="edit-links" href="edit_hours-<?php echo $PostRow['unique_biz'];?>"><span class="fa fa-edit"></span> Edit Hours</a> 
        <a class="edit-links" href="edit_map-<?php echo $PostRow['biz_id'];?>"><span class="fa fa-map-marker"></span> Edit Map</a> 
        <a class="edit-links btnDelete" href="delete_biz-<?php echo $PostRow['biz_id'];?>"><span class="fa fa-remove"></span> Delete</a>
        </p>
      </div>
    </div>
 </div>
 
<?php     
	}
$PostSql->close();
}else{
     printf("There Seems to be an issue");
}
if($CountRows==0){
?>
<div class="col-note">You don’t have any business listed with us.</div>
<?php }?>

  </div>
      <!--col-desc--> 
      
      </div>
    <!--col-shadow-->