<?php include("header.php");?>
  <div class="container container-main">
    <div class="col-md-8">
      <div class="col-shadow">
        <div class="biz-title-2">
          <h1>Contact Us</h1>
        </div>
        <div class="col-desc"> 
          <script type="text/javascript" src="js/jquery.form.js"></script> 
          <script>
$(document).ready(function()
{
    $('#ContactForm').on('submit', function(e)
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
          <div id="output"></div>
          <form id="ContactForm" action="send_mail.php" method="post">
            <div class="form-group">
              <label for="inputYourname">Your Name</label>
              <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input type="text" class="form-control" name="inputYourname" id="inputYourname" placeholder="Your Name">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail">Email</label>
              <div class="input-group"> <span class="input-group-addon">@</span>
                <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="Your Email Adress">
              </div>
            </div>
            <div class="form-group">
              <label for="inputSubject">Subject</label>
              <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-info-sign"></span></span>
                <input type="text" class="form-control" name="inputSubject" id="inputSubject" placeholder="Subject">
              </div>
            </div>
            <div class="form-group">
              <label for="inputMessage">Message</label>
              <textarea class="form-control" id="inputMessage" name="inputMessage" rows="3" placeholder="Your Message"></textarea>
            </div>
            <button type="submit" id="submitButton" class="btn btn-lg btn-danger pull-right">Send</button>
          </form>
        </div>
        <!--col-desc--> 
      </div>
      <!--col-shadow-->
      
      <?php if(!empty($Ad2)){?>
      <div class="col-shadow col-ads"> <?php echo $Ad2;?> </div>
      <!--col-shadow-->
      <?php } ?>
    </div>
    <!--col-md-8-->
    
    <div class="col-md-4">
      <?php include("side_bar.php");?>
    </div>
    <!--col-md-4--> 
    
  </div>
  <!--container-->
  
  <?php include("footer.php");?>