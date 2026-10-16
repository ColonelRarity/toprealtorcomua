<?php
/**
 * Генератор sitemap'ов для MODX (PHP 5.4)
 * - Языковые карты: sitemap_ru.xml, sitemap_ua.xml
 * - Индекс:         sitemap.xml  (перечень языковых карт)
 * - Комбо-файл:     sitemap_all.xml (urlset с альтернативами hreflang)
 */

if (!defined('MODX_BASE_PATH')) {
    define('MODX_BASE_PATH', dirname(__FILE__) . '/');
}

// === НАСТРОЙКИ ===
$domainBase = rtrim($modx->config['site_url'], '/') . '/';  // базовый публичный URL
$maps = array(
    'ru' => 'sitemap_ru.xml',
    'ua' => 'sitemap_ua.xml',
);
$indexFilenameIndex = 'sitemap_index.xml';       // индекс (то, что просили)
$combinedFilename   = 'sitemap_all.xml';   // комбинированный urlset с hreflang (опционально)

$logFile = MODX_BASE_PATH . 'assets/cache/sitemap_debug.log';

// === ХЕЛПЕРЫ ===
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function url_join($base, $path){ return rtrim($base, '/') . '/' . ltrim($path, '/'); }
function log_line($msg){
    @file_put_contents(MODX_BASE_PATH . 'assets/cache/sitemap_debug.log',
        '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n",
        FILE_APPEND
    );
}
/** Атомарная запись: tmp -> rename + верификация + chmod */
function write_atomic($path, $content){
    $dir = dirname($path);
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Cannot create dir: ' . $dir);
    }
    $tmp = tempnam($dir, 'sm_');
    if ($tmp === false) throw new RuntimeException('Cannot create tmp for: ' . $path);
    $bytes = file_put_contents($tmp, $content, LOCK_EX);
    if ($bytes === false) {
        @unlink($tmp);
        throw new RuntimeException('Cannot write tmp for: ' . $path);
    }
    // пробуем атомарно перенести
    if (!@rename($tmp, $path)) {
        if (!@copy($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException('Cannot move to: ' . $path);
        }
        @unlink($tmp);
    }
    @chmod($path, 0644);
    clearstatcache(true, $path);
    $fs = @filesize($path);
    log_line("WROTE: $path (bytes_written=$bytes, final_size=$fs)");
    return $fs;
}
/** hreflang-код: для украинского — 'uk' */
function hreflang_code($lang){ return ($lang === 'ua') ? 'uk' : $lang; }

// === ПОДГОТОВКА ===
$today = date('Y-m-d');
// Собираем все URL: $allUrls[$key]['ru'|'ua'] = абсолютный URL
$allUrls = array();

// Нормализуем относительный путь каталога (ID 134)
$shopRel = ltrim($modx->makeUrl(134, '', '', ''), '/');
if (strpos($shopRel, '://') !== false) {
    $parts = parse_url($shopRel);
    $shopRel = ltrim(isset($parts['path']) ? $parts['path'] : '/', '/');
}
$shopRel = rtrim($shopRel, '/') . '/';

// === 1) ЯЗЫКОВЫЕ КАРТЫ + сбор для комбо ===
foreach ($maps as $lang => $sitemapName) {
    $langPrefix = ($lang === 'ua') ? 'ua/' : '';

    $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    // Главная
    $homeRelKey = '';
    $homeUrlAbs = url_join($domainBase, $langPrefix);
    $xml .= '  <url><loc>' . h($homeUrlAbs) . '</loc><lastmod>' . $today . "</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>\n";
    if (!isset($allUrls[$homeRelKey])) $allUrls[$homeRelKey] = array();
    $allUrls[$homeRelKey][$lang] = $homeUrlAbs;

    // Страницы
    $qPages = $modx->db->query("SELECT id FROM `modx_site_content` WHERE searchable=1 AND published=1 AND deleted=0 AND id != 1");
    while ($row = $modx->db->getRow($qPages)) {
        $rel = ltrim($modx->makeUrl((int)$row['id'], '', '', ''), '/');
        $rel = rtrim($rel, '/') . '/';
        $key = ($langPrefix && strpos($rel, 'ua/') === 0) ? substr($rel, 3) : $rel;
        $loc = url_join($domainBase, $langPrefix . $rel);

        $xml .= '  <url><loc>' . h($loc) . "</loc><changefreq>weekly</changefreq></url>\n";
        if (!isset($allUrls[$key])) $allUrls[$key] = array();
        $allUrls[$key][$lang] = $loc;
    }

    // Объекты
    $qObj = $modx->db->query("SELECT url FROM `modx_a_flat` WHERE active = 1");
    while ($o = $modx->db->getRow($qObj)) {
        $slug = trim($o['url'], '/') . '/';
        $rel  = $shopRel . $slug;
        $loc  = url_join($domainBase, $langPrefix . $rel);

        $xml .= '  <url><loc>' . h($loc) . "</loc><changefreq>weekly</changefreq></url>\n";
        if (!isset($allUrls[$rel])) $allUrls[$rel] = array();
        $allUrls[$rel][$lang] = $loc;
    }

    $xml .= "</urlset>\n";

    // пишем языковую карту
    write_atomic(MODX_BASE_PATH . $sitemapName, $xml);
}

// === 2) ИНДЕКС (sitemap.xml) — перечисление языковых карт ===
// ---- DEBUG-хелпер ----
if (!function_exists('dlog')) {
    function dlog($msg){
        @file_put_contents(
            rtrim($GLOBALS['modx']->config['base_path'], '/\\').'/assets/cache/sitemap_debug.log',
            '['.date('Y-m-d H:i:s').'] '.$msg."\n",
            FILE_APPEND
        );
    }
}

$bp = rtrim($modx->config['base_path'], '/\\').DIRECTORY_SEPARATOR;
dlog("INDEX: domainBase=".$domainBase." base_path=".$bp);

// === 2) ИНДЕКС всех языковых карт: sitemap.xml ===
$indexXml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$indexXml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

foreach ($maps as $lang => $sitemapName) {
    // Абсолютный URL для <loc>
    $loc = url_join($domainBase, $sitemapName);

    // Физический путь и проверка существования/размера
    $filePath = $bp . $sitemapName;
    $exists   = file_exists($filePath);
    $size     = $exists ? (int)filesize($filePath) : 0;

    // Логируем всё по каждой записи
    dlog("INDEX item: lang={$lang} name={$sitemapName} loc={$loc} path={$filePath} exists=" . ($exists?'yes':'no') . " size={$size}");

    // (опционально) если файл отсутствует — можно пропустить его в индексе:
    // if (!$exists) { dlog("SKIP {$sitemapName} — file not found"); continue; }

    $indexXml .= "  <sitemap>\n";
    $indexXml .= "    <loc>" . h($loc) . "</loc>\n";
    $indexXml .= "    <lastmod>" . $today . "</lastmod>\n";
    $indexXml .= "  </sitemap>\n";
}

$indexXml .= "</sitemapindex>\n";

// Лог длины и первые байты индекса
dlog("INDEX built length=" . strlen($indexXml));
dlog("INDEX preview:\n" . substr($indexXml, 0, 300));

// Пишем ИНДЕКС в КОРЕНЬ ИМЕННО ЧЕРЕЗ base_path
$indexTarget = $bp . $indexFilenameIndex;
$writtenSize = write_atomic($indexTarget, $indexXml);
dlog("INDEX write: target={$indexTarget} final_size={$writtenSize}");

// === 3) КОМБО (sitemap_all.xml) — urlset с hreflang (опционально) ===
$combinedXml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$combinedXml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xhtml=\"http://www.w3.org/1999/xhtml\">\n";

foreach ($allUrls as $key => $variants) {
    if (!is_array($variants) || !count($variants)) continue;
    $first   = reset($variants);
    $locMain = isset($variants['ru']) ? $variants['ru'] : $first;

    $combinedXml .= "  <url>\n";
    $combinedXml .= "    <loc>" . h($locMain) . "</loc>\n";

    foreach ($variants as $lang => $abs) {
        $combinedXml .= '    <xhtml:link rel="alternate" hreflang="' . h(hreflang_code($lang)) . '" href="' . h($abs) . "\" />\n";
    }
    if (isset($variants['ru']) && isset($variants['ua'])) {
        $combinedXml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . h($variants['ru']) . "\" />\n";
    }

    $combinedXml .= "  </url>\n";
}
$combinedXml .= "</urlset>\n";

write_atomic(MODX_BASE_PATH . $combinedFilename, $combinedXml);

// Готово, подробности — в assets/cache/sitemap_debug.log

@file_put_contents(
  $modx->config['base_path'].'assets/cache/sitemap_debug.log',
  "[bp] ".$modx->config['base_path']."\n[mbp] ".(defined('MODX_BASE_PATH')?MODX_BASE_PATH:'(undef)')."\n[docroot] ".(isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:'(undef)')."\n",
  FILE_APPEND
);
