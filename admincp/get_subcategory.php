<?php
include("..db.php");
 
$Subcategory = $_POST['categoryId'];

?>

	  <option value="">Select a Subcategory</option>

<?php

if($SubSelectTopics = $mysqli->query("SELECT * FROM categories WHERE parent_id='$Subcategory' ORDER BY category ASC")){

    while($SubTopicRow = mysqli_fetch_array($SubSelectTopics)){
				
?>
      <option value="<?php echo $SubTopicRow['cat_id'];?>"><?php echo $SubTopicRow['category'];?></option>
<?php

}

	$SubSelectTopics->close();
	
}else{
    
	 printf("There Seems to be an issue");
}
?>

?>