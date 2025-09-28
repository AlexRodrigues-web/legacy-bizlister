<?php include("header.php");?>


<div class="container container-main">

<div class="col-md-8">

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function()
{
    $('#recoveredForm').on('submit', function(e)
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
        <h1>Recover Your Login Details</h1>
      </div>
      <div class="col-desc">
<div id="output"></div>

<form id="recoveredForm" action="send_recovery.php" method="post">

<div class="form-group">
            <label for="inputRecovery">Registered Email</label>
                <div class="input-group">
                   <span class="input-group-addon">@</span>
<input type="email" class="form-control" name="inputRecovery" id="inputRecovery" placeholder="Email">
</div>
</div>
   
<button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Reset</button>

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