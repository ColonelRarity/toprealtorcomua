<?php

require MODX_BASE_PATH . "assets/logic/class.php";
$class = new Corp($modx);

$lang = $modx->config['lang'];
/*
 *	Технический сниппет
 */
switch ($get) {
    case 'currency':
        $title = explode("|", $modx->config['shop_curr_name']);
        $sign  = explode("|", $modx->config['shop_curr_sign']);
        $codes = explode("|", $modx->config['shop_curr_code']);
        foreach ($title as $key => $val) {
            if ($sign[$key] != $modx->config['currency']) {
                $res .= $modx->parseChunk($tpl, ["id" => $codes[$key], "pagetitle" => $sign[$key]]);
            }
        }
        break;

    case 'search_currency':
        $title = explode("|", $modx->config['shop_curr_name']);
        $sign  = explode("|", $modx->config['shop_curr_sign']);
        $codes = explode("|", $modx->config['shop_curr_code']);
        foreach ($title as $key => $val) {
            $res .= $modx->parseChunk($tpl, ["id" => $codes[$key], "pagetitle" => $sign[$key]]);
        }
        break;

    case 'show_course':
        $res = $modx->config['course'];
        break;

    case 'show_currency':
        $res = $modx->config['currency'];
        break;

    /*
     * Хлебные крошки языковые
     */
    case "breadcrumbs":
        $id             = isset($id) ? $id : $modx->documentIdentifier;
        $showHomeCrumb  = isset($showHomeCrumb) ? $showHomeCrumb : true;
        $showLastCrumb  = isset($showLastCrumb) ? $showLastCrumb : true;
        $showHidden     = isset($showHidden) ? $showHidden : true;
        $separator      = isset($separator) ? $separator : '';
        $lastAsLink     = isset($lastAsLink) ? $lastAsLink : ($modx->documentIdentifier == 134);
        $title          = isset($title) ? $title : 'pagetitle';
        $lastCrumbClass = isset($lastCrumbClass) ? $lastCrumbClass : 'active';
        $documents      = [];
        $outerTpl       = '<div class="_breadcumbs">[+crumbs+]</div>';
        $crumbTpl       = '<a href="[~~[+url+]~~]" class="breadcrumb-main">[+title+]</a>';
        $lastCrumbTpl   = '<span class="breadcrumb-last">[+title+]</span>';

        if ($showHomeCrumb) {
            $documents[] = $modx->getConfig('site_start');
        }
        $documents = array_merge($documents, array_reverse(array_values($modx->getParentIds($id))));
        if ($showLastCrumb) {
            $documents[] = $id;
        }
        $document = implode(',', $documents);
        $document = trim($document,',');
        $fields   = $modx->config['lang_enable'] ? "pagetitle_" . $modx->config['lang'] . " as 'pagetitle', menutitle_" . $modx->config['lang'] . " as 'menutitle'" : "pagetitle, menutitle";
        $query
            = "select
	                            id, template, $fields
	                      from `modx_site_content`
	                      where id in (" . $document . ") " . ($showHidden ? "" : "and hidemenu = 0") . "
	                      order by field(id, " . $document . ")";
        $crumbs   = $modx->db->query($query);
        while ($crumb = $modx->db->getRow($crumbs)) {

            if ($crumb['template'] != 0 && $crumb['id'] != 5) {
                $parse[] = strtr(($crumb['id'] == end($documents) && !$lastAsLink ? $lastCrumbTpl : $crumbTpl),
                    ["[+url+]"   => $crumb['id'],
                        "[+title+]" => ($title == "menutitle" && $crumb['menutitle'] != "" ? $crumb['menutitle'] : $crumb['pagetitle'])]);
            }
        }
        if ($modx->documentIdentifier == 134) {
            $parse[] = strtr($lastCrumbTpl, ["[+title+]" => $modx->documentObject['name']]);
        }
        $res = str_replace("[+crumbs+]", implode("", $parse), $outerTpl);
        break;


    /**
     * Карта сайта
     */
    case "sitemap":
        // дерево
        $res .= $modx->runSnippet("Wayfinder", ["startId" => 0, "level" => 4, "outerTpl" => "wf_wrapTpl", "rowTpl" => "wf_rowTpl_sitemap"]);
        //каталог
        $res .= $modx->runSnippet("Wayfinder", ["startId" => 5, "level" => 4, "outerTpl" => "wf_wrapTpl", "rowTpl" => "wf_rowTpl_sitemap"]);
        //объекты
        $q = $modx->db->query("SELECT url, name as 'title' FROM `modx_a_flat` WHERE active = 1");
        while ($r = $modx->db->getRow($q)) {
            $body .= $modx->parseChunk("sitemap_one", ["url" => $modx->makeUrl(134) . $r['url'] . "/", "title" => $r['title'], "body" => "", "url_false" => 0]);
        }
        $res .= $modx->parseChunk("sitemap_one", ["url" => '', "title" => "Объекты", "body" => $body, "url_false" => 1, "body_true" => 1]);
        break;

    /**
     * Mobile menu
     */
    case 'mobile_menu':
        $menu = $class->menu();
        while ($cat = $modx->db->getRow($menu)) {
            $list[$cat['id']] = $cat;
        }

        foreach ($list as $k1 => $v1) {
            // 1 level
            if ($v1['parent'] == 5 or $v1['parent'] == 0) {
                // 2 level
                $l2 = '';
                foreach ($list as $k2 => $v2) {
                    if ($k1 == $v2['parent']) {
                        //3 level
                        $l3 = '';
                        foreach ($list as $k3 => $v3) {
                            if ($k2 == $v3['parent']) {
                                // 4 level
                                $l4 = '';
                                foreach ($list as $k4 => $v4)
                                    if ($k3 == $v4['parent']) {
                                        $l4 .= $modx->parseChunk($rowTpl, ["id" => $v4['id'], "title" => $v4['pagetitle'], "short_title" => htmlspecialchars($v4['longtitle']), "wrapper" => '', "li_class" => '', "ul_class" => '']);
                                    }
                                $l4 = $l4 != '' ? $modx->parseChunk($wrapRowTpl, ['li' => $l4, "ul_class" => "third-ul"]) : " ";
                                $l3 .= $modx->parseChunk($rowTpl, ["id" => $v3['id'], "title" => $v3['pagetitle'], "short_title" => htmlspecialchars($v3['longtitle']), "wrapper" => $l4, "li_class" => 'drop-third', "mob-butt" => 'mob-butt2']);

                            }
                        }

                        $l3 = $l3 != '' ? $modx->parseChunk($wrapRowTpl, ['li' => $l3, "ul_class" => "second-ul"]) : " ";

                        $l2 .= $modx->parseChunk($rowTpl, ["id" => $v2['id'], "title" => $v2['pagetitle'], "short_title" => htmlspecialchars($v2['longtitle']), "wrapper" => $l3, "li_class" => 'drop-sec', "mob-butt" => 'mob-butt1']);
                    }
                }

                $li_class = ($l2 != '') ? 'drop' : '';
                $l2       = $l2 != '' ? $modx->parseChunk($wrapRowTpl, ['li' => $l2, "ul_class" => "first-ul"]) : " ";
                $wrapper  .= $modx->parseChunk($rowTpl, ["id" => $v1['id'], "title" => $v1['pagetitle'], "short_title" => htmlspecialchars($v1['longtitle']), "wrapper" => $l2, "li_class" => $li_class, "mob-butt" => 'mob-butt']);
            }
        }
        $res = $modx->parseDocumentSource($modx->parseChunk($outerTpl, ["wrapper" => $wrapper]));
        break;

    /**
     * Список валют
     */
    case 'course':
        $course = ["UAH", "USD", "EURO"];
        foreach ($course as $key => $value)
            if ($value == $_SESSION['course']) {
                unset($course[$key]);
            } else {
                $c[] = $value;
            }
        $res = $modx->parseChunk($tpl, ["curr" => $_SESSION['course'], "c1" => $c[0], "c2" => $c[1]]);

        break;

    /**
     * Получение числа из строки
     */
    case 'integer':
        $res = $class->number($param);
        break;

    /**
     * Получение числа из строки
     */
    case 'urlencode':
        $res = urlencode($param);
        break;

    /**
     * Каталог на главной странице
     */
    case 'index_catalog':
        $cat = $class->index_catalog();
        foreach ($cat as $key => $value) {
            $list = '';
            if ($value['parent'] == 5) {
                foreach ($cat as $k2 => $v2) {
                    if ($v2['parent'] == $value['id']) {
                        $list .= $modx->parseChunk($rowTpl, ["id" => $v2['id'], "pagetitle" => $v2['pagetitle']]);
                    }
                }
                $value['list'] = ($list != '') ? $list : "";
                $res           .= $modx->parseChunk($outerTpl, $value);
            }
        }
        break;

    /**
     * Вывод списка новинок на главной
     */
    case 'index_news':
        $where           = [];
        $where[]         = 'active = 1';
	
		//исключаем скрытую продажу для неавторизованных
		if($modx->documentObject['admin_login']!='yes') {$where[] = ' status!=5 ';}
	
        $param['limit']  = ($limit != '') ? $limit : 3;
        $pageNum = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
        $param['offset'] = ($pageNum - 1) * $param['limit'];
        $flats           = $class->getFlats($param, $where, 6);
        foreach ($flats as $flat) {
            $flat['updated_at'] = date('d/m/Y', strtotime($flat['updated_at']));
            if ($flat['character'] != '') {
                $flat['character'] = json_decode($flat['character'], true);
            }
            if (count($flat['character']) > 0 && $flat['character'] != '') {
                $character = '';
                foreach ($flat['character'] as $c)
                    if ($c != '') {
                        $character .= $modx->parseChunk('tpl_character', ["c" => $c]);
                    }
                $flat['character'] = $character;
            }
            $flat['price']     = $class->getPrice($flat['price'], $modx->config['course']);
            $flat['old_price'] = $class->getPrice($flat['old_price'], $modx->config['course']);

            $flat['name'] = htmlentities($flat['name']);
            $flat['description'] = htmlentities($flat['description']);

            if ($flat['watermark'] == 1) {
                $flat['opt'] = ',fltr_wmi|/images/watermark-min.png|C|99|50|50';
            }
            switch ($flat['type']) {
                 case '1':
                    $flat['type_name'] = '[#127#]';
                    break;
                case '2':
                    $flat['type_name'] = '[#267#]';
                    break;
                case '3':
                    $flat['type_name'] = '[#234#]';
                    break;
                case '4':
                    $flat['type_name'] = '[#91#]';
                    break;
                case '5':
                    $flat['type_name'] = '[#92#]';
                    break;
                case '6':
                    $flat['type_name'] = '[#233#]';
                    break;
            }
            $res .= $modx->parseDocumentSource($modx->parseChunk($tpl, $flat));
        }
        break;

    /**
     * Вывод вермени в формате
     */
    case 'date_format':
        if ($timestamp == 1) {
            $time = strtotime($time);
        }
        $res = date($format, $time);
        break;

    /*
     * Выбираем тв поле
     */
    case "getTv":
        $tv  = $class->getTv($id, $tv);
        $res = $tv['value'];
        break;

    /**
     * Экранирование текста
     */
    case 'escape':
        $res = $modx->db->escape($text);
        break;

    /**
     * Отзывы
     */
    case 'reviews':
        $param['limit']  = ($limit != '') ? $limit : 1;
        $pageNum = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
        $param['offset'] = ($pageNum - 1) * $param['limit'];
        $reviews         = $class->getReviews($param);
        foreach ($reviews as $r)
            $res .= $modx->parseChunk($tpl, $r);
        $count = $class->getFoundRows();
        $pagin = $class->getPaginate($count, $modx->makeUrl($modx->documentObject['id']) . "?page=", $param['limit'], 'ditto_pagi_link', 'ditto_pagi_currlink', 'ditto_next', 'ditto_prev');
        if ($pagin != '') {
            $modx->setPlaceholder("pagin", $pagin);
        }
        if ($count == 0) {
            $modx->regClientScript("<script>$('.sidebar .sidebar__post:eq(1)').hide();</script>");
        }
        break;

    /**
     * Вывод объектов
     */
    case "getFlat":

        $where           = [];
        $where[]         = 'active = 1';
	
		//исключаем скрытую продажу для неавторизованных
		if($modx->documentObject['admin_login']!='yes') {$where[] = ' status!=5 ';}
		//если поиск по коду, то убираем из параметров тип недвижимости
		if (!empty($_GET['code']) && is_numeric($_GET['code'])) {unset($_GET['type'],$_GET['price_min'],$_GET['price_max'],$_GET['area_min'],$_GET['area_max'],$_GET['rooms'],$_GET['district'],$_GET['rooms_type'],$_GET['time'],$_GET['course']);}
	
        $param['limit']  = ($limit != '') ? $limit : 10;
        $pageNum = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
        $param['offset'] = ($pageNum - 1) * $param['limit'];

        // parametrs from GET
        if (count($_GET) > 0) {
            foreach ($_GET as $k => $v) {
                if ($v != '') {
                    switch ($k) {
                        case 'type':
                            $where[]    = "type = '" . (int)$v . "'";
                            $pagi_url[] = "type=" . (int)$v;
                            break;
                        case 'price_min':
                            switch ($_GET['course']) {
                                case '2':
                                    $course = 'UAH';
                                    break;
                                case '3':
                                    $course = 'EURO';
                                    break;
                                default:
                                    $course = 'USD';
                                    break;

                            }
                            $rawPrice   = $v;
                            $v          = (int)$class->priceToBase($rawPrice, $course);
                            $where[]    = 'price > ' . $v;
                            $pagi_url[] = 'price_min=' . (int)$rawPrice;

                            break;
                        case 'price_max':
                            switch ($_GET['course']) {
                                case '2':
                                    $course = 'UAH';
                                    break;
                                case '3':
                                    $course = 'EURO';
                                    break;
                                default:
                                    $course = 'USD';
                                    break;
                            }
                            $rawPrice = $v;
                            if ($_GET['course'] == '2' && $rawPrice != '25000000') {
                                $v       = (int)$class->priceToBase($rawPrice, $course);
                                $where[] = 'price < ' . $v;
                            } elseif ($_GET['course'] != '2' && $rawPrice != '1000000') {
                                $v       = (int)$class->priceToBase($rawPrice, $course);
                                $where[] = 'price < ' . $v;
                            }
                            $pagi_url[] = 'price_max=' . (int)$rawPrice;
                            break;
                        case 'area_min':
                            $where[]    = '(area > '.(int)$v.' || h_area > '.(int)$v.')';
                            $pagi_url[] = 'area_min=' . (int)$v;
                            break;
                        case 'area_max':
                            if ($v != '1000') {
                                $where[] = 'area < ' . (int)$v;
                            }
                            $pagi_url[] = 'area_max=' . (int)$v;
                            break;
                        case 'rooms':
                            if (is_array($v) && count($v) > 0) {
                                foreach ($v as $r)
                                    $pagi_url[] = 'rooms[]=' . $r;
                                $rooms_type = $rooms = [];
                                //$v = explode(',', $v[0]);
                                if ($_GET['type'] == 1) {
                                    foreach ($v as $r) {
                                        // var_dump($r);
                                        if ($r > 4) {
                                            $rooms_type[] = "rooms_type LIKE ('%" . (int)$r . "%') ";
                                        } else {
                                            if ($r == 4) {
                                                $rooms[] = "rooms >= '" . (int)$r . "'";
                                            } else {
                                                $rooms[] = "rooms = '" . (int)$r . "'";
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($v as $r)
                                        $rooms[] = ($r == 4) ? "rooms >= '" . (int)$r . "'" : "rooms = '" . (int)$r . "'";
                                }

                                if (count($rooms_type) > 0) {
                                    $rooms   = implode(' OR ', $rooms);
                                    $where[] = "(" . implode(' AND ', $rooms_type) . ($rooms != '' ? " AND (" . $rooms . ")" : '') . ")";
                                } else {
                                    if (count($rooms) > 0) {
                                        $where[] = "(" . implode(' OR ', $rooms) . ")";
                                    }
                                }
                            }
                            break;
                        case 'district':
                            if (is_array($v) && count($v) > 0) {
                                $district   = [];
                                $pagi_url[] = 'district[]=' . implode(',', $v);
                                foreach ($v as $d)
                                    if ($d != '') {
                                        $district[] = $d;
                                    }
                                $districtIds = array();
                                foreach ($district as $d) {
                                    $d = (int)$d;
                                    if ($d > 0) {
                                        $districtIds[] = $d;
                                    }
                                }
                                if (count($districtIds) > 0) {
                                    $districtIn = implode(',', $districtIds);
                                    if ($_GET['type'] == '1') {
                                        $where[] = " district IN (" . $districtIn . ")";
                                    } else {
                                        $where[] = " id IN (select flat_id from `modx_a_flat2cat` where cat_id IN (" . $districtIn . "))";
                                    }
                                }
                            } else {
                                if ($_GET['type'] == 4 && $v == 4) {
                                    if (isset($_GET['district_detail'])) {
                                        $where[] = " district = '" . (int)$_GET['district_detail'] . "'";
                                    } else {
                                        $where[] = " ( district = '1' OR district = '2')";
                                    }
                                } else {
                                    $where[] = " district = '" . (int)$v . "'";
                                }
                                $pagi_url[] = 'district=' . (int)$v;
                            }
                            break;
                        case 'code':
                            // Получаем строку для поиска
                            $search_string = $v;

                            $search_string = strip_tags($search_string); // Удаляем теги
                            $search_string = str_replace(['`','\'', '’'], '_', $search_string); // Апостроф
                            $search_string = preg_replace('/[^\d\w-_]/iu',' ', $search_string); // Вырезаем все символы кроме букв, цифр, минуса и нижнего подчеркивания

                            //$search_string = substr($search_string, 0, 256); // Ограничим длинну сторки в 256 символа

                            // Бьем на слова
                            //$search_string = explode(" ", $search_string);
                            $search = $search_string;
                            $prepare = array();

                            if (mb_strlen($search, 'UTF-8') >= 3) {
                                $search    = $modx->db->escape($search);
                                $prepare[] = "code LIKE('%{$search}%')";
                                $prepare[] = "s.name LIKE('%{$search}%')";
                                $prepare[] = "s.addr LIKE('%{$search}%')";
                                $prepare[] = "s.description LIKE('%{$search}%')";
                            }

                            if (!empty($prepare)) {
                                $where[] = ' (' . implode(' OR ', $prepare) . ') ';
                                $points = array();
                                foreach ($prepare as $expr) {
                                    $points[] = 'IF (' . $expr . ', 1, 0)';
                                }
                                $param['points'] = implode(' + ', $points);
                            }

                            //$where[]    = " ( code LIKE('%" . $modx->db->escape($v) . "%') OR name LIKE('%" . $modx->db->escape(trim($v)) . "%')) ";
                            //$pagi_url[] = 'code=' . (int)$v;
                            $pagi_url[] = 'code=' . $modx->db->escape(trim($v));
                            break;
                        case 'rooms_type':
                            switch ($_GET['type']) {
                                case '2': // дома
                                    $rooms_type = [];

                                    foreach ($v as $r) {
                                        switch ($r) {
                                            case '1':
                                            case '2':
                                            case '3':
                                                $or[] = "rooms_type LIKE ('%" . (int)$r . "%')";
                                                break;
                                            case '4':
                                            case '5':
                                            case '6':
                                                $and[] = "rooms_type LIKE ('%" . (int)$r . "%')";
                                                break;
                                        }

                                    }
                                    if (count($or) > 0) {
                                        $where[] = "(" . implode(' OR ', $or) . ")";
                                    }
                                    if (count($and) > 0) {
                                        $where[] = "(" . implode(' AND ', $and) . ")";
                                    }
                                    break;
                                case '3': // участок
                                    if ($v == 2) { // под коммерцию
                                        $where[] = " rooms_type LIKE('%5%') ";
                                    } else { // по застройку
                                        if (count($_GET['rooms_type_detail']) > 0) {
                                            foreach ($_GET['rooms_type_detail'] as $r)
                                                if ($r != '') {
                                                    $rooms_type_detail[] = $modx->db->escape($r);
                                                }
                                            if (count($rooms_type_detail) > 0) {
                                                foreach ($rooms_type_detail as $rtd)
                                                    $where[] = ' rooms_type LIKE ("%' . $modx->db->escape($rtd) . '%") ';
                                            }
                                        } else {
                                            $where[] = " ( rooms_type LIKE ('%1%') or rooms_type LIKE ('%2%') or rooms_type LIKE ('%3%') or rooms_type LIKE ('%4%'))";
                                        }
                                    }
                                    break;
                                case '5':
                                    $where[] = " rooms_type LIKE ('%" . (int)$v . "%') ";
                                    break;
                                default:
                                    if (is_array($v) && count($v) > 0) {
                                        $rooms_type = [];
                                        foreach ($v as $r)
                                            $rooms_type[] = "rooms_type = '" . (int)$r . "'";
                                        if (count($rooms_type) > 0) {
                                            $where[] = "(" . implode(' OR ', $rooms_type) . ")";
                                        }
                                    } else {
                                        $where[] = "rooms_type = '" . (int)$v . "'";
                                    }
                                    break;
                            }

                            if (isset($_GET['rooms_type_detail'])) {
                                $pagi_url[] = 'rooms_type_detail[]=' . implode(",", $_GET['rooms_type_detail']);
                            }

                            if (is_array($v) && count($v) > 0) {
                                foreach ($v as $ir) {
                                    $pagi_url[] = 'rooms_type[]=' . $ir;
                                }


                                //$pagi_url[] = 'rooms_type[]='.implode(",", $v);

                            } else {
                                $pagi_url[] = 'rooms_type=' . $v;
                            }
                            break;
                        case 'status':
                            if ($v == '1') {
                                $where[] = ' (status LIKE("%1%") OR status LIKE("%2%"))';
                            } else {
                                $where[] = ' status LIKE("%' . (int)$v . '%")';
                            }
                            $pagi_url[] = 'status=' . $v;
                            break;
                        case 'time':
                            $timeinterval = [];
                            foreach ($v as $item) {
                                switch ($item) {
                                    case '1':
                                        $timeinterval[] = ' created_at >= LAST_DAY(CURDATE()) - INTERVAL 1 MONTH ';
                                        break;
                                    case '2':
                                        $timeinterval[] = ' (created_at <= LAST_DAY(CURDATE()) - INTERVAL 1 MONTH '
                                            . 'AND created_at >= LAST_DAY(CURDATE()) - INTERVAL 6 MONTH) ';
                                        break;
                                    case '3':
                                        $timeinterval[] = ' (created_at <= LAST_DAY(CURDATE()) - INTERVAL 6 MONTH '
                                            . 'AND created_at >= LAST_DAY(CURDATE()) - INTERVAL 12 MONTH )';
                                        break;
                                    case '4':
                                        $timeinterval[] = ' created_at <= LAST_DAY(CURDATE()) - INTERVAL 12 MONTH ';
                                        break;
                                }
                                $pagi_url[] = 'time[]=' . $item;
                            }
                            $where[] = ' (' . implode('OR', $timeinterval) . ') ';
                            break;
                    }
                }
            }
        }
        //   if($_COOKIE['dev'] == 'sanja'){
        //     var_dump($pagi_url); die;
        //   }
        // status
        //if(!isset($_GET['status']) || $_GET['status'] == '' ) {
        //		if(!isset($_SESSION['mgrRole']) || $_SESSION['mgrRole'] != 1)
        //			$where[] = ' (status LIKE("%1%") OR status LIKE("%2%"))';
        //	}
        if (!isset($_GET['status']) || $_GET['status'] == '') {
            if (!isset($_SESSION["webUser"]) && !isset($_SESSION['mgrRole']) && $_SESSION['mgrRole'] != 1) {
                //$where[] = ' (status LIKE("%1%") OR status LIKE("%2%"))';
            }
        }

        // query & view
        $id    = $modx->documentObject['id'] != 159 ? $modx->documentObject['id'] : 5;

        if ($modx->documentObject['id'] == 6) {
            $flats = $class->getFlats($param, $where, $id, 'id DESC');
        } else {
            $flats = $class->getFlats($param, $where, $id);
        }

        foreach ($flats as $flat) {
            //  var_dump($flat);
			//echo $flat['status'];
            $flat['updated_at'] = date('d/m/Y', strtotime($flat['updated_at']));
            if ($flat['character'] != '') {
                $flat['character'] = json_decode($flat['character'], true);
            }
            if (count($flat['character']) > 0 && $flat['character'] != '') {
                $character = '';
                foreach ($flat['character'] as $c)
                    if ($c != '') {
                        $character .= $modx->parseChunk('tpl_character', ["c" => $c]);
                    }
                $flat['character'] = $character;
            }
            $flat['description_intro'] = $modx->db->escape($flat['description']);
            $flat['price']             = $class->getPrice($flat['price'], $modx->config['course']);
            $flat['old_price']         = $class->getPrice($flat['old_price'], $modx->config['course']);
            if ($flat['watermark'] == 1) {
                $flat['opt'] = ',fltr_wmi|/images/watermark-min.png|C|99|50|50';
            }
            switch ($flat['type']) {
                case '1':
                    $flat['type_name'] = '[#127#]';
                    break;
                case '2':
                    $flat['type_name'] = '[#267#]';
                    break;
                case '3':
                    $flat['type_name'] = '[#234#]';
                    break;
                case '4':
                    $flat['type_name'] = '[#91#]';
                    break;
                case '5':
                    $flat['type_name'] = '[#92#]';
                    break;
                case '6':
                    $flat['type_name'] = '[#233#]';
                    break;
            }
            $res .= $modx->parseChunk($tpl, $flat);
        }
        // if($_COOKIE['dev'] == 'sanja'){
        //     var_dump($pagi_url); die;
        // }
        // paginate
        $count = $class->getFoundRows();
        $modx->setPlaceholder('totalItems', $count);
        $modx->setPlaceholder('perPage', $limit);
        //$pagi_url[] = 'course=' . $_GET['course'];
        $pagi_url[] = 'page=';

        $pagi_url = count($pagi_url) > 0 ? "?" . implode('&', $pagi_url) : '';

        $pagin = $class->getPaginate($count, $modx->makeUrl($modx->documentObject['id']) . $pagi_url, $param['limit'], 'ditto_pagi_link', 'ditto_pagi_currlink', 'ditto_next', 'ditto_prev');
        if ($pagin != '') {
            $modx->setPlaceholder("pagin", $pagin);
        }

        if ($res == '') {
            $res = 'По данному запросу совпадений не найдено';
        }
        break;

    /**
     * Избранные объекта
     */
    case 'getFlatFavorites':
        $where           = [];
        $where[]         = 'active = 1';
	
		//исключаем скрытую продажу для неавторизованных
		if($modx->documentObject['admin_login']!='yes') {$where[] = ' status!=5 ';}
	
        $param['limit']  = ($limit != '') ? $limit : 3;
        $pageNum = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
        $param['offset'] = ($pageNum - 1) * $param['limit'];
        if (!empty($_COOKIE['favorites'])) {
            $f = json_decode($_COOKIE['favorites'], true);
            $favoriteIds = array();
            if (is_array($f)) {
                foreach ($f as $favoriteId) {
                    $favoriteId = (int)$favoriteId;
                    if ($favoriteId > 0) {
                        $favoriteIds[] = $favoriteId;
                    }
                }
            }
            if (count($favoriteIds) > 0) {
                $where[] = ' id IN (' . implode(',', $favoriteIds) . ')';

                // query & view
                $flats = $class->getFlats($param, $where, 5);
                foreach ($flats as $flat) {
                    $flat['updated_at'] = date('d/m/Y', strtotime($flat['updated_at']));
                    if ($flat['character'] != '') {
                        $flat['character'] = json_decode($flat['character'], true);
                    }
                    if (count($flat['character']) > 0 && $flat['character'] != '') {
                        $character = '';
                        foreach ($flat['character'] as $c)
                            if ($c != '') {
                                $character .= $modx->parseChunk('tpl_character', ["c" => $c]);
                            }
                        $flat['character'] = $character;
                    }
                    $flat['price']     = $class->getPrice($flat['price'], $modx->config['course']);
                    $flat['old_price'] = $class->getPrice($flat['old_price'], $modx->config['course']);
                    switch ($flat['type']) {
                          case '1':
                                $flat['type_name'] = '[#127#]';
                                break;
                            case '2':
                                $flat['type_name'] = '[#267#]';
                                break;
                            case '3':
                                $flat['type_name'] = '[#234#]';
                                break;
                            case '4':
                                $flat['type_name'] = '[#91#]';
                                break;
                            case '5':
                                $flat['type_name'] = '[#92#]';
                                break;
                            case '6':
                                $flat['type_name'] = '[#233#]';
                                break;
                    }
                    $res .= $modx->parseChunk($tpl, $flat);
                }

                // paginate
                $count      = $class->getFoundRows();
                $pagi_url[] = 'page=';
                $pagi_url   = count($pagi_url) > 0 ? "?" . implode('&', $pagi_url) : '';
                $pagin      = $class->getPaginate($count, $modx->makeUrl($modx->documentObject['id']) . $pagi_url, $param['limit'], 'ditto_pagi_link', 'ditto_pagi_currlink', 'ditto_next', 'ditto_prev');
                if ($pagin != '') {
                    $modx->setPlaceholder("pagin", $pagin);
                }
            } else {
                $res = "Вы ничего не добавили в избранное.";
            }
        } else {
            $res = "Вы ничего не добавили в избранное.";
        }
        break;

    /**
     * Вывод изображений для объекта
     */
case 'object_slider':
    $res = '';

    // --- 1) Берём все картинки квартиры, отсортированные как раньше ---
    $images_all = $modx->db->makeArray(
        $modx->db->query(
            'SELECT * FROM `modx_a_flat2image` WHERE flat_id = "' . (int)$id . '" ORDER BY sort ASC'
        )
    );

    $count_images = count($images_all);

    if ($count_images > 1) {
        // Логика как у тебя: если 2 фото — показываем 1; если >=3 — оставляем как есть
        if ($count_images < 3) {
            $count_images = $count_images - 1;
        }

        // Берём "хвост" для слайдера (как в исходнике: LIMIT 3,100)
        // Если нужно иначе — отрегулируй LIMIT.
        $images = $modx->db->makeArray(
            $modx->db->query(
                'SELECT * FROM `modx_a_flat2image` WHERE flat_id = "' . (int)$id . '" ORDER BY sort ASC LIMIT 3,100'
            )
        );

        // Если "хвоста" нет, но где-то хранится $_SESSION['count_images'] == 3 — уменьшаем на 1
        if (count($images) == 0 && !empty($_SESSION['count_images']) && (int)$_SESSION['count_images'] === 3) {
            $count_images = max(1, $count_images - 1);
        }

        // Нумерация слайдов
        $i = $count_images;

        // --- 2) Пресеты размеров и тип вотермарки по ширине устройства ---
        switch ($type_width) {
            case 'mobile':
                $size_full    = 'w=900,h=500';
                $size_preview = 'w=660,h=350';
                $water_type   = '-middle';
                break;
            case 'small':
                $size_full    = 'w=136,h=77';
                $size_preview = 'w=136,h=77';
                $water_type   = '-small';
                break;
            default:
                $size_full    = 'w=1920,h=1080';
                $size_preview = 'w=900,h=500';
                $water_type   = '';
                break;
        }

        // --- 3) Базовые опции генератора изображений ---
        // ВАЖНО: используем zc=1 (обрезка) => без белых полей.
        // Если нужно качество/формат — добавляем f=webp,q=85 (оставил как у тебя).
        $opt_base = ',f=webp,q=85,zc=1';

        // --- 4) Основной цикл генерации слайдов ---
        foreach ($images as $image) {
            // Пропускаем, если текущая картинка теоретически совпадает с превью документа
            if (!empty($image['image']) && $image['image'] != $modx->documentObject['preview']) {

                // Опции на слайд
                $image['opt']     = $opt_base;        // одинаково для всех типов
                $image['opt_big'] = $opt_base;        // для "большой" версии (если она используется в чанке)

                // Вотермарк (если включён)
                if (!empty($watermark) && (int)$watermark === 1) {
                    // Большой вариант — общий водяной знак
                    $image['opt_big'] .= ',fltr_wmi|/images/watermark.png|C|100|51|51';
                    // Обычный — зависящий от типа ширины
                    $image['opt']     .= ',fltr_wmi|/images/watermark' . $water_type . '.png|C|100|51|51';
                }

                // Добавляем превью (cover) вторым элементом — как у тебя: "должно идти вторым"
                if ($i == 4) {
                    $image_i_for_preview = $i++;
                    $res .= $modx->parseChunk($tpl, [
                        'flat_id'      => $image['flat_id'],
                        'opt_big'      => $image['opt_big'],
                        'opt'          => $image['opt'],
                        'image'        => $modx->documentObject['preview'],
                        'i'            => $image_i_for_preview,
                        'size_full'    => $size_full,
                        'size_preview' => $size_preview,
                    ]);
                }

                // Рендерим саму картинку
                $image['size_full']    = $size_full;
                $image['size_preview'] = $size_preview;
                $image['i']            = $i++;

                $res .= $modx->parseChunk($tpl, $image);
            }
        }

        // --- 5) Фолбэк: если по какой-то причине ничего не добавили в $res ---
        if ($res === '') {
            $opt     = $opt_base;
            $opt_big = $opt_base;

            if (!empty($watermark) && (int)$watermark === 1) {
                $opt_big .= ',fltr_wmi|/images/watermark.png|C|100|51|51';
                $opt     .= ',fltr_wmi|/images/watermark' . $water_type . '.png|C|100|51|51';
            }

            $res .= $modx->parseChunk($tpl, [
                'flat_id'      => $modx->documentObject['id'],
                'opt_big'      => $opt_big,
                'opt'          => $opt,
                'image'        => $modx->documentObject['preview'],
                'i'            => $i,
                'size_full'    => $size_full,
                'size_preview' => $size_preview,
            ]);
        }
    }
    break;


    /**
     * Вывод изображений для объекта по умолчанию
     */
    case 'object_slider_one':

        $images = $modx->db->makeArray($modx->db->query('select * from `modx_a_flat2image` where flat_id = "' . (int)$id . '" ORDER BY sort ASC'));
        //$images = $class->getImagesFlat($id);
        $i = 0;

        $_SESSION['count_images'] = count($images);

        switch ($type_width) {
            case 'mobile':
                $size_full    = 'w=900,h=500';
                $size_preview = 'w=660,h=350';
                $water_type   = '-middle';
                break;
            case 'small':
                $size_full    = 'w=136,h=77';
                $size_preview = 'w=136,h=77';
                $water_type   = '-small';
                break;
            default:
                $size_full    = 'w=1920,h=1080';
                $size_preview = 'w=900,h=500';
                $water_type   = '';
                break;
        }

        foreach ($images as $image) {
            $image['size_full']    = $size_full;
            $image['size_preview'] = $size_preview;
            //if ($image['image'] != $modx->documentObject['preview']) {
                $image['opt'] = $image['type'] == 0 ? ",zc=1" : ",f=webp,far=1,q=85,bg=ffffff";
                $image['opt_big'] = '';
                if ($watermark == 1) {
                    $image['opt_big'] = $image['opt'] . ',fltr_wmi|/images/watermark.png|C|100|51|51';
                    $image['opt']     .= ',fltr_wmi|/images/watermark' . $water_type . '.png|C|100|51|51';
                }
                // добавляем прьвью, которыое должно идти вторым для адекватного отображения
//                if ($i == 0) {
//                    $image['i'] = $i++;
//                    $res        .= $modx->parseChunk($tpl, ["flat_id" => $image['flat_id'], "opt_big" => $image['opt_big'], "opt" => $image['opt'], "image" => $modx->documentObject['preview'], "i" => $image['i'], 'size_full' => $size_full, 'size_preview' => $size_preview]);
//
//                }
                $image['i'] = $i++;
                $res        .= $modx->parseChunk($tpl, $image);
            //}
        }

		$modx->setPlaceholder('nextNumSlide',$i);
	
        if ($res == '') {
            $image['opt'] = $image['type'] == 1 ? ",zc=1" : ",f=webp,far=1,q=85,bg=ffffff";
            if ($watermark == 1) {
                $image['opt_big'] = $image['opt'] . ',fltr_wmi|/images/watermark.png|C|100|51|51';
                $image['opt']     .= ',fltr_wmi|/images/watermark' . $water_type . '.png|C|100|51|51';
            }
            $res .= $modx->parseChunk($tpl, ["flat_id" => $modx->documentObject['id'], "opt_big" => $image['opt_big'], "opt" => $image['opt'], "image" => $modx->documentObject['preview'], "i" => 0, 'size_full' => $size_full, 'size_preview' => $size_preview]);
        }
        break; 




    // 	case 'object_slider':
    // $images = $class->getImagesFlat($id);
    // $i = 0;
    // foreach ($images as $image) {
    // 	if($image['image'] != $modx->documentObject['preview']) {
    // 		$image['opt'] = $image['type'] == 0 ? ",zc=1" : ",f=png,far=1,bg=ffffff";
    // 		if($watermark == 1) {
    // 			$image['opt'] .= ',fltr_wmi|/images/watermark.png|C|100|50|50';
    // 		}
    // 		// добавляем прьвью, которыое должно идти вторым для адекватного отображения
    // 		if($i == 1) {
    // 			$image['i'] = $i++;
    // 			$res .= $modx->parseChunk($tpl, array("flat_id" => $image['flat_id'], "opt" => $image['opt'], "image" => $modx->documentObject['preview'], "i" => $image['i']));
    // 		}
    // 		$image['i'] = $i++;
    // 		$res .= $modx->parseChunk($tpl, $image);
    // 	}
    // }
    // if($_COOKIE['dev'] == "sanja") {
    // 	var_dump($image);die;
    // }
    // if($res == '') {
    // 	$res .= $modx->parseChunk($tpl, array("flat_id" => $modx->documentObject['id'], "opt" => ',fltr_wmi|/images/watermark.png|C|100|50|50', "image" => $modx->documentObject['preview'], "i" => 0));
    // }
    // break;

    /**
     * Вывод свойств объекта
     */
    case 'properties':
        if (count($modx->documentObject['properties']) > 0) {
            foreach ($modx->documentObject['properties'] as $p)
                if (!empty($p)) {
                    $res .= $modx->parseChunk($tpl, ["title" => $p]);
                }
        }
        break;

    /**
     * Агент на странице объекта
     */
    case 'agent':
        $agent = $class->getAgents($id);
        if ($agent) {
            $res .= $modx->parseChunk($tpl, $agent[0]);
        }
        break;

    /**
     * Вывод похожих товаров
     */
    case 'friend_items':
        $param['offset'] = 0;
        $param['limit']  = 5;
        $where[]         = " id != '" . $modx->documentObject['id'] . "'";
	
		//исключаем скрытую продажу для неавторизованных
		if($modx->documentObject['admin_login']!='yes') {$where[] = ' status!=5 ';}	
	
        if ($excludeStatus != '') {
            $where[] = " status NOT IN(" . $excludeStatus . ")";
        }
        if ($modx->documentObject['type'] == 1 || $modx->documentObject['type'] == 2 || $modx->documentObject['type'] == 4) {
            $where[] = "rooms = " . $modx->documentObject['rooms'];
            $flats   = $class->getFlats($param, $where, $modx->documentObject['cat']);
            // если категорий пустая выводим объекты из категории
            if (count($flats) == 0) {
                switch ($modx->documentObject['type']) {
                    case '1':
                        $page = $class->getPage($modx->documentObject['cat']);
                        $cat  = $page['parent'];
                        break;
                    case '2':
                        $page = $class->getPage($modx->documentObject['cat']);
                        $cat  = $page['parent'];
                        break;
                    case '4':
                        $cat = 10;
                        break;
                }
                $flats = $class->getFlats($param, $where, $cat);
            }
        } else {
            $flats = $class->getFlats($param, $where, $modx->documentObject['cat']);
        }

        if ($flats) {
            foreach ($flats as $flat) {
                $flat['type_name'] = '';
                switch ($flat['type']) {
                     case '1':
                    $flat['type_name'] = '[#127#]';
                    break;
                case '2':
                    $flat['type_name'] = '[#267#]';
                    break;
                case '3':
                    $flat['type_name'] = '[#234#]';
                    break;
                case '4':
                    $flat['type_name'] = '[#91#]';
                    break;
                case '5':
                    $flat['type_name'] = '[#92#]';
                    break;
                case '6':
                    $flat['type_name'] = '[#93#]';
                    break;
                }
                $flat['updated_at'] = date('d/m/Y', strtotime($flat['updated_at']));
                if ($flat['character'] != '') {
                    $flat['character'] = json_decode($flat['character'], true);
                }
                if (count($flat['character']) > 0 && $flat['character'] != '') {
                    $character = '';
                    foreach ($flat['character'] as $c)
                        if ($c != '') {
                            $character .= $modx->parseChunk('tpl_character', ["c" => $c]);
                        }
                    $flat['character'] = $character;
                }
                $flat['price']     = $class->getPrice($flat['price'], $modx->config['course']);
                $flat['old_price'] = $class->getPrice($flat['old_price'], $modx->config['course']);
                if ($flat['watermark'] == 1) {
                    $flat['opt'] = ',fltr_wmi|/images/watermark-min.png|C|99|50|50';
                }
                $res .= $modx->parseChunk($tpl, $flat);
            }
        }
        break;

    /**
     * Список дочерних категорий для блока поиска
     */
    case 'getChildOptions':
        $cats = $class->getListLot($parent, $ignore);
        //print_r($cats); die;
        // $curr = explode(",", $curr);
        foreach ($cats as $key => $value) {
            if ($value['parent'] != $parent) {
                $value['pagetitle'] = $value['parent_pagetitle'] . " | " . $value['pagetitle'];
            }
            // $value['selected'] = in_array($value['id'], $curr) ? 'selected' : '';
            $value['selected'] = '';
            $res               .= $modx->parseChunk($tpl, $value);
        }

        break;

    /**
     * Объект который нужно редактировать, псоледний просмотренный
     */
    case 'lastObject':
        $res = $_SESSION['edit_object'];
        break;

    /**
     * Урл для объект
     */
    case 'objectUrl':
        $flat = $class->getFlat($id);
        $res  = $flat['url'];
        break;

    /**
     * Вылюта в поиске
     */
    case 'currency_search':
        switch ($_GET['course']) {
            case '2':
                $res = $modx->config['currency_uah'];
                break;
            case '3':
                $res = $modx->config['currency_euro'];
                break;
            default:
                $res = $modx->config['currency_usd'];
                break;
        }
        break;

    /**
     * Level to flat
     */
    case 'floor':
        if ($modx->documentObject['appartament'] != '') {
            $appartament = $class->getAppartament();
            foreach ($modx->documentObject['appartament'] as $k => $a) {
                $item = '';
                switch ($k) {
                    case '0':
                        $title = '[#268#]';
                        break;
                    case '1':
                        $title = '<span>1</span> [#269#]';
                        break;
                    case '2':
                        $title = '<span>2</span> [#269#]';
                        break;
                    case '3':
                        $title = '<span>3</span> [#269#]';
                        break;
                }
                foreach ($a as $id => $count) {
                    $name = '';
                    if ($count > 0) {
                        foreach ($appartament as $list_app)
                            if ($list_app['id'] == $id) {
                                $name  = $list_app['pagetitle'];
                                $image = $list_app['tv_preview'];
                            }
                        $item .= $modx->parseChunk($tpl_item, ['count' => $count, "name" => $name, "tv_preview" => $image]);
                    }
                }
                if ($item != '') {
                    $res .= $modx->parseChunk($tpl, ["title" => $title, "item" => $item]);
                }
            }
        } else {
            $modx->regClientScript("<script>$(\"[href='#floor']\").hide();</script>");
        }
        break;

    /**
     * Сео линковка страниц паггинации в head
     */
    case 'head_link_paginate':
        // новости
        if ($modx->documentObject['template'] == 3) {
            $currpage = $modx->getPlaceholder('currentPage');
            $total    = $modx->getPlaceholder('totalPages');
            $limit    = $modx->getPlaceholder('perPage');
            $urlStart = $modx->getPlaceholder('urlStart');
            if ($currpage == 1 && $total > 1) {
                $res = $modx->parseChunk("head_link", ["type" => "next", "url" => $modx->makeUrl($modx->documentObject['id']) . "?start=" . $limit]);
            } elseif ($currpage == $total && $total != 1) {
                $res = $modx->parseChunk("head_link", ["type" => "prev", "url" => $modx->makeUrl($modx->documentObject['id']) . "?start=" . ($urlStart - $limit)]);
            } elseif ($currpage > 1 && $currpage != $total && $total > 2) {
                $res = $modx->parseChunk("head_link", ["type" => "next", "url" => $modx->makeUrl($modx->documentObject['id']) . "?start=" . ($urlStart + $limit)]);
                $res .= $modx->parseChunk("head_link", ["type" => "prev", "url" => $modx->makeUrl($modx->documentObject['id']) . "?start=" . ($urlStart - $limit)]);
            }
        }
        // каталог
        if ($modx->documentObject['template'] == 9) {
            $currpage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
            $count    = $modx->getPlaceholder('totalItems');
            $limit    = $modx->getPlaceholder('perPage');
            $total    = ceil($count / $limit);
            if ($currpage == 1 && $total > 1) {
                $res = $modx->parseChunk("head_link", ["type" => "next", "url" => $modx->makeUrl($modx->documentObject['id']) . "?page=2"]);
            } elseif ($currpage == $total && $total != 1) {
                $res = $modx->parseChunk("head_link", ["type" => "prev", "url" => $modx->makeUrl($modx->documentObject['id']) . "?page=" . ($currpage - 1)]);
            } elseif ($currpage > 1 && $currpage != $total && $total > 2) {
                $res = $modx->parseChunk("head_link", ["type" => "prev", "url" => $modx->makeUrl($modx->documentObject['id']) . ($currpage != 2 ? "?page=" . ($currpage - 1) : '')]);
                $res .= $modx->parseChunk("head_link", ["type" => "next", "url" => $modx->makeUrl($modx->documentObject['id']) . "?page=" . ($currpage + 1)]);
            }
        }

        //$res = $modx->parseDocumentSource($res);

        break;

    /**
     * Расылка новости
     */
    case 'send_article':
        if (isset($_SESSION['mgrRole']) && $modx->documentObject['id'] != 530) {
            $send = strtotime($modx->documentObject['send_time']) > 0 ? 1 : 0;
            $res  = $modx->parseChunk($tpl, ["send" => $send]);
        }
        break;



    case 'param':
        if(isset($value) && $value != ''){
            $res = $modx->getConfig($value);
        }
        break;
    /**
     * Текущий год
     * @var get = setCurrentYear
     */
    case "setCurrentYear":
        $res = date("Y");
        break;


     case "PriceHistory":
    /**
     * Snippet: Price history block (chunks)
     *
     * Параметры сниппета:
     * &tpl=`tplPriceHistoryWrap`
     * &tplRow=`tplPriceHistoryRow`
     * &title=`Історія змін ціни`
     * &flatId=`123`  (необязательно, по умолчанию $_GET['i'])
     */

    $tpl    = isset($tpl) ? $tpl : 'tplPriceHistoryWrap';
    $tplRow = isset($tplRow) ? $tplRow : 'tplPriceHistoryRow';
    $title  = isset($title) ? $title : 'Історія змін ціни';

    if (isset($flatId) && (int)$flatId > 0) {
        $flatId = (int)$flatId;
    } 
    if ($flatId <= 0) return '';
 
    if( $modx->documentObject['admin_login'] == 'yes' || $modx->documentObject["show_price_history"] == 1){
    // получить историю
        $rows = $class->getFlatPriceHistory($flatId);

        if (!is_array($rows) || count($rows) === 0) return '';

        // сортировка: новые сверху по start_date, fallback по sort_order/id
        usort($rows, function($a, $b){
            $da = !empty($a['start_date']) ? strtotime($a['start_date']) : 0;
            $db = !empty($b['start_date']) ? strtotime($b['start_date']) : 0;

            if ($da === $db) {
                $sa = (int)($a['sort_order'] ?? 0);
                $sb = (int)($b['sort_order'] ?? 0);
                if ($sa === $sb) {
                    $ia = (int)($a['id'] ?? 0);
                    $ib = (int)($b['id'] ?? 0);
                    return $ib <=> $ia;
                }
                return $sb <=> $sa;
            }
            return $db <=> $da;
        });

        $fmtDate = function($d){
            if (!$d) return '';
            $ts = strtotime($d);
            return $ts ? date('d.m.Y', $ts) : (string)$d;
        };
        $fmtMoney = function($n){
            return number_format((float)$n, 0, '.', ' ');
        };

        $outRows = '';
        $count = count($rows);

        for ($i = 0; $i < $count; $i++) {

            $curPrice = (float)($rows[$i]['price'] ?? 0);
            $curDate  = (string)($rows[$i]['start_date'] ?? '');

            $deltaText  = '';
            $deltaClass = '';

            if (isset($rows[$i + 1])) {
                $nextPrice = (float)($rows[$i + 1]['price'] ?? 0);
                $delta = $curPrice - $nextPrice;

                if (abs($delta) > 0.00001) {
                    if ($delta > 0) {
                        $deltaClass = 'up';
                        $deltaText  = '+ ' . $fmtMoney(abs($delta)) . ' $ ▲';
                    } else {
                        $deltaClass = 'down';
                        $deltaText  = '- ' . $fmtMoney(abs($delta)) . ' $ ▼';
                    }
                }
            }

            $outRows .= $modx->parseChunk($tplRow, [
                'date'        => htmlspecialchars($fmtDate($curDate), ENT_QUOTES, 'UTF-8'),
                'delta'       => htmlspecialchars($deltaText, ENT_QUOTES, 'UTF-8'),
                'delta_class' => $deltaClass,
                'price'       => htmlspecialchars($fmtMoney($curPrice), ENT_QUOTES, 'UTF-8'),
            ]);
        }
    //var_dump($outRows);
        $res =  $modx->parseChunk($tpl, [
            'title' => $title,
            'rows'  => $outRows
        ]);
    }
     break;
}
return $res;
