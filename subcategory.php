<?php
/****************************************************
 * subcategory.php  (COM ERROR LOG + PLACEHOLDER)
 * Lista negócios por SUBCATEGORIA (business.sid)
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
error_log("===== [subcategory.php] request: " . ($_SERVER['REQUEST_URI'] ?? '') . " =====");

/* 
   IMPORTANTE:
   header_subcategory.php já inicia sessão, carrega DB,
   normaliza $SiteLink, define $Settings/$Template,
   captura $id da subcategoria e declara os helpers 
   de imagem (biz_thumb_src).
*/
include("header_subcategory.php");
?>

<div class="container container-main" id="display-posts">

  <div class="page-title">
    <h1><?php echo htmlspecialchars($CatRow['category'] ?? 'Subcategory', ENT_QUOTES, 'UTF-8'); ?></h1>
  </div>

  <script>
  $(document).ready(function(){
    $('.star-rates').raty({
      readOnly: true,
      score: function() { return $(this).attr('data-score'); }
    });
  });
  </script>

<?php
/* ============================================================
   LISTAGEM POR SUBCATEGORIA:
   - business.sid = categories.cat_id (subcategoria)
   - filtra somente ativos
   - ordena por mais recentes
   ============================================================ */

$CountRows = 0;

$sql = "
  SELECT business.*, categories.category
  FROM business
  LEFT JOIN categories ON categories.cat_id = business.sid
  WHERE business.active = 1
    AND categories.cat_id = ?
  ORDER BY business.biz_id DESC
  LIMIT 0, 12
";

if ($stmt = @$mysqli->prepare($sql)) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute() && ($PostSql = $stmt->get_result())) {

        $CountRows = $PostSql->num_rows;

        while ($PostRow = $PostSql->fetch_assoc()) {

            /* ====== Títulos e infos ====== */
            $longTitle = stripslashes((string)$PostRow['business_name']);
            $PostTitle = (mb_strlen($longTitle) > 25) ? mb_substr($longTitle, 0, 23) . '...' : $longTitle;

            $slug     = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
            $PostLink = urlencode(strtolower($slug));

            $longDescription = stripslashes((string)$PostRow['description']);
            $Description     = (mb_strlen($longDescription) > 70) ? mb_substr($longDescription, 0, 67) . '...' : $longDescription;

            $Tel       = stripslashes((string)$PostRow['phone']);
            $City      = stripslashes((string)$PostRow['city']);
            $Site      = stripslashes((string)$PostRow['website']);
            $Telephone = !empty($Tel) ? $Tel : "N/A";

            /* ====== IMAGEM (sempre via thumbs + placeholder) ====== */
            $feat   = isset($PostRow['featured_image']) ? trim((string)$PostRow['featured_image']) : '';
            $bizId  = (int)($PostRow['biz_id'] ?? 0);
            // usa helper vindo do header_subcategory.php
            $imgSrc = biz_thumb_src($feat, 300, 500, 100, $bizId);
            ?>
            
            <div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
              <div class="grid wow fadeInUp"> 

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
                    <div class="info-row">
                      <span class="fa fa-link"></span>
                      <a href="<?php echo htmlspecialchars($Site, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Website</a>
                    </div>
                  <?php }else{?>
                    <div class="info-row"><span class="fa fa-link"></span> N/A</div>
                  <?php }?>
                </div>

              </div><!-- /.grid -->  
            </div><!-- /.col -->
            <?php
        } // while

        $PostSql->free();
    } else {
        error_log('[subcategory.php] Falha ao executar query ou obter result set.');
        echo '<div class="col-note">There seems to be an issue.</div>';
    }
    $stmt->close();

} else {
    error_log('[subcategory.php] Prepare falhou: ' . $mysqli->error);
    echo '<div class="col-note">There seems to be an issue.</div>';
}

if ($CountRows == 0){
    echo '<div class="col-note">There is nothing to display at the moment. Please check back again.</div>';
}
?>

</div><!--container-->

<nav id="page-nav"><a href="data_subcategory.php?page=2&amp;id=<?php echo (int)$id; ?>"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
<script src="js/manual-trigger.js"></script>
<script>
$('#display-posts').infinitescroll({
  navSelector  : '#page-nav',
  nextSelector : '#page-nav a',
  itemSelector : '.col-box',
  loading: {
    finishedMsg: 'End of business listings.',
    img: 'templates/<?php echo htmlspecialchars($Settings['template'] ?? 'default', ENT_QUOTES, 'UTF-8'); ?>/images/loader.gif'
  }
}, function(newElements, data, url){
  $('.star-rates').raty({
    readOnly: true,
    score: function() { return $(this).attr('data-score'); }
  });
  $('.star-rates').raty('reload');
});
</script>

<div class="container">
<?php if(!empty($Ad2)){?>
  <div class="col-shadow col-ads-long"> <?php echo $Ad2; ?> </div>
<?php }?>
</div><!--container-->

<?php include("footer.php");?>
