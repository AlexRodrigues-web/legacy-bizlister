<?php include("header.php");

if(!isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "login";
}
setTimeout("leave()", 2);
</script>
<?php }else{?>

<div class="container container-main">

<div class="col-md-8">

<script type="text/javascript" src="js/jquery.form.js"></script>

     <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Manage Your Business</h1>
      </div>
      <div class="col-desc" id="display-posts">

<script>
$(document).ready(function()
{
$('.biz-rate').raty({
	readOnly: true,
    score: function() {
    return $(this).attr('data-score');

  }
});
});
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
    $('[data-id=' + id + ']').parent().parent().remove();
    $('#myModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "delete_biz.php",
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

<div id="output"></div>

<?php

if($PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 AND biz_user='$UserId' ORDER BY business.biz_id DESC LIMIT 0, 12")){

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
        <p class="font-small"><span class="biz-rate" data-score="<?php echo stripslashes($PostRow['avg']);?>"></span> <?php echo stripslashes($PostRow['reviews']);?> Reviews | <?php echo stripslashes($PostRow['hits']);?> Views</p>
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
      
<nav id="page-nav"><a href="data_my_business.php?page=2"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
	<script src="js/manual-trigger.js"></script>
	
	<script>
	
	
	$('#display-posts').infinitescroll({
		navSelector  : '#page-nav',    // selector for the paged navigation 
      	nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      	itemSelector : '.img-thumbs',     //
		loading: {
          				finishedMsg: 'End of business listings.',
          				img: 'templates/<?php echo $Settings['template'];?>/images/loader.gif'
	}
	}, function(newElements, data, url){
		
		$('.biz-rate').raty({
	   readOnly: true,
       score: function() {
       return $(this).attr('data-score');

       }
		});
		$('.biz-rate').raty('reload');	
	});	

</script>      
      
<?php if(!empty($Ad2)){?>
<div class="col-shadow col-ads">
<?php echo $Ad2;?>
</div><!--col-shadow-->
<?php } ?>      

</div><!--col-md-8-->

<div class="col-md-4">
<?php include("side_bar.php");?>
</div><!--col-md-4-->

</div><!--container-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
				<p>Are you sure you want to delete this business listing?</p>
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

<?php } include("footer.php");?>