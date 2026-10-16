<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <title><?=$_lang['image_title']?></title>
  <meta charset="UTF-8">
  <link href="/assets/site/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/site/bootstrap3/css/bootstrap-theme.min.css" rel="stylesheet">
  <script src="/assets/site/jquery.min.js"></script>
  <script src="/assets/site/bootstrap3/js/bootstrap.min.js"></script>
  <script src="/assets/modules/images/js/ajaxupload.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("[data-active]").each(function(){
        _option = $(this).find("option[value='"+$(this).attr("data-active")+"']");
        _option.prop("selected", true);
      })
    })
  </script>
</head>
<body>  
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header pull-right">
        <a class="navbar-brand" href="<?=$url?>"><?=$doc['pagetitle'.($modx->config['lang_enable'] ? "_".$modx->config['lang_default'] : "")]?></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <? if ($_GET['category'] != '') :?>
            <li><a href="<?=$url?>&get=uploader" id="uploader"><span class="glyphicon glyphicon-upload"></span> <?=$_lang['image_upload']?></a></li>
            <li><a href="<?=$url?>" id="updater"><span class="glyphicon glyphicon-ok-sign"></span> <?=$_lang['image_update']?></a></li>
          <? endif ?>
        </ul>
        <div class="clearfix"></div>
      </div>
  </div> 
  <br><br><br>
  <div class="container">
    <div class="row">
      <form action="<?=$url?>" enctype="multipart/form-data" method="post" id="image_list">
        <ul id="upload_base">
          <?=$res['images']?>
        </ul>
      </form>
      <script src="/assets/modules/images/js/jquery-ui.min.js"></script>
      <script>
        function updateImages() {
          $.ajax({url:'<?=$url?>&get=imageslist', cache:false,success:function(ajax){
            $("#upload_base").html(ajax);
            $("#upload_base").sortable();
          }})
        }
        $(document).ready(function(){
          $("#upload_base").sortable();

          new AjaxUpload($("#uploader"), {
            action: "<?=$url?>&get=upload",
            multiple: true,
            name: "upload[]",
            onSubmit: function(file, ext){
              if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){ 
                alert('<?=$_lang['image_error_format']?>');
                return false;
              }
            },
            onComplete: function(file, response){
              updateImages();
            }
          });
          $("#updater").on("click", function(){
            $("#image_list").submit();
            return false;
          })
          //delete image
          $(document).on("click", "[data-image-remove]", function(){
            if (confirm("<?=$_lang['image_confirm_delete']?>")) {
              var _this = $(this);
              $.ajax({url:'<?=$url?>&get=delete&file='+_this.attr("data-image-remove"), cache:false, success:function(ajax){
                _this.parents(".image").fadeOut(1000, function(){$(this).remove()})
              }})
            }
            return false;
          });
          //show/hide image
          $(document).on("click", "[data-image-visible]", function(){
            var _this = $(this);
            $.ajax({url:'<?=$url?>&get=visible&status='+_this.attr("data-image-visible")+"&i="+_this.attr("data-image-i"), cache:false, dataType: "json", success:function(ajax){
              if(ajax.status == "true") {
                _this
                .attr("title", ajax.title)
                .attr("data-image-visible", ajax.status_image)
                .removeClass("btn-success, btn-danger")
                .addClass(ajax.class)
                .find("span").removeClass("glyphicon-eye-open").removeClass("glyphicon-eye-close").addClass(ajax.eye);
              }
            }});
            return false;
          });
          
          //get info image            
          $(document).on("click", ".edit", function(){
            $("#image_edit [name='i']").val($(this).attr("data-i"));
            $.ajax({url:'<?=$url?>&get=getImage&i='+$(this).attr("data-i"), cache:false, dataType: "json", success:function(ajax){
              if(typeof(ajax.images_nav) != undefined && typeof(ajax.images_content) != undefined) {
                $("#modalImage .modal-body ul.nav").html(ajax.images_nav);
                $("#modalImage .modal-body div.tab-content").html(ajax.images_content);
              }
            }});
            return false;
          });

          //get info image            
          $(document).on("click", ".js_image_edit", function(){
            _form = $("#image_edit").serialize();
            $.ajax({url:'<?=$url?>&get=setImage', data: _form,cache:false, dataType: "json", success:function(ajax){
              if(ajax.status == "true"){
                $(".alert-success").show("slow");
                setTimeout(function(){
                  $(".alert-success").hide("slow");
                }, 5000);
              }
            }});
            return false;
          });

        })
      </script>
      <style>
        .image {position: relative;width: 250px;height: 400px;margin:0 10px 10px 0;list-style: none;float:left;}
        .image > span.delete {position: absolute;top:5px;right:5px;display: none;}
        .image > span.visible {position: absolute;top:5px;left:5px;display: none;}
        .image > span.edit {position: absolute;top:5px;left:35px;display: none;}

        .image:hover > span {display: inline;}
        .thumbnail{margin-bottom: 2px!important;}
        #upload_base input{ border: 1px solid #ddd;border-radius: 4px;padding: 0px 3px; width: 100%;}
      </style>
    </div>
  </div>

  <div class="modal fade" id="modalImage">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?=$_lang['image_edit_info']?></h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-success" style="display:none"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$_lang['image_save']?></div>
          <form action="<?=$url?>" method="POST" id="image_edit">
            <input type="hidden" name="i" value="">
            <ul class="nav nav-tabs">
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?=$_lang['close']?></button>
          <button type="button" class="btn btn-primary js_image_edit"><?=$_lang['save']?></button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

</body>
</html>