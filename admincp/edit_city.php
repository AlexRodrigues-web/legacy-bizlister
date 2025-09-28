<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Categories</li>
  <li>Manage Cities</li>
  <li class="active">Edit City</li>
</ol>

<div class="page-header">
  <h3>Edit City <small>Edit cities</small></h3>
</div>

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
    
<?php

$id = $mysqli->escape_string($_GET['id']); 

if($City = $mysqli->query("SELECT * FROM city WHERE city_id='$id'")){

    $CityRow = mysqli_fetch_array($City);
	
    $City->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


?>    

<div id="output"></div>

<form id="categoryForm" action="update_city.php?id=<?php echo $id;?>" method="post">

<div class="form-group">
        <label for="inputCity">City</label>
    <div class="input-group">
         <span class="input-group-addon"><span class="glyphicon fa  fa-info"></span></span>
      <input type="text" id="inputCity" name="inputCity" class="form-control" placeholder="Enter city name Ex: New York" value="<?php echo $CityRow['city'];?>">
    </div>
</div>


</div><!-- panel body -->

<div class="panel-footer clearfix">

<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Update City</button>

</div><!--panel-footer clearfix-->

</form>


</div><!--panel panel-default-->  

</section>

</section><!--col-md-10-->

<?php include("footer.php");?>