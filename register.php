<?php include("header.php");?>


<div class="container container-main">

<div class="col-md-8">

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#RegisterForm').on('submit', function(e)
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
</script>

      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Register</h1>
      </div>
      <div class="col-desc">

                      <div id="output"></div>
                          <form id="RegisterForm" class="forms" action="submit_register.php" method="post">
                              <div class="form-group">
            <label for="inputUsername">Username</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
		<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Desired Username">
			</div>
			</div>
            
             <div class="form-group">
            <label for="inputEmail">Email</label>
                <div class="input-group">
                   <span class="input-group-addon">@</span>
<input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Your Email Adress">
</div>
</div>

<div class="form-group">
            <label for="inputPassword">Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Enter a Strong Password">
</div>
</div>

<div class="form-group">
            <label for="inputConfirmPassword">Confirm Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputConfirmPassword" id="inputConfirmPassword" placeholder="Re-Type Password">
</div>
</div>         

                                         <button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Register</button>
                          </form>
  </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
      
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

<?php include("footer.php");?>