<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);

if(!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE != 'true') die("No access");

define("ROOT", dirname(__FILE__));
define("TPLS", dirname(__FILE__)."/tpl/");

// lang
$languages      = explode(",", $modx->config['lang_list']);
$lang = (isset($_COOKIE['lang']) && preg_match('/^[a-z]{2}$/', $_COOKIE['lang']) && is_file(ROOT."/lang/".$_COOKIE['lang'].".php")) ? $_COOKIE['lang'] : 'en';
include ROOT."/lang/".$lang.".php";

include MODX_BASE_PATH . "assets/libs/PHPex/PHPExcel.php";
include MODX_BASE_PATH . "assets/libs/PHPex/PHPExcel/Writer/Excel5.php";
$bootstrap      = isset($_GET['b']) ? $_GET['b'] : "flat";
$controller     = isset($_GET['c']) ? $_GET['c'] : "";
$res            = Array();
$res['version'] = "v 2.1";
$res['url']     = $url = "index.php?a=112&id=7&";
$search         = true;

switch ($_GET['w']) {
    case 'blockFlat': $flash['success']  = "Объект скрыт"; break;
    case 'activeFlat': $flash['success'] = "Объект опубликован"; break;
    case 'updateFlat': $flash['success'] = 'Объект успешно обновлен'; break;
    case 'saveFlat': $flash['success']   = 'Объект успешно сохранен'; break;
    case 'deleteFlat': $flash['success'] = 'Объект удален'; break;
    case 'xml_active': $flash['success'] = 'Список выгрузки в XML успешно обновлен'; break;
}

include "plectan.php";

include MODX_BASE_PATH . "assets/logic/class.php";
$class = new Corp($modx);
switch ($bootstrap) {

    // старая версия кейса



     case 'flat':
        switch ($controller) {
            case "getPrint":
                if (!empty($_SESSION['sort']['status'])) {
                    switch ($_SESSION['sort']['status']) {
                        case '1':
                            $status = "Продажа";
                            break;
                        case '2':
                            $status = "Аренда";
                            break;
                        case '3':
                            $status = "Снят с продажи";
                            break;
                        case '4':
                            $status = "Продан";
                            break;

                        default:
                            $status = "-";
                            break;
                    }
                }

                if (!empty($_SESSION['sort']['sort_one'])) {
                    $sortValueOne = $class->getNameCategory($_SESSION['sort']['sort_one']);
                }

                if (!empty($_SESSION['sort']['sort_two'])) {
                    $sortValueTwo = $class->getNameCategory($_SESSION['sort']['sort_two']);
                }

                if (!empty($_SESSION['sort']['sort_three'])) {
                    $sortValueThree = $class->getNameCategory($_SESSION['sort']['sort_three']);
                }

                $where = array();
                //$param['limit'] = 20;
                $param['limit'] = 10000000;
                $param['offset'] = ($_GET['page'] > 0) ? ((int)$_GET['page']-1) * $param['limit'] : 0;
                if ($_GET['flat'] != '') {
                    $where[] = "name LIKE ('%" . $modx->db->escape($_GET['flat']) . "%') OR addr LIKE ('%" . $modx->db->escape($_GET['flat']) . "%')";
                }
                if ($_REQUEST['type'] != '') {
                    $where[] = "type = '" . $modx->db->escape($_GET['type']) . "'";
                }
                if ($_GET['code'] != '') {
                    $where[] = "code LIKE ('" . $modx->db->escape($_GET['code']) . "%')";
                }
                if ($_GET['owner'] != '') {
                    $where[] = "( owner_name LIKE ('%" . $modx->db->escape($_GET['owner']) . "%') OR owner_phone LIKE ('%" . $modx->db->escape($_GET['owner']) . "%') OR owner_comment LIKE ('%" . $modx->db->escape($_GET['owner']) . "%'))";
                }
                if ($_GET['status'] != '') {
                    $where[] = "status LIKE ('" . $modx->db->escape($_GET['status']) . "%')";
                }
                $flats = $class->getFlatsExel($param, $where);
                $count = $class->getFoundRows();
                //$pagin = $class->getPaginate($count, $url . "b=flat" . "&c=getPrint" . ($_GET['flat'] != '' ? "&flat=" . $_GET['flat'] : "") . ($_GET['type'] != '' ? "&type=" . $_GET['type'] : "") . ($_GET['code'] != '' ? "&code=" . $_GET['code'] : "") . ($_GET['owner'] != '' ? "&owner=" . $_GET['owner'] : "") . "&page=", 20);
                if ($_GET['excel'] != 1) {
                    $tpl = "/flat/res.tpl";
                } else {
                    $xls = new PHPExcel();
                    $styleArray = array(
                        'font' => array(
                            'bold' => true
                        )
                    );
                    $xls->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                    $xls->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);

                    $xls->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'Название')
                        ->setCellValue('C1', 'Адрес')
                        ->setCellValue('D1', 'Тип')
                        ->setCellValue('E1', 'Цена')
                        ->setCellValue('F1', 'Код')
                        ->setCellValue('G1', 'Имя владельца')
                        ->setCellValue('H1', 'Телефон владельца')
                        ->setCellValue('I1', 'Комментарий о владельце')
                        ->setCellValue('J1', 'Описание');
                    for ($i = 0; $i < count($flats); $i++) {
                        $row = $i + 2;
                        $xls->setActiveSheetIndex(0)
                            ->setCellValue('A' . $row, $flats[$i]['id'])
                            ->setCellValue('B' . $row, $flats[$i]['name'])
                            ->setCellValue('C' . $row, $flats[$i]['addr'])
                            ->setCellValue('D' . $row, $flats[$i]['cat_name'])
                            ->setCellValue('E' . $row, $flats[$i]['price'])
                            ->setCellValue('F' . $row, $flats[$i]['code'])
                            ->setCellValue('G' . $row, $flats[$i]['owner_name'])
                            ->setCellValue('H' . $row, $flats[$i]['owner_phone'])
                            ->setCellValue('I' . $row, $flats[$i]['owner_comment'])
                            ->setCellValue('J' . $row, $flats[$i]['description']);
                    }

                    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
                    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Pragma: no-cache");
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=file.xls");

                    $objWriter = new PHPExcel_Writer_Excel5($xls);
                    $objWriter->save('php://output');
                    die;
                }
                break;

            case "getCategory":
                $categories_two_dept = $class->getCategory_two_dept($_GET['cat']);
                $temp = json_encode($categories_two_dept);

                die($temp);
                break;
            case "setActualization": // актуализация объектов
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->setFlat(array('updated_at' => date('Y-m-d H:i:s')), $_GET['i']);
                        die(date('Y-m-d'));
                        //$class->setFlat(array("active" => $_GET['status'], 'updated_at' => 'NOW()'), $_GET['i']);
                        break;
                    case "on_all":
                        return $class->updateFlatsStatus(0);
                        break;
                    case "several":
                        return $class->updateFlatsStatus($_GET['flats_ids']);
                        break;
                    case "on_page":
                        return $class->updateFlatsStatus(json_decode($_GET['flats_ids']));
                        break;
                    case "off_page":
                        break;
                }

                break;

            case "setExelActive": // Добавить в Exel
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->setFlat(array("exel_active" => $_GET['exel_active']), $_GET['i']);
                        die('OK');
                        break;
                    case "on_all":
                        return $class->updateFlatsExel(json_decode($_POST['selected_flats']), 1);
                        break;
                    case "off_all":
                        return $class->updateFlatsExel(0, 0);
                        break;
                    case "on_page":
                        return $class->updateFlatsExel(json_decode($_GET['flats_ids']), 1);
                        break;
                    case "off_page":
                        return $class->updateFlatsExel(json_decode($_GET['flats_ids']), 0);
                        break;
                }
                foreach ($_GET as $key => $value) {
                    if ($value != 'setXmlActive' && $key != 'act')
                        $url_xml .= '&' . $key . '=' . $value;
                }
                header("Location: " . $url_xml . "&w=xml_active");
                break;

            case "setXmlActive": // Добавить в XML
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->setFlat(array("xml_active" => $_GET['xml_active']), $_GET['i']);

                        die('OK');
                        break;
                    case "on_all":
                        return $class->updateFlats(0, 1);
                        break;
                    case "off_all":
                        return $class->updateFlats(0, 0);
                        break;
                    case "on_page":

                        return $class->updateFlats(json_decode($_GET['flats_ids']), 1);
                        break;
                    case "off_page":
                        return $class->updateFlats(json_decode($_GET['flats_ids']), 0);
                        break;
                }
                foreach ($_GET as $key => $value) {
                    if ($value != 'setXmlActive' && $key != 'act')
                        $url_xml .= '&' . $key . '=' . $value;
                }
                header("Location: " . $url_xml . "&w=xml_active");
                break;

            case "setXmlActiveLun": // Добавить в lun
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->setFlat(array("xml_active_lun" => $_GET['xml_active_lun']), $_GET['i']);
                        die('OK');
                        break;
                    case "on_all":
                        return $class->updateFlatsLun(0, 1);
                        break;
                    case "off_all":
                        return $class->updateFlatsLun(0, 0);
                        break;
                    case "on_page":
                        return $class->updateFlatsLun(json_decode($_GET['flats_ids']), 1);
                        break;
                    case "off_page":
                        return $class->updateFlatsLun(json_decode($_GET['flats_ids']), 0);
                        break;
                }
                foreach ($_GET as $key => $value) {
                    if ($value != 'updateFlatsLun' && $key != 'act')
                        $url_xml .= '&' . $key . '=' . $value;
                }
                header("Location: " . $url_xml . "&w=xml_active_lun");
                break;

            case "setPlectanActive": // Добавить в Plectan
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->updateFlatsPlectan($_GET['i'], $_GET['plectan_active']);
                        die('OK');
                        break;
                    case "on_all":
                        return $class->updateFlatsPlectan(0, 1);
                        break;
                    case "off_all":
                        return $class->updateFlatsPlectan(0, 0);
                        break;
                    case "on_page":
                        return $class->updateFlatsPlectan(json_decode($_GET['flats_ids']), 1);
                        break;
                    case "off_page":
                        return $class->updateFlatsPlectan(json_decode($_GET['flats_ids']), 0);
                        break;
                }
                foreach ($_GET as $key => $value) {
                    if ($value != 'updateFlatsPlectan' && $key != 'act')
                        $url_xml .= '&' . $key . '=' . $value;
                }
                header("Location: " . $url_xml . "&w=xml_active");
                break;

            case "setXmlActiveVhn": // Добавить в vhn
                $all = false;
                switch ($_GET['act']) {
                    case "one":
                        $class->setFlat(array("xml_active_vhn" => $_GET['xml_active_vhn']), $_GET['i']);
                        die('OK');
                        break;
                    case "on_all":
                        return $class->updateFlatsVhn(0, 1);
                        break;
                    case "off_all":
                        return $class->updateFlatsVhn(0, 0);
                        break;
                    case "on_page":
                        return $class->updateFlatsVhn(json_decode($_GET['flats_ids']), 1);
                        break;
                    case "off_page":
                        return $class->updateFlatsVhn(json_decode($_GET['flats_ids']), 0);
                        break;
                }
                foreach ($_GET as $key => $value) {
                    if ($value != 'updateFlatsVhn' && $key != 'act')
                        $url_xml .= '&' . $key . '=' . $value;
                }
                header("Location: " . $url_xml . "&w=xml_active_vhn");
                break;

            case "save_xml":
                $filepath = 'assets/export/';
                if ($_GET['type'] == 'all') {
                    $filename = 'yandex.xml';
                    $file = MODX_BASE_PATH . $filepath . $filename;
                    $xml = $class->yandexXmlSave($_GET['type']);
                    file_put_contents($file, $xml);
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: no-cache');
                    header('Pragma: no-cache');
                    header("Content-Length: " . filesize($file));
                    readfile($file);
                } elseif ($_GET['type'] == 'plectan') {
                    $filename = 'plectan.xml';
                    $file = MODX_BASE_PATH . $filepath . $filename;
                    $xml = $class->plektanXml();

                    unlink($file);

                    file_put_contents($file, $xml);

                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: no-cache');
                    header('Pragma: no-cache');
                    header("Content-Length: " . filesize($file));
                    readfile($file);
                } elseif ($_GET['type'] == 'vhn') {
                    $filename = 'vhn.xml';
                    $file = MODX_BASE_PATH . $filepath . $filename;
                    $xml = $class->yandexXmlSaveVhn($_GET['type']);

                    file_put_contents($file, $xml);
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: no-cache');
                    header('Pragma: no-cache');
                    header("Content-Length: " . filesize($file));
                    readfile($file);
                    //экспорт в exel
                } elseif ($_GET['type'] == 'exel') {
                    $data_export_exel = $class->yandexExelSave();
                    $xls = new PHPExcel();
                    $styleArray = array(
                        'font' => array(
                            'bold' => true
                        )
                    );
                    $xls->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                    $xls->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                    $xls->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
                    $xls->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);

                    $xls->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'ID')
                        ->setCellValue('B1', 'Название')
                        ->setCellValue('C1', 'Адрес')
                        ->setCellValue('D1', 'Тип')
                        ->setCellValue('E1', 'Цена')
                        ->setCellValue('F1', 'Код')
                        ->setCellValue('G1', 'Имя владельца')
                        ->setCellValue('H1', 'Телефон владельца')
                        ->setCellValue('I1', 'Комментарий о владельце')
                        ->setCellValue('J1', 'Описание');

                    $i = 2;
                    //for ($i = 0; $i < count($data_export_exel); $i++) {
                    foreach ($data_export_exel as $value) {
                        $xls->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $value['id'])
                            ->setCellValue('B' . $i, $value['name'])
                            ->setCellValue('C' . $i, $value['addr'])
                            ->setCellValue('D' . $i, $value['cat_name'])
                            ->setCellValue('E' . $i, $value['price'])
                            ->setCellValue('F' . $i, $value['code'])
                            ->setCellValue('G' . $i, $value['owner_name'])
                            ->setCellValue('H' . $i, $value['owner_phone'])
                            ->setCellValue('I' . $i, $value['owner_comment'])
                            ->setCellValue('J' . $i, $value['description']);

                        $i++;
                    }

                    header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
                    header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Pragma: no-cache");
                    header("Content-type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=file.xls");

                    $objWriter = new PHPExcel_Writer_Excel5($xls);
                    $objWriter->save('php://output');
                    echo '<pre>';
                    print_r($xls); die;
                } else {
                    $filename = 'lun.xml';
                    $file = MODX_BASE_PATH . $filepath . $filename;

                    $xml = $class->yandexXmlSaveLun($_GET['type']);
                    file_put_contents($file, $xml);

                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: no-cache');
                    header('Pragma: no-cache');
                    header("Content-Length: " . filesize($file));
                    readfile($file);
                }

                die;
                break;

            case "add": //добавление
                $dir = MODX_BASE_PATH . 'assets/images/flat/tmp/';
                $clean_dir = $class->clear_dir($dir);
                $agents = $class->getAgents();
                $categories = $class->categories();
                $properties1 = $class->getProperties(199); // квартиры
                $properties2 = $class->getProperties(239); // дома
                $properties3 = $class->getProperties(338); // аренда
                $properties4 = $class->getProperties(339); // бизнес
                $properties5 = $class->getProperties(340); // участок
                $appartament = $class->getAppartament();
                $tpl = "/flat/add.tpl";
                break;
            case "save": //сохранение нового пользователя
                if (is_array($_POST['flat']['rooms_type']) && count($_POST['flat']['rooms_type']) > 0)
                    $_POST['flat']['rooms_type'] = implode(",", $_POST['flat']['rooms_type']);
                if (is_array($_POST['flat']['status']) && count($_POST['flat']['status']) > 0) {
                    if (in_array(3, $_POST['flat']['status']) or in_array(4, $_POST['flat']['status'])) {
                        $_POST['flat']['xml_active'] = 0;
                        $_POST['flat']['active'] = 0;
                        $_POST['flat']['xml_active_vhn'] = 0;
                        $_POST['flat']['xml_active_lun'] = 0;
                    }
                    $_POST['flat']['status'] = implode(",", $_POST['flat']['status']);
                }
                if ($_POST['flat']['coord'] == '') {
                    // $xml = simplexml_load_file('http://maps.google.com/maps/api/geocode/xml?address=' . $_POST['flat']['addr'] . '&sensor=false');
                    // $lat = $xml->result->geometry->location->lat;
                    // $lng = $xml->result->geometry->location->lng;
                    // $_POST['flat']['coord'] = $lat . "," . $lng;
                }
                if (count($_POST['flat']['appartament']) > 0)
                    $_POST['flat']['appartament'] = json_encode($_POST['flat']['appartament']);
                else
                    $_POST['flat']['appartament'] = '';

                if(strlen($_POST['flatString']['ru']['name']) > 0){
                    $_POST['flat']['name'] = $_POST['flatString']['ru']['name'];
                } else {
                    $_POST['flat']['name'] = $_POST['flatString']['ua']['name'];
                }
                
                
                $id = $class->setFlat($_POST['flat']);


                foreach ($_POST['flatString'] as $locale => $data) {
                    $class->insertFlatStringsNew($data, $id, $locale);
                }
                if (count($_POST['images']) > 0) {
                    // Moving files into new directory
                    if (!file_exists(MODX_BASE_PATH . "assets/images/flat/$id"))
                        mkdir(MODX_BASE_PATH . "assets/images/flat/$id");
                    $files = glob(MODX_BASE_PATH . "assets/images/flat/tmp/*");
                    if (is_array($files)) {
                        foreach ($files as $file) {
                            $filename = str_replace("tmp", $id, $file);
                            copy($file, $filename);
                            unlink($file);
                        }
                    }
                    foreach ($_POST['images'] as $key => $value) {
                        $class->setImageFlat($id, $value['filename'], $key);
                    }
                }
                // dowload files
                if (count($_FILES['files']['name']) > 0) {
                    $folder = MODX_BASE_PATH . "assets/files/flat/" . $id;
                    if (!file_exists($folder)) {
                        mkdir($folder);
                        chmod($folder, 0777);
                    }
                    foreach ($_FILES['files']['name'] as $k => $f) {
                        if ($_FILES['files']['type'][$k] == 'image/jpeg'
                            || $_FILES['files']['type'][$k] == 'image/png'
                            || $_FILES['files']['type'][$k] == 'image/jpg'
                            || $_FILES['files']['type'][$k] == 'application/pdf'
                            || $_FILES['files']['type'][$k] == 'application/vnd.ms-excel'
                            || $_FILES['files']['type'][$k] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            || $_FILES['files']['type'][$k] == 'application/vnd.oasis.opendocument.text'
                            || $_FILES['files']['type'][$k] == 'application/msword') {
                            move_uploaded_file($_FILES['files']['tmp_name'][$k], $folder . "/" . $f);
                        }
                    }
                }

                header("Location: " . $url . "b=flat&c=edit&i=" . $id . "&w=saveFlat");
                die;
                break;
            case 'edit': // редактирование
                $flat = $class->getFlat($_GET['i']);
                $flatStrings = $class->getFlatStringAll($_GET['i']);
                $flatPrice = $class->getFlatPriceHistory($_GET['i']);
                $agents = $class->getAgents();
                $categories = $class->categories();
                $properties1 = $class->getProperties(199); // квартиры
                $properties2 = $class->getProperties(239); // дома
                $properties3 = $class->getProperties(338); // аренда
                $properties4 = $class->getProperties(339); // бизнес
                $properties5 = $class->getProperties(340); // участок
                $appartament = $class->getAppartament();
                $files = glob(MODX_BASE_PATH . "assets/files/flat/" . $_GET['i'] . "/*");
                $tpl = "/flat/edit.tpl";
                break;
            case "update": //обновление
                if (is_array($_POST['flat']['rooms_type']) && count($_POST['flat']['rooms_type']) > 0)
                    $_POST['flat']['rooms_type'] = implode(",", $_POST['flat']['rooms_type']);
                if (is_array($_POST['flat']['status']) && count($_POST['flat']['status']) > 0) {
                    if (in_array(3, $_POST['flat']['status']) or in_array(4, $_POST['flat']['status'])) {
                        $_POST['flat']['xml_active'] = 0;
                        //$_POST['flat']['active'] = 0;
                        $_POST['flat']['xml_active_vhn'] = 0;
                        $_POST['flat']['xml_active_lun'] = 0;
                    }
                    $_POST['flat']['status'] = implode(",", $_POST['flat']['status']);
                }
                if ($_POST['flat']['coord'] == '') {
                    $xml = simplexml_load_file('http://maps.google.com/maps/api/geocode/xml?address=' . $_POST['flat']['addr'] . '&sensor=false');
                    $lat = $xml->result->geometry->location->lat;
                    $lng = $xml->result->geometry->location->lng;
                    $_POST['flat']['coord'] = $lat . "," . $lng;
                }

                if($_POST['flat']['watermark'] != $_POST['flat']['watermark_old']){
                     unlink(MODX_BASE_PATH . 'assets/cache/images/object/' . $_POST['flat']['id'] . '/');
                }
                unset($_POST['flat']['watermark_old']);


                if (count($_POST['flat']['appartament']) > 0)
                    $_POST['flat']['appartament'] = json_encode($_POST['flat']['appartament']);
                else
                    $_POST['flat']['appartament'] = '';
                $class->setFlat($_POST['flat'], $_POST['flat']['id']);

                $history = $_POST['flatPrice']['price_history'] ?? [];

                foreach($_POST['flatString'] as $key => $value){
                       $class->setFlatStrings($value, $_POST['flat']['id'],  $key);
                } 
                
                

                if (count($_POST['images']) > 0) {
                    $class->deleteImagesFlat($_POST['flat']['id']);
                    foreach ($_POST['images'] as $key => $value)
                        $class->setImageFlat($_POST['flat']['id'], $value['filename'], $key, $_POST['types'][$key]);
                }


                $data = $_POST['flat'] ?? [];

                /**
                 * 2) История цен
                 */
                
                if (!is_array($history)) {
                    $history = [];
                }

                $class->saveFlatPriceHistory($_POST['flat']['id'], $history);


                // dowload files
                if (count($_FILES['files']['name']) > 0) {
                    $folder = MODX_BASE_PATH . "assets/files/flat/" . $_POST['flat']['id'];
                    if (!file_exists($folder)) {
                        mkdir($folder);
                        chmod($folder, 0777);
                    }
                    foreach ($_FILES['files']['name'] as $k => $f) {
                        if ($_FILES['files']['type'][$k] == 'image/jpeg'
                            || $_FILES['files']['type'][$k] == 'image/png'
                            || $_FILES['files']['type'][$k] == 'image/jpg'
                            || $_FILES['files']['type'][$k] == 'application/pdf'
                            || $_FILES['files']['type'][$k] == 'application/vnd.ms-excel'
                            || $_FILES['files']['type'][$k] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                            || $_FILES['files']['type'][$k] == 'application/vnd.oasis.opendocument.text'
                            || $_FILES['files']['type'][$k] == 'application/msword') {
                            move_uploaded_file($_FILES['files']['tmp_name'][$k], $folder . "/" . $f);
                        }
                    }
                }

                // $mem = new Memcache;
                // $mem->connect('127.0.0.1', 11211);
                // $mem->flush();
                // $mem->close();

                header("Location: " . $url . "b=flat&c=edit&i=" . $_POST['flat']['id'] . "&w=updateFlat");
                break;
            case 'chageTypeImage': // изменение типа изображения
                switch ($_GET['t']) {
                    case '0':
                        $status = 1;
                        break;
                    case '1':
                        $status = 0;
                        break;
                }
                $class->chageTypeImage($_GET['i'], $_GET['image'], $status);
                die;
                break;
            case "ban": // забанить
                $class->setFlat(array("active" => 0), $_GET['i']);
                header("Location: " . $url . "b=flat&w=blockFlat");
                break;
            case "unban": //разбанить
                $class->setFlat(array("active" => 1), $_GET['i']);
                header("Location: " . $url . "b=flat&w=activeFlat");
                break;
            case "getImages": // Список изображение заведения
                $i = 0;
                $res = '';
                if ($_GET['folder'] != '') {
                    $folder = "/assets/images/flat/" . $_GET['folder'];
                    $images = $class->getImagesFlat($_GET['folder']);
                    foreach ($images as $k => $image) {
                        $row['i'] = $k;
                        $res .= $modx->parseChunk("image.tpl", $image);
                    }
                } else {
                    $folder = "/assets/images/flat/tmp";
                    chdir(MODX_BASE_PATH . $folder);
                    $files = glob('*');
                    if (is_array($files)) {
                        foreach ($files as $image)
                            if (!is_dir(MODX_BASE_PATH . $folder . '/' . $image)) {
                                $res .= $modx->parseChunk("image.tpl", array("image" => $image, "flat_id" => "tmp"));
                            }
                    }
                }
                die($modx->parseDocumentSource($res));
                break;
case "upload": // загрузка изображений с конвертацией в JPEG и ресайзом (PHP 5.4)
    header('Content-Type: application/json; charset=utf-8');

    // ===== Конфиг =====
    $maxWidth     = 1920;                  // макс. ширина
    $maxHeight    = 1540;                  // макс. высота
    $jpegQuality  = 90;                    // качество JPEG (0..100)
    $maxFilesize  = 50 * 1024 * 1024;      // 50MB на файл (перестраховка, фронт режет своим лимитом)
    $LIMIT_TOTAL  = 100;                   // общий лимит фото в галерее

    $allowedMimes = array(
        'image/jpeg','image/pjpeg',
        'image/png',
        'image/gif',
        'image/webp',     // при наличии GD/Imagick поддержки
        'image/bmp','image/x-ms-bmp'
    );

    // ===== Папка назначения =====
    $folderKey = isset($_POST['folder']) && $_POST['folder'] !== '' ? $_POST['folder'] : 'tmp';
    $folderAbs = MODX_BASE_PATH . "assets/images/flat/" . $folderKey;
    if (!is_dir($folderAbs)) {
        @mkdir($folderAbs, 0755, true);
        @chmod($folderAbs, 0755);
    }

    // ===== Хелперы (PHP 5.4 совместимо) =====
    if (!function_exists('tr_safe_ext')) {
        function tr_safe_ext($name) {
            $p = explode('.', $name);
            return strtolower(count($p) ? end($p) : '');
        }
    }
    if (!function_exists('tr_rand_name')) {
        function tr_rand_name() {
            return dechex(mt_rand(0, 0x7fffffff)) . dechex(mt_rand(0, 0x7fffffff));
        }
    }
    if (!function_exists('tr_get_mime')) {
        function tr_get_mime($path) {
            $mime = '';
            if (function_exists('finfo_open')) {
                $f = @finfo_open(FILEINFO_MIME_TYPE);
                if ($f) {
                    $mime = @finfo_file($f, $path);
                    @finfo_close($f);
                }
            }
            if ($mime === '' || $mime === false) {
                $i = @getimagesize($path);
                if (is_array($i) && isset($i['mime'])) $mime = $i['mime'];
            }
            return $mime ? $mime : 'application/octet-stream';
        }
    }
    if (!function_exists('tr_load_gd')) {
        function tr_load_gd($path, $mime) {
            switch ($mime) {
                case 'image/jpeg':
                case 'image/pjpeg':
                    return @imagecreatefromjpeg($path);
                case 'image/png':
                    return @imagecreatefrompng($path);
                case 'image/gif':
                    return @imagecreatefromgif($path);
                case 'image/webp':
                    return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
                case 'image/bmp':
                case 'image/x-ms-bmp':
                    return function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : false;
            }
            return false;
        }
    }
    if (!function_exists('tr_resize_and_save_jpeg')) {
        function tr_resize_and_save_jpeg($srcIm, $dstPath, $maxW, $maxH, $quality) {
            $w = imagesx($srcIm);
            $h = imagesy($srcIm);
            if ($w < 1 || $h < 1) return false;

            $scale = min(1, $maxW / $w, $maxH / $h);
            $tw = max(1, (int)round($w * $scale));
            $th = max(1, (int)round($h * $scale));

            $dst = imagecreatetruecolor($tw, $th);
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);

            imagecopyresampled($dst, $srcIm, 0, 0, 0, 0, $tw, $th, $w, $h);

            $ok = imagejpeg($dst, $dstPath, $quality);
            imagedestroy($dst);
            return $ok;
        }
    }

    // ===== Проверка входных файлов =====
    if (!isset($_FILES['uploader']) || !is_array($_FILES['uploader']['tmp_name'])) {
        echo json_encode(array('ok' => false, 'error' => 'no_files'));
        break;
    }

    // ===== Получаем текущее количество и стартовую позицию =====
    $existingCount = 0;
    $nextPos       = 1;
    if ($folderKey !== 'tmp' && isset($class) && method_exists($class, 'getImagesFlat')) {
        $current = $class->getImagesFlat($folderKey); // ожидаем массив позиций => данные
        if (is_array($current)) {
            $existingCount = count($current);
            if ($existingCount > 0) {
                $keys = array_keys($current);
                // ключи — позиции
                $ints = array_map('intval', $keys);
                $nextPos = (max($ints) + 1);
            }
        }
        if ($existingCount >= $LIMIT_TOTAL) {
            echo json_encode(array('ok' => false, 'error' => 'limit_reached', 'remaining' => 0));
            break;
        }
    }

    $saved = 0;
    $failed = 0;
    $filesTotal = count($_FILES['uploader']['tmp_name']);

    // ===== Обработка файлов =====
    for ($k = 0; $k < $filesTotal; $k++) {
        $tmpPath = $_FILES['uploader']['tmp_name'][$k];

        if (!is_uploaded_file($tmpPath)) {
            $failed++;
            continue;
        }

        // Общий лимит с учётом уже сохранённых в этой сессии
        if ($folderKey !== 'tmp' && ($existingCount + $saved) >= $LIMIT_TOTAL) {
            @unlink($tmpPath);
            break;
        }

        // Размер
        if (isset($_FILES['uploader']['size'][$k]) && $_FILES['uploader']['size'][$k] > $maxFilesize) {
            @unlink($tmpPath);
            $failed++;
            continue;
        }

        // MIME + базовая валидация
        $mime = tr_get_mime($tmpPath);
        if (!in_array($mime, $allowedMimes)) {
            @unlink($tmpPath);
            $failed++;
            continue;
        }

        // Имя и путь сохранения (всегда .jpg)
        $base  = tr_rand_name();
        $nameJ = $base . '.jpg';
        $pathJ = $folderAbs . '/' . $nameJ;

        // ===== Ветка Imagick =====
        if (extension_loaded('imagick')) {
            try {
                $im = new Imagick();
                $ok = $im->readImage($tmpPath);
                @unlink($tmpPath);
                if (!$ok) { @unlink($pathJ); $failed++; continue; }

                // GIF/TIFF — берём первый кадр
                if ($im->getNumberImages() > 1) {
                    $coalesced = $im->coalesceImages();
                    $coalesced->setIteratorIndex(0);
                    $frame = $coalesced->getImage();
                    $im->clear(); $im->destroy();
                    $coalesced->clear(); $coalesced->destroy();
                    $im = $frame;
                }

                // Автоповорот по EXIF
                $ori = $im->getImageOrientation();
                if ($ori) {
                    switch ($ori) {
                        case Imagick::ORIENTATION_RIGHTTOP:    $im->rotateImage(new ImagickPixel('none'), 90);  break;
                        case Imagick::ORIENTATION_BOTTOMRIGHT: $im->rotateImage(new ImagickPixel('none'), 180); break;
                        case Imagick::ORIENTATION_LEFTBOTTOM:  $im->rotateImage(new ImagickPixel('none'), 270); break;
                    }
                    $im->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
                }

                // Ресайз при превышении лимитов
                $w = $im->getImageWidth();
                $h = $im->getImageHeight();
                if ($w > $maxWidth || $h > $maxHeight) {
                    $im->thumbnailImage($maxWidth, $maxHeight, true, false);
                }

                // Если есть альфа — кладём на белый фон
                $hasAlpha = false;
                if (method_exists($im, 'getImageAlphaChannel')) {
                    $hasAlpha = (bool)$im->getImageAlphaChannel();
                } elseif (method_exists($im, 'getImageMatte')) {
                    $hasAlpha = (bool)$im->getImageMatte();
                }
                if ($hasAlpha) {
                    $im->setImageBackgroundColor('white');
                    $flattened = $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                    $im->clear(); $im->destroy();
                    $im = $flattened;
                }

                // Конвертация в JPEG + оптимизации
                $im->setImageFormat('jpeg');
                if (defined('Imagick::COMPRESSION_JPEG')) {
                    $im->setImageCompression(Imagick::COMPRESSION_JPEG);
                }
                $im->setImageCompressionQuality($jpegQuality);
                @ $im->setOption('jpeg:optimize-coding', 'true');
                @ $im->setOption('jpeg:dct-method', 'float');
                if (method_exists($im, 'setImageInterlaceScheme')) {
                    @ $im->setImageInterlaceScheme(Imagick::INTERLACE_PLANE); // прогрессивный
                }
                if (method_exists($im, 'stripImage')) {
                    $im->stripImage();
                }

                if ($im->writeImage($pathJ) === false) {
                    $im->clear(); $im->destroy();
                    @unlink($pathJ);
                    $failed++;
                    continue;
                }
                $im->clear(); $im->destroy();

            } catch (Exception $e) {
                @unlink($pathJ);
                @unlink($tmpPath);
                $failed++;
                continue;
            }

        } else {
            // ===== Ветка GD =====
            $im = tr_load_gd($tmpPath, $mime);
            @unlink($tmpPath);
            if (!$im) {
                $failed++;
                continue;
            }
            if (!tr_resize_and_save_jpeg($im, $pathJ, $maxWidth, $maxHeight, $jpegQuality)) {
                imagedestroy($im);
                @unlink($pathJ);
                $failed++;
                continue;
            }
            imagedestroy($im);
        }

        // ===== Привязка к объекту и порядку =====
        if ($folderKey !== 'tmp' && isset($class) && method_exists($class, 'setImageFlat')) {
            // последовательная позиция без конфликтов
            $class->setImageFlat($folderKey, $nameJ, $nextPos + $saved);
        }

        // (Опционально) генерация превью через твой сниппет
        // ВНИМАНИЕ: для tmp/реального id используем разные пути
        $thumbRel = ($folderKey === 'tmp')
            ? "assets/images/flat/tmp/" . $nameJ
            : "assets/images/flat/" . $folderKey . "/" . $nameJ;

        $modx->runSnippet("R", array(
            "img"    => $thumbRel,
            "opt"    => 'w=128&h=128&zc=1',
            "folder" => ($folderKey === 'tmp' ? "object/tmp/" : "object/".$folderKey."/"),
            "out"    => '{w}x{h}--'.$nameJ
        ));

        $saved++;
    }

    // Остаток до лимита (для UI)
    $remaining = ($folderKey !== 'tmp')
        ? max(0, $LIMIT_TOTAL - ($existingCount + $saved))
        : null;

    echo json_encode(array(
        'ok'         => ($saved > 0),
        'saved'      => $saved,
        'failed'     => $failed,
        'remaining'  => $remaining
    ));
    die();
    break;


//             case "upload": // загрузка изображений с конвертацией в JPEG и ресайзом (PHP 5.4)
//     // ===== Конфиг =====
//     $maxWidth     = 1920;  // макс. ширина
//     $maxHeight    = 1540;  // макс. высота (на случай очень высоких)
//     $jpegQuality  = 90;    // качество JPEG (0..100)
//     $maxFilesize  = 50 * 1024 * 1024; // 50MB на файл
//     $allowedMimes = array(
//         'image/jpeg','image/pjpeg',
//         'image/png',
//         'image/gif',
//         'image/webp',     // только если GD с webp
//         'image/bmp','image/x-ms-bmp'
//     );

//     // ===== Папка назначения =====
//     $folderKey = isset($_POST['folder']) && $_POST['folder'] !== '' ? $_POST['folder'] : 'tmp';
//     $folderAbs = MODX_BASE_PATH . "assets/images/flat/" . $folderKey;
//     if (!is_dir($folderAbs)) {
//         @mkdir($folderAbs, 0755, true);
//         @chmod($folderAbs, 0755);
//     }

//     // ===== Хелперы (PHP 5.4 совместимо) =====
//     if (!function_exists('tr_safe_ext')) {
//         function tr_safe_ext($name) {
//             $p = explode('.', $name);
//             return strtolower(count($p) ? end($p) : '');
//         }
//     }
//     if (!function_exists('tr_rand_name')) {
//         function tr_rand_name() {
//             return dechex(mt_rand(0, 0x7fffffff)) . dechex(mt_rand(0, 0x7fffffff));
//         }
//     }
//     if (!function_exists('tr_get_mime')) {
//         function tr_get_mime($path) {
//             $mime = '';
//             if (function_exists('finfo_open')) {
//                 $f = finfo_open(FILEINFO_MIME_TYPE);
//                 if ($f) {
//                     $mime = finfo_file($f, $path);
//                     finfo_close($f);
//                 }
//             }
//             if ($mime === '' || $mime === false) {
//                 $i = @getimagesize($path);
//                 if (is_array($i) && isset($i['mime'])) $mime = $i['mime'];
//             }
//             return $mime ? $mime : 'application/octet-stream';
//         }
//     }
//     if (!function_exists('tr_load_gd')) {
//         function tr_load_gd($path, $mime) {
//             switch ($mime) {
//                 case 'image/jpeg':
//                 case 'image/pjpeg':
//                     return @imagecreatefromjpeg($path);
//                 case 'image/png':
//                     return @imagecreatefrompng($path);
//                 case 'image/gif':
//                     return @imagecreatefromgif($path);
//                 case 'image/webp':
//                     return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
//                 case 'image/bmp':
//                 case 'image/x-ms-bmp':
//                     return function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : false;
//             }
//             return false;
//         }
//     }
//     if (!function_exists('tr_resize_and_save_jpeg')) {
//         function tr_resize_and_save_jpeg($srcIm, $dstPath, $maxW, $maxH, $quality) {
//             $w = imagesx($srcIm);
//             $h = imagesy($srcIm);
//             if ($w < 1 || $h < 1) return false;

//             $scale = min(1, $maxW / $w, $maxH / $h);
//             $tw = max(1, (int)round($w * $scale));
//             $th = max(1, (int)round($h * $scale));

//             // truecolor и белый фон (чтобы убрать альфу)
//             $dst = imagecreatetruecolor($tw, $th);
//             $white = imagecolorallocate($dst, 255, 255, 255);
//             imagefill($dst, 0, 0, $white);

//             // Высококачественный ресемплинг
//             imagecopyresampled($dst, $srcIm, 0, 0, 0, 0, $tw, $th, $w, $h);

//             // Сохраняем JPEG
//             $ok = imagejpeg($dst, $dstPath, $quality);
//             imagedestroy($dst);
//             return $ok;
//         }
//     }

//     // ===== Обработка файлов =====
//     if (!isset($_FILES['uploader']) || !is_array($_FILES['uploader']['tmp_name'])) {
//         die('False'); // ожидался множественный ввод
//     }

// $filesOk = 0;
// foreach ($_FILES['uploader']['tmp_name'] as $k => $tmpPath) {
//     if (!is_uploaded_file($tmpPath)) {
//         continue;
//     }

//     // Лимит размера файла
//     if (isset($_FILES['uploader']['size'][$k]) && $_FILES['uploader']['size'][$k] > $maxFilesize) {
//         @unlink($tmpPath);
//         continue;
//     }

//     // MIME + базовая валидация
//     $mime = tr_get_mime($tmpPath);
//     if (!in_array($mime, $allowedMimes)) {
//         @unlink($tmpPath);
//         continue;
//     }

//     // Уникальное имя .jpg и путь сохранения
//     $base  = tr_rand_name();
//     $nameJ = $base . '.jpg';
//     $pathJ = $folderAbs . '/' . $nameJ;

//     if (extension_loaded('imagick')) {
//     try {
//         $im = new Imagick();
//         $ok = $im->readImage($tmpPath);
//         @unlink($tmpPath);
//         if (!$ok) { @unlink($pathJ); continue; }

//         // Многокадровые (gif/tiff): берём первый кадр корректно
//         if ($im->getNumberImages() > 1) {
//             $coalesced = $im->coalesceImages();
//             $coalesced->setIteratorIndex(0);
//             $frame = $coalesced->getImage(); // возвращает клон текущего кадра
//             $im->clear(); $im->destroy();
//             $coalesced->clear(); $coalesced->destroy();
//             $im = $frame; // здесь снова объект Imagick
//         }

//         // Автоповорот по EXIF (если есть)
//         $ori = $im->getImageOrientation();
//         if ($ori) {
//             switch ($ori) {
//                 case Imagick::ORIENTATION_RIGHTTOP:    $im->rotateImage(new ImagickPixel('none'), 90);  break;
//                 case Imagick::ORIENTATION_BOTTOMRIGHT: $im->rotateImage(new ImagickPixel('none'), 180); break;
//                 case Imagick::ORIENTATION_LEFTBOTTOM:  $im->rotateImage(new ImagickPixel('none'), 270); break;
//             }
//             $im->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);
//         }

//         // Ресайз только если больше лимитов
//         $w = $im->getImageWidth();
//         $h = $im->getImageHeight();
//         if ($w > $maxWidth || $h > $maxHeight) {
//             $im->thumbnailImage($maxWidth, $maxHeight, true, false);
//         }

//         // Если есть прозрачность — кладём на белый фон корректно
//         // Способ через mergeImageLayers: он возвращает новый объект Imagick
//         $hasAlpha = false;
//         if (method_exists($im, 'getImageAlphaChannel')) {
//             $hasAlpha = (bool)$im->getImageAlphaChannel();
//         } elseif (method_exists($im, 'getImageMatte')) {
//             $hasAlpha = (bool)$im->getImageMatte();
//         }
//         if ($hasAlpha) {
//             $im->setImageBackgroundColor('white');
//             $flattened = $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
//             $im->clear(); $im->destroy();
//             $im = $flattened; // mergeImageLayers возвращает новый Imagick
//         }

//         // Конвертация в JPEG + оптимизация
//         $im->setImageFormat('jpeg');
//         if (defined('Imagick::COMPRESSION_JPEG')) {
//             $im->setImageCompression(Imagick::COMPRESSION_JPEG);
//         }
//         $im->setImageCompressionQuality($jpegQuality);
//         @ $im->setOption('jpeg:optimize-coding', 'true');
//         @ $im->setOption('jpeg:dct-method', 'float');
//         if (method_exists($im, 'setImageInterlaceScheme')) {
//             @ $im->setImageInterlaceScheme(Imagick::INTERLACE_PLANE); // прогрессивный
//         }

//         // Убрать метаданные
//         if (method_exists($im, 'stripImage')) {
//             $im->stripImage();
//         }

//         if ($im->writeImage($pathJ) === false) {
//             $im->clear(); $im->destroy();
//             @unlink($pathJ);
//             continue;
//         }
//         $im->clear(); $im->destroy();

//     } catch (Exception $e) {
//         @unlink($pathJ);
//         @unlink($tmpPath);
//         continue;
//     }
// } else {
//         // ===========================
//         // ====== ВЕТКА GD ===========
//         // ===========================
//         $im = tr_load_gd($tmpPath, $mime);
//         @unlink($tmpPath);
//         if (!$im) {
//             continue;
//         }

//         // tr_resize_and_save_jpeg: уменьшаем ТОЛЬКО если больше лимитов (scale = min(1,...)),
//         // всегда сохраняем JPEG (очистка метаданных за счёт перекодировки)
//         if (!tr_resize_and_save_jpeg($im, $pathJ, $maxWidth, $maxHeight, $jpegQuality)) {
//             imagedestroy($im);
//             @unlink($pathJ);
//             continue;
//         }
//         imagedestroy($im);
//     }

//     // Прописать порядок/связь, если загружаем в реальный объект (не tmp)
//     if ($folderKey !== 'tmp' && isset($class) && method_exists($class, 'setImageFlat')) {
//         $class->setImageFlat($folderKey, $nameJ, 100 + $k);
//     }
//    // var_dump("assets/images/flat/tmp/" . $nameJ);
//     $modx->runSnippet("R", ["img" => "assets/images/flat/tmp/" . $nameJ, "opt" => 'w=128&h=128&zc=1', "folder" => "object/tmp/",  "out"=> '{w}x{h}--'.$nameJ]);

//     // [[R? &img=`/assets/images/flat/{flat_id}/{image}` &opt=`w=128&h=128&zc=1` &folder=`object/{flat_id}`]]
//     $filesOk++;
// }

// die($filesOk > 0 ? "True" : "False");
    break;    
            case 'delete_image': //удаление изображения
                $class->deleteImageFlat($_GET['folder'], $_GET['delete']);
                die(true);
                break;
            case 'delete_file': // удаление файла
                $folder = isset($_GET['folder']) ? basename($_GET['folder']) : '';
                $file = isset($_GET['delete']) ? basename($_GET['delete']) : '';
                $path = MODX_BASE_PATH . 'assets/files/flat/' . $folder . '/' . $file;
                if ($folder !== '' && $file !== '' && is_file($path)) {
                    unlink($path);
                }
                die("true");
                break;
            case 'delete': // удаление объекта
                if($_SESSION['mgrRole'] != 2){
                     $class->deleteFlat($_GET['i']);
                    header("Location: " . $url . "b=flat&w=deleteFlat");
                }
                break;
            default: // вывод всех юзеров
                $_SESSION['sort'] = $_GET;
                $categories_one_dept = $class->getCategory_one_dept();
                $search_params = '';
                if (empty($_GET['sort_two']) && empty($_GET['sort_three'])) {
                    $search_params = $class->getIdCategorys($_GET['sort_one']);
                } elseif (!empty($_GET['sort_one']) && empty($_GET['sort_three'])) {
                    $search_params = $class->getIdCategorys($_GET['sort_two']);
                } elseif (!empty($_GET['sort_one']) && !empty($_GET['sort_two'])) {
                    $search_params = $class->getIdCategorys($_GET['sort_three']);
                }



                $where = array();
                $param['limit'] = 20;
                if ($_GET['categ'] != '') {
                    $param['categ'] = " AND cat_name LIKE ('%" . $modx->db->escape($_GET['categ']) . "%')";
                }
                //$param['offset']  = ($_GET['page'] > 0) ? $_GET['page'] * $param['limit'] : 0;
                $param['offset'] = ($_GET['page'] > 1) ? ($_GET['page'] - 1) * $param['limit'] : 0;
                if ($_GET['flat'] != '') {
                    $where[] = "s.name LIKE ('%" . $modx->db->escape($_GET['flat']) . "%') OR s.addr LIKE ('%" . $modx->db->escape($_GET['flat']) . "%')";
                }
                if ($_REQUEST['type'] != '') {
                    $where[] = "type = '" . $modx->db->escape($_GET['type']) . "'";
                }
                if ($_GET['categ'] != '') {
                    $where['categ_name'] = " AND cat_name LIKE ('%" . $modx->db->escape($_GET['categ']) . "%')";
                }
                if ($_GET['code'] != '') {
                    $where[] = "code LIKE ('" . $modx->db->escape($_GET['code']) . "%')";
                }
                if ($_GET['sort_one'] != '') {
                    //$param['sort_one'] = "AND cat_name LIKE '"."%".$modx->db->escape($_GET['sort_one'])."%'";
                    $categories_two_dept = $class->getCategory_two_dept($_GET['sort_one']);
                }
                if ($_GET['sort_two'] != '') {
                    $categories_three_dept = $class->getCategory_two_dept($_GET['sort_two']);
                }

                if($_GET['owner'] != '') {
                    $owner = $modx->db->escape($_GET['owner']);
                    $words = array();

                    $owner = strip_tags($owner); // Удаляем теги
                    $owner = str_replace(['`','\'', '’'], '_', $owner); // Апостроф
                    
                    //нормализация телефона
                        $owner = str_replace('+38','',$owner);
                    //--
                    
                    $owner = preg_replace('/[^\d\w._]/iu',' ', $owner); // Вырезаем все символы кроме букв, цифр, минуса и нижнего подчеркивания

                    $owner = substr($owner, 0, 256); // Ограничим длинну сторки в 256 символа

                    // Бьем на слова
                    $owner = explode(" ", $owner);

                    // Генерим массив слов
                    if (is_array($owner) && count($owner) > 0) {
                        foreach ($owner as $search) {
                            //----нормализация телефона
                            if (substr($search, 0, 3)=='380') {
                                $search = substr_replace($search, null, 0, 2);
                            }
                            //--
                            
                            if (mb_strlen($search, 'UTF-8') >= 3) {
                                $words[] = $modx->db->escape($search); // На всякий случай, хотя думаю это уже лишнее
                            }
                        }
                    }

                    $whereRaw = array();

                    if (is_array($words) && count($words) > 0) {    
                        //нормализация телефона
                        $getowner = $_GET['owner'];
                        $_GET['owner'] = str_replace('+38','',$_GET['owner']);
                        //--
                        
                        $onlyDigit = preg_replace("/[^0-9]/", '', $_GET['owner']);
                        $onlyDigit = preg_split('//u', $onlyDigit, NULL, PREG_SPLIT_NO_EMPTY);
                        
                        //--
                        $_GET['owner'] = $getowner; //восстановление $_GET['owner'] для отображения в урл
                        //--

                        $max_points = count($words);
                        $points[] = "IF (owner_name LIKE '%".implode(' ', $words)."%', {$max_points}, 0)";
                        $points[] = "IF (owner_phone LIKE '%".implode(' ', $words)."%', {$max_points}, 0)";
                        $points[] = "IF (owner_comment LIKE '%".implode(' ', $words)."%', {$max_points}, 0)";

                        if (is_array($onlyDigit) && count($onlyDigit) > 5) {    
                            if(substr(implode('',$onlyDigit), 0, 3)=='380'){
                                unset($onlyDigit[0],$onlyDigit[1]);
                            }
                            unset($words);
                            $points[]   = "IF (owner_name REGEXP '".implode('[^0-9]*', $onlyDigit)."', {$max_points}, 0)";
                            $points[]   = "IF (owner_phone REGEXP '".implode('[^0-9]*', $onlyDigit)."', {$max_points}, 0)";
                            $points[]   = "IF (owner_comment REGEXP '".implode('[^0-9]*', $onlyDigit)."', {$max_points}, 0)";
                            $whereRaw[] = "owner_name REGEXP '".implode('[^0-9]*', $onlyDigit)."'";
                            $whereRaw[] = "owner_phone REGEXP '".implode('[^0-9]*', $onlyDigit)."'";
                            $whereRaw[] = "owner_comment REGEXP '".implode('[^0-9]*', $onlyDigit)."'";
                        }

                        $fields = [
                            'owner_name' => 1,
                            'owner_phone' => 1,
                            'owner_comment' => 1,
                        ];

                        foreach ($words as $ser) {
                            if (strlen(trim(preg_replace('/[^\w]/iu', '', $ser))) > 2 ) {
                                foreach ($fields as $field => $priority) {
                                    $points[] = "IF ({$field} LIKE '%{$ser}%', '{$priority}', '0')";
                                    $whereRaw[] = "{$field} LIKE '%$ser%'";
                                }
                            }
                        }

                        if (count($whereRaw) > 0) {
                            $where[] = implode(" OR ", $whereRaw);
                        }

                        $param['points'] = implode(' + ', $points);
                    }
                }

                if($_GET['status'] != '') {
                    $where[] = "status LIKE ('%".$modx->db->escape($_GET['status'])."%')";
                }

                $cat_id = 5;

                if ($_GET['sort_one'] != '') {
                    $cat_id = $_GET['sort_one'];
                }

                if ($_GET['sort_two'] != '') {
                    $cat_id = implode(',' ,$_GET['sort_two']);
                }

                if ($_GET['sort_three'] != '') {
                    $cat_id = implode(',' ,$_GET['sort_three']);
                }

                $flats = $class->getFlats($param, $where, $cat_id, 'id DESC');


                $param['limit']  = 100000;
                $param['offset'] = 0;
                $all_current_ids = $class->getFlats($param, $where, $cat_id);
                foreach($all_current_ids as $value) {
                    $selected_flats .= $value['id'].',';
                }

                $selected_flats = trim($selected_flats,',');



                $count = $class->getFoundRows();
                $pagin = $class->getPaginate($count, $url."b=flat".
                  ($_GET['flat'] != '' ? "&flat=".$_GET['flat'] : "").
                  ($_GET['type'] != '' ? "&type=".$_GET['type'] : "").
                  ($_GET['code'] != '' ? "&code=".$_GET['code'] : "").
                  ($_GET['owner'] != '' ? "&owner=".$_GET['owner'] : "").
                  //($_GET['sort_one'] != '' ? "&sort_one=".$_GET['sort_one'] : "").
                    (isset($_GET['sort_one']) ? "&sort_one=".$_GET['sort_one'] : "").
                    (isset($_GET['sort_two']) ? '&sort_two[]=' . implode('&sort_two[]=', $_GET['sort_two']) : "").
                    (isset($_GET['sort_three']) ? '&sort_three[]=' . implode('&sort_three[]=', $_GET['sort_three']) : "").
                    (isset($_GET['status']) ? "&status=".$_GET['status'] : "").

                  "&page=", 20);
                $tpl = "/flat/index.tpl";

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
