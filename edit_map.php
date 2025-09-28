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

<?php 

$id = $mysqli->escape_string($_GET['id']);


if($Biz = $mysqli->query("SELECT * FROM business WHERE biz_id='$id'")){
	
	$BizRow = mysqli_fetch_array($Biz);
	
	$add1 = $BizRow['address_1'];
	$add2 = $BizRow['address_2'];
	$City = stripslashes($BizRow['city']);
	
	$Latitude = stripslashes($BizRow['latitude']);
	$Longitude = stripslashes($BizRow['longitude']);
	
	$Biz->close();
	
}else{
    
	 printf("There Seems to be an issue");
}

if(empty($Latitude)){
$add = urlencode($add1.", ".$add2);
$city = urlencode($BizRow['city']);
//$state = urlencode($BizRow['state']);
$country  = urlencode($Settings['county']);
$zip = urlencode($Settings['zip']);;

$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$add.',+'.$city.',+'.$country.'&sensor=false');

$output= json_decode($geocode); //Store values in variable

if($output->status == 'OK'){ // Check if address is available or not
$lat = $output->results[0]->geometry->location->lat; //Returns Latitude
$long = $output->results[0]->geometry->location->lng; // Returns Longitude
}
}else{

$lat = $Latitude;
$long = $Longitude; 	
	
}


?>

      <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Edit Map/Location</h1>
      </div>
      <div class="col-desc">
      
      <p class="note">We admit that we are humans. Sometime we make mistakes. Our auto generated map not always 100% accurate. If the map is wrong you can update it by dragging the marker to correct location.</p>
      
      <div id="output"></div>
      
      <div id="map-big"></div>
<div class="controls">
  <button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Update</button>
</div>


<script src="http://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false"></script>
<script>
///////////////////////
// Ajax / upload part
$(document).ready(function() {
  // initialize Google Maps
  initialize();
  // save marker to database
  $('#submitButton').click(function() {
    // we read the position of the marker and send it via AJAX
    var position = marker.getPosition();
    $.ajax({
      url: 'update_map.php',
      type: 'post',
      data: {
        lat: position.lat(),
        lng: position.lng(),
		id : <?php echo $id;?>
      },
      success: function(response) {
        // we print the INSERT query to #display
        $('#output').html(response);
      }
    });
  });

});

///////////////////////
//Google Maps part
var map = null;
var marker = null;

// Google Maps
function initialize() {
  var startDragPosition = null;
  var mapOptions = {
    zoom: 15,
    center: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>),  // Over Belgium
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };
  map = new google.maps.Map(document.getElementById('map-big'), mapOptions);
  // set the new marker
  marker = new google.maps.Marker({
    position: new google.maps.LatLng(<?php echo $lat;?>, <?php echo $long;?>),
    map: map,
    draggable: true
  });

  var myGeocoder = new google.maps.Geocoder();

  // set a callback for the start and end of dragging
  google.maps.event.addListener(marker,'dragstart',function(event) {
    // we remember the position from which the marker started.  
    // If the marker is dropped in an other country, we will set the marker back to this position
    startDragPosition = marker.getPosition();
  });
  google.maps.event.addListener(marker,'dragend',function(event) {
    // now we have to see if the country is the right country.  
    myGeocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK && results[1]) {
        var countryMarker = addresComponent('country', results[1], true);
        
      }
      else {
        // geocoder didn't find anything.  So let's presume the position is invalid
        marker.setPosition(startDragPosition);
      }
    });
  });
}

function addresComponent(type, geocodeResponse, shortName) {
  for(var i=0; i < geocodeResponse.address_components.length; i++) {
    for (var j=0; j < geocodeResponse.address_components[i].types.length; j++) {
      if (geocodeResponse.address_components[i].types[j] == type) {
        if (shortName) {
          return geocodeResponse.address_components[i].short_name;
        }
        else {
          return geocodeResponse.address_components[i].long_name;
        }
      }
    }
  }
  return '';
}
</script>
    
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
  
<?php } include("footer.php");?>