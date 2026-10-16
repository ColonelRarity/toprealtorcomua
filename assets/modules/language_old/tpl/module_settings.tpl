<form action="<?=$res['url']?>&get=settings" method="post">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tbody>
            <tr>
                <td width="200px">Мультиязычность работает?</td>
                <td>
                    <select name="lang_enable" class="form-control" data-active="<?=$modx->config["lang_enable"]?>">
                        <option value="1">Да</option>
                        <option value="0">Нет</option>
                    </select>
                </td>
                <td width="250px"><small class="text-muted"></small></td>
            </tr>
            <tr>
                <td>Список языков</td>
                <td><input type="text" name="lang_list" class="form-control" value="<?=$modx->config["lang_list"]?>"></td>
                <td><small class="text-muted">через запятую, например: ua,en,fr</small></td>
            </tr>
            <tr>
                <td>Язык по-умолчанию</td>
                <td><input type="text" name="lang_default" class="form-control" value="<?=$modx->config["lang_default"]?>"></td>
                <td><small class="text-muted">индекс локали (ua)</small></td>
            </tr>
        </tbody>
    </table>
    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-save"></span> <?=$_lang['lang_btn_save']?></button>
</form>