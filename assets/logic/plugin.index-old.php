<?php

require_once MODX_BASE_PATH . "assets/logic/class.php";


$class = new Corp($modx);
$modx->config['lang'] = $class->lang;

/** Хелпер для языковых тегов */
if (!function_exists('T')) {
    function T($id){
        global $modx;
        return $modx->parseDocumentSource('[#'.$id.'#]');
    }
}

switch ($modx->event->name) {
    case "OnWebPageInit":
    case "OnPageNotFound":


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                        // Папка для логов
            $logDir = MODX_BASE_PATH . 'assets/logs/';
            $logFile = $logDir . 'post_requests.log';

            // Создаём папку, если её нет
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Текущий URL (куда пришёл POST)
            $isHttps = (
                (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            );
            $scheme = $isHttps ? 'https://' : 'http://';
            $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $uri    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $currentUrl = $scheme . $host . $uri;

            // Страница, с которой был отправлен запрос
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

            // IP
            $ip = '';
            if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP']; // если Cloudflare
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $tmp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = trim($tmp[0]);
            } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            // User-Agent
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

            // POST-данные
            $postData = $_POST;

            // Если нужно логировать и сырое тело запроса:
            $rawInput = file_get_contents('php://input');

            // Формируем запись
            $logEntry = array(
                'datetime'      => date('Y-m-d H:i:s'),
                'request_url'   => $currentUrl,
                'referer'       => $referer,
                'ip'            => $ip,
                'user_agent'    => $userAgent,
                'post'          => $postData,
                'raw_input'     => $rawInput,
            );

            $line = "==============================\n";
            $line .= print_r($logEntry, true);
            $line .= "\n";

            // Пишем в файл с блокировкой
            file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
                
        }



        $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'toprealtor.com.ua';
        $isSsl  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $scheme = $isSsl ? 'https' : 'http';
        $uri    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $ref    = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $abs    = $scheme . '://' . $host . $uri;

        function _path_only($uri) { // безопасно получить path+query
            $p = parse_url($uri, PHP_URL_PATH);
            $q = parse_url($uri, PHP_URL_QUERY);
            return rtrim($p ?: '/', '/') . ( $q ? '?' . $q : '' );
        }
        $path = _path_only($abs);

        // ===== Помощники =====
        function starts_with_ua($path) {
            // true для /ua, /ua/, /ua/xxx
            if ($path === '/ua' || $path === '/ua/') return true;
            return strpos($path, '/ua/') === 0;
        }
        function to_ua_url($scheme, $host, $path) {
            // добавляем /ua/ в начало path, если его нет
            $q = '';
            $purePath = $path;
            $qpos = strpos($path, '?');
            if ($qpos !== false) {
                $q = substr($path, $qpos);         // ?a=1
                $purePath = substr($path, 0, $qpos); // /object/...
            }
            if (starts_with_ua($purePath)) {
                return $scheme . '://' . $host . $purePath . $q;
            }
            // гарантируем ведущий /
            if ($purePath === '' || $purePath[0] !== '/') $purePath = '/' . $purePath;
            // / -> /ua/
            if ($purePath === '/') $purePath = '/ua/';
            else $purePath = '/ua' . $purePath;
            return $scheme . '://' . $host . $purePath . $q;
        }
        function to_ru_url($scheme, $host, $path) {
            // убираем ведущий /ua из path
            $q = '';
            $purePath = $path;
            $qpos = strpos($path, '?');
            if ($qpos !== false) {
                $q = substr($path, $qpos);
                $purePath = substr($path, 0, $qpos);
            }
            if (starts_with_ua($purePath)) {
                // /ua -> /
                if ($purePath === '/ua' || $purePath === '/ua/') $purePath = '/';
                else $purePath = substr($purePath, 3); // обрезаем "/ua"
                if ($purePath === '') $purePath = '/';
            }
            return $scheme . '://' . $host . $purePath . $q;
        }
        function redirect_302($url) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
            header('Location: ' . $url, true, 302);
            exit;
        }
        function is_internal_ref($ref, $host) {
            if ($ref === '') return false;
            $rhost = parse_url($ref, PHP_URL_HOST);
            return $rhost && (strcasecmp($rhost, $host) === 0);
        }

        // ===== Обработка явного выбора ?setlang=ua|ru =====
        if (isset($_GET['setlang'])) {
            $set = $_GET['setlang'] === 'ua' ? 'ua' : 'ru';
            $_SESSION['lang'] = $set;
            $target = ($set === 'ua') ? to_ua_url($scheme, $host, $path) : to_ru_url($scheme, $host, $path);
            // Убираем параметр setlang из URL
            $target_no_param = $target;
            $qpos = strpos($target_no_param, '?');
            if ($qpos !== false) {
                // выкинуть setlang из query
                $base = substr($target_no_param, 0, $qpos);
                $query = substr($target_no_param, $qpos + 1);
                parse_str($query, $args);
                unset($args['setlang']);
                $target_no_param = $base . (count($args) ? ('?' . http_build_query($args)) : '');
            }
            redirect_302($target_no_param);
        }


function is_root_path($path) {
    // корневые варианты: /, /index.php, /?...
    if ($path === '/' || $path === '/index.php') return true;
    // /?a=1 — тоже корень
    $qpos = strpos($path, '?');
    if ($qpos !== false) {
        $base = substr($path, 0, $qpos);
        return ($base === '/' || $base === '/index.php');
    }
    return false;
}

// ===== Главная логика =====
$isUa      = starts_with_ua($path);          // true если путь начинается с /ua
$hasRef    = ($ref !== '');
$internal  = is_internal_ref($ref, $host);   // true если реферер наш
$prefKnown = isset($_SESSION['lang']) ? $_SESSION['lang'] : null;
$showLangModal = 0;

/**
 * 0) ВНУТРЕННИЕ переходы:
 *    — не редиректим; просто обновляем сессию под фактическую версию
 */
if ($internal) {
    $_SESSION['lang'] = $isUa ? 'ua' : 'ru';
    // выводим страницу как есть, без модалки
}

/**
 * 1) БЕЗ реферера (ввел руками/закладка):
 *    — трактуем текущий URL как явный выбор
 *    — никаких редиректов, просто фиксируем выбор
 *    — исключение: чистый корень при отсутствии предпочтения -> один раз ведём на /ua/
 */
// elseif (!$hasRef) {
//     // обновим предпочтение под текущий URL
//     $_SESSION['lang'] = $isUa ? 'ua' : 'ru';

//     // если предпочтения ещё не было и мы на самом корне — по умолчанию на /ua/
//     if (!$prefKnown && is_root_path($path) && !$isUa) {
//         redirect_302(to_ua_url($scheme, $host, $path));
//     }
// }
elseif (!$hasRef) {
    // Первый визит (нет предпочтения)
    if (!$prefKnown) {
        if ($isUa) {
            // Пользователь сам попал на /ua — фиксируем UA
            $_SESSION['lang'] = 'ua';
        } else {
            // Первый визит и сразу на RU — показываем модалку
            // НИЧЕГО не фиксируем в сессии до выбора
            $showLangModal = 1;
        }
    } else {
        // Предпочтение уже было — синхронизируем с текущим URL
        $_SESSION['lang'] = $isUa ? 'ua' : 'ru';
    }
}
/**
 * 2) ВНЕШНИЕ переходы:
 *    — если уже есть предпочтение 'ua' и пользователь попал на RU -> мягко уводим на UA
 *    — если предпочтения нет и пользователь на RU -> показываем модалку с предложением UA
 *    — если попал на UA -> всё ок, просто фиксируем 'ua' в сессии
 */
else {
    if ($isUa) {
        $_SESSION['lang'] = 'ua';
    } else {
        if ($prefKnown === 'ua') {
            redirect_302(to_ua_url($scheme, $host, $path));
        } elseif (!$prefKnown) {
            $showLangModal = 1; // предложим перейти на UA
        } else {
            // prefKnown === 'ru' — оставляем как есть
        }
    }
}


$modx->setPlaceholder('langStatus', (int)$showLangModal);

if ($showLangModal == 1) {
    // Ссылка на текущую страницу, но с /ua/
    $uaUrl = to_ua_url($scheme, $host, $path);

    // Вариант с фиксацией выбора через setlang=ua (можно использовать любой из двух)
    $uaUrlSet  = $uaUrl . (strpos($uaUrl, '?') === false ? '?setlang=ua' : '&setlang=ua');

    // «Остаться на RU» — текущий URL с setlang=ru
    $stayUrlSet = $abs . (strpos($abs, '?') === false ? '?setlang=ru' : '&setlang=ru');

    $modx->setPlaceholder('langUaUrl',    $uaUrl);     // чистая UA-ссылка
    $modx->setPlaceholder('langUaUrlSet', $uaUrlSet);  // UA-ссылка + фиксация
    $modx->setPlaceholder('langStayUrl',  $stayUrlSet);// остаться на RU + фиксация
}



        // // ===== Главная логика =====
        // $isUa      = starts_with_ua($path);
        // $hasRef    = ($ref !== '');
        // $internal  = is_internal_ref($ref, $host);
        // $prefKnown = isset($_SESSION['lang']) ? $_SESSION['lang'] : null;

        // // 1) Если нет реферера и предпочтение ещё не выбрано — по умолчанию ведём на UA
        // if (!$hasRef && !$prefKnown) {
        //     $_SESSION['lang'] = 'ua';
        //     if (!$isUa) {
        //         redirect_302(to_ua_url($scheme, $host, $path));
        //     }
        //     // если уже /ua/ — просто продолжаем
        // }

        // // 2) Если есть внешняя ссылка (не с нашего домена)
        // $showLangModal = false;
        // if ($hasRef && !$internal) {
        //     // если ранее выбрал UA — сразу уводим на UA-версию
        //     if ($prefKnown === 'ua' && !$isUa) {
        //         redirect_302(to_ua_url($scheme, $host, $path));
        //     }
        //     // если ранее выбрал RU — ничего не делаем
        //     // если предпочтения нет и мы на RU — показываем модалку
        //     if (!$prefKnown && !$isUa) {
        //         $showLangModal = true;
        //     }
        // }
        // $modx->setPlaceholder['langStatus'] = $showLangModal;
        // // 3) Если пользователь ранее выбрал язык — уважаем выбор
        // if ($prefKnown === 'ua' && !$isUa) {
        //     // Навигация без реферера / из закладки — держим на UA
        //     if (!$hasRef || !$internal) {
        //         redirect_302(to_ua_url($scheme, $host, $path));
        //     }
        // } elseif ($prefKnown === 'ru' && $isUa) {
        //     // Ранее выбрал RU — не мешаем; можно автоперенаправить на RU, если хотите:
        //     // redirect_302(to_ru_url($scheme, $host, $path));
        // }

        // ===== курс валют =====
        $currency = explode("|", $modx->config['shop_curr_code']);
        $signs    = explode("|", $modx->config['shop_curr_sign']);
        if (isset($_GET['course'])) {
            $_SESSION['currency'] = $_GET['course'];
            $modx->clearCache('full', false);
        } else {
            $_SESSION['currency'] = isset($_SESSION['currency']) ? $_SESSION['currency'] : $modx->config['shop_curr_default_code'];
        }
        $modx->config['course']   = $modx->config['currcode'] = in_array($_SESSION['currency'], $currency) ? $_SESSION['currency'] : $modx->config['shop_curr_default_code'];
        $modx->config['currency'] = $signs[array_search($_SESSION['currency'], $currency)];

        // ===== Ajax роутер =====
        $isAjaxHdr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) : '';
        if ($isAjaxHdr === 'xmlhttprequest') {
            require MODX_BASE_PATH . "/assets/logic/plugin.ajax.php";
            die;
        }

        /**
         * ===== Отзывы =====
         */
        if (isset($_POST['review']) && count($_POST['review']) > 0) {


            $key = '6LcQFBUUAAAAAGkLzriVv95KWCZX0udFRgJTelOH';
            
            $respToken = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
            $out = @file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=" . $key .
                "&response=" . $respToken .
                "&remoteip=" . $_SERVER['REMOTE_ADDR']
            );
            $out = json_decode($out, true);


            if (isset($out['success']) && $out['success'] === true) {
                $class->setReview($_POST['review']);
                $page = $modx->getDocument($_POST['review']['page']);
                $_POST['review']['pagetitle'] = isset($page['pagetitle']) ? $page['pagetitle'] : '';
                $body = $modx->parseChunk("mail_review", $_POST['review']);

                // "Новый отзыв на сайте - " (196)
                $subject = T(196) . $modx->config['site_name'];
                $class->sendMail($modx->config['emailsender'], $subject, $body);
                $class->sendMail('topstatusrealty@gmail.com', $subject, $body);

                // "Отзыв оставлен..." (193)
                $js = "[#193#]";
            } else {
                // "Вы не прошли проверку" (194)
                $js = "[#194#]";
            }
        }

        /**
         * ===== Резюме =====
         */
        if (isset($_POST['vacancy']) && count($_POST['vacancy']) > 0) {
            $_SESSION['hash']['vacancy'] = md5(serialize($_POST['vacancy']));
            $body = $modx->parseChunk("mail_vacancy", $_POST['vacancy']);

            // "Резюме" (197)
            $fname = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : '';
            $ftmp  = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';
            $class->sendMail($modx->config['emailsender'], T(197), $body, $fname, $ftmp);
            $class->sendMail('topstatusrealty@gmail.com', T(197), $body, $fname, $ftmp);

            // "Резюме отправлено успешно" (195)
            $js = "[#195#]";
        }

        /**
         * ===== Предложить цену =====
         */
        if (isset($_POST['offer']) && count($_POST['offer']) > 0) {

            /** проверка капчи **/
            $respToken = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
            $out = false;
            if ($curl = curl_init()) {
                curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, "secret=6LcQFBUUAAAAAGkLzriVv95KWCZX0udFRgJTelOH&response=" . $respToken . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
                $out = curl_exec($curl);
                $out = json_decode($out, true);
                curl_close($curl);
            }

            if (isset($out['success']) && $out['success'] === true) {
                $flat  = $class->getFlat($_POST['offer']['object']);
                $agent = $class->getAgents($_POST['offer']['id']);
                $body  = $modx->parseChunk("mail_offer", array(
                    "name"      => $_POST['offer']['name'],
                    "email"     => $_POST['offer']['email'],
                    "mess"      => $_POST['offer']['message'],
                    "phone"     => $_POST['offer']['phone'],
                    "yourprice" => $_POST['offer']['yourprice'],
                    "flat_url"  => $_POST['offer']['flat_url'],
                    "flat_name" => $_POST['offer']['flat_name'],
                ));

                // "Запрос с сайта " (198)
                $subject = T(198) . $modx->config['site_name'];
                $class->sendMail('toprealtor.ua@gmail.com', $subject, $body);
                $class->sendMail('topstatusrealty@gmail.com', $subject, $body);

               
                // Добавка пользователя в мейлер
                $sql = "INSERT INTO `modx_a_mailer_users` SET `user_name` = '" . $modx->db->escape($_POST['offer']['name']) . "', user_email = '" . $modx->db->escape($_POST['offer']['email']) . "' ON DUPLICATE KEY UPDATE user_email = user_email";
                $modx->db->query($sql);

                // "Ваше предложение отправленно." (199)
                $js = "[#199#]";
            } else {
                // "Вы не прошли проверку" (200)
                $js = "[#200#]";
            }
        }

        /**
         * ===== Обращение к агенту =====
         */
        // if (isset($_POST['agent']) && count($_POST['agent']) > 0) {

        //     $respToken = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        //     $out = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcQFBUUAAAAAGkLzriVv95KWCZX0udFRgJTelOH&response=" . $respToken . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        //     $out = json_decode($out, true);

        //     if (isset($out['success']) && $out['success'] === true) {
        //         $flat  = $class->getFlat($_POST['agent']['object']);
        //         $agent = $class->getAgents($_POST['agent']['id']);
        //         $body  = $modx->parseChunk("mail_agent", array(
        //             "name"      => $_POST['agent']['name'],
        //             "email"     => $_POST['agent']['email'],
        //             "mess"      => $_POST['agent']['message'],
        //             "phone"     => $_POST['agent']['phone'],
        //             "flat_url"  => $flat['url'],
        //             "flat_name" => $flat['name'],
        //         ));

        //         // "Запрос с сайта " (198)
        //         $subject = T(198) . $modx->config['site_name'];
        //        //$class->sendMail($agent[0]['tv_email'], $subject, $body);
        //         //$class->sendMail('topstatusrealty@gmail.com', $subject, $body);
        //         $class->sendMail('GontarALek@gmail.com', $subject, $body);

        //         // Добавка пользователя в мейлер
        //         $sql = "INSERT INTO `modx_a_mailer_users` SET `user_name` = '" . $modx->db->escape($_POST['agent']['name']) . "', user_email = '" . $modx->db->escape($_POST['agent']['email']) . "' ON DUPLICATE KEY UPDATE user_email = user_email";
        //         $modx->db->query($sql);

        //         // "Запрос к агенту отправлен успешно" (201)
        //         $js = "[#201#]";
        //     } else {
        //         // "Вы не прошли проверку" (200)
        //         $js = "[#200#]";
        //     }
        // }
        if (isset($_POST['agent']) && is_array($_POST['agent']) && count($_POST['agent']) > 0 && isset($_POST['g-recaptcha-response'])) {

            // === Настройки ===
            // ВАЖНО: секрет лучше хранить в конфиге и ротировать, если он "светился"
            $recaptchaSecret = $modx->config['recaptcha_secret'] ?? '6LcylHcsAAAAAHyyhXtnzYeBG1Xc53WgEA5Sk9qE';

            $expectedHosts = [
                'toprealtor.com.ua',
                'www.toprealtor.com.ua',
            ];

            // === CSRF check (поддержка 2 полей: csrf_agent и csrf_tab_agent) ===
            $csrfField = '';
            if (isset($_POST['csrf_agent'])) {
                $csrfField = 'csrf_agent';
            } elseif (isset($_POST['csrf_tab_agent'])) {
                $csrfField = 'csrf_tab_agent';
            }

            $csrfPost = $csrfField !== '' ? (string)$_POST[$csrfField] : '';
            $csrfSess = ($csrfField !== '' && isset($_SESSION[$csrfField])) ? (string)$_SESSION[$csrfField] : '';

            if ($csrfField === '' || $csrfPost === '' || $csrfSess === '' || !hash_equals($csrfSess, $csrfPost)) {
                // "Вы не прошли проверку" (200)
                $js = "[#200#]";
            } else {

                // === reCAPTCHA token ===
                $respToken = isset($_POST['g-recaptcha-response']) ? trim((string)$_POST['g-recaptcha-response']) : '';
                if ($respToken === '') {
                    $js = "[#200#]";
                } else {

                    // === Verify reCAPTCHA (POST + таймауты) ===
                    $out = null;

                    if ($curl = curl_init('https://www.google.com/recaptcha/api/siteverify')) {
                        $postData = http_build_query([
                            'secret'   => $recaptchaSecret,
                            'response' => $respToken,
                            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
                        ]);

                        curl_setopt_array($curl, [
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST           => true,
                            CURLOPT_POSTFIELDS     => $postData,
                            CURLOPT_TIMEOUT        => 5,
                            CURLOPT_CONNECTTIMEOUT => 3,
                            CURLOPT_SSL_VERIFYPEER => true,
                        ]);

                        $raw = curl_exec($curl);
                        $err = curl_errno($curl);
                        curl_close($curl);

                        if ($err === 0 && is_string($raw) && $raw !== '') {
                            $out = json_decode($raw, true);
                        }
                    }

                    $ok = is_array($out) && $out['success'] === true;

                    // === Hostname check (domain) ===
                    if ($ok) {
                        $host = isset($out['hostname']) ? (string)$out['hostname'] : '';
                        if ($host === '' || !in_array($host, $expectedHosts, true)) {
                            $ok = false;
                        }
                    }

                    if ($ok) {

                        // === Нормализация входных данных ===
                        $agentId  = isset($_POST['agent']['id']) ? (int)$_POST['agent']['id'] : 0;
                        $objectId = isset($_POST['agent']['object']) ? (int)$_POST['agent']['object'] : 0;

                        $name    = trim((string)($_POST['agent']['name'] ?? ''));
                        $email   = trim((string)($_POST['agent']['email'] ?? ''));
                        $message = trim((string)($_POST['agent']['message'] ?? ''));
                        $phone   = trim((string)($_POST['agent']['phone'] ?? ''));

                        // Ограничение длины сообщения
                        if (function_exists('mb_strlen') && mb_strlen($message, 'UTF-8') > 5000) {
                            $message = mb_substr($message, 0, 5000, 'UTF-8');
                        } elseif (strlen($message) > 5000) {
                            $message = substr($message, 0, 5000);
                        }

                        // (Опционально) базовая проверка email (можно включить при желании)
                        // if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        //     $js = "[#200#]";
                        // } else {

                        $flat  = $class->getFlat($objectId);
                        $agent = $class->getAgents($agentId);

                        $body = $modx->parseChunk("mail_agent", [
                            "name"      => $name,
                            "email"     => $email,
                            "mess"      => $message,
                            "phone"     => $phone,
                            "flat_url"  => $flat['url'] ?? '',
                            "flat_name" => $flat['name'] ?? '',
                        ]);

                        // "Запрос с сайта " (198)
                        $subject = T(198) .'-'. ($modx->config['site_name'] ?? '');

                        // ТЕСТ: отправка на фиксированный адрес
                        $class->sendMail($agent[0]['tv_email'], $subject, $body);
                        $class->sendMail('topstatusrealty@gmail.com', $subject, $body);
                         $class->sendMail('GontarALek@gmail.com', $subject, $body);

                        // Добавка пользователя в мейлер
                        $sql = "INSERT INTO `modx_a_mailer_users`
                                SET `user_name` = '" . $modx->db->escape($name) . "',
                                    `user_email` = '" . $modx->db->escape($email) . "'
                                ON DUPLICATE KEY UPDATE `user_email` = `user_email`";
                        $modx->db->query($sql);

                        // "Запрос к агенту отправлен успешно" (201)
                        $js = "[#201#]";

                        // Сделать CSRF одноразовым после УСПЕШНОЙ отправки
                        unset($_SESSION[$csrfField]);

                        // } // конец опциональной проверки email

                    } else {
                        // "Вы не прошли проверку" (200)
                        $js = "[#200#]";
                        // (Опционально) лог: $out['error-codes']
                    }
                }
            }
        }
        /**
         * ===== Регистрация =====
         */
        if (!empty($_POST['reg']) && (!isset($_SESSION['hash']['reg']) || $_SESSION['hash']['reg'] != md5(serialize($_POST['reg'])))) {
            $_SESSION['hash']['reg'] = md5(serialize($_POST['reg']));
            $dat   = $_POST['reg'];
            $email = filter_var($dat['mail'], FILTER_SANITIZE_EMAIL);

            if (trim($dat['name']) == "") {
                $js = "[#202#]"; // Укажите имя.
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $js = "[#203#]"; // Укажите email
            }
            if (!isset($_POST['agree'])) {
                $js = "[#204#]"; // Подтвердите разрешение ...
            }

            if ($js == "") {
                $user          = $class->getUser($dat['mail']);
                $dat['pass']   = substr(uniqid(), -6, 6);
                $dat['active'] = uniqid();
                if ($user) {
                    $js = "[#205#]"; // Пользователь уже зарегистрирован
                } else {
                    $data = array(
                        "username" => microtime(),
                        "password" => md5($dat['pass']),
                        "email"    => $modx->db->escape($dat['mail']),
                        "fullname" => $modx->db->escape($dat['name']),
                        "active"   => $dat['active'],
                    );
                    $class->setUser($data);
                    $body = $modx->parseChunk("mail_reg", array("email" => $email, "password" => $dat['pass'], "active" => $dat['active']));
                    $class->sendMail($email, $modx->config['site_name'], $body);

                    $js    = "[#206#]"; // Вы успешно зарегистрировались...
                    $title = T(207);    // Спасибо
                }
            }
        } elseif (!empty($_POST['reg'])) {
            // служебная диагностика
            $js = 'Произошло зацикливание регистрации';
        }

        /**
         * ===== Активация аккаунта =====
         */
        if (isset($_GET['active_reg']) && $_GET['active_reg'] != '') {
            $user = $class->getUsetActive($_GET['active_reg']);
            $js   = $user ? "[#208#]" : "[#209#]"; // Аккаунт активирован / Код не действительный
        }

        /**
         * ===== Авторизация =====
         */
        if (!empty($_REQUEST['auth'])) {
            $user = $modx->runSnippet("Auth", array("email" => $_REQUEST['auth']['email'], "pass" => $_REQUEST['auth']['pass']));
            switch ($user) {
                case 11:     $js = "[#210#]"; break; // Пользователь не найден
                case 13:     $js = "[#211#]"; break; // Неправильный пароль
                case 159262: $js = "[#212#]"; break; // Аккаунт не активирован
                case 159263: $js = "[#213#]"; break; // Успешная авторизация
            }
        }

        /**
         * ===== Восстановление пароля =====
         */
        if (isset($_POST['recovery']) && filter_var($_POST['recovery'], FILTER_VALIDATE_EMAIL)) {
            $pwd     = $class->gen_pass(6);
            $email   = filter_var($_POST['recovery'], FILTER_SANITIZE_EMAIL);
            $cachpwd = md5($pwd);
            $class->changePassUser($cachpwd, $email);
            if ($modx->db->getAffectedRows() > 0) {
                $body = $modx->parseChunk("mail_recovery", array("password" => $pwd, "confirm_url" => $modx->makeUrl(1) . "?recovery=" . md5($pwd)));
                $class->sendMail($email, T(214) . $modx->config['site_name'], $body); // тема

                $js = "[#215#]"; // Новый пароль выслан...
            } else {
                $js = "[#216#]"; // Пользователя не существует
            }
        }

        /**
         * ===== Активация нового пароля =====
         */
        if (isset($_GET['recovery']) && strlen($_GET['recovery']) == 32) {
            $class->setPassword($_GET['recovery']);
            if ($modx->db->getAffectedRows() > 0) {
                $js = "[#217#]"; // Пароль успешно изменен
            } else {
                $js = "[#218#]"; // Ссылка не действительна
            }
        }

        /**
         * ===== Выход =====
         */
        if (isset($_GET['logout'])) {
            unset(
                $_SESSION['webShortname'],
                $_SESSION['webFullname'],
                $_SESSION['webEmail'],
                $_SESSION['webValidated'],
                $_SESSION['webInternalKey'],
                $_SESSION['webValid'],
                $_SESSION['webUser'],
                $_SESSION['webFailedlogins'],
                $_SESSION['webLastlogin'],
                $_SESSION['webnrlogins'],
                $_SESSION['webUsrConfigSet'],
                $_SESSION['webUserGroupNames'],
                $_SESSION['webDocgroups'],
                $_SESSION['webuser'],
                $_SESSION['cart'],
                $_SESSION['order_num'],
                $_SESSION['order_cost']
            );
            die(header("Location: " . $modx->config['site_url']));
        }

        /**
         * ===== Авторизация редактора =====
         */
        if (isset($_GET['auth']) && isset($_SESSION['mgrRole']) && $_SESSION['mgrRole'] == 2) {
            $js = "[#219#]";
        }

        /**
         * ===== Отписка =====
         */
        if (isset($_GET['unsubscribe']) && (!isset($_SESSION['hash']['unsubscribe']) || $_SESSION['hash']['unsubscribe'] != md5(serialize($_GET['unsubscribe'])))) {
            $modx->db->query('DELETE FROM `modx_a_mailer_users` WHERE user_email = "' . $modx->db->escape($_GET['unsubscribe']) . '"');
            $_SESSION['hash']['unsubscribe'] = md5(serialize($_GET['unsubscribe']));
            $js = "[#220#]";
        }

        /**
         * ===== Js -> alert =====
         */
        if (!empty($js)) {
            // Декодируем сущности и безопасно прокидываем в JS
            $msg   = html_entity_decode($modx->parseDocumentSource($js), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $msgJs = json_encode($msg, JSON_UNESCAPED_UNICODE);
            $modx->regClientScript("<script>jQuery(function(){ alert($msgJs); });</script>");
        }

        // ===== роутинг для страницы объекта =====
        $qParam = isset($_GET['q']) ? $_GET['q'] : '';
        $parts  = explode("/", $qParam);
        if (!empty($parts) && end($parts) == "") {
            unset($parts[count($parts) - 1]);
        }

        /**
         * Страница объекта (форвард на id=134, если нужен алиас)
         */
        $first = reset($parts);
        if ((isset($modx->documentListing[$first]) ? $modx->documentListing[$first] : null) == 134 && !empty($parts) && end($parts) != '') {
            $modx->sendForward(134);
            die;
        }

        break;

    case 'OnWebPagePrerender':
        // $documentOutput = $modx->runSnippet("minify", array("input" => $modx->documentOutput));
        if (!empty($documentOutput)) {
            $modx->documentOutput = $documentOutput;
        }
        break;

    case "OnLoadWebDocument":
        $modx->documentObject['authorized'] = 'no';

        if (isset($_SESSION["usertype"]) && $_SESSION["usertype"] == 'manager') {
            $modx->documentObject['admin_login'] = 'yes';
            $modx->documentObject['authorized']  = 'yes';
        }

        if (!empty($_SESSION['webuser']) && is_array($_SESSION['webuser'])) {
            foreach ($_SESSION['webuser'] as $k => $v) {
                $modx->documentObject['user_' . $k] = $v;
            }
            $modx->documentObject['authorized'] = 'yes';
        }

        if (isset($_SESSION['mgrRole'])) {
            $modx->documentObject['role_editor'] = true;
        }

        // записываем GET
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if (is_array($value)) {
                    $modx->documentObject['get_' . $key] = implode(",", $value);
                } else {
                    $modx->documentObject['get_' . $key] = $value;
                }
            }
        }

        // кол-во товаров в избранном
        if (!empty($_COOKIE['favorites'])) {
            $favorites = json_decode($_COOKIE['favorites'], true);
            $favorites = is_array($favorites) ? $favorites : array();
            $modx->documentObject['favorites']     = implode(",", $favorites);
            $modx->documentObject['favorites_cnt'] = count($favorites) > 0 ? 1 : 0;
        } else {
            $modx->documentObject['favorites_cnt'] = 0;
        }

        // ===== Один объект =====
        if (isset($modx->documentObject['id']) && $modx->documentObject['id'] == 134) {
            $modx->documentObject['page_id'] = $modx->documentObject['id'];
            $parts = explode("/", isset($_GET['q']) ? $_GET['q'] : '');
            if (!empty($parts) && end($parts) == "") {
                unset($parts[count($parts) - 1]);
            }

            $alias = !empty($parts) ? end($parts) : '';
            $flat  = $class->getFlat(0, $alias);


            if ($flat['id'] > 0) {
                $agent = $class->getAgents($flat['agent']);
                if (!empty($agent[0]['tv_mobile_phone'])) {
                        $flat['agent_mobile_phone'] = preg_replace('/\D/', '', $agent[0]['tv_mobile_phone']);
                }
            }

  

            // скрытая продажа
            $isAdminLogin = isset($modx->documentObject['admin_login']) && $modx->documentObject['admin_login'] == 'yes';
            if (!$isAdminLogin && isset($flat['status']) && $flat['status'] == 5) {
                $modx->sendRedirect($modx->makeUrl(133));
            }

            // Download file
            if (isset($_GET['dowload']) && $_GET['dowload'] != '' && isset($_SESSION['mgrRole'])) {
                $file_url = MODX_BASE_PATH . "assets/files/flat/" . $alias . "/" . $_GET['dowload'];
                if (file_exists($file_url)) {
                    header("Content-Type: application/octet-stream");
                    header("Accept-Ranges: bytes");
                    header("Content-Length: " . filesize($file_url));
                    header("Content-Disposition: attachment; filename=" . $_GET['dowload']);
                    readfile($file_url);
                    die;
                }
            }

            // Download images (zip)
            if (isset($_GET['images']) && (isset($_SESSION['mgrRole']) || (isset($_SESSION['webuser']['internalKey']) && $_SESSION['webuser']['internalKey']))) {
                if (extension_loaded('zip')) {
                    $images = $class->getImagesFlat($flat['id']);
                    $list_images = array();
                    if (is_array($images) && count($images) > 0) {
                        foreach ($images as $image) {
                            $list_images[] = $modx->runSnippet("R", array(
                                "img" => "assets/images/flat/" . $flat['id'] . "/" . $image['image'],
                                "opt" => "w=1920,h=1080,zc=1,fltr_wmi|/images/watermark.png|C|100|51|51"
                            ));
                        }
                    }
                    if (count($list_images) > 0) {
                        $zip      = new ZipArchive();
                        $zip_name = MODX_BASE_PATH . "assets/files/zip/" . time() . ".zip";
                        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                            $error = "* Sorry ZIP creation failed at this time";
                        }
                        foreach ($list_images as $f) {
                            $filename = explode("/", $f);
                            $filename = end($filename);
                            $zip->addFile(MODX_BASE_PATH . $f, $filename);
                        }
                        $zip->close();
                        if (file_exists($zip_name)) {
                            header("Content-Type: application/octet-stream");
                            header("Accept-Ranges: bytes");
                            header("Content-Length: " . filesize($zip_name));
                            header("Content-Disposition: attachment; filename=images.zip");
                            readfile($zip_name);
                            unlink($zip_name);
                            die;
                        }
                    }
                }
            }

            // View one object
            if ($flat && isset($flat['active']) && $flat['active'] == 1) {

                $flat['tv_seo_title']      = $flat['tv_seo_title'] != '' ? $flat['tv_seo_title'] : $flat['name'] . " | " .  $flat['id'] . " | " . $modx->config['site_name'];
                $flat['price']             = $class->getPrice($flat['price'], $modx->config['course']);
                $flat['old_price']         = $class->getPrice($flat['old_price'], $modx->config['course']);
                $flat['updated_at']        = date("d/m/Y", strtotime($flat['updated_at']));
                $flat['description_intro'] = $modx->db->escape($flat['description']);
               

                if (isset($flat['description']) && strlen($flat['description']) > 100) {
                    $flat['desc_more'] = true;
                }

                $video_id = array();
                preg_match('/[A-z0-9_-]{11}/', isset($flat['video']) ? $flat['video'] : '', $video_id);
                if (is_array($video_id) && count($video_id) > 0 ) {
                    $flat['video'] = end($video_id);
                }

                // характеристики
                if (!empty($flat['character']) && is_array($flat['character'])) {
                    $character = '';
                    foreach ($flat['character'] as $c) {
                        if ($c != '') {
                            $character .= $modx->parseChunk('tpl_character', array("c" => $c));
                        }
                    }
                    $flat['character'] = $character;
                }

                // добавляем просмотр
                $class->setFlat(array("views" => ($flat['views'] + 1), "active" => $flat['active'], "cottage_view" => $flat['cottage_view']), $flat['id']);

                // формируем данные для вывода на страницу
                if (!empty($flat['categories']) && is_array($flat['categories'])) {
                    $flat['cat'] = array_keys($flat['categories']);
                    $flat['cat'] = end($flat['cat']);
                }

                // кол-во файлов (для админов)
                if (file_exists(MODX_BASE_PATH . "assets/files/flat/" . $flat['id'])) {
                    $files                 = glob(MODX_BASE_PATH . "assets/files/flat/" . $flat['id'] . "/*");
                    $flat['manager_files'] = is_array($files) ? count($files) : 0;
                } else {
                    $flat['manager_files'] = 0;
                }

                // объединяем с documentObject
                $modx->documentObject = array_merge($modx->documentObject, $flat);



                // рейтинг (если 0 — пересчитываем)
                if (!isset($flat['rating']) || $flat['rating'] == 0) {
                    $rating = $modx->db->makeArray($modx->db->query("SELECT SQL_CALC_FOUND_ROWS *, rating FROM `modx_a_rating` WHERE page_id = " . (int)$flat['id']));
                    $count  = $modx->db->getValue($modx->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
                    $raty   = 0;
                    if (is_array($rating)) {
                        foreach ($rating as $value) { $raty += $value['rating']; }
                    }
                    if ($count != 0) { $raty = $raty / $count; }
                    $modx->documentObject['rating'] = ceil($raty);
                }

                // сессия для редактирования
                if (isset($_SESSION['mgrRole'])) {
                    $_SESSION['edit_object'] = $flat['id'];
                }
            } else {
                $modx->sendRedirect($modx->makeUrl(133));
            }
        }

        // ===== Правка даты на странице "Новостройки Харькова" =====
        if (isset($modx->documentObject['id']) && $modx->documentObject['id'] == 530) {
            $modx->documentObject['pub_date'] = time();
        }

        // ===== Печать / PDF на странице 488 =====
        if (isset($modx->documentObject['id']) && $modx->documentObject['id'] == 488) {
            if (isset($_GET['print']) && $_GET['print'] != '') {
                $item = $class->getFlat($_GET['print']);
                if ($item) {
                    if (isset($modx->documentObject['admin_login']) && $modx->documentObject['admin_login'] == 'yes' && isset($_REQUEST['w']) && $_REQUEST['w'] == 0) {
                        $item['watermark'] = 0;
                    }
                    if (isset($modx->documentObject['admin_login']) && $modx->documentObject['admin_login'] == 'yes' && isset($_REQUEST['a']) && $_REQUEST['a'] == 0) {
                        $item['agent'] = 0;
                    }
                    $item['tv_seo_title'] = $item['tv_seo_title'] != '' ? $item['tv_seo_title'] : $item['name'] . " | " . $modx->config['site_name'];
                    $item['price']        = $class->getPrice($item['price'], $modx->config['course']);
                    $item['old_price']    = $class->getPrice($item['old_price'], $modx->config['course']);
                    $item['updated_at']   = date("d/m/Y", strtotime($item['updated_at']));
                    if (!empty($item['character']) && is_array($item['character'])) {
                        $character = '';
                        foreach ($item['character'] as $c) {
                            if ($c != '') {
                                $character .= $modx->parseChunk('tpl_character', array("c" => $c));
                            }
                        }
                        $item['character'] = $character;
                    }
                    $modx->documentObject = array_merge($modx->documentObject, $item);
                } else {
                    header("Location: " . $modx->makeUrl(133));
                }
            } elseif (isset($_GET['pdf']) && $_GET['pdf'] != '') {
                $item = $class->getFlat($_GET['pdf']);
                if ($item) {
                    if (isset($modx->documentObject['admin_login']) && $modx->documentObject['admin_login'] == 'yes' && isset($_REQUEST['w']) && $_REQUEST['w'] == 0) {
                        $item['watermark'] = 0;
                    }
                    if (isset($modx->documentObject['admin_login']) && $modx->documentObject['admin_login'] == 'yes' && isset($_REQUEST['a']) && $_REQUEST['a'] == 0) {
                        $item['agent'] = 0;
                    }
                    $item['tv_seo_title'] = $item['tv_seo_title'] != '' ? $item['tv_seo_title'] : $item['name'] . " | " . $modx->config['site_name'];
                    $item['price']        = $class->getPrice($item['price'], $modx->config['course']);
                    $item['old_price']    = $class->getPrice($item['old_price'], $modx->config['course']);
                    $item['updated_at']   = date("d/m/Y", strtotime($item['updated_at']));
                    if (!empty($item['character']) && is_array($item['character'])) {
                        $character = '';
                        foreach ($item['character'] as $c) {
                            if ($c != '') {
                                $character .= $modx->parseChunk('tpl_character', array("c" => $c));
                            }
                        }
                        $item['character'] = $character;
                    }

                    $pdf = $modx->parseChunk("tpl_pdf_dowload", $item);
                    $pdf = $modx->parseDocumentSource($pdf);
                    include MODX_BASE_PATH . "assets/logic/php/mpdf/mpdf.php";
                    $mpdf             = new mPDF('utf-8', 'A4', '10', 'Arial', 0, 0, 5, 5, 5, 5);
                    $mpdf->charset_in = 'utf-8';
                    $mpdf->WriteHTML($pdf);
                    $mpdf->Output();
                    exit;
                } else {
                    header("Location: " . $modx->makeUrl(133));
                }
            } else {
                header("Location: " . $modx->makeUrl(133));
            }
        }

        // ===== Страница списка объектов (template = 4) =====
        if (isset($modx->documentObject['template']) && $modx->documentObject['template'] == 4) {
            $parents = $modx->getParentIds($modx->documentObject['id']);
            $parents = array_keys($parents);
            
            // Для уникальности title
            $parent_id = $parents;
            array_pop($parent_id);
            $par_id_for_mid_name = end($parent_id);

            if ((int)$par_id_for_mid_name > 0) {
                $mid_name = current($modx->getDocument($par_id_for_mid_name, 'pagetitle'));
                if (trim($modx->documentObject['pagetitle']) != trim($mid_name)) {
                    $modx->documentObject['pagetitle'] = $modx->documentObject['pagetitle'] . ' | ' . $mid_name;
                }
            }

            if (isset($modx->documentObject['tv_seo_title'][1]) && $modx->documentObject['tv_seo_title'][1] == '[*pagetitle*] | [(site_name)]' && (int)$par_id_for_mid_name > 0) {
                $mid_name = current($modx->getDocument($par_id_for_mid_name, 'pagetitle'));
                $modx->documentObject['tv_seo_title'][1] = '[*pagetitle*] | [(site_name)]';
            }
        }

        // ===== Страница списка объектов (template = 9) =====
        if (isset($modx->documentObject['template']) && $modx->documentObject['template'] == 9) {
            $parents = $modx->getParentIds($modx->documentObject['id']);
            $parents = array_keys($parents);

            // Для уникальности title
            $parent_id = $parents;
            array_pop($parent_id);
            $par_id_for_mid_name = end($parent_id);

            if ((int)$par_id_for_mid_name > 0) {
                $mid_name = current($modx->getDocument($par_id_for_mid_name, 'pagetitle'));
                if (trim($modx->documentObject['pagetitle']) != trim($mid_name)) {
                    $modx->documentObject['pagetitle'] = $modx->documentObject['pagetitle'] . ' | ' . $mid_name;
                }
            }

            if (isset($modx->documentObject['tv_seo_title'][1]) && $modx->documentObject['tv_seo_title'][1] == '[*pagetitle*] | [(site_name)]' && (int)$par_id_for_mid_name > 0) {
                $mid_name = current($modx->getDocument($par_id_for_mid_name, 'pagetitle'));
                $modx->documentObject['tv_seo_title'][1] = '[*pagetitle*] | [(site_name)]';
            }

            $modx->documentObject['object_type_select'] = end($parents);
            // активность типов объектов
            if ($modx->documentObject['object_type_select'] != false && $modx->documentObject['object_type_select'] != 6) {
                $modx->documentObject['object_type_select_1'] = 7  == $modx->documentObject['object_type_select'] ? 'selected' : '';
                $modx->documentObject['object_type_select_2'] = 8  == $modx->documentObject['object_type_select'] ? 'selected' : '';
                $modx->documentObject['object_type_select_3'] = 9  == $modx->documentObject['object_type_select'] ? 'selected' : '';
                $modx->documentObject['object_type_select_4'] = 11 == $modx->documentObject['object_type_select'] ? 'selected' : '';
                $modx->documentObject['object_type_select_5'] = 10 == $modx->documentObject['object_type_select'] ? 'selected' : '';
                $modx->documentObject['object_type_select_6'] = 12 == $modx->documentObject['object_type_select'] ? 'selected' : '';
            }
            $modx->documentObject['catalog'] = $modx->runSnippet("S", array("get" => "getFlat", "tpl" => "tpl_flat_one", "limit" => 10));
        }

        // ===== Пагинация — добавка к title =====
        if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
            $modx->documentObject['tv_seo_title'][1] .= T(221) . ((int)$_GET['page'] + 1); // " | Страница "
        }

        break;

    case "OnCacheUpdate": // выполняется при сбросе кеша сайта в админке
        if (isset($_GET['a']) && $_GET['a'] == 26) { // иначе будет выполняться при любом чихе в админке
            // очищаем memcache
            $mem = new Memcache;
            $mem->connect('127.0.0.1', 11211);
            $mem->flush();
            $mem->close();
        }
        break;

    case 'OnMakePageCacheKey':
        // Кеш для кастомных страниц
        $modx->event->output($modx->event->params['id'] . '_' . md5($_SERVER['REQUEST_URI']));
        break;
}
