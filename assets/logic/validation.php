<?php

if (!class_exists('FormSecurity')) {
    class FormSecurity
    {
        protected $sessionGuardRoot = '_form_guards';

        /**
         * Проверка CSRF по одному или нескольким возможным полям.
         * Пример: ['csrf_agent', 'csrf_tab_agent']
         */
        public function verifyCsrf(array $allowedFields)
        {
            $result = [
                'ok'      => false,
                'field'   => '',
                'post'    => '',
                'session' => '',
                'reason'  => '',
            ];

            $csrfField = '';

            foreach ($allowedFields as $field) {
                if (isset($_POST[$field])) {
                    $csrfField = $field;
                    break;
                }
            }

            if ($csrfField === '') {
                $result['reason'] = 'csrf_field_not_found';
                return $result;
            }

            $csrfPost = isset($_POST[$csrfField]) ? (string)$_POST[$csrfField] : '';
            $csrfSess = isset($_SESSION[$csrfField]) ? (string)$_SESSION[$csrfField] : '';

            $result['field']   = $csrfField;
            $result['post']    = $csrfPost;
            $result['session'] = $csrfSess;

            if ($csrfPost === '') {
                $result['reason'] = 'csrf_post_empty';
                return $result;
            }

            if ($csrfSess === '') {
                $result['reason'] = 'csrf_session_empty';
                return $result;
            }

            if (!hash_equals($csrfSess, $csrfPost)) {
                $result['reason'] = 'csrf_mismatch';
                return $result;
            }

            $result['ok'] = true;
            return $result;
        }

        /**
         * Проверка reCAPTCHA v3: success / score / action.
         * Проверка hostname вынесена отдельно.
         */
        public function verifyRecaptchaV3($secret, $expectedAction = 'submit_form', $minScore = 0.5, $tokenField = 'g-recaptcha-response')
        {
            $token = isset($_POST[$tokenField]) ? trim((string)$_POST[$tokenField]) : '';

            $result = [
                'ok'        => false,
                'reason'    => '',
                'token'     => $token,
                'token_len' => strlen($token),
                'success'   => null,
                'score'     => null,
                'action'    => null,
                'hostname'  => null,
                'errors'    => [],
                'curl_err'  => 0,
                'raw'       => '',
                'response'  => null,
            ];

            if ($token === '') {
                $result['reason'] = 'empty_token';
                return $result;
            }

            if ($secret === '') {
                $result['reason'] = 'empty_secret';
                return $result;
            }

            $out = null;
            $raw = '';
            $err = 0;

            if ($curl = curl_init('https://www.google.com/recaptcha/api/siteverify')) {
                $postData = http_build_query([
                    'secret'   => $secret,
                    'response' => $token,
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

            $result['curl_err'] = (int)$err;
            $result['raw']      = is_string($raw) ? $raw : '';
            $result['response'] = $out;

            if (!is_array($out)) {
                $result['reason'] = 'invalid_response';
                return $result;
            }

            $result['success']  = !empty($out['success']);
            $result['score']    = $out['score'] ?? null;
            $result['action']   = $out['action'] ?? null;
            $result['hostname'] = $out['hostname'] ?? null;
            $result['errors']   = !empty($out['error-codes']) ? (array)$out['error-codes'] : [];

            if (empty($out['success'])) {
                $result['reason'] = 'google_success_false';
                return $result;
            }

            if (($out['score'] ?? 0) < $minScore) {
                $result['reason'] = 'score_too_low';
                return $result;
            }

            if (($out['action'] ?? '') !== $expectedAction) {
                $result['reason'] = 'action_mismatch';
                return $result;
            }

            $result['ok'] = true;
            return $result;
        }

        /**
         * Отдельная проверка hostname/domain из ответа reCAPTCHA.
         */
        public function verifyRecaptchaHostname(array $recaptchaResult, array $expectedHosts)
        {
            $host = isset($recaptchaResult['hostname']) ? (string)$recaptchaResult['hostname'] : '';

            $result = [
                'ok'            => false,
                'reason'        => '',
                'hostname'      => $host,
                'expectedHosts' => $expectedHosts,
            ];

            if ($host === '') {
                $result['reason'] = 'empty_hostname';
                return $result;
            }

            if (!in_array($host, $expectedHosts, true)) {
                $result['reason'] = 'hostname_mismatch';
                return $result;
            }

            $result['ok'] = true;
            return $result;
        }

        /**
         * Защита от дубля/зацикливания одного и того же запроса.
         * Ловит повторную отправку одинаковых данных в течение TTL.
         */
        public function guardAgainstLoop($guardKey, array $parts, $ttlSeconds = 30)
        {
            $now = time();

            if (!isset($_SESSION[$this->sessionGuardRoot]) || !is_array($_SESSION[$this->sessionGuardRoot])) {
                $_SESSION[$this->sessionGuardRoot] = [];
            }

            foreach ($_SESSION[$this->sessionGuardRoot] as $key => $row) {
                $ts = isset($row['time']) ? (int)$row['time'] : 0;
                if ($ts < ($now - $ttlSeconds)) {
                    unset($_SESSION[$this->sessionGuardRoot][$key]);
                }
            }

            $normalized = [];

            foreach ($parts as $part) {
                if (is_scalar($part) || $part === null) {
                    $normalized[] = (string)$part;
                } else {
                    $normalized[] = json_encode($part, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }

            $fingerprint = hash('sha256', implode('|', $normalized));

            $result = [
                'ok'          => true,
                'reason'      => '',
                'guard_key'   => $guardKey,
                'fingerprint' => $fingerprint,
            ];

            if (
                isset($_SESSION[$this->sessionGuardRoot][$guardKey]['fingerprint'], $_SESSION[$this->sessionGuardRoot][$guardKey]['time']) &&
                $_SESSION[$this->sessionGuardRoot][$guardKey]['fingerprint'] === $fingerprint &&
                (int)$_SESSION[$this->sessionGuardRoot][$guardKey]['time'] >= ($now - $ttlSeconds)
            ) {
                $result['ok'] = false;
                $result['reason'] = 'duplicate_request';
                return $result;
            }

            $_SESSION[$this->sessionGuardRoot][$guardKey] = [
                'fingerprint' => $fingerprint,
                'time'        => $now,
            ];

            return $result;
        }

        /**
         * После успешной обработки можно снять CSRF из сессии.
         */
        public function clearCsrf($field)
        {
            if ($field !== '' && isset($_SESSION[$field])) {
                unset($_SESSION[$field]);
            }
        }

        /**
         * Удобный debug-string для reCAPTCHA.
         */
        public function recaptchaDebugString(array $recaptchaResult)
        {
            return 'success=' . (int)!empty($recaptchaResult['success'])
                . ' score=' . ($recaptchaResult['score'] ?? 'null')
                . ' action=' . ($recaptchaResult['action'] ?? 'null')
                . ' host=' . ($recaptchaResult['hostname'] ?? 'null')
                . ' errors=' . (!empty($recaptchaResult['errors']) ? implode(',', (array)$recaptchaResult['errors']) : 'none')
                . ' curl_err=' . (string)($recaptchaResult['curl_err'] ?? 0)
                . ' token_len=' . (string)($recaptchaResult['token_len'] ?? 0)
                . ' reason=' . ($recaptchaResult['reason'] ?? '');
        }

        /**
         * Вспомогательная обрезка длинных сообщений.
         */
        public function trimMessage($message, $maxLen = 5000)
        {
            $message = trim((string)$message);

            if (function_exists('mb_strlen') && mb_strlen($message, 'UTF-8') > $maxLen) {
                return mb_substr($message, 0, $maxLen, 'UTF-8');
            }

            if (strlen($message) > $maxLen) {
                return substr($message, 0, $maxLen);
            }

            return $message;
        }
    }
}