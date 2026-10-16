<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>Manager</title>
    <meta charset="UTF-8">
    <!-- <link href="/assets/modules/manager/css/bootstrap-theme.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/assets/modules/manager/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="/assets/modules/manager/css/bootstrap.datetime.min.css">
    <link rel="stylesheet" href="/assets/modules/manager/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/modules/manager/css/bootstrap.min.css" />
    <link href="/assets/modules/manager/css/4theme.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/modules/manager/css/switcher.min.css" />
    <link rel="stylesheet" href="/assets/modules/manager/css/main.css" />
    
    

    <script src="/assets/modules/manager/js/jquery-2.1.4.min.js"></script>
    <script src="/assets/modules/manager/js/jquery-ui.min.js"></script>
    <script src="/assets/modules/manager/js/bootstrap.min.js"></script>
    <script src="/assets/modules/manager/js/bootstrap.datetime.min.js"></script>
    <script src="/assets/modules/manager/js/bootstrap-select.min.js"></script>
    <script src="/assets/modules/manager/js/typeahead.jquery.min.js"></script>
    <script src="/assets/modules/manager/js/switcher.min.js"></script>
    <script src="/assets/modules/manager/js/ajaxupload.min.js"></script>
    
    <script language="javascript" type="text/javascript" src="/assets/plugins/tinymce/tiny_mce/tiny_mce.js?231077"></script>
<script language="javascript" type="text/javascript" src="/assets/plugins/tinymce/js/xconfig.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
  theme                            : 'advanced',
  skin                             : 'default',
  skin_variant                     : '',
  mode                             : 'exact',
  elements                         : 'content_ru,content_ua,content_en',
  width                            : '97%',
  height                           : '300',
  language                         : 'ru',
  element_format                   : 'xhtml',
  schema                           : 'html4',
  paste_text_use_dialog            : true,
  document_base_url                : '<?= rtrim($modx->config['site_url'], '/') ?>/',
  relative_urls                    : true,
  remove_script_host               : true,
  convert_urls                     : true,
  force_br_newlines                : false,
  force_p_newlines                 : true,
  forced_root_block                : 'p',
  valid_elements                   : mce_valid_elements,
  popup_css_add                    : '/assets/plugins/tinymce/style/popup_add.css',
  theme_advanced_source_editor_height : 400,
  accessibility_warnings : false,
  theme_advanced_toolbar_location  : 'top',
  theme_advanced_statusbar_location: 'bottom',
  theme_advanced_toolbar_align     : 'ltr',
  theme_advanced_font_sizes        : '80%,90%,100%,120%,140%,160%,180%,220%,260%,320%,400%,500%,700%',
  content_css                      : '/assets/plugins/tinymce/style/content.css',
  formats : {
    alignleft   : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyleft'},
    alignright  : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyright'},
    alignfull   : {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes : 'justifyfull'}
  },
  apply_source_formatting          : true,
  remove_linebreaks                : false,
  convert_fonts_to_spans           : true,
  plugins                          : 'visualblocks,template,autolink,inlinepopups,autosave,save,advlist,style,fullscreen,advimage,paste,advlink,media,contextmenu,table,youtubeIframe',
  theme_advanced_buttons1          : 'undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect,pastetext,pasteword,code,template,|,fullscreen,help',
  theme_advanced_buttons2          : 'image,media,youtubeIframe,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,|,blockquote,outdent,indent,|,table,hr,|,visualblocks,styleprops,removeformat',
  theme_advanced_buttons3          : '',
  theme_advanced_buttons4          : '',
  theme_advanced_resize_horizontal :  false,
  external_link_list_url           : '/assets/plugins/tinymce/js/tinymce.linklist.php',
  template_external_list_url       : '/assets/plugins/tinymce/js/get_template.php',
  template_popup_width             : 550,
  template_popup_height            : 350,
  theme_advanced_blockformats      : 'p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre',
  theme_advanced_styles            : 'left=justifyleft;right=justifyright',
  theme_advanced_disable           : '',
  theme_advanced_resizing          : true,
  fullscreen_settings : {
    theme_advanced_buttons1_add_before : 'save'
  },
  plugin_insertdate_dateFormat     : '%d-%m-%Y',
  plugin_insertdate_timeFormat     : '%H:%M:%S',
  entity_encoding                  : 'named',
  file_browser_callback            : 'mceOpenServerBrowser',
  paste_text_sticky                : true,
  setup : function(ed)
  {
    ed.onPostProcess.add(function(ed, o)
    {
      // State get is set when contents is extracted from editor
      if (o.get)
      {
        o.content = o.content.replace('<p>{{', '{{');
        o.content = o.content.replace('}}</p>', '}}');
        o.content = o.content.replace(/<p>\[([\[\!\~\^])/g, '[$1');
        o.content = o.content.replace(/([\]\!\~\^])\]<\/p>/g, '$1]');
      }
    });
  },
  onchange_callback                : false,
  valid_elements : "*[*]"
})
</script>



    <script src="/assets/modules/manager/js/main.js"></script>

    <script type="text/javascript">
        String.prototype.translit = (function(){
                var L = {
                    'А' : 'A','а' : 'a','Б' : 'B','б' : 'b','В' : 'V','в' : 'v','Г' : 'G','г' : 'g','Д' : 'D','д' : 'd','Е' : 'E','е' : 'e','Ё' : 'Yo','ё' : 'yo','Ж' : 'Zh','ж' : 'zh','З' : 'Z','з' : 'z','И' : 'I','и' : 'i','Й' : 'Y','й' : 'y','К' : 'K','к' : 'k','Л' : 'L','л' : 'l','М' : 'M','м' : 'm','Н' : 'N','н' : 'n','О' : 'O','о' : 'o','П' : 'P','п' : 'p','Р' : 'R','р' : 'r','С' : 'S','с' : 's','Т' : 'T','т' : 't','У' : 'U','у' : 'u','Ф' : 'F','ф' : 'f','Х' : 'Kh','х' : 'kh','Ц' : 'Ts','ц' : 'ts','Ч' : 'Ch','ч' : 'ch','Ш' : 'Sh','ш' : 'sh','Щ' : 'Sch','щ' : 'sch','Ъ' : '','ъ' : '','Ы' : 'Y','ы' : 'y','Ь' : "",'ь' : "",'Э' : 'E','э' : 'e','Ю' : 'Yu','ю' : 'yu','Я' : 'Ya','я' : 'ya',' ' : '-','&' : '','"' : '',"'" : '','%' : '',',' : '','.' : '','!' : '','І' : 'I','і' : 'i','Є' : 'E','є' : 'e','Ґ' : 'G','ґ' : 'g','Ї' : 'i','ї' : 'i','~' : '','`' : '',';' : '',':' : '',')' : '','(' : '','*' : '','@' : '','#' : '','$' : '','^' : '','+' : '','=' : '','?' : '', "_":"","/":''
                    },
                    r = '',
                    k;
                for (k in L) r += k;
                r = new RegExp('[' + r + ']', 'g');
                k = function(a){
                    return a in L ? L[a] : '';
                };
                return function(){
                    return this.replace(r, k);
                };
            })();
        $(document).ready(function(){
            $(function() {
                $('#datetimepicker').datetimepicker({language: 'ru',pickTime: false});
                $('#datetimepicker2').datetimepicker({language: 'ru',pickTime: false});
              });

            $("[data-active]").each(function(){
              _ids = $(this).attr("data-active").split(",");
              for (_i in _ids) {
                 _option = $(this).find("option[value='"+_ids[_i]+"']");
                 _option.prop("selected", true);
              }
              $(this).trigger("liszt:updated");
            });
            $("[data-allow]").on("keyup", function(e){
                var _key = e.keyCode;
                if (_key != 36 && _key != 37 && _key != 39 && _key != 65 && _key != 35)
                    $(this).val($(this).val().replace(new RegExp($(this).attr("data-allow")), ''));
            })

            $("[title]").tooltip({placement:'bottom'})
            
        });
    </script>
</head>

  <body role="document" id="js_actt">
    <?php //if($_SESSION['mgrRole'] != 2) : ?>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand1" href="<?=$url?>"><img src="/images/logo.png" height="35px;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<? if ($_GET['b'] == "flat" || $_GET['b'] == ''): ?>active<?endif?>">
              <a href="<?=$url?>b=flat"><span class="glyphicon glyphicon-home"></span> Объекты</a>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <?php // endif;?>
    <div class="container theme-showcase" role="main">
      <?=$res['content']?>
    </div> <!-- /container -->
    <div id="preloader" style="display: none;">
      <div class="preload-background"></div>
      <img src="/assets/modules/manager/img/preloader.gif" alt="">
    </div>
  
</body>
</html>