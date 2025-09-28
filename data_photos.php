<?php include("header.php");?>

<div class="container" id="display-posts">

<?php
$id = $mysqli->escape_string($_GET['id']);

if($PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.unique_biz='$id'")){

$PostRow = mysqli_fetch_array($PostSql);
	
	$PostSql->close();

}else{
     printf("There Seems to be an issue");
}

$page = $mysqli->escape_string($_GET["page"]);
$start = ($page - 1) * 30;

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$id' ORDER BY img_id DESC LIMIT $start, 30")){
	
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
		

?>
      <div class="col-sm-6 col-md-3 col-gallery"> <a href="http://<?php echo $SiteLink;?>/gallery/<?php echo $PhotosRow['image'];?>" class="thumbnail" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo $PostRow['business_name'];?> Photos"> <img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/gallery/<?php echo $PhotosRow['image'];?>&amp;h=400&amp;w=500&amp;q=100" alt="<?php echo $PostRow['business_name'];?> Photos"> </a> </div>
      <?php
}

	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>


</div><!--container-->



<?php include("footer.php");?>