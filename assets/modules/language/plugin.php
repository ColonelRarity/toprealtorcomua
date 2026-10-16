<?php
/**
 * Языковой плагин
 */
$modx->config['lang'] = in_array($_GET['lang'], explode(",", $modx->config['lang_list'])) ? $_GET['lang'] : $modx->config['lang_default'];
switch ($modx->event->name) {
    case "OnWebPageInit":
        break;
    case "OnLoadWebDocument":
        if (is_array($_POST)) foreach ($_POST as $k => $v) if (!is_array($v)) $modx->documentObject['post_'.strip_tags($k)] = strip_tags($v);
        if (is_array($_GET)) foreach ($_GET as $k => $v) if (!is_array($v)) $modx->documentObject['get_'.strip_tags($k)] = strip_tags($v);

        if (!isset($modx->documentObject['get_q'])) {$modx->documentObject['get_q'] = '';}

        if ($modx->config['lang_enable']) {
            // multilanguage fields
            $danger_fields = Array('pagetitle', 'longtitle', 'description', 'link_attributes', 'introtext', 'content', 'menutitle');
            foreach ($danger_fields as $field) {
                $temp = $modx->documentObject[$field.'_'.$modx->config['lang']];
                if(!empty($temp) &&  $modx->documentObject['template'] != 8){
                   $modx->documentObject[$field] = $temp;
                }
                
            }
 
            // tv parameters
            foreach ($modx->documentObject as $field) {
                if (is_array($field) && is_string($field[0]) && $modx->documentObject['template'] != 8) {
                    if (substr($field[0], -3) == "_".$modx->config['lang']) {
                        $modx->documentObject[str_replace(substr($field[0], -3), "", $field[0])] = $modx->documentObject[$field[0]];
                    }
                }
            }
        }
        break;
    case "OnParseDocument":
        if ($modx->config['lang_enable']) {
            // parse language strings
            preg_match_all('/\[#(\d+)#\]/', $modx->documentOutput, $match);
            $dump = $_SERVER['DOCUMENT_ROOT']."/assets/modules/language/dump.php";
            if (file_exists($dump)) {
                require $dump;
                if ($match[0]) {
                    foreach ($match[0] as $key => $value) {
                        $modx->documentOutput = str_replace($value, $l[$match[1][$key]][$modx->config['lang']], $modx->documentOutput);
                    }
                }
            }
            // parse language urls
            preg_match_all('/\[~~(\d+)~~\]/', $modx->documentOutput, $match);
            if ($match[0]) {
                foreach ($match[0] as $key => $value) {
                    if ($match[1][$key] == $modx->config['site_start']) {
                        if ($modx->config['lang'] == $modx->config['lang_default']) {
                            $modx->documentOutput = str_replace($value, "/", $modx->documentOutput);
                        } else {
                            $modx->documentOutput = str_replace($value, "/".$modx->config['lang']."/", $modx->documentOutput);
                        }
                    } else {
                       $fullUrl = $modx->makeUrl($match[1][$key], '', '', 'full');
                       $siteUrl = rtrim($modx->config['site_url'], '/');
                       if ($siteUrl !== '' && strpos($fullUrl, $siteUrl) === 0) {
                           $fullUrl = ltrim(substr($fullUrl, strlen($siteUrl)), '/');
                       }
                       $modx->documentOutput = str_replace($value, $fullUrl, $modx->documentOutput);
                    }
                }
            }
        }
        // ditto 1st page pagination seo fix
        $modx->documentOutput = str_replace("?start=0", "", $modx->documentOutput);
        $modx->documentOutput = str_replace("?p=0", "", $modx->documentOutput);
        break;


        

    case "OnCacheUpdate": // выполняется при сбрасывание кеша сайта в админке
        if ($_GET['a'] == 26) { // иначе будет выполнятся при любом чихе в админке
        }
        break;
}