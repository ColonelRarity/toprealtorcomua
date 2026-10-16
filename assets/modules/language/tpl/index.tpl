<div class="notifier">
    <div class="notifier-txt"></div>
</div>
<h1 class="pagetitle">
    <span class="pagetitle-icon"><i class="fa fa-globe"></i></span>
    <span class="pagetitle-text"><?=$_lang['language_management'];?></span>
</h1>
<p style="margin-left:15px;"><?=$_lang['language_description'];?></p>
<div class="sectionBody">
    <div class="dynamic-tab-pane-control tab-pane">
        <div class="tab-row">
            <h2 class="tab<?=($get=='translates'?' selected':'');?>"><a href="<?=$url?>&get=translates"><span><i class="fa fa-language"></i> <?=$_lang['language_translates'];?></span></a></h2>
            <h2 class="tab<?=($get=='settings'?' selected':'');?>"><a href="<?=$url?>&get=settings"><span><i class="fa fa-cogs"></i> <?=$_lang['language_settings'];?></span></a></h2>
        </div>
        <div class="tab-page" id="tabContents">
            <?=$res['content']?>
        </div>
    </div>
</div>

<script type="text/javascript" src="../assets/site/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../assets/site/select2.min.css">

<script type="text/javascript">
    document.title = "<?=$res['title']?> <?=$res['version']?>";
    jQuery(document).ready(function() {
        jQuery(".bsw").bootstrapSwitch();
        jQuery(".select2").select2();
    });

    // Валидация и сохранение формы
    function saveForm(selector) {
        var errors    = 0;
        var messages  = "";
        var validates = jQuery(selector+" [data-validate]");
        validates.each(function(k, v) {
            var rule = jQuery(v).attr("data-validate").split(":");
            switch (rule[0]) {
                case "inputMaxSum": // Максимальное число
                    if (parseFloat(jQuery(v).val()) > parseFloat(rule[1])) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    } else {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    }
                    break;
                case "textNoEmpty": // Не пустое поле
                    if (jQuery(v).val().length < 1) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    } else {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    }
                    break;
                case "textMaxLength": // Максимальная длинна текста
                    if (jQuery(v).val().length > parseInt(rule[1])) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    } else {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    }
                    break;
                case "textMaxLengthOnlyLatinNoEmpty": // Не пустое поле с максимальной длинной и только латинские буквы
                    if (jQuery(v).val().length > parseInt(rule[1]) || !/^[a-zA-Z]+$/.test(jQuery(v).val())) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    } else {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    }
                    break;
                case "textMustContainDefault": // Должно содержать значение дефолтного языка
                    var _default = jQuery(v).parents('tbody').find('[name^="lang_default"]').val();
                    _index = jQuery(v).val().indexOf(_default);
                    if (_index >= jQuery(v).val().length || _index < 0 || isNaN(_index)) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    } else {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    }
                    break;
                case "urlIsset": // Валидный URL с максимальной длинной
                    var _tmp = 0;
                    if (jQuery(v).val().length > 0) {
                        jQuery.ajax({
                            url: "<?=$res['url']?>&get=validate",
                            dataType: "json",
                            type: "POST",
                            async: false,
                            data: "ajax=url&link=" + jQuery(v).val(),
                            success: function (_json) {
                                _tmp = _json.status;
                            }
                        });
                    }

                    if (parseInt(_tmp) > 0) {
                        jQuery(v).parent().removeClass("has-error").addClass("has-success");
                    } else {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery(v).parent().removeClass("has-success").addClass("has-error");
                        errors = errors + 1;
                    }
                    break;
                case "checkDob":
                    var _inpts = jQuery(v).parents('form').find('[name^="'+rule[1]+'"]');
                    var _arrs  = [];
                    var _rslts = [];

                    _inpts.each(function(key, val) {
                        _arrs.push(val.value);
                    });

                    var sorted_arr = _arrs.slice().sort();

                    for (var i = 0; i < sorted_arr.length - 1; i++) {
                        if (sorted_arr[i + 1] == sorted_arr[i]) {
                            _rslts.push(sorted_arr[i]);
                        }
                    }

                    if (_rslts.length > 0) {
                        messages = messages + jQuery(v).parent().find(".error-text").text() + "<br/>";
                        jQuery('[name^="'+rule[1]+'"]').parent().each(function(key, val) {
                            val.removeClass("has-success").addClass("has-error");
                        });
                        errors = errors + 1;
                    }
                    break;
            }
        });
        if (errors == 0) {
            jQuery(selector).submit();
        } else {
            jQuery('.notifier').addClass("notifier-error");
            jQuery('.notifier').fadeIn(500);
            jQuery('.notifier').find('.notifier-txt').html(messages);
            setTimeout(function() {
                jQuery('.notifier').fadeOut(5000);
            },2000);
            setTimeout(function() {
                jQuery('.notifier').removeClass("notifier-error");
            },5000);
        }
    }
</script>
