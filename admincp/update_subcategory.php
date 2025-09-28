<?php
/****************************************************
 * admincp/update_subcategory.php
 * Atualiza Subcategoria com validaÃ§Ãµes + ERROR LOG
 ****************************************************/

/* =============== ERROR LOG =============== */
error_reporting(E_ALL);
ini_set('display_errors', 0);
$__logDir  = __DIR__ . DIRECTORY_SEPARATOR . 'logs';
$__logFile = $__logDir . DIRECTORY_SEPARATOR . 'php_errors.log';
if (!is_dir($__logDir)) { @mkdir($__logDir, 0777, true); }
if (!is_dir($__logDir)) { $__logFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bizlister_admin_php_errors.log'; }
ini_set('log_errors', 1);
ini_set('error_log', $__logFile);
error_log("===== [update_subcategory.php] request: " . ($_SERVER['REQUEST_URI'] ?? '') . " =====");

/* =============== SESSION =============== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =============== DB =============== */
require __DIR__ . '/../db.php';

/* =============== Helpers de resposta =============== */
function respond_alert(string $type, string $message) {
    // $type: success | danger | warning | info
    $type = preg_replace('~[^a-z]+~i', '', $type);
    echo '<div class="alert alert-' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '" role="alert">'
       . htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
       . '</div>';
    exit;
}

/* =============== Checagem de mÃ©todo =============== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('[update_subcategory] MÃ©todo invÃ¡lido: ' . $_SERVER['REQUEST_METHOD']);
    respond_alert('danger', 'Invalid request method.');
}

/* =============== Captura/validaÃ§Ã£o de parÃ¢metros =============== */
$id = 0;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
}
if ($id <= 0) {
    error_log('[update_subcategory] ID invÃ¡lido ou ausente.');
    respond_alert('danger', 'Invalid subcategory ID.');
}

$ParentCategory      = isset($_POST['inputCategory'])    ? trim((string)$_POST['inputCategory'])    : '';
$CategoryTitle       = isset($_POST['inputTitle'])       ? trim((string)$_POST['inputTitle'])       : '';
$CategoryDescription = isset($_POST['inputDescription']) ? trim((string)$_POST['inputDescription']) : '';

if ($ParentCategory === '' || !ctype_digit($ParentCategory)) {
    respond_alert('danger', 'Please select a parent category.');
}
$ParentCategory = (int)$ParentCategory;

if ($CategoryTitle === '') {
    respond_alert('danger', 'Please enter desired category.');
}
if ($CategoryDescription === '') {
    respond_alert('danger', 'Please enter description for your new category.');
}
if ($ParentCategory === $id) {
    respond_alert('danger', 'Parent category cannot be the same as the subcategory.');
}

/* =============== VerificaÃ§Ãµes no banco =============== */

// Verifica se a subcategoria (alvo do update) existe
$existsSub = false;
if ($stmt = $mysqli->prepare("SELECT 1 FROM categories WHERE cat_id = ? LIMIT 1")) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute() && ($res = $stmt->get_result())) {
        $existsSub = (bool)$res->fetch_row();
        $res->free();
    }
    $stmt->close();
}
if (!$existsSub) {
    error_log("[update_subcategory] Subcategory cat_id={$id} nÃ£o encontrada.");
    respond_alert('danger', 'Subcategory not found.');
}

// Verifica se o parent existe e Ã© realmente uma categoria vÃ¡lida
$existsParent = false;
if ($stmt = $mysqli->prepare("SELECT 1 FROM categories WHERE cat_id = ? LIMIT 1")) {
    $stmt->bind_param('i', $ParentCategory);
    if ($stmt->execute() && ($res = $stmt->get_result())) {
        $existsParent = (bool)$res->fetch_row();
        $res->free();
    }
    $stmt->close();
}
if (!$existsParent) {
    error_log("[update_subcategory] Parent category cat_id={$ParentCategory} nÃ£o encontrada.");
    respond_alert('danger', 'Selected parent category does not exist.');
}

/* =============== AtualizaÃ§Ã£o (prepared) =============== */
if ($stmt = $mysqli->prepare("UPDATE categories SET category = ?, cat_description = ?, parent_id = ? WHERE cat_id = ?")) {
    $stmt->bind_param('ssii', $CategoryTitle, $CategoryDescription, $ParentCategory, $id);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        error_log("[update_subcategory] cat_id={$id} atualizado com sucesso (parent_id={$ParentCategory}).");
        respond_alert('success', 'Category updated successfully.');
    } else {
        error_log("[update_subcategory][ERR] Falha ao atualizar cat_id={$id}: {$err}");
        respond_alert('danger', 'There was a problem updating the category. Please try again.');
    }
} else {
    error_log("[update_subcategory][ERR] Prepare falhou: " . $mysqli->error);
    respond_alert('danger', 'There was a problem. Please try again.');
}

