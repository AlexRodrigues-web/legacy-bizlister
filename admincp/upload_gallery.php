<?php
error_reporting(0);

include("../db.php");

$id = $mysqli->escape_string($_GET['id']);

define ("MAX_SIZE","9000"); 
function getExtension($str)
{
     $i = strrpos($str,".");
     if (!$i) { return ""; }
     $l = strlen($str) - $i;
     $ext = substr($str,$i+1,$l);
     return $ext;
}


$valid_formats = array("jpg", "png", "jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") 
{

$uploaddir = "../gallery/"; //a directory inside

$successfulUploads = 0;

foreach ($_FILES['photos']['name'] as $name => $value)
{

    $filename = stripslashes($_FILES['photos']['name'][$name]);
    $size=filesize($_FILES['photos']['tmp_name'][$name]);
    //get the extension of the file in a lower case format
    $ext = getExtension($filename);
    $ext = strtolower($ext);

    if(in_array($ext,$valid_formats)) {
        if ($size < (MAX_SIZE*1024)) {

            $image_name=time().$filename;
            $newname=$uploaddir.$image_name;

            if (move_uploaded_file($_FILES['photos']['tmp_name'][$name], $newname)) {

                $mysqli->query("INSERT INTO galleries(image,uniq) VALUES('$image_name','$id')");

                //echo "Image uploaded";

                $successfulUploads = $successfulUploads + 1;

            } else {

                echo '<div class="alert alert-danger" role="alert">There seems to be a problem. please try again.</div>';

            }

        } else {
 

           echo '<div class="alert alert-danger" role="alert">You have exceeded the size limit!</div>';

        }

    } else { 

        echo '<div class="alert alert-danger" role="alert">One or more unsupported file(s) detected! Those file(s) will be ignored. Please upload JPEG, PNG images.</div>';

    }

 }


 if($successfulUploads === count($_FILES['photos'])){
	 
    
	echo '<div class="alert alert-success" role="alert">>Photos uploaded successfully.</div>';
	
?>
<script>
$('#imageform').delay(1000).resetForm(1000);
</script>
<?php

 } else {
	 

    echo '<div class="alert alert-success" role="alert">Photos uploaded successfully.</div>';

?>
<script>
$('#imageform').delay(1000).resetForm(1000);
</script>
<?php
 }

}