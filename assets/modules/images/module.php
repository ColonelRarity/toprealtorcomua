<?php

	global $_lang;
	// UPLOAD = ;
	define("ROOT", dirname(__FILE__));
	define("UPLOAD", MODX_BASE_PATH . "assets/images/collections/");
	$get            = isset($_GET['get']) ? $_GET['get'] : "settings";
	$res            = Array();
	$res['url']     = $table['url'] = $url = "index.php?a=112&id=".$_GET['id']."&category=".$_GET['category'];
	$res['get']     = $_GET['get'];
	error_reporting(0);
	switch ($get) {
		case "upload":
			if (!file_exists(UPLOAD . $_GET['category'])) 
				mkdir(UPLOAD . $_GET['category']);
			if (count($_FILES['upload']['tmp_name']) > 0)
				foreach ($_FILES['upload']['tmp_name'] as $key => $file) {
					$type = explode("/", $_FILES['upload']['type'][$key]);
					if ($type[0] == "image"){
						move_uploaded_file($file, UPLOAD.$_GET['category']."/".md5($file).".".$type[1]);
						$modx->db->query("INSERT INTO `modx_a_images` SET 
							image_parent   = '".(int)$_GET['category']."',
							image_file     = '".$modx->db->escape(md5($file).".".$type[1])."',
							image_position = 9999
							");
					}
				}
			die;
			break;
		case "delete":
			unlink(UPLOAD.$_GET['category'].'/'.$_GET['file']);
			$modx->db->query("DELETE FROM `modx_a_images` WHERE image_file = '".$modx->db->escape($_GET['file'])."'");
			die;
			break;
		case "imageslist":
			$images = $modx->db->query("SELECT * FROM `modx_a_images` WHERE image_parent = '".$modx->db->escape($_GET['category'])."' ORDER BY image_position ASC");
			while ($img = $modx->db->getRow($images)) {
				$image = str_replace(MODX_BASE_PATH, '',UPLOAD.$_GET['category']."/".$img['image_file']);
				include ROOT . "/tpl/image.tpl";
			}
			die;
			break;
		case "visible":
			if($_GET['status'] != '' && $_GET['i'] != '') {
				$modx->db->query("UPDATE `modx_a_images` SET image_visible = ".($_GET['status'] == 0 ? 1 : 0)." WHERE image_id = ".$modx->db->escape($_GET['i']));
				$ajax['title']        = ($_GET['status'] == 0 ? $_lang['image_show'] : $_lang['image_hide']);
				$ajax['class']        = ($_GET['status'] == 0 ? "btn-success" : "btn-danger");
				$ajax['eye']          = ($_GET['status'] == 0 ? "glyphicon-eye-open" : "glyphicon-eye-close");
				$ajax['status']       = "true";
				$ajax['status_image'] = ($_GET['status'] == 0 ? 1 : 0);
				die(json_encode($ajax));
			}
			break;
		case "getImage":
			if($_GET['i'] != '') {
				$image = $modx->db->getRow($modx->db->query("SELECT * FROM `modx_a_images` WHERE image_id = '".$modx->db->escape($_GET['i'])."'"));

				if($modx->config['lang_list'] != '')
					$langs = explode(",", $modx->config['lang_list']);

				if(is_array($langs)) {
					ob_start();
					foreach ($langs as $lang) {
						include ROOT . "/tpl/edit_image_nav.tpl";
					}
					$result['images_nav'] = ob_get_contents();
					ob_end_clean();
					ob_start();
					foreach ($langs as $lang) {
						include ROOT . "/tpl/edit_image_content.tpl";
					}
					$result['images_content'] = ob_get_contents();
					ob_end_clean();
					die(json_encode($result));
				}
				
			}
 			break;
		case "setImage":

			if($modx->config['lang_list'] != '')
				$langs = explode(",", $modx->config['lang_list']);	
			if(is_array($langs)) {
				foreach ($langs as $lang) {
					$arr[] = "image_title_".$lang." = '".$modx->db->escape($_GET['image'][$lang]['title'])."'";
					$arr[] = "image_alt_".$lang." = '".$modx->db->escape($_GET['image'][$lang]['alt'])."'";
				}
			}
			if(count($arr) > 0) {
				$res = implode(",", $arr);
				$modx->db->query("UPDATE `modx_a_images` SET ".$res." WHERE image_id = ".$modx->db->escape($_GET['i']));
				die(json_encode(array("status" => "true")));
			}
			break;
		default:
			if (count($_POST) > 0) {
				foreach ($_POST['image']['href'] as $key => $value) {
					$modx->db->query("UPDATE `modx_a_images` SET 
						image_position = '".(int)$key."'
						WHERE 
						image_file     = '".$modx->db->escape($value)."'
						");
				}
			}
			$images = $modx->db->query("SELECT * FROM `modx_a_images` WHERE image_parent = ".(int)$_GET['category']." ORDER BY image_position ASC");
			
			ob_start();
			while ($img = $modx->db->getRow($images)) {
				$image = str_replace(MODX_BASE_PATH, '',UPLOAD.$_GET['category']."/".$img['image_file']);

				include ROOT . "/tpl/image.tpl";
			}
			$res['images'] = ob_get_contents();
			ob_end_clean();
			$doc = $modx->getDocument($_GET['category']);
			break;
	}
	if (isset($tpl)) {
		ob_start();
		include ROOT . $tpl;
		$res['content'] = ob_get_contents();
		ob_end_clean();
	}

	include ROOT . "/tpl/index.tpl";
	die;

