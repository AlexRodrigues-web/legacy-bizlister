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
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-filestyle.min.js"></script> 
<script>
$(function(){
    $('#birthday').datepicker({
    format: 'yyyy/mm/dd',
    startDate: '-3y'
})
});

//Upload Profile Photo

$(document).ready(function()
{
$('#inputfile').on('change', function()
{
$("#preview").html('');
$("#output-msg").html('<div class"alert alert-info">Uploading.. Please wait..</div>');


$("#PictureForm").ajaxForm(
{
    dataType:'json',
    success:function(json){
       $('#preview').html(json.img);
       $('#output-msg').html(json.msg);
    }
}).submit();

});
});
$(function(){

$(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});

});
</script>

<?php

if($Profile = $mysqli->query("SELECT * FROM users WHERE user_id='$UserId'")){

    $ProfileRow = mysqli_fetch_array($Profile);
	
	$Gender = $ProfileRow['gender'];
	
	$Profile->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}	

?>

<div class="col-shadow">
      <div class="biz-title-2">
        <h1>Upload Profile Picture</h1>
      </div>
      <div class="col-desc">
      
     <div id="uploading"></div>
<div id="output-msg"></div>

<form action="avatar.php" method="post" name="PictureForm" id="PictureForm" enctype="multipart/form-data">
        <!-- begin image label and input -->
		<label>Image (gif, jpg, png)</span>
    </label>
		<input type="file" size="45" name="inputfile" id="inputfile" /><!-- end image label and input -->
 
      </form><!-- end form -->
      
<div id="preview"></div> 
      
      
    </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->  
 
<script>
$(document).ready(function()
{
    $('#FromProfile').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output-profile").html('<div class="alert alert-info">Submiting... Please wait...</div>');
        $(this).ajaxSubmit({
        target: '#output-profile',
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
        <h1>Update Your Info</h1>
      </div>
<div class="col-desc">

<?php

if($Profile = $mysqli->query("SELECT * FROM users WHERE user_id='$UserId'")){

    $ProfileRow = mysqli_fetch_array($Profile);
	
	$Gender = $ProfileRow['gender'];
	
	$Profile->close();
	
}else{
    
	 printf("<div class='alert alert-danger alert-pull'>There seems to be an issue. Please Trey again</div>");
}	

?>

<div id="output-profile"></div>

<form action="submit_profile.php" id="FromProfile" method="post" >

<div class="form-group">
    <label for="uName">Nickname</label>
    
    <input type="text" class="form-control" disabled="disabled" name="uName" id="uName" value="<?php echo $ProfileRow['username'];?>"/>
</div><!--/ form-group -->

<div class="form-group">
	<label for="sex">Gender</label>
    <select class="form-control" name="sex" id="sex">
   	<?php if(!empty($Gender)){?>
    <option value="<?php echo $Gender;?>"><?php echo $Gender;?></option>
    <?php }?>
    <option value="">Chose</option>
	<option value="Male">Male</option>
    <option value="Female">Female</option>
    </select>    
</div><!--/ form-group -->

<div class="form-group">    
    <label for="birthday">Birthday</label>
    
    <input type="text" class="form-control" name="birthday" id="birthday" value="<?php echo $ProfileRow['birthday'];?>" />
</div><!--/ form-group -->
<div class="form-group">    
    <label for="uEmail">Email</label>
   
    <input type="text" class="form-control" name="uEmail" id="uEmail" value="<?php echo $ProfileRow['email'];?>" placeholder="Enter a valid email address"/>
</div><!--/ form-group -->
<div class="form-group">    
    <label for="country">Country</label>
    
    <input type="text" class="form-control" name="country" id="country" value="<?php echo $ProfileRow['country'];?>" placeholder="Let us know your country"/>
</div><!--/ form-group -->
<div class="form-group">    
    <label for="about">About</label>
    
    <textarea name="about" class="form-control" cols="40" rows="5" placeholder="Tell us little bit about your self "><?php echo $ProfileRow['about'];?></textarea>
</div><!--/ form-group -->

    <button type="submit" class="btn btn-lg btn-danger pull-right" id="submitButton">Update</button>
  
</form>      
 
</div>
      <!--col-desc--> 
    </div>
    <!--col-shadow--> 

<script>
$(document).ready(function()
{
    $('#FromPassword').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#outputmsg").html('<div class="alert alert-info">Submiting... Please wait...</div>');
        $(this).ajaxSubmit({
        target: '#outputmsg',
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
        <h1>Update Password</h1>
      </div>
<div class="col-desc">

<div id="outputmsg"></div>

<form action="submit_password.php" id="FromPassword" method="post" >

<div class="form-group">
    <label for="nPassword">Current Password</label>
    <input type="password" class="form-control" name="nPassword" id="uPassword" placeholder="Please provide your current password" />
</div><!--/ form-group -->
<div class="form-group">    
     <label for="uPassword">New Password</label>
    <input type="password" class="form-control" name="uPassword" id="uPassword" placeholder="Please provide the new password" />
</div><!--/ form-group -->
<div class="form-group">    
     <label for="cPassword">Conform Password</label>
    <input type="password" class="form-control" name="cPassword" id="cPassword" placeholder="Retype the above password" />
</div><!--/ form-group -->
    
  <button type="submit" class="btn btn-lg btn-danger pull-right" id="submitButton">Update</button>
  

</form>

</div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->                  

</div><!--col-md-8-->

<div class="col-md-4">
<?php } include("side_bar.php");?>
</div><!--col-md-4-->


</div><!--container-->

<?php include("footer.php");?>