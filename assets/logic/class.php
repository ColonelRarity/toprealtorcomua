<?php
if (!class_exists('Corp')) {
    /**
     * Class
     */
    class Corp
    {
        var $modx = null;
        var $db = null;
        var $lang = null;

        function __construct($modx)
        {

            $this->modx = $modx;
            $this->db = $modx->db;
            $this->lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ru' ;
        }

        /*************************************/
        /*** ----------  CORE   ---------- ***/
        /*************************************/

        function number($var)
        {
            return preg_replace('/\D+/i', '', $var);
        }

        function dd($param)
        {
            echo "<pre>";
            var_dump($param);
            echo "</pre>";
            die;
        }

        function gen_pass($max = 12)
        {
            $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
            $size = StrLen($chars) - 1;
            $pass = null;
            while ($max--) {
                $pass .= $chars[rand(0, $size)];
            }
            return $pass;
        }

        /**
         * Кол-во строк без лимитов
         */
        public function getFoundRows()
        {
            return $this->db->getValue($this->db->query("SELECT FOUND_ROWS() as value"));
        }

        /**
         * Пагинация
         *
         * @param  [int] $count Кол-во всего эл-тов
         * @param  [string] $url   урл для пагинации
         *
         * @return [html]        верстка пагинации
         */
        public function getPaginate($count, $url, $limit = '', $tpl_enable = '', $tpl_disable = '', $tpl_next, $tpl_prev)
        {
            $res = '';

            $limit = $limit != '' ? $limit : 10;
            $pages['all'] = ceil($count / $limit);
            $prev = '&laquo;';
            $next = '&raquo;';
            $classPrev = 'prev';
            $classNext = 'next';
            $classPage = 'page';
            $classCurrent = 'active';
            $classSeparator = 'separator';
            $separator = '...';
            $tpl_enable = $tpl_enable != '' ? $tpl_enable : "enable.tpl";
            $tpl_disable = $tpl_disable != '' ? $tpl_disable : "disable.tpl";
            $tpl_next = $tpl_next != '' ? $tpl_next : 'enable.tpl';
            $tpl_prev = $tpl_prev != '' ? $tpl_prev : 'enable.tpl';
            $url = $url != '' ? $url : '';

            if ($pages['all'] > 1) {
                $limit_pagination = 3;
                $curent = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
                $start = $curent - $limit_pagination;
                $end = $curent + $limit_pagination;
                $start = $start < 1 ? 1 : $start;
                $end = $end > $pages['all'] ? $pages['all'] : $end;

                if ($curent > 1) {
                    $res = $this->modx->parseChunk($tpl_prev, ['url' => $url . ($curent - 1), 'class' => $classPrev, 'page' => $prev], "[+", "+]");
                }

                // if($curent > $limit_pagination+1){
                //     $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.'1', 'class' => $classPage, 'page' => '1')).$this->modx->parseChunk($tpl_enable, array('url'=> $url, 'class' => $classSeparator, 'title' => $separator));
                // }

                for ($i = $start; $i < $end + 1; $i++) {
                    if ($i == $curent) {
                        $res .= $this->modx->parseChunk($tpl_disable, ['class' => $classCurrent, 'page' => $i], "[+", "+]");
                    } else {
                        $res .= $this->modx->parseChunk($tpl_enable, ['url' => $url . $i, 'class' => $classPage, 'page' => $i], "[+", "+]");
                    }

                }
                // if($curent < ($pages['all'] - $limit_pagination))
                //     $res .= $this->modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'page' => $separator)).$this->modx->parseChunk($tpl_enable , array('url' => $url.$pages['all'], 'class' => $classPage, 'page' => $pages['all']));
                if ($curent != $pages['all']) {
                    $res .= $this->modx->parseChunk($tpl_next, ['url' => $url . ($curent + 1), 'class' => $classNext, 'page' => $next], "[+", "+]");
                }
            }
            return $res;
        }

        /**
         * Send email
         *
         * @param  [email] $to           Кому отсылать письма
         * @param  [string] $subject   Тема письма
         * @param  [string] $content   Тело пиьсма
         * @param  [string] $file_name Название файла
         * @param  [string] $file_tmp  Путь к файлу
         */
        function sendmail($to, $subject, $content, $file_name = null, $file_tmp = null)
        {
            if (!class_exists('PHPMailer')) {
                require MODX_BASE_PATH . "manager/includes/controls/phpmailer/class.phpmailer.php";
            }
            $body = $this->modx->rewriteUrls($this->modx->parseDocumentSource($this->modx->parseChunk("mail_wrap", ["content" => $content])));
            $Password = $this->modx->config['smtppw'];
            $Password = substr($Password, 0, -7);
            $Password = str_replace('%', '=', $Password);
            $Password = base64_decode($Password);

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            //$mail->SMTPSecure = "tls";
            $mail->Host = $this->modx->config['smtp_host'];
            $mail->Port = $this->modx->config['smtp_port'];
            $mail->Username = $this->modx->config['smtp_username'];
            $mail->Password = $Password;
            $mail->Subject = $subject;
            $mail->SetFrom($this->modx->config['smtp_username'], $this->modx->config['site_name']);

            if ($file_tmp != '') {
                $mail->addAttachment($file_tmp, $file_name);
            }
            // $mail->SMTPDebug = 2;
            // $mail->Debugoutput = function ($str, $level) {
            //     file_put_contents(
            //         MODX_BASE_PATH . 'assets/logic/smtp.log',
            //         date('Y-m-d H:i:s') . " [$level] $str\n",
            //         FILE_APPEND
            //     );
            // };
            //echo $Password.'/';
            $mail->AddAddress($to);
            $mail->MsgHTML($body);
            $mail->Send();
        }

        /**
         * Clear directory
         *
         * @param  [str] $dir directory
         */
        function clear_dir($dir)
        {
            $list = $this->myscandir($dir);
            foreach ($list as $file) {
                if (is_dir($dir . $file)) {
                    $this->clear_dir($dir . $file . '/');
                    rmdir($dir . $file);
                } else {
                    unlink($dir . $file);
                }
            }
            rmdir($dir);
        }

        /**
         * Массовое обновление обектов (актуализация)
         *
         * @param [array] параментры для выборки
         */
        public function idsForUpdate($ids)
        {
            if ($ids === 0 || $ids === '0') {
                return 'ALL';
            }
            if (is_numeric($ids)) {
                $id = (int)$ids;
                return $id > 0 ? (string)$id : false;
            }
            if (is_string($ids)) {
                $ids = explode(',', $ids);
            }
            if (!is_array($ids)) {
                return false;
            }
            $clean = array();
            foreach ($ids as $id) {
                $id = (int)$id;
                if ($id > 0) {
                    $clean[] = $id;
                }
            }
            if (!$clean) {
                return false;
            }
            return implode(',', $clean);
        }

        public function updateFlatsStatus($ids = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            if ($idsSql === 'ALL') {
                return $this->db->query("
                          UPDATE `modx_a_flat`
                          SET updated_at = NOW()");
            }
            return $this->db->query("
                      UPDATE `modx_a_flat`
                      SET updated_at = NOW() 
                      WHERE id IN (" . $idsSql . ")");
        }

        /**
         * Scan directory
         *
         * @param  [str] $dir directory
         */
        function myscandir($dir)
        {
            $list = scandir($dir);
            unset($list[0], $list[1]);
            return array_values($list);
        }


        /*************************************/
        /*** ----------  FLATS  ---------- ***/
        /*************************************/

        /**
         * Выводим 1 объеект
         * @id [int] id объекта
         */
        // function getFlat($id, $url = null)
        // {
        //     $id = (int)$id;
        //     $sql
        //         = "SELECT *,
        //  (SELECT pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
        //     (SELECT count(*) as user_rating FROM `modx_a_rating` WHERE page_id = " . $this->db->escape($id) . " AND user_ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "') ) as 'user_rating'
        //  FROM `modx_a_flat` f
        //  WHERE id = '" . $this->db->escape($id) . "'" . ($url != '' ? " OR url = '" . $this->db->escape($url) . "'" : "");

        //     $flat = $this->db->getRow($this->db->query($sql));
        //     if ($flat) {

        //         // категории
        //         $sql = "SELECT *, (select pagetitle_{$this->lang} AS pagetitle from `modx_site_content` where id = cat_id)  AS pagetitle FROM `modx_a_flat2cat` WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";

        //         $categories = $this->db->query($sql);
        //         while ($c = $this->db->getRow($categories)) {
        //             $flat['categories'][$c['cat_id']] = $c['pagetitle_{$this->lang} AS pagetitle'];
        //         }

        //         // св-ва
        //         $sql = "SELECT *, (select pagetitle_{$this->lang} AS pagetitle from `modx_site_content` where id = properties_id)  AS pagetitle FROM `modx_a_flat2properties` WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";
        //         $properties = $this->db->query($sql);
        //         while ($c = $this->db->getRow($properties)) {
        //             $flat['properties'][$c['properties_id']] = $c['pagetitle_{$this->lang} AS pagetitle'];
        //         }

        //         //хар-ки
        //         if ($flat['character'] != '') {
        //             $flat['character'] = json_decode($flat['character'], true);
        //         }

        //         //Аппартаменты
        //         if ($flat['appartament'] != '') {
        //             $flat['appartament'] = json_decode($flat['appartament'], true);
        //         }
        //     }
        //     return $flat;
        // }

        public function getFlat($id, $url = null)
        {

            $id  = (int)$id;
            $loc = $this->lang; // используется в имени столбца pagetitle_<lang>

            // WHERE по id или по url
            $where = "f.id = '" . $this->db->escape($id) . "'";
            if (!empty($url)) {
                $where = "(" . $where . " OR f.url = '" . $this->db->escape($url) . "')";
            }

            // Явно перечисляем поля из старой таблицы, исключая те, что вынесены в *_flat_strings
            $sql = "
                SELECT
                    f.id, f.url, f.price, f.old_price, f.created_at, f.updated_at, f.code, f.active_now, f.preview,
                    f.area, f.h_area, f.beds, f.bath, f.status, f.status_vhn, f.rooms_type_vhn, f.active,
                    f.agent, f.video, f.type, f.rating, f.rooms, f.rooms_type, f.district, f.views,
                    f.cottage_view, f.flatInHouse_view, f.firend_items, f.coord,
                    f.panorama, f.panorama_gallery, f.panorama_image, f.panorama_360,
                    f.watermark, f.appartament,
                    f.owner_name, f.owner_phone, f.owner_comment,
                    f.cache, f.link_developer,
                    f.xml_active, f.exel_active, f.print_active,
                    f.xml_active_vhn, f.xml_active_lun, f.xml_active_plektan,
                    f.advantage_for_client,
                    f.floor, f.floors_total, f.plectan_district, f.plectan_city, f.plectan_area, f.created_at, f.updated_at, f.show_price_history,

                    /* ---- поля из локализованной таблицы ---- */
                    s.name                           AS name,
                    s.addr                           AS addr,
                    s.description                    AS description,
                    s.consumption                    AS consumption,
                    s.taxation                       AS taxation,
                    s.`character`                    AS `character`,
                    s.country                        AS country,
                    s.region                         AS region,
                    s.locality_name                  AS locality_name,
                    s.non_admin_sub_locality         AS non_admin_sub_locality,
                    s.metro                          AS metro,
                    s.microdistrict_xml              AS microdistrict_xml,
                    s.street_xml                     AS street_xml,
                    s.house_number_xml               AS house_number_xml,
                    /* SEO из новой таблицы, но под старыми ключами */
                    s.product_seo_title              AS tv_seo_title,
                    s.product_seo_keywords           AS tv_seo_keywords,
                    s.product_seo_description        AS tv_seo_description,
                    s.product_seo_robots             AS tv_seo_robots,
                    s.product_seo_canonical          AS tv_seo_canonical,

                    /* cat_name и user_rating — как были */
                    (SELECT pagetitle_{$loc} AS pagetitle
                       FROM `modx_site_content`
                      WHERE id = (SELECT cat_id
                                    FROM `modx_a_flat2cat`
                                   WHERE flat_id = f.id AND cat_id != 6
                                   LIMIT 1)
                    ) AS cat_name,

                    (SELECT COUNT(*)
                       FROM `modx_a_rating`
                      WHERE page_id = " . $this->db->escape($id) . "
                        AND user_ip = INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')
                    ) AS user_rating

                FROM `modx_a_flat` f
                LEFT JOIN `".$this->db->config['table_prefix']."a_flat_strings` s
                       ON s.product_id = f.id AND s.string_locate = '" . $this->db->escape($this->lang) . "'
                WHERE {$where}
                LIMIT 1
            ";

            $q = $this->db->query($sql);
            $flat = $q ? $this->db->getRow($q) : array();

            if (!empty($flat)) {

                // Категории
                $sql = "SELECT *,
                           (SELECT pagetitle_{$loc} AS pagetitle
                              FROM `modx_site_content`
                             WHERE id = cat_id) AS pagetitle
                        FROM `modx_a_flat2cat`
                        WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";
                $categories = $this->db->query($sql);
                while ($c = $this->db->getRow($categories)) {
                    $flat['categories'][$c['cat_id']] = $c['pagetitle'];
                }

                // Свойства
                $sql = "SELECT *,
                           (SELECT pagetitle_{$loc} AS pagetitle
                              FROM `modx_site_content`
                             WHERE id = properties_id) AS pagetitle
                        FROM `modx_a_flat2properties`
                        WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";
                $properties = $this->db->query($sql);
                while ($c = $this->db->getRow($properties)) {
                    $flat['properties'][$c['properties_id']] = $c['pagetitle'];
                }

                // JSON-поля
                if (!empty($flat['character'])) {
                    $tmp = json_decode($flat['character'], true);
                    if (is_array($tmp)) $flat['character'] = $tmp;
                }
                if (!empty($flat['appartament'])) {
                    $tmp = json_decode($flat['appartament'], true);
                    if (is_array($tmp)) $flat['appartament'] = $tmp;
                }
            }

            return $flat;
        }



        public function getFlatString($product_id, $lang = 'ru')
        {
            return $this->db->getRow($this->db->query("SELECT * FROM `modx_a_flat_strings` WHERE product_id = " . $product_id . " AND string_locate = '" . $this->lang . "'"));
        }



        public function getFlatStringAll($product_id)
        {

            $rows =  $this->db->makeArray($this->db->query("SELECT * FROM `modx_a_flat_strings` WHERE product_id = " . $product_id));


            $out = array();
            foreach ($rows as $row) {
                if (!isset($row['string_locate'])) continue;
                $loc = strtolower($row['string_locate']); // 'ru', 'ua', ...

                if ($row['character'] != '') {
                    $row['character'] = json_decode($row['character'], true);
                }

                $out[$loc] = $row; // если может быть несколько записей на один язык — сделайте $out[$loc][] = $row;
            }
            return $out;
        }


        function getFlatInfo($id, $url = null)
        {
            $sql
                = "SELECT *,
      (SELECT pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
            (SELECT count(*) as user_rating FROM `modx_a_rating` WHERE page_id = " . $this->db->escape($id) . " AND user_ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "') ) as 'user_rating'
      FROM `modx_a_flat` f
      WHERE id = '" . $this->db->escape($id) . "'" . ($url != '' ? " OR url = '" . $this->db->escape($url) . "'" : "");

            $flat = $this->db->getRow($this->db->query($sql));
            if ($flat) {

                // категории
                $sql = "SELECT *, (select pagetitle_{$this->lang} AS pagetitle from `modx_site_content` where id = cat_id) AS pagetitle FROM `modx_a_flat2cat` WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";
                $categories = $this->db->query($sql);
                while ($c = $this->db->getRow($categories)) {
                    $flat['categories'][$c['cat_id']] = $c['pagetitle'];
                }

                // св-ва
                $sql = "SELECT *, (select pagetitle_{$this->lang} AS pagetitle from `modx_site_content` where id = properties_id) AS pagetitle FROM `modx_a_flat2properties` WHERE flat_id = '" . $this->db->escape($flat['id']) . "'";
                $properties = $this->db->query($sql);
                while ($c = $this->db->getRow($properties)) {
                    $flat['properties'][$c['properties_id']] = $c['pagetitle'];
                }

                //хар-ки
                if ($flat['character'] != '') {
                    $flat['character'] = json_decode($flat['character'], true);
                }

                //Аппартаменты
                if ($flat['appartament'] != '') {
                    $flat['appartament'] = json_decode($flat['appartament'], true);
                }
            }

            return $flat;
        }


        /**
         * Выводим список обектов
         *
         * @param [array] параментры для выборки
         */
        //public function getFlats($param, $where = [], $id = 5, $order = 'points DESC, ABS(status) ASC, id DESC')
        //  public function getFlats($param, $where = [], $id = 5, $order = 'points DESC,field(ABS(status), 1, 2, 5, 3, 4), id DESC')
            
        // {
        //     $categories = [];
        //     $ids = explode(',', $id);
        //     if (!is_array($ids)) {
        //         $ids = $id;
        //     }
        //     foreach ($ids as $value) {
        //         $categories = array_merge($categories, array_values($this->modx->getChildIds($value)));
        //         $categories[] = $value;
        //     }

        //     $categories = implode(",", $categories);
        //     $categories = trim($categories,",");
        //     //print_r($categories); die;
        //     $param_where = (isset($param['AND']) && $param['AND'] != '') ? 'AND cat_id IN(' . $param['AND'] . ')' : '';


        //     if ($categories == '') {
        //         $categories = 0;
        //     }

        //     $points = $param['points'] ? $param['points'] : '';

        //     $sql = "
        //     SELECT SQL_CALC_FOUND_ROWS *,
        //         {$points} '0' as points,
        //      (select count(*) from `modx_a_flat2cat` where cat_id in (" . $categories . ") and flat_id = f.id) AS 'cnt',
        //      (SELECT pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
        //      (SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 20 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_object_type',
        //         (SELECT id FROM  `modx_site_content` WHERE id=(select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_id'
        //  FROM `modx_a_flat` f
        //      " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
        //  HAVING cnt > 0
        //  " . $param_where . "
        //  " . $param_where_pagin . "
        //  " . $param_where_pagin_three . "
        //  ORDER BY " . $this->db->escape($order) . " 
        //     LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);

        //  //print_r($sql); die;

        //     if (isset($_GET['sort_two_pagin']) && $_GET['sort_two_pagin'] != '') {
        //         $this->modx->setPlaceholder('cats_two', $_GET['sort_two_pagin']);
        //     } else if (isset($_GET['sort_three_pagin']) && isset($_GET['sort_three_pagin']) != '') {
        //         $this->modx->setPlaceholder('cats_three', $_GET['sort_three_pagin']);
        //     } else if (!empty($_GET['sort_one']) && !empty($_GET['sort_two'])) {
        //         $this->modx->setPlaceholder('cats_three', $param['AND']);
        //     }

     

        //     return $this->db->makeArray($this->db->query($sql));
        // }


        // public function getFlats($param, $where = array(), $id = 5, $order = 'points DESC, FIELD(ABS(status),1,2,5,3,4), id DESC')
        // {
        //     $modx = $this->modx;
        //     $db   = $this->db; // в Evo это DBAPI (mysql)

        //     // ---- 1) Категории: корневые + все дочерние ----
        //     $rootIds = is_array($id) ? $id : explode(',', (string)$id);
        //     $rootIds = array_filter(array_map('intval', $rootIds), function($v){ return $v > 0; });

        //     $allCats = array();
        //     foreach ($rootIds as $rid) {
        //         // getChildIds возвращает массив ID детей
        //         $allCats = array_merge($allCats, array_values($modx->getChildIds($rid)));
        //         $allCats[] = $rid;
        //     }
        //     $allCats = array_values(array_unique(array_map('intval', $allCats)));
        //     if (empty($allCats)) $allCats = array(0); // чтобы IN(...) не был пустым

        //     // Доп. фильтр категорий из $param['AND'] (как у тебя было)
        //     $andCats = array();
        //     if (!empty($param['AND'])) {
        //         $andCats = array_filter(array_map('intval', explode(',', (string)$param['AND'])), function($v){ return $v > 0; });
        //     }

        //     // ---- 2) Пагинация / points ----
        //     $limit  = isset($param['limit'])  ? max(0, (int)$param['limit'])  : 20;
        //     $offset = isset($param['offset']) ? max(0, (int)$param['offset']) : 0;
        //     $points = isset($param['points']) ? (float)$param['points']       : 0.0;

        //     // ---- 3) WHERE из массива $where (как и раньше) ----
        //     // В Evo обычно сюда приходят уже готовые куски выражений, поэтому просто склеиваем через AND.
        //     $whereSql = '';
        //     if (is_array($where) && count($where) > 0) {
        //         $whereSql = ' WHERE ' . implode(' AND ', $where);
        //     }

        //     // ---- 4) HAVING (как у тебя): cnt > 0 и, при необходимости, фильтр по cat_id из AND ----
        //     $having = ' HAVING cnt > 0';
        //     if (!empty($andCats)) {
        //         $having .= ' AND cat_id IN (' . implode(',', $andCats) . ')';
        //     }

        //     // ---- 5) Доп. хвосты (если ты их где-то выставляешь) ----
        //     // Раньше были $param_where_pagin / $param_where_pagin_three — дадим возможность передать их через $param.
        //     $extraHaving = '';
        //     if (!empty($param['extra_having'])) {
        //         // ОЖИДАЕТСЯ безопасная строка формата "AND ...", как раньше.
        //         $extraHaving .= ' ' . $param['extra_having'];
        //     }

        //     // ---- 6) ORDER BY — легкая валидация ----
        //     // Разрешим только символы, обычно встречающиеся в ORDER BY.
        //     $orderDefault = 'points DESC, FIELD(ABS(status),1,2,5,3,4), id DESC';
        //     if (!is_string($order) || !preg_match('~^[a-z0-9_(),.\s]+$~i', $order)) {
        //         $order = $orderDefault;
        //     }

        //     // ---- 7) Язык для pagetitle_* ----
        //     $lang = isset($this->lang) && $this->lang ? preg_replace('~[^a-z0-9_]~i', '', $this->lang) : 'ua';

        //     // ---- 8) Сборка SQL ----
        //     // Оставляем твою структуру: SQL_CALC_FOUND_ROWS + HAVING cnt>0 + подзапросы к первой категории != 6
        //     $sql = "
        //         SELECT SQL_CALC_FOUND_ROWS
        //             f.*,
        //             " . $points . " AS points,
        //             (
        //                 SELECT COUNT(*) 
        //                 FROM `modx_a_flat2cat` 
        //                 WHERE cat_id IN (" . implode(',', $allCats) . ") 
        //                   AND flat_id = f.id
        //             ) AS cnt,
        //             (
        //                 SELECT pagetitle_{$lang}
        //                 FROM `modx_site_content`
        //                 WHERE id = (
        //                     SELECT cat_id 
        //                     FROM `modx_a_flat2cat`
        //                     WHERE flat_id = f.id AND cat_id != 6
        //                     LIMIT 1
        //                 )
        //             ) AS cat_name,
        //             (
        //                 SELECT value
        //                 FROM `modx_site_tmplvar_contentvalues`
        //                 WHERE tmplvarid = 20
        //                   AND contentid = (
        //                       SELECT cat_id 
        //                       FROM `modx_a_flat2cat` 
        //                       WHERE flat_id = f.id AND cat_id != 6
        //                       LIMIT 1
        //                   )
        //             ) AS tv_object_type,
        //             (
        //                 SELECT id 
        //                 FROM `modx_site_content`
        //                 WHERE id = (
        //                     SELECT cat_id 
        //                     FROM `modx_a_flat2cat`
        //                     WHERE flat_id = f.id AND cat_id != 6
        //                     LIMIT 1
        //                 )
        //             ) AS cat_id
        //         FROM `modx_a_flat` AS f
        //         " . $whereSql . "
        //         " . $having . "
        //         " . $extraHaving . "
        //         ORDER BY " . $order . "
        //         LIMIT " . $offset . ", " . $limit;

        //     // ---- 9) Плейсхолдеры как у тебя были (cats_two / cats_three) ----
        //     if (isset($_GET['sort_two_pagin']) && $_GET['sort_two_pagin'] !== '') {
        //         $modx->setPlaceholder('cats_two', $_GET['sort_two_pagin']);
        //     } elseif (isset($_GET['sort_three_pagin']) && $_GET['sort_three_pagin'] !== '') {
        //         $modx->setPlaceholder('cats_three', $_GET['sort_three_pagin']);
        //     } elseif (!empty($_GET['sort_one']) && !empty($_GET['sort_two']) && !empty($param['AND'])) {
        //         $modx->setPlaceholder('cats_three', $param['AND']);
        //     }

        //     // ---- 10) Выполнение ----
        //     $q = $db->query($sql);
        //     $rows = $db->makeArray($q);

        //     // при желании — общее количество (как раньше через FOUND_ROWS)
        //     if (!empty($param['with_total'])) {
        //         $total = (int)$db->getValue($db->query('SELECT FOUND_ROWS()'));
        //         // можно положить в плейсхолдер
        //         $modx->setPlaceholder('flats_total', $total);
        //         // и вернуть рядом (если нужно)
        //         return array('rows' => $rows, 'total' => $total);
        //     }

        //     return $rows;
        // }
public function getFlats($param, $where = array(), $id = 5, $order = 'points DESC, FIELD(ABS(status),1,2,5,3,4), id DESC')
{
    $modx = $this->modx;
    $db   = $this->db; // Evo DBAPI

    // ---- язык из сессии (без фолбэков) ----
    $lang = isset($_SESSION['lang']) && is_string($_SESSION['lang']) ? strtolower($_SESSION['lang']) : 'ru';
    // допустимые коды; если прилетело что-то левое — сбрасываем в ru
    if (!in_array($lang, array('ru','ua'), true)) $lang = 'ru';
    $langEsc = $db->escape($lang);

    // ---- 1) Категории ----
    $rootIds = is_array($id) ? $id : explode(',', (string)$id);
    $rootIds = array_filter(array_map('intval', $rootIds), function($v){ return $v > 0; });

    $allCats = array();
    foreach ($rootIds as $rid) {
        $allCats = array_merge($allCats, array_values($modx->getChildIds($rid)));
        $allCats[] = $rid;
    }
    $allCats = array_values(array_unique(array_map('intval', $allCats)));
    if (empty($allCats)) $allCats = array(0);

    $andCats = array();
    if (!empty($param['AND'])) {
        $andCats = array_filter(array_map('intval', explode(',', (string)$param['AND'])), function($v){ return $v > 0; });
    }

    // ---- 2) Пагинация / points ----
    $limit  = isset($param['limit'])  ? max(0, (int)$param['limit'])  : 20;
    $offset = isset($param['offset']) ? max(0, (int)$param['offset']) : 0;
    $points = 0;
    if (isset($param['points']) && is_string($param['points']) && preg_match('/^IF \\(.+\\)$/s', $param['points']) && strpos($param['points'], ';') === false) {
        $points = $param['points'];
    } elseif (isset($param['points'])) {
        $points = (float)$param['points'];
    }

    // ---- 3) WHERE ----
    $whereSql = '';
    if (is_array($where) && count($where) > 0) {
        $whereSql = ' WHERE ' . implode(' AND ', $where);
    }

    // ---- 4) HAVING ----
    $having = ' HAVING cnt > 0';
    if (!empty($andCats)) {
        $having .= ' AND cat_id IN (' . implode(',', $andCats) . ')';
    }

    // ---- 5) доп. HAVING-хвост ----
    $extraHaving = '';
    if (!empty($param['extra_having'])) {
        $extraHaving .= ' ' . $param['extra_having'];
    }

    // ---- 6) ORDER ----
    $orderDefault = 'points DESC, FIELD(ABS(status),1,2,5,3,4), id DESC';
    if (!is_string($order) || !preg_match('~^[a-z0-9_(),.\s]+$~i', $order)) {
        $order = $orderDefault;
    }

    // ---- 7) SQL ----
    // Обрати внимание: LEFT JOIN по product_id и string_locate = $lang
    // Если для языка записи нет — поля из s.* будут NULL (как ты и просил).
    $sql = "
        SELECT SQL_CALC_FOUND_ROWS
            f.*,
            {$points} AS points,

            /* ===== языковые поля из modx_a_flat_strings ===== */
            s.name,
            s.consumption,
            s.taxation,
            s.description,
            s.`character`,
            s.country,
            s.region,
            s.locality_name,
            s.non_admin_sub_locality,
            s.metro,
            s.microdistrict_xml,
            s.street_xml,
            s.house_number_xml,
            s.addr,
            s.product_seo_title,
            s.product_seo_description,
            s.product_seo_keywords,
            s.product_seo_canonical,
            s.product_seo_robots,

            /* ===== служебные вычисления как раньше ===== */
            (
                SELECT COUNT(*)
                FROM `modx_a_flat2cat`
                WHERE cat_id IN (" . implode(',', $allCats) . ")
                  AND flat_id = f.id
            ) AS cnt,

            (
                SELECT sc.pagetitle_{$langEsc}
                FROM `modx_site_content` sc
                WHERE sc.id = (
                    SELECT cat_id
                    FROM `modx_a_flat2cat`
                    WHERE flat_id = f.id AND cat_id != 6
                    LIMIT 1
                )
            ) AS cat_name,

            (
                SELECT value
                FROM `modx_site_tmplvar_contentvalues`
                WHERE tmplvarid = 20
                  AND contentid = (
                      SELECT cat_id
                      FROM `modx_a_flat2cat`
                      WHERE flat_id = f.id AND cat_id != 6
                      LIMIT 1
                  )
            ) AS tv_object_type,

            (
                SELECT sc2.id
                FROM `modx_site_content` sc2
                WHERE sc2.id = (
                    SELECT cat_id
                    FROM `modx_a_flat2cat`
                    WHERE flat_id = f.id AND cat_id != 6
                    LIMIT 1
                )
            ) AS cat_id

        FROM `modx_a_flat` AS f
        LEFT JOIN `modx_a_flat_strings` AS s
               ON s.product_id = f.id
              AND s.string_locate = '{$langEsc}'
        {$whereSql}
        {$having}
        {$extraHaving}
        ORDER BY {$order}
        LIMIT {$offset}, {$limit}
    ";


    if (isset($_GET['sort_two_pagin']) && $_GET['sort_two_pagin'] !== '') {
        $modx->setPlaceholder('cats_two', $_GET['sort_two_pagin']);
    } elseif (isset($_GET['sort_three_pagin']) && $_GET['sort_three_pagin'] !== '') {
        $modx->setPlaceholder('cats_three', $_GET['sort_three_pagin']);
    } elseif (!empty($_GET['sort_one']) && !empty($_GET['sort_two']) && !empty($param['AND'])) {
        $modx->setPlaceholder('cats_three', $param['AND']);
    }

    // ---- выполнение ----
    $q    = $db->query($sql);
    $rows = $db->makeArray($q);

    if (!empty($param['with_total'])) {
        $total = (int)$db->getValue($db->query('SELECT FOUND_ROWS()'));
        $modx->setPlaceholder('flats_total', $total);
        return array('rows' => $rows, 'total' => $total);
    }

    return $rows;
}



        // public function getFlatsExel($param, $where = '', $id = 5)
        // {
        //     $categories = array_values($this->modx->getChildIds($id));
        //     $categories[] = $id;
        //     $categories = implode(",", $categories);
        //     $sql
        //         = "SELECT SQL_CALC_FOUND_ROWS *,
        //         (select count(*) from `modx_a_flat2cat` where cat_id in (" . $categories . ") and flat_id = f.id) AS 'cnt',
        //         (SELECT pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
        //         (SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 20 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_object_type'
        //         FROM `modx_a_flat` f
        //             " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
        //         HAVING cnt > 0
        //         AND exel_active = 1 
        //         ORDER BY id DESC
        //         LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);
        //     return $this->db->makeArray($this->db->query($sql));
        // }

        public function getFlatsExel($param, $where = array(), $id = 5)
        {
            $modx = $this->modx;
            $db   = $this->db; // DBAPI в Evo

            // ---- 1) Собираем категории: корневые + все дочерние ----
            $rootIds = is_array($id) ? $id : explode(',', (string)$id);
            $rootIds = array_filter(array_map('intval', $rootIds), function($v){ return $v > 0; });

            $allCats = array();
            foreach ($rootIds as $rid) {
                $allCats = array_merge($allCats, array_values($modx->getChildIds($rid)));
                $allCats[] = $rid;
            }
            $allCats = array_values(array_unique(array_map('intval', $allCats)));
            if (empty($allCats)) $allCats = array(0);

            // Доп. фильтр категорий из $param['AND'] (если используется)
            $andCats = array();
            if (!empty($param['AND'])) {
                $andCats = array_filter(array_map('intval', explode(',', (string)$param['AND'])), function($v){ return $v > 0; });
            }

            // ---- 2) Пагинация ----
            $limit  = isset($param['limit'])  ? max(0, (int)$param['limit'])  : 20;
            $offset = isset($param['offset']) ? max(0, (int)$param['offset']) : 0;

            // ---- 3) WHERE из массива $where (+ exel_active = 1) ----
            // Ожидается массив готовых условий (как раньше). Добавим туда наш флаг выгрузки.
            $whereParts = array();
            if (is_array($where) && count($where) > 0) {
                $whereParts = $where;
            }
            // Перенёс exel_active = 1 в WHERE (у тебя он был в HAVING — это не критично, но так быстрее)
            $whereParts[] = 'exel_active = 1';

            $whereSql = '';
            if (count($whereParts) > 0) {
                $whereSql = ' WHERE ' . implode(' AND ', $whereParts);
            }

            // ---- 4) Язык для pagetitle_* ----
            $lang = isset($this->lang) && $this->lang ? preg_replace('~[^a-z0-9_]~i', '', $this->lang) : 'ua';

            // ---- 5) HAVING: cnt > 0 (+ фильтр по AND-списку категорий, если был передан) ----
            $having = ' HAVING cnt > 0';
            if (!empty($andCats)) {
                $having .= ' AND cat_id IN (' . implode(',', $andCats) . ')';
            }

            // ---- 6) SQL ----
            $sql = "
                SELECT SQL_CALC_FOUND_ROWS
                    f.*,
                    (
                        SELECT COUNT(*)
                        FROM `modx_a_flat2cat`
                        WHERE cat_id IN (" . implode(',', $allCats) . ")
                          AND flat_id = f.id
                    ) AS cnt,
                    (
                        SELECT pagetitle_{$lang}
                        FROM `modx_site_content`
                        WHERE id = (
                            SELECT cat_id
                            FROM `modx_a_flat2cat`
                            WHERE flat_id = f.id AND cat_id != 6
                            LIMIT 1
                        )
                    ) AS cat_name,
                    (
                        SELECT value
                        FROM `modx_site_tmplvar_contentvalues`
                        WHERE tmplvarid = 20
                          AND contentid = (
                              SELECT cat_id
                              FROM `modx_a_flat2cat`
                              WHERE flat_id = f.id AND cat_id != 6
                              LIMIT 1
                          )
                    ) AS tv_object_type,
                    (
                        SELECT id
                        FROM `modx_site_content`
                        WHERE id = (
                            SELECT cat_id
                            FROM `modx_a_flat2cat`
                            WHERE flat_id = f.id AND cat_id != 6
                            LIMIT 1
                        )
                    ) AS cat_id
                FROM `modx_a_flat` AS f
                " . $whereSql . "
                " . $having . "
                ORDER BY id DESC
                LIMIT " . $offset . ", " . $limit;

            // ---- 7) Выполняем ----
            $q = $db->query($sql);
            $rows = $db->makeArray($q);

            // Опционально вернуть total через FOUND_ROWS()
            if (!empty($param['with_total'])) {
                $total = (int)$db->getValue($db->query('SELECT FOUND_ROWS()'));
                return array('rows' => $rows, 'total' => $total);
            }

            return $rows;
        }


        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
//        public function updateFlats($param, $where = '', $set = '')
//        {
//            if ($set != '') {
//                $this->db->query("UPDATE `modx_a_flat`
//                  " . (count($set) > 0 ? " SET " . implode(" , ", $set) : "") . "
//                  WHERE id IN (SELECT id FROM `modx_a_flat` f
//                  " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
//            " . ($param['offset'] != '' && $param['limit'] != '' ? "  LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']) : '') . ")");
//                return $this->db->getAffectedRows();
//            }
//        }
        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlats($ids = 0, $xml_active = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $xml_active = (int)$xml_active;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET xml_active=" . $xml_active);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET xml_active=" . $xml_active . " WHERE id IN (" . $idsSql . ")");
        }

        //my
        public function updateFlatsExel($ids = 0, $xml_active = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $xml_active = (int)$xml_active;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET exel_active=" . $xml_active);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET exel_active=" . $xml_active . " WHERE id IN (" . $idsSql . ")");
        }

        //my
        public function updateFlatsPrint($ids = 0, $xml_active = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $xml_active = (int)$xml_active;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET print_active=" . $xml_active);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET print_active=" . $xml_active . " WHERE id IN (" . $idsSql . ")");
        }

        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlatsVhn($ids = 0, $xml_active_vhn = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $xml_active_vhn = (int)$xml_active_vhn;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET xml_active_vhn=" . $xml_active_vhn);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET xml_active_vhn=" . $xml_active_vhn . " WHERE id IN (" . $idsSql . ")");
        }

        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlatsLun($ids = 0, $xml_active_lun = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $xml_active_lun = (int)$xml_active_lun;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET xml_active_lun=" . $xml_active_lun);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET xml_active_lun=" . $xml_active_lun . " WHERE id IN (" . $idsSql . ")");
        }

        /**
         * Массовое обновление объектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlatsPlectan($ids = 0, $active = 0)
        {
            $idsSql = $this->idsForUpdate($ids);
            if ($idsSql === false) {
                return false;
            }
            $active = (int)$active;
            if ($idsSql === 'ALL') {
                return $this->db->query("UPDATE `modx_a_flat` SET `xml_active_plektan`='".$active."'");
            }
            return $this->db->query("UPDATE `modx_a_flat` SET `xml_active_plektan`='".$active."' WHERE id IN (".$idsSql.")");
        }

        /**
         * Выводим список обектов
         *
         * @param [array] параментры для выборки
         */
        // public function getFlatsXML($param, $where = '', $id = 5)
        // {
        //     $categories = array_values($this->modx->getChildIds($id));

        //     //исключаем зарубежную недвижимость
        //     $excludeDocs = array_values($this->modx->getChildIds(12));
        //     $excludeDocs[] = 12;
        //     $excludeDocs[] = 6;//новинки
        //     foreach ($categories as $key => $value) {
        //         if (in_array($value, $excludeDocs)) {
        //             unset($categories[$key]);
        //         }
        //     }

        //     $categories[] = $id;
        //     $categories = implode(",", $categories);

        //     $sql
        //         = "SELECT SQL_CALC_FOUND_ROWS *,
        //      (select count(*) from `modx_a_flat2cat` where cat_id in (" . $categories . ") and flat_id = f.id) AS 'cnt',
        //      (SELECT pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
        //      (SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 20 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_object_type',
        //      (SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 26 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_xml_category',
        //      (select value from `modx_site_tmplvar_contentvalues` where contentid = f.agent and tmplvarid = 14) as agent_phone,
        //      (select pagetitle_{$this->lang} AS pagetitle from `modx_site_content` where id = f.agent) as agent_name
        //  FROM `modx_a_flat` f
        //      " . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
        //  HAVING cnt > 0
        //  ORDER BY id DESC
        //     LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);

        //     return $this->db->makeArray($this->db->query($sql));
        // }

        public function getFlatsXML($param, $where = '', $lang = null, $id = 5 )
        {
            // Собираем категории
            $categories = array_values($this->modx->getChildIds($id));

            // Исключения: зарубежка и новинки
            $excludeDocs   = array_values($this->modx->getChildIds(12));
            $excludeDocs[] = 12;
            $excludeDocs[] = 6;

            foreach ($categories as $k => $v) {
                if (in_array($v, $excludeDocs)) unset($categories[$k]);
            }

            $categories[] = $id;
            $categories   = implode(',', $categories);

            // Язык

              $lang = $lang ? $lang : $this->lang;


            // WHERE — поддержим и массив, и строку
            if (is_array($where) && count($where) > 0) {
                $whereSql = ' WHERE ' . implode(' AND ', $where);
            } elseif (is_string($where) && strlen(trim($where)) > 0) {
                $whereSql = ' WHERE ' . $where;
            } else {
                $whereSql = '';
            }

            // Пагинация
            $offset = isset($param['offset']) ? (int)$param['offset'] : 0;
            $limit  = isset($param['limit'])  ? (int)$param['limit']  : 20;

            $sql = "
                SELECT SQL_CALC_FOUND_ROWS
                    f.*,

                    /* Текстовые поля из новой таблицы (экранированы) */
                    s.`name`                     AS `name`,
                    s.`description`              AS `description`,
                    s.`character`                AS `character`,
                    s.`country`                  AS `country`,
                    s.`region`                   AS `region`,
                    s.`locality_name`            AS `locality_name`,
                    s.`non_admin_sub_locality`   AS `non_admin_sub_locality`,
                    s.`metro`                    AS `metro`,
                    s.`microdistrict_xml`        AS `microdistrict_xml`,
                    s.`street_xml`               AS `street_xml`,
                    s.`house_number_xml`         AS `house_number_xml`,
                    s.`addr`                     AS `addr`,
                    s.`consumption`              AS `consumption`,
                    s.`taxation`                 AS `taxation`,
                    s.`product_seo_title`        AS `product_seo_title`,
                    s.`product_seo_description`  AS `product_seo_description`,
                    s.`product_seo_keywords`     AS `product_seo_keywords`,
                    s.`product_seo_canonical`    AS `product_seo_canonical`,
                    s.`product_seo_robots`       AS `product_seo_robots`,

                    /* Вспомогательные поля */
                    (
                        SELECT COUNT(*)
                        FROM `modx_a_flat2cat`
                        WHERE cat_id IN ({$categories}) AND flat_id = f.`id`
                    ) AS `cnt`,

                    (
                        SELECT `pagetitle_{$lang}`
                        FROM `modx_site_content`
                        WHERE `id` = (
                            SELECT `cat_id`
                            FROM `modx_a_flat2cat`
                            WHERE `flat_id` = f.`id` AND `cat_id` != 6
                            LIMIT 1
                        )
                    ) AS `cat_name`,

                    (
                        SELECT `value`
                        FROM `modx_site_tmplvar_contentvalues`
                        WHERE `tmplvarid` = 20
                          AND `contentid` = (
                              SELECT `cat_id`
                              FROM `modx_a_flat2cat`
                              WHERE `flat_id` = f.`id` AND `cat_id` != 6
                              LIMIT 1
                          )
                    ) AS `tv_object_type`,

                    (
                        SELECT `value`
                        FROM `modx_site_tmplvar_contentvalues`
                        WHERE `tmplvarid` = 26
                          AND `contentid` = (
                              SELECT `cat_id`
                              FROM `modx_a_flat2cat`
                              WHERE `flat_id` = f.`id` AND `cat_id` != 6
                              LIMIT 1
                          )
                    ) AS `tv_xml_category`,

                    (
                        SELECT `value`
                        FROM `modx_site_tmplvar_contentvalues`
                        WHERE `contentid` = f.`agent` AND `tmplvarid` = 14
                    ) AS `agent_phone`,

                    (
                        SELECT `pagetitle_{$lang}`
                        FROM `modx_site_content`
                        WHERE `id` = f.`agent`
                    ) AS `agent_name`

                FROM `modx_a_flat` f

                /* Языковые строки */
                LEFT JOIN `modx_a_flat_strings` s
                    ON s.`product_id` = f.`id`
                   AND s.`string_locate` = '{$lang}'

                {$whereSql}
                HAVING `cnt` > 0
                ORDER BY f.`id` DESC
                LIMIT {$offset}, {$limit}
            ";

            $q = $this->db->query($sql);
            return $this->db->makeArray($q);
        }


        /**
         * Создание/обновление обектов
         *
         * @param [array] массив чего записуем
         * @id [int] id объекта
         */
        public function setFlat($param, $id = null)
        {
            //Url
           
            if (isset($param['url'])) {
                if ($param['url'] == '') {
                    $param['url'] = ($param['name'] != '') ? $this->modx->stripAlias($param['name']) : $param['id'];
                }
             
                do {
                    $sql = "SELECT count(*) as value FROM `modx_a_flat` WHERE url = '" . $this->db->escape($param['url']) . "' and id != '" . $this->db->escape($param['id']) . "'";
                    
                    $count = $this->db->getValue($this->db->query($sql));
                    if ($count > 0) {
                        $u = false;
                        $param['url'] .= '-1';
                    } else {
                        $u = true;
                    }
                } while ($u == false);


            }

            // Сохраняем основную информацию
            if ($id != '') {
                if (count($param) > 0) {
                    foreach ($param as $key => $value) {
                        switch ($key) {
                            case 'id':
                            case 'categories':
                            case 'properties':
                                break;
                            case 'character':
                                $where[] = "`character` = '" . $this->db->escape(json_encode($value)) . "'";
                                break;
                            default:
                                $where[] = "`" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "'";
                                break;
                        }

                    }
                    //  $where[] = "updated_at = NOW()";

//


                    if ($_GET['c'] == 'update') {
                        $price = $this->db->getValue($this->db->select('price', 'modx_a_flat', "id=" . $param['id'] . ""));
                        if ($price != $param['price']) {
                            $where[] = "updated_at = NOW()";
                        }
                        if ($param['active_now'] == 1) {
                            $where[] = "updated_at = NOW()";
                        }
                    }


                    if (count($where) > 0) {
                        $sql
                            = 'UPDATE `modx_a_flat`
                        SET ' . implode(",", $where) . '
                        WHERE id = "' . $this->db->escape($id) . '"
                        ';

                        $this->db->query($sql);
                    }
                }
            } else {
                if (count($param) > 0) {
                    foreach ($param as $key => $value) {
                        switch ($key) {
                            case 'id':
                            case 'categories':
                            case 'properties':
                                break;
                            case 'character':
                                $where[] = "`character` = '" . $this->db->escape(json_encode($value)) . "'";
                                break;
                            default:
                                $where[] = "`" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "'";
                                break;
                        }
                    }

                    $where[] = "updated_at = NOW()";
                    $where[] = "created_at = NOW()";
                    if (count($where) > 0) {
                        $sql
                            = 'INSERT INTO `modx_a_flat`
                        SET ' . implode(",", $where) . '
                        ';
                        $this->db->query($sql);
                        $id = $this->db->getInsertId();

                    }
                }
            }
            //Сохраняем категории
            if (count($param['categories']) > 0) {
                $this->db->query("DELETE FROM `modx_a_flat2cat` WHERE flat_id = '" . $this->db->escape($id) . "'");
                foreach ($param['categories'] as $key => $value) {
                    $this->db->query("INSERT INTO `modx_a_flat2cat` SET flat_id = '" . $this->db->escape($id) . "', cat_id = '" . $this->db->escape($value) . "'");
                }
            }
            //Сохраняем св-ва объекта
            if (count($param['properties']) > 0) {
                $this->db->query("DELETE FROM `modx_a_flat2properties` WHERE flat_id = '" . $this->db->escape($id) . "'");
                foreach ($param['properties'] as $key => $value) {
                    $this->db->query("INSERT INTO `modx_a_flat2properties` SET flat_id = '" . $this->db->escape($id) . "', properties_id = '" . $this->db->escape($value) . "'");
                }
            }
            return $id;
        }



        // public function setFlatStrings($data, $product_id, $locale)
        // {
        //     // helper: пустые строки -> NULL
        //     $nn = function ($v) {
        //         if (is_array($v)) return $v;
        //         if (is_string($v)) $v = trim($v);
        //         return ($v === '' || $v === null) ? null : $v;
        //     };

        //     // character: массив строк -> JSON-массив
        //     $character = null;
        //     if (!empty($data['character'])) {
        //         // допускаем как массив, так и одну строку
        //         $chars = is_array($data['character']) ? $data['character'] : array($data['character']);
        //         $lines = array();
        //         foreach ($chars as $s) {
        //             $s = trim((string)$s);
        //             if ($s !== '') $lines[] = $s;
        //         }
        //         if (!empty($lines)) {
        //             $json = json_encode(array_values($lines), JSON_UNESCAPED_UNICODE);
        //             // подстраховка: если по какой-то причине вернулся false
        //             if ($json === false) $json = json_encode(array_values($lines));
        //             $character = $json;
        //         }
        //     }

        //     $row = array(
        //         'string_locate'           => (string)$locale,
        //         'product_id'              => (int)$product_id,
        //         'name'            => isset($data['name']) ? $nn($data['name']) : null,
        //         'consumption'             => isset($data['consumption']) ? $nn($data['consumption']) : null,
        //         'taxation'                => isset($data['taxation']) ? $nn($data['taxation']) : null,
        //         'description'             => isset($data['description']) ? $nn($data['description']) : null,
        //         'character'               => $nn($character), // <-- JSON здесь
        //         'country'                 => isset($data['country']) ? $nn($data['country']) : null,
        //         'region'                  => isset($data['region']) ? $nn($data['region']) : null,
        //         'locality_name'           => isset($data['locality_name']) ? $nn($data['locality_name']) : null,
        //         'non_admin_sub_locality'  => isset($data['non_admin_sub_locality']) ? $nn($data['non_admin_sub_locality']) : null,
        //         'metro'                   => isset($data['metro']) ? $nn($data['metro']) : null,
        //         'microdistrict_xml'       => isset($data['microdistrict_xml']) ? $nn($data['microdistrict_xml']) : null,
        //         'street_xml'              => isset($data['street_xml']) ? $nn($data['street_xml']) : null,
        //         'house_number_xml'        => isset($data['house_number_xml']) ? $nn($data['house_number_xml']) : null,
        //         'addr'                    => isset($data['addr']) ? $nn($data['addr']) : null,
        //         'product_seo_title'       => isset($data['product_seo_title']) ? $nn($data['product_seo_title']) : null,
        //         'product_seo_description' => isset($data['product_seo_description']) ? $nn($data['product_seo_description']) : null,
        //         'product_seo_keywords'    => isset($data['product_seo_keywords']) ? $nn($data['product_seo_keywords']) : null,
        //         'product_seo_canonical'   => isset($data['product_seo_canonical']) ? $nn($data['product_seo_canonical']) : null,
        //         'product_seo_robots'      => isset($data['product_seo_robots']) ? $nn($data['product_seo_robots']) : null,
        //     );

        //     // upsert по (product_id, string_locate)
        //     $pid = (int)$product_id;
        //     $loc = $this->db->escape((string)$locale);
        //     $tbl = $this->db->config['table_prefix'] . 'a_flat_strings';

        //     $sql = "SELECT string_id FROM `{$tbl}` WHERE product_id={$pid} AND string_locate='{$loc}' LIMIT 1";
        //     $q   = $this->db->query($sql);
        //     if (!$q) return false;

        //     $exists = $this->db->getRow($q);

        //     if ($exists && !empty($exists['string_id'])) {
        //         $where = "string_id=" . (int)$exists['string_id'];
        //         return (bool)$this->db->update($row, $tbl, $where);
        //     } else {
        //         return (bool)$this->db->insert($row, $tbl);
        //     }
        // }

        public function setFlatStrings($data, $product_id, $locale)
        {
            $db   = $this->db;
            $pref = $db->config['table_prefix'];
            $tbl  = $pref . 'a_flat_strings';

            // helper: пустые строки -> NULL
            $nn = function ($v) {
                if (is_array($v)) return $v;
                if (is_string($v)) $v = trim($v);
                return ($v === '' || $v === null) ? null : $v;
            };

            // JSON для character (массив строк -> JSON)
            $character = null;
            if (!empty($data['character'])) {
                $chars = is_array($data['character']) ? $data['character'] : array($data['character']);
                $lines = array();
                foreach ($chars as $s) {
                    $s = trim((string)$s);
                    if ($s !== '') $lines[] = $s;
                }
                if (!empty($lines)) {
                    $json = json_encode(array_values($lines), JSON_UNESCAPED_UNICODE);
                    if ($json === false) $json = json_encode(array_values($lines)); // подстраховка
                    $character = $json;
                }
            }

            // Собираем данные
            $row = array(
                'string_locate'           => (string)$locale,
                'product_id'              => (int)$product_id,
                'name'                    => isset($data['name']) ? $nn($data['name']) : null,
                'consumption'             => isset($data['consumption']) ? $nn($data['consumption']) : null,
                'taxation'                => isset($data['taxation']) ? $nn($data['taxation']) : null,
                'description'             => isset($data['description']) ? $nn($data['description']) : null,
                'character'               => $nn($character), // JSON
                'country'                 => isset($data['country']) ? $nn($data['country']) : null,
                'region'                  => isset($data['region']) ? $nn($data['region']) : null,
                'locality_name'           => isset($data['locality_name']) ? $nn($data['locality_name']) : null,
                'non_admin_sub_locality'  => isset($data['non_admin_sub_locality']) ? $nn($data['non_admin_sub_locality']) : null,
                'metro'                   => isset($data['metro']) ? $nn($data['metro']) : null,
                'microdistrict_xml'       => isset($data['microdistrict_xml']) ? $nn($data['microdistrict_xml']) : null,
                'street_xml'              => isset($data['street_xml']) ? $nn($data['street_xml']) : null,
                'house_number_xml'        => isset($data['house_number_xml']) ? $nn($data['house_number_xml']) : null,
                'addr'                    => isset($data['addr']) ? $nn($data['addr']) : null,
                'product_seo_title'       => isset($data['product_seo_title']) ? $nn($data['product_seo_title']) : null,
                'product_seo_description' => isset($data['product_seo_description']) ? $nn($data['product_seo_description']) : null,
                'product_seo_keywords'    => isset($data['product_seo_keywords']) ? $nn($data['product_seo_keywords']) : null,
                'product_seo_canonical'   => isset($data['product_seo_canonical']) ? $nn($data['product_seo_canonical']) : null,
                'product_seo_robots'      => isset($data['product_seo_robots']) ? $nn($data['product_seo_robots']) : null,
            );

            // Литерал для SQL c учётом NO_BACKSLASH_ESCAPES:
            // NULL -> NULL, строка -> 'строка_с_удвоенными_апострофами', число -> как есть
            $sql_literal = function($v) use ($db) {
                if ($v === null) return 'NULL';
                if (is_int($v) || is_float($v)) return (string)$v;

                // ДОВЕРЯЕМ escape(): он сам учтёт режим NO_BACKSLASH_ESCAPES
                $s = $db->escape((string)$v);
                return "'" . $s . "'";
            };

            $pid = (int)$product_id;
            $loc = $db->escape((string)$locale);

            // Проверяем наличие записи
            $q = $db->query("SELECT `string_id` FROM `{$tbl}` WHERE `product_id`={$pid} AND `string_locate`='{$loc}' LIMIT 1");
            if (!$q) return false;

            $exists = $db->getRow($q);

            if ($exists && !empty($exists['string_id'])) {
                // UPDATE
                $sid = (int)$exists['string_id'];
                $sets = array();
                foreach ($row as $k => $v) {
                    // product_id / string_locate не меняем в апдейте
                    if ($k === 'product_id' || $k === 'string_locate') continue;
                    $sets[] = "`{$k}` = " . $sql_literal($v);
                }
                $sql = "UPDATE `{$tbl}` SET " . implode(', ', $sets) . " WHERE `string_id` = {$sid} LIMIT 1";
                return (bool)$db->query($sql);
            } else {
                // INSERT
                $cols = array();
                $vals = array();
                foreach ($row as $k => $v) {
                    $cols[] = "`{$k}`";
                    $vals[] = $sql_literal($v);
                }
                $sql = "INSERT INTO `{$tbl}` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ")";
                return (bool)$db->query($sql);
            }
        }





        public function insertFlatStringsNew($data, $product_id, $locale)
            {
                $db   = $this->db;
                $pref = $db->config['table_prefix'];
                $tbl  = $pref . 'a_flat_strings';

                // helper: пустые строки -> NULL
                $nn = function ($v) {
                    if (is_array($v)) return $v;
                    if (is_string($v)) $v = trim($v);
                    return ($v === '' || $v === null) ? null : $v;
                };

                // JSON для character (массив строк -> JSON)
                $character = null;
                if (!empty($data['character'])) {
                    $chars = is_array($data['character']) ? $data['character'] : array($data['character']);
                    $lines = array();
                    foreach ($chars as $s) {
                        $s = trim((string)$s);
                        if ($s !== '') $lines[] = $s;
                    }
                    if (!empty($lines)) {
                        $json = json_encode(array_values($lines), JSON_UNESCAPED_UNICODE);
                        if ($json === false) $json = json_encode(array_values($lines));
                        $character = $json;
                    }
                }

                // Собираем данные
                $row = array(
                    'string_locate'           => (string)$locale,
                    'product_id'              => (int)$product_id,
                    'name'                    => isset($data['name']) ? $nn($data['name']) : null,
                    'consumption'             => isset($data['consumption']) ? $nn($data['consumption']) : null,
                    'taxation'                => isset($data['taxation']) ? $nn($data['taxation']) : null,
                    'description'             => isset($data['description']) ? $nn($data['description']) : null,
                    'character'               => $nn($character), // JSON
                    'country'                 => isset($data['country']) ? $nn($data['country']) : null,
                    'region'                  => isset($data['region']) ? $nn($data['region']) : null,
                    'locality_name'           => isset($data['locality_name']) ? $nn($data['locality_name']) : null,
                    'non_admin_sub_locality'  => isset($data['non_admin_sub_locality']) ? $nn($data['non_admin_sub_locality']) : null,
                    'metro'                   => isset($data['metro']) ? $nn($data['metro']) : null,
                    'microdistrict_xml'       => isset($data['microdistrict_xml']) ? $nn($data['microdistrict_xml']) : null,
                    'street_xml'              => isset($data['street_xml']) ? $nn($data['street_xml']) : null,
                    'house_number_xml'        => isset($data['house_number_xml']) ? $nn($data['house_number_xml']) : null,
                    'addr'                    => isset($data['addr']) ? $nn($data['addr']) : null,
                    'product_seo_title'       => isset($data['product_seo_title']) ? $nn($data['product_seo_title']) : null,
                    'product_seo_description' => isset($data['product_seo_description']) ? $nn($data['product_seo_description']) : null,
                    'product_seo_keywords'    => isset($data['product_seo_keywords']) ? $nn($data['product_seo_keywords']) : null,
                    'product_seo_canonical'   => isset($data['product_seo_canonical']) ? $nn($data['product_seo_canonical']) : null,
                    'product_seo_robots'      => isset($data['product_seo_robots']) ? $nn($data['product_seo_robots']) : null,
                );

                // Литерал для SQL: NULL -> NULL; строки экранируем; числа — как есть
                $sql_literal = function($v) use ($db) {
                    if ($v === null) return 'NULL';
                    if (is_int($v) || is_float($v)) return (string)$v;
                    $s = $db->escape((string)$v); // корректно с/без NO_BACKSLASH_ESCAPES
                    return "'" . $s . "'";
                };

                $cols = array();
                $vals = array();
                foreach ($row as $k => $v) {
                    $cols[] = "`{$k}`";
                    $vals[] = $sql_literal($v);
                }

                $sql = "INSERT INTO `{$tbl}` (" . implode(',', $cols) . ") VALUES (" . implode(',', $vals) . ")";
                return (bool)$db->query($sql);
            }








        /**
         * Выводим все изображения для объекта
         * @id [int] $id Id объекта
         */
        public function getImagesFlat($id)
        {
            $sql = 'select * from `modx_a_flat2image` where flat_id = "' . $this->db->escape($id) . '" ORDER BY sort ASC';
            return $this->db->makeArray($this->db->query($sql));
        }

        /**
         * Записуем изображение
         */
        public function setImageFlat($id, $name, $sort, $type = 0)
        {
            $sql = "INSERT INTO `modx_a_flat2image` SET flat_id = '" . $this->db->escape($id) . "', image = '" . $this->db->escape($name) . "', sort = '" . $sort . "', `type` = '" . $this->db->escape($type) . "'";
            $this->db->query($sql);

            $this->setImageFlatToTmp($id, $name, $sort, $type = 0);

        //     $opt = $type == 0 ? "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=1920,h=1080,far=1,f=jpg,fltr_wmi|/images/watermark.png|C|100|50|50";
        //     $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "folder" => "object/" . $id]);
        //     $opt = $type == 0 ? "w=670&h=400&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=jpg,fltr_wmi|/images/watermark.png|C|100|50|50";
        //     $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=jpg&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);

        //     $opt = $type == 0 ? "w=900&h=500&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark.png|C|100|50|50";


        //     $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
        //     $opt = $type == 0 ? "w=660&h=350&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark-middle.png|C|100|50|50";
        //     $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
        //     $opt = $type == 0 ? "w=136&h=77&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark-small.png|C|100|50|50";
        //     $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
         }

        private function setImageFlatToTmp($id, $name, $sort, $type = 0)
        {
            $sql = "INSERT INTO `modx_a_flat2imagerawtmp` SET flat_id = '" . $this->db->escape($id) . "', image = '" . $this->db->escape($name) . "', `type` = '" . $this->db->escape($type) . "', status = 0";
            $this->db->query($sql);
        }



        /**
         * Изменяем статус изображения
         */
        public function chageTypeImage($id, $image, $type)
        {
            $sql = "UPDATE `modx_a_flat2image` SET type = '" . $this->db->escape($type) . "' WHERE flat_id = '" . $this->db->escape($id) . "' AND image = '" . $this->db->escape($image) . "'";
            $this->db->query($sql);
        }

        /**
         * Удаление изображения объекта
         */
        public function deleteImageFlat($id, $name)
        {
            if(is_numeric($id)){
                unlink(MODX_BASE_PATH . 'assets/images/flat/' . $id . '/' . $name);
            } else {
                unlink(MODX_BASE_PATH . 'assets/images/flat/tmp/' . $name);
            }
            
            $sql = 'DELETE FROM `modx_a_flat2image` WHERE flat_id = "' . $this->db->escape($id) . '" AND image = "' . $this->db->escape($name) . '"';
            $this->db->query($sql);
        }

        public function deleteImagesFlat($id)
        {
            $sql = 'DELETE FROM `modx_a_flat2image` WHERE flat_id = "' . $this->db->escape($id) . '"';
            $this->db->query($sql);
        }

        /**
         * Удаление объекта
         */
        public function deleteFlat($id)
        {
            $id = $this->db->escape($id);
            $sql = "DELETE FROM `modx_a_flat2image` WHERE flat_id = '{$id}'";
            $this->db->query($sql);
            $sql = "DELETE FROM `modx_a_flat2properties` WHERE flat_id = '{$id}'";
            $this->db->query($sql);
            $sql = "DELETE FROM `modx_a_flat2cat` WHERE flat_id = '{$id}'";
            $this->db->query($sql);
            $sql = "DELETE FROM `modx_a_flat` WHERE id = '{$id}'";
            $this->db->query($sql);
            $this->clear_dir(MODX_BASE_PATH . "assets/images/flat/" . $id . "/");
        }

        public function getNameCategory($id_category)
        {
            $res = '';

            if (is_array ($id_category) ) {
                $id_categories = implode(',', $id_category);
            } else {
                $id_categories = $id_category;
            }

            $query = "SELECT `pagetitle_{$this->lang}` AS `pagetitle` FROM `modx_site_content` WHERE `id` IN ({$id_categories})";
            $array = $this->db->makeArray($this->db->query($query));
            foreach($array as $value) {
                $res[] = $value['pagetitle'];
            }

            return implode(', ', $res);


        }

        /*************************************/
        /*** ----------  AGENTS ---------- ***/
        /*************************************/

        /**
         * Выбираем агента
         * @id  [int] $id Id агенты
         * @return [array]    массив агентов
         */
        public function getAgents($id)
        {
            $sql
                = "SELECT id, pagetitle_{$this->lang} AS pagetitle,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 8) as tv_image,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 13) as tv_city_phone,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 14) as tv_mobile_phone,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 15) as tv_email,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 16) as tv_skype
            FROM `modx_site_content` sc
            WHERE parent = 123 " . ($id != '' ? 'AND id = ' . (int)$id : '');
            return $this->db->makeArray($this->db->query($sql));
        }

        /*************************************/
        /*** ----------  Properties ---------- ***/
        /*************************************/

        /**
         * Выбираем агента
         * @id  [int] $id Id агенты
         * @return [array]    массив агентов
         */
        public function getProperties($id)
        {
            $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle FROM `modx_site_content` WHERE parent = " . $this->db->escape($id);
            return $this->db->makeArray($this->db->query($sql));
        }

        /*************************************/
        /*** ---------- REVIEWS ---------- ***/
        /*************************************/

        /**
         * Выводим отзывы
         */
        public function getReviews($param)
        {
            $sql
                = "SELECT SQL_CALC_FOUND_ROWS * FROM `modx_a_recall`
            WHERE recall_moderated = 1 and recall_page = '" . $this->modx->documentObject['id'] . "'
            ORDER BY recall_id DESC
            LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);
            return $this->db->makeArray($this->db->query($sql));
        }

        /**
         * Сохраяем отзвыв
         */
        public function setReview($review)
        {
            $sql
                = 'INSERT INTO `modx_a_recall` SET
            recall_pub_date  = NOW(),
            recall_name      = "' . $this->db->escape($review['name']) . '",
            recall_subject   = "' . $this->db->escape($review['subject']) . '",
            recall_text      = "' . $this->db->escape($review['text']) . '",
            recall_email     = "' . $this->db->escape($review['email']) . '",
            recall_page      = "' . $this->db->escape($review['page']) . '",
            recall_lang      = "' . $this->lang . '",
            recall_moderated = 0
            ';
            $this->db->query($sql);
        }

        /*************************************/
        /*** ----------  USERS  ---------- ***/
        /*************************************/

        /**
         * get info user
         *
         * @param $email
         */
        public function getUser($email)
        {
            $sql
                = 'SELECT
                  *
                  FROM `modx_web_user_attributes`
                  WHERE email = "' . $this->db->escape($email) . '" limit 1';
            return $this->db->getRow($this->db->query($sql));
        }

        /**
         * set new user
         */
        public function setUser($data)
        {
            $sql = "INSERT INTO `modx_web_users` SET username = '" . $this->db->escape($data['username']) . "', password = '" . $data['password'] . "'";
            $this->db->query($sql);
            $user_id = $this->db->getInsertId();
            $sql = "INSERT INTO `modx_web_user_attributes` SET
                        internalKey = '" . $user_id . "',
                        fullname    = '" . $this->db->escape($data['fullname']) . "',
                        email       = '" . $this->db->escape($data['email']) . "',
                        active      = '" . $this->db->escape($data['active']) . "'
                    ";
            $this->db->query($sql);

            $sql = "INSERT INTO `modx_web_groups` SET webgroup = 1, webuser = '" . $user_id . "'";
            $this->db->query($sql);

            // Добавка пользователя в мейлер
            $sql = "INSERT INTO `modx_a_mailer_users` SET user_name = '" . $this->db->escape($data['fullname']) . "', user_email = '" . $this->db->escape($data['email']) . "'";
            $this->db->query($sql);
        }

        /**
         * active user to registration
         */
        public function getUsetActive($code)
        {
            $sql = 'SELECT count(*) value FROM `modx_web_user_attributes` WHERE active = "' . $this->db->escape($code) . '"';
            $user = $this->db->getValue($this->db->query($sql));
            if ($user > 0) {
                $sql = 'UPDATE `modx_web_user_attributes` SET active = 0 WHERE active = "' . $this->db->escape($code) . '"';
                $this->db->query($sql);
                return true;
            } else {
                return false;
            }
        }

        /**
         * Change pass user
         */
        public function changePassUser($pwd, $email)
        {
            $sql = "UPDATE `modx_web_users` SET cachepwd = '" . $this->db->escape($pwd) . "' WHERE id = (SELECT internalKey FROM `modx_web_user_attributes` WHERE email = '" . $this->db->escape($email) . "')";
            $this->db->query($sql);
        }

        /**
         * Save new password
         */
        public function setPassword($cachepwd)
        {
            $sql = "UPDATE `modx_web_users` SET password = cachepwd, cachepwd = '' WHERE cachepwd = '" . $this->db->escape($cachepwd) . "' LIMIT 1";
            $this->db->query($sql);
        }

        /*************************************/
        /*** -------- APPARTAMENT -------- ***/
        /*************************************/

        /**
         * Get list appartament
         */
        public function getAppartament()
        {
            $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle, (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 6) as 'tv_preview' FROM `modx_site_content` sc WHERE parent = 165";
            return $this->db->makeArray($this->db->query($sql));
        }

        /*************************************/
        /*** ---------- BACKEND ---------- ***/
        /*************************************/

        /**
         * Вывод списка категорий
         */
        public function categories()
{
    $res = [];
    $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle, isfolder FROM `modx_site_content` WHERE parent = 5";
    $cats = $this->db->makeArray($this->db->query($sql));

    foreach ($cats as $v1) {
        if ((int)$v1['isfolder'] === 0) {
            $res[] = ["id" => $v1['id'], "pagetitle" => $v1['pagetitle']];
        } else {
            $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle, isfolder FROM `modx_site_content` WHERE parent = " . (int)$v1['id'];
            $cats2 = $this->db->makeArray($this->db->query($sql));
            foreach ($cats2 as $v2) {
                if ((int)$v2['isfolder'] === 0) {
                    $res[] = ["id" => $v2['id'], "pagetitle" => $v1['pagetitle'] . " | " . $v2['pagetitle']];
                } else {
                    $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle, isfolder FROM `modx_site_content` WHERE parent = " . (int)$v2['id'];
                    $cats3 = $this->db->makeArray($this->db->query($sql));
                    foreach ($cats3 as $v3) {
                        if ((int)$v3['isfolder'] === 0) {
                            $res[] = ["id" => $v3['id'], "pagetitle" => $v1['pagetitle'] . " | " . $v2['pagetitle'] . " | " . $v3['pagetitle']];
                        } else {
                            $sql = "SELECT id, pagetitle_{$this->lang} AS pagetitle, isfolder FROM `modx_site_content` WHERE parent = " . (int)$v3['id'];
                            $cats4 = $this->db->makeArray($this->db->query($sql));
                            foreach ($cats4 as $v4) {
                                $res[] = ["id" => $v4['id'], "pagetitle" => $v1['pagetitle'] . " | " . $v2['pagetitle'] . " | " . $v3['pagetitle'] . " | " . $v4['pagetitle']];
                            }
                        }
                    }
                }
            }
        }

    }
    return $res;
}

        /**
         * Resize image
         */
        public function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
        {
            if (!file_exists($src)) {
                return false;
            }
            $size = getimagesize($src);
            if ($size === false) {
                return false;
            }

            // Определяем исходный формат по MIME-информации, предоставленной
            // функцией getimagesize, и выбираем соответствующую формату
            // imagecreatefrom-функцию.
            $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
            $icfunc = "imagecreatefrom" . $format;
            if (!function_exists($icfunc)) {
                return false;
            }
            $x_ratio = $width / $size[0];
            $y_ratio = $height / $size[1];
            $ratio = min($x_ratio, $y_ratio);
            $use_x_ratio = ($x_ratio == $ratio);
            $new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
            $new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
            $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
            $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
            $isrc = $icfunc($src);
            $idest = imagecreatetruecolor($width, $height);
            imagefill($idest, 0, 0, $rgb);
            imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
            imagejpeg($idest, $dest, $quality);
            imagedestroy($isrc);
            imagedestroy($idest);
            return true;
        }

        /**
         * Rotate image
         *
         * @param  [type] &$image   [description]
         * @param  [type] $filename [description]
         *
         * @return [type]           [description]
         */
        public function image_fix_orientation($image, $filename)
        {
            // $exif = exif_read_data($filename);
            // if (!empty($exif['Orientation'])) {
            //     switch ($exif['Orientation']) {
            //         case 3:
            //             $image = imagerotate($image, 180, 0);
            //             break;

            //         case 6:
            //             $image = imagerotate($image, -90, 0);
            //             break;

            //         case 8:
            //             $image = imagerotate($image, 90, 0);
            //             break;
            //     }
            // }
        }


        /*************************************/
        /*** ---------- FRONEND ---------- ***/
        /*************************************/

        /*
         * Выбираем TV
         */
        public function getTv($id, $tv)
        {
            $sql = "SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE contentid = '" . $this->db->escape($id) . "' AND tmplvarid = '" . $this->db->escape($tv) . "'";
            return $this->db->getRow($this->db->query($sql));
        }

        /**
         * Список участков
         */
        public function getListLot($parent, $ignore = '')
        {
            
            $sql
                = "SELECT id, pagetitle_{$this->lang} AS pagetitle, parent, menuindex,
                (select pagetitle_{$this->lang}  from `modx_site_content` where id = sc.parent) as  parent_pagetitle,
                (select pagetitle_{$this->lang}  from `modx_site_content` where id = (select parent from `modx_site_content` where id = sc.parent))  parent_parent_pagetitle
                FROM `modx_site_content` sc
                WHERE
                    (
                        parent = " . $this->db->escape($parent) . ' OR
                        parent IN (select id from `modx_site_content` where parent = ' . $this->db->escape($parent) . ') OR
                        parent IN (select id from `modx_site_content` where parent IN (select id from `modx_site_content` where parent = ' . $this->db->escape($parent) . '))
                    ) AND
                    isfolder = 0
                ' . ($ignore != '' ? ' AND id NOT IN (' . $this->db->escape($ignore) . ')' : '') . '
                ' . ($ignore != '' ? ' AND parent NOT IN (' . $this->db->escape($ignore) . ')' : '') . '
                ORDER BY parent ASC, menuindex ASC
            ';

           

            return $this->db->makeArray($this->db->query($sql));
        }

        /**
         * Mobile Menu
         */
        public function menu()
        {
            $sql
                = "SELECT id, parent, pagetitle_{$this->lang} AS pagetitle, longtitle_{$this->lang} as longtitle FROM `modx_site_content`
            WHERE
                published = 1 AND
                deleted = 0 AND
                (
                    parent = 5 OR
                    parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5) OR
                    parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5)) OR
                    parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5))) OR
                    parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5)))) OR
                    `id` = '799'
                )
            ORDER BY menuindex ASC
            ";
            return $this->db->query($sql);
        }

        /**
         * Catalog for index page
         */
        public function index_catalog()
        {
            $sql
                = "SELECT id, pagetitle_{$this->lang} AS pagetitle,parent,
            (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 6) as image
            FROM `modx_site_content` sc
            WHERE
                id != 6 AND
                (
                    parent = 5 OR
                    parent IN (select id from `modx_site_content` where parent = 5)
                )
                ORDER BY menuindex
            ";
            return $this->db->makeArray($this->db->query($sql));
        }

//        /**
//         * Вычиляем стоимость в разынх валютах
//         * @param  [type] $price  [description]
//         * @param  [type] $course [description]
//         * @return [type]         [description]
//         */
//        public function getPrice($price, $course)
//        {
//            switch ($course) {
//                case 'USD':
//                    break;
//                case 'UAH':
//                    $price = $price / $this->modx->config['course_uah'];
//                    break;
//                case 'EURO':
//                    $price = $price * $this->modx->config['course_euro'];
//                    break;
//            }
//            return $price;
//        }

        /**
         *  getPrice
         *  получить цену в пересчете на выбранную валюту по курсу из настроек
         */
        function getPrice($amount, $to = "USD")
        {
            $curr = [];
            if ($amount != 0) {
                $codes = explode("|", $this->modx->config['shop_curr_code']);
                $rates = explode("|", $this->modx->config['shop_curr_rate']);

                foreach ($codes as $key => $value) {
                    if ($value != '') {
                        $curr[$value] = $rates[$key];
                    }
                }

                $res = round($amount * $curr[$to], 2);
            }
            return $res;
        }

        function priceToBase($amount, $from = "USD")
        {
            $amount = (float)$amount;
            if ($amount == 0) {
                return 0;
            }
            $codes = explode("|", $this->modx->config['shop_curr_code']);
            $rates = explode("|", $this->modx->config['shop_curr_rate']);
            $rate = 1;
            foreach ($codes as $key => $value) {
                if ($value === $from && isset($rates[$key]) && (float)$rates[$key] != 0) {
                    $rate = (float)$rates[$key];
                    break;
                }
            }
            return $amount / $rate;
        }

        /**
         * get Page
         */
        public function getPage($id)
        {
            $sql = "SELECT *, (select value from `modx_site_tmplvar_contentvalues` WHERE contentid = sc.id AND tmplvarid = 6) as 'image' FROM `modx_site_content` sc WHERE id = '" . $this->db->escape($id) . "'";
            $page = $this->db->getRow($this->db->query($sql));
            $page['date'] = date("Y-m-d H:i:s", $page['pub_date']);
            return $page;
        }

        /**
         * setpage
         */
        public function setPage($id, $param)
        {
            if (count($param) > 0) {
                foreach ($param as $key => $value)
                    $set[] = "`" . $this->db->escape($key) . "` = '" . $this->db->escape($value) . "'";
            }
            $sql = "UPDATE `modx_site_content` SET " . implode(",", $set) . " WHERE id = '" . $this->db->escape($id) . "'";
            $this->db->query($sql);
        }

        function yandexExelSave($type = '')
        {
            $property_type = 1;
            $cat_id = 0;
            $currency = explode("|", $this->modx->config["shop_curr_rate"]);
            $status = "1,2";//продажа и аренда

            $where = [];
            $param['limit'] = 10000;
            $param['offset'] = 0;
            $where[] = " exel_active = 1";
            // $where[] = " status IN (4) ";
            //$where[] = " status IN (" . $this->db->escape($status) . ") ";

            return $this->getFlatsXML($param, $where, 'ru');
            // switch ($type) {
            //     case 'lun':

            //        break;
            // case '057':
            //  return $this->createXMLLun($flats);
            // break;
            // case 'ci.ua':
            //  return $this->createXMLLun($flats);
            // break;
            // }
        }

        function yandexXmlSave($type)
        {
            $property_type = 1;
            $cat_id = 0;
            $currency = explode("|", $this->modx->config["shop_curr_rate"]);
            $status = "1,2";//продажа и аренда

            $where = [];
            $param['limit'] = 10000;
            $param['offset'] = 0;
            $where[] = " xml_active = 1";
            // $where[] = " status IN (4) ";
            $where[] = " status IN (" . $this->db->escape($status) . ") ";

            $flats = $this->getFlatsXML($param, $where, 'ua');
            // switch ($type) {
            //     case 'lun':
            return $this->createXMLLun($flats, $type);
            //        break;
            // case '057':
            //  return $this->createXMLLun($flats);
            // break;
            // case 'ci.ua':
            //  return $this->createXMLLun($flats);
            // break;
            // }
        }

        function yandexXmlSaveLun($type)
        {
            $property_type = 1;
            $cat_id = 0;
            $currency = explode("|", $this->modx->config["shop_curr_rate"]);
            $status = "1,2";//продажа и аренда

            $where = [];
            $param['limit'] = 10000;
            $param['offset'] = 0;
            $where[] = " xml_active_lun = 1";
            // $where[] = " status IN (4) ";
            $where[] = " status IN (" . $this->db->escape($status) . ") ";

            $flats = $this->getFlatsXML($param, $where, 'ua');
            // switch ($type) {
            //     case 'lun':
            return $this->createXMLLun($flats, $type);
            //        break;
            // case '057':
            //  return $this->createXMLLun($flats);
            // break;
            // case 'ci.ua':
            //  return $this->createXMLLun($flats);
            // break;
            // }
        }

        function yandexXmlSaveVhn($type)
        {
            $property_type = 1;
            $cat_id = 0;
            $currency = explode("|", $this->modx->config["shop_curr_rate"]);
            $status = "1,2";//продажа и аренда

            $where = [];
            $param['limit'] = 10000;
            $param['offset'] = 0;
            $where[] = " xml_active_vhn = 1";
            // $where[] = " status IN (4) ";
            $where[] = " status IN (" . $this->db->escape($status) . ") ";
            $flats = $this->getFlatsXML($param, $where, 'ua');
            // switch ($type) {
            //     case 'lun':
            return $this->createXMLLun($flats, $type);
            //        break;
            // case '057':
            //  return $this->createXMLLun($flats);
            // break;
            // case 'ci.ua':
            //  return $this->createXMLLun($flats);
            // break;
            // }
        }

        function plektanXml()
        {
            $status = "1,2";//продажа и аренда
            $where = [];
            $param['limit'] = 10000;
            $param['offset'] = 0;
            $where[] = " xml_active_plektan = 1";
            $where[] = " status IN (" . $this->db->escape($status) . ") ";
            $flats = $this->getFlatsXML($param, $where, 'ua');
            return $this->createPlectanXml($flats);
        }

        public function getCategory_one_dept()
        {
            $cat = $this->modx->getDocumentChildren(5);
            foreach ($cat as $value) {
        
                $res[] = [
                    'id' => (int)$value['id'],
                    'name' => $value['pagetitle_'.$this->lang]
                ];
            }
            return $res;
        }

        public function getIdCategorys($id)
        {
            //Если передан массив id-ков
            if (is_array($id) && count($id) > 0) {
                foreach ($id as $value) {
                    $category_id = $this->modx->getChildIds((int)$value);
                    if (is_array($category_id) && count($category_id) > 0) {
                        foreach ($category_id as $k => $v) {
                            $res[] = "'{$v}'";
                        }
                    } else {
                        $res[] = "'{$value}'";
                    }
                }
            }
            return $res;
        }

        public function getCategory_two_dept($in)
        {
            $res = [];
            $ids = explode(',', $in);
            if (!is_array($ids)) {
                $ids = $in;
            }

            foreach($ids as $id) {
                $cat = $this->modx->getDocumentChildren($id);
                foreach ($cat as $value) {

                    $res[] = [
                        'id' => (int)$value['id'],
                        'name' => $value['pagetitle_'.$this->lang]
                    ];
                }
            }

            return $res;
        }

        public function createXMLLunOLD($flats, $type_xml)
        {
            $offers = '';
            if (count($flats) > 0) {
                foreach ($flats as $value) {
                    switch ($value['non_admin_sub_locality']) {
                        case "Алексеевка":
                            $locality = "Алексеевка";
                            break;
                        case "Павлово Поле":
                            $locality = "П. Поле";
                            break;
                        case "Центр":
                            $locality = "Центр";
                            break;
                        case "Холодная Гора ":
                            $locality = "Х. Гора";
                            break;
                        case "м. Наукова":
                            $locality = "М. Научная";
                            break;
                        default:
                            $locality = $value['non_admin_sub_locality'];
                            break;
                    }
                    switch ($value['status_vhn']) {
                        case '0':
                            $status_vhn = '';
                            break;
                        case '1':
                            $status_vhn = 'Платный эксклюзив';
                            break;
                        case '2':
                            $status_vhn = 'Бесплатный эксклюзив';
                            break;
                        case '3':
                            $status_vhn = 'Болванка';
                            break;
                        case '4':
                            $status_vhn = 'Реклама реального объекта';
                            break;

                    }
                    switch ($value['status']) {
                        case '1':
                            $status = 'продажа';
                            break;
                        case '2':
                            $status = 'аренда';
                            break;

                    }
                    switch ($value['type']) {
                        case '1':
                            $type = 'Квартира';
                            break;
                        case '2':
                            $type = 'Дом';
                            break;
                        case '3':
                            $type = 'Участок';
                            break;
                        case '4':
                            $type = 'Аренда';
                            break;
                        case '5':
                            $type = 'Бизнес';
                            break;
                        case '6':
                            $type = 'Зарубежная недвижимость';
                            break;
                    }

                    $value['price'] = $this->getPrice($value['price']);
                    $value['url'] = $this->modx->makeUrl(134, '', '', 'full') . $value['url'] . '/';

                    $images = $this->getImagesFlat($value['id']);
                    $list_images = '';
                    if (is_array($images) && count($images) > 0) {
                        foreach ($images as $image) {
                            // $img = '<image>'.$this->config['site_url'].$this->modx->runSnippet("R", array("img" => "assets/images/flat/".$value['id']."/".$image['image'], "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/".$value['id'])).'</image>';
                            $list_images
                                .= '
                            <image>' . str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'https://toprealtor.com.ua', $this->modx->config['site_url']) . "assets/images/flat/" . $value['id'] . "/" . $image['image'] . '</image>';
                        }
                    }
                    //Телефоны
                    $phones = explode(',', $value['agent_phone']);
                    $list_phones = '';
                    if (is_array($phones) && count($phones) > 0) {
                        foreach ($phones as $phone) {
                            $list_phones
                                .= '
                            <phone>' . $this->number($phone) . '</phone>';
                        }
                    }
                    //latitude, longitude
                    $coordArr = explode(", ", $value['coord']);
                    $description = $string = str_replace('"', '&quot;', $description);
                    $description = $string = str_replace('>', '&gt;', $description);
                    $description = $string = str_replace('<', '&lt;', $description);

                    $value['url'] = str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'https://toprealtor.com.ua', $value['url']);

                    if ($type_xml == 'vhn') {
                        $description = $string = str_replace('&', '&amp;', $value['description']);
                        // living
                        if ($value['type'] == 1 || $value['type'] == 2 || $value['type'] == 3) {
                            //Если тип объекта Дом или Участок, добавляем площадь участка
                            if ($value['type'] == 2 || $value['type'] == 3) {
                                $lot_area =
                                    "<lot-area>
                                        <value>" . $value['h_area'] . "</value>
                                        <unit>соток</unit>
                                    </lot-area>";
                            } else {
                                $lot_area = '';
                            }
                            $offers .= '<offer internal-id="' . $value['id'] . '">
                        <title>' . $value['name'] . '</title>
                                     <type>' . $status . '</type>
                                     <property-type>жилая</property-type>
                                     <category>' . $type . '</category>
                                     <status>' . $status_vhn . '</status>
                                     <url>' . trim(preg_replace('/\s+/', ' ', $value['url'])) . '</url>
                                     <creation-date>' . date('c', strtotime($value['created_at'])) . '</creation-date>
                                     <last-update-date>' . date('c', strtotime($value['updated_at'])) . '</last-update-date>
                                     <location.sub-locality-name>' . $locality . '</location.sub-locality-name>
                                     <location.non-admin-sub-locality>' . $locality . '</location.non-admin-sub-locality>
                                     <deal-status></deal-status>
                                     <location>
                                        <country>' . $value['country'] . '</country>
                                        <region>' . $value['region'] . '</region>
                                        <locality-name>' . $value['locality_name'] . '</locality-name>
                                        <district>' . $value['non_admin_sub_locality'] . '</district>  
                                        <address>' . $value['street_xml'] . '</address>
                                        <metro><name>' . $value['metro'] . '</name></metro>
                                        <latitude>' . $coordArr[0] . '</latitude>
                                        <longitude>' . $coordArr[1] . '</longitude>
                                     </location>
                                     <rooms>' . $value['rooms'] . '</rooms>
                                     <rooms-offered>' . $value['rooms'] . '</rooms-offered>
                                     <floor>' . $value['floor'] . '</floor>
                                     <floors-total>' . $value['floors_total'] . '</floors-total>
                                     <price>
                                        <value>' . $value['price'] . '</value>
                                        <currency>USD</currency>
                                     </price>
                                     <sales-agent>
                                    ' . $list_phones . '
                                        <name>' . $value['agent_name'] . '</name>
                                        <category>агентство</category>
                                     </sales-agent>
                                       ' . $list_images . '     
                                     <description>' . strip_tags($description) . '</description>
                                     <area>
                                      <value>' . $value['area'] . '</value>
                                      <unit>кв. м</unit>
                                     </area>
                                    ' . $lot_area . '
                                </offer>';
                            // коммерческая
                        } elseif ($value['type'] == 5) {
                            switch ($value['rooms_type_vhn']) {
                                case '1':
                                    $rooms_type_vhn = 'ресторан';
                                    break;
                                case '2':
                                    $rooms_type_vhn = 'гостинница';
                                    break;
                                case '3':
                                    $rooms_type_vhn = 'бар';
                                    break;
                                case '4':
                                    $rooms_type_vhn = 'парикмахерская';
                                    break;
                                case '5':
                                    $rooms_type_vhn = 'дом пристарелых';
                                    break;
                                case '6':
                                    $rooms_type_vhn = 'спортивный зал';
                                    break;
                                case '7':
                                    $rooms_type_vhn = 'продуктовый магазин';
                                    break;
                                case '8':
                                    $rooms_type_vhn = 'киоск';
                                    break;
                                case '9':
                                    $rooms_type_vhn = 'мастерская';
                                    break;
                                case '10':
                                    $rooms_type_vhn = 'объект сельскохозяйственного назначения';
                                    break;
                                case '11':
                                    $rooms_type_vhn = 'помещение промышленного назначения';
                                    break;
                                case '12':
                                    $rooms_type_vhn = 'больница';
                                    break;
                                case '13':
                                    $rooms_type_vhn = 'салон красоты';
                                    break;
                                case '14':
                                    $rooms_type_vhn = 'спортклуб';
                                    break;
                                case '15':
                                    $rooms_type_vhn = 'закусочная';
                                    break;
                                case '16':
                                    $rooms_type_vhn = 'павильон';
                                    break;
                                case '17':
                                    $rooms_type_vhn = 'столовая';
                                    break;
                                case '18':
                                    $rooms_type_vhn = 'кондитерский цех';
                                    break;
                                case '19':
                                    $rooms_type_vhn = 'магазин';
                                    break;
                                case '20':
                                    $rooms_type_vhn = 'склад';
                                    break;
                                case '21':
                                    $rooms_type_vhn = 'офис';
                                    break;
                                default:
                                    $rooms_type_vhn = 'помещения';
                                    break;
                            }
                            $offers .= '<offer internal-id="' . $value['id'] . '">
                        <title>' . $value['name'] . '</title>
                                     <type>' . $status . '</type>
                                     <category>' . $rooms_type_vhn . '</category>
                                     <status>' . $status_vhn . '</status>
                                     <url>' . trim(preg_replace('/\s+/', ' ', $value['url'])) . '</url>
                                     <creation-date>' . date('c', strtotime($value['created_at'])) . '</creation-date>
                                     <last-update-date>' . date('c', strtotime($value['updated_at'])) . '</last-update-date>
                                     <location.sub-locality-name>' . $value['non_admin_sub_locality'] . '</location.sub-locality-name>
                                     <location.non-admin-sub-locality>' . $locality . '</location.non-admin-sub-locality>
                                     <deal-status></deal-status>
                                     <location>
                                        <country>' . $value['country'] . '</country>
                                        <region>' . $value['region'] . '</region>
                                        <locality-name>' . $value['locality_name'] . '</locality-name>
                                        <district>' . $value['non_admin_sub_locality'] . '</district>  
                                        <address>' . $value['street_xml'] . '</address>
                                        <apartment>' . $value['house_number_xml'] . '</apartment>
                                        <metro><name>' . $value['metro'] . '</name></metro>
                                     </location>
                                     <price>
                                        <value>' . $value['price'] . '</value>
                                        <currency>USD</currency>
                                     </price>
                                     <sales-agent>
                                        <phone>' . $this->number($value['agent_phone']) . '</phone>
                                        <name>' . $value['agent_name'] . '</name>
                                        <category>агентство</category>
                                     </sales-agent>
                                       ' . $list_images . '     
                                     <description>' . $description . '</description>
                                     <area>
                                      <value>' . $value['area'] . '</value>
                                      <unit>кв. м</unit>
                                     </area>
                                </offer>';
                        }

                    } elseif ($type_xml == 'lun') {
                        $offers .= '<offer internal-id="' . $value['id'] . '">
                        <title>' . $value['name'] . '</title>
                                     <type>' . $status . '</type>
                                     <property-type>' . $type . '</property-type>
                                     <url>' . trim(preg_replace('/\s+/', ' ', $value['url'])) . '</url>
                                     <creation-date>' . date('c', strtotime($value['created_at'])) . '</creation-date>
                                     <location>
                                        <country>' . $value['country'] . '</country>
                                        <region>' . $value['region'] . '</region>
                                        <locality-name>' . $value['locality_name'] . '</locality-name>
                                        <district>' . $value['non_admin_sub_locality'] . '</district>  
                                        <address>' . $value['street_xml'] . '</address>
                                        <apartment>' . $value['house_number_xml'] . '</apartment>
                                        <metro><name>' . $value['metro'] . '</name></metro>
                                     </location>
                                                                         <rooms>' . $value['rooms'] . '</rooms>
                                     <floor>' . $value['floor'] . '</floor>
                                     <floors-total>' . $value['floors_total'] . '</floors-total>
                                     <price>
                                        <value>' . $value['price'] . '</value>
                                        <currency>USD</currency>
                                     </price>
                                     <sales-agent>
                                        <phone>' . $this->number($value['agent_phone']) . '</phone>
                                        <name>' . $value['agent_name'] . '</name>
                                     </sales-agent>
                                       ' . $list_images . '     
                                     <description>' . $description . '</description>
                                     <area>
                                      <value>' . $value['area'] . '</value>
                                      <unit>кв. м</unit>
                                     </area>
                                </offer>';
                    } else {

                        $offers .= '<offer internal-id="' . $value['id'] . '">
                        <title>' . $value['name'] . '</title>
                                     <type>' . $status . '</type>
                                     <property-type>' . $type . '</property-type>
                                     <url>' . trim(preg_replace('/\s+/', ' ', $value['url'])) . '</url>
                                     <creation-date>' . date('c', strtotime($value['created_at'])) . '</creation-date>
                                     <location>
                                        <country>' . $value['country'] . '</country>
                                        <region>' . $value['region'] . '</region>
                                        <locality-name>' . $value['locality_name'] . '</locality-name>
                                        <district>' . $value['non_admin_sub_locality'] . '</district>  
                                        <address>' . $value['street_xml'] . '</address>
                                        <apartment>' . $value['house_number_xml'] . '</apartment>
                                     </location>
                                                                         <rooms>' . $value['rooms'] . '</rooms>
                                     <floor>' . $value['floor'] . '</floor>
                                     <floors-total>' . $value['floors_total'] . '</floors-total>
                                     <price>
                                        <value>' . $value['price'] . '</value>
                                        <currency>USD</currency>
                                     </price>
                                     <sales-agent>
                                        <phone>' . $this->number($value['agent_phone']) . '</phone>
                                        <name>' . $value['agent_name'] . '</name>
                                     </sales-agent>
                                       ' . $list_images . '     
                                     <description>' . $description . '</description>
                                     <metro>' . $value['metro'] . '</metro>
                                     <area>
                                      <value>' . $value['area'] . '</value>
                                      <unit>кв. м</unit>
                                     </area>
                                </offer>';

                    }

                }


                $result
                    = '<?xml version="1.0" encoding="UTF-8"?> 
                        <realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">
                          <generation-date>' . date('c') . '</generation-date>
                              ' . $offers . '
                        </realty-feed>';
            }
            return $result;
        }


        public function createXMLLun($flats, $type_xml)
        {
            $offers = '';

            if (!is_array($flats) || count($flats) === 0) {
                return '<?xml version="1.0" encoding="UTF-8"?> 
                    <realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">
                        <generation-date>' . date('c') . '</generation-date>
                    </realty-feed>';
            }

            // Хелперы (PHP 5.4 ОК)
            $xml = function ($s) {
                return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
            };
            $num = function ($v) {
                return is_numeric($v) ? $v : '';
            };
            $normalizeUrl = function ($url) {
                return str_replace(
                    'http//:/hosting/toprealtor/public_html/assets/modules',
                    'https://toprealtor.com.ua',
                    $url
                );
            };

            $siteUrl = str_replace(
                'http//:/hosting/toprealtor/public_html/assets/modules',
                'https://toprealtor.com.ua',
                $this->modx->config['site_url']
            );

            foreach ($flats as $value) {
                // ---- Локализация района
                $localitySrc = isset($value['non_admin_sub_locality']) ? trim($value['non_admin_sub_locality']) : '';
                switch ($localitySrc) {
                    case 'Алексеевка':     $locality = 'Алексеевка'; break;
                    case 'Павлово Поле':   $locality = 'П. Поле';    break;
                    case 'Центр':          $locality = 'Центр';      break;
                    case 'Холодная Гора':  $locality = 'Х. Гора';    break;
                    case 'м. Наукова':     $locality = 'М. Научная'; break;
                    default:               $locality = $localitySrc; break;
                }

                // ---- Статусы
                $status_vhn = '';
                if (isset($value['status_vhn'])) {
                    switch ((string)$value['status_vhn']) {
                        case '1': $status_vhn = 'Платный эксклюзив'; break;
                        case '2': $status_vhn = 'Бесплатный эксклюзив'; break;
                        case '3': $status_vhn = 'Болванка'; break;
                        case '4': $status_vhn = 'Реклама реального объекта'; break;
                    }
                }

                $status = '';
                $status_src = isset($value['status']) ? (string)$value['status'] : '';
                switch ($status_src) {
                    case '1': $status = 'продажа'; break;
                    case '2': $status = 'аренда';  break;
                }

                $type = '';
                $type_src = isset($value['type']) ? (string)$value['type'] : '';
                switch ($type_src) {
                    case '1': $type = 'Квартира';                break;
                    case '2': $type = 'Дом';                     break;
                    case '3': $type = 'Участок';                 break;
                    case '4': $type = 'Аренда';                  break;
                    case '5': $type = 'Бизнес';                  break;
                    case '6': $type = 'Зарубежная недвижимость'; break;
                }

                // ---- Цена и URL
                $value['price'] = isset($value['price']) ? $this->getPrice($value['price']) : '';
                $value_url_part = isset($value['url']) ? $value['url'] : '';
                $value['url'] = $this->modx->makeUrl(134, '', '', 'full') . $value_url_part . '/';
                $value['url'] = $normalizeUrl(trim(preg_replace('/\s+/', ' ', $value['url'])));

                // ---- Картинки
                $list_images = '';
                $images = $this->getImagesFlat($value['id']);
                if (is_array($images) && count($images) > 0) {
                    foreach ($images as $image) {
                        $img_name = isset($image['image']) ? $image['image'] : '';
                        $list_images .= "\n        <image>" . $siteUrl . 'assets/images/flat/' . $value['id'] . '/' . $this->xmlSafe($img_name) . "</image>";
                    }
                }

                // ---- Телефоны
                $list_phones = '';
                $agent_phone_raw = isset($value['agent_phone']) ? (string)$value['agent_phone'] : '';
                $phones = array_map('trim', array_filter(explode(',', $agent_phone_raw)));
                if (count($phones) > 0) {
                    foreach ($phones as $phone) {
                        $list_phones .= "\n            <phone>" . $this->number($phone) . "</phone>";
                    }
                }

                // ---- Координаты
                $coordArr = array('', '');
                if (!empty($value['coord'])) {
                    if (strpos($value['coord'], ',') !== false) {
                        $coordArr = array_map('trim', explode(',', $value['coord']));
                    } elseif (strpos($value['coord'], ' ') !== false) {
                        $coordArr = array_map('trim', explode(' ', $value['coord']));
                    }
                    if (count($coordArr) < 2) $coordArr = array('', '');
                }

                // ---- Описание
                $descRaw = isset($value['description']) ? (string)$value['description'] : '';
                $descriptionEsc = $xml($descRaw);

                // ---- Тип выгрузки
                if ($type_xml === 'vhn') {

                    // жилая (type 1/2/3)
                    if (in_array($type_src, array('1','2','3'), true)) {
                        // участок для Дом/Участок
                        $lot_area = '';
                        if (in_array($type_src, array('2','3'), true) && !empty($value['h_area'])) {
                            $lot_area = "
                            <lot-area>
                                <value>" . $xml($value['h_area']) . "</value>
                                <unit>соток</unit>
                            </lot-area>";
                        }

                        $offers .= '<offer internal-id="' . $xml($value['id']) . '">
                            <title>' . $xml(isset($value['name']) ? $value['name'] : '') . '</title>
                            <type>' . $xml($status) . '</type>
                            <property-type>жилая</property-type>
                            <category>' . $xml($type) . '</category>
                            <status>' . $xml($status_vhn) . '</status>
                            <url>' . $xml($value['url']) . '</url>
                            <creation-date>' . date('c', strtotime(isset($value['created_at']) ? $value['created_at'] : 'now')) . '</creation-date>
                            <last-update-date>' . date('c', strtotime(isset($value['updated_at']) ? $value['updated_at'] : 'now')) . '</last-update-date>
                            <location.sub-locality-name>' . $xml($locality) . '</location.sub-locality-name>
                            <location.non-admin-sub-locality>' . $xml($locality) . '</location.non-admin-sub-locality>
                            <deal-status></deal-status>
                            <location>
                                <country>' . $xml(isset($value['country']) ? $value['country'] : '') . '</country>
                                <region>' . $xml(isset($value['region']) ? $value['region'] : '') . '</region>
                                <locality-name>' . $xml(isset($value['locality_name']) ? $value['locality_name'] : '') . '</locality-name>
                                <district>' . $xml(isset($value['non_admin_sub_locality']) ? $value['non_admin_sub_locality'] : '') . '</district>
                                <address>' . $xml(isset($value['street_xml']) ? $value['street_xml'] : '') . '</address>
                                <metro><name>' . $xml(isset($value['metro']) ? $value['metro'] : '') . '</name></metro>
                                <latitude>' . $xml($coordArr[0]) . '</latitude>
                                <longitude>' . $xml($coordArr[1]) . '</longitude>
                            </location>
                            <rooms>' . $xml(isset($value['rooms']) ? $value['rooms'] : '') . '</rooms>
                            <rooms-offered>' . $xml(isset($value['rooms']) ? $value['rooms'] : '') . '</rooms-offered>
                            <floor>' . $xml(isset($value['floor']) ? $value['floor'] : '') . '</floor>
                            <floors-total>' . $xml(isset($value['floors_total']) ? $value['floors_total'] : '') . '</floors-total>
                            <price>
                                <value>' . $xml($value['price']) . '</value>
                                <currency>USD</currency>
                            </price>
                            <sales-agent>' . $list_phones . '
                                <name>' . $xml(isset($value['agent_name']) ? $value['agent_name'] : '') . '</name>
                                <category>агентство</category>
                            </sales-agent>' . $list_images . '     
                            <description>' . strip_tags($descriptionEsc) . '</description>
                            <area>
                                <value>' . $xml(isset($value['area']) ? $value['area'] : '') . '</value>
                                <unit>кв. м</unit>
                            </area>' . $lot_area . '
                        </offer>';

                    // коммерческая (type = 5)
                    } elseif ($type_src === '5') {
                        $rooms_type_src = isset($value['rooms_type_vhn']) ? (string)$value['rooms_type_vhn'] : '';
                        switch ($rooms_type_src) {
                            case '1':  $rooms_type_vhn = 'ресторан'; break;
                            case '2':  $rooms_type_vhn = 'гостинница'; break;
                            case '3':  $rooms_type_vhn = 'бар'; break;
                            case '4':  $rooms_type_vhn = 'парикмахерская'; break;
                            case '5':  $rooms_type_vhn = 'дом пристарелых'; break;
                            case '6':  $rooms_type_vhn = 'спортивный зал'; break;
                            case '7':  $rooms_type_vhn = 'продуктовый магазин'; break;
                            case '8':  $rooms_type_vhn = 'киоск'; break;
                            case '9':  $rooms_type_vhn = 'мастерская'; break;
                            case '10': $rooms_type_vhn = 'объект сельскохозяйственного назначения'; break;
                            case '11': $rooms_type_vhn = 'помещение промышленного назначения'; break;
                            case '12': $rooms_type_vhn = 'больница'; break;
                            case '13': $rooms_type_vhn = 'салон красоты'; break;
                            case '14': $rooms_type_vhn = 'спортклуб'; break;
                            case '15': $rooms_type_vhn = 'закусочная'; break;
                            case '16': $rooms_type_vhn = 'павильон'; break;
                            case '17': $rooms_type_vhn = 'столовая'; break;
                            case '18': $rooms_type_vhn = 'кондитерский цех'; break;
                            case '19': $rooms_type_vhn = 'магазин'; break;
                            case '20': $rooms_type_vhn = 'склад'; break;
                            case '21': $rooms_type_vhn = 'офис'; break;
                            default:   $rooms_type_vhn = 'помещения'; break;
                        }

                        $offers .= '<offer internal-id="' . $xml($value['id']) . '">
                            <title>' . $xml(isset($value['name']) ? $value['name'] : '') . '</title>
                            <type>' . $xml($status) . '</type>
                            <category>' . $xml($rooms_type_vhn) . '</category>
                            <status>' . $xml($status_vhn) . '</status>
                            <url>' . $xml($value['url']) . '</url>
                            <creation-date>' . date('c', strtotime(isset($value['created_at']) ? $value['created_at'] : 'now')) . '</creation-date>
                            <last-update-date>' . date('c', strtotime(isset($value['updated_at']) ? $value['updated_at'] : 'now')) . '</last-update-date>
                            <location.sub-locality-name>' . $xml(isset($value['non_admin_sub_locality']) ? $value['non_admin_sub_locality'] : '') . '</location.sub-locality-name>
                            <location.non-admin-sub-locality>' . $xml($locality) . '</location.non-admin-sub-locality>
                            <deal-status></deal-status>
                            <location>
                                <country>' . $xml(isset($value['country']) ? $value['country'] : '') . '</country>
                                <region>' . $xml(isset($value['region']) ? $value['region'] : '') . '</region>
                                <locality-name>' . $xml(isset($value['locality_name']) ? $value['locality_name'] : '') . '</locality-name>
                                <district>' . $xml(isset($value['non_admin_sub_locality']) ? $value['non_admin_sub_locality'] : '') . '</district>
                                <address>' . $xml(isset($value['street_xml']) ? $value['street_xml'] : '') . '</address>
                                <apartment>' . $xml(isset($value['house_number_xml']) ? $value['house_number_xml'] : '') . '</apartment>
                                <metro><name>' . $xml(isset($value['metro']) ? $value['metro'] : '') . '</name></metro>
                            </location>
                            <price>
                                <value>' . $xml($value['price']) . '</value>
                                <currency>USD</currency>
                            </price>
                            <sales-agent>
                                <phone>' . $this->number($agent_phone_raw) . '</phone>
                                <name>' . $xml(isset($value['agent_name']) ? $value['agent_name'] : '') . '</name>
                                <category>агентство</category>
                            </sales-agent>' . $list_images . '     
                            <description>' . $descriptionEsc . '</description>
                            <area>
                                <value>' . $xml(isset($value['area']) ? $value['area'] : '') . '</value>
                                <unit>кв. м</unit>
                            </area>
                        </offer>';

                    }

                } elseif ($type_xml === 'lun') {

                    $offers .= '<offer internal-id="' . $xml($value['id']) . '">
                        <title>' . $xml(isset($value['name']) ? $value['name'] : '') . '</title>
                        <type>' . $xml($status) . '</type>
                        <property-type>' . $xml($type) . '</property-type>
                        <url>' . $xml($value['url']) . '</url>
                        <creation-date>' . date('c', strtotime(isset($value['created_at']) ? $value['created_at'] : 'now')) . '</creation-date>
                        <location>
                            <country>' . $xml(isset($value['country']) ? $value['country'] : '') . '</country>
                            <region>' . $xml(isset($value['region']) ? $value['region'] : '') . '</region>
                            <locality-name>' . $xml(isset($value['locality_name']) ? $value['locality_name'] : '') . '</locality-name>
                            <district>' . $xml(isset($value['non_admin_sub_locality']) ? $value['non_admin_sub_locality'] : '') . '</district>
                            <address>' . $xml(isset($value['street_xml']) ? $value['street_xml'] : '') . '</address>
                            <apartment>' . $xml(isset($value['house_number_xml']) ? $value['house_number_xml'] : '') . '</apartment>
                            <metro><name>' . $xml(isset($value['metro']) ? $value['metro'] : '') . '</name></metro>
                        </location>
                        <rooms>' . $xml(isset($value['rooms']) ? $value['rooms'] : '') . '</rooms>
                        <floor>' . $xml(isset($value['floor']) ? $value['floor'] : '') . '</floor>
                        <floors-total>' . $xml(isset($value['floors_total']) ? $value['floors_total'] : '') . '</floors-total>
                        <price>
                            <value>' . $xml($value['price']) . '</value>
                            <currency>USD</currency>
                        </price>
                        <sales-agent>
                            <phone>' . $this->number($agent_phone_raw) . '</phone>
                            <name>' . $xml(isset($value['agent_name']) ? $value['agent_name'] : '') . '</name>
                        </sales-agent>' . $list_images . '     
                        <description>' . $descriptionEsc . '</description>
                        <area>
                            <value>' . $xml(isset($value['area']) ? $value['area'] : '') . '</value>
                            <unit>кв. м</unit>
                        </area>
                    </offer>';

                } else {
                    // Дефолтная ветка
                    $offers .= '<offer internal-id="' . $xml($value['id']) . '">
                        <title>' . $xml(isset($value['name']) ? $value['name'] : '') . '</title>
                        <type>' . $xml($status) . '</type>
                        <property-type>' . $xml($type) . '</property-type>
                        <url>' . $xml($value['url']) . '</url>
                        <creation-date>' . date('c', strtotime(isset($value['created_at']) ? $value['created_at'] : 'now')) . '</creation-date>
                        <location>
                            <country>' . $xml(isset($value['country']) ? $value['country'] : '') . '</country>
                            <region>' . $xml(isset($value['region']) ? $value['region'] : '') . '</region>
                            <locality-name>' . $xml(isset($value['locality_name']) ? $value['locality_name'] : '') . '</locality-name>
                            <district>' . $xml(isset($value['non_admin_sub_locality']) ? $value['non_admin_sub_locality'] : '') . '</district>
                            <address>' . $xml(isset($value['street_xml']) ? $value['street_xml'] : '') . '</address>
                            <apartment>' . $xml(isset($value['house_number_xml']) ? $value['house_number_xml'] : '') . '</apartment>
                        </location>
                        <rooms>' . $xml(isset($value['rooms']) ? $value['rooms'] : '') . '</rooms>
                        <floor>' . $xml(isset($value['floor']) ? $value['floor'] : '') . '</floor>
                        <floors-total>' . $xml(isset($value['floors_total']) ? $value['floors_total'] : '') . '</floors-total>
                        <price>
                            <value>' . $xml($value['price']) . '</value>
                            <currency>USD</currency>
                        </price>
                        <sales-agent>
                            <phone>' . $this->number($agent_phone_raw) . '</phone>
                            <name>' . $xml(isset($value['agent_name']) ? $value['agent_name'] : '') . '</name>
                        </sales-agent>' . $list_images . '     
                        <description>' . $descriptionEsc . '</description>
                        <metro>' . $xml(isset($value['metro']) ? $value['metro'] : '') . '</metro>
                        <area>
                            <value>' . $xml(isset($value['area']) ? $value['area'] : '') . '</value>
                            <unit>кв. м</unit>
                        </area>
                    </offer>';
                }
            }

            $result = '<?xml version="1.0" encoding="UTF-8"?> 
                <realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">
                    <generation-date>' . date('c') . '</generation-date>'
                    . $offers .
                "\n</realty-feed>";

            return $result;
        }

        /**
         * Безопасный helper для имени файла/строк в XML
         */
        private function xmlSafe($s) {
            return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
        }



        public function createPlectanXml($flats)
        {
            $offers = '';

            if (!is_array($flats) || count($flats) === 0) {
                return '<?xml version="1.0" encoding="UTF-8"?>
        <realty-feed>
            <generation-date>' . date('c') . '</generation-date>
        </realty-feed>';
            }

            // --- helpers ---
            $xml = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
            $normalizeUrl = function ($url) {
                return str_replace(
                    'http//:/hosting/toprealtor/public_html/assets/modules',
                    'https://toprealtor.com.ua',
                    $url
                );
            };
            $siteUrl = $normalizeUrl($this->modx->config['site_url']);

            foreach ($flats as $value) {
                // ---- type / property_type / category
                $type = '';
                switch (isset($value['status']) ? (string)$value['status'] : '') {
                    case '1': $type = 'sell';       break;
                    case '2': $type = 'rent_long';  break;
                }

                $property_type = '';
                $category = '';
                $type_src = isset($value['type']) ? (string)$value['type'] : '';
                switch ($type_src) {
                    case '1':
                        $property_type = 'flat';   $category = 'isolated'; break;
                    case '2':
                        $property_type = 'house';  $category = 'single';   break;
                    case '3':
                        if (isset($value['cat_name']) && $value['cat_name'] === 'Участки под коммерцию') {
                            $property_type = 'commercial'; $category = 'construction_non_residential';
                        } else {
                            $property_type = 'plot';       $category = 'private_house_building';
                        }
                        break;
                    case '4':
                        // Оставляю как у тебя (хотя логичнее было бы commercial/rent), не меняю формат
                        $property_type = 'Аренда';
                        break;
                    case '5':
                        $property_type = 'commercial';
                        $rooms_type = isset($value['rooms_type_vhn']) ? (string)$value['rooms_type_vhn'] : '';
                        switch ($rooms_type) {
                            case '1':  $category = 'restaurant';            break;
                            case '2':  $category = 'hotel';                 break;
                            case '3':  $category = 'bar';                   break;
                            case '4':  $category = 'hairdressing_saloon';   break;
                            case '10': $category = 'agricultural_object';   break;
                            case '12': $category = 'clinic';                break;
                            case '13': $category = 'beauty_parlor';         break;
                            case '15': $category = 'restaurant-fastfood';   break;
                            case '16': $category = 'pavilion';              break;
                            case '17': $category = 'catering_object';       break;
                            case '19': $category = 'shop';                  break;
                            case '20': $category = 'storehouse';            break;
                            case '21': $category = 'office_premise';        break;
                            default:   $category = 'service_object';
                        }
                        break;
                    case '6':
                        // как у тебя
                        $property_type = 'Зарубежная недвижимость';
                        break;
                }

                // ---- translate country/region (как у тебя)
                if (isset($value['country']) && $value['country'] === 'Украина') {
                    $value['country'] = 'Ukraine';
                }
                if (isset($value['region']) && $value['region'] === 'Харьковская область') {
                    $value['region'] = 'Харківська область';
                }

                // ---- plectan поля (как у тебя)
                $value['district']            = isset($value['plectan_district']) ? $value['plectan_district'] : '';
                $value['locality_name']       = isset($value['plectan_city'])     ? $value['plectan_city']     : (isset($value['locality_name']) ? $value['locality_name'] : '');
                $value['plectan_area']        = isset($value['plectan_area'])     ? $value['plectan_area']     : '';

                // ---- phones (из owner_phone)
                $list_phones = '';
                $owner_phone_raw = isset($value['owner_phone']) ? (string)$value['owner_phone'] : '';
                $phones = array_map('trim', array_filter(explode(',', $owner_phone_raw)));
                if (count($phones) > 0) {
                    foreach ($phones as $phone) {
                        if ($phone !== '') {
                            $list_phones .= '<phone>' . $this->number($phone) . '</phone>';
                        }
                    }
                }

                // ---- price / url
                $price_raw = isset($value['price']) ? $value['price'] : '';
                $value['price'] = $this->getPrice($price_raw);

                $slug = isset($value['url']) ? $value['url'] : '';
                $value['url'] = $this->modx->makeUrl(134, '', '', 'full') . $slug . '/';
                $value['url'] = $normalizeUrl(trim(preg_replace('/\s+/', ' ', $value['url'])));

                // ---- images
                $list_images = '';
                $images = $this->getImagesFlat($value['id']);
                if (is_array($images) && count($images) > 0) {
                    foreach ($images as $image) {
                        $img = isset($image['image']) ? $image['image'] : '';
                        if ($img !== '') {
                            $list_images .= '<image>' . $siteUrl . 'assets/images/flat/' . $value['id'] . '/' . $this->xmlSafe($img) . '</image>';
                        }
                    }
                }

                // ---- description -> XML safe
                $descRaw = isset($value['description']) ? $value['description'] : '';
                $description = $xml($descRaw);

                // ---- поля для location
                $country       = $xml(isset($value['country']) ? $value['country'] : '');
                $region        = $xml(isset($value['region']) ? $value['region'] : '');
                $locality_name = $xml(trim($value['locality_name']));
                $district      = $xml(trim(isset($value['district']) ? $value['district'] : ''));
                $plectan_area  = $xml(trim($value['plectan_area']));

                // В исходнике было ОШИБОЧНОЕ присваивание: ($value['locality_name'] = 'Харків' ? ... )
                // Чиним на сравнение:
                $districtTag = ($locality_name == $xml('Харків')) ? '' : '<district>' . $district . '</district>';
                $subLocalityTag = ($locality_name != $xml('Харків')) ? '' : '<non-admin-sub-locality>' . $plectan_area . '</non-admin-sub-locality>';

                // ---- остальные поля
                $rooms        = $xml(isset($value['rooms']) ? $value['rooms'] : '');
                $floor        = $xml(isset($value['floor']) ? $value['floor'] : '');
                $floors_total = $xml(isset($value['floors_total']) ? $value['floors_total'] : '');
                $priceVal     = $xml($value['price']);
                $owner_name   = $xml(isset($value['owner_name']) ? $value['owner_name'] : '');
                $metro        = $xml(isset($value['metro']) ? $value['metro'] : '');
                $area         = $xml(isset($value['area']) ? $value['area'] : '');

                $offers .= "
        <offer internal-id=\"" . $xml($value['id']) . "\">
            <title>" . $xml(isset($value['name']) ? $value['name'] : '') . "</title>
            <type>{$type}</type>
            <property-type>{$property_type}</property-type>
            <category>{$category}</category>
            <url>" . $xml($value['url']) . "</url>
            <creation-date>" . date('c', strtotime(isset($value['created_at']) ? $value['created_at'] : 'now')) . "</creation-date>
            <location>
                <country>{$country}</country>
                <region>{$region}</region>
                <locality-name>{$locality_name}</locality-name>
                {$districtTag}  
                {$subLocalityTag}
            </location>
            <rooms>{$rooms}</rooms>
            <floor>{$floor}</floor>
            <floors-total>{$floors_total}</floors-total>
            <price>
                <value>{$priceVal}</value>
                <currency>USD</currency>
            </price>
            <sales-agent>
                {$list_phones}
                <name>{$owner_name}</name>
            </sales-agent>
            {$list_images}
            <description>{$description}</description>
            <metro>{$metro}</metro>
            <area>
                <value>{$area}</value>
                <unit>кв.м</unit>
            </area>
        </offer>";
            }

            $result = '<?xml version="1.0" encoding="UTF-8"?>
        <realty-feed>
            <generation-date>' . date('c') . '</generation-date>
            ' . $offers . '
        </realty-feed>';

            return $result;
        }



        public function createPlectanXmlOLD($flats)
        {
            $offers = '';
            if (count($flats) > 0) {
                foreach ($flats as $value) {
                    switch ($value['status']) {
                        case '1':
                            $type = 'sell';
                            break;
                        case '2':
                            $type = 'rent_long';
                            break;
                    }
                    switch ($value['type']) {
                        case '1':
                            $property_type = 'flat';
                            $category = 'isolated';
                            break;
                        case '2':
                            $property_type = 'house';
                            $category = 'single';
                            break;
                        case '3':
                            if ($value['cat_name'] == 'Участки под коммерцию') {
                                $property_type = 'commercial';
                                $category = 'construction_non_residential';
                            } else {
                                $property_type = 'plot';
                                $category = 'private_house_building';
                            }
                            break;
                        case '4':
                            $property_type = 'Аренда';
                            break;
                        case '5':
                            $property_type = 'commercial';
                            switch ($value['rooms_type_vhn']){
                                case '1': //<option value="1">ресторан</option>
                                    $category = 'restaurant';
                                    break;
                                case '2': //<option value="2">гостинница</option>
                                    $category = 'hotel';
                                    break;
                                case '3': //<option value="3">бар</option>
                                    $category = 'bar';
                                    break;
                                case '4': //<option value="4">парикмахерская</option>
                                    $category = 'hairdressing_saloon';
                                    break;
//                                case '5': //<option value="5">дом пристарелых</option>
//                                    $category = 'construction_non_residential';
//                                    break;
//                                case '6': //<option value="6">спортивный зал</option>
//                                    $category = 'construction_non_residential';
//                                    break;
//                                case '7': //<option value="7">продуктовый магазин</option>
//                                    $category = 'construction_non_residential';
//                                    break;
//                                case '8': //<option value="8">киоск</option>
//                                    $category = 'construction_non_residential';
//                                    break;
//                                case '9': //<option value="9">мастерская</option>
//                                    $category = 'construction_non_residential';
//                                    break;
                                case '10': //<option value="10">объект сельскохозяйственного назначения</option>
                                    $category = 'agricultural_object';
                                    break;
//                                case '11': //<option value="11">помещение промышленного назначения</option>
//                                    $category = 'construction_non_residential';
//                                    break;
                                case '12': //<option value="12">больница</option>
                                    $category = 'clinic';
                                    break;
                                case '13': //<option value="13">салон красоты</option>
                                    $category = 'beauty_parlor';
                                    break;
//                                case '14': //<option value="14">спортклуб</option>
//                                    $category = 'construction_non_residential';
//                                    break;
                                case '15': //<option value="15">закусочная</option>
                                    $category = 'restaurant-fastfood';
                                    break;
                                case '16': //<option value="16">павильон</option>
                                    $category = 'pavilion';
                                    break;
                                case '17': //<option value="17">столовая</option>
                                    $category = 'catering_object';
                                    break;
//                                case '18': //<option value="18">кондитерский цех</option>
//                                    $category = 'construction_non_residential';
//                                    break;
                                case '19': //<option value="19">магазин</option>
                                    $category = 'shop';
                                    break;
                                case '20': //<option value="20">склад</option>
                                    $category = 'storehouse';
                                    break;
                                case '21': //<option value="21" selected="">офис</option>
                                    $category = 'office_premise';
                                    break;
                                default:
                                    $category = 'service_object';
                            }
                            break;
                        case '6':
                            $property_type = 'Зарубежная недвижимость';
                            break;
                    }

                    switch ($value['country']) {
                        case 'Украина':
                            $value['country'] = 'Ukraine';
                            break;
                    }

                    switch ($value['region']) {
                        case 'Харьковская область':
                            $value['region'] = 'Харківська область';
                            break;
                    }

                    $value['district'] = $value['plectan_district'];
                    $value['locality_name'] = $value['plectan_city'];
                    $value['non-admin-sub-locality'] = $value['plectan_area'];
//                    switch ($value['locality_name']) {
//                        case 'Харьков':
//                            $value['locality_name'] = 'Харків';
//                            break;
//                    }

//                    switch ($value['non_admin_sub_locality']) {
//                        // Районы области
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Балаклійський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Барвінківський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Близнюківський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Богодухівський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Борівський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Валківський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Великобурлуцький район';
////                            break;
//
//                        case 'Волчанский':
//                            $value['non_admin_sub_locality'] = 'Вовчанський район';
//                            break;
//
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Дворічанський район';
////                            break;
//
//                        case 'Дергачевский':
//                            $value['non_admin_sub_locality'] = 'Дергачівський район';
//                            break;
//
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Зачепилівський район';
////                            break;
//
//                        case 'Змиевской':
//                            $value['non_admin_sub_locality'] = 'Зміївський район';
//                            break;
//
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Золочівський район (Хрк.)';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Ізюмський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Кегичівський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Коломацький район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Красноградський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Краснокутський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = "Куп'янський район";
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Лозівський район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Нововодолазький район';
////                            break;
////
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Первомайський район (Хрк.)';
////                            break;
//
//                        case 'Печенежский':
//                            $value['non_admin_sub_locality'] = 'Печенізький район';
//                            break;
//
////                        case 'no':
////                            $value['non_admin_sub_locality'] = 'Сахновщинський район';
////                            break;
//
//                        case 'Харьковская р-н':
//                        case 'Харьковский р-н':
//                        case 'Харьковский':
//                            $value['non_admin_sub_locality'] = 'Харківський район';
//                            break;
//
//                        case 'Чугуевский':
//                            $value['non_admin_sub_locality'] = 'Чугуївський район';
//                            break;
//
//                        case 'Шевченковский':
//                            $value['non_admin_sub_locality'] = 'Шевченківський район';
//                            break;
//
//                        case "Павлово Поле":
//                            $value['non_admin_sub_locality'] = "Павлово поле";
//                            break;
//
//                        case "Холодная Гора":
//                            $value['non_admin_sub_locality'] = "Холодная гора Ленинский";
//                            break;
//
//                        case "м. Научная":
//                            $value['non_admin_sub_locality'] = "Госпром";
//                            break;
//
//                        case "м. Гагарина":
//                            $value['non_admin_sub_locality'] = "Гагарина, р-н ст. метро";
//                            break;
//
//                        case "Жуковского":
//                            $value['non_admin_sub_locality'] = "Жуковского пос.";
//                            break;
//
//                        case "Салтовка":
//                            switch ($value['metro']) {
//                                case 'Героев Труда':
//                                    $value['non_admin_sub_locality'] = "520";
//                                    break;
//                            }
//
//                            break;
//
//                    }

                    //Телефоны
                    //$phones = explode(',', $value['agent_phone']);
                    $phones = explode(',', $value['owner_phone']);
                    $list_phones = '';
                    if (is_array($phones) && count($phones) > 0) {
                        foreach ($phones as $phone) {
                            if (trim($phone) != '') {
                                $list_phones .= '<phone>'.$this->number($phone).'</phone>';
                            }
                        }
                    }

                    $value['price'] = $this->getPrice($value['price']);
                    $value['url'] = $this->modx->makeUrl(134, '', '', 'full') . $value['url'] . '/';

                    $images = $this->getImagesFlat($value['id']);
                    $list_images = '';
                    if (is_array($images) && count($images) > 0) {
                        foreach ($images as $image) {
                            // $img = '<image>'.$this->config['site_url'].$this->modx->runSnippet("R", array("img" => "assets/images/flat/".$value['id']."/".$image['image'], "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/".$value['id'])).'</image>';
                            $list_images .= '<image>' . str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'https://toprealtor.com.ua', $this->modx->config['site_url']) . "assets/images/flat/" . $value['id'] . "/" . $image['image'] . '</image>';
                        }
                    }
                    //Телефоны
//                    $phones = explode(',', $value['agent_phone']);
//                    $list_phones = '';
//                    if (is_array($phones) && count($phones) > 0) {
//                        foreach ($phones as $phone) {
//                            $list_phones .= '<phone>' . $this->number($phone) . '</phone>';
//                        }
//                    }
                    //latitude, longitude
                    //$coordArr = explode(", ", $value['coord']);
                    $description = $string = str_replace('&', '&amp;', $value['description']);
                    $description = $string = str_replace('"', '&quot;', $description);
                    $description = $string = str_replace('>', '&gt;', $description);
                    $description = $string = str_replace('<', '&lt;', $description);

                    $value['url'] = str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'https://toprealtor.com.ua', $value['url']);
                    //$offers .= print_r($value, true);
                    $offers .= "
<offer internal-id=\"{$value['id']}\">
    <title>{$value['name']}</title>
    <type>{$type}</type>
    <property-type>{$property_type}</property-type>
    <category>{$category}</category>
    <url>" . trim(preg_replace('/\s+/', ' ', $value['url'])) . "</url>
    <creation-date>" . date('c', strtotime($value['created_at'])) . "</creation-date>
    <location>
        <country>{$value['country']}</country>
        <region>{$value['region']}</region>
        <locality-name>" . trim($value['locality_name']) . "</locality-name>
        " . ($value['locality_name'] = 'Харків' ? '' : '<district>' . trim($value['district']) . '</district>') . "  
        " . ($value['locality_name'] != 'Харків' ? '' : '<non-admin-sub-locality>' . trim($value['plectan_area']) . '</non-admin-sub-locality>') . "   
    </location>
    <rooms>{$value['rooms']}</rooms>
    <floor>{$value['floor']}</floor>
    <floors-total>{$value['floors_total']}</floors-total>
    <price>
        <value>{$value['price']}</value>
        <currency>USD</currency>
    </price>
    <sales-agent>
        {$list_phones}
        <name>{$value['owner_name']}</name>
    </sales-agent>
    {$list_images}
    <description>{$description}</description>
    <metro>{$value['metro']}</metro>
    <area>
        <value>{$value['area']}</value>
        <unit>кв.м</unit>
    </area>
</offer>";

                }

                $result = '<?xml version="1.0" encoding="UTF-8"?>
    <realty-feed>
        <generation-date>'.date('c').'</generation-date>
        '.$offers.'
    </realty-feed>';
            }
            return $result;
        }


 /**
     * Сохраняет историю цен квартиры (DELETE + INSERT)
     * порядок строк = порядок массива (drag & drop)
     *
     * @param int   $flatId
     * @param array $history flat[price_history][]
     */
    public function saveFlatPriceHistory($flatId,  $history)
    {
        $flatId = (int)$flatId;

        // 1. Удаляем старую историю
        $sql = "DELETE FROM `modx_a_flat2price`
                WHERE `flat_id` = '" . $this->db->escape($flatId) . "'";
        $this->db->query($sql);

        if (empty($history)) {
            return;
        }

        $values = [];
        $sort   = 0;
        $now    = date('Y-m-d H:i:s');

        foreach ($history as $row) {

            $price = trim((string)($row['price'] ?? ''));
            $date  = trim((string)($row['start_date'] ?? ''));

            // пропускаем полностью пустые строки
            if ($price === '' && $date === '') {
                continue;
            }

            // нормализация цены "12 345,67" → "12345.67"
            $priceNorm = str_replace([' ', ','], ['', '.'], $price);
            $priceVal  = (float)$priceNorm;

            // дата или NULL
            $dateSql = ($date !== '')
                ? "'" . $this->db->escape($date) . "'"
                : "NULL";

            $values[] = "(" .
                "'" . $this->db->escape($flatId) . "', " .
                "'" . $this->db->escape($priceVal) . "', " .
                $dateSql . ", " .
                "'" . $this->db->escape($sort) . "', " .
                "'" . $this->db->escape($now) . "'" .
            ")";

            $sort++;
        }

        // если после фильтрации строк ничего не осталось
        if (empty($values)) {
            return;
        }

        // 2. Массовая вставка
        $sql = "
            INSERT INTO `modx_a_flat2price`
                (`flat_id`, `price`, `start_date`, `sort_order`, `updated_at`)
            VALUES
                " . implode(",\n", $values);

        $this->db->query($sql);
    }





    public function getFlatPriceHistory($flatId)
{
    $flatId = (int)$flatId;

    $sql = "SELECT `price`, `start_date`, `sort_order`
            FROM `modx_a_flat2price`
            WHERE `flat_id` = '" . $this->db->escape($flatId) . "'
            ORDER BY `sort_order` ASC, `id` ASC";

    $res = $this->db->query($sql);

    $out = [];
    if ($res) {
        while ($row = $this->db->getRow($res)) {
            // В зависимости от твоего db-класса: getRow/getRowAssoc/fetchRow и т.п.
            $out[] = $row;
        }
    }

    return $out;
}



        
    }
}
