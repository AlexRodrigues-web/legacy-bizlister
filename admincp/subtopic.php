<?php
include("../db.php");
 
$topicId = $_POST['topicId'];

?>

	  <option value="">Select a Related Topic</option>

<?php

if($SubSelectTopics = $mysqli->query("SELECT * FROM subtopics WHERE t_id=$topicId ORDER BY sub_topic ASC")){

    while($SubTopicRow = mysqli_fetch_array($SubSelectTopics)){
				
?>
      <option value="<?php echo $SubTopicRow['sub_id'];?>"><?php echo $SubTopicRow['sub_topic'];?></option>
<?php

}

	$SubSelectTopics->close();
	
}else{
    
	 printf("There Seems to be an issue");
}
?>

?>