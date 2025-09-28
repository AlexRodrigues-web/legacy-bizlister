<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li class="active">Settings</li>
</ol>

<div class="page-header">
  <h3>Site Settings <small>Update your website settings</small></h3>
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
    $('#settingsForm').on('submit', function(e)
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

if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $SettingsRow = mysqli_fetch_array($SiteSettings);
	
    $SiteSettings->close();
	
}else{
    
	 printf("There Seems to be an issue");
}


?>

<div id="output"></div>

<form id="settingsForm" action="update_settings.php" enctype="multipart/form-data" method="post">

<div class="form-group">
        <label for="inputTitle">Website Title</label>
    <div class="input-group">
         <span class="input-group-addon"><span class="glyphicon fa  fa-info"></span></span>
      <input type="text" id="inputTitle" name="inputTitle" class="form-control" placeholder="Enter your site title here" value="<?php echo $SettingsRow['site_title']?>">
    </div>
</div>
        
<div class="form-group">
<label for="inputfile">Website Logo (130px x 68px)</label>
<input type="file" id="inputfile" name="inputfile" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Website Logo">
</div>

<div class="form-group">
        <label for="inputSiteurl">Website URL (Without "http://" and end "/")</label>
    <div class="input-group">
         <span class="input-group-addon"><span class="glyphicon fa  fa-info"></span></span>
      <input type="text" id="inputSiteurl" name="inputSiteurl" class="form-control" placeholder="Enter your website title here" value="<?php echo $SettingsRow['site_link']?>">
    </div>
</div>

<div class="form-group">
<label for="inputDescription">Meta Description</label>
<textarea class="form-control" id="inputDescription" name="inputDescription" rows="3" placeholder="Enter a meta description for your website"><?php echo $SettingsRow['meta_description']?></textarea>
</div>

<div class="form-group">
<label for="inputKeywords">Meta Keywords</label>
<textarea class="form-control" id="inputKeywords" name="inputKeywords" rows="3" placeholder="Enter a meta keywords for your website"><?php echo $SettingsRow['meta_keywords']?></textarea>
</div>

<div class="form-group">
        <label for="inputEmail">Email Address</label>
    <div class="input-group">
         <span class="input-group-addon">@</span>
      <input type="text" id="inputEmail" name="inputEmail" class="form-control" placeholder="Enter your website email address" value="<?php echo $SettingsRow['site_email']?>">
    </div>
</div>


<div class="row">
			<div class="col-xs-6">
            <label for="inputCountry">Country</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-map-marker"></span></span>
                    <input type="text" id="inputCountry" name="inputCountry" class="form-control" placeholder="Enter the country that you accept listings" value="<?php echo $SettingsRow['county']?>">
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputZip">Country or Zip Code</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-map-marker"></span></span>
                    <input type="text" id="inputZip" name="inputZip" class="form-control" placeholder="Enter the country or zip code (Ex: 01)" value="<?php echo $SettingsRow['zip']?>">
                </div>
            </div>

            <div class="col-xs-6">
            <label for="inputFbapp">Facebook App ID</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-facebook-square"></span></span>
                    <input type="text" id="inputFbapp" name="inputFbapp" class="form-control" placeholder="Enter your facebook app id" value="<?php echo $SettingsRow['fb_app_id']?>">
                </div>
            </div>
                    
            <div class="col-xs-6">
            <label for="inputFbpage">Facebook Page URL</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-facebook"></span></span>
                    <input type="text" id="inputFbpage" name="inputFbpage" class="form-control" placeholder="Facebook page url" value="<?php echo $SettingsRow['fb_page']?>">
                </div>
            </div>
            
            
             <div class="col-xs-6">
            <label for="inputTwitter">Twitter URL</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-twitter"></span></span>
                    <input type="text" id="inputTwitter" name="inputTwitter" class="form-control" placeholder="Twitter url" value="<?php echo $SettingsRow['twitter_link']?>">
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputPinterest">Pinterest URL</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-pinterest"></span></span>
                    <input type="text" id="inputPinterest" name="inputPinterest" class="form-control" placeholder="Pinterest URL" value="<?php echo $SettingsRow['pinterest_link']?>">
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputGoogle">Google+ URL</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-google-plus"></span></span>
                    <input type="text" id="inputGoogle" name="inputGoogle" class="form-control" placeholder="Google+ URL" value="<?php echo $SettingsRow['google_pluse_link']?>">
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputApprove">Auto Approve Listings</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-check"></span></span>
                    <select class="form-control" id="inputApprove" name="inputApprove">
 					 <?php if ($SettingsRow['active']==1){?>
					<option value="1">ON</option>
					<option value="0">OFF</option>
					<?php }else{?>
					<option value="0">OFF</option>
					<option value="1">ON</option>
					<?php }?>
					</select>
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputReviews">Auto Approve Reviews</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-check"></span></span>
                    <select class="form-control" id="inputReviews" name="inputReviews">
 					 <?php if ($SettingsRow['rev_active']==1){?>
					<option value="1">ON</option>
					<option value="0">OFF</option>
					<?php }else{?>
					<option value="0">OFF</option>
					<option value="1">ON</option>
					<?php }?>
					</select>
                </div>
            </div>
            
            <div class="col-xs-6">
            <label for="inputTemplate">Template</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-desktop"></span></span>
                    <select class="form-control" id="inputTemplate" name="inputTemplate">
 					 <option value="<?php echo $SettingsRow['template'];?>"><?php echo ucfirst($SettingsRow['template']);?></option>
				<?php
				foreach(glob('../templates/*', GLOB_ONLYDIR) as $dir) {
				$TemplateDir = substr($dir, 13);
				$TemplateName = ucfirst($TemplateDir)
				?>
			<option value="<?php echo $TemplateDir;?>"><?php echo $TemplateName;?></option>
				<?php }?>
					</select>
                </div>

            </div>
            
			</div><!--row-->

</div><!-- panel body -->

<div class="panel-footer clearfix">

<button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">Update Site Settings</button>

</div><!--panel-footer clearfix-->

</form>


</div><!--panel panel-default-->  

</section>

</section><!--col-md-10-->

<?php include("footer.php");?>