<?php

	define("ROOT", dirname(__FILE__));
	$get            = isset($_GET['get']) ? $_GET['get'] : "";
	$res            = Array();
	$res['version'] = "v 1.0";
	$res['url']     = $table['url'] = $url = "index.php?a=112&id=".$_GET['id']."";
	$res['get']     = $_GET['get'];
	error_reporting(0);
	include MODX_BASE_PATH . "manager/includes/lang/".$modx->config['manager_language'].".inc.php";
	switch ($get) {		
		case "templates":
			switch ($_GET['do']) {
				case "add":
					$tpl = "/tpl/templates_add.tpl";
				break;
				case "edit":
					$template = $modx->db->getRow($modx->db->query("select * from `modx_a_mailer_templates` where template_id = ".$_GET['t']));
					$tpl      = "/tpl/template_edit.tpl";
				break;
				default:
					if (isset($_POST['post']) && !isset($_POST['tpl'])) {
					 	$modx->db->query("insert into `modx_a_mailer_templates` set 
											template_subject = '".$modx->db->escape($_POST['theme'])."', 
											template_post    = '".$modx->db->escape($_POST['post'])."'");
					 	$id = $modx->db->getInsertId();
					 	$modx->db->query("insert into `modx_a_mailer` (tpl_id, user_id) select '".$id."' as 'tpl_id', u.user_id from `modx_a_mailer_users` u on duplicate key update user_id = u.user_id");
					 	$res['alert'] = $_lang["mailer_added_tpl"];
					} elseif (isset($_POST['post']) && isset($_POST['tpl'])) {
					 	$modx->db->query("update `modx_a_mailer_templates` set 
											template_subject  = '".$modx->db->escape($_POST['theme'])."', 
											template_post     = '".$modx->db->escape($_POST['post'])."'
											where template_id = '".$modx->db->escape($_POST['tpl'])."'");
					 	$res['alert'] = $_lang["mailer_update_tpl"];
					}
					if (isset($_GET['delete'])) {
						$modx->db->query("delete from `modx_a_mailer` where tpl_id = '".$_GET['delete']."'");
						$modx->db->query("delete from `modx_a_mailer_templates` where template_id = '".$_GET['delete']."'");
					 	$res['alert'] = $_lang["mailer_del_tpl"];
					}
					$templates = $modx->db->query("select * from `modx_a_mailer_templates` order by template_id desc");
					$tpl       = "/tpl/templates.tpl";
				break;
			}
		break;
		case "config":
			if (count($_POST) > 0) {
				foreach ($_POST as $key => $value) 
					$modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values('$value','$key')");
				// empty cache
				include_once MODX_BASE_PATH . "manager/processors/cache_sync.class.processor.php";
				$sync = new synccache();
				$sync->setCachepath(MODX_BASE_PATH . "assets/cache/");
				$sync->setReport(false);
				$sync->emptyCache(); // first empty the cache
				die(header("Location: ".$url."&get=config&i=yes"));
			}
			if (!empty($_GET['i'])) $res['alert'] = $_lang["mailer_update_conf"];
			$tpl          = "/tpl/config.tpl";
		break;
		case "userlist":
			if (isset($_GET['i']))
				$modx->db->query("insert into `modx_a_mailer_users` (user_email, user_name) select email as 'user_email', fullname as 'user_name' from `modx_web_user_attributes` on duplicate key update user_name = user_name");
			if (isset($_GET['d'])) {
				$modx->db->query("delete from `modx_a_mailer_users` where user_id =".$_GET['d']);
				$res['alert'] = $_lang["mailer_del_user_i"];
			}
			$users = $modx->db->query("select * from `modx_a_mailer_users` order by user_id desc limit 0, 100");
			$tpl   = "/tpl/userlist.tpl";
		break;
		default:
			$stats = $modx->db->query("
						select 
							t.*,
							(select count(*) from `modx_a_mailer` where tpl_id = t.template_id) as 'all',
							(select count(*) from `modx_a_mailer` where tpl_id = t.template_id and mailed = 1) as 'mailed'
						from `modx_a_mailer_templates` t
						order by t.template_date desc
						");
			$tpl   = "/tpl/stat.tpl";
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

