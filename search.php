<?php include("header.php");

$term = $mysqli->escape_string($_GET['term']);

$city = $mysqli->escape_string($_GET['city']);

?>

<div class="container container-main" id="display-posts">

<div class="page-title"><h1>Your Search for "<?php echo $term;?>"</h1></div>

<script>
$(document).ready(function()
{
$('.star-rates').raty({
	readOnly: true,
  score: function() {
    return $(this).attr('data-score');

  }
});
});
</script>

<?php
if($city=="all"){

$PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 AND (business.business_name LIKE '%$term%' OR business.description LIKE '%$term%' OR business.tags LIKE '%$term%') ORDER BY business.biz_id DESC LIMIT 0, 12");

}else{

$PostSql = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 AND city='$city' AND (business.business_name LIKE '%$term%' OR business.description LIKE '%$term%' OR business.tags LIKE '%$term%') ORDER BY business.biz_id DESC LIMIT 0, 12");	
	
}

$CountRows = mysqli_num_rows($PostSql);	

while ($PostRow = mysqli_fetch_array($PostSql)){
	
	$longTitle = stripslashes($PostRow['business_name']);
	$strTitle = strlen ($longTitle);
	if ($strTitle > 25) {
	$PostTitle = substr($longTitle,0,23).'...';
	}else{
	$PostTitle = $longTitle;}
	
	$PostLink = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
	$PostLink = urlencode(strtolower($PostLink));
	
	$longDescription = stripslashes($PostRow['description']);
	$strDescription = strlen ($longDescription);
	if ($strDescription > 70) {
	$Description = substr($longDescription,0,67).'...';
	}else{
	$Description = $longDescription;}
	
	$Tel = stripslashes($PostRow['phone']);
	$City = stripslashes($PostRow['city']);
	$Site = stripslashes($PostRow['website']);
	
	if(!empty($Tel)){
		$Telephone = $Tel;
	}else{
		$Telephone = "N/A";		
	}
	
	$CName = $PostRow['category'];
	$CLink = preg_replace("![^a-z0-9]+!i", "-", $CName);
	$CLink = urlencode($CLink);
	$CLink = strtolower($CLink);
	

?>

<div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
 
 <div class="grid wow fadeInUp">
 
 <a class="over-label" href="category-<?php echo $PostRow['cid'];?>-<?php echo $CLink;?>"><?php echo $PostRow['category'];?></a> 
        
        <a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><img class="img-responsive" src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=300&amp;w=500&amp;q=100" alt="<?php echo $PostTitle;?>"></a>
    
    <h2><a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><?php echo $PostTitle;?></a></h2>
    <p><?php echo $Description;?></p>
    
    <div class="post-info-bottom">
<div class="col-rate">    
<span class="star-rates"  data-score="<?php echo $PostRow['avg'];?>"></span> <?php echo $PostRow['reviews'];?> Reviews
</div>

<div class="info-row"><span class="fa fa-home"></span> <?php echo $City;?></div>
<div class="info-row"><span class="fa fa-phone"></span> <?php echo $Telephone;?></div>
<?php if(!empty($Site)){?>
<div class="info-row"><span class="fa fa-link"></span> <a href="<?php echo $Site;?>" target="_blank">Website</a></div>
<?php }else{?>
<div class="info-row"><span class="fa fa-link"></span> N/A</div>
<?php }?>
</div>
 
  </div><!-- /.grid -->  
    
</div><!-- /.col-sm-12 col-xs-12 col-md-4 col-lg-4 -->

<?php     
	}

if($CountRows==0){
?>
<div class="col-box-search">
<div class="search-title"><h3>Your search for <span class="tt-text">"<?php echo $term;?>"</span> did not produce any results</h3></div>
<ul class="search-again">
<li>Make sure all words are spelled correctly</li>
<li>Try different keywords</li>
<li>Try more general keywords</li>
</ul>
</div>
<?php }?>

</div><!--container-->

<nav id="page-nav"><a href="data_search.php?page=2&amp;term=<?php echo $term;?>&amp;city=<?php echo $city;?>"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
	<script src="js/manual-trigger.js"></script>
	
	<script>
	
	
	$('#display-posts').infinitescroll({
		navSelector  : '#page-nav',    // selector for the paged navigation 
      	nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      	itemSelector : '.col-box',     //
		loading: {
          				finishedMsg: 'End of search results.',
          				img: 'templates/<?php echo $Settings['template'];?>/images/loader.gif'
	}
	}, function(newElements, data, url){
		
		$('.star-rates').raty({
		readOnly: true,
  		score: function() {
   		 return $(this).attr('data-score');

  		}
		});
		$('.star-rates').raty('reload');	
	});	

</script>


<?php include("footer.php");?>