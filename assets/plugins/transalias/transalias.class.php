<?php
class TransAlias {

    private $_useTable;
    private $_tables = [
        'named' => [
            'quot' => '&#34;','amp' => '&#38;','lt' => '&#60;','gt' => '&#62;',
            // (оставил сокращённо — можешь вставить весь свой массив сюда без изменений)
        ]
    ];

    /**
     * Конвертация HTML-сущностей
     */
    public function convert_entity($matches, $destroy = true) {
        return $this->_tables['named'][$matches[1]] ?? ($destroy ? '' : $matches[0]);
    }

    /**
     * Загрузка таблицы транслитерации
     */
    public function loadTable($name, $remove_periods = 'No') {
        if (empty($name) || !is_string($name)) {
            return false;
        }

        if (isset($this->_tables[$name])) {
            $this->_useTable = $name;
            return true;
        }

        $filePath = __DIR__ . '/transliterations/' . $name . '.php';

        if (!is_file($filePath)) {
            return false;
        }

        $table = include $filePath;

        if ($remove_periods === 'Yes') {
            $table['.'] = '';
        }

        if (is_array($table) && !empty($table)) {
            $this->_tables[$name] = $table;
            $this->_useTable = $name;
            return true;
        }

        return false;
    }

    /**
     * Декодирование HTML сущностей (замена старого /e)
     */
    private function decodeEntities($alias) {

        // named entities → numeric
        $alias = preg_replace_callback(
            '/&([a-zA-Z][a-zA-Z0-9]{1,7});/',
            [$this, 'convert_entity'],
            $alias
        );

        // hex entities
        $alias = preg_replace_callback(
            '/&#x([0-9a-f]{1,7});/i',
            function ($m) {
                return chr(hexdec($m[1]));
            },
            $alias
        );

        // decimal entities
        $alias = preg_replace_callback(
            '/&#([0-9]{1,7});/',
            function ($m) {
                return chr((int)$m[1]);
            },
            $alias
        );

        return $alias;
    }

    /**
     * Основная функция
     */
    public function stripAlias($alias, $char_restrict = '', $word_separator = '-') {

        $alias = (string)$alias;

        // decode entities
        $alias = $this->decodeEntities($alias);

        // Unicode нормализация (если есть intl)
        if (class_exists('Normalizer')) {
            $alias = \Normalizer::normalize($alias);
        }

        // транслитерация
        if (!empty($this->_useTable) && isset($this->_tables[$this->_useTable])) {
            $alias = strtr($alias, $this->_tables[$this->_useTable]);
        }

        // удаление HTML
        $alias = strip_tags($alias);

        // фильтрация символов
        if ($char_restrict === 'lowercase alphanumeric') {
            $alias = preg_replace('/[^\.%A-Za-z0-9 _-]/u', '', $alias);
            $alias = strtolower($alias);
        } elseif ($char_restrict === 'alphanumeric') {
            $alias = preg_replace('/[^\.%A-Za-z0-9 _-]/u', '', $alias);
        } else {
            $alias = preg_replace('/[&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/u', '', $alias);
        }

        // разделитель слов
        switch ($word_separator) {
            case 'dash': $word_separator = '-'; break;
            case 'underscore': $word_separator = '_'; break;
            case 'none': $word_separator = ''; break;
        }

        // пробелы → разделитель
        $alias = preg_replace('/\s+/u', $word_separator, $alias);

        if ($word_separator !== '') {
            $alias = preg_replace('/' . preg_quote($word_separator, '/') . '+/u', $word_separator, $alias);
        }

        // trim
        if (strlen($word_separator) === 1) {
            $alias = trim($alias, $word_separator . '/. ');
        } else {
            $alias = trim($alias, '/. ');
        }

        return $alias;
    }
}