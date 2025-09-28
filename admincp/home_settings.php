<?php
// admin/home_page_settings.php
include("header.php");
?>
<section class="col-md-2">
  <?php include("left_menu.php");?>
</section>

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li>Settings</li>
  <li class="active">Home Page Settings</li>
</ol>

<div class="page-header">
  <h3>Home Page Settings <small>Update your website home page settings</small></h3>
</div>

<script src="js/bootstrap-filestyle.min.js"></script>
<script>
$(function(){
  $(":file").filestyle({iconName: "glyphicon-picture", buttonText: "Select Photo"});
});
</script>

<script type="text/javascript" src="js/jquery.form.js"></script>
<script>
$(document).ready(function(){
  $('#settingsForm').on('submit', function(e){
    e.preventDefault();
    $('#submitButton').attr('disabled', '');
    $("#output").html('<div class="alert alert-info" role="alert">Submitting.. Please wait..</div>');
    $(this).ajaxSubmit({
      target: '#output',
      success:  function(){
        $('#submitButton').removeAttr('disabled');
        // Atualiza preview depois de salvar
        var d = new Date().getTime();
        // tenta jpg e png
        $('#promoPreviewJpg').attr('src', '../images/promo.jpg?d='+d);
        $('#promoPreviewPng').attr('src', '../images/promo.png?d='+d);
      }
    });
  });
});
</script>

<section class="col-md-8">
  <div class="panel panel-default">
    <div class="panel-body">
      <?php 
      include('../db.php');
      $SettingsRow = ['home_text'=>''];
      if($SiteSettings = $mysqli->query("SELECT * FROM settings WHERE id='1' LIMIT 1")){
          if ($row = $SiteSettings->fetch_assoc()) {
              $SettingsRow = $row;
          }
          $SiteSettings->close();
      } else {
          echo "<div class='alert alert-danger alert-pull'>There seems to be an issue. Please try again.</div>";
      }
      ?>

      <div id="output"></div>

      <form id="settingsForm" action="update_home_page.php" enctype="multipart/form-data" method="post">

        <div class="form-group">
          <label for="inputfile">Home Promo Image (1990px x 545px)</label>
          <input type="file" id="inputfile" name="inputfile" class="filestyle" data-iconName="glyphicon-picture" data-buttonText="Select Promo Image" accept="image/jpeg,image/png">
          <p class="help-block">Accepted: JPG or PNG. Max ~8MB.</p>

          <div style="margin-top:10px">
            <strong>Current promo:</strong><br>
            <!-- Tentamos exibir jpg e png; o que falhar cai no placeholder -->
            <img id="promoPreviewJpg" src="../images/promo.jpg" alt="Current promo (jpg)" class="img-responsive" style="max-width:100%;margin-bottom:8px"
                 onerror="this.onerror=null; this.src='../images/placeholder.png';">
            <img id="promoPreviewPng" src="../images/promo.png" alt="Current promo (png)" class="img-responsive" style="max-width:100%"
                 onerror="this.onerror=null; this.src='../images/placeholder.png';">
          </div>
        </div>

        <div class="form-group">
          <label for="inputLineOne">Home Page Promo Text Line</label>
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon fa fa-info"></span></span>
            <input type="text" id="inputLineOne" name="inputLineOne" class="form-control" placeholder="Enter your promo text line here" 
                   value="<?php echo htmlspecialchars($SettingsRow['home_text'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>

    </div><!-- panel-body -->

    <div class="panel-footer clearfix">
      <button type="submit" id="submitButton" class="btn btn-default btn-success btn-lg pull-right">
        Update Home Page Settings
      </button>
    </div>
    </form>

  </div><!--panel-->

</section><!--col-md-8-->

</section><!--col-md-10-->

<?php include("footer.php");?>
