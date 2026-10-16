<form action="<?=$url?>&get=translate&action=save" method="post">
    <table class="table table-condensed table-striped table-bordered table-hover sectionTrans">
        <thead>
            <tr>
                <td style="text-align:center !important;"><b>ID</b></td>
                <?=$table['thead']?>
            </tr>
        </thead>
        <tbody>
            <?=$table['tbody']?>
        </tbody>
    </table>
    <div id="actions">
        <ul class="actionButtons">
            <li id="Button1" class="transition">
                <a href="#addTranslate" role="button" data-toggle="modal"><i class="fa fa-plus-square"></i>&emsp;<?=$_lang['language_add_translate'];?></a>
            </li>
            <li id="Button2" class="transition">
                <input type="text" id="search_text" value="" class="form-control" placeholder="<?=$_lang['language_search_ph']?>"/>
            </li>
        </ul>
    </div>
</form>
<ul class="pagination"><?=$pagin;?></ul>
<div class="modal fade" id="addTranslate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?=$url?>&get=translates&action=add" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span><span class="sr-only"><?=$_lang["language_btn_close"];?></span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?=$_lang['language_add_translate']?></h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered table-striped table-condensed">
                        <?=$table['add']?>
                    </table>
                    <i><?=$_lang['language_add_message']?> <b>[#<span class="text-danger">ID</span>#]</b></i>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang['cancel']?>
                    </button>
                    <button type="submit" class="btn btn-primary pull-right"><?=$_lang['save']?>
                        <span class="glyphicon glyphicon-ok-sign"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function search() {
        if (jQuery.trim(jQuery("#search_text").val()).length > 0) {
            jQuery(".sectionTrans tbody").html("<tr><th colspan='<?=$table['col']?>'><h3><?=$_lang['language_searching']?></h3></th></tr>");
            jQuery(".pagination").fadeOut(0);
            jQuery.ajax({
                url: '<?=$url?>&get=translates&action=search',
                type: 'POST',
                data: 'search=' + jQuery("#search_text").val(),
                success: function (ajax) {
                    if (ajax == "") {
                        jQuery(".sectionTrans tbody").html("<tr><th colspan='<?=$table['col']?>'><h3><?=$_lang['language_search_empty']?></h3></th></tr>");
                    } else {
                        jQuery(".sectionTrans tbody").html(ajax);
                    }
                }
            });
        }
        return false;
    }

    jQuery(document).ready(function () {
        jQuery("#search_submit").on("click", function () {
            search();
        });
        jQuery("#search_text").on("keyup", function () {
            if (jQuery(this).val().length > 2) {
                search();
            }
        });
        jQuery(".sectionTrans").on("blur", "input", function () {
            var _this = jQuery(this);
            jQuery.ajax({
                url: '<?=$url?>&get=translates&action=update',
                type: 'POST',
                data: _this.attr("name") + '=' + _this.val(),
                success: function (ajax) {
                }
            });
        });

        var source = '<?=$modx->config['lang_default']?>';
        jQuery(document).on("click", ".js_translate", function () {
            var _this = jQuery(this).parents('td');
            var target = _this.data('lang');
            var text = _this.parents('tr').find('[data-lang='+source+'] input').val();

            if (typeof text == "undefined") {
                var text = _this.parents('tbody').find('[data-lang='+source+'] input').val();
                console.log(text);
            }

            jQuery.ajax({
                url: '<?=$url?>&get=translate&action=translate',
                type: 'POST',
                data: _this.find('input').attr("name") + '&source=' + source + '&target=' + target + '&text=' + text,
                success: function (ajax) {
                    _this.find('input').val(ajax);
                }
            });
        });
    });
</script>