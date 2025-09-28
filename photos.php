<?php include("header.php");

$id = $mysqli->escape_string($_GET['id']);

?>

<script>
$(document).ready(function(){
//Delete	
$('a.btnDelete').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('div').data('id');
    $('#myModal').data('id', id).modal('show');
});

$('#btnDelteYes').click(function () {
    var id = $('#myModal').data('id');
	var dataString = 'id='+ id ;
    $('[data-id=' + id + ']').remove();
    $('#myModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "delete_photo.php",
data: dataString,
cache: false,
success: function(html)
{
//$(".fav-count").html(html);
$("#output").html(html);
}
});
//ajax ends
});
});
</script>
<div class="container container-biz">
  <div class="col-md-4">
    <?php

if($PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.unique_biz='$id'")){

$PostRow = mysqli_fetch_array($PostSql);
	
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
	
	$BizUsser = stripslashes($PostRow['biz_user']);
	
	if(!empty($Tel)){
		$Telephone = $Tel;
	}else{
		$Telephone = "N/A";		
	}
	

?>
    <div class="col-box">
      <div class="grid wow fadeInUp"> <a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=300&amp;w=500&amp;q=100" alt="<?php echo $PostTitle;?>"></a>
        <h2><a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><?php echo $PostTitle;?></a></h2>
        <p><?php echo $Description;?></p>
        <div class="post-info-bottom">
          <div class="col-rate"> <span id="rate<?php echo $PostRow['biz_id'];?>"></span> <?php echo $PostRow['reviews'];?> Reviews </div>
          <div class="info-row"><span class="fa fa-home"></span> <?php echo $City;?></div>
          <div class="info-row"><span class="fa fa-phone"></span> <?php echo $Telephone;?></div>
          <?php if(!empty($Site)){?>
          <div class="info-row"><span class="fa fa-link"></span> <a href="<?php echo $Site;?>" target="_blank">Website</a></div>
          <?php }else{?>
          <div class="info-row"><span class="fa fa-link"></span> N/A</div>
          <?php }?>
        </div>
        <script>
$(function(){
$('#rate<?php echo $PostRow['biz_id'];?>').raty({readOnly: true, score:<?php echo $PostRow['avg'];?>});
});
</script> 
      </div>
      <!-- /.grid --> 
      
    </div>
    <!-- /.col-box -->
    
    <?php     

$PostSql->close();

}else{
     printf("There Seems to be an issue");
}

$CountPhotos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$id'");

$NumPhotos = $CountPhotos->num_rows;
?>


<div class="col-shadow">
<div class="col-right pull-down">
<p>Do you have photos of <?php echo $PostRow['business_name'];?>? You can add them to this photo gallery if you like.</p>

<a href="upload_photo-<?php echo $id;?>" class="btn btn-danger btn-lg btn-block"><span class="fa fa-cloud-upload"></span> Upload</a>
</div>
</div>
    <!-- /.col-shadow -->
  </div>
  <!--col-md-4-->
  <div class="col-md-8">
    <h1>Photos of <?php echo $PostTitle." (".$NumPhotos.")"?></h1>
    <div class="row col-row" id="display-posts">
      <?php

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$id' ORDER BY img_id DESC LIMIT 0, 30")){
	
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
	
	$PhotoUploader = $PhotosRow['uid'];

?>
      <div class="col-sm-6 col-md-3 col-gallery" data-id="<?php echo $PhotosRow['img_id'];?>">
      <?php if($UserId>0){ if(($PhotoUploader==$UserId) or ($BizUsser==$UserId)){?>
      <a class="btnDelete btn-pic-delete " href="#"><span class="fa fa-remove"></span></a>
      <?php } }?>   
<a href="http://<?php echo $SiteLink;?>/gallery/<?php echo $PhotosRow['image'];?>" class="thumbnail" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo $PostRow['business_name'];?> Photos"> <img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/gallery/<?php echo $PhotosRow['image'];?>&amp;h=400&amp;w=500&amp;q=100" alt="<?php echo $PostRow['business_name'];?> Photos"> </a> </div>
      <?php
}

	$Photos->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>
    </div>
    <!--row--> 
    
  </div>
  <!--col-md-8--> 
  
</div>
<!--container-biz-->

<nav id="page-nav"><a href="data_photos.php?page=2&amp;id=<?php echo $id;?>"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
<script src="js/manual-trigger.js"></script>
<script src="js/ekko-lightbox.min.js"></script> 
<script>
$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
	
	
	$('#display-posts').infinitescroll({
		navSelector  : '#page-nav',    // selector for the paged navigation 
      	nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      	itemSelector : '.col-gallery',     //
		loading: {
          				finishedMsg: 'End of photos.',
          				img: 'templates/<?php echo $Settings['template'];?>/images/loader.gif'
	}
	}, function(newElements, data, url){
		
	});	

</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
				<p>Are you sure you want to dele this photo?</p>
                <p class="text-warning"><small>This action cannot be undone.</small></p>		
            </div>
            <!--/modal-body-collapse -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnDelteYes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <!--/modal-footer-collapse -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include("footer.php");?>