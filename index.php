<?php
/****************************************************
 * index.php  (HOME) — COM ERROR LOG + PLACEHOLDER
 * - Usa helpers de imagem do header_home.php (biz_thumb_src)
 * - Garante thumbs corretas em Featured e Latest
 ****************************************************/

/* =============== ERROR LOG =============== */
error_reporting(E_ALL);
ini_set('display_errors', 0);
$__logDir  = __DIR__ . DIRECTORY_SEPARATOR . 'logs';
$__logFile = $__logDir . DIRECTORY_SEPARATOR . 'php_errors.log';
if (!is_dir($__logDir)) { @mkdir($__logDir, 0777, true); }
if (!is_dir($__logDir)) { $__logFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bizlister_php_errors.log'; }
ini_set('log_errors', 1);
ini_set('error_log', $__logFile);
error_log("===== [index.php] request: " . ($_SERVER['REQUEST_URI'] ?? '') . " =====");

/*
  IMPORTANTE:
  header_home.php já:
  - inicia sessão e inclui db.php
  - carrega $Settings/$SiteLink/$Template
  - define helpers de imagem: biz_thumb_src()
*/
include("header_home.php");
?>

<div class="promo">
  <div class="container">
    <div class="front-search">

      <h1><?php echo htmlspecialchars((string)($Settings['home_text'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></h1>

      <form role="search" method="get" action="search.php">
        <div class="form-group">
          <div class="col-md-6">
            <input type="text" class="form-control input-lg" id="term" name="term" placeholder="Search">
          </div>
          <div class="col-md-6">
            <select class="form-control input-lg" id="city" name="city">
              <option value="all">All Cities</option>
              <?php
              if($SelectCity = @$mysqli->query("SELECT city_id, city FROM city")){
                while($CityRow = $SelectCity->fetch_assoc()){
                  $city = (string)($CityRow['city'] ?? '');
                  echo '<option value="'.htmlspecialchars($city, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($city, ENT_QUOTES, 'UTF-8').'</option>';
                }
                $SelectCity->close();
              } else {
                error_log('[index.php] Falha ao carregar cidades.');
                // silencioso na UI
              }
              ?>
            </select>
          </div>
        </div>
        <div class="front-submit">
          <button type="submit" class="btn btn-lg btn-danger"><i class="glyphicon glyphicon-search"></i> Search</button>
        </div>
      </form>

    </div><!--front-search-->
  </div><!--container-->
</div><!--promo-->

<script>
$(document).ready(function(){
  $('.star-rates').raty({
    readOnly: true,
    score: function(){ return $(this).attr('data-score'); }
  });
});
</script>

<div class="container">

  <div class="page-title"><h1>Featured Businesses</h1></div>

  <?php
  $CountFeat = 0;
  $sqlFeat = "
    SELECT business.*, categories.category
    FROM business
    LEFT JOIN categories ON categories.cat_id = business.cid
    WHERE business.active = 1 AND business.feat = 1
    ORDER BY business.biz_id DESC
    LIMIT 6
  ";
  if($FeatSql = @$mysqli->query($sqlFeat)){
    $CountFeat = $FeatSql->num_rows;

    while ($FeatRow = $FeatSql->fetch_assoc()){

      $longFeat   = stripslashes((string)$FeatRow['business_name']);
      $FeatTitle  = (mb_strlen($longFeat) > 25) ? mb_substr($longFeat, 0, 23) . '...' : $longFeat;
      $FeatSlug   = preg_replace("![^a-z0-9]+!i", "-", $longFeat);
      $FeatLink   = urlencode(strtolower($FeatSlug));

      $FeatDesc   = stripslashes((string)$FeatRow['description']);
      $feDescription = (mb_strlen($FeatDesc) > 70) ? mb_substr($FeatDesc, 0, 67) . '...' : $FeatDesc;

      $FeatTel    = stripslashes((string)$FeatRow['phone']);
      $FetCity    = stripslashes((string)$FeatRow['city']);
      $FeatSite   = stripslashes((string)$FeatRow['website']);
      $FeatPhone  = !empty($FeatTel) ? $FeatTel : 'N/A';

      $FCName   = (string)$FeatRow['category'];
      $FCLink   = strtolower(urlencode(preg_replace("![^a-z0-9]+!i", "-", $FCName)));

      // IMAGEM via helper -> placeholder garantido
      $featFile = isset($FeatRow['featured_image']) ? trim((string)$FeatRow['featured_image']) : '';
      $imgSrc   = biz_thumb_src($featFile, 300, 500, 100, (int)$FeatRow['biz_id']);
  ?>
    <div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
      <div class="grid wow fadeInUp">
        <a class="over-label" href="category-<?php echo (int)$FeatRow['cid']; ?>-<?php echo $FCLink; ?>">
          <?php echo htmlspecialchars($FCName, ENT_QUOTES, 'UTF-8'); ?>
        </a>

        <a href="business-<?php echo (int)$FeatRow['biz_id']; ?>-<?php echo $FeatLink; ?>">
          <img class="img-responsive"
               src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
               alt="<?php echo htmlspecialchars($FeatTitle, ENT_QUOTES, 'UTF-8'); ?>">
        </a>

        <h2>
          <a href="business-<?php echo (int)$FeatRow['biz_id']; ?>-<?php echo $FeatLink; ?>">
            <?php echo htmlspecialchars($FeatTitle, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </h2>

        <p><?php echo htmlspecialchars($feDescription, ENT_QUOTES, 'UTF-8'); ?></p>

        <div class="post-info-bottom">
          <div class="col-rate">
            <span class="star-rates" data-score="<?php echo (int)($FeatRow['avg'] ?? 0); ?>"></span>
            <?php echo (int)($FeatRow['reviews'] ?? 0); ?> Reviews
          </div>

          <div class="info-row"><span class="fa fa-home"></span> <?php echo htmlspecialchars($FetCity, ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="info-row"><span class="fa fa-phone"></span> <?php echo htmlspecialchars($FeatPhone, ENT_QUOTES, 'UTF-8'); ?></div>
          <?php if(!empty($FeatSite)){?>
            <div class="info-row"><span class="fa fa-link"></span>
              <a href="<?php echo htmlspecialchars($FeatSite, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Website</a>
            </div>
          <?php }else{?>
            <div class="info-row"><span class="fa fa-link"></span> N/A</div>
          <?php }?>
        </div>
      </div><!-- /.grid -->
    </div><!-- /.col -->
  <?php
    }
    $FeatSql->close();
  } else {
    error_log('[index.php] Falha ao carregar Featured.');
  }

  if($CountFeat == 0){
    echo '<div class="col-note">There is nothing to display at the moment. Please check back again.</div>';
  }
  ?>

</div><!--container-->

<div class="container-fluid container-color">
  <div class="container">
    <h1>Let Your Business Reach Thousands of New Customers</h1>
    <h3>List Your Business with Us for Free</h3>
    <a class="btn btn-danger btn-lg" href="submit">Submit Now</a>
  </div>
</div>

<div class="container">

  <div class="page-title"><h1>Latest Businesses</h1></div>

  <?php
  $CountRows = 0;
  $sqlLatest = "
    SELECT business.*, categories.category
    FROM business
    LEFT JOIN categories ON categories.cat_id = business.cid
    WHERE business.active = 1
    ORDER BY business.biz_id DESC
    LIMIT 6
  ";
  if($PostSql = @$mysqli->query($sqlLatest)){

    $CountRows = $PostSql->num_rows;

    while ($PostRow = $PostSql->fetch_assoc()){

      $longTitle  = stripslashes((string)$PostRow['business_name']);
      $PostTitle  = (mb_strlen($longTitle) > 25) ? mb_substr($longTitle, 0, 23) . '...' : $longTitle;
      $slug       = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
      $PostLink   = urlencode(strtolower($slug));

      $longDesc   = stripslashes((string)$PostRow['description']);
      $Description= (mb_strlen($longDesc) > 70) ? mb_substr($longDesc, 0, 67) . '...' : $longDesc;

      $Tel        = stripslashes((string)$PostRow['phone']);
      $City       = stripslashes((string)$PostRow['city']);
      $Site       = stripslashes((string)$PostRow['website']);
      $Telephone  = !empty($Tel) ? $Tel : 'N/A';

      $CName      = (string)$PostRow['category'];
      $CLink      = strtolower(urlencode(preg_replace("![^a-z0-9]+!i", "-", $CName)));

      // IMAGEM via helper -> placeholder garantido
      $featFile = isset($PostRow['featured_image']) ? trim((string)$PostRow['featured_image']) : '';
      $imgSrc   = biz_thumb_src($featFile, 300, 500, 100, (int)$PostRow['biz_id']);
  ?>
    <div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
      <div class="grid wow fadeInUp">

        <a class="over-label" href="category-<?php echo (int)$PostRow['cid']; ?>-<?php echo $CLink; ?>">
          <?php echo htmlspecialchars($CName, ENT_QUOTES, 'UTF-8'); ?>
        </a>

        <a href="business-<?php echo (int)$PostRow['biz_id']; ?>-<?php echo $PostLink; ?>">
          <img class="img-responsive"
               src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
               alt="<?php echo htmlspecialchars($PostTitle, ENT_QUOTES, 'UTF-8'); ?>">
        </a>

        <h2>
          <a href="business-<?php echo (int)$PostRow['biz_id']; ?>-<?php echo $PostLink; ?>">
            <?php echo htmlspecialchars($PostTitle, ENT_QUOTES, 'UTF-8'); ?>
          </a>
        </h2>

        <p><?php echo htmlspecialchars($Description, ENT_QUOTES, 'UTF-8'); ?></p>

        <div class="post-info-bottom">
          <div class="col-rate">
            <span class="star-rates" data-score="<?php echo (int)($PostRow['avg'] ?? 0); ?>"></span>
            <?php echo (int)($PostRow['reviews'] ?? 0); ?> Reviews
          </div>

          <div class="info-row"><span class="fa fa-home"></span> <?php echo htmlspecialchars($City, ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="info-row"><span class="fa fa-phone"></span> <?php echo htmlspecialchars($Telephone, ENT_QUOTES, 'UTF-8'); ?></div>
          <?php if(!empty($Site)){?>
            <div class="info-row"><span class="fa fa-link"></span>
              <a href="<?php echo htmlspecialchars($Site, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Website</a>
            </div>
          <?php }else{?>
            <div class="info-row"><span class="fa fa-link"></span> N/A</div>
          <?php } ?>
        </div>

      </div><!-- /.grid -->
    </div><!-- /.col -->
  <?php
    }
    $PostSql->close();

  } else {
    error_log('[index.php] Falha ao carregar Latest.');
  }

  if($CountRows == 0){
    echo '<div class="col-note">There is nothing to display at the moment. Please check back again.</div>';
  }
  ?>

</div><!--container-->

<div class="container">
  <?php if(!empty($Ad2)){?>
    <div class="col-shadow col-ads-long"> <?php echo $Ad2;?> </div>
  <?php }?>
</div><!--container-->

<?php include("footer.php");?>
