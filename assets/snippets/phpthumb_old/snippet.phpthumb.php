<?php
/**
 * phpthumb
 *
 * PHPThumb creates thumbnails and altered images on the fly and caches them
 *
 * @category 	snippet
 * @version 	1.3
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 * @documentation Usage: [[R? &img=`[+image+]` &opt=`w=150,h=76,far=C,bg=FFFFFF`]]
 * @documentation phpThumb docs http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
 * @reportissues https://github.com/modxcms/evolution
 * @link        noimage.png here [+site_url+]assets/snippets/phpthumb/noimage.png
 * @author      Bumkaka and fix Seiger
 * @author      Many contributors since then
 * @lastupdate  21/07/2017
 */

if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

$newfolderaccessmode = $modx->config['new_folder_permissions'] ? octdec($modx->config['new_folder_permissions']) : 0777;

$cacheFolder = isset($cacheFolder) ? $cacheFolder : "assets/cache/images";
if(!is_dir(MODX_BASE_PATH.$cacheFolder)) {
    mkdir(MODX_BASE_PATH.$cacheFolder);
    chmod(MODX_BASE_PATH.$cacheFolder, $newfolderaccessmode);
}

$tmpFolder = 'assets/cache/tmp';
if (!empty($img)) $img = rawurldecode($img);

if(empty($img) || !file_exists(MODX_BASE_PATH . $img)){
    $img = isset($noImage) ? $noImage : 'assets/snippets/phpthumb/noimage.png';
}

// allow read in phpthumb cache folder
if (strpos($cacheFolder, 'assets/cache/') === 0 && $cacheFolder != 'assets/cache/' && !is_file(MODX_BASE_PATH . $cacheFolder . '/.htaccess')) {
    file_put_contents(MODX_BASE_PATH . $cacheFolder . '/.htaccess', "order deny,allow\nallow from all\n");
}

if(!is_dir(MODX_BASE_PATH.$tmpFolder)) {
    mkdir(MODX_BASE_PATH.$tmpFolder);
    chmod(MODX_BASE_PATH.$tmpFolder, $newfolderaccessmode);
}

$path_parts = pathinfo($img);
$ext = strtolower($path_parts['extension']);
$opt = 'f='.(in_array($ext,explode(",","png,gif,jpeg,jpg,webp,svg"))?$ext:"jpg&q=96").'&'.strtr($opt, Array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
parse_str($opt, $params);

$fname = md5($img.$opt).".".$params['f'];

if ($folder != "") {
    $folder = explode("/", $folder);
    foreach ($folder as $tfolder) {
        if (!empty($tfolder)) {
            $cacheFolder.= "/".$tfolder;
            if(!is_dir(MODX_BASE_PATH.$cacheFolder)) {
                mkdir(MODX_BASE_PATH.$cacheFolder);
                chmod(MODX_BASE_PATH.$cacheFolder, $newfolderaccessmode);
            }
        }
    }
} else {
	$cacheFolder = $cacheFolder.'/'.$fname[0];
	if(!is_dir(MODX_BASE_PATH.$cacheFolder)) {
		mkdir(MODX_BASE_PATH.$cacheFolder);
		chmod(MODX_BASE_PATH.$cacheFolder, $newfolderaccessmode);
	}
}

$outputFilename = MODX_BASE_PATH.$cacheFolder.'/'.$fname;
if (!file_exists($outputFilename)) {
    if ($ext == 'svg') {
        $svg = file_get_contents(MODX_BASE_PATH . $img);
        $reW = '/(.*<svg[^>]* width=")([\d.]+)(.*)/si';
        $reH = '/(.*<svg[^>]* height=")([\d.]+)(.*)/si';

        preg_match($reW, $svg, $mw);
        preg_match($reH, $svg, $mh);
        $width = intval(trim($mw[2]));
        $height = intval(trim($mh[2]));

        if (($width || $height) && ($params['w'] || $params['h'])) {
            if ($params['w'] && $width) {
                $scale = $params['w']/$width;
                if ($height) {
                    $height = intval($scale * $height);
                }
            }
            if ($params['h'] && $height) {
                $scale = $params['h']/$height;
                if ($width) {
                    $width = intval($scale * $width);
                }
            }
            if ($height && $height > $params['h']) {
                $svg = preg_replace($reW, "\${1}{$width}\${3}", $svg);
                $svg = preg_replace($reH, "\${1}{$params['h']}\${3}", $svg);
            }
            if ($width && $width > $params['w']) {
                $svg = preg_replace($reW, "\${1}{$params['w']}\${3}", $svg);
                $svg = preg_replace($reH, "\${1}{$height}\${3}", $svg);
            }
            if ($height <= $params['h'] && $width <= $params['w']) {
                if ($params['w'] > $params['h']) {
                    $svg = preg_replace($reW, "\${1}{$width}\${3}", $svg);
                    $svg = preg_replace($reH, "\${1}{$params['h']}\${3}", $svg);
                } else {
                    $svg = preg_replace($reW, "\${1}{$params['w']}\${3}", $svg);
                    $svg = preg_replace($reH, "\${1}{$height}\${3}", $svg);
                }
            }
        }
        file_put_contents($outputFilename, $svg);
    } else {
        require_once MODX_BASE_PATH.'assets/snippets/phpthumb/phpthumb.class.php';
        $phpThumb = new phpthumb();
        $phpThumb->config_temp_directory = MODX_BASE_PATH.$tmpFolder;
        $phpThumb->config_document_root = MODX_BASE_PATH;
        $phpThumb->config_cache_directory = MODX_BASE_PATH.$tmpFolder;
        $phpThumb->setSourceFilename(MODX_BASE_PATH . $img);
        foreach ($params as $key => $value) {
            $phpThumb->setParameter($key, $value);
        }
        if ($phpThumb->GenerateThumbnail()) {
            $phpThumb->RenderToFile($outputFilename);
        } else {
            $modx->logEvent(0, 3, implode('<br/>', $phpThumb->debugmessages), 'phpthumb');
        }
    }
}
return "/".$cacheFolder."/".rawurlencode($fname);