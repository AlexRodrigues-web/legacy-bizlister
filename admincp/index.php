<?php
/****************************************************
 * admincp/index.php (DASHBOARD ADMIN) - LIMPO + LOG
 * - MantÃ©m o Admin funcional, sem widget Alexa
 * - Inclui ERROR LOG (admincp/logs/php_errors.log)
 * - Mostra KPIs bÃ¡sicos (negÃ³cios, categorias, cidades, usuÃ¡rios)
 ****************************************************/

/* =============== ERROR LOG =============== */
error_reporting(E_ALL);
ini_set('display_errors', 0);
$__logDir  = __DIR__ . DIRECTORY_SEPARATOR . 'logs';
$__logFile = $__logDir . DIRECTORY_SEPARATOR . 'php_errors.log';
if (!is_dir($__logDir)) { @mkdir($__logDir, 0777, true); }
if (!is_dir($__logDir)) {
    $__logFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bizlister_admin_php_errors.log';
}
ini_set('log_errors', 1);
ini_set('error_log', $__logFile);
error_log("===== [admincp/index.php] request: " . ($_SERVER['REQUEST_URI'] ?? '') . " =====");

/* =============== SESSION =============== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =============== DB =============== */
/* db.php estÃ¡ na raiz do legado; admincp/ Ã© subpasta */
require __DIR__ . '/../db.php';

/* =============== (Opcional) VerificaÃ§Ã£o de acesso =============== */
/* Se o seu admin usa alguma flag de sessÃ£o, valide aqui:
   Ex.: if (empty($_SESSION['admin_logged'])) { header('Location: login.php'); exit; } */
if (empty($_SESSION['username'])) {
    // Se quiser forÃ§ar login de admin, troque para sua regra real:
    // header('Location: login.php'); exit;
    error_log('[admincp] UsuÃ¡rio nÃ£o autenticado acessou o dashboard (apenas log).');
}

/* =============== SETTINGS BÃSICOS =============== */
$Settings = [];
if ($SiteSettings = @$mysqli->query("SELECT * FROM settings WHERE id=1 LIMIT 1")) {
    if ($row = $SiteSettings->fetch_assoc()) { $Settings = $row; }
    $SiteSettings->close();
}
$SiteTitle = (string)($Settings['site_title'] ?? 'BizLister Admin');
$Template  = (string)($Settings['template']   ?? 'default');

/* =============== KPIs (totais) =============== */
function safe_count_table(mysqli $db, string $table, string $where = '1=1'): int {
    $table = preg_replace('~[^a-z0-9_]+~i', '', $table);
    $sql   = "SELECT COUNT(*) AS c FROM `{$table}` WHERE {$where}";
    if ($res = @$db->query($sql)) {
        $row = $res->fetch_assoc();
        $res->close();
        return (int)($row['c'] ?? 0);
    }
    error_log("[admincp] Falha ao contar tabela {$table}. SQL={$sql}");
    return 0;
}

$totBusinesses = safe_count_table($mysqli, 'business');             // total de negÃ³cios
$totActiveBiz  = safe_count_table($mysqli, 'business', 'active=1'); // negÃ³cios ativos
$totCategories = safe_count_table($mysqli, 'categories');
$totCities     = safe_count_table($mysqli, 'city');
$totUsers      = safe_count_table($mysqli, 'users');

/* =============== HTML =============== */
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($SiteTitle, ENT_QUOTES, 'UTF-8'); ?> | Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="../templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8'); ?>/css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="../templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8'); ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../templates/<?php echo htmlspecialchars($Template, ENT_QUOTES, 'UTF-8'); ?>/css/style.css" rel="stylesheet" type="text/css">

<style>
  body { background:#f6f7fb; }
  .admin-navbar { background:#fff; border-bottom:1px solid #e9ecef; margin-bottom:20px; }
  .kpi-card{ background:#fff; border:1px solid #e9ecef; border-radius:10px; padding:20px; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,0,0,.03);}
  .kpi-title{ font-size:13px; color:#6c757d; text-transform:uppercase; letter-spacing:.08em; margin-bottom:6px;}
  .kpi-value{ font-size:28px; font-weight:700; }
  .kpi-sub{ color:#8f9aa7; font-size:12px; }
  .panel{ background:#fff; border:1px solid #e9ecef; border-radius:10px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,.03);}
  .panel h3{ margin-top:0; font-size:18px; }
  .table>thead>tr>th{ border-bottom:1px solid #e9ecef;}
  .quick-links a{ display:inline-block; margin:6px 8px 0 0; }
</style>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default admin-navbar">
  <div class="container-fluid">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">
          <i class="fa fa-cogs"></i> Admin Dashboard
        </a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <?php if (!empty($_SESSION['username'])): ?>
          <li><a><i class="fa fa-user"></i> <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a></li>
          <li><a href="../logout"><i class="fa fa-sign-out"></i> Logout</a></li>
        <?php else: ?>
          <li><a href="../login"><i class="fa fa-sign-in"></i> Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

  <!-- KPIs -->
  <div class="row">
    <div class="col-sm-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-title">NegÃ³cios</div>
        <div class="kpi-value"><?php echo $totBusinesses; ?></div>
        <div class="kpi-sub"><?php echo $totActiveBiz; ?> ativos</div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-title">Categorias</div>
        <div class="kpi-value"><?php echo $totCategories; ?></div>
        <div class="kpi-sub">Principais + subcats</div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-title">Cidades</div>
        <div class="kpi-value"><?php echo $totCities; ?></div>
        <div class="kpi-sub">Locais cadastrados</div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-title">UsuÃ¡rios</div>
        <div class="kpi-value"><?php echo $totUsers; ?></div>
        <div class="kpi-sub">Contas no sistema</div>
      </div>
    </div>
  </div>

  <!-- Atalhos rÃ¡pidos -->
  <div class="panel">
    <h3><i class="fa fa-bolt"></i> Atalhos RÃ¡pidos</h3>
    <p class="quick-links">
      <a class="btn btn-default btn-sm" href="categories.php"><i class="fa fa-sitemap"></i> Gerir Categorias</a>
      <a class="btn btn-default btn-sm" href="cities.php"><i class="fa fa-map-marker"></i> Gerir Cidades</a>
      <a class="btn btn-default btn-sm" href="business.php"><i class="fa fa-building"></i> Gerir NegÃ³cios</a>
      <a class="btn btn-default btn-sm" href="users.php"><i class="fa fa-users"></i> Gerir UsuÃ¡rios</a>
      <a class="btn btn-default btn-sm" href="settings.php"><i class="fa fa-sliders"></i> Settings</a>
    </p>
  </div>

  <!-- Ãšltimos negÃ³cios (exemplo simples) -->
  <div class="panel">
    <h3><i class="fa fa-clock-o"></i> Ãšltimos NegÃ³cios</h3>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th style="width:70px;">ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Cidade</th>
            <th>Status</th>
            <th style="width:140px;">Criado em</th>
          </tr>
        </thead>
        <tbody>
        <?php
        if ($res = @$mysqli->query("
            SELECT b.biz_id, b.business_name, c.category, b.city, b.active, b.date
            FROM business b
            LEFT JOIN categories c ON c.cat_id = b.cid
            ORDER BY b.biz_id DESC
            LIMIT 10
        ")) {
            while ($r = $res->fetch_assoc()) {
                $status = ((int)$r['active'] === 1) ? '<span class="label label-success">Ativo</span>' : '<span class="label label-default">Inativo</span>';
                echo '<tr>';
                echo '<td>'.(int)$r['biz_id'].'</td>';
                echo '<td>'.htmlspecialchars($r['business_name'] ?? '', ENT_QUOTES, 'UTF-8').'</td>';
                echo '<td>'.htmlspecialchars($r['category'] ?? '', ENT_QUOTES, 'UTF-8').'</td>';
                echo '<td>'.htmlspecialchars($r['city'] ?? '', ENT_QUOTES, 'UTF-8').'</td>';
                echo '<td>'.$status.'</td>';
                echo '<td>'.htmlspecialchars($r['date'] ?? '', ENT_QUOTES, 'UTF-8').'</td>';
                echo '</tr>';
            }
            $res->close();
        } else {
            echo '<tr><td colspan="6">NÃ£o foi possÃ­vel carregar a lista.</td></tr>';
            error_log('[admincp] Falha ao carregar Ãºltimos negÃ³cios.');
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>

</div><!-- /.container -->

</body>
</html>

