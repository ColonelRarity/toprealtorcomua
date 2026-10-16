<?php
/**
 *	Языковой модуль
 */
global $_lang;
define("ROOT", dirname(__FILE__));
$get        = isset($_GET['get']) ? $_GET['get'] : "translates";
$res        = Array();
$res['url'] = $table['url'] = $url = "index.php?a=112&id=".$_GET['id']."";
$res['get'] = $_GET['get'];
error_reporting(0);
switch ($get) {
	default:
		$action = isset($_GET['action']) ? $_GET['action'] : "";
		switch ($action) {
		    case "search":
		    	$config = explode(",", $modx->config['lang_list']);
		    	foreach ($config as $key) {
		    	    $where[] = $key . " LIKE '%".$modx->db->escape($_POST['search'])."%'";
		    	}
		    	$items = $modx->db->query("
		    		SELECT *
           			FROM `modx_a_lang`
           			WHERE lang_id LIKE '".$modx->db->escape($_POST['search'])."%' 
           				OR ".implode(" OR ", $where)."
           			ORDER BY lang_id ASC
           		");
		    	while ($su = $modx->db->getRow($items)) {
    	    		$tmp = '<tr><td><b>'.$su['lang_id'].'</b></td>';
    	    		foreach ($config as $value) {
    	        		$tmp .= '<td><input type="text" name="lang['.$su['lang_id'].']['.$value.']" class="form-control" value="'.$su[$value].'" /></td>';
    	    		}
					$tmp .= '</tr>';
					$result .= $tmp;
		    	}
		    	die($result);
			    //break;
		    case "update":
		    	//var_dump();
		    	$lang = reset(array_keys($_POST['lang']));
		    	$id   = reset(array_keys($_POST['lang'][$lang]));
		    	$val  = $modx->db->escape(htmlspecialchars($_POST['lang'][$lang][$id], ENT_QUOTES));
		    	$modx->db->query("UPDATE `modx_a_lang` SET $id = '$val' WHERE lang_id = $lang" );
		    	update_lang_file();
		    	die;
		    	//break;
		    case "save":
		    	$config = explode(",", $modx->config['lang_list']);
		    	if (is_array($_POST['lang'])) {
            		foreach ($_POST['lang'] as $key => $val) {
            			$update = Array();
            			foreach ($config as $value) {
               				$update[] = $value. " = '".$modx->db->escape(htmlspecialchars($val[$value], ENT_QUOTES))."'";
            			}
            			$modx->db->query("UPDATE `modx_a_lang` SET ".implode(",", $update)." WHERE lang_id = '".$key."'");
            		}
            	}
	        	update_lang_file();
	        	die(header("Location: ".$url."&get=translate"));
		    	//break;
		    default:
		    	$config = explode(",", $modx->config['lang_list']);
		    	if ($action == "add" && $_SESSION['hash']['tongue']['add'] != md5(serialize($_POST))) {
		    		$_SESSION['hash']['tongue']['add'] = md5(serialize($_POST));
					$data   = Array();
			        foreach ($config as $v) {
			        	$data[] = "'".$modx->db->escape(htmlspecialchars($_POST['add'][$v], ENT_QUOTES))."'";
			        }
			        $modx->db->query("INSERT INTO `modx_a_lang`(`lang_id`, ".$modx->config['lang_list'].") VALUES (null, ".implode(",", $data).")");
			        update_lang_file();
		    	}
		    	// add + thead
		    	foreach ($config as $lng){
		    		$table['thead'] .= '<th>'.strtoupper($lng).'</th>';
		    		$table['add']   .= '<tr> <td>'.strtoupper($lng).'</td> <td> <input type="text" class="form-control" name="add['.$lng.']" value="" /> </td></tr>'; 
		    	}
		    	// tbody
		    	$limit = 30;
    			$page  = isset($_REQUEST['p']) ? ((int)($_REQUEST['p'])-1) * $limit : 0;
				$items = $modx->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM `modx_a_lang` ORDER BY lang_id DESC LIMIT ".$page.", ".$limit);
				$pages = $modx->db->getRow($modx->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
    			$modx->setPlaceholder("max_items", $pages['cnt']);
    			$modx->setPlaceholder("limit_items", $limit);
				$table['tbody'] = "";
				while ($su = $modx->db->getRow($items)) {
		    		$tmp = '<tr><td><b>'.$su['lang_id'].'</b></td>';
		    		foreach ($config as $key) {
		        		$tmp .= '<td><input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$key.']" value="'.$su[$key].'" /></td>';
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
		$tpl = "/tpl/module_trans.tpl";
		break;
	case "settings":
		if (count($_POST) > 0) {
			if ($_POST['lang_enable'] == 0) {
				$copy = ROOT . "/htaccess_single";
			} else {
				$copy = ROOT . "/htaccess_multi";
			}

			foreach ($_POST as $key => $value) {
				$modx->db->query("update `modx_system_settings` set setting_value = '$value' where setting_name = '$key'");
			}
			unlink($_SERVER['DOCUMENT_ROOT'] ."/.htaccess");
			copy($copy, $_SERVER['DOCUMENT_ROOT'] ."/.htaccess");
			if ($_POST['lang_list'] != $modx->config['lang_list']) {
				$columns = Array();
				$needed  = Array();
				$fields  = $modx->db->query("describe `modx_site_content`");
				while ($f = $modx->db->getRow($fields))
				    $columns[] = $f['Field'];
					$langs  = explode(",", $_POST['lang_list']);

					foreach ($langs as $lng) {
					    if (!in_array("pagetitle_".$lng, $columns)) {
					        $needed[] = $lng;   
					    }
					}
					foreach ($needed as $lng) {
						if (!empty($lng)) {
					    	$modx->db->query("ALTER TABLE `modx_site_content`
					    		ADD `pagetitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'pagetitle ".$lng." version',
					    		ADD `longtitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'longtitle ".$lng." version',
					    		ADD `description_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'description ".$lng." version',
					    		ADD `link_attributes_".$lng."`  varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'link_attributes ".$lng." version',
					    		ADD `introtext_".$lng."` 		text COLLATE 'utf8_general_ci' NOT NULL COMMENT 'introtext ".$lng." version',
					    		ADD `menutitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'menutitle ".$lng." version',
					    		ADD `content_".$lng."` 			mediumtext COLLATE 'utf8_general_ci' NOT NULL COMMENT 'content ".$lng." version';"
					    	);
					    }	
					}
					// dump 
					$columns = Array();
					$needed  = Array();
					$fields  = $modx->db->query("describe `modx_a_lang`");
					while ($f = $modx->db->getRow($fields)) {
						$columns[] = $f['Field'];
					}

					$langs  = explode(",", $_POST['lang_list']);

					foreach ($langs as $lng) {
					    if (!in_array($lng, $columns)) {
					        $needed[] = $lng;   
					    }
					}
					foreach ($needed as $lng) {
						if (!empty($lng)) {
					    	$modx->db->query("ALTER TABLE `modx_a_lang` ADD `".$lng."` text NOT NULL COMMENT '".$lng."'	");
						}
					}
					
				}

				// empty cache
				include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
				$sync = new synccache();
				$sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
				$sync->setReport(false);
				$sync->emptyCache(); // first empty the cache
				die(header("Location: ".$url."&get=settings"));
			}
			$tpl = "/tpl/module_settings.tpl";
		break;
	case "about":
		break;
}
if (isset($tpl)) {
	ob_start();
	include ROOT . $tpl;
	$res['content'] = ob_get_contents();
	ob_end_clean();
}
include ROOT . "/tpl/module_index.tpl";

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
	$strings = $modx->db->query("select * from `modx_a_lang` order by lang_id asc");
	$f       = fopen(ROOT . "/dump.php", "w");
	fwrite($f, '<?php '."\n".'$l=Array(');
	while ($string = $modx->db->getRow($strings)) {
	    $write = Array();
	    $list  = explode(",", $modx->config['lang_list']);
	    foreach ($list as $value) {
	        $write[] = "'".$value."'=>'".addslashes($string[$value])."'";
	    }
	    fwrite  ($f, $string['lang_id']."=>Array(".implode(",", $write)."),");
	}
	fwrite($f, ");");
	fclose($f);
}
die;