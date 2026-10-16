<?php
/*
	Крон для xml
*/
	error_reporting(E_ALL);
	define ("ROOT", dirname(__FILE__));
	error_reporting(E_ALL);
	require_once(ROOT.'/../../../manager/includes/protect.inc.php');
	include_once ROOT.'/../../../manager/includes/config.inc.php';
	define('MODX_API_MODE', true);
	include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
	include MODX_BASE_PATH . "/assets/logic/class.php";	
	$modx = new DocumentParser();
	$modx->db->connect();
	$modx->getSettings();
	global $modx;
	$class = new Corp($modx);
	startCMSSession();


    $filepath = 'assets/export/cron/';
    $filename     = 'toprealtor.xml';
    $filename_lun = 'lun.xml';

    $file     	    = MODX_BASE_PATH.$filepath.$filename;
    $file_lun    	= MODX_BASE_PATH.$filepath.$filename_lun;

    $lun = $class->yandexXmlSave('lun');
    $xml = $class->yandexXmlSave('all');

    file_put_contents($file_lun, $lun);
    file_put_contents($file, $xml);

    die;
