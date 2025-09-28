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
      <script src="js/bootstrap-tagsinput.min.js"></script> 
      <script src="js/bootstrap-filestyle.min.js"></script> 
      <script>
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
</script>

<?php 

$TimeNow			= time();
$RandNumber   		= rand(0, 9999);
$UniqNumber			= $UserId.$RandNumber.$TimeNow;	

?>

      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Basic Business info</h1>
      </div>
      <div class="col-desc">
              <div id="output"></div>
              <form id="SubmitForm" class="forms" action="submit_business.php?id=<?php echo $UniqNumber;?>" enctype="multipart/form-data" method="post">
                <div class="form-group">
                  <label for="inputBizname">Business Name</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputBizname" id="inputBizname" placeholder="Business Name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputDescription">Description</label>
                  <textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Tell us Little Bit About this Business"></textarea>
                </div>
                <div class="form-group">
                  <label for="inputLineOne">Address 1</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputLineOne" id="inputLineOne" placeholder="Address Line 1">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputLineTwo">Address 2</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputLineTwo" id="inputLineTwo" placeholder="Address Line 2">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputCity">City</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputCity" name="inputCity">
                      <option value="">Select City</option>
                      <?php
if($SelectCity = $mysqli->query("SELECT city_id, city FROM city")){

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
                    <input type="text" class="form-control" name="inputPhone" id="inputPhone" placeholder="Phone Number">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputWeb">Web Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputWeb" id="inputWeb" placeholder="Website URL">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputEmail">Email Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputEmail" id="inputEmail" placeholder="Email Address Where Customers Can Reach">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputMenu">Menu Web Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputMenu" id="inputMenu" placeholder="Web Address to Your Menu">
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
                      <option value="">Select a Category</option>
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
                  <label for="inputSubcategory">Subcategory (Optional)</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <select class="form-control" id="inputSubcategory" name="inputSubcategory">
                      <option value="">Select a Subcategory</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputFacebook">Facebook Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputFacebook" id="inputFacebook" placeholder="Web Address to Facebook Page">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputTwitter">Twitter Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputTwitter" id="inputTwitter" placeholder="Web Address to Twitter">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputPinterest">Pinterest Address</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" name="inputPinterest" id="inputPinterest" placeholder="Web Address to Pinterest">
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="inputTags">Tags (Up to 5 separated by commas)</label>
                  <div class="input-group"> <span class="input-group-addon"><span class="fa fa-info"></span></span>
                    <input type="text" class="form-control" data-role="tagsinput" name="inputTags" id="inputTags">
                  </div>
                </div>
                <button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Submit</button>
              </form>
  </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
      <script>
$(document).ready(function(){

    $('#inputCategory').on("change",function () {
        var categoryId = $(this).find('option:selected').val();
        $.ajax({
            url: "update_subcategory.php",
            type: "POST",
            data: "categoryId="+categoryId,
            success: function (response) {
                console.log(response);
                $("#inputSubcategory").html(response);
            },
        });
    }); 

});

$(document).ready(function()
{
    $('#SubmitHours').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output-1").html('<div class="alert alert-info" role="alert">Working.. Please wait..</div>');
		
        $(this).ajaxSubmit({
        target: '#output-1',
        success:  afterSuccess //call function after success
        });
    });
});
 
function afterSuccess()
{	
	 
    $('#submitButton').removeAttr('disabled'); //enable submit button
   
}

$(document).ready(function()
{
$("#SubmitHours #submitButton").prop('disabled', true);
$("#imageform #submitButton").prop('disabled', true);
});

</script>
      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Open Hours (Optional)</h1>
      </div>
      <div class="col-desc">
            
            <p class="note">You can add open hours after you submit basic info. Till you submit basic info this form will be disabled.</p>
            
            
            
              <div id="output-1"></div>
              <form id="SubmitHours" class="forms" action="submit_hours.php?id=<?php echo $UniqNumber;?>" method="post">
              <div class="row">
               <div class="input-group-2">
                <div class="col-xs-4">
                  <label for="inputDay">Day</label>
                  <select class="form-control" id="inputDay" name="inputDay">
                    <option>Mon</option>
                    <option>Tue</option>
                    <option>Wed</option>
                    <option>Thu</option>
                    <option>Fri</option>
                    <option>Sat</option>
                    <option>Sun</option>
                  </select>
                </div>
                <div class="col-xs-4">
                  <label for="inputFrom">From</label>
                  <select class="form-control" id="inputFrom" name="inputFrom">
                    <option>12.00 am (Midnight)</option>
                    <option>12.30 am</option>
                    <option>01.00 am</option>
                    <option>01.30 am</option>
                    <option>02.00 am</option>
                    <option>02.30 am</option>
                    <option>03.00 am</option>
                    <option>03.30 am</option>
                    <option>04.00 am</option>
                    <option>04.30 am</option>
                    <option>05.00 am</option>
                    <option>05.30 am</option>
                    <option>06.00 am</option>
                    <option>06.30 am</option>
                    <option>07.00 am</option>
                    <option>07.30 am</option>
                    <option>08.00 am</option>
                    <option>08.30 am</option>
                    <option selected="selected">09.00 am</option>
                    <option>09.30 am</option>
                    <option>10.00 am</option>
                    <option>10.30 am</option>
                    <option>11.00 am</option>
                    <option>11.30 am</option>
                    <option>12.00 pm (Noon)</option>
                    <option>12.30 pm</option>
                    <option>01.00 pm</option>
                    <option>01.30 pm</option>
                    <option>02.00 pm</option>
                    <option>02.30 pm</option>
                    <option>03.00 pm</option>
                    <option>03.30 pm</option>
                    <option>04.00 pm</option>
                    <option>04.30 pm</option>
                    <option>05.00 pm</option>
                    <option>05.30 pm</option>
                    <option>06.00 pm</option>
                    <option>06.30 pm</option>
                    <option>07.00 pm</option>
                    <option>07.30 pm</option>
                    <option>08.00 pm</option>
                    <option>08.30 pm</option>
                    <option>09.00 pm</option>
                    <option>09.30 pm</option>
                    <option>10.00 pm</option>
                    <option>10.30 pm</option>
                    <option>11.00 pm</option>
                    <option>11.30 pm</option>
                  </select>
                </div>
                <div class="col-xs-4">
                  <label for="inputTo">To</label>
                  <select class="form-control" id="inputTo" name="inputTo">
                    <option>12.00 am (Midnight)</option>
                    <option>12.30 am</option>
                    <option>01.00 am</option>
                    <option>01.30 am</option>
                    <option>02.00 am</option>
                    <option>02.30 am</option>
                    <option>03.00 am</option>
                    <option>03.30 am</option>
                    <option>04.00 am</option>
                    <option>04.30 am</option>
                    <option>05.00 am</option>
                    <option>05.30 am</option>
                    <option>06.00 am</option>
                    <option>06.30 am</option>
                    <option>07.00 am</option>
                    <option>07.30 am</option>
                    <option>08.00 am</option>
                    <option>08.30 am</option>
                    <option>09.00 am</option>
                    <option>09.30 am</option>
                    <option>10.00 am</option>
                    <option>10.30 am</option>
                    <option>11.00 am</option>
                    <option>11.30 am</option>
                    <option>12.00 pm (Noon)</option>
                    <option>12.30 pm</option>
                    <option>01.00 pm</option>
                    <option>01.30 pm</option>
                    <option>02.00 pm</option>
                    <option>02.30 pm</option>
                    <option>03.00 pm</option>
                    <option>03.30 pm</option>
                    <option>04.00 pm</option>
                    <option>04.30 pm</option>
                    <option selected="selected">05.00 pm</option>
                    <option>05.30 pm</option>
                    <option>06.00 pm</option>
                    <option>06.30 pm</option>
                    <option>07.00 pm</option>
                    <option>07.30 pm</option>
                    <option>08.00 pm</option>
                    <option>08.30 pm</option>
                    <option>09.00 pm</option>
                    <option>09.30 pm</option>
                    <option>10.00 pm</option>
                    <option>10.30 pm</option>
                    <option>11.00 pm</option>
                    <option>11.30 pm</option>
                  </select>
                </div>
                </div>
                </div><!--row-->
                 <button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Submit</button>
              </form>
               
  </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
      
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

</script>     
   
<div class="col-shadow">
      <div class="biz-title-2">
        <h1>Add More Photos (Optional)</h1>
      </div>
      <div class="col-desc">
      
      <p class="note">You can add more photos after you submit basic info. Till you submit basic info this form will be disabled.</p>
            
              <div id="output-gallery"></div>

<form id="imageform" action="upload_gallery.php?id=<?php echo $UniqNumber;?>" enctype="multipart/form-data" method="post">

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
    </div>
    <!--col-md-4--> 
    
  </div>
  <!--container-->
  
<?php } include("footer.php");?>