<?php
/*
	Языковой модуль
*/
	global $_lang;
	define("ROOT", dirname(__FILE__));
	$get            = isset($_GET['get']) ? $_GET['get'] : "settings";
	$res            = Array();
	$res['url']     = $table['url'] = $url = "index.php?a=112&id=".$_GET['id']."";
	$res['get']     = $_GET['get'];
	error_reporting(0);
	switch ($get) {
		default:
			function update_lang_file () {
			  global $modx;
			  $strings = $modx->db->query("select * from `modx_a_lang` order by lang_id asc");
	      $mem = new Memcache;
	      $mem->connect('127.0.0.1', 11211);
	      $mem->flush();
	      while ($t = $modx->db->getRow($strings)) {
	      	$r = $t;
	      	unset($r['lang_id']);
	      	$mem->set($modx->config['site_name'].'_'.$t['lang_id'], $r);
	      }
	      $mem->close();
			}		
			$action = isset($_GET['action']) ? $_GET['action'] : "";
			switch ($action) {
			    case "search":
			    	$config = explode(",", $modx->config['lang_list']);
			    	foreach ($config as $key)
			    	         $where[] = $key . " LIKE '%".$modx->db->escape($_POST['search'])."%'";
			    	$items = $modx->db->query("
			    		SELECT
                *
              FROM `modx_a_lang`
              WHERE lang_id LIKE '".$modx->db->escape($_POST['search'])."%' 
            		OR ".implode(" OR ", $where)."
              ORDER BY lang_id ASC");
			    	while ($su = $modx->db->getRow($items)) {
	    	      $tmp = '<tr><td><b>'.$su['lang_id'].'</b></td>';
	    	      foreach ($config as $value)
	    	        $tmp .= '<td><input type="text" name="lang['.$su['lang_id'].']['.$value.']" class="form-control" value="'.$su[$value].'" /></td>';
							$tmp .= '</tr>';
							$result .= $tmp;
			    	}
			    	die( $result );
			    break;
			    case "update":
			    	var_dump();
			    	$lang = reset(array_keys($_POST['lang']));
			    	$id   = reset(array_keys($_POST['lang'][$lang]));
			    	$val  = $modx->db->escape($_POST['lang'][$lang][$id]);
			    	$modx->db->query( "update `modx_a_lang` set $id = '$val' where lang_id = $lang" );
			    	// $modx->db->query("update `modx_a_lang` set where ")
			    	die;
			    	break;
			    case "save":
			    	$config = explode(",", $modx->config['lang_list']);
			    	if (is_array($_POST['lang'])) 
	            foreach ($_POST['lang'] as $key => $val) {
	              $update = Array();
	              foreach ($config as $value)
                  $update[] = $value. " = '".$modx->db->escape($val[$value])."'";
	              $modx->db->query( "update `modx_a_lang` set ".implode(",", $update)." where lang_id = '".$key."'");
	            }
		        update_lang_file();
		        die(header("Location: ".$url."&get=translate"));
			      break;
			    default:
			    	$config = explode(",", $modx->config['lang_list']);
			    	if ($action == "add" && $_SESSION['hash']['tongue']['add'] != md5(serialize($_POST))) {
			    		$_SESSION['hash']['tongue']['add'] = md5(serialize($_POST));
						$data   = Array();
			        foreach ($config as $v)
			          $data[] = "'".$modx->db->escape($_POST['add'][$v])."'";
			        $modx->db->query("INSERT INTO `modx_a_lang`(`lang_id`, ".$modx->config['lang_list'].") VALUES (null, ".implode(",", $data).")");
			        update_lang_file();
			    	}
			    	// add + thead
			    	foreach ($config as $lng){
			    		$table['thead'] .= '<th>'.strtoupper($lng).'</th>';
			    		$table['add']   .= '<tr> <td>'.strtoupper($lng).'</td> <td> <input type="text" class="form-control" name="add['.$lng.']" value="" /> </td></tr>'; 
			    	}
			    	// tbody
					$items          = $modx->db->query("SELECT * FROM `modx_a_lang` ORDER BY lang_id DESC LIMIT 0, 20"); 
					$table['tbody'] = "";
					while ($su = $modx->db->getRow($items)) {
			      $tmp = '<tr><td><b>'.$su['lang_id'].'</b></td>';
			      foreach ($config as $key)
			        $tmp .= '<td><input type="text" class="form-control" name="lang['.$su['lang_id'].']['.$key.']" value="'.$su[$key].'" /></td>';
			      $tmp .= '</tr>';
			      $table['tbody'] .= $tmp;
					}
					$table['col'] = count($config) + 1;
					$subtpl = 'tongue_tpl_translator';
			    break;
			  }
			  $tpl = "/tpl/module_trans.tpl";
		break;
		case "settings":
			if (count($_POST) > 0) {
				if ($_POST['lang_enable'] == 0) 
					$copy = ROOT . "/htaccess_single";
				else 
					$copy = ROOT . "/htaccess_multi";
				foreach ($_POST as $key => $value) $modx->db->query("update `modx_system_settings` set setting_value = '$value' where setting_name = '$key'");
				unlink($_SERVER['DOCUMENT_ROOT'] ."/.htaccess");
				copy($copy, $_SERVER['DOCUMENT_ROOT'] ."/.htaccess");

				if ($_POST['lang_list'] != $modx->config['lang_list']) {
					$columns = Array();
					$needed  = Array();
					$fields  = $modx->db->query("describe `modx_site_content`");
					while ($f = $modx->db->getRow($fields))
					    $columns[] = $f['Field'];

					$langs  = explode(",", $_POST['lang_list']);

					foreach ($langs as $lng)
					    if (!in_array("pagetitle_".$lng, $columns))
					        $needed[] = $lng;   
					foreach ($needed as $lng) 
						if (!empty($lng))
					    $modx->db->query("ALTER TABLE `modx_site_content`
					    ADD `pagetitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'pagetitle ".$lng." version',
					    ADD `longtitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'longtitle ".$lng." version',
					    ADD `description_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'description ".$lng." version',
					    ADD `link_attributes_".$lng."`  varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'link_attributes ".$lng." version',
					    ADD `introtext_".$lng."` 		text COLLATE 'utf8_general_ci' NOT NULL COMMENT 'introtext ".$lng." version',
					    ADD `menutitle_".$lng."` 		varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT 'menutitle ".$lng." version',
					    ADD `content_".$lng."` 			mediumtext COLLATE 'utf8_general_ci' NOT NULL COMMENT 'content ".$lng." version';");	
					
					// dump 
					$columns = Array();
					$needed  = Array();
					$fields  = $modx->db->query("describe `modx_a_lang`");
					while ($f = $modx->db->getRow($fields)) $columns[] = $f['Field'];

					$langs  = explode(",", $_POST['lang_list']);

					foreach ($langs as $lng)
					    if (!in_array($lng, $columns))
					        $needed[] = $lng;   
					foreach ($needed as $lng) 
						if (!empty($lng))
					    	$modx->db->query("ALTER TABLE `modx_a_lang` ADD `".$lng."` text NOT NULL COMMENT '".$lng."'	");
					
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
	die;

