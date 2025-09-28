<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Business</li>
  <li>Manage Business</li>
  <li>Edit Business</li>
  <li class="active">Manage Photos</li>
</ol>

<div class="page-header">
  <h3>Manage Photos <small>Manage photos of this business</small></h3>
</div>

<?php

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
<section class="col-md-8">

<div class="panel panel-default">

<div class="panel-body">
       
    <div class="row col-row" id="display-posts">
      <?php

if($Photos = $mysqli->query("SELECT * FROM galleries WHERE uniq='$id' ORDER BY img_id DESC")){
	
	
	while($PhotosRow = mysqli_fetch_array($Photos)){
	
	$PhotoUploader = $PhotosRow['uid'];

?>
      <div class="col-sm-6 col-md-3 col-gallery" data-id="<?php echo $PhotosRow['img_id'];?>">
       <a class="btnDelete btn-pic-delete " href="#"><span class="glyphicon glyphicon-remove"></span></a>

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

</div><!--panel panel-default--> 

</section>

</section><!--col-md-10-->

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