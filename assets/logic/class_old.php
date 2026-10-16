<?php
if (!class_exists('Corp')) {
    /**
     * Class
     */
    class Corp
    {
        var $modx = null;
        var $db = null;

        function __construct($modx)
        {
            $this->modx = $modx;
            $this->db = $modx->db;
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
            $mail->Host = $this->modx->config['smtp_host'];
            $mail->Port = $this->modx->config['smtp_port'];
            $mail->Username = $this->modx->config['smtp_username'];
            $mail->Password = $Password;
            $mail->Subject = $subject;
            $mail->SetFrom($this->modx->config['smtp_username'], $this->modx->config['site_name']);

            if ($file_tmp != '') {
                $mail->addAttachment($file_tmp, $file_name);
            }

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
        public function updateFlatsStatus($ids = 0)
        {
            if ($ids == 0) {
                return $this->db->query("
                          UPDATE `modx_a_flat`
					      SET updated_at = NOW()");
            } elseif (is_array($ids)) {
                $ids = implode(' ,', $ids);
            }
            return $this->db->query("
                      UPDATE `modx_a_flat`
				      SET updated_at = NOW() 
				      WHERE id IN (" . $ids . ")");
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
        function getFlat($id, $url = null)
        {
            $sql
                = "SELECT *,
			(SELECT pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
            (SELECT count(*) as user_rating FROM `modx_a_rating` WHERE page_id = " . $this->db->escape($id) . " AND user_ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "') ) as 'user_rating'
			FROM `modx_a_flat` f
			WHERE id = '" . $this->db->escape($id) . "'" . ($url != '' ? " OR url = '" . $this->db->escape($url) . "'" : "");

            $flat = $this->db->getRow($this->db->query($sql));
            if ($flat) {

                // категории
                $sql = 'SELECT *, (select pagetitle from `modx_site_content` where id = cat_id) as pagetitle FROM `modx_a_flat2cat` WHERE flat_id = "' . $this->db->escape($flat['id']) . '"';
                $categories = $this->db->query($sql);
                while ($c = $this->db->getRow($categories)) {
                    $flat['categories'][$c['cat_id']] = $c['pagetitle'];
                }

                // св-ва
                $sql = 'SELECT *, (select pagetitle from `modx_site_content` where id = properties_id) as pagetitle FROM `modx_a_flat2properties` WHERE flat_id = "' . $this->db->escape($flat['id']) . '"';
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


        function getFlatInfo($id, $url = null)
        {
            $sql
                = "SELECT *,
      (SELECT pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
            (SELECT count(*) as user_rating FROM `modx_a_rating` WHERE page_id = " . $this->db->escape($id) . " AND user_ip = inet_aton('" . $_SERVER['REMOTE_ADDR'] . "') ) as 'user_rating'
      FROM `modx_a_flat` f
      WHERE id = '" . $this->db->escape($id) . "'" . ($url != '' ? " OR url = '" . $this->db->escape($url) . "'" : "");

            $flat = $this->db->getRow($this->db->query($sql));
            if ($flat) {

                // категории
                $sql = 'SELECT *, (select pagetitle from `modx_site_content` where id = cat_id) as pagetitle FROM `modx_a_flat2cat` WHERE flat_id = "' . $this->db->escape($flat['id']) . '"';
                $categories = $this->db->query($sql);
                while ($c = $this->db->getRow($categories)) {
                    $flat['categories'][$c['cat_id']] = $c['pagetitle'];
                }

                // св-ва
                $sql = 'SELECT *, (select pagetitle from `modx_site_content` where id = properties_id) as pagetitle FROM `modx_a_flat2properties` WHERE flat_id = "' . $this->db->escape($flat['id']) . '"';
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
        public function getFlats($param, $where = '', $id = 5, $order = 'status ASC, id DESC')
        {
            $categories = array_values($this->modx->getChildIds($id));
            $categories[] = $id;
            $categories = implode(",", $categories);
            if ($categories == '') {
                $categories = 0;
            }

            $sql
                = "SELECT SQL_CALC_FOUND_ROWS *,
				(select count(*) from `modx_a_flat2cat` where cat_id in (" . $categories . ") and flat_id = f.id) AS 'cnt',
				(SELECT pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
				(SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 20 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_object_type',
                (SELECT id FROM  `modx_site_content` WHERE id=(select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_id'
			FROM `modx_a_flat` f
				" . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
			HAVING cnt > 0
            " . $param['AND'] . "
            " . $param['categor'] . "
			ORDER BY " . $this->db->escape($order) . " 
	        LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);

            return $this->db->makeArray($this->db->query($sql));
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
//					" . (count($set) > 0 ? " SET " . implode(" , ", $set) : "") . "
//					WHERE id IN (SELECT id FROM `modx_a_flat` f
//					" . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
//		      " . ($param['offset'] != '' && $param['limit'] != '' ? "  LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']) : '') . ")");
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
            if ($ids == 0) {
                return $this->db->query("UPDATE `modx_a_flat`
					SET xml_active=" . $xml_active);
            } elseif (is_array($ids)) {
                $ids = implode(' ,', $ids);
            }
            return $this->db->query("UPDATE `modx_a_flat`
				SET xml_active=" . $xml_active . " WHERE id IN (" . $ids . ")");
        }

        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlatsVhn($ids = 0, $xml_active_vhn = 0)
        {
            if ($ids == 0) {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET xml_active_vhn=" . $xml_active_vhn);
            } elseif (is_array($ids)) {
                $ids = implode(' ,', $ids);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET xml_active_vhn=" . $xml_active_vhn . " WHERE id IN (" . $ids . ")");
        }

        /**
         * Массовое обновление обектов
         *
         * @param [array] параментры для выборки
         */
        public function updateFlatsLun($ids = 0, $xml_active_lun = 0)
        {
            if ($ids == 0) {
                return $this->db->query("UPDATE `modx_a_flat`
                    SET xml_active_lun=" . $xml_active_lun);
            } elseif (is_array($ids)) {
                $ids = implode(' ,', $ids);
            }
            return $this->db->query("UPDATE `modx_a_flat`
                SET xml_active_lun=" . $xml_active_lun . " WHERE id IN (" . $ids . ")");
        }

        /**
         * Выводим список обектов
         *
         * @param [array] параментры для выборки
         */
        public function getFlatsXML($param, $where = '', $id = 5)
        {
            $categories = array_values($this->modx->getChildIds($id));

            //исключаем зарубежную недвижимость
            $excludeDocs = array_values($this->modx->getChildIds(12));
            $excludeDocs[] = 12;
            $excludeDocs[] = 6;//новинки
            foreach ($categories as $key => $value) {
                if (in_array($value, $excludeDocs)) {
                    unset($categories[$key]);
                }
            }

            $categories[] = $id;
            $categories = implode(",", $categories);

            $sql
                = "SELECT SQL_CALC_FOUND_ROWS *,
				(select count(*) from `modx_a_flat2cat` where cat_id in (" . $categories . ") and flat_id = f.id) AS 'cnt',
				(SELECT pagetitle FROM `modx_site_content` WHERE id = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'cat_name',
				(SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 20 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_object_type',
				(SELECT value FROM `modx_site_tmplvar_contentvalues` WHERE tmplvarid = 26 AND contentid = (select cat_id from `modx_a_flat2cat` where flat_id = f.id and cat_id != 6 limit 1)) as 'tv_xml_category',
				(select value from `modx_site_tmplvar_contentvalues` where contentid = f.agent and tmplvarid = 14) as agent_phone,
				(select pagetitle from `modx_site_content` where id = f.agent) as agent_name
			FROM `modx_a_flat` f
				" . (count($where) > 0 ? " WHERE " . implode(" AND ", $where) : "") . "
			HAVING cnt > 0
			ORDER BY id DESC
	        LIMIT " . $this->db->escape($param['offset']) . "," . $this->db->escape($param['limit']);

            return $this->db->makeArray($this->db->query($sql));
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
                    //	$where[] = "updated_at = NOW()";

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
            $opt = $type == 0 ? "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=1920,h=1080,far=1,f=png,fltr_wmi|/images/watermark.png|C|100|50|50";
            $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => $opt, "folder" => "object/" . $id]);
            $opt = $type == 0 ? "w=670&h=400&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark.png|C|100|50|50";
            $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);

            $opt = $type == 0 ? "w=900&h=500&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark.png|C|100|50|50";
            $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
            $opt = $type == 0 ? "w=660&h=350&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark-middle.png|C|100|50|50";
            $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
            $opt = $type == 0 ? "w=136&h=77&zc=1,fltr_wmi|/images/watermark.png|C|100|50|50" : "w=670&h=400&far=1,f=png,fltr_wmi|/images/watermark-small.png|C|100|50|50";
            $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $id . "/" . $name, "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/" . $id]);
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
            unlink(MODX_BASE_PATH . 'assets/images/flat/' . $id . '/' . $name);
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
                = 'SELECT id, pagetitle,
	    	(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 8) as tv_image,
	    	(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 13) as tv_city_phone,
	    	(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 14) as tv_mobile_phone,
	    	(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 15) as tv_email,
	    	(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 16) as tv_skype
	    	FROM `modx_site_content` sc
	    	WHERE parent = 123 ' . ($id != '' ? 'AND id = ' . $this->db->escape($id) : '');
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
            $sql = 'SELECT id, pagetitle FROM `modx_site_content` WHERE parent = ' . $this->db->escape($id);
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
            $sql
                = "INSERT INTO `modx_web_user_attributes` SET
                        internalKey = '" . $user_id . "',
                        fullname    = '" . $this->db->escape($data['fullname']) . "',
                        email       = '" . $this->db->escape($data['email']) . "',
                        active      = '" . $this->db->escape($data['active']) . "'
                    ";
            $this->db->query($sql);
            $sql = "INSERT INTO `modx_web_groups` SET webgroup = 1, webuser = '" . $user_id . "'";
            $sql = "INSERT INTO `modx_a_mailer_users` SET user_name = '" . $this->db->escape($data['fullname']) . "', user_email = '" . $this->db->escape($data['fullname']) . "'";
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
            $sql = "SELECT id, pagetitle, (select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 6) as 'tv_preview' FROM `modx_site_content` sc WHERE parent = 165";
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
            $sql = 'SELECT id, pagetitle, isfolder FROM `modx_site_content` WHERE parent = 5';
            $cats = $this->db->makeArray($this->db->query($sql));
            foreach ($cats as $k1 => $v1) {
                if ($v1['isfolder'] == 0) {
                    $res[] = ["id" => $v1['id'], "pagetitle" => $v1['pagetitle']];
                } else {
                    $sql = 'SELECT id, pagetitle, isfolder FROM `modx_site_content` WHERE parent = ' . $v1['id'];
                    $cats2 = $this->db->makeArray($this->db->query($sql));
                    foreach ($cats2 as $k2 => $v2) {
                        if ($v2['isfolder'] == 0) {
                            $res[] = ["id" => $v2['id'], "pagetitle" => $v1['pagetitle'] . " | " . $v2['pagetitle']];
                        } else {
                            $sql = 'SELECT id, pagetitle, isfolder FROM `modx_site_content` WHERE parent = ' . $v2['id'];
                            $cats3 = $this->db->makeArray($this->db->query($sql));
                            foreach ($cats3 as $k3 => $v3) {
                                if ($v3['isfolder'] == 0) {
                                    $res[] = ["id" => $v3['id'], "pagetitle" => $v1['pagetitle'] . " | " . $v2['pagetitle'] . " | " . $v3['pagetitle']];
                                } else {
                                    $sql = 'SELECT id, pagetitle, isfolder FROM `modx_site_content` WHERE parent = ' . $v3['id'];
                                    $cats4 = $this->db->makeArray($this->db->query($sql));
                                    foreach ($cats4 as $k4 => $v4) {
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
                = 'SELECT id, pagetitle, parent,
	   			(select pagetitle from `modx_site_content` where id = sc.parent) as "parent_pagetitle",
	   			(select pagetitle from `modx_site_content` where id = (select parent from `modx_site_content` where id = sc.parent)) as "parent_parent_pagetitle"
				FROM `modx_site_content` sc
				WHERE
					(
						parent = ' . $this->db->escape($parent) . ' OR
						parent IN (select id from `modx_site_content` where parent = ' . $this->db->escape($parent) . ') OR
						parent IN (select id from `modx_site_content` where parent IN (select id from `modx_site_content` where parent = ' . $this->db->escape($parent) . '))
					) AND
					isfolder = 0
				' . ($ignore != '' ? ' AND id NOT IN (' . $this->db->escape($ignore) . ')' : '') . '
				ORDER BY menuindex ASC
	   		';
            return $this->db->makeArray($this->db->query($sql));
        }

        /**
         * Mobile Menu
         */
        public function menu()
        {
            $sql
                = "SELECT id, parent, pagetitle, longtitle FROM `modx_site_content`
			WHERE
				published = 1 AND
				deleted = 0 AND
				(
					parent = 5 OR
					parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5) OR
					parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5)) OR
					parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5))) OR
					parent IN (SELECT id FROM `modx_site_content` where parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent IN (SELECT id FROM `modx_site_content` WHERE parent = 5))))
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
                = 'SELECT id, pagetitle,parent,
			(select value from `modx_site_tmplvar_contentvalues` where contentid = sc.id and tmplvarid = 6) as image
			FROM `modx_site_content` sc
			WHERE
				id != 6 AND
				(
					parent = 5 OR
					parent IN (select id from `modx_site_content` where parent = 5)
				)
                ORDER BY menuindex
			';
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


                //var_dump($codes);
                $rates = explode("|", $this->modx->config['shop_curr_rate']);


                foreach ($codes as $key => $value) {
                    if ($value != '') {
                        $curr[$value] = $rates[$key];
                    }
                }
                //   echo $amount;
                $to = "USD";
                if ($this->modx->config['shop_curr_default_code'] == $to) {
                    //  echo $amount;
                    //   echo (round($amount * $curr[$to], 2));
                    return round($amount * $curr[$to], 2);
                } else {
                    return round($amount * $curr[$to], 2);/*round($amount * $curr[$to], 2);*/
                }
            } else {
                return $amount;
            }
//            return number_format($res, 2, '.', ' ');
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

            $flats = $this->getFlatsXML($param, $where);
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

            $flats = $this->getFlatsXML($param, $where);
            print_r($flats); die;
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
            $flats = $this->getFlatsXML($param, $where);
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

        public function getCategory_one_dept()
        {
            $res_array = array();
            $categoroes_one_dept = $this->modx->getDocumentChildren(5);
            for ($i = 1; $i < count($categoroes_one_dept); $i++) {
                $id_category_res[$i]['id'] = (int)$categoroes_one_dept[$i]['id'];
                $id_category_res[$i]['name'] = $categoroes_one_dept[$i]['pagetitle'];
            }
            return $id_category_res;
        }

        public function getIdCategorys($id)
        {
            $category_id = $this->modx->getChildIds($id);
            if (empty($category_id)) {
                $res[] = $id;
            } else {
                foreach ($category_id as $key => $value) {
                    $res[] = "'{$value}'";
                }
            }
            return $res;
        }

        public function getCategory_two_dept($id_children)
        {
            $categoroes_two_dept = $this->modx->getDocumentChildren($id_children);
            for ($i = 1; $i < count($categoroes_two_dept); $i++) {
                $id_category_res[$i]['id'] = (int)$categoroes_two_dept[$i]['id'];
                $id_category_res[$i]['name'] = $categoroes_two_dept[$i]['pagetitle'];
            }
            return $id_category_res;
        }

        public function createXMLLun($flats, $type_xml)
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

                    $value['price'] = $this->getPrice($value['price'], $this->modx->config['course']);
                    $value['url'] = $this->modx->makeUrl(134, '', '', 'full') . $value['url'] . '/';

                    $images = $this->getImagesFlat($value['id']);
                    $list_images = '';
                    if (is_array($images) && count($images) > 0) {
                        foreach ($images as $image) {
                            // $img = '<image>'.$this->config['site_url'].$this->modx->runSnippet("R", array("img" => "assets/images/flat/".$value['id']."/".$image['image'], "opt" => "w=670&h=400&zc=1&f=png&fltr_wmi|/images/watermark.png|BL|100|20|20", "folder" => "object/".$value['id'])).'</image>';
                            $list_images
                                .= '
                            <image>' . str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'http://toprealtor.com.ua', $this->modx->config['site_url']) . "assets/images/flat/" . $value['id'] . "/" . $image['image'] . '</image>';

                            //     $list_images
                            //         .= '
                            // <image>' . str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'http://toprealtor.com.ua', $this->modx->config['site_url']) . $this->modx->runSnippet("R", ["img" => "assets/images/flat/" . $value['id'] . "/" . $image['image'], "opt" => "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|50|50"]) . '</image>';
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

                    $value['url'] = str_replace('http//:/hosting/toprealtor/public_html/assets/modules', 'http://toprealtor.com.ua', $value['url']);

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
                                     <sales-agent>'
                                . $list_phones . '
                                        <name>' . $value['agent_name'] . '</name>
                                        <category>агентство</category>
                                     </sales-agent>
                                       ' . $list_images . '     
                                     <description>' . $description . '</description>
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


    }
}
