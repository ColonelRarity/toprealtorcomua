<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <title>Settings <?=$res['version']?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/site/bootstrap3/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/site/bootstrap3/css/bootstrap-theme.min.css">
    <script src="/assets/site/jquery-2.1.4.min.js"></script>
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
        <a class="navbar-brand" href="<?=$url?>"><?=$_lang['advanced_settings']?></a>
      </div>
  </div> 
  <br><br><br>
  <div class="container">
    <?php if (isset($_GET['w'])): ?>
      <div class="alert alert-<?=$_GET['stat']?>">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><?=$messages[$_GET['w']]?></strong>
      </div>
    <?php endif; ?>
    <?=$res['content']?>
  </div>
</body>
</html>