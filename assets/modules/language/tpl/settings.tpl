<form action="<?=$res['url']?>&get=settings" method="POST" id="editForm">
    <table class="table table-hover table-condensed">
        <tbody>
            <tr>
                <th width="160px;"><?=$_lang['language_lang_def']?></th>
                <td>
                    <select style="width:100%" name="lang_default" class="form-control select2" data-placeholder="<?=$_lang['language_select_lang']?>" data-validate="textNoEmpty">
                        <option value=""></option>
                        <?php foreach ($lang_list as $id => $title): ?>
                            <option value="<?=$id;?>"<?php if ($id == $modx->config["lang_default"]) echo ' selected'; ?>><?=$title['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-text" style="display:none;"><?=$_lang['language_field']?> <b>"<?=$_lang['language_lang_def']?>"</b> <?=$_lang['language_do_not_empty']?></div>
                </td>
                <!--<td width="200px;">
                    <input type="hidden" name="lang_default_show" value="0">
                    <label><input type="checkbox" name="lang_default_show" value="1"<?php if (1 == $modx->config["lang_default_show"]) echo ' checked'; ?>>&emsp;<?=$_lang['language_use_url']?></label>
                </td>-->
            </tr>
            <tr>
                <th><?=$_lang['language_languages']?></th>
                <td colspan="2">
                    <select style="width:100%" name="lang_list[]" class="form-control select2" data-placeholder="<?=$_lang['language_select_lang']?>" multiple data-validate="textMustContainDefault">
                        <option value=""></option>
                        <?php foreach ($lang_list as $id => $title): ?>
                            <option value="<?=$id;?>"<?php if (in_array($id, $lang_conf)) echo ' selected'; ?>><?=$title['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-text" style="display:none;"><?=$_lang['language_field']?> <b>"<?=$_lang['language_languages']?>"</b> <?=$_lang['language_do_not_empty']?> <?=$_lang['language_must_contain']?></div>
                </td>
            </tr>
            <tr>
                <th><?=$_lang['language_lang_front']?></th>
                <td colspan="2">
                    <select style="width:100%" name="lang_front[]" class="form-control select2" data-placeholder="<?=$_lang['language_select_lang']?>" multiple data-validate="textMustContainDefault">
                        <option value=""></option>
                        <?php foreach ($lang_list as $id => $title): ?>
                            <option value="<?=$id;?>"<?php if (in_array($id, $lang_front)) echo ' selected'; ?>><?=$title['name'];?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-text" style="display:none;"><?=$_lang['language_field']?> <b>"<?=$_lang['language_lang_front']?>"</b> <?=$_lang['language_do_not_empty']?> <?=$_lang['language_must_contain']?></div>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<div id="actions">
    <ul class="actionButtons">
        <li id="Button1" class="transition">
            <a href="#" class="primary" onclick="documentDirty=false;saveForm('#editForm');"><i class="fa fa-save"></i>&emsp;<?=$_lang['save']?></a>
        </li>
    </ul>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        // Создание нового региона
        jQuery(".js_plus").on("click", function () {
            var _row = jQuery("#row_blank").find('tr');
            var _cnt = jQuery('form').find('select').length;
            _row.find('select:eq(0)').attr('id', 'select0-'+_cnt);
            _row.find('select:eq(1)').attr('id', 'select1-'+_cnt);
            _row.find('select:eq(2)').attr('id', 'select2-'+_cnt);
            jQuery('form').find('tbody').append('<tr>'+_row.html()+'</tr>');
            jQuery("#select0-"+_cnt).select2();
            jQuery("#select1-"+_cnt).select2();
            jQuery("#select2-"+_cnt).select2();
        });

        // Удаление региона
        jQuery(document).on("click", ".js_minus", function () {
            var _item = jQuery(this).parents('tr');
            var _name = _item.find('[name="language_region[]"]').val();
            jQuery('#confirm-delete').find('#confirm-name').text(_name);
            jQuery('#confirm-delete').modal('show');
            jQuery(document).on("click", "#btn-ok", function () {
                jQuery('#confirm-delete').modal('hide');
                _item.remove();
            });
        });

        // Формирование корректного массива
        jQuery(document).on("blur", '[name^="language_region"]', function () {
            var _row  = jQuery(this).parents('tr');
            var _name = jQuery(this).val();
            _row.find('[name^="language_domain"]').attr('name', 'language_domain['+_name+']');
            _row.find('[name^="language_lang_def"]').attr('name', 'language_lang_def['+_name+']');
            _row.find('[name^="language_langs"]').attr('name', 'language_langs['+_name+'][]');
            _row.find('[name^="language_lands"]').attr('name', 'language_lands['+_name+'][]');
        });
    });
</script>