<?php
// ===== SessÃ£o: evita "Ignoring session_start()" =====
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("db.php");

require __DIR__ . '/inc/media_helpers.php';


// ===== Settings com fallbacks seguros (evita offsets nulos) =====
$Settings = [];
if ($SiteSettings = @$mysqli->query("SELECT * FROM settings WHERE id=1 LIMIT 1")) {
    $row = $SiteSettings->fetch_assoc();
    if (is_array($row)) {
        $Settings = $row;
    }
    $SiteSettings->close();
}
$defaults = [
    'site_title'        => 'BizLister (dev)',
    'site_link'         => 'localhost/TECINFOSP/Flippy BizLister/bizlister_legacy', // ajuste se mover a pasta
    'meta_description'  => '',
    'meta_keywords'     => '',
    'fb_app_id'         => '',
    'fb_page'           => '',
    'twitter_link'      => '',
    'pinterest_link'    => '',
    'google_pluse_link' => '',
    'template'          => 'default',
    'site_views'        => 0,
];
foreach ($defaults as $k => $v) {
    if (!isset($Settings[$k]) || $Settings[$k] === null) {
        $Settings[$k] = $v;
    }
}
$SiteLink  = $Settings['site_link'];
$SiteTitle = $Settings['site_title'];
$FaceBook  = $Settings['fb_page'];
$Twitter   = $Settings['twitter_link'];
$Pinterest = $Settings['pinterest_link'];
$Gplus     = $Settings['google_pluse_link'];

// ===== UsuÃ¡rio (inalterado, com proteÃ§Ã£o) =====
if (isset($_SESSION['username'])) {
    $LoggedUser = $_SESSION['username'];
    if ($GetUser = @$mysqli->query("SELECT * FROM users WHERE username='".$mysqli->real_escape_string($LoggedUser)."'")) {
        $UserInfo       = $GetUser->fetch_assoc();
        $LoggedUsername = strtolower($UserInfo['username'] ?? '');
        $LoggedUserLink = preg_replace("![^a-z0-9]+!i", "-", $LoggedUsername);
        $LoggedUserLink = strtolower($LoggedUserLink);
        $UserId   = (int)($UserInfo['user_id'] ?? 0);
        $UserEmail= $UserInfo['email'] ?? '';
        $GetUser->close();
    } else {
        $UserId = 0;
    }
} else {
    $UserId = 0;
}

// ===== Ads (inalterado) =====
$Ad1 = $Ad2 = $Ad3 = '';
if ($AdsSql = @$mysqli->query("SELECT * FROM advertisements WHERE id='1'")) {
    $AdsRow = $AdsSql->fetch_assoc();
    if ($AdsRow) {
        $Ad1 = $AdsRow['ad1'] ?? '';
        $Ad2 = $AdsRow['ad2'] ?? '';
        $Ad3 = $AdsRow['ad3'] ?? '';
    }
    $AdsSql->close();
}

// ===== ID seguro + categoria =====
$id = 0;
if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = (int) $_GET['id'];
}
$CatRow = null;
$CName1 = $CLink1 = '';
if ($id > 0 && ($CatSql = @$mysqli->query("SELECT * FROM categories WHERE cat_id='$id' LIMIT 1"))) {
    $CatRow = $CatSql->fetch_assoc();
    if ($CatRow) {
        $CName1 = $CatRow['category'] ?? '';
        $CLink1 = preg_replace("![^a-z0-9]+!i", "-", $CName1);
        $CLink1 = urlencode($CLink1);
        $CLink1 = strtolower($CLink1);
    }
    $CatSql->close();
}

// ===== Views =====
@$mysqli->query("UPDATE settings SET site_views=site_views+1 WHERE id=1");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($CatRow['category'] ?? 'Category', ENT_QUOTES, 'UTF-8');?> | <?php echo htmlspecialchars($SiteTitle, ENT_QUOTES, 'UTF-8');?></title>
<meta name="description" content="<?php echo htmlspecialchars($CatRow['cat_description'] ?? '', ENT_QUOTES, 'UTF-8');?>" />
<meta name="keywords" content="<?php echo htmlspecialchars($Settings['meta_keywords'], ENT_QUOTES, 'UTF-8');?>" />

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="favicon.ico" rel="shortcut icon" type="image/x-icon"/>

<!--Facebook Meta Tags-->
<meta property="fb:app_id"          content="<?php echo htmlspecialchars($Settings['fb_app_id'], ENT_QUOTES, 'UTF-8'); ?>" /> 
<meta property="og:url"             content="http://<?php echo htmlspecialchars($SiteLink, ENT_QUOTES, 'UTF-8'); ?>/category-<?php echo (int)$id;?>-<?php echo htmlspecialchars($CLink1, ENT_QUOTES, 'UTF-8');?>" /> 
<meta property="og:title"           content="<?php echo htmlspecialchars($CatRow['category'] ?? 'Category', ENT_QUOTES, 'UTF-8');?>" />
<meta property="og:description" 	content="<?php echo htmlspecialchars($CatRow['cat_description'] ?? '', ENT_QUOTES, 'UTF-8');?>" /> 
<meta property="og:image"           content="http://<?php echo htmlspecialchars($SiteLink, ENT_QUOTES, 'UTF-8'); ?>/images/logo.png" /> 
<!--End Facebook Meta Tags-->

<link href="templates/<?php echo htmlspecialchars($Settings['template'], ENT_QUOTES, 'UTF-8');?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Settings['template'], ENT_QUOTES, 'UTF-8');?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Settings['template'], ENT_QUOTES, 'UTF-8');?>/css/style.css" rel="stylesheet" type="text/css">
<link href="templates/<?php echo htmlspecialchars($Settings['template'], ENT_QUOTES, 'UTF-8');?>/css/animate.css" rel="stylesheet" type="text/css">

<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script src="js/jquery.min.js"></script>	
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.raty.js"></script>
<script src="js/wow.min.js"></script>

<script>
function popup(e){var t=700;var n=400;var r=(screen.width-t)/2;var i=(screen.height-n)/2;var s="width="+t+", height="+n;s+=", top="+i+", left="+r;s+=", directories=no";s+=", location=no";s+=", menubar=no";s+=", resizable=no";s+=", scrollbars=no";s+=", status=no";s+=", toolbar=no";newwin=window.open(e,"windowname5",s);if(window.focus){newwin.focus()}return false}
$(function() { $.fn.raty.defaults.path = 'templates/default/images'; new WOW().init(); });
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
      <a class="navbar-brand" href="http://<?php echo $SiteLink; ?>"><img src="images/logo.png" class="logo" alt="<?php echo htmlspecialchars($SiteTitle, ENT_QUOTES, 'UTF-8');?>"></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="http://<?php echo $SiteLink;?>">Home</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Browse <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="all">All Businesses</a></li>
            <li><a href="popular">popular Businesses</a></li>
            <li><a href="featured">Featured Businesses</a></li>
          </ul>
        </li>

<?php
// ===== MENU DE CATEGORIAS =====
$categories = [];
if ($res = @$mysqli->query("SELECT cat_id, category, parent_id FROM categories ORDER BY category")) {
  while ($row = $res->fetch_assoc()) {
      $parent = (int)$row['parent_id'];
      if (!isset($categories[$parent])) $categories[$parent] = [];
      $categories[$parent][] = $row;
  }
  $res->close();
}
function build_categories($parent, $categories) {
    if (isset($categories[$parent]) && count($categories[$parent])) {
        foreach ($categories[$parent] as $category) {
            $CategoryName = $category['category'];
            $CategoryLink = preg_replace("![^a-z0-9]+!i", "-", $CategoryName);
            $CategoryLink = urlencode($CategoryLink);
            $CategoryLink = strtolower($CategoryLink);

            if ($category['parent_id']==0) {
                echo '<li><a href="category-'.(int)$category['cat_id'].'-'.$CategoryLink.'">'.htmlspecialchars($category['category']).'</a>';
            } else {
                echo '<li><a href="subcategory-'.(int)$category['cat_id'].'-'.$CategoryLink.'"><span class="fa fa-angle-double-right"></span> '.htmlspecialchars($category['category']).'</a>';
            }
            echo build_categories((int)$category['cat_id'], $categories);
            echo '</li>';
        }
    }
}
?>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <span class="caret"></span></a>
  <ul class="dropdown-menu" role="menu">
    <?php build_categories(0, $categories); ?>
  </ul>
</li>

      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="submit">Submit</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
          <?php if(!isset($_SESSION['username'])){?>
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
            <h2>Search Local Businesses</h2>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" id="term" name="term" placeholder="Search">
          </div>
          <div class="col-md-4">
            <select class="form-control" id="city" name="city">
              <option value="all">All Cities</option>
<?php
if ($SearchCity = @$mysqli->query("SELECT city_id, city FROM city")) {
    while ($SearchRow = $SearchCity->fetch_assoc()) {
        echo '<option value="'.htmlspecialchars($SearchRow['city']).'">'.htmlspecialchars($SearchRow['city']).'</option>';
    }
    $SearchCity->close();
}
?>
            </select>
          </div>
        </div>
        <div class="col-btn">
          <button type="submit" class="btn btn-danger btn-width"><i class="glyphicon glyphicon-search"></i> <span class="col-mobile-only">Search</span></button>
        </div>
      </form>
    </div>
  </div>
</div><!-- /.container-fluid -->




