<?php
include("header_business.php");

/* ==== BLINDAGEM IMEDIATA PARA EVITAR WARNINGS ==== */
$BizRow = (is_array($BizRow)) ? $BizRow : [];
$BizRow += [
  'business_name'  => '',
  'address_1'      => '',
  'address_2'      => '',
  'phone'          => '',
  'city'           => '',
  'website'        => '',
  'email'          => '',
  'menu'           => '',
  'facebook'       => '',
  'twitter'        => '',
  'pinterest'      => '',
  'tags'           => '',
  'biz_user'       => 0,
  'latitude'       => '',
  'longitude'      => '',
  'cid'            => 0,
  'featured_image' => '',
  'avg'            => 0,
  'reviews'        => 0,
  'bookmarks'      => 0,
  'unique_biz'     => '',
  'star1'          => 0,
  'star2'          => 0,
  'star3'          => 0,
  'star4'          => 0,
  'star5'          => 0,
  'tot'            => 0,
  'biz_id'         => 0,
];

$Settings = isset($Settings) && is_array($Settings) ? $Settings : [];
$Settings += [
  'county' => '',
  'zip'    => '',
  'template' => 'default',
];

$BizName = isset($BizName) ? $BizName : ($BizRow['business_name'] ?? '');
$id      = isset($id) ? (int)$id : (int)($BizRow['biz_id'] ?? 0);
$UserId  = isset($UserId) ? (int)$UserId : 0;
/* ================================================ */

$add1 = $BizRow['address_1'];
$add2 = $BizRow['address_2'];

$Tel = stripslashes($BizRow['phone']);
$City = stripslashes($BizRow['city']);
$Site = stripslashes($BizRow['website']);
$Email = stripslashes($BizRow['email']);
$Menu  = stripslashes($BizRow['menu']);
$Facebook  = stripslashes($BizRow['facebook']);
$Twitter  = stripslashes($BizRow['twitter']);
$Pinterest  = stripslashes($BizRow['pinterest']);
$Tags  = stripslashes($BizRow['tags']);
$BizUid  = (int)$BizRow['biz_user'];

$BizLink = preg_replace("![^a-z0-9]+!i", "-", $BizName);
$BizLink = urlencode(strtolower($BizLink)); // (ajuste: usar o slug gerado acima)
$bizLink = urlencode(strtolower($BizName)); // mantém a variável original usada no HTML

$Latitude = trim((string)$BizRow['latitude']);
$Longitude = trim((string)$BizRow['longitude']);

$Category = (int)$BizRow['cid'];

$Telephone = !empty($Tel) ? $Tel : "N/A";

$add = urlencode(trim($add1 . (empty($add2) ? '' : ", ".$add2)));
$city = urlencode($BizRow['city']);
$country  = urlencode($Settings['county']);
$zip = urlencode($Settings['zip']);

$lat = $Latitude;
$long = $Longitude;

if ($lat === '' || $long === '') {
    // fallback geocode somente se tiver endereço mínimo
    $queryAddr = $add;
    if (!empty($city)) $queryAddr .= ',+'.$city;
    if (!empty($country)) $queryAddr .= ',+'.$country;

    if (!empty($queryAddr)) {
        $geocode = @file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$queryAddr.'&sensor=false');
        $output = $geocode ? json_decode($geocode) : null;
        if ($output && isset($output->status) && $output->status === 'OK') {
            $lat = (string)$output->results[0]->geometry->location->lat;
            $long = (string)$output->results[0]->geometry->location->lng;
        }
    }
}
// se ainda vazio, define um centro padrão (não quebra o mapa)
if ($lat === '' || $long === '') {
    $lat = '0';
    $long = '0';
}

//Get Review Count
$NumReviews = 0;
if ($ReviewsCount = @$mysqli->query("SELECT COUNT(*) AS c FROM reviews WHERE rev_active=1 AND b_id='$id'")) {
    $rcRow = $ReviewsCount->fetch_assoc();
    $NumReviews = (int)($rcRow['c'] ?? 0);
    $ReviewsCount->close();
}

//Get Opening time today
$Today = date("D");
$UniqId = stripslashes($BizRow['unique_biz']);

$OpenTime = '';
$TodayTime = ['open_from'=>'', 'open_till'=>''];
if (!empty($UniqId)) {
    if ($GetToday = @$mysqli->query("SELECT * FROM hours WHERE unique_hours='".$mysqli->real_escape_string($UniqId)."' AND day='".$mysqli->real_escape_string($Today)."' LIMIT 1")) {
        $TodayTime = $GetToday->fetch_assoc() ?: $TodayTime;
        $OpenTime = $TodayTime ? $Today : '';
        $GetToday->close();
    }
}
?>
<script src="js/jquery.form.js"></script>
<script>
$(function(){
  $('#rate-biz').raty({readOnly: true, score:<?php echo (float)$BizRow['avg'];?>});
});

$(document).ready(function()
{
    $('#SubmitForm').on('submit', function(e)
    {
        e.preventDefault();
        $('#submitButton').attr('disabled', ''); // disable upload button
        //show uploading message
        $("#output").html('<div class="alert alert-info" role="alert">Working.. Please wait..</div>');
		
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

$(function() {
  $(".bookmarks").click(function() 
  {
    var id = $(this).data("id");
    var name = $(this).data("name");
    var dataString = 'id='+ id ;
    var parent = $(this);

    if (name=='bookmarks')
    {
      $(this).fadeIn(200).html;
      $.ajax({
        type: "POST",
        url: "save_bookmarks.php",
        data: dataString,
        cache: false,
        success: function(html)
        {
          parent.html(html);
        }
      });
    }
    return false;
  });
});
</script>

<div class="container container-biz">
  <div class="col-md-8">
    <div class="col-shadow">
      <div class="biz-title">
        <h1><?php echo htmlspecialchars($BizRow['business_name']);?></h1>
        <span><span class="fa fa-home"></span> <?php echo htmlspecialchars($add1); echo (!empty($add1)?", ":""); if(!empty($add2)){ echo htmlspecialchars($add2).", "; } echo htmlspecialchars($City)?></span> </div>
      <!--biz-title--> 
      <?php
        $feat = trim($BizRow['featured_image']);
        $imgSrc = !empty($feat)
          ? "http://{$SiteLink}/uploads/{$feat}"
          : "http://{$SiteLink}/images/placeholder.png";
      ?>
      <img class="img-responsive" src="thumbs.php?src=<?php echo urlencode($imgSrc); ?>&amp;h=400&amp;w=900&amp;q=100" alt="<?php echo htmlspecialchars($BizRow['business_name']);?>">
      <div class="col-desc col-boader">
        <a class="social-buttons btn-fb" href="javascript:void(0);" onclick="popup('http://www.facebook.com/share.php?u=http://<?php echo $SiteLink;?>/business-<?php echo $BizRow['biz_id'];?>-<?php echo $BizLink;?>&amp;title=<?php echo urlencode(ucfirst($BizName));?>')"><span class="fa fa-facebook"></span> Facebook</a>
        <a class="social-buttons btn-tweet" href="javascript:void(0);" onclick="popup('http://twitter.com/home?status=<?php echo urlencode(ucfirst($BizName));?>+http://<?php echo $SiteLink;?>/business-<?php echo $BizRow['biz_id'];?>-<?php echo $BizLink;?>')"><span class="fa fa-twitter"></span> Twitter</a>
        <a class="social-buttons btn-pinit" href="javascript:void(0);" onclick="popup('http://pinterest.com/pin/create/bookmarklet/?media=<?php echo urlencode($imgSrc); ?>&amp;url=http://<?php echo $SiteLink;?>/business-<?php echo $BizRow['biz_id'];?>-<?php echo $BizLink;?>&amp;is_video=false&description=<?php echo urlencode(ucfirst($BizName));?>')"><span class="fa fa-pinterest"></span> Pinterest</a>
        <?php
          $BookmarkCount = 0;
          if ($id > 0 && $UserId > 0 && ($CheckBookmarks = @$mysqli->query("SELECT 1 FROM bookmarks WHERE bizid='$id' AND user_id='$UserId' LIMIT 1"))) {
              $BookmarkCount = $CheckBookmarks->num_rows;
              $CheckBookmarks->close();
          }
          if(!isset($_SESSION['username'])){	
        ?>
          <a class="social-buttons btn-bm" href="login"><span class="fa fa-bookmark"></span> Bookmark (<?php echo (int)$BizRow['bookmarks'];?>)</a>
        <?php }else{
          $BmText = ($BookmarkCount==0) ? "Bookmark" : "Bookmarked";
        ?>
          <a class="social-buttons btn-bm bookmarks" data-id="<?php echo (int)$id;?>" data-name="bookmarks" href="#"><span class="fa fa-bookmark"></span> <?php echo $BmText;?> (<?php echo (int)$BizRow['bookmarks'];?>)</a>
        <?php } if($BizUid==$UserId){?>
          <a class="pull-right edit-link-1" href="edit_basic-<?php echo (int)$id;?>"><span class="fa fa-edit"></span> Edit</a>
        <?php }?>
      </div>
      <!--col-desc-->
      
      <div class="col-desc">
        <h2>Business Info</h2>
        <?php if(!empty($OpenTime)){?>
        <div class="info-biz-row"><span class="fa fa-clock-o"></span> Today: <?php echo htmlspecialchars($TodayTime['open_from']);?> - <?php echo htmlspecialchars($TodayTime['open_till']);?></div>
        <?php }else{?>
        <div class="info-biz-row"><span class="fa fa-clock-o"></span> Today: N/A</div>
        <?php }?>
        <div class="info-biz-row"><span class="fa fa-phone"></span> <?php echo htmlspecialchars($Telephone);?></div>
        <div class="info-biz-row"><span class="fa fa-link"></span>
          <?php if(!empty($Site)){?>
          <a href="<?php echo htmlspecialchars($Site);?>" target="_blank">Website</a>
          <?php }else{?>
          N/A
          <?php }?>
          &nbsp;&nbsp;<span class="fa fa-envelope"></span>
          <?php if(!empty($Email)){?>
          <a href="mailto:{<?php echo htmlspecialchars($Email);?>}?Subject=<?php echo urlencode($BizRow['business_name']);?>" target="_blank">Email</a>
          <?php }else{?>
          N/A
          <?php }?>
          &nbsp;&nbsp;<span class="fa fa-facebook-square"></span>
          <?php if(!empty($Facebook)){?>
          <a href="<?php echo htmlspecialchars($Facebook);?>" target="_blank">Facebook</a>
          <?php }else{?>
          N/A
          <?php }?>
          &nbsp;&nbsp;<span class="fa fa-twitter-square"></span>
          <?php if(!empty($Twitter)){?>
          <a href="<?php echo htmlspecialchars($Twitter);?>" target="_blank">Twitter</a>
          <?php }else{?>
          N/A
          <?php }?>
          &nbsp;&nbsp;<span class="fa fa-pinterest-square"></span>
          <?php if(!empty($Pinterest)){?>
          <a href="<?php echo htmlspecialchars($Pinterest);?>" target="_blank">Pinterest</a>
          <?php }else{?>
          N/A
          <?php }?>
        </div>
        <?php if(!empty($Menu)){?>
        <div class="info-biz-row"><span class="fa fa-book"></span> <a href="<?php echo htmlspecialchars($Menu);?>" target="_blank">Menu</a></div>
        <?php }?>
        <?php if(!empty($Tags)){?>
        <div class="info-biz-row"><span class="fa fa-tag"></span> Tags:
          <?php
            $arr = explode(",", $Tags);
            foreach ($arr as $TagValue) {
              $TagValue = trim($TagValue);
              if ($TagValue === '') continue;
              $ShowTags = preg_replace("![^a-z0-9]+!i", "", $TagValue);
              $ShowTags = strtolower($ShowTags);
              echo '<a href="tags-'.$ShowTags.'">'.htmlspecialchars($TagValue).'</a>&nbsp;';
            }
          ?>
        </div>
        <?php }?>
        <?php
          $CName  = isset($CName) ? $CName : ($BizRow['category'] ?? '');
          $CLink  = isset($CLink) ? $CLink : strtolower(urlencode(preg_replace("![^a-z0-9]+!i", "-", $CName)));
          $SId    = isset($SId) ? (int)$SId : (int)($BizRow['sid'] ?? 0);
        ?>
        <div class="info-biz-row"><span class="fa fa-chevron-circle-right"></span> Category:
          <a href="category-<?php echo (int)$BizRow['cid'];?>-<?php echo $CLink;?>"><?php echo htmlspecialchars($CName)." - ".(int)$SId;?></a>
          <?php
            if($SId>0){
              if($SubCat = @$mysqli->query("SELECT * FROM categories WHERE cat_id='$SId' LIMIT 1")){
                $SubCatRow = $SubCat->fetch_assoc();
                if ($SubCatRow) {
                  $SCName = $SubCatRow['category'];
                  $SCLink = preg_replace("![^a-z0-9]+!i", "-", $SCName);
                  $SCLink = urlencode($SCLink);
                  $SCLink = strtolower($SCLink);
                  echo ' / <a href="subcategory-'.(int)$SubCatRow['cat_id'].'-'.$SCLink.'">'.htmlspecialchars($SCName).'</a>';
                }
                $SubCat->close();
              }
            }
          ?>
        </div>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
    
    <div class="col-shadow">
      <div class="col-desc">
        <h2>Description</h2>
        <p><?php echo nl2br(htmlspecialchars($BizRow['description']));?></p>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
    
    <div class="col-shadow">
      <div class="col-desc">
        <h2>Photos</h2>
        <div class="row">
<?php
$NumPhotos = 0;
if(!empty($UniqId)){
  if($CountPhotos = @$mysqli->query("SELECT COUNT(*) AS c FROM galleries WHERE uniq='".$mysqli->real_escape_string($UniqId)."'")){
    $cp = $CountPhotos->fetch_assoc();
    $NumPhotos = (int)($cp['c'] ?? 0);
    $CountPhotos->close();
  }

  if($Photos = @$mysqli->query("SELECT * FROM galleries WHERE uniq='".$mysqli->real_escape_string($UniqId)."' ORDER BY img_id DESC LIMIT 4")){
    while($PhotosRow = $Photos->fetch_assoc()){
      $photo = $PhotosRow['image'];
      $photoSrc = "http://{$SiteLink}/gallery/{$photo}";
?>
          <div class="col-sm-6 col-md-3">
            <a href="<?php echo htmlspecialchars($photoSrc);?>" class="thumbnail" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php echo htmlspecialchars($BizRow['business_name']);?> Photos">
              <img class="img-responsive" src="thumbs.php?src=<?php echo urlencode($photoSrc); ?>&amp;h=400&amp;w=500&amp;q=100" alt="<?php echo htmlspecialchars($BizRow['business_name']);?> Photos">
            </a>
          </div>
<?php
    }
    $Photos->close();
  }
}
?>
        </div>
        <!--row-->
        <?php if($NumPhotos==0){?>
        <div class="col-note"> No photos added yet. Do you have photos of <?php echo htmlspecialchars($BizRow['business_name']);?>? <a href="upload_photo-<?php echo htmlspecialchars($UniqId);?>"><span class="fa fa-cloud-upload"></span> Upload</a> </div>
        <?php  } else { ?>
        <div class="col-note"><a href="photos-<?php echo htmlspecialchars($UniqId);?>">See all photos (<?php echo (int)$NumPhotos;?>)</a></div>
        <?php }?>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow-->
    
    <?php if(!empty($Ad2)){?>
    <div class="col-shadow col-ads"> <?php echo $Ad2;?> </div>
    <!--col-shadow-->
    <?php }?>
    <div id="output"></div>
    <div class="col-shadow" id="col-review-box">
      <div class="biz-title-2">
        <h1>Whould You Like to Review This?</h1>
      </div>
      <div class="col-desc">
        <?php if(isset($_SESSION['username'])){
          $TimeNow   = time();
          $RandNumber= rand(0, 9999);
          $UniqNumber= $UserId.$RandNumber.$TimeNow;		
        ?>
        <script>
$(function() {
  $('#rate-biz-2').raty({
    score: 0,
    click: function(score, evt) {
      $('#rate-msg').load("rate.php?id="+<?php echo (int)$id;?>+"&score="+score+"&uniq="+<?php echo (int)$UniqNumber;?>);
      $('#rate-msg').fadeOut(10000);
    }
  });
});
</script>
        <form id="SubmitForm" class="forms" action="submit_review.php?id=<?php echo (int)$id;?>" method="post">
          <div class="form-group">
            <label for="star-rate">Rating</label>
            <div id="rate-biz-2" class="star-rate"></div>
            <span id="rate-msg"></span>
          </div>
          <div class="form-group">
            <label for="inputReview">Your review</label>
            <textarea class="form-control" id="inputReview" name="inputReview" placeholder="Your review help others learn about great local businesses. Please don’t review this business if you received a freebie for writing this review, or if you’re contacted in any way to the owner or employees."></textarea>
          </div>
          <input type="hidden" class="form-control" name="inputUniq" id="inputUniq" value="<?php echo (int)$UniqNumber;?>">
          <button type="submit" id="submitButton" class="btn btn-danger btn-lg pull-right">Post Review</button>
        </form>
        <?php }else{?>
        <div class="col-note">Please <a href="login">login</a> or <a href="register">Register</a> to Review</div>
        <?php }?>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow--> 
    
    <script>
$(function(){
  $('.more').on("click",function(){
    var ID = $(this).attr("id");
    if(ID){
      $("#more"+ID).html('<img src="templates/<?php echo $Settings['template'];?>/images/loader.gif"/>');
      $.ajax({
        type: "POST",
        url: "data_reviews.php",
        data: "lastmsg="+ ID +"&id="+<?php echo (int)$id;?>,
        cache: false,
        success: function(html){
          $("div#display-reviews").append(html);
          $("#more"+ID).remove();
        }
      });
    }
    return false;
  });
});

$(document).ready(function(){
  $('.star-rates').raty({
    readOnly: true,
    score: function() { return $(this).attr('data-score'); }
  });
});
</script>
    <div class="col-shadow">
      <div class="biz-title-2">
        <h1>Reviews for <?php echo htmlspecialchars($BizRow['business_name']);?></h1>
      </div>
      <div class="col-desc" id="display-reviews">
        <?php
if($Reviews = @$mysqli->query("SELECT * FROM reviews LEFT JOIN users ON users.user_id=reviews.u_id WHERE reviews.u_id=users.user_id AND reviews.rev_active=1 AND reviews.b_id='$id' ORDER BY reviews.rev_id DESC LIMIT 10")){
    while($ReviewsRow = $Reviews->fetch_assoc()){
        $UserName = $ReviewsRow['username'];
        $UserLink = preg_replace("![^a-z0-9]+!i", "-", $UserName);
        $UserLink = urlencode(strtolower($UserLink));
        $UserAvatar = $ReviewsRow['avatar'];
        
        if (empty($UserAvatar)){ 
          $AvatarImg =  'http://'.$SiteLink.'/templates/'.$Settings['template'].'/images/avatar.jpg';
        } else {
          $AvatarImg =  'http://'.$SiteLink.'/avatars/'.$UserAvatar;
        }	
        
        $RewId = (int)$ReviewsRow['rev_id'];
?>
        <div class="review-box">
          <a href="profile-<?php echo (int)$ReviewsRow['user_id'];?>-<?php echo $UserLink;?>">
            <?php
              echo '<img class="img-avatar" src="thumbs.php?src='.htmlspecialchars($AvatarImg).'&amp;h=60&amp;w=60&amp;q=100" alt="'.htmlspecialchars(ucfirst($UserName)).'" />';
            ?>
          </a>
          <div class="review-heading">
            <a href="profile-<?php echo (int)$ReviewsRow['user_id'];?>-<?php echo $UserLink;?>"><?php echo htmlspecialchars(ucfirst($UserName));?></a>
            <span><?php echo htmlspecialchars($ReviewsRow['rew_date']);?></span>
            <div class="col-rate"><span class="star-rates" data-score="<?php echo htmlspecialchars($ReviewsRow['avg']);?>"></span></div>
          </div>
          <div class="review-body">
            <p><?php echo nl2br(htmlspecialchars($ReviewsRow['review']));?></p>
          </div>
        </div>
<?php
    }
    $Reviews->close();
}else{
    // silencioso
}

if($NumReviews==0){
?>
        <div class="col-note">Be the First to Review This Business</div>
<?php } if($NumReviews>10){ ?>
        <div id="more<?php echo (int)$RewId ;?>" class="morebox">
          <a href="#" class="more btn btn-lg btn-danger" id="<?php echo (int)$RewId ;?>"><span class="fa fa-chevron-down"></span> See More</a>
        </div>
<?php } ?>
      </div>
      <!--col-desc--> 
    </div>
    <!--col-shadow--> 
    
  </div>
  <!--col-md-8-->
  
  <div class="col-md-4">
    <div class="col-shadow">
      <div class="rate-title"> <span class="font-big"><?php echo (float)$BizRow['avg'];?></span>/5
        <div id="rate-biz" class="pull-biz-info"></div>
        <div class="pull-biz-info"><?php echo (int)$NumReviews;?> Reviews</div>
      </div>
      <!--rate-title-->
      <div class="col-right">
        <?php
$star1 = (int)$BizRow['star1'];
$star2 = (int)$BizRow['star2'];
$star3 = (int)$BizRow['star3'];
$star4 = (int)$BizRow['star4'];
$star5 = (int)$BizRow['star5'];
$Tot   = (int)$BizRow['tot'];

if($Tot==0){
	echo "Not Yet Rated";
}else{
  for ($i=1;$i<=5;++$i) {
    $var = "star$i";
    $count = $$var;
    $percent = $Tot > 0 ? ($count * 100 / $Tot) : 0;
?>
        <div class="progress-left"><?php echo $i;?> Stars</div>
        <div class="progress" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo (int)$count;?>">
          <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo (float)$percent;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo (float)$percent;?>%"></div>
        </div>
<?php	
  }
}
?>
      </div>
      <!--col-right--> 
    </div>
    <!--col-shadow-->
    
    <div class="col-shadow">
      <div class="right-title">
        <h1 class="pull-left">Map &amp; Location</h1>
        <?php if($BizUid==$UserId){?>
        <a class="pull-right edit-link-2" href="edit_map-<?php echo (int)$id;?>"><span class="fa fa-edit"></span> Edit</a>
        <?php }?>
      </div>
      <div class="col-right">
        <div id="map"></div>
        <div class="col-note"><a href="http://maps.google.com/maps?z=12&t=m&q=loc:<?php echo htmlspecialchars($lat);?>,<?php echo htmlspecialchars($long);?>" target="_blank"><span class="fa fa-car"></span> Get Directions</a></div>
      </div>
      <!--col-right--> 
    </div>
    <!--col-shadow-->
    
    <div class="col-shadow">
      <div class="right-title">
        <h1 class="pull-left">Hours</h1>
        <?php if($BizUid==$UserId){?>
        <a class="pull-right edit-link-2" href="edit_hours-<?php echo htmlspecialchars($UniqId);?>"><span class="fa fa-edit"></span> Edit</a>
        <?php }?>
      </div>
      <div class="col-right">
        <?php
$NumHours = 0;
if(!empty($UniqId) && ($OpenHours = @$mysqli->query("SELECT * FROM hours WHERE unique_hours='".$mysqli->real_escape_string($UniqId)."' ORDER BY FIELD(day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');"))){
    $NumHours = $OpenHours->num_rows;
    while($DisplyHours = $OpenHours->fetch_assoc()){
?>
        <div class="info-biz-row">
          <div class="col-day"><?php echo htmlspecialchars($DisplyHours['day']);?></div>
          <?php echo htmlspecialchars($DisplyHours['open_from'])." - ".htmlspecialchars($DisplyHours['open_till']);?>
        </div>
<?php
    }
    $OpenHours->close();
}
if($NumHours==0){
?>
        <div class="info-biz-row">N/A</div>
<?php } ?>
      </div>
      <!--col-right--> 
    </div>
    <!--col-shadow-->
    
    <?php if(!empty($Ad1)){?>
    <div class="col-shadow col-ads"> <?php echo $Ad1;?> </div>
    <?php } ?>
    <div class="col-shadow">
      <div class="right-title">
        <h1 class="pull-left">Related</h1>
      </div>
      <script>
$(document).ready(function(){
  $('.sidebar-rate').raty({
    readOnly: true,
    score: function() { return $(this).attr('data-score'); }
  });
});
</script>
      <?php
if($RelatedSql = @$mysqli->query("SELECT * FROM business WHERE active=1 AND cid='$Category' AND biz_id >= Round(  Rand() * ( SELECT Max( biz_id ) FROM business)) LIMIT 6")){ 
  while ($RelatedRow = $RelatedSql->fetch_assoc()){ 
    $longRelated = stripslashes($RelatedRow['business_name']);
    $strRelated = strlen ($longRelated);
    if ($strRelated > 20) {
      $RelatedTitle = substr($longRelated,0,17).'...';
    }else{
      $RelatedTitle = $longRelated;
    }
    $RelatedLink = preg_replace("![^a-z0-9]+!i", "-", $longRelated);
    $RelatedLink = urlencode(strtolower($RelatedLink));
    $relatedFeat = trim($RelatedRow['featured_image']);
    $relatedImg  = !empty($relatedFeat) ? "http://{$SiteLink}/uploads/{$relatedFeat}" : "http://{$SiteLink}/images/placeholder.png";
?>
      <div class="img-thumbs">
        <div class="right-caption span4">
          <img class="img-remove" src="thumbs.php?src=<?php echo urlencode($relatedImg); ?>&amp;h=90&amp;w=120&amp;q=100" alt="<?php echo htmlspecialchars($RelatedTitle);?>">
          <div class="col-caption">
            <a href="business-<?php echo (int)$RelatedRow['biz_id'];?>-<?php echo $RelatedLink;?>">
              <h4><?php echo htmlspecialchars($RelatedTitle);?></h4>
            </a>
            <p><span class="sidebar-rate" data-score="<?php echo htmlspecialchars($RelatedRow['avg']);?>"></span></p>
            <p><?php echo (int)$RelatedRow['reviews'];?> Reviews</p>
          </div>
        </div>
      </div>
<?php     
  }
  $RelatedSql->close();
}
?>
      <a class="pull-link" href="all"><span class="fa fa-arrow-right"></span> See All</a> 
    </div>
    <!--col-shadow--> 
    
  </div>
  <!--col-md-4--> 
  
</div>
<!--container-biz--> 
<script src="js/ekko-lightbox.min.js"></script> 
<script src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript">
$(document).ready(function () {
  // Define the latitude and longitude positions
  var latitude = parseFloat("<?php echo $lat !== '' ? $lat : '0'; ?>");
  var longitude = parseFloat("<?php echo $long !== '' ? $long : '0'; ?>");
  var latlngPos = new google.maps.LatLng(latitude, longitude);
  var myOptions = {
    zoom: 15,
    center: latlngPos,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    zoomControlOptions: {
      style: google.maps.ZoomControlStyle.LARGE
    }
  };
  map = new google.maps.Map(document.getElementById("map"), myOptions);
  var marker = new google.maps.Marker({
    position: latlngPos,
    map: map,
    title: "<?php echo htmlspecialchars($BizName);?>"
  });
});
$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
  event.preventDefault();
  $(this).ekkoLightbox();
});
</script>
<?php include("footer.php");?>
