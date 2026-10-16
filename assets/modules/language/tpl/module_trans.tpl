<a href="#addTranslate" class="btn btn-success" role="button" data-toggle="modal"><span class="glyphicon glyphicon-white glyphicon-plus-sign"></span> <?=$_lang['lang_add_translate']?></a>
<div class="pull-right menu col-md-4 col-md-offset-3">
    <input type="text" id="search_text" value="" class="form-control" placeholder="<?=$_lang['lang_search_ph']?>" />
  <!-- <a href="#" class="btn btn-success" id="save"><span class="glyphicon glyphicon-white glyphicon-ok-sign"></span> Сохранить</a> -->
</div>
<br>
<br>
<form action="<?=$url?>&get=translate&action=save" method="post">
    <table class="table table-hover table-bordered table-striped sectionBody">
      <thead>
        <tr>
          <td>#</td>
          <?=$table['thead']?>
        </tr>
      </thead>
      <tbody>
        <?=$table['tbody']?>
      </tbody>
    </table>
</form>

<div class="modal fade" id="addTranslate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="<?=$url?>&get=translate&action=add" method="post">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel"><?=$_lang['lang_add_translate']?></h4>
        </div>
        <div class="modal-body">
          
        <table class="table table-hover table-bordered table-striped table-condensed">
          <?=$table['add']?>
        </table>
        <i><?=$_lang['lang_add_description']?> <b>[#<span class="text-danger">ID</span>#]</b></i>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang['lang_btn_cancel']?></button>
          <button type="submit" class="btn btn-primary pull-right"><?=$_lang['lang_btn_save']?> <span class="glyphicon glyphicon-ok-sign"></span> </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
    function search () {
      if ($.trim($("#search_text").val()).length > 0) {
        $(".sectionBody tbody").html("<tr><th colspan='<?=$table['col']?>'><h3><?=$_lang['language_searching']?></h3></th></tr>");
        $.ajax ({
          url    : '<?=$url?>&get=translate&action=search', 
          type   : 'POST', 
          data   : 'search=' + $("#search_text").val(), 
          success: function (ajax){
            if (ajax == "")
              $(".sectionBody tbody").html("<tr><th colspan='<?=$table['col']?>'><h3 class='red'><?=$_lang['language_search_empty']?></h3></th></tr>");
            else
              $(".sectionBody tbody").html(ajax);
        } });
      }
      return false;    
    }
    $(document).ready(function (){
        $("#search_submit").on ("click", function (){
          search();
        });
        $("#search_text").on("keyup", function (){
          if ($(this).val().length > 2)
              search();
        });
        $(".sectionBody").on("blur", "input", function(){
          var _this = $(this);
          $.ajax ({
            url    : '<?=$url?>&get=translate&action=update', 
            type   : 'POST', 
            data   : _this.attr("name")+'='+_this.val(), 
            success: function (ajax){
            } 
          });
        })

    });
</script>        