<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Categories</li>
  <li class="active">Add New Subcategory</li>
</ol>

<div class="page-header">
  <h3>New Subcategory <small>Add new subcategory</small></h3>
</div>

<script src="js/bootstrap-filestyle.min.js"></script>
<script>
$(function(){
$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});
});
</script>

<script type="text/javascript" src="js/jquery.form.js"></script>

<script>
$(document).ready(function()
{
    $('#categoryForm').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output").html('<div class="alert alert-info" role="alert">Submitting.. Please wait..</div>');
		
        $(this).ajaxSubmit({
        target: '#output',
        success:  afterSuccess //call function after success
        });
    });
});
 
function afterSuccess()
{	
	 
    $('#submitButton').removeAttr('disabled'); //enable submit button
   
}
</script>

<section class="col-md-8">

<div class="panel panel-default">

    <div class="panel-body">

<div id="output"></div>

<form id="categoryForm" action="submit_subcategory.php" method="post">

 <div class="form-group">
                  <label for="inputCategory">Parent Category</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputCategory" name="inputCategory">
                      <option value="">Select Parent Category</option>
                      <?php
if($SelectCategories = $mysqli->query("SELECT cat_id, category FROM categories WHERE parent_id=0")){

    while($categoryRow = mysqli_fetch_array($SelectCategories)){
				
?>
                      <option value="<?php echo $categoryRow['cat_id'];?>"><?php echo $categoryRow['category'];?></option>
                      <?php

}

	$SelectCategories->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>
                    </select>
                  </div>
                </div>

<div class="form-group">
        <label for="inputTitle">Category</label>
    <div class="input-group">
         <span class="input-group-addon"><span class="glyphicon fa  fa-info"></span></span>
      <input type="text" id="inputTitle" name="inputTitle" class="form-control" placeholder="Enter your new category" >
    </div>
</div>


<div class="form-group">
<label for="inputDescription">Category Description</label>
<textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Enter description for your new category"></textarea>
</div>


</div><!-- panel body -->

<div class="panel-footer clearfix">

<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Submit</button>

</div><!--panel-footer clearfix-->

</form>


</div><!--panel panel-default-->  

</section>

</section><!--col-md-10-->

<?php include("footer.php");?>