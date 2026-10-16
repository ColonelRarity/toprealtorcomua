<?php
/** Хелпер для языковых тегов */
if (!function_exists('T')) {
    function T($id){ global $modx; return $modx->parseDocumentSource('[#'.$id.'#]'); }
}


$lang = $_REQUEST['lang'];

/** Безопасные дефолты */
$response = [];                          // чтобы json_encode не получил undefined
$ajax = isset($_REQUEST['ajax']) ? $_REQUEST['ajax'] : '';

switch ($ajax) {

    case 'course':
        $course = ["UAH", "USD", "EURO"];
        if (isset($_POST['c']) && in_array($_POST['c'], $course, true)) {
            $_SESSION['course'] = $_POST['c'];
            $response['status'] = true;
        }
        break;

    case 'send_email':
            if (isset($_POST['send']) && is_array($_POST['send']) && count($_POST['send']) > 0) {

                $loopCheck = $security->guardAgainstLoop('send_form_guard', [
                    $_POST['send']['id'] ?? '',
                    $_POST['send']['email'] ?? '',
                    $_POST['link'] ?? '',
                ], 50);

                if (!$loopCheck['ok']) {

                    $response['mess']   = T(200);
                    $response['status'] = false;

                } else {


                            $hostCheck = $security->verifyRecaptchaHostname($recaptchaCheck, $expectedHosts);

                            if (!$hostCheck['ok']) {

                                $response['mess']   = T(200);
                                $response['status'] = false;

                            } else {

                                $sendId = isset($_POST['send']['id']) ? (int)$_POST['send']['id'] : 0;
                                $sendEmail = isset($_POST['send']['email']) ? trim((string)$_POST['send']['email']) : '';
                                $link = isset($_POST['link']) ? trim((string)$_POST['link']) : '';

                                if (!filter_var($sendEmail, FILTER_VALIDATE_EMAIL) || $sendId <= 0) {
                                    $response['mess']   = T(200);
                                    $response['status'] = false;
                                } else {

                                    $item = $class->getFlat($sendId);

                                    if (!$item || !is_array($item)) {
                                        $response['mess']   = T(200);
                                        $response['status'] = false;
                                    } else {

                                        $item['tv_seo_title'] = $item['tv_seo_title'] != '' ? $item['tv_seo_title'] : $item['name'] . " | " . $modx->config['site_name'];
                                        $item['price']        = $class->getPrice($item['price'], $modx->config['course']);
                                        $item['old_price']    = $class->getPrice($item['old_price'], $modx->config['course']);
                                        $item['updated_at']   = date("d/m/Y", strtotime($item['updated_at']));

                                        if (!empty($item['character']) && is_array($item['character'])) {
                                            $character = '';
                                            foreach ($item['character'] as $c) {
                                                if ($c !== '') {
                                                    $character .= $modx->parseChunk('tpl_character', ["c" => $c]);
                                                }
                                            }
                                            $item['character'] = $character;
                                        }

                                        $pdf = $modx->parseDocumentSource($modx->parseChunk("tpl_pdf_dowload", $item));

                                        include MODX_BASE_PATH . "assets/logic/php/mpdf/mpdf.php";

                                        $mpdf = new mPDF('utf-8', 'A4', '10', 'Arial', 0, 0, 5, 5, 5, 5);
                                        $mpdf->charset_in = 'utf-8';
                                        $mpdf->WriteHTML($pdf);
                                        $mpdf->Output(MODX_BASE_PATH . '/assets/files/Object.pdf', 'F');

                                        $pagetitle = $modx->db->getValue(
                                            $modx->db->select('name', 'modx_a_flat', "id=" . $sendId)
                                        );

                                        $body = $modx->evalSnippets(
                                            $modx->parseChunk("mail_item", [
                                                "pagetitle" => $pagetitle,
                                                "link"      => $link
                                            ])
                                        );

                                        $class->sendMail(
                                            $sendEmail,
                                            T(222) . $modx->config['site_name'],
                                            $body,
                                            'Object.pdf',
                                            MODX_BASE_PATH . "/assets/files/Object.pdf"
                                        );

                                        $response['mess']   = T(223);
                                        $response['status'] = true;

                                        $security->clearCsrf($csrfCheck['field']);
                                    }
                                }
                            
                    }
                }
            }
        break;

    case "fullImage":
        if (isset($_POST['i']) && $_POST['i'] !== '') {
            $image = $modx->db->getRow($modx->db->query(
                "SELECT *, (SELECT watermark FROM `modx_a_flat` WHERE id = flat_id) AS `watermark`
                 FROM `modx_a_flat2image` WHERE id = '" . $modx->db->escape($_POST['i']) . "'"
            ));
            if ($image) {
                $opt = (isset($image['watermark']) && (int)$image['watermark'] === 1) ? ",fltr_wmi|images/watermark.png|BL|100|50|50" : "";
                $response['image'] = $modx->runSnippet("R", [
                    "img" => 'assets/images/flat/' . $image['flat_id'] . "/" . $image['image'],
                    "opt" => "w=1920,zc=1" . $opt
                ]);
            }
        }
        break;

    case "rating":
        if (isset($_POST['page_id'], $_POST['value'])) {
            $count = $modx->db->getValue($modx->db->query(
                "SELECT count(*) FROM `modx_a_rating`
                 WHERE user_ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "')
                 AND page_id = " . (int)$modx->db->escape($_POST['page_id'])
            ));
            if ((int)$count === 0) {
                $modx->db->query(
                    "INSERT INTO `modx_a_rating` (page_id, user_ip, rating)
                     VALUE (" . (int)$modx->db->escape($_POST['page_id']) . ",
                            inet_aton('" . $_SERVER['REMOTE_ADDR'] . "'),
                            " . (int)$modx->db->escape($_POST['value']) . ")"
                );
                $response['mess']   = T(224);
                $response['status'] = true;
            } else {
                $response['mess']   = T(225);
                $response['status'] = false;
            }
        }
        break;

    case "setCurrency":
        $modx->clearCache('full', true);
        $currency = explode("|", $modx->config['shop_curr_code']);
        $currReq  = isset($_GET['curr']) ? $_GET['curr'] : null;
        $_SESSION['currency'] = (in_array($currReq, $currency, true) ? $currReq : $modx->config['shop_curr_default_code']);
        $response['status'] = false;
        break;

    case 'info':
        if (isset($_SESSION['mgrRole'], $_POST['type'])) {
            switch ($_POST['type']) {
                case 'user':
                    if (isset($_POST['i'])) {
                        $flat = $class->getFlat($_POST['i']);
                        $response['mess']   = $modx->parseDocumentSource($modx->parseChunk("tpl_modal_offer_".$lang, $flat));
                        $response['status'] = true;
                    }
                    break;
                case 'file':
                    if (isset($_POST['i'])) {
                        $files = glob(MODX_BASE_PATH . "assets/files/flat/" . $_POST['i'] . "/*");
                        if (!empty($files)) {
                            $f = ''; // init!
                            foreach ($files as $file) {
                                $filename = end(explode('/', $file));
                                $f .= $modx->parseChunk("tpl_modal_files", ['filename' => $filename, "flat_id" => $_POST['i']]);
                            }
                            $response['mess']   = '<ul>' . $modx->rewriteUrls($modx->parseDocumentSource($f)) . '</ul>';
                            $response['status'] = true;
                        }
                    }
                    break;
                case 'flat':
                    if (isset($_POST['i'])) {
                        $flat = $class->getFlat($_POST['i']);
                        $response['mess']   = $modx->parseDocumentSource($modx->parseChunk("modal_flat_info_".$lang, $flat));
                        $response['status'] = true;
                    }
                    break;
            }
        }
        break;

    case 'new_send_subs':
        if (isset($_SESSION['mgrRole'], $_POST['i'])) {
            $page = $class->getPage($_POST['i']);
            $class->setPage($_POST['i'], ["send_time" => date("Y-m-d H:i:s")]);
            $body = $modx->parseChunk("mail_article", $page);
            $body = $modx->rewriteUrls($modx->parseDocumentSource($body));
            if ($body !== '') {
                $modx->db->query(
                    "INSERT INTO `modx_a_mailer_templates`
                     SET template_subject = '" . $modx->db->escape(T(226) . $modx->config['site_name']) . "',
                         template_post    = '" . $modx->db->escape($body) . "'"
                );
                $id = $modx->db->getInsertId();
                $modx->db->query(
                    "INSERT INTO `modx_a_mailer` (tpl_id, user_id)
                     SELECT '" . (int)$id . "' AS tpl_id, u.user_id
                     FROM `modx_a_mailer_users` u
                     ON DUPLICATE KEY UPDATE user_id = u.user_id"
                );
            }
            $response['status'] = true;
            $response['mess']   = T(227);
        }
        break;

        case 'priceHistory':
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $response = array('rows' => array());
            if ($id > 0) {
                $show = $modx->db->getValue($modx->db->select('show_price_history', 'modx_a_flat', 'id=' . $id));
                if ((string)$show === '1') {
                    $res = $modx->db->query("SELECT `price`, `start_date` FROM `modx_a_flat2price` WHERE `flat_id` = " . $id . " ORDER BY `start_date` DESC, `id` DESC");
                    if ($res) {
                        while ($row = $modx->db->getRow($res)) {
                            $response['rows'][] = $row;
                        }
                    }
                }
            }
            break;

        case 'slider':
        if (!empty($_POST['i'])) {
            $flat = $class->getFlat($_POST['i']);
            $modx->documentObject['preview'] = $flat['preview'];
            $modx->documentObject['id']      = $flat['id'];

            $response['bigSlider'] = $modx->runSnippet("S", [
                "get" => "object_slider", "tpl" => "tpl_object_slider", "id" => $_POST['i'],
                "watermark" => isset($_POST['watermark']) ? $_POST['watermark'] : '',
                'type_width' => isset($_POST['m']) ? $_POST['m'] : ''
            ]);
            $response['bigSlider'] = $modx->parseDocumentSource($response['bigSlider']);

            $response['smallSlider'] = $modx->runSnippet("S", [
                "get" => "object_slider", "tpl" => "tpl_object_list_slide", "id" => $_POST['i'],
                "watermark" => isset($_POST['watermark']) ? $_POST['watermark'] : '',
                'type_width' => 'small'
            ]);
            $response['smallSlider'] = $modx->parseDocumentSource($response['smallSlider']);
            $response['status'] = true;
        }
        break;
}

die(json_encode($response));
