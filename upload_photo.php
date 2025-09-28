<?php include("header.php");

if(!isset($_SESSION['username'])){?>
<script type="text/javascript">
function leave() {
window.location = "login";
}
setTimeout("leave()", 2);
</script>
<?php }else{

$UniqNumber = $mysqli->escape_string($_GET['id']);

if($Biz = $mysqli->query("SELECT * FROM business WHERE unique_biz='$UniqNumber'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}	
	
	
?>


<div class="container container-main">

<div class="col-md-8">

<script type="text/javascript" src="js/jquery.form.js"></script> 
<script src="js/bootstrap-filestyle.min.js"></script>

<script type="text/javascript">

$(document).ready(function()
{
    $('#imageform').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output-gallery").html('<div class="alert alert-info" role="alert">Uploading.. Please wait..</div>');
		
        $(this).ajaxSubmit({
        target: '#output-gallery',
        success:  afterSuccess //call function after success
        });
    });
});
 
function afterSuccess()
{	
	 
    $('#submitButton').removeAttr('disabled'); //enable submit button
   
}

$(function(){

$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});

});

</script>     
   
<div class="col-shadow">
      <div class="biz-title-2">
        <h1>Add More Photos of <?php echo $BizRow['business_name'];?></h1>
      </div>
      <div class="col-desc">
            
              <div id="output-gallery"></div>

<form id="imageform" action="upload_gallery.php?id=<?php echo $UniqNumber;?>&amp;uid=<?php echo $UserId;?>" enctype="multipart/form-data" method="post">

<div class="form-group">
<label for="inputfile">Select Photos</label>
<input type="file" name="photos[]" id="photo-img" class="filestyle" multiple data-iconName="glyphicon-picture" data-buttonText="Select Photos">
</div>

<button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Upload</button>

</form>
  </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->

</div><!--col-md-8-->

<div class="col-md-4">
<?php include("side_bar.php");?>
</div><!--col-md-4-->


</div><!--container-->

<?php } include("footer.php");?>