<?php include("header_login.php");?>

<div class="container">

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#LoginForm').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output").html('<div class="alert alert-info" role="alert">Login you on.. Please wait..</div>');
		
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
<section class="col-md-5 col-centered">


<div class="panel panel-default">

<div class="panel-heading"><h3>Login to Admin Control Panel</h3></div>

    <div class="panel-body">


<div class="the-form">


<div id="output"></div>

<form id="LoginForm" action="submit_login.php" method="post">

<div class="form-group">
            <label for="inputUsername">Username</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
<input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Username">
</div>
</div>

<div class="form-group">
            <label for="inputPassword">Password</label>
                <div class="input-group">
                   <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
<input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">
</div>
</div>
   
<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Login</button>

</form>
</div><!--the-form-->

 </div>

</div><!--panel panel-default-->

</section>
   
</div><!--container-->

<?php include("footer.php");?>