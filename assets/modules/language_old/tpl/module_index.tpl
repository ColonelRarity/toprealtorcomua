<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <title><?=$_lang['lang_title']?></title>
  <meta charset="UTF-8">
  <link href="/assets/site/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/site/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
  <script src="/assets/site/jquery-2.1.1.min.js"></script>
  <script src="/assets/site/bootstrap3/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("[data-active]").each(function(){
        _option = $(this).find("option[value='"+$(this).attr("data-active")+"']");
        _option.prop("selected", true);
      })
      $('#addTranslate').on('shown.bs.modal', function (e) {
        $(this).find("[type=text]:first").focus();
      })
    })
  </script>
</head>
<body>  
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <a class="navbar-brand" href="<?=$url?>"><?=$_lang['lang_title']?></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <? if ($modx->config['lang_enable']) :?>
            <li<?=$get != "settings" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=translates"><span class="glyphicon glyphicon-flag"></span> <?=$_lang['lang_title_translates']?></a></li>
          <? endif ?>
          <li<?=$get == "settings" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=settings"><span class="glyphicon glyphicon-cog"></span> <?=$_lang['lang_title_config']?></a></li>
        </ul>
      </div><!--/.nav-collapse -->
  </div>
  <br>
  <br>
  <br>
  <div class="container">
    <div class="row">
      <p><?=$_lang['lang_description']?></p>
      <?=$res['content']?>
    </div>
  </div>
</body>
</html>