<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>SEO <?=$res['version']?></title>
    <meta charset="UTF-8">
    <link href="/assets/site/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/site/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="/assets/site/jquery-2.1.1.min.js"></script>
    <script src="/assets/site/bootstrap3/js/bootstrap.min.js"></script>
    <script src="/assets/modules/images/js/ajaxupload.js"></script>
    <style type="text/css">
        form {margin-bottom:0px;}
        input[type=text] {margin-bottom:0px;}
        .navbar-inner {padding:0px;}
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $("[data-active]").each(function(){
               _option = $(this).find("option[value='"+$(this).attr("data-active")+"']");
               _option.prop("selected", true);
            });
            $(".update").on("click", function(e){
              e.preventDefault();
              $(this).parents("tr").wrap("<form method='post' />");//find("input, select")
              $(this).parents("form").submit();
            })
        });
    </script>
</head>
<body>


  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header pull-right">
        <a class="navbar-brand" href="<?=$url?>">SEO</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li<?=$get == "redirect" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=redirect"><span class="icon icon-retweet"></span> Перенаправления</a></li>
          <li<?=$get == "robots" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=robots"><span class="icon icon-adjust"></span> Robots.txt</a></li>
          <li<?=$get == "sitemap" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=sitemap"><span class="icon icon-map-marker"></span> Карта сайта</a></li>
          <li<?=$get == "counters" ? ' class="active"' : '' ?>><a href="<?=$url?>&get=counters"><span class="icon icon-globe"></span> Счетчики</a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
  </div> 
  <br><br><br>
  <div class="container">
    <? if (isset($res['alert'])): ?>
      <div class="alert alert-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong><?=$res['alert']?></strong>
      </div>
    <? endif ?>
    <?=$res['content']?>
  </div>
</body>
</html>