<?php include("header.php");?>

  <div class="container container-main">
    <div class="col-md-8"> 
     

      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Advertise</h1>
      </div>
      <div class="col-desc">
 
 <?php
if($PageSql = $mysqli->query("SELECT * FROM  pages WHERE id='4'")){

    $PageRow = mysqli_fetch_array($PageSql);
	
?>           
            <p class="note"><?php echo $PageRow['page'];?></p>

<?php	

    $PageSql->close();
	
}else{
    
	 printf("There Seems to be an issue");
}
?>           
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
  
<?php include("footer.php");?>