<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>Mailer <?=$res['version']?></title>
    <meta charset="UTF-8">
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
    <style type="text/css">
        form {margin-bottom:0px;}
        input[type=text] {margin-bottom:0px;}
        .navbar-inner {padding:0px;}
        #EditAreaArroundInfos_myeditarea {display: none;}
        .progress {margin-bottom: 0px!important;}
        kbd {
            padding: 0 3px; border-width: 2px 3px 4px; border-style: solid; border-color: #cdcdcd #c9c9c9 #9a9a9a #c0c0c0;
            font-size: 11px; color: #000; background: #ececec;
            -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $("[data-active]").each(function(){
               _option = $(this).find("option[value='"+$(this).attr("data-active")+"']");
               _option.prop("selected", true);
            });
            $("[title]").tooltip();
        });
    </script>
</head>
<body>
    <div class="container">
      <div class="masthead">
        <p></p>
        <div class="navbar">
          <div class="navbar-inner">
            <ul class="nav">
              <li<?=$get == "" ? ' class="active"' : '' ?>><a href="<?=$url?>"><span class="icon icon-tasks"></span> <?=$_lang["mailer_analitycs"]?></a></li>
              <li<?=$get == "templates" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=templates"><span class="icon icon-envelope"></span> <?=$_lang["mailer_tpl"]?></a></li>
              <li<?=$get == "userlist" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=userlist"><span class="icon icon-user"></span> <?=$_lang["mailer_list_mail"]?></a></li>
              <li<?=$get == "config" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=config"><span class="icon icon-wrench"></span> <?=$_lang["mailer_config"]?></a></li>
            </ul>
          </div>
        </div><!-- /.navbar -->
        <? if (isset($res['alert'])): ?>
          <div class="alert fade in">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong><?=$res['alert']?></strong>
          </div>
        <? endif ?>
        <?=$res['content']?>
      </div>
    </div>
</body>
</html>