<?php include("header.php");?>

<section class="col-md-2">

<?php include("left_menu.php");?>
                    
</section><!--col-md-2-->

<section class="col-md-10">

<ol class="breadcrumb">
  <li>Admin CP</li>
  <li class="active">Dashboard</li>
</ol>

<div class="page-header">
  <h3>Dashboard <small>Your website dashboard</small></h3>
</div>

<section class="col-md-8">

<section class="col-md-6 box-space-right">

<div class="panel panel-default">

<div class="panel-heading"><h4>Business Listing Status</h4></div>

    <div class="panel-body">

<ul>

<?php
if($PostsNumber = $mysqli->query("SELECT biz_id FROM business")){

    $TotalNumber = $PostsNumber->num_rows;
  
?> 
     <li class="fa fa-align-left"><span>Total Number of Business Listings: <?php echo $TotalNumber;?></span></li>

<?php

    $PostsNumber->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($ApprovedPosts = $mysqli->query("SELECT biz_id FROM business WHERE active=1")){

    $ApprovedNumber = $ApprovedPosts->num_rows;
?>     

	<li class="fa fa-align-left"><span>Total Approved Business Listings: <?php echo $ApprovedNumber;?></span></li>

<?php

    $ApprovedPosts->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($PendingPosts = $mysqli->query("SELECT biz_id FROM business WHERE active=0")){

    $PendingNumber= $PendingPosts->num_rows;
?>      
    <li class="fa fa-align-left"><span>Total Approval Pending Business Listings: <?php echo $PendingNumber;?></span></li>
<?php

    $PendingPosts->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?> 
</ul>

</div>

</div><!--panel panel-default-->  

</section><!--col-md-6-->


<section class="col-md-6 box-space-right">

<div class="panel panel-default">

<div class="panel-heading"><h4>Review Status</h4></div>

    <div class="panel-body">

<ul>

<?php
if($ReviewNumber = $mysqli->query("SELECT rev_id FROM reviews")){

    $TotalReviewNumber = $ReviewNumber->num_rows;
  
?> 
     <li class="fa fa-comment"><span>Total Number of Reviews: <?php echo $TotalReviewNumber;?></span></li>

<?php

    $ReviewNumber->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($ApprovedReviews = $mysqli->query("SELECT rev_id FROM reviews WHERE rev_active=1")){

    $ApprovedReviewsNumber = $ApprovedReviews->num_rows;
?>     

	<li class="fa fa-comment"><span>Total Approved Reviews: <?php echo $ApprovedReviewsNumber;?></span></li>

<?php

    $ApprovedReviews->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if($PendingReviews = $mysqli->query("SELECT rev_id FROM reviews WHERE rev_active=0")){

    $PendingReviewsNumber= $PendingReviews->num_rows;
?>      
    <li class="fa  fa-comment"><span>Total Approval Pending Reviews: <?php echo $PendingReviewsNumber;?></span></li>
<?php

    $PendingReviews->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?> 
</ul>

</div>

</div><!--panel panel-default-->  

</section><!--col-md-6-->

</section><!--col-md-8-->

<section class="col-md-8 box-space-top col-li">

<div class="panel panel-default">

<div class="panel-heading"><h4>Site Status</h4></div>

    <div class="panel-body">

<ul>

<?php 
if($TotalUsers = $mysqli->query("SELECT user_id FROM users")){

    $UsersNumber = $TotalUsers->num_rows;
  
?>      
    <li class="fa fa-users"><span>Total Number of Registered Users: <?php echo $UsersNumber;?></span></li>
<?php

    $TotalUsers->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

?>

<li class="fa fa-bar-chart-o"><span>Total Site Views: <?php echo $Settings['site_views'];?></span></li> 

<?php

$url="http://".$SiteLink;
$xml = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$url);
$rank=isset($xml->SD[1]->POPULARITY)?$xml->SD[1]->POPULARITY->attributes()->TEXT:0;
//$web=(string)$xml->SD[0]->attributes()->HOST;
  
?>    
    <li class="fa fa-bar-chart-o"><span>Alexa Rank: <?php echo $rank;?></span></li>

</ul>

</div>

</div><!--panel panel-default--> 

</section><!--col-md-8-->


<section class="col-md-8 box-space-top">

<div class="panel panel-default">

<div class="panel-heading"><h4>Last 10 Approved Business Listings</h4></div>

    <div class="panel-body">

<?php

$App= $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.biz_id DESC LIMIT 10");


	$numr = mysqli_num_rows($App);
	if ($numr==0)
	{
	echo '<div class="alert alert-danger">There are no approved articles to display at this moment.</div>';
	}
	if ($numr>0)
	{
	?>
       <table class="table table-bordered">

        <thead>

            <tr>
				<th>Feat Photo</th>
                
                <th>Business Name</th>
                
                <th>Description</th>

                <th>Added On</th>
                
            </tr>

        </thead>

        <tbody>
    <?php
	}
	
	while($PostRow=mysqli_fetch_assoc($App)){
	
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
	

	$CName = $PostRow['category'];
	$CLink = preg_replace("![^a-z0-9]+!i", "-", $CName);
	$CLink = urlencode($CLink);
	$CLink = strtolower($CLink);

	
?>        

            <tr>
				<td><a href="../business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>" target="_blank"><img src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PostRow['featured_image'];?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $longTitle;?>" class="img-responsive"></a></td>
                
                <td><a href="../business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>l" target="_blank"><?php echo ucfirst($longTitle);?></a> <br/><br/> <strong>category:</strong> <a href="../category-<?php echo $PostRow['cid'];?>-<?php echo $CLink;?>" target="_blank"><?php echo $PostRow['category'];?></a></td>
                
                <td><?php echo $Description;?></td>

                <td><?php echo $PostRow['date'];?></td>

                <td>

            </tr>
<?php } ?>
    
         
        </tbody>

    </table>
    

</div>

</div><!--panel panel-default--> 

</section><!--col-md-8-->


<section class="col-md-8 box-space-top">

<div class="panel panel-default">

<div class="panel-heading"><h4>Last 10 Approval Pending Business Listings</h4></div>

    <div class="panel-body">

<?php

$Pen = $mysqli->query("SELECT * FROM business LEFT JOIN categories ON categories.cat_id=business.cid WHERE business.active=1 ORDER BY business.biz_id DESC LIMIT 10");


	$Pennumr = mysqli_num_rows($Pen);
	if ($Pennumr==0)
	{
	echo '<div class="alert alert-danger">There are no approved articles to display at this moment.</div>';
	}
	if ($Pennumr>0)
	{
	?>
       <table class="table table-bordered">

        <thead>

            <tr>
				<th>Feat Photo</th>
                
                <th>Business Name</th>
                
                <th>Description</th>

                <th>Added On</th>
                
            </tr>

        </thead>

        <tbody>
    <?php
	}
	
	while($PenRow=mysqli_fetch_assoc($Pen)){
	
	$penTitle = stripslashes($PenRow['business_name']);
	$strPenTitle = strlen ($penTitle);
	if ($strPenTitle > 25) {
	$PenPostTitle = substr($penTitle,0,23).'...';
	}else{
	$PenPostTitle = $penTitle;}
	
	$PenPostLink = preg_replace("![^a-z0-9]+!i", "-", $penTitle);
	$PenPostLink = urlencode(strtolower($PenPostLink));
	
	$longPenDescription = stripslashes($PenRow['description']);
	$strPenDescription = strlen ($longPenDescription);
	if ($strPenDescription > 70) {
	$PenDescription = substr($longPenDescription,0,67).'...';
	}else{
	$PenDescription = $longPenDescription;}
	

	$PCName = $PenRow['category'];
	$PCLink = preg_replace("![^a-z0-9]+!i", "-", $PCName);
	$PCLink = urlencode($PCLink);
	$PCLink = strtolower($PCLink);

	
?>        

            <tr>
				<td><a href="../business-<?php echo $PenRow['biz_id'];?>-<?php echo $PenPostLink;?>" target="_blank"><img src="thumbs.php?src=http://<?php echo $SiteLink;?>/uploads/<?php echo $PenRow['featured_image'];?>&amp;h=50&amp;w=50&amp;q=100" alt="<?php echo $penTitle;?>" class="img-responsive"></a></td>
                
                <td><a href="../business-<?php echo $PenRow['biz_id'];?>-<?php echo $PenPostLink;?>l" target="_blank"><?php echo ucfirst($penTitle);?></a> <br/><br/> <strong>category:</strong> <a href="../category-<?php echo $PenRow['cid'];?>-<?php echo $PCLink;?>" target="_blank"><?php echo $PenRow['category'];?></a></td>
                
                <td><?php echo $PenDescription;?></td>

                <td><?php echo $PenRow['date'];?></td>

                <td>

            </tr>
<?php } ?>
    
    
         
        </tbody>

    </table>
    

</div>

</div><!--panel panel-default--> 

</section><!--col-md-8-->

</section><!--col-md-10-->

<?php include("footer.php");?>