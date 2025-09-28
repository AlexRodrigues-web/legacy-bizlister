<?php 
session_start();

include("db.php");

// ====== GARANTIA: $id e $CatRow definidos ======
$id = 0;
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = (int) $_GET['id'];
}
if ($id === 0 && !empty($_SERVER['REQUEST_URI'])) {
    // tenta extrair de URLs do tipo category-123-slug
    if (preg_match('~category-(\d+)-~', $_SERVER['REQUEST_URI'], $m)) {
        $id = (int) $m[1];
    }
}

// Carrega settings (apenas o necessário aqui)
$Settings = [];
if ($SiteSettings = @$mysqli->query("SELECT * FROM settings WHERE id=1 LIMIT 1")) {
    $row = $SiteSettings->fetch_assoc();
    if (is_array($row)) $Settings = $row;
    $SiteSettings->close();
}
$SiteLink  = isset($Settings['site_link']) ? $Settings['site_link'] : 'localhost/TECINFOSP/Flippy BizLister/bizlister_legacy';
$Template  = isset($Settings['template']) ? $Settings['template'] : 'default';

// Descobre a categoria (se header_category.php não tiver feito)
$CatRow = $CatRow ?? null;
if (!$CatRow && $id > 0) {
    if ($CatSql = $mysqli->query("SELECT * FROM categories WHERE cat_id={$id} LIMIT 1")) {
        $CatRow = $CatSql->fetch_assoc();
        $CatSql->close();
    }
}
$CatName = isset($CatRow['category']) ? $CatRow['category'] : 'Category';

// ================================================
include("header_category.php");
?>

<div class="container container-main" id="display-posts">

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

<div class="page-title"><h1><?php echo htmlspecialchars($CatName, ENT_QUOTES, 'UTF-8'); ?></h1></div>

<?php
$CountRows = 0;

if ($id > 0 && $PostSql = $mysqli->query("
    SELECT business.*, categories.category
    FROM business
    LEFT JOIN categories ON categories.cat_id = business.cid
    WHERE business.active = 1 AND categories.cat_id = {$id}
    ORDER BY business.biz_id DESC
    LIMIT 0, 12
")) {

    $CountRows = mysqli_num_rows($PostSql);	

    while ($PostRow = mysqli_fetch_array($PostSql)){

        $longTitle = stripslashes($PostRow['business_name']);
        $strTitle = strlen($longTitle);
        $PostTitle = ($strTitle > 25) ? substr($longTitle,0,23).'...' : $longTitle;

        $PostLink = preg_replace("![^a-z0-9]+!i", "-", $longTitle);
        $PostLink = urlencode(strtolower($PostLink));

        $longDescription = stripslashes($PostRow['description']);
        $strDescription = strlen($longDescription);
        $Description = ($strDescription > 70) ? substr($longDescription,0,67).'...' : $longDescription;

        $Tel  = stripslashes($PostRow['phone']);
        $City = stripslashes($PostRow['city']);
        $Site = stripslashes($PostRow['website']);
        $Telephone = !empty($Tel) ? $Tel : "N/A";

        // ========= IMAGEM =========
        $feat = isset($PostRow['featured_image']) ? trim($PostRow['featured_image']) : '';

        // Se tiver imagem -> usa thumbs.php (mantendo seu comportamento original)
        // Se NÃO tiver -> usa placeholder direto (SEM thumbs.php) para garantir que aparece
        if ($feat !== '') {
            // monta URL do upload
            // aceita site_link tanto com quanto sem http://
            $base = (stripos($SiteLink, 'http://') === 0 || stripos($SiteLink, 'https://') === 0)
                ? $SiteLink
                : 'http://' . $SiteLink;

            // não codificar a barra, apenas o nome do arquivo
            $uploadUrl = rtrim($base, '/').'/uploads/'.rawurlencode($feat);

            $imgTag = '<img class="img-responsive" src="thumbs.php?src='
                    . htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8')
                    . '&amp;h=300&amp;w=500&amp;q=100" alt="'
                    . htmlspecialchars($PostTitle, ENT_QUOTES, 'UTF-8')
                    . '">';
        } else {
            // placeholder direto, sem thumbs.php
            $imgTag = '<img class="img-responsive" src="images/placeholder.png" alt="'
                    . htmlspecialchars($PostTitle, ENT_QUOTES, 'UTF-8')
                    . '">';
        }
        // ===========================
        ?>

        <div class="col-sm-12 col-xs-12 col-md-4 col-lg-4 col-box">
          <div class="grid wow fadeInUp"> 
              <a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>">
                <?php echo $imgTag; ?>
              </a>
          
              <h2><a href="business-<?php echo $PostRow['biz_id'];?>-<?php echo $PostLink;?>"><?php echo $PostTitle;?></a></h2>
              <p><?php echo $Description;?></p>
          
              <div class="post-info-bottom">
                <div class="col-rate">    
                  <span class="star-rates" data-score="<?php echo (int)$PostRow['avg'];?>"></span> <?php echo (int)$PostRow['reviews'];?> Reviews
                </div>

                <div class="info-row"><span class="fa fa-home"></span> <?php echo $City;?></div>
                <div class="info-row"><span class="fa fa-phone"></span> <?php echo $Telephone;?></div>
                <?php if(!empty($Site)){?>
                  <div class="info-row"><span class="fa fa-link"></span> <a href="<?php echo htmlspecialchars($Site, ENT_QUOTES, 'UTF-8');?>" target="_blank">Website</a></div>
                <?php }else{?>
                  <div class="info-row"><span class="fa fa-link"></span> N/A</div>
                <?php } ?>
              </div>
          </div><!-- /.grid -->  
        </div><!-- /.col -->
        <?php     
    }
    $PostSql->close();
}

if ($CountRows == 0){
?>
  <div class="col-note">There is nothing to display at the moment. Please check back again.</div>
<?php }?>

</div><!--container-->

<nav id="page-nav"><a href="data_category.php?page=2&amp;id=<?php echo (int)$id;?>"></a></nav>

<script src="js/jquery.infinitescroll.min.js"></script>
<script src="js/manual-trigger.js"></script>

<script>
$('#display-posts').infinitescroll({
  navSelector  : '#page-nav',
  nextSelector : '#page-nav a',
  itemSelector : '.col-box',
  loading: {
    finishedMsg: 'End of business listings.',
    img: 'templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, "UTF-8");?>/images/loader.gif'
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
  <div class="col-shadow col-ads-long"> <?php echo $Ad2;?> </div>
<?php }?>
</div><!--container-->

<?php include("footer.php");?>
