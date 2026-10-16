<?php
/**
 * Chunkie / prePHx
 * PHP 7.0 compatible rewrite (hardened + Ditto-chain compatible)
 */

class prePHx
{
    /** @var string */
    public $template = '';

    /** @var PHxParser|null */
    public $phx = null;

    /** @var string */
    public $phxreq = '2.0.0';

    /** @var string */
    public $phxerror = '';

    /** @var bool */
    public $check = false;

    public function __construct($template = '')
    {
        // не типизируем аргумент, чтобы не словить TypeError из старого кода
        $this->template = (string)$template;

        if (!class_exists('PHxParser', false)) {
            $file = strtr(realpath(dirname(__FILE__)) . '/phx.parser.class.inc.php', '\\', '/');
            if (is_file($file)) {
                include_once $file;
            }
        }

        if (class_exists('PHxParser', false)) {
            $this->phx = new PHxParser();
        }

        $this->phxerror =
            '<div style="border: 1px solid red;font-weight: bold;margin: 10px;padding: 5px;">
                Error! This MODX installation is running an older version of the PHx plugin.<br /><br />
                Please update PHx to version ' . $this->phxreq . ' or higher.<br />
                OR - Disable the PHx plugin in the MODX Manager. (Manage Resources -> Plugins)
            </div>';

        $currentVersion = null;
        if ($this->phx && isset($this->phx->version)) {
            $currentVersion = (string)$this->phx->version;
        }

        // сравнение версий безопаснее чем ($a < $b)
        $this->check = ($currentVersion !== null && version_compare($currentVersion, $this->phxreq, '>='));
    }

    /**
     * Convert any scalar/array/object to safe string for PHx.
     */
    protected function normalizeValue($value)
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string)$value;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string)$value;
            }
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_resource($value)) {
            return '';
        }

        return (string)$value;
    }

    /**
     * Ditto expects this to be chain-friendly in some places,
     * so we return $this (like old code implicitly allowed).
     */
    public function setPlaceholders($value = '', $key = '', $path = '')
    {
        // chain-safe even if PHx not loaded
        if (!$this->phx) {
            return $this;
        }

        $key  = (string)$key;
        $path = (string)$path;

        $keypath = ($path !== '' && $key !== '') ? ($path . '.' . $key) : ($path !== '' ? $path : $key);

        if (is_array($value)) {
            foreach ($value as $subkey => $subval) {
                $this->setPlaceholders($subval, (string)$subkey, $keypath);
            }
            return $this;
        }

        if ($keypath === '') {
            return $this;
        }

        // sanitize placeholder name a bit
        $keypath = preg_replace('/[^\w\.\-]/u', '_', $keypath);

        $safeValue = $this->normalizeValue($value);

        $this->phx->setPHxVariable($keypath, $safeValue);

        return $this;
    }

    public function output()
    {
        if (!$this->check || !$this->phx) {
            return $this->phxerror;
        }

        return (string)$this->phx->Parse($this->template);
    }
}
