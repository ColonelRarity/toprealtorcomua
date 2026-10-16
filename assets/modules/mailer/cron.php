<?php
/*
	Модуль рассылки
*/
	// error_reporting(E_ALL);
	define ('ROOT', dirname(__FILE__));
	error_reporting(E_ALL);
	require_once(ROOT.'/../../../manager/includes/protect.inc.php');
	include_once ROOT.'/../../../manager/includes/config.inc.php';
	define('MODX_API_MODE', true);
	include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
	include_once(MODX_MANAGER_PATH.'/includes/controls/phpmailer/class.phpmailer.php');

	$modx = new DocumentParser();
	$modx->db->connect();
	$modx->getSettings();
	global $modx;
	startCMSSession();

	$lim   = $modx->config['mailer_smtp_limit'] == "" ? 10 : $modx->config['mailer_smtp_limit'];
	$query = "select * 
			  from `modx_a_mailer` m 
			  join `modx_a_mailer_templates` t on t.template_id = m.tpl_id 
			  join `modx_a_mailer_users` 	 u on u.user_id = m.user_id 
			  where m.mailed = 0
			  limit $lim ";
			  // echo $query;
	$list  = $modx->db->query($query);
	$host  = parse_url($modx->config['site_url']);
	$host  = $host['scheme']."://".$host['host']."/";
	$host = 'https://toprealtor.com.ua/';

	while ($l = $modx->db->getRow($list)) {
		$letter = str_replace("{username}", $l['user_name'], $l['template_post']);
		$theme  = str_replace("{username}", $l['user_name'], $l['template_subject']);
		$letter = str_replace("{unsubscribe_link}", $host."?unsubscribe=".$l['user_email'], $letter);
		// $modx->mailM($l['user_email'], $theme, $letter);
		// $modx->loadExtension('MODxMailer');
  //   $modx->mail->Subject = $theme;
  //   $modx->mail->AddAddress($l['user_email']);
  //   $modx->mail->MsgHTML($letter);
  //   $modx->mail->SMTPDebug = 2;
  //   $modx->mail->Send();
		$mail =  new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = true;
        if ($modx->config['mailer_smtp_ssl'] == 1) $mail->SMTPSecure = "ssl";
        if ($modx->config['mailer_smtp_tls'] == 1) $mail->SMTPSecure = "tls";
        $mail->SMTPSecure = "ssl";
        $mail->Host       = $modx->config['mailer_smtp_server'];
        $mail->Port       = $modx->config['mailer_smtp_port'];
        $mail->Username   = $modx->config['mailer_smtp_login'];
        $mail->Password   = $modx->config['mailer_smtp_pass'];
        $mail->Subject    = $theme;
        $mail->SetFrom($mail->Username, $modx->config['mailer_smtp_name']);
        $mail->AddAddress($l['user_email']);
        $mail->MsgHTML($letter);
        $mail->Send();
		$modx->db->query("update `modx_a_mailer` set mailed = 1 where user_id = '".$l['user_id']."' and tpl_id = '".$l['template_id']."'");
	}
	echo $modx->db->getRecordCount($list);

	// generate cache images
	$cache = $modx->db->makeArray($modx->db->query("SELECT * FROM `modx_a_flat` WHERE cache = 0 LIMIT 1"));
	if(is_array($cache) && count($cache) > 0) {
		$cache = $cache[0];
		$sql = 'select * from `modx_a_flat2image` where flat_id = "'.$cache['id'].'" ORDER BY sort ASC';
	   	$images = $modx->db->makeArray($modx->db->query($sql));
	   	if(is_array($images) && count($images) > 0) {
	   		foreach ($images as $image) {
	   			$modx->runSnippet("R", array("img" => MODX_BASE_PATH . "assets/images/flat/".$image['flat_id']."/".$image['image'], "opt" => "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|50|50", "folder" => "object/".$image['flat_id']));
		   		$modx->runSnippet("R", array("img" => MODX_BASE_PATH . "assets/images/flat/".$image['flat_id']."/".$image['image'], "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/".$image['flat_id']));
	   		}
		}
		$modx->db->query("UPDATE `modx_a_flat` SET `cache` = '1' WHERE `id` = '{$cache['id']}'");
		echo "<hr>End generate cache for object #".$image['flat_id'];
	} else {
		echo "<hr>Replay cache all object";
		$modx->db->query("UPDATE `modx_a_flat` SET cache = 0");
	}

	die(".");