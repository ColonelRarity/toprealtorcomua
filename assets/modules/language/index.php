<?php
/**
 *	Модуль управления языками и доменами
 */
if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");
global $_lang;
global $modx_lang_attribute;
define("ROOT", dirname(__FILE__));
$get    = $_REQUEST['get'] ?: "translates";
$res    = [];
$m_lang = is_file(ROOT.'/langs.php') ? require_once ROOT.'/langs.php' : [$modx_lang_attribute => []];
$_lang  = is_array($m_lang[$modx_lang_attribute]) ? array_merge($_lang, $m_lang[$modx_lang_attribute]) : array_merge($_lang, reset($m_lang));
$res['url']     = $table['url'] = $url = "index.php?a=112&id=".$_REQUEST['id']."";
$res['get']     = $_REQUEST['get'];
$res['title']   = $_lang['language_management'];
$res['version'] = "v 2.1 (makeup edition)";
$modx->config['lang_list']    = $modx->config['lang_list'] ?: $modx_lang_attribute;
$modx->config['lang_default'] = $modx->config['lang_default'] ?: $modx_lang_attribute;
$table_system_settings        = $modx->getFullTableName('system_settings');
$table_site_content           = $modx->getFullTableName('site_content');
$table_a_lang                 = $modx->getFullTableName('a_lang');
$table_a_filter_values        = $modx->getFullTableName('a_filter_values');

switch ($get) {
    default:
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
        switch ($action) {
            case "search":
                $config = explode(",", $modx->config['lang_list']);
                foreach ($config as $key) {
                    $where[] = $key . " LIKE '%".$modx->db->escape($_POST['search'])."%'";
                }
                $items = $modx->db->query("
		    		SELECT *
           			FROM {$table_a_lang}
           			WHERE `lang_id` LIKE '".$modx->db->escape($_POST['search'])."%' 
           				OR ".implode(" OR ", $where)."
           			ORDER BY `lang_id` ASC
           		");
                while ($su = $modx->db->getRow($items)) {
                    $tmp = '<tr><td style="text-align:center !important;" class="vertical-align"><b>[#'.$su['lang_id'].'#]</b></td>';
                    foreach ($config as $lng) {
                        if ($lng == $modx->config['lang_default']) {
                            $tmp .= '
                                <td data-lang="'.$lng.'">
                                    <input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$lng.']" value="'.$su[$lng].'" />
                                </td>
                            ';
                        } else {
                            $tmp .= '
                                <td data-lang="'.$lng.'">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$lng.']" value="'.$su[$lng].'" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default js_translate" type="button" title="'.$_lang['language_auto'].' '.strtoupper($modx->config['lang_default']).' => '.strtoupper($lng).'">
                                                <img src="/assets/site/google-translate-icon.png" height="19px" alt="'.$_lang['language_auto'].'" />
                                            </button>
                                        </span>
                                    </div>
                                </td>
                            ';
                        }
                    }
                    $tmp .= '</tr>';
                    $result .= $tmp;
                }
                die($result);
            case "update":
                $lang = reset(array_keys($_POST['lang']));
                $id   = reset(array_keys($_POST['lang'][$lang]));
                $val  = $modx->db->escape(htmlspecialchars($_POST['lang'][$lang][$id], ENT_QUOTES));
                $modx->db->query("UPDATE {$table_a_lang} SET `{$id}` = '{$val}' WHERE `lang_id` = '{$lang}' ;" );
                update_lang_file();
                die;
            case "translate":
                $text   = $_POST['text'];
                $source = $_POST['source'];
                $target = $_POST['target'];
                $id     = reset(array_keys($_POST['lang']));
                $result = googleTranslate($text, $source, $target);
                $modx->db->query("UPDATE {$table_a_lang} SET `{$target}` = '".$modx->db->escape($result)."' WHERE `lang_id` = '{$id}'" );
                update_lang_file();
                die($result);
            case "save":
                $config = explode(",", $modx->config['lang_list']);
                if (is_array($_POST['lang'])) {
                    foreach ($_POST['lang'] as $key => $val) {
                        $update = Array();
                        foreach ($config as $value) {
                            $update[] = $value. " = '".$modx->db->escape(htmlspecialchars($val[$value], ENT_QUOTES))."'";
                        }
                        $modx->db->query("UPDATE {$table_a_lang} SET ".implode(",", $update)." WHERE `lang_id` = '".$key."'");
                    }
                }
                update_lang_file();
                die(header("Location: ".$url."&get=translate"));
            default:
                $config = explode(",", $modx->config['lang_list']);
                if ($action == "add" && $_SESSION['hash']['tongue']['add'] != md5(serialize($_POST))) {
                    $_SESSION['hash']['tongue']['add'] = md5(serialize($_POST));
                    $data   = Array();
                    foreach ($config as $v) {
                        $data[] = "'".$modx->db->escape(htmlspecialchars($_POST['add'][$v], ENT_QUOTES))."'";
                    }
                    $modx->db->query("INSERT INTO {$table_a_lang} (`lang_id`, ".$modx->config['lang_list'].") VALUES (null, ".implode(",", $data).")");
                    update_lang_file();
                }
                // add + thead
                foreach ($config as $lng){
                    $table['thead'] .= '<th style="text-align:center !important;">'.strtoupper($lng).'</th>';
                    if ($lng == $modx->config['lang_default']) {
                        $table['add'] .= '
                            <tr>
                                <td>'.strtoupper($lng).'</td>
                                <td data-lang="'.$lng.'">
                                    <input type="text" class="form-control" name="add['.$lng.']" value="" />
                                </td>
                            </tr>    
                        ';
                    } else {
                        $table['add'] .= '
                            <tr>
                                <td>'.strtoupper($lng).'</td>
                                <td data-lang="'.$lng.'">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="add['.$lng.']" value="" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default js_translate" type="button" title="'.$_lang['language_auto'].' '.strtoupper($modx->config['lang_default']).' => '.strtoupper($lng).'">
                                                <img src="/assets/site/google-translate-icon.png" height="19px" alt="'.$_lang['language_auto'].'" />
                                            </button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        ';
                    }
                }
                // tbody
                $limit = 30;
                $page  = isset($_REQUEST['p']) ? ((int)($_REQUEST['p'])-1) * $limit : 0;
                $items = $modx->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$table_a_lang} ORDER BY `lang_id` DESC LIMIT ".$page.", ".$limit);
                $pages = $modx->db->getRow($modx->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
                $modx->setPlaceholder("max_items", $pages['cnt']);
                $modx->setPlaceholder("limit_items", $limit);
                $table['tbody'] = "";
                while ($su = $modx->db->getRow($items)) {
                    $tmp = '<tr><td style="text-align:center !important;" class="vertical-align"><b>[#'.$su['lang_id'].'#]</b></td>';
                    foreach ($config as $lng) {
                        if ($lng == $modx->config['lang_default']) {
                            $tmp .= '
                                <td data-lang="'.$lng.'">
                                    <input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$lng.']" value="'.$su[$lng].'" />
                                </td>
                            ';
                        } else {
                            $tmp .= '
                                <td data-lang="'.$lng.'">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$lng.']" value="'.$su[$lng].'" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default js_translate" type="button" title="'.$_lang['language_auto'].' '.strtoupper($modx->config['lang_default']).' => '.strtoupper($lng).'">
                                                <img src="/assets/site/google-translate-icon.png" height="19px" alt="'.$_lang['language_auto'].'" />
                                            </button>
                                        </span>
                                    </div>
                                </td>
                            ';
                        }
                    }
                    $tmp .= '</tr>';
                    $table['tbody'] .= $tmp;
                }
                $table['col'] = count($config) + 1;
                $subtpl = 'tongue_tpl_translator';
                $modx->setPlaceholder('url', $url."&get=translates&p=");
                $pagin = getPaginate();
                break;
        }
        $tpl = "/tpl/translates.tpl";
        break;
    case "settings":
        if (count($_POST) > 0) {
            $post_lang_list = [];

            $post_lang_default = trim($_POST['lang_default']) ? trim($_POST['lang_default']) : $modx->config["lang_default"];
            $modx->db->query("REPLACE INTO `modx_system_settings` (`setting_value`, `setting_name`) VALUES ('{$post_lang_default}', 'lang_default')");

            if (is_array($_POST['lang_list']) && count($_POST['lang_list'])) {
                $post_lang_list = array_flip($_POST['lang_list']);
                unset($post_lang_list[$post_lang_default]);
                $post_lang_list = array_flip($post_lang_list);
                array_unshift($post_lang_list, $post_lang_default);
                $modx->db->query("REPLACE INTO `modx_system_settings` (`setting_value`, `setting_name`) VALUES ('".implode(',', $post_lang_list)."', 'lang_list')");
            }

            if (is_array($_POST['lang_front']) && count($_POST['lang_front'])) {
                $post_lang_front = array_flip($_POST['lang_front']);
                unset($post_lang_front[$post_lang_default]);
                $post_lang_front = array_flip($post_lang_front);
                array_unshift($post_lang_front, $post_lang_default);
                $modx->db->query("REPLACE INTO `modx_system_settings` (`setting_value`, `setting_name`) VALUES ('".implode(',', $post_lang_front)."', 'lang_front')");
            }

            if (is_array($post_lang_list) && count($post_lang_list)) {
                $langs = implode(',', $post_lang_list);

                if ($langs != $modx->config['lang_list']) {
                    // Модификация контентной таблицы
                    $columns = $needed = [];
                    $fields = $modx->db->query("DESCRIBE {$table_site_content}");

                    while ($f = $modx->db->getRow($fields)) {
                        $columns[] = $f['Field'];
                    }

                    foreach ($post_lang_list as $lng) {
                        if (!in_array("pagetitle_".$lng, $columns)) {
                            $needed[] = $lng;
                        }
                    }

                    if (count($needed)) {
                        foreach ($needed as $lng) {
                            if (!empty($lng)) {
                                $modx->db->query("
                                    ALTER TABLE {$table_site_content}
                                        ADD `pagetitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'pagetitle ".$lng." version',
                                        ADD `longtitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'longtitle ".$lng." version',
                                        ADD `description_".$lng."` 		varchar(512) COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'description ".$lng." version',
                                        ADD `link_attributes_".$lng."`  varchar(255) COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'link_attributes ".$lng." version',
                                        ADD `introtext_".$lng."` 		text COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'introtext ".$lng." version',
                                        ADD `menutitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL default '' COMMENT 'menutitle ".$lng." version',
                                        ADD `content_".$lng."` 			mediumtext COLLATE 'utf8_general_ci' NOT NULL COMMENT 'content ".$lng." version';
                                ");
                            }
                        }
                    }
                    // dump
                    $columns = $needed = [];
                    $fields = $modx->db->query("DESCRIBE {$table_a_lang}");

                    while ($f = $modx->db->getRow($fields)) {
                        $columns[] = $f['Field'];
                    }

                    foreach ($post_lang_list as $lng) {
                        if (!in_array($lng, $columns)) {
                            $needed[] = $lng;
                        }
                    }

                    if (count($needed)) {
                        foreach ($needed as $lng) {
                            if (!empty($lng)) {
                                $modx->db->query("ALTER TABLE {$table_a_lang} ADD `".$lng."` text COMMENT '".$lng."'");
                            }
                        }
                    }

                    // Модификация таблицы значений фильтров
                    $columns = $needed = [];
                    $check = "SHOW TABLES LIKE '".str_replace('`', '', end(explode('.', $table_a_filter_values)))."'";
                    if ((bool)$modx->db->getValue($modx->db->query($check))) {
                        $fields = $modx->db->query("DESCRIBE {$table_a_filter_values}");

                        while ($f = $modx->db->getRow($fields)) {
                            $columns[] = $f['Field'];
                        }

                        foreach ($post_lang_list as $lng) {
                            if (!in_array("filter_value_".$lng, $columns)) {
                                $needed[] = $lng;
                            }
                        }

                        if (count($needed)) {
                            foreach ($needed as $lng) {
                                if (!empty($lng)) {
                                    $modx->db->query("
                                        ALTER TABLE {$table_a_filter_values}
                                            ADD `filter_value_".$lng."` varchar(128) COLLATE 'utf8_general_ci' NULL;
					                ");
                                }
                            }
                        }
                    }
                }
            }

            // empty cache
            include_once MODX_MANAGER_PATH."processors/cache_sync.class.processor.php";
            $sync = new synccache();
            $sync->setCachepath(MODX_BASE_PATH."assets/cache/");
            $sync->setReport(false);
            $sync->emptyCache(); // first empty the cache
            die(header("Location: ".$url."&get=settings"));
        }
        $lang_list = is_file(ROOT.'/lang_list.php') ? require_once ROOT.'/lang_list.php' : [];
        $lang_conf = explode(',', $modx->config["lang_list"]);
        $lang_front = explode(',', $modx->config["lang_front"]);
        $tpl = "/tpl/settings.tpl";
        break;
    case "validate" :
        $response['status'] = 0;
        switch ($_REQUEST['ajax']) {
            case "url" :
                $ch = curl_init($_REQUEST['link']); // Создаем дескриптор cURL
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36");
                curl_exec($ch); // Запускаем
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch); // Закрываем дескриптор
                $response['status'] = (int)$http_code;
                break;
        }
        die(json_encode($response));
}

if (isset($tpl)) {
    ob_start();
    include ROOT . $tpl;
    $res['content'] = ob_get_contents();
    ob_end_clean();
}
include_once(includeFileProcessor("includes/header.inc.php", $manager_theme));
include ROOT . "/tpl/index.tpl";

/**
 *  getPaginate
 *  Постраничная навигация
 *  @admin_page_enable - чанк с шаблоном номерации страниц
 *  @admin_page_disable - чанк с шаблоном текущей страницы
 */
function getPaginate($tpl_enable = 'pagination_backend_enable', $tpl_disable = 'pagination_backend_disable', $classJS = ' js_change_page'){
    global $modx;
    if($modx->getPlaceholder("max_items") != '' && $modx->getPlaceholder("limit_items") != '') {
        $pages['all']   = ceil($modx->getPlaceholder("max_items") / $modx->getPlaceholder("limit_items"));
    }
    $prev           = $prev != '' ? $prev : '&laquo;';
    $next           = $next != '' ? $next : '&raquo;';
    $classPrev      = ($classPrev != '' ? $classPrev : 'prev').$classJS;
    $classNext      = ($classNext != '' ? $classNext : 'next').$classJS;
    $classPage      = ($classPage != '' ? $classPage : 'page').$classJS;
    $classCurrent   = $classCurrent != '' ? $classCurrent : 'active';
    $classSeparator = $classSeparator != '' ? $classSeparator : 'separator';
    $separator      = $separator != '' ? $separator : '...';
    $url            = $modx->getPlaceholder("url") != '' ? $modx->getPlaceholder("url") : '';
    if($pages['all'] > 1){
        $limit_pagination = 5;
        if(isset($_GET['p'])) {
            $curent = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        } else {
            $curent = ($modx->getPlaceholder("page")) ? $modx->getPlaceholder("page") : 1;
        }
        $start  = $curent - $limit_pagination;
        $end    = $curent + $limit_pagination;
        $start  = $start < 1 ? 1 : $start;
        $end    = $end > $pages['all'] ? $pages['all'] : $end;
        if($curent > 1) {
            $res .= $modx->parseChunk($tpl_enable , array('url' => $url."1", 'class' => $classPrev, 'title' => $prev, 'rel' => "prev"));
        }

        if($curent > $limit_pagination+1) {
            $res .= $modx->parseChunk($tpl_enable , array('url' => $url.'1', 'class' => $classPage, 'title' => '1', 'rel' => "prev")).$modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'title' => $separator));
        }
        for($i=$start; $i<$end+1; $i++) {
            if($i==($curent)) {
                $res .= $modx->parseChunk($tpl_disable, array('class' => $classCurrent, 'title' => $i, 'rel' => ""));
            } else {
                $res .= $modx->parseChunk($tpl_enable , array('url' => $url.$i, 'class' => $classPage, 'title' => $i,  "rel" => ($i < $curent) ? "prev" : "next"));
            }
        }
        if($curent < ($pages['all'] - $limit_pagination)) {
            $res .= $modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'title' => $separator)).$modx->parseChunk($tpl_enable , array('url' => $url.$pages['all'], 'class' => $classPage, 'title' => $pages['all'], 'rel' => "next"));
        }
        if($curent != $pages['all']) {
            $res .= $modx->parseChunk($tpl_enable , array('url' => $url.$pages['all'], 'class' => $classNext, 'title' => $next, 'rel' => "next"));
        }
    }
    return $res;
}

/**
 * Сохранение переводов в кеш
 */
function update_lang_file() {
    global $modx;
    $table_a_lang = $modx->getFullTableName('a_lang');
    $strings = $modx->db->query("SELECT * FROM {$table_a_lang} ORDER BY lang_id ASC");
    $f       = fopen(ROOT . "/dump.php", "w");
    fwrite($f, '<?php '."\n".'$l=[');
    while ($string = $modx->db->getRow($strings)) {
        $write = [];
        $list  = explode(",", $modx->config['lang_list']);
        foreach ($list as $value) {
            $write[] = "'".$value."'=>'".addslashes($string[$value])."'";
        }
        fwrite  ($f, $string['lang_id']."=>[".implode(",", $write)."],");
    }
    fwrite($f, "];");
    fclose($f);
}

/**
 * Получение переводов от Google
 *
 * @param $text
 * @param string $source
 * @param string $target
 * @return string
 */
function googleTranslate($text, $source = 'ru', $target = 'uk')
{
    if ($source == 'ua') {$source = 'ru';}
    if ($target == 'ua') {$target = 'ru';}

    if ($source == $target) {
        return $text;
    }

    $out = '';

    // Google translate URL
    $url = 'https://translate.google.com/translate_a/single?client=at&dt=t&dt=ld&dt=qca&dt=rm&dt=bd&dj=1&hl=uk-RU&ie=UTF-8&oe=UTF-8&inputm=2&otf=2&iid=1dd3b944-fa62-4b55-b330-74909a99969e';
    $fields_string = 'sl=' . urlencode($source) . '&tl=' . urlencode($target) . '&q=' . urlencode($text) ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 3);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, 'AndroidTranslate/5.3.0.RC02.130475354-53000263 5.1 phone TRANSLATE_OPM5_TEST_1');

    $result = curl_exec($ch);
    $result = json_decode($result, TRUE);

    if (isset($result['sentences'])) {
        foreach ($result['sentences'] as $s) {
            $out .= isset($s['trans']) ? $s['trans'] : '';
        }
    } else {
        $out = 'No result';
    }

    if(preg_match('%^\p{Lu}%u', $text) && !preg_match('%^\p{Lu}%u', $out)) { // Если оригинал с заглавной буквы то делаем и певерод с заглавной
        $out = mb_strtoupper(mb_substr($out, 0, 1)) . mb_substr($out, 1);
    }

    return $out;
}
die;