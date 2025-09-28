<?php include("header.php");?>
<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Business listings</li>
  <li class="active">Approved Businesses</li>
</ol>

<div class="page-header">
  <h3>Approved Businesses <small>Manage approved businesses</small></h3>
</div>

<script type="text/javascript">
$(document).ready(function(){
//Delete	
$('button.btnDelete').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
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
url: "delete_biz.php",
data: dataString,
cache: false,
success: function(html)
{

$("#output").html(html);
}
});
//ajax ends
});
//Desapprove
$('button.btnDisapprove').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
    $('#DisapproveModal').data('id', id).modal('show');
});

$('#btnDisapproveYes').click(function () {
    var id = $('#DisapproveModal').data('id');
	var dataString = 'id='+ id ;
    $('[data-id=' + id + ']').remove();
    $('#DisapproveModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "disapprove_biz.php",
data: dataString,
cache: false,
success: function(html)
{
//$(".fav-count")(html);
$("#output").html(html);
}
});
//ajax ends
});
//Desapprove
//Feat
$('button.btnMkFeat').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
    $('#FeatModal').data('id', id).modal('show');
});

$('#btnFeatYes').click(function () {
    var id = $('#FeatModal').data('id');
	var dataString = 'id='+ id ;
    //$('[data-id=' + id + ']').remove();
    $('#FeatModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "mk_feat_biz.php",
data: dataString,
cache: false,
success: function(html)
{
//$(".fav-count")(html);
$("#output").html(html);
}
});
//ajax ends
});
//Un Feat
$('button.btnUnFeat').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('tr').data('id');
    $('#unFeatModal').data('id', id).modal('show');
});

$('#btnUnFeatYes').click(function () {
    var id = $('#unFeatModal').data('id');
	var dataString = 'id='+ id ;
    //$('[data-id=' + id + ']').remove();
    $('#unFeatModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "un_feat_biz.php",
data: dataString,
cache: false,
success: function(html)
{
//$(".fav-count")(html);
$("#output").html(html);
}
});
//ajax ends
});
});
</script>

<section class="col-md-8">

<div id="output"></div>
      
<?php
error_reporting(E_ALL ^ E_NOTICE);
// How many adjacent pages should be shown on each side?
	$adjacents = 5;
	
	$query = $mysqli->query("SELECT COUNT(*) as num FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.biz_id DESC");
	
	//$query = $mysqli->query("SELECT COUNT(*) as num FROM business listings WHERE  posts.active=1 ORDER BY posts.pid DESC");
	
	$total_pages = mysqli_fetch_array($query);
	$total_pages = $total_pages['num'];
	
	$targetpage = "approved_businesses.php";
	$limit = 15; 								//how many items to show per page
	$page = $_GET['page'];
	 
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
 	/* Get data. */
	$result = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.biz_id DESC LIMIT $start, $limit");
	 
	//$result = $mysqli->query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<ul class=\"pagination pagination-lg\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<li><a href=\"$targetpage?page=$prev\">&laquo;</a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"#\">&laquo;</a></li>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"$targetpage?page=1\">1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=2\">2</a></li>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";					
				}
				$pagination.= "...";
				$pagination.= "<li><a href=\"$targetpage?page=$lpm1\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=$lastpage\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li><a href=\"$targetpage?page=1\">1</a></li>";
				$pagination.= "<li><a href=\"$targetpage?page=2\">2</a></li>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"$targetpage?page=$counter\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li><a href=\"$targetpage?page=$next\">&raquo;</a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"#\">&raquo;</a></li>";
		$pagination.= "</ul>\n";		
	}
	
	$q= $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.biz_id DESC limit $start,$limit");


	$numr = mysqli_num_rows($q);
	if ($numr==0)
	{
	echo '<div class="alert alert-danger">There are no approved businesses to display at this moment.</div>';
	}
	if ($numr>0)
	{
	?>
       <table class="table table-bordered">

        <thead>

            <tr>
				<th>Feat Photo</th>
                
                <th>Business Name</th>
                
                <th>Description</th>

                <th>Added On</th>

                <th>Manage</th>

            </tr>

        </thead>

        <tbody>
    <?php
	}
	
	while($PostRow=mysqli_fetch_assoc($q)){
	
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
	

	$CName = $PostRow['category'];
	$CLink = preg_replace("![^a-z0-9]+!i", "-", $CName);
	$CLink = urlencode($CLink);
	$CLink = strtolower($CLink);
	
	$Feat = $PostRow['feat'];
			
	
?>        

            <tr class="btnDelete" data-id="<?php echo $PostRow['biz_id'];?>">
				<td><a href="../business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>" target="_blank"><img src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $longTitle;?>" class="img-responsive"></a></td>
                
                <td><a href="../business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>l" target="_blank"><?php echo ucfirst($longTitle);?></a> <br/><br/> <strong>category:</strong> <a href="../category-<?php echo $PostRow['cid'];?>-<?php echo $CLink;?>" target="_blank"><?php echo $PostRow['category'];?></a></td>
                
                <td><?php echo $Description;?></td>

                <td><?php echo $PostRow['date'];?></td>

                <td>
                <!--Testing Modal-->
                
               <button class="btn btn-danger btnDelete">Delete</button>
               <button class="btn btn-warning btnDisapprove">Disapprove</button>
               <a class="btn btn-success btnEdit" href="edit_business.php?id=<?php echo $PostRow['biz_id'];?>">Edit</a>
               <?php if($Feat==1){?>
               <button class="btn btn-default btnUnFeat">unFeat</button>
               <?php }else if($Feat==0){?>
               <button class="btn btn-primary btnMkFeat">mkFeat</button>
               <?php }?>   
                <!--Testing Modal-->
                </td>

            </tr>
<?php } ?>
    
         
        </tbody>

    </table>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
				<p>Are you sure you want to DELETE this business listing?</p>
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

<!--Approve Modal-->
<div class="modal fade" id="DisapproveModal" tabindex="-1" role="dialog" aria-labelledby="DisapproveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
                 <p> Are you sure you want to DISAPPROVE this business listing?</p>
				 <p class="text-info"><small>You can re-approve this business listing by navigating to Business Listing > Pending businesses.</small></p>	
            </div>
            <!--/modal-body-collapse -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnDisapproveYes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <!--/modal-footer-collapse -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--Feat Modal-->
<div class="modal fade" id="FeatModal" tabindex="-1" role="dialog" aria-labelledby="FeatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
                 <p> Are you sure you want to make this business listing FEATURED?</p>
				 <p class="text-info"><small>You can un-feature this clicking unFeat button.</small></p>	
            </div>
            <!--/modal-body-collapse -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnFeatYes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <!--/modal-footer-collapse -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--UnFeat Modal-->
<div class="modal fade" id="unFeatModal" tabindex="-1" role="dialog" aria-labelledby="unFeatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
                 <p> Are you sure you want to make this business listing UN-FEATURED?</p>
				 <p class="text-info"><small>You can feature this business listing again by clicking mkFeat button.</small></p>	
            </div>
            <!--/modal-body-collapse -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnUnFeatYes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <!--/modal-footer-collapse -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<?php echo $pagination;?>

</section>

</section><!--col-md-10-->

<?php include("footer.php");?>