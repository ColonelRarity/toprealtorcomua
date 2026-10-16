<?php
// ====== Пролог / хелперы (PHP 5.4 совместимо) ======

if (!function_exists('hs')) {
    function hs($v) {
        return htmlspecialchars(isset($v) ? $v : '', ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('val')) {
    function val($arr, $key, $default = '') {
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}
if (!function_exists('valn')) {
    // безопасно достаём $arr[$key1][$key2]...
    function valn($arr /*, ...$keys */) {
        $args = func_get_args();
        array_shift($args);
        foreach ($args as $k) {
            if (!is_array($arr) || !isset($arr[$k])) return '';
            $arr = $arr[$k];
        }
        return $arr;
    }
}

$langs = (isset($langs) && is_array($langs)) ? $langs : array('ru','ua');

// На создании $flatStrings чаще пустой — нормализуем структуру
if (!isset($flatStrings) || !is_array($flatStrings)) {
    $flatStrings = array();
}
foreach ($langs as $lg) {
    if (!isset($flatStrings[$lg]) || !is_array($flatStrings[$lg])) {
        $flatStrings[$lg] = array();
    }
}
?>

<?php if (!empty($flash['success'])): ?>
  <div class="alert alert-success alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <?= hs($flash['success']) ?>
  </div>
<?php endif; ?>

<form action="<?= rtrim(val($url, 0) ? $url : (isset($url) ? $url : ''), '?&') ?>?b=flat&c=save" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="flat[id]" value="<?= hs(val($flat, 'id')) ?>">

  <div class="row">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#base" data-toggle="tab"><span class="glyphicon glyphicon-home"></span> Основная информация</a></li>
      <li><a href="#textbase" data-toggle="tab"><span class="glyphicon glyphicon-font"></span> Текст.информация</a></li>
      <li><a href="#content" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Контент</a></li>
      <li><a href="#seo" data-toggle="tab"><span class="glyphicon glyphicon-search"></span> SEO</a></li>
      <li><a href="#gallery" data-toggle="tab"><span class="glyphicon glyphicon-picture"></span> Галерея</a></li>
      <li><a href="#character" data-toggle="tab"><span class="glyphicon glyphicon-info-sign"></span> Характеристики</a></li>
      <li><a href="#appartament" data-toggle="tab"><span class="glyphicon glyphicon-thumbs-up"></span> Аппартаменты</a></li>
      <li><a href="#info" data-toggle="tab"><span class="glyphicon glyphicon-info-sign"></span> Информация о владельце</a></li>
      <li><a href="#file" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> Файлы</a></li>
    </ul>

    <div class="tab-content">

      <!-- ====== БАЗА ====== -->
      <div class="tab-pane active" id="base">
        <table class="table table-bordered table-condensed table-striped">
          <tr>
            <td><b>Активность</b></td>
            <td>
              <input type="hidden" name="flat[active]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" name="flat[active]" <?= !empty($flat['active']) ? 'checked' : '' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Выгрузка в xml</b></td>
            <td>
              <input type="hidden" name="flat[xml_active]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" name="flat[xml_active]" <?= !empty($flat['xml_active']) ? 'checked' : '' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Выгрузка в vhn.ua</b></td>
            <td>
              <input type="hidden" name="flat[xml_active_vhn]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" name="flat[xml_active_vhn]" <?= !empty($flat['xml_active_vhn']) ? 'checked' : '' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Выгрузка в lun.ua</b></td>
            <td>
              <input type="hidden" name="flat[xml_active_lun]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" name="flat[xml_active_lun]" <?= !empty($flat['xml_active_lun']) ? 'checked' : '' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <td><b>Статус</b></td>
            <td>
              <select name="flat[status][]" class="form-control" required multiple="multiple">
                <option value="1">Продажа</option>
                <option value="2">Аренда</option>
                <option value="3">Снят с продажи</option>
                <option value="4">Продан</option>
                <option value="5">Скрытая продажа</option>
              </select>
            </td>
          </tr>
          <tr>
            <td><b>Статус vhn.ua</b></td>
            <td>
              <?php $sv = isset($flat['status_vhn']) ? (int)$flat['status_vhn'] : 4; ?>
              <select name="flat[status_vhn]" class="form-control" required>
                <option value="0" <?= $sv===0 ? 'selected' : '' ?>> - </option>
                <option value="1" <?= $sv===1 ? 'selected' : '' ?>>Платный эксклюзив</option>
                <option value="2" <?= $sv===2 ? 'selected' : '' ?>>Бесплатный эксклюзив</option>
                <option value="3" <?= $sv===3 ? 'selected' : '' ?>>Болванка</option>
                <option value="4" <?= $sv===4 ? 'selected' : '' ?>>Реклама реального объекта</option>
              </select>
            </td>
          </tr>

          <tr>
            <th width="150px">Цена</th>
            <td>
              <div class="input-group">
                <input type="text" class="form-control" name="flat[price]" value="<?= hs(val($flat,'price')) ?>">
                <div class="input-group-addon">$</div>
              </div>
            </td>
          </tr>
          <tr>
            <th>Старая цена</th>
            <td>
              <div class="input-group">
                <input type="text" name="flat[old_price]" class="form-control" value="<?= hs(val($flat,'old_price')) ?>">
                <div class="input-group-addon">$</div>
              </div>
            </td>
          </tr>
          <tr>
            <th>Агент</th>
            <td>
              <select name="flat[agent]" class="form-control">
                <?php if (isset($agents) && is_array($agents)): foreach ($agents as $a): ?>
                  <option value="<?= (int)$a['id'] ?>" <?= (!empty($flat['agent']) && (int)$a['id']==(int)$flat['agent']) ? 'selected' : '' ?>>
                    <?= hs($a['pagetitle']) ?>
                  </option>
                <?php endforeach; endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>Категория</th>
            <td>
              <select class="selectpicker form-control" name="flat[categories][]" id="catselect" multiple data-live-search="true">
                <?php if (isset($categories) && is_array($categories)): foreach ($categories as $c): ?>
                  <option value="<?= (int)$c['id'] ?>"><?= hs($c['pagetitle']) ?></option>
                <?php endforeach; endif; ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>URL</th>
            <td><input type="text" name="flat[url]" class="form-control" value="<?= hs(val($flat,'url')) ?>"></td>
          </tr>
          <tr>
            <th>Рейтинг (0-5)</th>
            <td><input type="number" min="0" max="5" name="flat[rating]" class="form-control" value="<?= hs(val($flat,'rating')) ?>"></td>
          </tr>
          <tr>
            <th>Youtube</th>
            <td><input type="text" name="flat[video]" class="form-control" value="<?= hs(val($flat,'video')) ?>"></td>
          </tr>
          <tr>
            <th>Отображение коттеджных поселков</th>
            <td>
              <input type="hidden" name="flat[cottage_view]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" class="myCheckbox" id="check1" name="flat[cottage_view]" <?= !empty($flat['cottage_view']) ? 'checked' : 'checked' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <th>Отображение watermark</th>
            <td>
              <input type="hidden" name="flat[watermark]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" id="check2" name="flat[watermark]" <?= !empty($flat['watermark']) ? 'checked' : '' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <th>
              <?php
                $type = isset($flat['type']) ? (int)$flat['type'] : 0;
                echo ($type===2 || $type===3)
                  ? 'Отображение кнопки "Показать дома (участки) в этом районе"'
                  : 'Отображение кнопки "Показать квартиры в доме"';
              ?>
            </th>
            <td>
              <input type="hidden" name="flat[flatInHouse_view]" value="0">
              <div class="make-switch" data-on="success" data-off="danger">
                <input type="checkbox" name="flat[flatInHouse_view]" <?= !empty($flat['flatInHouse_view']) ? 'checked' : 'checked' ?> value="1">
              </div>
            </td>
          </tr>
          <tr>
            <th>Координаты <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Подберутся автоматически по адресу, или прописать вручную"></span></th>
            <td><input type="text" name="flat[coord]" class="form-control" value="<?= hs(val($flat,'coord')) ?>"></td>
          </tr>
          <tr>
            <th>Панорама <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Полная ссылка Google. Используется НЕ в галерее"></span></th>
            <td><input type="text" name="flat[panorama]" class="form-control" value="<?= hs(val($flat,'panorama')) ?>"></td>
          </tr>
          <tr>
            <th>Панорама для галереи</th>
            <td><input type="text" name="flat[panorama_gallery]" class="form-control" value="<?= hs(val($flat,'panorama_gallery')) ?>"></td>
          </tr>
          <tr>
            <th>Превью панорамы</th>
            <td>
              <input type="text" name="flat[panorama_image]" id="flat_panorama_image" class="form-control" style="width:80%;float:left" value="<?= hs(val($flat,'panorama_image')) ?>">
              <input type="button" class="btn btn-success" value="Вставить" onclick="BrowseServer('flat_panorama_image')">
              <span id="flat_panorama_image_thumb">
                <img src="/assets/snippets/phpthumb/noimage.png" width="150" style="display:none">
              </span>
            </td>
          </tr>
          <tr>
            <th>Панорама объекта 360</th>
            <td><input type="text" name="flat[panorama_360]" class="form-control" value="<?= hs(val($flat,'panorama_360')) ?>"></td>
          </tr>
          <tr>
            <th>Застройщик</th>
            <td><input type="text" name="flat[link_developer]" class="form-control" value="<?= hs(val($flat,'link_developer')) ?>"></td>
          </tr>
          <tr>
            <th>Доход годовых (%)</th>
            <td><input type="number" name="flat[advantage_for_client]" class="form-control" value="<?= hs(val($flat,'advantage_for_client')) ?>"></td>
          </tr>
        </table>
      </div>

      <!-- ====== ТЕКСТ.ИНФОРМАЦИЯ (мультиязычно) ====== -->
      <div class="tab-pane" id="textbase">
        <ul class="nav nav-tabs" id="vallangTabs">
          <?php foreach ($langs as $lg): ?>
            <li class="<?= ($lg === 'ru') ? 'active' : '' ?>"><a href="#tab_val_<?= $lg ?>" data-toggle="tab"><?= strtoupper($lg) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <div class="tab-content" style="margin-top:10px">
          <?php foreach ($langs as $lg): $v = $flatStrings[$lg]; ?>
            <div class="tab-pane <?= ($lg === 'ru') ? 'active' : '' ?>" id="tab_val_<?= $lg ?>">
              <table class="table table-bordered table-condensed table-striped">
                <tr>
                  <th width="150">Название</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][name]" class="form-control" value="<?= hs(val($v,'name')) ?>"></td>
                </tr>
                <tr>
                  <th>Cтрана</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][country]" class="form-control" value="<?= hs(val($v,'country', ($lg==='ru'?'Украина':'Україна'))) ?>"></td>
                </tr>
                <tr>
                  <th>Область</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][region]" class="form-control" value="<?= hs(val($v,'region')) ?>"></td>
                </tr>
                <tr>
                  <th>Город</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][locality_name]" class="form-control" value="<?= hs(val($v,'locality_name')) ?>"></td>
                </tr>
                <tr>
                  <th>Район</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][non_admin_sub_locality]" class="form-control" value="<?= hs(val($v,'non_admin_sub_locality')) ?>"></td>
                </tr>
                <tr>
                  <th>Станция метро (yml)</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][metro]" class="form-control" value="<?= hs(val($v,'metro')) ?>"></td>
                </tr>
                <tr>
                  <th>Микрорайон (yml)</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][microdistrict_xml]" class="form-control" value="<?= hs(val($v,'microdistrict_xml')) ?>"></td>
                </tr>
                <tr>
                  <th>Улица (yml)</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][street_xml]" class="form-control" value="<?= hs(val($v,'street_xml')) ?>"></td>
                </tr>
                <tr>
                  <th>№ дома (yml)</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][house_number_xml]" class="form-control" value="<?= hs(val($v,'house_number_xml')) ?>"></td>
                </tr>
                <tr>
                  <th>Адрес</th>
                  <td><input type="text" name="flatString[<?= $lg ?>][addr]" class="form-control" value="<?= hs(val($v,'addr')) ?>"></td>
                </tr>
              </table>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ====== КОНТЕНТ (мультиязычно) ====== -->
      <div class="tab-pane" id="content">
        <ul class="nav nav-tabs" id="langTabs">
          <?php foreach ($langs as $lg): ?>
            <li class="<?= ($lg === 'ru') ? 'active' : '' ?>"><a href="#tab_content_<?= $lg ?>" data-toggle="tab"><?= strtoupper($lg) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <div class="tab-content" style="margin-top:10px">
          <?php foreach ($langs as $lg): $v = $flatStrings[$lg]; ?>
            <div class="tab-pane <?= ($lg === 'ru') ? 'active' : '' ?>" id="tab_content_<?= $lg ?>">
              <table class="table table-bordered table-condensed table-striped">
                <tr>
                  <td width="200"><b>Описание</b></td>
                  <td>
                    <textarea name="flatString[<?= $lg ?>][description]" class="form-control tiny col-xs-12" rows="10"><?= hs(val($v,'description')) ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td><b>Расходы на содержание объекта</b></td>
                  <td>
                    <textarea name="flatString[<?= $lg ?>][consumption]" class="form-control tiny" rows="10"><?= hs(val($v,'consumption')) ?></textarea>
                  </td>
                </tr>
                <tr>
                  <td><b>Налогообложение</b></td>
                  <td>
                    <textarea name="flatString[<?= $lg ?>][taxation]" class="form-control tiny" rows="10"><?= hs(val($v,'taxation')) ?></textarea>
                  </td>
                </tr>
              </table>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ====== SEO (мультиязычно) ====== -->
      <div class="tab-pane" id="seo">
        <ul class="nav nav-tabs" id="seoLangTabs">
          <?php foreach ($langs as $lg): ?>
            <li class="<?= ($lg === 'ru') ? 'active' : '' ?>"><a href="#tab_seo_<?= $lg ?>" data-toggle="tab"><?= strtoupper($lg) ?></a></li>
          <?php endforeach; ?>
        </ul>

        <div class="tab-content" style="margin-top:10px">
          <?php foreach ($langs as $lg): $v = $flatStrings[$lg]; ?>
            <div class="tab-pane <?= ($lg === 'ru') ? 'active' : '' ?>" id="tab_seo_<?= $lg ?>">
              <table class="table table-bordered table-condensed table-striped">
                <tr>
                  <td width="200"><b>Title</b></td>
                  <td><input type="text" name="flatString[<?= $lg ?>][product_seo_title]" class="form-control" value="<?= hs(val($v,'product_seo_title')) ?>"></td>
                </tr>
                <tr>
                  <td><b>Keywords</b></td>
                  <td><input type="text" name="flatString[<?= $lg ?>][product_seo_keywords]" class="form-control" value="<?= hs(val($v,'product_seo_keywords')) ?>"></td>
                </tr>
                <tr>
                  <td><b>Description</b></td>
                  <td><input type="text" name="flatString[<?= $lg ?>][product_seo_description]" class="form-control" value="<?= hs(val($v,'product_seo_description')) ?>"></td>
                </tr>
                <tr>
                  <td><b>Robots</b></td>
                  <td><input type="text" name="flatString[<?= $lg ?>][product_seo_robots]" class="form-control" value="<?= hs(val($v,'product_seo_robots')) ?>"></td>
                </tr>
                <tr>
                  <td><b>Canonical</b></td>
                  <td><input type="text" name="flatString[<?= $lg ?>][product_seo_canonical]" class="form-control" value="<?= hs(val($v,'product_seo_canonical')) ?>"></td>
                </tr>
              </table>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- ====== ГАЛЕРЕЯ ====== -->
      <div role="tabpanel" class="tab-pane" id="gallery">
<a href="#" id="uploader"
   class="btn btn-warning"
   data-endpoint="index.php?a=112&id=7&b=flat&c=upload"
   data-folder="<?= hs(val($flat,'id')) ?>"
   data-size="11048576"
   data-concurrency="2"
   data-retries="2"
   data-total="100" 
>
  <span class="js-up-text">Загрузить</span>
  <span class="js-up-spin" style="display:none;">Загружаем… <span class="js-up-count"></span></span>
</a>

<!-- индикатор количества -->
<span id="img-counter" style="margin-left:10px; display:inline-block;">
  Фото: <strong class="js-img-count">0</strong><span class="js-img-total"></span>
</span>

<input id="uploader-input" type="file" multiple accept="image/*"
       style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;">

<div id="upload-overlay" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,.6); z-index:9999;">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); padding:12px 16px; background:#fff; border:1px solid #ddd; border-radius:8px;">
    Идёт загрузка… <span class="js-up-count"></span>
  </div>
</div>
        <ul class="images"></ul>
        <input type="hidden" name="flat[preview]" id="cover" value="<?= hs(val($flat,'preview')) ?>" />
        <div style="clear:both"></div>
      </div>

      <!-- ====== ХАРАКТЕРИСТИКИ ====== -->
      <div class="tab-pane" id="character">
        <div class="panel panel-default">
          <div class="panel-heading">Основные характеристики</div>
          <div class="panel-body">
            <table class="table table-bordered table-condensed table-striped">
              <tr>
                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="1-я строка краткого описания"></span></th>
                <td>
                  <input type="text" name="flatString[ua][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ua','character',0)) ?>">
                </td>
                <td style="width: 41px;"><a class="btn js-copy-right" href=""><span class="glyphicon glyphicon-arrow-right"></span></a></td>
                <td>
                  <input type="text" name="flatString[ru][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ru','character',0)) ?>">
                </td>
              </tr>
              <tr>
                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="2-я строка краткого описания"></span></th>
                <td>
                  <input type="text" name="flatString[ua][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ua','character',1)) ?>">
                </td>
                <td style="width: 41px;"><a class="btn js-copy-right" href=""><span class="glyphicon glyphicon-arrow-right"></span></a></td>
                <td>
                  <input type="text" name="flatString[ru][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ru','character',1)) ?>">
                </td>
              </tr>
              <tr>
                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="3-я строка краткого описания"></span></th>
                <td>
                  <input type="text" name="flatString[ua][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ua','character',2)) ?>">
                </td>
                <td style="width: 41px;"><a class="btn js-copy-right" href=""><span class="glyphicon glyphicon-arrow-right"></span></a></td>
                <td>
                  <input type="text" name="flatString[ru][character][]" class="form-control" value="<?= hs(valn($flatStrings, 'ru','character',2)) ?>">
                </td>
              </tr>

              <tr>
                <th width="175px">Кол-во этажей</th>
                <td colspan="3"><input type="text" name="flat[floor]" class="form-control" value="<?= hs(val($flat,'floor')) ?>"></td>
              </tr>
              <tr>
                <th width="175px">Общее кол-во этажей</th>
                <td colspan="3"><input type="text" name="flat[floors_total]" class="form-control" value="<?= hs(val($flat,'floors_total')) ?>"></td>
              </tr>
              <tr>
                <th width="175px">Общая площадь</th>
                <td colspan="3"><input type="text" name="flat[area]" class="form-control" value="<?= hs(val($flat,'area')) ?>"></td>
              </tr>
              <tr>
                <th width="175px">Код</th>
                <td colspan="3"><input type="text" name="flat[code]" class="form-control" value="<?= hs(val($flat,'code')) ?>"></td>
              </tr>
              <tr>
                <th width="175px">Кол-во кроватей</th>
                <td colspan="3"><input type="text" name="flat[beds]" class="form-control" value="<?= hs(val($flat,'beds')) ?>"></td>
              </tr>
              <tr>
                <th width="175px">Кол-во ванн</th>
                <td colspan="3"><input type="text" name="flat[bath]" class="form-control" value="<?= hs(val($flat,'bath')) ?>"></td>
              </tr>
            </table>
          </div>
        </div>

        <div class="panel panel-info">
          <div class="panel-heading">Характеристики по типу объекта</div>
          <div class="panel-body">
            <input type="hidden" name="flat[properties][]" value="">
            <table class="table table-bordered table-condensed table-striped js_type_character">
              <tr>
                <td width="200"><b>Тип объекта</b></td>
                <td>
                  <?php $t = isset($flat['type']) ? (string)$flat['type'] : ''; $r = isset($flat['rooms']) ? (int)$flat['rooms'] : 0; ?>
                  <select name="flat[type]" class="form-control" required>
                    <option value=""> - </option>
                    <option value="1" <?= ($t==='1') ? 'selected' : '' ?>>Квартира</option>
                    <option value="2" <?= ($t==='2') ? 'selected' : '' ?>>Дом</option>
                    <option value="3" <?= ($t==='3') ? 'selected' : '' ?>>Участок</option>
                    <option value="4" <?= ($t==='4') ? 'selected' : '' ?>>Аренда</option>
                    <option value="5" <?= ($t==='5') ? 'selected' : '' ?>>Бизнес</option>
                    <option value="6" <?= ($t==='6') ? 'selected' : '' ?>>Зарубежная недвижимость</option>
                  </select>
                </td>
              </tr>

              <!-- КВАРТИРЫ -->
              <tr data-type="1" <?= ($t==='1') ? '' : 'style="display:none;"' ?>>
                <td><b>Кол-во комнат</b></td>
                <td>
                  <select name="flat[rooms]" class="form-control" <?= ($t==='1') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($r===1)?'selected':'' ?>>1</option>
                    <option value="2" <?= ($r===2)?'selected':'' ?>>2</option>
                    <option value="3" <?= ($r===3)?'selected':'' ?>>3</option>
                    <option value="4" <?= ($r>=4)?'selected':'' ?>>4+</option>
                  </select>
                </td>
              </tr>
              <tr data-type="1" <?= ($t==='1') ? '' : 'style="display:none;"' ?>>
                <td><b>Тип квартиры</b></td>
                <td>
                  <?php $rt = isset($flat['rooms_type']) ? (array)$flat['rooms_type'] : array(); ?>
                  <select name="flat[rooms_type][]" class="form-control" multiple <?= ($t==='1') ? '' : 'disabled' ?>>
                    <option value="5" <?= in_array(5,$rt)?'selected':'' ?>>Премиум-класс</option>
                    <option value="6" <?= in_array(6,$rt)?'selected':'' ?>>Двухуровневые</option>
                    <option value="7" <?= in_array(7,$rt)?'selected':'' ?>>В современном доме</option>
                  </select>
                </td>
              </tr>
              <tr data-type="1" <?= ($t==='1') ? '' : 'style="display:none;"' ?>>
                <td><b>Район</b></td>
                <td>
                  <?php $d = isset($flat['district']) ? (int)$flat['district'] : 0; ?>
                  <select name="flat[district]" class="form-control" <?= ($t==='1') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($d===1)?'selected':'' ?>>Центр</option>
                    <option value="2" <?= ($d===2)?'selected':'' ?>>Метро Научная</option>
                    <option value="3" <?= ($d===3)?'selected':'' ?>>В спальных районах</option>
                    <option value="4" <?= ($d===4)?'selected':'' ?>>Современный дом - Павлово Поле</option>
                    <option value="5" <?= ($d===5)?'selected':'' ?>>Современный дом - Алексеевка</option>
                    <option value="6" <?= ($d===6)?'selected':'' ?>>Современный дом - Холодная гора</option>
                    <option value="7" <?= ($d===7)?'selected':'' ?>>Соврменный дом - Новостройки в других районах</option>
                  </select>
                </td>
              </tr>
              <tr data-type="1" <?= ($t==='1') ? '' : 'style="display:none;"' ?>>
                <td>Свойства объекта</td>
                <td>
                  <select name="flat[properties][]" class="form-control" multiple>
                    <?php if (isset($properties1) && is_array($properties1)): foreach ($properties1 as $p): ?>
                      <option value="<?= (int)$p['id'] ?>" <?= (!empty($flat['properties']) && array_key_exists($p['id'], $flat['properties'])) ? 'selected' : '' ?>><?= hs($p['pagetitle']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </td>
              </tr>

              <!-- ДОМ -->
              <tr data-type="2" <?= ($t==='2') ? '' : 'style="display:none;"' ?>>
                <td><b>Кол-во спален</b></td>
                <td>
                  <select name="flat[rooms]" class="form-control" <?= ($t==='2') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($r===1)?'selected':'' ?>>1</option>
                    <option value="2" <?= ($r===2)?'selected':'' ?>>2</option>
                    <option value="3" <?= ($r===3)?'selected':'' ?>>3</option>
                    <option value="4" <?= ($r>=4)?'selected':'' ?>>4+</option>
                  </select>
                </td>
              </tr>
              <tr data-type="2" <?= ($t==='2') ? '' : 'style="display:none;"' ?>>
                <td><b>Тип дома</b></td>
                <td>
                  <?php $rt = isset($flat['rooms_type']) ? (array)$flat['rooms_type'] : array(); ?>
                  <select name="flat[rooms_type][]" class="form-control" multiple <?= ($t==='2') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= in_array(1,$rt)?'selected':'' ?>>В городе</option>
                    <option value="2" <?= in_array(2,$rt)?'selected':'' ?>>В пригороде</option>
                    <option value="3" <?= in_array(3,$rt)?'selected':'' ?>>В коттеджном поселке</option>
                    <option value="4" <?= in_array(4,$rt)?'selected':'' ?>>С выходом к воде</option>
                    <option value="5" <?= in_array(5,$rt)?'selected':'' ?>>С камином</option>
                    <option value="6" <?= in_array(6,$rt)?'selected':'' ?>>С бассейном</option>
                  </select>
                </td>
              </tr>
              <tr data-type="2" <?= ($t==='2') ? '' : 'style="display:none;"' ?>>
                <td>Свойства объекта</td>
                <td>
                  <select name="flat[properties][]" class="form-control" multiple>
                    <?php if (isset($properties2) && is_array($properties2)): foreach ($properties2 as $p): ?>
                      <option value="<?= (int)$p['id'] ?>" <?= (!empty($flat['properties']) && array_key_exists($p['id'], $flat['properties'])) ? 'selected' : '' ?>><?= hs($p['pagetitle']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </td>
              </tr>

              <!-- УЧАСТОК -->
              <tr data-type="3" <?= ($t==='3') ? '' : 'style="display:none;"' ?>>
                <td><b>Категория</b></td>
                <td>
                  <?php $rt = isset($flat['rooms_type']) ? (array)$flat['rooms_type'] : array(); ?>
                  <select name="flat[rooms_type][]" class="form-control" multiple <?= ($t==='3') ? '' : 'disabled' ?>>
                    <option value="1" <?= in_array(1,$rt)?'selected':'' ?>>под жилую застройку в городе</option>
                    <option value="2" <?= in_array(2,$rt)?'selected':'' ?>>под жилую застройку в пригороде</option>
                    <option value="3" <?= in_array(3,$rt)?'selected':'' ?>>под жилую застройку в коттеджном поселке</option>
                    <option value="4" <?= in_array(4,$rt)?'selected':'' ?>>под жилую застройку с выходом к воде</option>
                    <option value="5" <?= in_array(5,$rt)?'selected':'' ?>>под коммерческую застройку</option>
                  </select>
                </td>
              </tr>
              <tr data-type="3" <?= ($t==='3') ? '' : 'style="display:none;"' ?>>
                <td>Свойства объекта</td>
                <td>
                  <select name="flat[properties][]" class="form-control" multiple>
                    <?php if (isset($properties5) && is_array($properties5)): foreach ($properties5 as $p): ?>
                      <option value="<?= (int)$p['id'] ?>" <?= (!empty($flat['properties']) && array_key_exists($p['id'], $flat['properties'])) ? 'selected' : '' ?>><?= hs($p['pagetitle']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </td>
              </tr>

              <!-- АРЕНДА -->
              <tr data-type="4" <?= ($t==='4') ? '' : 'style="display:none;"' ?>>
                <td><b>Кол-во комнат</b></td>
                <td>
                  <select name="flat[rooms]" class="form-control" <?= ($t==='4') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($r===1)?'selected':'' ?>>1</option>
                    <option value="2" <?= ($r===2)?'selected':'' ?>>2</option>
                    <option value="3" <?= ($r===3)?'selected':'' ?>>3</option>
                    <option value="4" <?= ($r>=4)?'selected':'' ?>>4+</option>
                  </select>
                </td>
              </tr>
              <tr data-type="4" <?= ($t==='4') ? '' : 'style="display:none;"' ?>>
                <td><b>Месторасположение</b></td>
                <td>
                  <?php $rtv = isset($flat['rooms_type']) ? (int)$flat['rooms_type'] : 0; ?>
                  <select name="flat[rooms_type]" class="form-control" <?= ($t==='4') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($rtv===1)?'selected':'' ?>>В городе</option>
                    <option value="2" <?= ($rtv===2)?'selected':'' ?>>В пригороде</option>
                  </select>
                </td>
              </tr>
              <tr data-type="4" <?= ($t==='4') ? '' : 'style="display:none;"' ?>>
                <td><b>Тип</b></td>
                <td>
                  <?php $d = isset($flat['district']) ? (int)$flat['district'] : 0; ?>
                  <select name="flat[district]" class="form-control" <?= ($t==='4') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= ($d===1)?'selected':'' ?>>Жилая недвижимость - Квартира</option>
                    <option value="2" <?= ($d===2)?'selected':'' ?>>Жилая недвижимость - Дом</option>
                    <option value="3" <?= ($d===3)?'selected':'' ?>>Нежилая недвижимость</option>
                  </select>
                </td>
              </tr>
              <tr data-type="4" <?= ($t==='4') ? '' : 'style="display:none;"' ?>>
                <td>Свойства объекта</td>
                <td>
                  <select name="flat[properties][]" class="form-control" multiple>
                    <?php if (isset($properties3) && is_array($properties3)): foreach ($properties3 as $p): ?>
                      <option value="<?= (int)$p['id'] ?>" <?= (!empty($flat['properties']) && array_key_exists($p['id'], $flat['properties'])) ? 'selected' : '' ?>><?= hs($p['pagetitle']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </td>
              </tr>

              <!-- БИЗНЕС -->
              <tr data-type="5" <?= ($t==='5') ? '' : 'style="display:none;"' ?>>
                <td><b>Параметры</b></td>
                <td>
                  <?php $rt = isset($flat['rooms_type']) ? (array)$flat['rooms_type'] : array(); ?>
                  <select name="flat[rooms_type][]" class="form-control" multiple <?= ($t==='5') ? '' : 'disabled' ?>>
                    <option value=""> - </option>
                    <option value="1" <?= in_array(1,$rt)?'selected':'' ?>>Помещения на первых этажах</option>
                    <option value="2" <?= in_array(2,$rt)?'selected':'' ?>>Офисы</option>
                    <option value="3" <?= in_array(3,$rt)?'selected':'' ?>>Отдельно стоящие здания</option>
                    <option value="4" <?= in_array(4,$rt)?'selected':'' ?>>Промышленные объекты</option>
                    <option value="5" <?= in_array(5,$rt)?'selected':'' ?>>Продажа готового бизнеса</option>
                  </select>
                </td>
              </tr>
              <tr data-type="5" <?= ($t==='5') ? '' : 'style="display:none;"' ?>>
                <td><b>Категория коммерческого объекта (для vhn.ua)</b></td>
                <td>
                  <?php $rvhn = isset($flat['rooms_type_vhn']) ? (int)$flat['rooms_type_vhn'] : 0; ?>
                  <select name="flat[rooms_type_vhn]" class="form-control" <?= ($t==='5') ? '' : 'disabled' ?> required>
                    <option value="0"  <?= ($rvhn===0)?'selected':'' ?>> - </option>
                    <option value="1"  <?= ($rvhn===1)?'selected':'' ?>>ресторан</option>
                    <option value="2"  <?= ($rvhn===2)?'selected':'' ?>>гостинница</option>
                    <option value="3"  <?= ($rvhn===3)?'selected':'' ?>>бар</option>
                    <option value="4"  <?= ($rvhn===4)?'selected':'' ?>>парикмахерская</option>
                    <option value="5"  <?= ($rvhn===5)?'selected':'' ?>>дом пристарелых</option>
                    <option value="6"  <?= ($rvhn===6)?'selected':'' ?>>спортивный зал</option>
                    <option value="7"  <?= ($rvhn===7)?'selected':'' ?>>продуктовый магазин</option>
                    <option value="8"  <?= ($rvhn===8)?'selected':'' ?>>киоск</option>
                    <option value="9"  <?= ($rvhn===9)?'selected':'' ?>>мастерская</option>
                    <option value="10" <?= ($rvhn===10)?'selected':'' ?>>объект сельскохозяйственного назначения</option>
                    <option value="11" <?= ($rvhn===11)?'selected':'' ?>>помещение промышленного назначения</option>
                    <option value="12" <?= ($rvhn===12)?'selected':'' ?>>больница</option>
                    <option value="13" <?= ($rvhn===13)?'selected':'' ?>>салон красоты</option>
                    <option value="14" <?= ($rvhn===14)?'selected':'' ?>>спортклуб</option>
                    <option value="15" <?= ($rvhn===15)?'selected':'' ?>>закусочная</option>
                    <option value="16" <?= ($rvhn===16)?'selected':'' ?>>павильон</option>
                    <option value="17" <?= ($rvhn===17)?'selected':'' ?>>столовая</option>
                    <option value="18" <?= ($rvhn===18)?'selected':'' ?>>кондитерский цех</option>
                    <option value="19" <?= ($rvhn===19)?'selected':'' ?>>магазин</option>
                    <option value="20" <?= ($rvhn===20)?'selected':'' ?>>склад</option>
                    <option value="21" <?= ($rvhn===21)?'selected':'' ?>>офис</option>
                  </select>
                </td>
              </tr>
              <tr data-type="5" <?= ($t==='5') ? '' : 'style="display:none;"' ?>>
                <td>Свойства объекта</td>
                <td>
                  <select name="flat[properties][]" class="form-control" multiple>
                    <?php if (isset($properties4) && is_array($properties4)): foreach ($properties4 as $p): ?>
                      <option value="<?= (int)$p['id'] ?>" <?= (!empty($flat['properties']) && array_key_exists($p['id'], $flat['properties'])) ? 'selected' : '' ?>><?= hs($p['pagetitle']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
                </td>
              </tr>

            </table>
          </div>
        </div>
      </div>

      <!-- ====== АППАРТАМЕНТЫ ====== -->
      <div class="tab-pane" id="appartament">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

          <div class="panel panel-success">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Уровень 3</a></h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <div class="col-xs-12">
                  <div class="make-switch" data-on="success" data-off="danger">
                    <input type="checkbox" class="js_status_appartament" <?= (isset($flat['appartament'][3]) ? 'checked' : '') ?> >
                  </div>
                </div>
                <?php if (isset($appartament) && is_array($appartament)): foreach ($appartament as $a): ?>
                  <div class="col-xs-2">
                    <div class="form-group">
                      <label><?= hs($a['pagetitle']) ?></label>
                      <input min="0" type="number" name="flat[appartament][3][<?= (int)$a['id'] ?>]" class="form-control" placeholder="<?= hs($a['pagetitle']) ?>" value="<?= hs(valn($flat, 'appartament',3,$a['id'])) ?>" <?= (!isset($flat['appartament'][3]) ? 'disabled' : '') ?>>
                    </div>
                  </div>
                <?php endforeach; endif; ?>
              </div>
            </div>
          </div>

          <div class="panel panel-info">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">Уровень 2</a></h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
                <div class="col-xs-12">
                  <div class="make-switch" data-on="success" data-off="danger">
                    <input type="checkbox" class="js_status_appartament" <?= (isset($flat['appartament'][2]) ? 'checked' : '') ?> >
                  </div>
                </div>
                <?php if (isset($appartament) && is_array($appartament)): foreach ($appartament as $a): ?>
                  <div class="col-xs-2">
                    <div class="form-group">
                      <label><?= hs($a['pagetitle']) ?></label>
                      <input min="0" type="number" name="flat[appartament][2][<?= (int)$a['id'] ?>]" class="form-control" placeholder="<?= hs($a['pagetitle']) ?>" value="<?= hs(valn($flat, 'appartament',2,$a['id'])) ?>" <?= (!isset($flat['appartament'][2]) ? 'disabled' : '') ?>>
                    </div>
                  </div>
                <?php endforeach; endif; ?>
              </div>
            </div>
          </div>

          <div class="panel panel-warning">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">Уровень 1</a></h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">
                <div class="col-xs-12">
                  <div class="make-switch" data-on="success" data-off="danger">
                    <input type="checkbox" class="js_status_appartament" <?= (isset($flat['appartament'][1]) ? 'checked' : '') ?> >
                  </div>
                </div>
                <?php if (isset($appartament) && is_array($appartament)): foreach ($appartament as $a): ?>
                  <div class="col-xs-2">
                    <div class="form-group">
                      <label><?= hs($a['pagetitle']) ?></label>
                      <input min="0" type="number" name="flat[appartament][1][<?= (int)$a['id'] ?>]" class="form-control" placeholder="<?= hs($a['pagetitle']) ?>" value="<?= hs(valn($flat, 'appartament',1,$a['id'])) ?>" <?= (!isset($flat['appartament'][1]) ? 'disabled' : '') ?>>
                    </div>
                  </div>
                <?php endforeach; endif; ?>
              </div>
            </div>
          </div>

          <div class="panel panel-danger">
            <div class="panel-heading" role="tab" id="headingFour">
              <h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseVour" aria-expanded="true" aria-controls="collapseVour">Уровень -1</a></h4>
            </div>
            <div id="collapseVour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
              <div class="panel-body">
                <div class="col-xs-12">
                  <div class="make-switch" data-on="success" data-off="danger">
                    <input type="checkbox" class="js_status_appartament" <?= (isset($flat['appartament'][0]) ? 'checked' : '') ?> >
                  </div>
                </div>
                <?php if (isset($appartament) && is_array($appartament)): foreach ($appartament as $a): ?>
                  <div class="col-xs-2">
                    <div class="form-group">
                      <label><?= hs($a['pagetitle']) ?></label>
                      <input min="0" type="number" name="flat[appartament][0][<?= (int)$a['id'] ?>]" class="form-control" placeholder="<?= hs($a['pagetitle']) ?>" value="<?= hs(valn($flat, 'appartament',0,$a['id'])) ?>" <?= (!isset($flat['appartament'][0]) ? 'disabled' : '') ?>>
                    </div>
                  </div>
                <?php endforeach; endif; ?>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ====== ВЛАДЕЛЕЦ ====== -->
      <div class="tab-pane" id="info">
        <table class="table table-bordered table-condensed table-striped">
          <tr>
            <td><b>Имя владельца</b></td>
            <td><input type="text" name="flat[owner_name]" class="form-control" value="<?= hs(val($flat,'owner_name')) ?>"></td>
          </tr>
          <tr>
            <td><b>Телефон владельца</b></td>
            <td><input type="text" name="flat[owner_phone]" class="form-control" value="<?= hs(val($flat,'owner_phone')) ?>"></td>
          </tr>
          <tr>
            <td><b>Комментарии</b></td>
            <td><textarea name="flat[owner_comment]" cols="30" rows="10" class="form-control"><?= hs(val($flat,'owner_comment')) ?></textarea></td>
          </tr>
        </table>
      </div>

      <!-- ====== ФАЙЛЫ ====== -->
      <div class="tab-pane" id="file">
        <div class="form-group">
          <label for="exampleInputFile">Загрузить</label>
          <input type="file" id="exampleInputFile" name="files[]" class="btn btn-default" multiple>
        </div>
        <ul>
          <?php if (!empty($files) && is_array($files)): ?>
            <?php foreach ($files as $f): $fe = explode('/', $f); $fn = end($fe); ?>
              <li>
                <a href="<?= hs((isset($modx->config['site_url']) ? $modx->config['site_url'] : '/').'assets/files/flat/'.hs(val($flat,'id')).'/'.$fn) ?>" target="_blank"><?= hs($fn) ?></a>
                <a class="btn btn-primary btn-xs" data-file-delete="<?= hs($fn) ?>"><span class="glyphicon glyphicon-trash"></span></a>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>

    </div><!-- /.tab-content -->
  </div><!-- /.row -->

  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-body">
            <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span> <b>Сохранить</b></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


<script>
// ---- Копирование UA -> RU (стрелка) ----
$(document).on('click', '.js-copy-right', function (e) {
  e.preventDefault();
  var $tr  = $(this).closest('tr');
  var $src = $tr.find('td').eq(0).find('input, textarea, select').first();
  var $dst = $tr.find('td').last().find('input, textarea, select').first();
  if ($src.length && $dst.length) {
    $dst.val($src.val()).trigger('input').trigger('change');
  }
});

// ---- Галерея: подгрузка верстки списка изображений ----
function updateImages(){
  $.ajax({
    url: "index.php",
    type: "GET",
    data: "a=112&id=7&b=flat&c=getImages&folder=<?= hs(val($flat,'id')) ?>",
    success:function(ajax){
      $('#upload-overlay').hide();
      $(".images").html(ajax);
      $(".images img[alt='"+$("#cover").val()+"']").addClass("cover");
      if ($.fn.sortable) { $(".images").sortable(); $(".images").disableSelection(); }
      try { window.updateCounterUI(); } catch(e){}
    }
  });
}

// ---- Панорама/файл-менеджер (старый MCPUK) ----
var lastImageCtrl, lastFileCtrl;
function OpenServerBrowser(url, width, height ) {
  var iLeft = (screen.width  - width) / 2 ;
  var iTop  = (screen.height - height) / 2 ;
  var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes';
  sOptions += ',width=' + width;
  sOptions += ',height=' + height;
  sOptions += ',left=' + iLeft;
  sOptions += ',top=' + iTop;
  window.open(url, 'FCKBrowseWindow', sOptions);
}
function BrowseServer(ctrl) {
  lastImageCtrl = ctrl;
  var w = screen.width * 0.5, h = screen.height * 0.5;
  OpenServerBrowser('/manager/media/browser/mcpuk/browser.php?Type=images', w, h);
}
function BrowseFileServer(ctrl) {
  lastFileCtrl = ctrl;
  var w = screen.width * 0.5, h = screen.height * 0.5;
  OpenServerBrowser('/manager/media/browser/mcpuk/browser.php?Type=files', w, h);
}
function SetUrlChange(el) {
  if ('createEvent' in document) {
    var evt = document.createEvent('HTMLEvents');
    evt.initEvent('change', false, true);
    el.dispatchEvent(evt);
  } else if (el.fireEvent) {
    el.fireEvent('onchange');
  }
  if (el.value) {
    $('#flat_panorama_image_thumb img').attr('src','/'+el.value).show();
  } else {
    $('#flat_panorama_image_thumb img').hide();
  }
}
function SetUrl(url, width, height, alt) {
  var c;
  if(lastFileCtrl) {
    c = document.getElementById(lastFileCtrl);
    if(c && c.value != url) { c.value = url; SetUrlChange(c); }
    lastFileCtrl = '';
  } else if(lastImageCtrl) {
    c = document.getElementById(lastImageCtrl);
    if(c && c.value != url) { c.value = url; SetUrlChange(c); }
    lastImageCtrl = '';
  }
}

// ---- Кламп рейтинга 0..5 ----
$(document).on("input", "[name='flat[rating]']", function() {
  var v = parseFloat($(this).val());
  if (v > 5) $(this).val(5);
  else if (v < 0) $(this).val(0);
});

// ---- Выбор обложки (preview) ----
$(document).on("click", ".images .btn-primary, .image img", function(e) {
  e.preventDefault();
  var _img = $(this).parents("li").find("img");
  $("#cover").val(_img.attr('alt'));
  $(".images .cover").removeClass("cover");
  _img.addClass("cover");
});

// ---- Удаление изображения + обновление счётчика ----
$(document).on("click", ".images .btn-danger", function() {
  if (!confirm("Удалить ?")) return false;
  var $li  = $(this).closest('li');
  var $img = $li.find('img').first();
  if ($img.hasClass("cover")) {
    $("#cover").val('');
    $img.removeClass("cover");
  }
  $.ajax({
    url:"index.php",
    type:"GET",
    data:"a=112&id=7&b=flat&c=delete_image&folder=<?= hs(val($flat,'id')) ?>&delete="+($img.attr("alt")||""),
    success:function(){
      $li.fadeOut(function(){
        $(this).remove();
        try { window.updateCounterUI(); } catch(e){}
      });
    }
  });
});

// ---- Легаси-хук на категории ----
$('.selectpicker').on('change', function(){
  if($('.selectpicker').val()){
    $("#check1").trigger('click');
  }
});

// ===================== ЗАГРУЗКА ФОТО (с лимитом и счётчиком) =====================
(function (window, document, $) {
  if (!$) return;

  // --- элементы UI ---
  var $btn     = $("#uploader");
  var $input   = $("#uploader-input");
  var $overlay = $("#upload-overlay");
  var $spinTxt = $(".js-up-count");
  var $spinOn  = $(".js-up-spin");
  var $spinOff = $(".js-up-text");

  if (!$btn.length || !$input.length) return;

  // --- конфиг из data-атрибутов ---
  var ENDPOINT    = $btn.data("endpoint")    || "index.php?a=112&id=7&b=flat&c=upload";
  var FOLDER      = $btn.data("folder")      || "tmp";
  var SIZE_LIM    = parseInt($btn.data("size"), 10) || 3048576;
  var CONCURRENCY = parseInt($btn.data("concurrency"), 10) || 3;
  var RETRIES     = parseInt($btn.data("retries"), 10)     || 2;
  var ALLOWED_RE  = /\.(jpg|jpeg|png|gif|webp|bmp)$/i;
  var MAX_FILES   = 100; // мягкий лимит на один выбор
  var TOTAL_LIMIT = (function(v){ v=parseInt(v,10); return isNaN(v)? Infinity : v; })($btn.data("total"));

  // ---- Счётчик изображений (глобально) ----
  window.updateCounterUI = function () {
    var cnt = $(".images li").length; // при другом шаблоне можно посчитать по img
    $(".js-img-count").text(cnt);
    if (isFinite(TOTAL_LIMIT)) {
      $(".js-img-total").text(" / " + TOTAL_LIMIT);
    } else {
      $(".js-img-total").text("");
    }
  };

  function currentImageCount() {
    return $(".images li").length;
  }

  // --- нормализация списка файлов + учёт лимитов ---
  function normalizeAndCapFileList(listLike, allowedByTotal) {
    var arr = listLike ? Array.prototype.slice.call(listLike) : [];
    arr = arr.filter(function (f) { return ALLOWED_RE.test((f && f.name) ? f.name : ""); });

    // мягкий лимит на один выбор
    if (arr.length > MAX_FILES) {
      try { alert("Вы выбрали " + arr.length + " файлов. Будут загружены только первые " + MAX_FILES + "."); } catch(e){}
      arr = arr.slice(0, MAX_FILES);
    }

    // общий лимит
    if (isFinite(TOTAL_LIMIT)) {
      var remain = Math.max(0, allowedByTotal|0);
      if (remain <= 0) return []; // вообще ничего нельзя
      if (arr.length > remain) {
        try { alert("Можно прикрепить ещё максимум " + remain + " файл(ов) из-за общего лимита."); } catch(e){}
        arr = arr.slice(0, remain);
      }
    }
    return arr;
  }

  // --- открытие диалога выбора ---
  $btn.on("click", function (e) {
    e.preventDefault();
    if ($btn.prop("disabled")) return;
    this.blur();
    if ($input[0] && $input[0].click) $input[0].click();
  });

  // --- выбор файлов: запускаем очередь ---
  $input.on("change", function () {
    var already = currentImageCount();
    var remain  = isFinite(TOTAL_LIMIT) ? (TOTAL_LIMIT - already) : Infinity;

    var files = normalizeAndCapFileList(this.files, remain);
    if (!files.length) {
      if (isFinite(TOTAL_LIMIT) && remain <= 0) {
        alert("Достигнут общий лимит изображений (" + TOTAL_LIMIT + "). Удалите лишние, чтобы загрузить новые.");
      }
      return;
    }

    setUploadingUI(true);
    runQueue(files, CONCURRENCY, RETRIES, function () {
      setUploadingUI(false);
      $input.val("");
      if (typeof window.updateImages === "function") {
        try { window.updateImages(); } catch (e) {}
      } else {
        try { window.updateCounterUI(); } catch(e){}
      }
    });
  });

  // --- drag & drop на кнопку (можно расширить до всего документа) ---
  enableDropZone($btn);

  // ===== Реализация очереди =====
  function runQueue(files, concurrency, retries, doneCb) {
    var total    = files.length;
    var inFlight = 0;
    var finished = 0;
    var index    = 0;

    tick();
    pump();

    function pump() {
      while (inFlight < concurrency && index < total) {
        (function (file, attemptLeft) {
          inFlight++;
          tick();
          uploadOne(file, attemptLeft, function () {
            inFlight--;
            finished++;
            tick();
            if (finished === total) return doneCb();
            pump();
          });
        })(files[index++], retries);
      }
    }

    function tick() {
      var txt = finished + " / " + total + (inFlight ? " " : "");
      $spinTxt.text(txt);
    }
  }

  // Загрузка одного файла с ретраями
  function uploadOne(file, retriesLeft, cb) {
    var fd = new FormData();
    fd.append("uploader[]", file, file.name || "file");
    fd.append("size",  SIZE_LIM);
    fd.append("folder", FOLDER);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", ENDPOINT, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      var ok = (xhr.status >= 200 && xhr.status < 300 && /True/i.test(xhr.responseText || ""));
      if (ok) { cb(true); return; }

      if (retriesLeft > 0) {
        backoff(function () { uploadOne(file, retriesLeft - 1, cb); });
      } else {
        try { console.warn("Upload failed:", file && file.name, xhr.status, xhr.responseText); } catch (e) {}
        cb(false);
      }
    };

    xhr.onerror = function () {
      if (retriesLeft > 0) {
        backoff(function () { uploadOne(file, retriesLeft - 1, cb); });
      } else {
        try { console.warn("Network error:", file && file.name); } catch (e) {}
        cb(false);
      }
    };

    xhr.send(fd);
  }

  // Небольшой экспоненциальный бэк-офф (200–800 мс)
  function backoff(fn) {
    var base = 200 + Math.floor(Math.random() * 200);
    setTimeout(fn, base * (1 + Math.floor(Math.random() * 3)));
  }

  // Смена UI-состояния загрузки
  function setUploadingUI(isOn) {
    if (isOn) {
      $overlay.show();
      $spinOn.show();
      $spinOff.hide();
      $btn.prop("disabled", true);
    } else {
      $overlay.hide();
      $spinOn.hide();
      $spinOff.show().text("Загрузить");
      $btn.prop("disabled", false);
    }
  }

  // Поддержка drag & drop
  function enableDropZone($zone) {
    var zone = $zone && $zone[0] ? $zone[0] : document.body;

    function prevent(e) { e.preventDefault(); e.stopPropagation(); }
    function addHover() { $zone.addClass("is-dragover"); }
    function rmHover()  { $zone.removeClass("is-dragover"); }

    zone.addEventListener("dragenter", function (e) { prevent(e); addHover(); }, false);
    zone.addEventListener("dragover",  function (e) { prevent(e); addHover(); }, false);
    zone.addEventListener("dragleave", function (e) { prevent(e); rmHover();  }, false);
    zone.addEventListener("drop", function (e) {
      prevent(e); rmHover();
      if ($btn.prop("disabled")) return;

      var dt = e.dataTransfer;
      if (!dt || !dt.files || !dt.files.length) return;

      var already = currentImageCount();
      var remain  = isFinite(TOTAL_LIMIT) ? (TOTAL_LIMIT - already) : Infinity;
      var files   = normalizeAndCapFileList(dt.files, remain);
      if (!files.length) {
        if (isFinite(TOTAL_LIMIT) && remain <= 0) {
          alert("Достигнут общий лимит изображений (" + TOTAL_LIMIT + "). Удалите лишние, чтобы загрузить новые.");
        }
        return;
      }

      setUploadingUI(true);
      runQueue(files, CONCURRENCY, RETRIES, function () {
        setUploadingUI(false);
        if (typeof window.updateImages === "function") {
          try { window.updateImages(); } catch (err) {}
        } else {
          try { window.updateCounterUI(); } catch(e){}
        }
      });
    }, false);
  }

  // Инициализация счётчика на старте
  try { window.updateCounterUI(); } catch(e){}
})(window, document, window.jQuery);
</script>
