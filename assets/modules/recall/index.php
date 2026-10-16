<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");

define("ROOT", dirname(__FILE__));
define("TPLS", dirname(__FILE__)."/tpl/interface/");
$languages      = explode(",", $modx->config['lang_list']);
$bootstrap      = isset($_GET['b']) ? $_GET['b'] : "recalls";
$controller     = isset($_GET['c']) ? $_GET['c'] : "";
$res            = Array();
$res['version'] = "v 1.0";
$res['url']     = $url = "index.php?a=112&id=6&";
$search         = true;

include MODX_BASE_PATH . "assets/modules/recall/shop.class.php";
$shop = new Shop($modx);
switch ($bootstrap) {

  case 'delete_files':
    unlink(MODX_BASE_PATH . "assets/images/recall/".$_GET['folder'] ."/". $_GET['delete']);
    die();
    break;

  case "upload_recall":
    $folder = MODX_BASE_PATH . "assets/images/recall/".$_POST['folder']."/";
    if (!file_exists($folder)) {
      mkdir($folder, 0777);
      chmod($folder, 0777);
    }
    foreach ($_FILES['uploader']['tmp_name'] as $key => $value){
      $name = preg_replace('/\D+/', '', microtime()).".".end(explode(".", $_FILES['uploader']['name'][$key]));
      move_uploaded_file($value, $folder.$name);
    }
   die;
   break;

case 'get_images_recall':
    $folder = "/assets/images/recall/".$_GET['folder']."/";
    chdir(MODX_BASE_PATH . $folder);
    $files = glob('*');
    if (is_array($files))
        foreach($files as $image)
          require TPLS . "image_recall.tpl";
    die;
    break;

  case "recalls":
    switch ($_GET['c']) {
      case 'delete':
        $shop->deleteRecall($_GET['i']);
        die(header("Location: ".$url."b=recalls&w=recall_deleted"));
        break;
      case 'update':
        $shop->updateRecall($_POST['recall']);
        die(header("Location: ".$url."b=recalls&w=recall_updated"));
        break;
      case 'edit':
        $recall = $shop->getRecall($_GET['i']);    
        $dir = MODX_BASE_PATH."/assets/images/recall/".$_GET['i']."/";
        if (is_dir($dir)) {
           if ($dh = opendir($dir)) {
               while (($file = readdir($dh)) !== false) {
                  if($file != "." || $file != "..")
                    $filename[] = $file;
               }
               closedir($dh);
           }
        }  
        $tpl = "recall_edit.tpl";
        break;
      
      default:
        if($_REQUEST['date_to'] != ''){
          $_REQUEST['date_from'] = ($_REQUEST['date_from'] != '' ? $_REQUEST['date_from'] : '0000-00-00 00:00:00');
          $date     = ' AND (recall_pub_date between "'.$modx->db->escape($_REQUEST['date_from']).' 00:00:00'.'" and "'.$modx->db->escape($_REQUEST['date_to']).' 23:59:59'.'")  ';
          $date_url = '&date_from='.$_REQUEST['date_from'].'&date_to='.$_REQUEST['date_to'];
        }
        else
          $_REQUEST['date_to'] = date("Y-m-d");
        if($_REQUEST['status'] != '' AND $_REQUEST['status'] != '-1'){
          $status     = ' AND recall_moderated = '.$modx->db->escape($_REQUEST['status']);
          $status_url = '&status='.$_REQUEST['status'];
        }

        $recalls  = $shop->getRecalls($date, $status);

        $modx->setPlaceholder('url', $url."b=recalls".$date_url.$status_url."&p=");
        $pagin = $shop->getPaginate(); 
        $tpl   = "recalls.tpl";
        break;
    }    
    break;
}

if (isset($tpl)) {
  ob_start();
  include TPLS . $tpl;
  $res['content'] = ob_get_contents();
  ob_end_clean();
}
include TPLS . "index.tpl";
die;