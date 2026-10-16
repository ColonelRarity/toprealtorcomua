<?php
/*
 * Модуль расширенных настроек для сайта
 */
global $_lang;
define("ROOT", dirname(__FILE__));
error_reporting(E_ERROR);
$get            = isset($_GET['get']) ? $_GET['get'] : "settings";
$action         = isset($_GET['action']) ? $_GET['action'] : "";
$res            = Array();
$res['version'] = "v 1.0";
$res['url']     = $table['url'] = $url = "index.php?a=112&id=".$_GET['id']."";
$res['get']     = isset($_GET['get']) ? $_GET['get'] : "";
$messages       = [
    "settings_updated" => $_lang['advanced_success_upd'],
	"settings_created" => $_lang['advanced_success_add'],
	"settings_deleted" => $_lang['advanced_success_del']
];
switch ($get) {
	case "settings":
		include (ROOT . "/descriptions.php");
		switch ($action) {
			case 'add':
				if (count($_POST['s_add']) > 0) {
					$description['as_'.$_POST['s_add']['code']] = htmlspecialchars($_POST['s_add']['name']);
					$file = fopen(ROOT . "/descriptions.php", "w");
					fwrite($file, '<?php '."\n".'$description = Array(');
					foreach ($description as $key => $value) {
						fwrite($file, "\n\t"."'".$key."' => '".$value."',");
					}
					fwrite($file, "\n".");");
					fclose($file);

					$modx->db->query("
						INSERT INTO `modx_system_settings` SET 
							setting_name  = '".$modx->db->escape('as_'.$_POST['s_add']['code'])."',
							setting_value = '".$modx->db->escape($_POST['s_add']['value'])."'
						ON DUPLICATE KEY UPDATE 
							setting_value = '".$modx->db->escape($_POST['s_add']['value'])."'
					");

					include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
					$sync = new synccache();
					$sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
					$sync->setReport(false);
					$sync->emptyCache(); // first empty the cache
					$modx->getSettings();
					header("Location: ".$url."&get=settings&w=settings_created&stat=success");
					die;
				}
				break;
			case 'update':
				if (count($_POST) > 0) {
					foreach ($_POST as $k => $v) {
						$modx->db->query("UPDATE `modx_system_settings` SET setting_value = '".$modx->db->escape($v)."' WHERE setting_name = '$k'");
					}
					$res['alert'] = $_lang['seo_success_settings'];
					include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
					$sync = new synccache();
					$sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
					$sync->setReport(false);
					$sync->emptyCache(); // first empty the cache
					$modx->getSettings();
					header("Location: ".$url."&get=settings&w=settings_updated&stat=success");
					die;
				}
				break;
			case 'delete':
				if (isset($_GET['sid']) && $_GET['sid'] != '') {
					unlink($description[$_GET['sid']]);
					$file = fopen(ROOT . "/descriptions.php", "w");
					fwrite($file, '<?php '."\n".'$description = Array(');
					foreach ($description as $key => $value) {
						fwrite($file, "\n\t"."'".$key."' => '".$value."',");
					}
					fwrite($file, "\n".");");
					fclose($file);

					$modx->db->query("DELETE FROM `modx_system_settings` WHERE setting_name = '".$modx->db->escape($_GET['sid'])."'");

					include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
					$sync = new synccache();
					$sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
					$sync->setReport(false);
					$sync->emptyCache(); // first empty the cache
					$modx->getSettings();
					header("Location: ".$url."&get=settings&w=settings_deleted&stat=success");
					die;
				}
				break;
			default:
				$settings = $modx->db->makeArray($modx->db->query("SELECT * FROM `modx_system_settings` WHERE setting_name LIKE 'as_%'"));
				break;
		}
		$tpl = "/tpl/settings.tpl";
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