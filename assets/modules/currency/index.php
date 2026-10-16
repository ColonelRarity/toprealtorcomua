<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);

    if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') {
        die("No access");
    }

    define("ROOT", dirname(__FILE__));
    define("TPLS", dirname(__FILE__) . "/tpl/interface/");
    $languages      = explode(",", $modx->config['lang_list']);
    $bootstrap      = isset($_GET['b']) ? $_GET['b'] : "currency";
    $controller     = isset($_GET['c']) ? $_GET['c'] : "";
    $res            = [];
    $res['version'] = "v 1.0";
    $res['url']     = $url = "index.php?a=112&id=8";
    $search         = true;

    include MODX_BASE_PATH . "assets/modules/recall/shop.class.php";
    $shop = new Shop($modx);
    switch ($bootstrap) {

        case "currency":

            if (count($_POST) > 0) {
                foreach ($_POST['name'] as $key => $value)
                    if ($value == "") {
                        unset($_POST['name'][$key]);
                        unset($_POST['sign'][$key]);
                        unset($_POST['rate'][$key]);
                        unset($_POST['active'][$key]);
                    }

                $modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('" . implode("|", $_POST['sign']) . "', 'shop_curr_sign')");
                $modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('" . implode("|", $_POST['rate']) . "', 'shop_curr_rate')");
                $modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('" . $_POST['sign'][array_search(1, $_POST['active'])] . "', 'shop_curr_default_sign')");
                $modx->db->query("replace into `modx_system_settings` (setting_value, setting_name) values  ('" . $_POST['code'][array_search(1, $_POST['active'])] . "', 'shop_curr_default_code')");
                $modx->clearCache('full', true);
                die(header("Location: index.php?a=112&id=8&&w=currency_updated"));
            }
            $names = explode("|", $modx->config['shop_curr_name']);
            $codes = explode("|", $modx->config['shop_curr_code']);
            $rates = explode("|", $modx->config['shop_curr_rate']);
            $signs = explode("|", $modx->config['shop_curr_sign']);
            $activ = explode("|", $modx->config['shop_curr_active']);
            $tpl   = "index.tpl";


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