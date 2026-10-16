<?php
if (!class_exists('Shop')) {
  /**
   * Class Shop
   */
  class Shop  {
    var $modx = null;
    var $db   = null;

    function __construct ($modx){
      $this->modx = $modx;
      $this->db   = $modx->db;
    }

    function d ($var) {
      echo "<pre>";
      var_dump($var);
      echo "</pre>";
    }

    function go($url){
      header("Location: ".$url);
      die;
    }

    function debug ($var) {
      $this->d($var);
    }

    function sanitar ($var) {
        return $this->db->escape(strip_tags($var));
    }

    function number ($var) {
        // echo "NUMBER";
        return preg_replace('/\D+/i', '', $var);
    }

    function getPaginate($tpl = 'tpl_paginate', $tpl_enable = 'tpl_page_enable', $tpl_disable = 'tpl_page_disable', $classJS = ' js_change_page'){
      if($this->modx->getPlaceholder("max_items") != '' && $this->modx->getPlaceholder("limit_items") != '')
        $pages['all']   = ceil($this->modx->getPlaceholder("max_items") / $this->modx->getPlaceholder("limit_items"));
      $prev           = $prev != '' ? $prev : '&laquo;';
      $next           = $next != '' ? $next : '&raquo;';
      $classPrev      = ($classPrev != '' ? $classPrev : 'prev').$classJS;
      $classNext      = ($classNext != '' ? $classNext : 'next').$classJS;
      $classPage      = ($classPage != '' ? $classPage : 'page').$classJS;
      $classCurrent   = $classCurrent != '' ? $classCurrent : 'active';
      $classSeparator = $classSeparator != '' ? $classSeparator : 'separator';
      $separator      = $separator != '' ? $separator : '<span>...</span>';
      $url            = $this->modx->getPlaceholder("url") != '' ? $this->modx->getPlaceholder("url") : '';

      if($pages['all'] > 1){
        $limit_pagination = 5;
        //$curent = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        if(isset($_GET['p']))
          $curent = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        else
          $curent = ($this->modx->getPlaceholder("page")) ? $this->modx->getPlaceholder("page") : 1;
        $start  = $curent - $limit_pagination;
        $end    = $curent + $limit_pagination;
        $start  = $start < 1 ? 1 : $start;
        $end    = $end > $pages['all'] ? $pages['all'] : $end;
        if($curent > 1)
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url."1/", 'class' => $classPrev, 'page' => $prev, 'rel' => "prev"), "[+", "+]");

        if($curent > $limit_pagination+1)
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.'1/', 'class' => $classPage, 'page' => '1', 'rel' => "prev"), "[+", "+]").$this->modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'page' => $separator), "[+", "+]");
        for($i=$start; $i<$end+1; $i++){                         
          if($i==($curent)){
            $res .= $this->modx->parseChunk($tpl_disable, array('class' => $classCurrent, 'page' => '<span>'.$i.'</span>', 'rel' => ""), "[+", "+]");
          } else {
            $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.$i."/", 'class' => $classPage, 'page' => $i,  "rel" => ($i < $curent) ? "prev" : "next"), "[+", "+]");
          }
        }
        if($curent < ($pages['all'] - $limit_pagination))
          $res .= $this->modx->parseChunk($tpl_disable, array('class' => $classSeparator, 'page' => $separator), "[+", "+]").$this->modx->parseChunk($tpl_enable , array('url' => $url.$pages['all']."/", 'class' => $classPage, 'page' => $pages['all'], 'rel' => "next"), "[+", "+]");
        if($curent != $pages['all'])
          $res .= $this->modx->parseChunk($tpl_enable , array('url' => $url.$pages['all']."/", 'class' => $classNext, 'page' => $next, 'rel' => "next"), "[+", "+]");        
      }
      return $res;
    }
    /**
     *  getProductTranslate
     *  получает перевод товара
     *  @pid  - id товара
     *  @lang - id языка (ua, ru, en)
     *  >array [name, introtext, description]
     */
    

    function getRecalls($date = '', $status = '') {
      //$status = ($status != '' ? $status : '');
      $limit = 10;
      $page  = isset($_REQUEST['p']) ? ($this->number($_REQUEST['p'])-1) * $limit : 0;
      $query ="
      SELECT SQL_CALC_FOUND_ROWS r.* ,
      (select pagetitle from `modx_site_content` where id = r.recall_page) as 'pagetitle'
      FROM `modx_a_recall` r
      WHERE 0=0 ".$date.$status."
      ORDER BY recall_id DESC LIMIT ".$page.", ".$limit;
      $res = $this->db->query($query);
      $pages = $this->db->getRow($this->db->query("SELECT FOUND_ROWS() AS 'cnt'"));
      $this->modx->setPlaceholder("max_items", $pages['cnt']);
      $this->modx->setPlaceholder("limit_items", $limit);
      return $res;
    }
    function getRecall($recall_id) {
      return $this->db->getRow($this->db->query("
      SELECT r.*
      FROM `modx_a_recall` r
      WHERE recall_id = ".$recall_id));
    }
    function updateRecall($recall){
      $this->db->query("UPDATE `modx_a_recall` SET 
            recall_name      = '".$this->db->escape($recall['name'])."',
            recall_text      = '".$this->db->escape($recall['text'])."',
            recall_subject   = '".$this->db->escape($recall['subject'])."',
            recall_email     = '".$this->db->escape($recall['email'])."',
            recall_moderated = '".$this->db->escape($recall['moderated'])."',
            recall_cover     = '".$this->db->escape($recall['cover'])."'
            WHERE recall_id  = '".$this->db->escape($recall['id'])."'
          ");
    }
    function deleteRecall($id){
      $this->db->query("DELETE FROM `modx_a_recall` WHERE recall_id = '".$this->db->escape($id)."' LIMIT 1");
    }
    
    function clear_dir($dir){
      $list = $this->myscandir($dir);
      foreach ($list as $file){
          if (is_dir($dir.$file)){
              clear_dir($dir.$file.'/');
              rmdir($dir.$file);
          }else{
              unlink($dir.$file);
          }
      }
    } 
    
    function myscandir($dir){
      $list = scandir($dir);
      unset($list[0],$list[1]);
      return array_values($list);
    }

    
  }
}