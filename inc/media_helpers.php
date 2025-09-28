<?php
/* Helpers de imagem compartilhados (home, category, subcategory, etc.) */

function normalize_http_dup(string $url): string {
    return preg_replace('~^(https?://)+~i', '$1', $url);
}

/**
 * Placeholder SEM http(s) e SEM barra inicial, para não herdar o espaço da pasta.
 * thumbs.php aceita caminho relativo tipo "images/placeholder.png".
 */
function home_placeholder_url(): string {
    return 'images/placeholder.png';
}

function biz_original_image_url(?string $featured): ?string {
    $featured = trim((string)$featured);
    if ($featured === '') { return null; }

    // Se já é URL absoluta, só normaliza duplicado
    if (preg_match('~^https?://~i', $featured)) {
        return normalize_http_dup($featured);
    }

    // Retornamos RELATIVO p/ evitar http:// + espaço na pasta: "uploads/<arquivo>"
    // Importante: encode apenas o nome do arquivo, não a pasta toda
    return 'uploads/' . rawurlencode($featured);
}

/**
 * Monta SEMPRE thumbs.php com src=(original ou placeholder).
 * Garante que espaços na URL relativa sejam %20 (caso alguém tenha subpasta com espaço).
 */
function biz_thumb_src(?string $featured, int $h=300, int $w=500, int $q=100, ?int $bizId=null): string {
    $orig = biz_original_image_url($featured);

    // Se não veio arquivo, cai pro placeholder
    if (!$orig || preg_match('~/(uploads/?|uploads)$~i', $orig)) {
        $ph = home_placeholder_url(); // "images/placeholder.png"
        $who = $bizId !== null ? "biz_id={$bizId}" : "sem biz_id";
        error_log("[placeholder] usando placeholder ({$who}); src={$ph}");
        return 'thumbs.php?src=' . rawurlencode($ph) . "&h={$h}&w={$w}&q={$q}";
    }

    // Evita "http://http://" e força %20 caso haja espaços em segmentos
    $orig = normalize_http_dup($orig);
    $orig = preg_replace('/\s+/', '%20', $orig);

    return 'thumbs.php?src=' . rawurlencode($orig) . "&h={$h}&w={$w}&q={$q}";
}
