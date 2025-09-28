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
      <script>
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

$(document).ready(function(){
//Delete	
$('a.btnDelete').on('click', function (e) {
    e.preventDefault();
    var id = $(this).closest('div').data('id');
    $('#myModal').data('id', id).modal('show');
});

$('#btnDelteYes').click(function () {
    var id = $('#myModal').data('id');
	var dataString = 'id='+ id ;
    $('[data-id=' + id + ']').parent().remove();
    $('#myModal').modal('hide');
	//ajax
	$.ajax({
type: "POST",
url: "delete_hours.php",
data: dataString,
cache: false,
success: function(html)
{
//$(".fav-count").html(html);
$("#output").html(html);
}
});
//ajax ends
});
});
</script>

<?php 

$id = $mysqli->escape_string($_GET['id']);

?>	
	<div class="col-shadow">
      <div class="biz-title-2">
        <h1>Manage Open Hours</h1>
      </div>
      <div class="col-desc">
      
      <div id="output"></div>
      
      <div class="row">
        

        <?php
if($OpenHours = $mysqli->query("SELECT * FROM hours WHERE unique_hours='$id' ORDER BY FIELD(day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');")){
	
	$NumHours = $OpenHours->num_rows;
				
    while($DisplyHours = mysqli_fetch_array($OpenHours)){

?>		
		<div class="input-group-2">
        	
        <div class="col-xs-4">
      	<?php echo $DisplyHours['day'];?>
        </div><!--col-xs-4-->
        
        <div class="col-xs-4">
      	
        <?php echo $DisplyHours['open_from']." - ".$DisplyHours['open_till'];?>
        
        </div><!--col-xs-4-->
        
        <div class="col-xs-4" data-id="<?php echo $DisplyHours['hour_id'];?>">
      	
        <a class="btnDelete" href="#"><span class="fa fa-remove"></span> Remove </a>
        
        </div><!--col-xs-4-->
        
         </div><!--input-group-2-->
        
        <?php
}
	$OpenHours->close();
	
}else{
    
	 printf("There Seems to be an issue");
}
?>        
    
     
      </div><!--row-->
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow--> 	

     <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Add More Open Hours</h1>
      </div>
      <div class="col-desc">
                                 
              <div id="output-1"></div>
              <form id="SubmitHours" class="forms" action="submit_hours.php?id=<?php echo $id;?>" method="post">
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
    
</div><!--col-md-8-->

  
    
    <div class="col-md-4">
      <?php include("side_bar.php");?>
    </div>
    <!--col-md-4--> 
    
  </div>
 <!--container-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Confirmation</h4>

            </div>
            <div class="modal-body">
				<p>Are you sure you want to remove this opening hour?</p>
                <p class="text-warning"><small>This action cannot be undone.</small></p>		
            </div>
            <!--/modal-body-collapse -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnDelteYes">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
            <!--/modal-footer-collapse -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
  
<?php } include("footer.php");?>