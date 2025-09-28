<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Business</li>
  <li>Manage Business</li>
  <li class="active">Edit Business</li>
</ol>

<div class="page-header">
  <h3>Edit Business <small>Edit/update business</small></h3>
</div>

<?php

$id = $mysqli->escape_string($_GET['id']);


if($Biz = $mysqli->query("SELECT * FROM business WHERE biz_id='$id'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$City = stripslashes($BizRow['city']);
	
	$CatId = stripslashes($BizRow['cid']);
	
	$SubCat = stripslashes($BizRow['sid']);
	
	$Uniq = stripslashes($BizRow['unique_biz']);
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


if($SelectedCat = $mysqli->query("SELECT cat_id, category FROM categories WHERE cat_id='$CatId'")){

    $SelectedRow = mysqli_fetch_array($SelectedCat);	

	$SelectedCat->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($SelectedSubCat = $mysqli->query("SELECT cat_id, category FROM categories WHERE cat_id='$SubCat'")){

    $SelectedSubRow = mysqli_fetch_array($SelectedSubCat);
	
	$GetSubId = $SelectedSubRow['cat_id'];
	
	$GetSubName = $SelectedSubRow['category'];	

	$SelectedSubCat->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


?>  

<script type="text/javascript" src="js/jquery.form.js"></script>
<script src="js/bootstrap-tagsinput.min.js"></script>
<script src="js/bootstrap-filestyle.min.js"></script>

<script type="text/javascript" src="js/jquery.form.js"></script>
<link href="//oss.maxcdn.com/summernote/0.5.1/summernote.css" rel="stylesheet">
<script type="text/javascript">

$(document).ready(function()
{
    $('#SubmitForm').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output").html('<div class="alert alert-info" role="alert">Working.. Please wait..</div>');
		
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

$(function(){

$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});

});

$(document).ready(function(){

    $('#inputCategory').on("change",function () {
        var categoryId = $(this).find('option:selected').val();
        $.ajax({
            url: "get_subcategory.php",
            type: "POST",
            data: "categoryId="+categoryId,
            success: function (response) {
                console.log(response);
                $("#inputSubcategory").html(response);
            },
        });
    }); 

});
</script>

<section class="col-md-8">

<div class="panel panel-default">

    <div class="panel-body">
    
 

<div id="output"></div>

<form id="SubmitForm" class="forms" action="update_business.php?id=<?php echo $id;?>" enctype="multipart/form-data" method="post">
                <div class="form-group">
                  <label for="inputBizname">Business Name</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputBizname" id="inputBizname" placeholder="Business Name" value="<?php echo stripslashes($BizRow['business_name']);?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputDescription">Description</label>
                  <textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Tell us Little Bit About this Business"><?php echo stripslashes($BizRow['description']);?></textarea>
                </div>
                <div class="form-group">
                  <label for="inputLineOne">Address 1</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputLineOne" id="inputLineOne" placeholder="Address Line 1" value="<?php echo stripslashes($BizRow['address_1']);?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputLineTwo">Address 2</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputLineTwo" id="inputLineTwo" placeholder="Address Line 2" value="<?php echo stripslashes($BizRow['address_2']);?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputCity">City</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputCity" name="inputCity">
                      
                      <option value="<?php echo $City;?>"><?php echo $City;?></option>	
                      <option value="">Change City</option>
                      <?php
if($SelectCity = $mysqli->query("SELECT city_id, city FROM city WHERE city!='$City'")){

    while($CityRow = mysqli_fetch_array($SelectCity)){
				
?>
                      <option value="<?php echo $CityRow['city'];?>"><?php echo $CityRow['city'];?></option>
                      <?php

}

	$SelectCity->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPhone">Phone</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputPhone" id="inputPhone" placeholder="Phone Number" value="<?php echo stripslashes($BizRow['phone']);?>">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputWeb">Web Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputWeb" id="inputWeb" placeholder="Website URL" value="<?php echo stripslashes($BizRow['website']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail">Email Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email Address Where Customers Can Reach" value="<?php echo stripslashes($BizRow['email']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputMenu">Menu Web Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputMenu" id="inputMenu" placeholder="Web Address to Your Menu" value="<?php echo stripslashes($BizRow['menu']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputImage">Featured Image</label>
                  <input type="file" name="inputImage" id="inputImage" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Image">
                </div>
                <div class="form-group">
                  <label for="inputCategory">Category</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputCategory" name="inputCategory">
                      <option value="<?php echo $SelectedRow['cat_id'];?>"><?php echo $SelectedRow['category'];?></option>
                      <option value="">Change Category</option>
                      <?php
if($SelectCategories = $mysqli->query("SELECT cat_id, category FROM categories WHERE parent_id=0 AND cat_id!='$CatId'")){

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
                  <label for="inputSubcategory">Subcategory (Optional)</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputSubcategory" name="inputSubcategory">
                <?php if (!empty($GetSubId)){?>    
                    
                    <option value="<?php echo $GetSubId;?>"><?php echo $GetSubName;?></option>
<?php  } ?>
				<option value="">Change Subcategory</option>
                
<?php      
        
if($SelectSub = $mysqli->query("SELECT cat_id, category FROM categories WHERE parent_id='$CatId' AND cat_id!='$SubCat '")){

    while($SubRow = mysqli_fetch_array($SelectSub)){
				
?>
                    <option value="<?php echo $SubRow['cat_id'];?>"><?php echo $SubRow['category'];?></option>
<?php }

	$SelectSub->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>                   </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputFacebook">Facebook Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputFacebook" id="inputFacebook" placeholder="Web Address to Facebook Page" value="<?php echo stripslashes($BizRow['facebook']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputTwitter">Twitter Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputTwitter" id="inputTwitter" placeholder="Web Address to Twitter" value="<?php echo stripslashes($BizRow['twitter']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputPinterest">Pinterest Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputPinterest" id="inputPinterest" placeholder="Web Address to Pinterest" value="<?php echo stripslashes($BizRow['pinterest']);?>">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputTags">Tags (Up to 5 separated by commas)</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" data-role="tagsinput" name="inputTags" id="inputTags" value="<?php echo stripslashes($BizRow['tags']);?>">
                  </div>
                </div>
                <button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Update</button>
              </form>

</div><!--the-form-->


</div><!--panel panel-default--> 


<div class="panel panel-default">

<div class="panel-body">

<p><a href="edit_hours.php?id=<?php echo $Uniq;?>">Click here</a> to edit opening hours of this business.</p>
<p><a href="upload_photo.php?id=<?php echo $Uniq;?>">Click here</a> to add more photos of this business.</a></p>
<p><a href="manage_photos.php?id=<?php echo $Uniq;?>">Click here</a> to manage photos of this business.</p>

</div><!--panel panel-default-->


</div><!--panel-default-->

</section>

</section><!--col-md-10-->

<?php include("footer.php");?>