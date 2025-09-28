<?php
/* ================= ERROR LOG ================= */
error_reporting(E_ALL);
ini_set('display_errors', 0);
$__logDir  = __DIR__ . DIRECTORY_SEPARATOR . 'logs';
$__logFile = $__logDir . DIRECTORY_SEPARATOR . 'php_errors.log';
if (!is_dir($__logDir)) { @mkdir($__logDir, 0777, true); }
if (!is_dir($__logDir)) { $__logFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bizlister_php_errors.log'; }
ini_set('log_errors', 1);
ini_set('error_log', $__logFile);

/* ================= SESSION ================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("db.php");

/* ================= SETTINGS (com fallback) ================= */
$Settings = [];
if ($SiteSettings = @$mysqli->query("SELECT * FROM settings WHERE id=1 LIMIT 1")) {
    if ($row = $SiteSettings->fetch_assoc()) {
        $Settings = $row;
    }
    $SiteSettings->close();
}
$defaults = [
    'site_title'        => 'BizLister (dev)',
    'site_link'         => 'localhost/TECINFOSP/Flippy BizLister/bizlister_legacy',
    'meta_description'  => '',
    'meta_keywords'     => '',
    'fb_app_id'         => '',
    'fb_page'           => '',
    'twitter_link'      => '',
    'pinterest_link'    => '',
    'google_pluse_link' => '',
    'template'          => 'default',
];
foreach ($defaults as $k => $v) {
    if (!isset($Settings[$k]) || $Settings[$k] === null) {
        $Settings[$k] = $v;
    }
}

/* Normaliza $SiteLink UMA VEZ e nunca mais prefixe "http://" no HTML */
$SiteLink = (string)$Settings['site_link'];
if (!preg_match('~^https?://~i', $SiteLink)) {
    $SiteLink = 'http://' . $SiteLink;
}
$SiteLink = rtrim($SiteLink, '/');
$SiteTitle = (string)$Settings['site_title'];
$Template  = (string)$Settings['template'];

/* ================= HELPERS DE IMAGEM (SERVER-SIDE) ================= */
function normalize_http_dup(string $url): string {
    // remove http(s):// duplicado no inÃ­cio: http://http://...
    return preg_replace('~^(https?://)+~i', '$1', $url);
}
function home_placeholder_url(): string {
    // Mapeia o seu path fÃ­sico -> URL pÃºblica exata (com espaÃ§o em â€œFlippy BizListerâ€)
    // C:\xampp\htdocs\TECINFOSP\Flippy BizLister\bizlister_legacy\images\placeholder.png
    // http://localhost/TECINFOSP/Flippy BizLister/bizlister_legacy/images/placeholder.png
    global $SiteLink;
    return $SiteLink . '/images/placeholder.png';
}
function biz_original_image_url(?string $featured): ?string {
    global $SiteLink;
    $featured = trim((string)$featured);
    if ($featured === '') { return null; }

    // Se jÃ¡ veio completa (http/https), sÃ³ normaliza duplicado
    if (preg_match('~^https?://~i', $featured)) {
        return normalize_http_dup($featured);
    }

    $url = $SiteLink . '/uploads/' . rawurlencode($featured);
    $url = normalize_http_dup($url);
    return $url;
}
/**
 * Monta SEMPRE thumbs.php com src=(original ou placeholder).
 * Evita http:// duplo, e se detectar /uploads/ sem arquivo => placeholder + log.
 */
function biz_thumb_src(?string $featured, int $h=300, int $w=500, int $q=100, ?int $bizId=null): string {
    $orig = biz_original_image_url($featured);

    // Se nÃ£o veio arquivo, cai pro placeholder
    if (!$orig || preg_match('~/uploads/?$~i', $orig)) {
        $ph = home_placeholder_url();
        $who = $bizId !== null ? "biz_id={$bizId}" : "sem biz_id";
        error_log("[placeholder] HOME: usando placeholder ({$who}); src={$ph}");
        return 'thumbs.php?src=' . urlencode($ph) . "&h={$h}&w={$w}&q={$q}";
    }

    // Se por algum motivo ainda ficou com http://http://, normaliza e loga
    $norm = normalize_http_dup($orig);
    if ($norm !== $orig) {
        error_log("[normalize] Corrigido http duplo: {$orig} -> {$norm}");
        $orig = $norm;
    }

    return 'thumbs.php?src=' . urlencode($orig) . "&h={$h}&w={$w}&q={$q}";
}

/* ================= USER ================= */
$UserId = 0; $UserEmail = '';
if (!empty($_SESSION['username'])) {
    $LoggedUser = $_SESSION['username'];
    if ($GetUser = $mysqli->query("SELECT * FROM users WHERE username='".$mysqli->real_escape_string($LoggedUser)."' LIMIT 1")) {
        if ($UserInfo = $GetUser->fetch_assoc()) {
            $LoggedUsername  = strtolower($UserInfo['username'] ?? '');
            $LoggedUserLink  = strtolower(preg_replace("![^a-z0-9]+!i", "-", $LoggedUsername));
            $UserId          = (int)($UserInfo['user_id'] ?? 0);
            $UserEmail       = (string)($UserInfo['email'] ?? '');
        }
        $GetUser->close();
    }
}

/* ================= ADS ================= */
$Ad1 = $Ad2 = $Ad3 = '';
if ($AdsSql = $mysqli->query("SELECT * FROM advertisements WHERE id='1' LIMIT 1")) {
    if ($AdsRow = $AdsSql->fetch_assoc()) {
        $Ad1 = (string)($AdsRow['ad1'] ?? '');
        $Ad2 = (string)($AdsRow['ad2'] ?? '');
        $Ad3 = (string)($AdsRow['ad3'] ?? '');
    }
    $AdsSql->close();
}

/* ================= PAGE TITLE ================= */
$pageTitle = '';
$uriPath = (string)(isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '');
switch ($uriPath) {
    case '/advertise':      $pageTitle = 'Advertise | '; break;
    case '/about_us':       $pageTitle = 'About Us | '; break;
    case '/contact_us':     $pageTitle = 'Contact Us | '; break;
    case '/privacy_policy': $pageTitle = 'Privacy Policy | '; break;
    case '/tos':            $pageTitle = 'Terms of Use | '; break;
    case '/all':            $pageTitle = 'All Businesses | '; break;
    case '/popular':        $pageTitle = 'Popular Businesses | '; break;
    case '/featured':       $pageTitle = 'Featured Businesses | '; break;
    case '/login':          $pageTitle = 'Login | '; break;
    case '/register':       $pageTitle = 'Register | '; break;
    case '/my_business':    $pageTitle = 'Manage Your Business | '; break;
    case '/bookmarks':      $pageTitle = 'Bookmarks | '; break;
    case '/submit':         $pageTitle = 'Submit Your Business | '; break;
    default:                $pageTitle = ''; break;
}

/* Views (silencioso) */
@$mysqli->query("UPDATE settings SET site_views=site_views+1 WHERE id=1");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($pageTitle.$SiteTitle, ENT_QUOTES, 'UTF-8');?></title>
<meta name="description" content="<?php echo htmlspecialchars((string)$Settings['meta_description'], ENT_QUOTES, 'UTF-8');?>" />
<meta name="keywords" content="<?php echo htmlspecialchars((string)$Settings['meta_keywords'], ENT_QUOTES, 'UTF-8');?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="favicon.ico" rel="shortcut icon" type="image/x-icon"/>

<!--Facebook Meta Tags-->
<meta property="fb:app_id"          content="<?php echo htmlspecialchars((string)$Settings['fb_app_id'], ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:url"             content="<?php echo htmlspecialchars($SiteLink, ENT_QUOTES, 'UTF-8'); ?>" />
<meta property="og:title"           content="<?php echo htmlspecialchars($SiteTitle, ENT_QUOTES, 'UTF-8');?>" />
<meta property="og:description"     content="<?php echo htmlspecialchars((string)$Settings['meta_description'], ENT_QUOTES, 'UTF-8');?>" />
<meta property="og:image"           content="<?php echo htmlspecialchars($SiteLink.'/images/logo.png', ENT_QUOTES, 'UTF-8');?>" />
<!--End Facebook Meta Tags-->

<link href="templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8');?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8');?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8');?>/css/style.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8');?>/css/animate.css" rel="stylesheet" type="text/css">

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.raty.js"></script>
<script src="js/wow.min.js"></script>

<script>
function popup(e){
  var t=700,n=400,r=(screen.width-t)/2,i=(screen.height-n)/2,s="width="+t+", height="+n;
  s+=", top="+i+", left="+r;
  s+=", directories=no, location=no, menubar=no, resizable=no, scrollbars=no, status=no, toolbar=no";
  var w=window.open(e,"windowname5",s);
  if(window.focus){w.focus()}
  return false;
}
$(function() {
  $.fn.raty.defaults.path = 'templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8'); ?>/images';
});
new WOW().init();
</script>
</head>

<body>


<div id="wrap">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- NUNCA prefixe http:// aqui. $SiteLink jÃ¡ tem http:// -->
      <a class="navbar-brand" href="<?php echo htmlspecialchars($SiteLink, ENT_QUOTES, 'UTF-8'); ?>">
        <img src="images/logo.png" class="logo" alt="<?php echo htmlspecialchars($SiteTitle, ENT_QUOTES, 'UTF-8');?>">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo htmlspecialchars($SiteLink, ENT_QUOTES, 'UTF-8');?>">Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Browse <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="all">All Businesses</a></li>
            <li><a href="popular">popular Businesses</a></li>
            <li><a href="featured">Featured Businesses</a></li>
          </ul>
        </li>

<?php
/* ===== Menu de categorias ===== */
$categories = [];
if ($res = $mysqli->query("SELECT cat_id, category, parent_id FROM categories ORDER BY category")) {
    while ($row = $res->fetch_assoc()) {
        $parent = (int)($row['parent_id'] ?? 0);
        if (!isset($categories[$parent])) $categories[$parent] = [];
        $categories[$parent][] = $row;
    }
    $res->close();
}
function build_categories($parent, $categories) {
    if (isset($categories[$parent]) && count($categories[$parent])) {
        foreach ($categories[$parent] as $category) {
            $CategoryName = (string)$category['category'];
            $CategoryLink = strtolower(urlencode(preg_replace("![^a-z0-9]+!i", "-", $CategoryName)));
            if ((int)$category['parent_id'] === 0){
                echo '<li><a href="category-'.(int)$category['cat_id'].'-'.$CategoryLink.'">' . htmlspecialchars($CategoryName, ENT_QUOTES, 'UTF-8') . '</a>';
            } else {
                echo '<li><a href="subcategory-'.(int)$category['cat_id'].'-'.$CategoryLink.'"><span class="fa fa-angle-double-right"></span> ' . htmlspecialchars($CategoryName, ENT_QUOTES, 'UTF-8') . '</a>';
            }
            build_categories((int)$category['cat_id'], $categories);
            echo '</li>';
        }
    }
}
?>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <span class="caret"></span></a>
  <ul class="dropdown-menu" role="menu">
    <?php build_categories(0, $categories);?>
  </ul>
</li>

      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="submit">Submit</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <?php if(empty($_SESSION['username'])){?>
            <li><a href="login">Login</a></li>
            <li><a href="register">Register</a></li>
          <?php }else{ ?>
            <li><a href="settings">Settings</a></li>
            <li><a href="my_business">My Business</a></li>
            <li><a href="bookmarks">Bookmarks</a></li>
            <li><a href="logout">Logout</a></li>
          <?php } ?>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
   </div><!--container-->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid search-bar">
  <div class="container">
    <div class="row">
      <form role="search" method="get" action="search.php">
        <div class="form-group">
          <div class="col-md-4 col-desktop-only">
            <h2>Search </h2>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="term" name="term" placeholder="Search">
          </div>
          <div class="col-md-4">
            <select class="form-control" id="city" name="city">
              <option value="all">All Cities</option>
              <?php
              if($SearchCity = $mysqli->query("SELECT city_id, city FROM city")){
                  while($SearchRow = $SearchCity->fetch_assoc()){
                      $city = (string)($SearchRow['city'] ?? '');
                      echo '<option value="'.htmlspecialchars($city, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($city, ENT_QUOTES, 'UTF-8').'</option>';
                  }
                  $SearchCity->close();
              }
              ?>
            </select>
          </div>
        </div>
        <div class="col-btn">
          <button type="submit" class="btn btn-danger btn-width">
            <i class="glyphicon glyphicon-search"></i> <span class="col-mobile-only">Search</span>
          </button>
        </div>
      </form>
    </div><!--row-->
  </div><!--container-->
</div><!-- /.container-fluid -->



