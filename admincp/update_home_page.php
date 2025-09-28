<?php
// admin/update_home_page.php
header('Content-Type: text/html; charset=utf-8');
include('../db.php');

// Pasta de upload (relativa ao diretório atual /admin)
$UploadDirectory = realpath(__DIR__ . '/../images') . DIRECTORY_SEPARATOR;

if ($UploadDirectory === false || !is_dir($UploadDirectory) || !is_writable($UploadDirectory)) {
    die('<div class="alert alert-danger">Upload directory inválido ou sem permissão: ' . htmlspecialchars($UploadDirectory) . '</div>');
}

function upload_errors($err_code) {
    switch ($err_code) {
        case UPLOAD_ERR_INI_SIZE:   return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:  return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:    return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:    return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR: return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE: return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:  return 'File upload stopped by extension';
        default:                    return 'Unknown upload error';
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('<div class="alert alert-danger" role="alert">Invalid request.</div>');
}

$LineOneRaw = isset($_POST['inputLineOne']) ? trim($_POST['inputLineOne']) : '';
$LineOne    = $mysqli->real_escape_string($LineOneRaw);

// Flag para saber se teve upload de arquivo válido
$didUpload = false;

if (isset($_FILES['inputfile']) && is_array($_FILES['inputfile']) && ($_FILES['inputfile']['error'] !== UPLOAD_ERR_NO_FILE)) {

    if (!empty($_FILES['inputfile']['error'])) {
        die('<div class="alert alert-danger" role="alert">' . upload_errors($_FILES['inputfile']['error']) . '</div>');
    }

    $FileType  = isset($_FILES['inputfile']['type']) ? strtolower($_FILES['inputfile']['type']) : '';
    $FileSize  = (int)($_FILES['inputfile']['size'] ?? 0);
    $FileName  = (string)($_FILES['inputfile']['name'] ?? '');
    $TmpName   = (string)($_FILES['inputfile']['tmp_name'] ?? '');

    // Limite de 8MB (ajuste se precisar)
    if ($FileSize <= 0 || $FileSize > 8 * 1024 * 1024) {
        die('<div class="alert alert-danger" role="alert">File too large or empty. Max 8MB.</div>');
    }

    // Extensão
    $ext = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));

    // Tipos permitidos
    $allowedMime = ['image/jpeg', 'image/jpg', 'image/png'];
    $allowedExt  = ['jpg', 'jpeg', 'png'];

    if (!in_array($FileType, $allowedMime, true) || !in_array($ext, $allowedExt, true)) {
        die('<div class="alert alert-danger" role="alert">Unsupported file! Please upload JPEG or PNG.</div>');
    }

    // Nome final (mantém a extensão)
    $NewLogoName = 'promo.' . ($ext === 'jpeg' ? 'jpg' : $ext); // normaliza jpeg -> jpg

    // Apaga a outra extensão, se existir (evita ficar com promo.png e promo.jpg ao mesmo tempo)
    $otherExt = ($NewLogoName === 'promo.jpg') ? 'png' : 'jpg';
    $otherPath = $UploadDirectory . 'promo.' . $otherExt;
    if (is_file($otherPath)) { @unlink($otherPath); }

    // Caminho final
    $destPath = $UploadDirectory . $NewLogoName;

    if (!@move_uploaded_file($TmpName, $destPath)) {
        die('<div class="alert alert-danger" role="alert">Failed to save uploaded file.</div>');
    }

    // Permissões (opcional em Windows, útil em Linux)
    @chmod($destPath, 0644);

    $didUpload = true;
}

// Atualiza o texto da home (sempre)
$ok = @$mysqli->query("UPDATE settings SET home_text='{$LineOne}' WHERE id=1");

if ($ok) {
    if ($didUpload) {
        echo '<div class="alert alert-success" role="alert">Home page settings updated successfully (text + promo image).</div>';
    } else {
        echo '<div class="alert alert-success" role="alert">Home page text updated successfully.</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Text not updated (no DB change), but upload may have succeeded.</div>';
}
