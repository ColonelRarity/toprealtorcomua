<?php
/*
	Крон для xml
*/
     ini_set('memory_limit', '512M');
     set_time_limit(0);

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



	 $sql = "SELECT *, 
               (SELECT watermark FROM modx_a_flat  af WHERE af.id = afi.flat_id ) as watermark
      FROM  `modx_a_flat2imagerawtmp` afi WHERE status = 0 limit 50";
     $images = $modx->db->makeArray($modx->db->query($sql));
      foreach ($images as $image) {

      		$id = $image['flat_id'];
      		$name = $image['image'];
      		$type = $image['type'];

          if($image['watermark'] == 0 ){
            $opt = "w=1920,h=1080";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);

            $opt = "w=900,h=500,zc=1";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);

            $opt = "`w=136,h=77,zc=1";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);

          } elseif($image['watermark'] == 1 ){
            $opt = "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|51|51";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);

            $opt = "w=900,h=500,zc=1,fltr_wmi|/images/watermark.png|C|100|51|51";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);

            $opt = "``w=136,h=77,zc=1,fltr_wmi|/images/watermark-small.png|C|100|51|51";
            $modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "expectFormat" => 'webp', "folder" => "object/" . $id]);
          }



            $modx->db->query("UPDATE `modx_a_flat2imagerawtmp` SET `status` = '1' WHERE `id` = '".$image['id']."';");

      }


    die;
