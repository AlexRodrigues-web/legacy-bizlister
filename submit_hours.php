<?php

include('db.php');

if($_POST)
{		
	
	$Day			= $mysqli->escape_string($_POST['inputDay']);
	$From			= $mysqli->escape_string($_POST['inputFrom']);
	$To		        = $mysqli->escape_string($_POST['inputTo']); 
	$Uniqid 		= $mysqli->escape_string($_GET['id']);
	
	
	
		
$mysqli->query("INSERT INTO hours(day,open_from,open_till,unique_hours) VALUES ('$Day','$From','$To','$Uniqid')") or die(mysqli_error());

//$mysqli->query("INSERT INTO hours(day, from, to, unique_hours) VALUES ('$Day','$From','$To','$Uniqid')") or die(mysqli_error());

?>

<script>
$('#AddProduct').delay(1000).resetForm(1000);
</script>

<?php		
		die('<div class="alert alert-success" role="alert">Open hour added. If you like you can add more open hours.</div>');
		
   
   }else{
   		die('<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>');
   } 

?>