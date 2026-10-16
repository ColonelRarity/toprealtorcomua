<form action="<?=$res['url']?>&get=settings" method="post">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tbody>
            <tr>
                <td width="200px"><?=$_lang['language_multilang']?></td>
                <td>
                    <select name="lang_enable" class="form-control" data-active="<?=$modx->config["lang_enable"]?>">
                        <option value="1"><?=$_lang['yes']?></option>
                        <option value="0"><?=$_lang['no']?></option>
                    </select>
                </td>
                <td width="250px"><small class="text-muted"></small></td>
            </tr>
            <tr>
                <td><?=$_lang['language_list']?></td>
                <td><input type="text" name="lang_list" class="form-control" value="<?=$modx->config["lang_list"]?>"></td>
                <td><small class="text-muted"><?=$_lang['language_implode']?></small></td>
            </tr>
            <tr>
                <td><?=$_lang['language_default']?></td>
                <td><input type="text" name="lang_default" class="form-control" value="<?=$modx->config["lang_default"]?>"></td>
                <td><small class="text-muted"><?=$_lang['language_locale']?></small></td>
            </tr>
        </tbody>
    </table>
    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> <?=$_lang['lang_btn_save']?></button>
</form>