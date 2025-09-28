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
  <li class="active">Add More Photos</li>
</ol>

<div class="page-header">
  <h3>Add More Photos <small>Add more photos of this business</small></h3>
</div>

<?php

$id = $mysqli->escape_string($_GET['id']);


$UniqNumber = $mysqli->escape_string($_GET['id']);

if($Biz = $mysqli->query("SELECT * FROM business WHERE unique_biz='$UniqNumber'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}	

?>  

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
<section class="col-md-8">

<div class="panel panel-default">

<div class="panel-body">
       
<div id="output-gallery"></div>

<form id="imageform" action="upload_gallery.php?id=<?php echo $UniqNumber;?>" enctype="multipart/form-data" method="post">

<div class="form-group">
<label for="inputfile">Select Photos</label>
<input type="file" name="photos[]" id="photo-img" class="filestyle" multiple data-iconName="glyphicon-picture" data-buttonText="Select Photos">
</div>

<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Upload</button>

</form>
              
</div><!--the-form-->


</div><!--panel panel-default--> 

</section>

</section><!--col-md-10-->


<?php include("footer.php");?>