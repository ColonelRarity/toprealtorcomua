<? if ($flash['success'] != ''): ?>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?=$flash['success']?>
    </div>
<? endif ?>

<form action="<?=$url?>b=flat&c=update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="flat[id]" value="<?=$flat['id']?>">

    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#base" data-toggle="tab"><span class="glyphicon glyphicon-home"></span> Осн. информация</a></li>
            <li class=""><a href="#textbase" data-toggle="tab"><span class="glyphicon glyphicon-font"></span> Текст.информация</a></li>
            <li><a href="#content" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Контент</a></li>
            <li><a href="#seo" data-toggle="tab"><span class="glyphicon glyphicon-search"></span> SEO</a></li>
            <li><a href="#gallery" data-toggle="tab"><span class="glyphicon glyphicon-picture"></span> Галерея</a></li>
            <li><a href="#character" data-toggle="tab"><span class="glyphicon glyphicon-info-sign"></span> Характеристики</a></li>
            <li><a href="#appartament" data-toggle="tab"><span class="glyphicon glyphicon-thumbs-up"></span> Аппартаменты</a></li>
            <li><a href="#info" data-toggle="tab"><span class="glyphicon glyphicon-info-sign"></span> Владелец</a></li>
            <li><a href="#file" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> Файлы</a></li>
        </ul>

        <div class="tab-content">

            <!-- ====== ТЕКСТ.ИНФОРМАЦИЯ ====== -->
            <div class="tab-pane" id="textbase">
                <ul class="nav nav-tabs" id="vallangTabs">
                    <?php foreach($flatStrings as $key => $value):?>
                        <li class="<?= $key == 'ru' ? 'active' : ''?>"><a href="#tab_val_<?= $key ?>" data-toggle="tab"><?= strtoupper($key) ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content">
                    <?php foreach($flatStrings as $key => $value): ?>
                        <div class="tab-pane <?= $key == 'ru' ? 'active' : ''?>" id="tab_val_<?= $key ?>">
                            <table class="table table-bordered table-condensed table-striped">
                                <tr>
                                    <th width="150px">Название</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][name]" id="firstname" class="form-control" value="<?=isset($value['name'])?$value['name']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Cтрана</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][country]" class="form-control" value="<?=isset($value['country'])?$value['country']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Область</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][region]" class="form-control" value="<?=isset($value['region'])?$value['region']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Город</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][locality_name]" class="form-control" value="<?=isset($value['locality_name'])?$value['locality_name']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Район</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][non_admin_sub_locality]" class="form-control" value="<?=isset($value['non_admin_sub_locality'])?$value['non_admin_sub_locality']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Станция метро(yml)</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][metro]" class="form-control" value="<?=isset($value['metro'])?$value['metro']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="170px">Микрорайон (yml) <span class="glyphicon glyphicon-info-sign" title="Название микрорайона. Необходимо для корректного формирования yml"></span></th>
                                    <td><input type="text" name="flatString[<?= $key ?>][microdistrict_xml]" class="form-control" value="<?=isset($value['microdistrict_xml'])?$value['microdistrict_xml']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="170px">Улица (yml) <span class="glyphicon glyphicon-info-sign" title="Название улицы. Необходимо для корректного формирования yml"></span></th>
                                    <td><input type="text" name="flatString[<?= $key ?>][street_xml]" class="form-control" value="<?=isset($value['street_xml'])?$value['street_xml']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="170px">№ дома (yml) <span class="glyphicon glyphicon-info-sign" title="Номер дома. Необходимо для корректного формирования yml"></span></th>
                                    <td><input type="text" name="flatString[<?= $key ?>][house_number_xml]" class="form-control" value="<?=isset($value['house_number_xml'])?$value['house_number_xml']:''?>"></td>
                                </tr>
                                <tr>
                                    <th width="150px">Адрес</th>
                                    <td><input type="text" name="flatString[<?= $key ?>][addr]" class="form-control" value="<?=isset($value['addr'])?$value['addr']:''?>"></td>
                                </tr>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ====== БАЗА ====== -->
            <div class="tab-pane active" id="base">
                <table class="table table-bordered table-condensed table-striped">
                    <tr>
                        <td><b>Дата создания</b></td>
                        <td><input type="text" name="flat[created_at]" class="form-control" value="<?=$flat['created_at']?>" disabled></td>
                    </tr>
                    <tr>
                        <td><b>Последняя дата редактирования</b></td>
                        <td><input type="text" name="flat[updated_at]" class="form-control" value="<?=$flat['updated_at']?>" disabled></td>
                    </tr>
                    <tr>
                        <td><b>Актуализация</b></td><? $flat['status'] = explode(",", $flat['status']); ?>
                        <td>
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input class="js_status" type="checkbox" name="flat[active_now]" <?= (date('Y-m-d', strtotime($flat['updated_at'])) == date('Y-m-d') ? "checked" : "") ?> data-status="<?=$flat['id']?>" value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Активность</b></td>
                        <td>
                            <input type="hidden" name="flat[active]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" name="flat[active]" <?=($flat['active'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Выгрузка в xml</b></td>
                        <td>
                            <input type="hidden" name="flat[xml_active]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" <?=(in_array(4, $flat['status']) ? 'disabled': "")?> <?=(in_array(3, $flat['status']) ? 'disabled': "")?> name="flat[xml_active]" <?=($flat['xml_active'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Выгрузка в vhn.ua</b></td>
                        <td>
                            <input type="hidden" name="flat[xml_active_vhn]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" <?=(in_array(4, $flat['status']) ? 'disabled': "")?> <?=(in_array(3, $flat['status']) ? 'disabled': "")?> name="flat[xml_active_vhn]" <?=($flat['xml_active_vhn'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Выгрузка в lun.ua</b></td>
                        <td>
                            <input type="hidden" name="flat[xml_active_lun]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" <?=(in_array(4, $flat['status']) ? 'disabled': "")?> <?=(in_array(3, $flat['status']) ? 'disabled': "")?> name="flat[xml_active_lun]" <?=($flat['xml_active_lun'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Статус</b></td>
                        <td>
                            <select name="flat[status][]" class="form-control" required multiple="multiple">
                                <option value=""> - </option>
                                <option value="1" <?=(in_array(1, $flat['status']) ? 'selected': "")?>>Продажа</option>
                                <option value="2" <?=(in_array(2, $flat['status']) ? 'selected': "")?>>Аренда</option>
                                <option value="3" <?=(in_array(3, $flat['status']) ? 'selected': "")?>>Снят с продажи</option>
                                <option value="4" <?=(in_array(4, $flat['status']) ? 'selected': "")?>>Продан</option>
                                <option value="5" <?=(in_array(5, $flat['status']) ? 'selected': "")?>>Скрытая продажа</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Статус vhn.ua</b></td>
                        <td>
                            <select name="flat[status_vhn]" class="form-control" required>
                                <?php if ($flat['status_vhn'] == '') {$flat['status_vhn'] = 4;} ?>
                                <option value="0" <?=($flat['status_vhn'] ==0 ? 'selected': "")?>> - </option>
                                <option value="1" <?=($flat['status_vhn'] ==1 ? 'selected': "")?>>Платный эксклюзив</option>
                                <option value="2" <?=($flat['status_vhn'] ==2 ? 'selected': "")?>>Бесплатный эксклюзив</option>
                                <option value="3" <?=($flat['status_vhn'] ==3 ? 'selected': "")?>>Болванка</option>
                                <option value="4" <?=($flat['status_vhn'] ==4  ? 'selected': "")?>>Реклама реального объекта</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Цена</th>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" name="flat[price]" value="<?=$flat['price']?>">
                                <div class="input-group-addon">$</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Старая цена</th>
                        <td>
                            <div class="input-group">
                                <input type="text" name="flat[old_price]" class="form-control" value="<?=$flat['old_price']?>">
                                <div class="input-group-addon">$</div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th width="150px">История цен</th>
                        <td>
                            <div class="input-group">
                                

                              <!-- чекбокс только сохраняем, на таблицу не влияет -->
                              <label style="display:block; margin-bottom:8px;">
                                <input type="hidden" name="flat[show_price_history]" value="0">
                                <div class="make-switch" data-on="success" data-off="danger">
                                                            <input type="checkbox" name="flat[show_price_history]" <?=($flat['show_price_history'] == 1 ? "checked" : "")?>  value="1">
                                                        </div>


                                Отображать историю цен
                              </label>

                              <table class="table table-bordered" id="price-history-table" style="margin-bottom:8px;">
                                <thead>
                                  <tr>
                                    <th style="width:40px;"></th>
                                    <th style="width:180px;">Цена ($)</th>
                                    <th style="width:200px;">Дата старта цены</th>
                                    <th style="width:220px;">Действия</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  // История цен приходит как $flatPrice (результат getFlatPriceHistory)
                                  $rows = (!empty($flatPrice) && is_array($flatPrice))
                                      ? array_values($flatPrice)
                                      : [];

                                  // Фоллбек: если истории нет — покажем 1 строку из $flat['price']
                                  if (empty($rows)) {
                                      $rows[] = [
                                          'price'      => isset($flat['price']) ? $flat['price'] : '',
                                          'start_date' => '',
                                      ];
                                  }

                                  foreach ($rows as $i => $r):
                                    $price = htmlspecialchars($r['price'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $date  = htmlspecialchars($r['start_date'] ?? '', ENT_QUOTES, 'UTF-8');
                                  ?>
                                    <tr class="ph-row" data-index="<?= (int)$i ?>">
                                      <td class="ph-handle" style="cursor:move; text-align:center; vertical-align:middle;">☰</td>

                                      <td>
                                        <div class="input-group">
                                          <input type="number"
                                                 class="form-control ph-price"
                                                 name="flatPrice[price_history][<?= (int)$i ?>][price]"
                                                 value="<?= $price ?>">
                                          <div class="input-group-addon">$</div>
                                        </div>
                                      </td>

                                      <td>
                                        <input type="date"
                                               class="form-control ph-date"
                                               name="flatPrice[price_history][<?= (int)$i ?>][start_date]"
                                               value="<?= $date ?>">
                                      </td>

                                      <td>
                                        <button type="button" class="btn btn-default btn-sm ph-clone">Клонировать</button>

                                        <?php if ($i === 0): ?>
                                          <button type="button" class="btn btn-warning btn-sm ph-clear">Очистить</button>
                                        <?php else: ?>
                                          <button type="button" class="btn btn-danger btn-sm ph-delete">Удалить</button>
                                        <?php endif; ?>
                                      </td>
                                    </tr>
                                  <?php endforeach; ?>
                                </tbody>
                              </table>

                              <button type="button" class="btn btn-primary btn-sm" id="ph-add-row">+ Добавить строку</button>

                              

                                    </td>
                                    </tr>


                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th width="150px">Агент</th>
                        <td>
                            <select name="flat[agent]" class="form-control">
                                <?php foreach($agents as $a) : ?>
                                    <option value="<?=$a['id']?>" <?=($a['id'] == $flat['agent'] ? 'selected' : '')?>><?=$a['pagetitle']?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Категория</th>
                        <td>
                            <select class="selectpicker form-control" name="flat[categories][]" multiple data-live-search="true">
                                <?php foreach($categories as $c) : ?>
                                    <option value="<?=$c['id']?>" <?=(array_key_exists($c['id'], $flat['categories']) ? 'selected' : '')?>><?=$c['pagetitle']?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">URL</th>
                        <td><input type="text" name="flat[url]" class="form-control" value="<?=$flat['url']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Рейтинг (0-5)</th>
                        <td><input type="number" name="flat[rating]"  class="form-control" value="<?=$flat['rating']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Youtube</th>
                        <td><input type="text" name="flat[video]" class="form-control" value="<?=$flat['video']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Отображение коттеджных поселков</th>
                        <td>
                            <input type="hidden" name="flat[cottage_view]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" name="flat[cottage_view]" <?=($flat['cottage_view'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Отображение watermark</th>
                        <td>
                        		<input type="hidden" name="flat[watermark_old]" value="<?=$flat['watermark']?>">
                            <input type="hidden" name="flat[watermark]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" name="flat[watermark]" <?=($flat['watermark'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px"><?= (in_array($flat['type'], [2,3]) ? 'Отображение кнопки "Показать дома (участки) в этом районе"' : 'Отображение кнопки "Показать квартиры в доме"') ?></th>
                        <td>
                            <input type="hidden" name="flat[flatInHouse_view]" value="0">
                            <div class="make-switch" data-on="success" data-off="danger">
                                <input type="checkbox" name="flat[flatInHouse_view]" <?=($flat['flatInHouse_view'] == 1 ? "checked" : "")?>  value="1">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Координаты <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Подберутся автоматически по адресу, или прописать вручную"></span></th>
                        <td><input type="text" name="flat[coord]" class="form-control" value="<?=$flat['coord']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Панорама <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Вставлять полную сылку из карт google. Используется НЕ в галерее"></span></th>
                        <td><input type="text" name="flat[panorama]" class="form-control" value="<?=$flat['panorama']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Панорама для галереи<span class="glyphicon glyphicon-info-sign" title="" data-original-title="Ссылка. Используется только для вставки в галерее."></span></th>
                        <td><input type="text" name="flat[panorama_gallery]" class="form-control" value="<?=$flat['panorama_gallery']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Превью панорамы <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Вставить картинку-превью для отображения в галерее"></span></th>
                        <td>
                            <input type="text" name="flat[panorama_image]" id="flat_panorama_image" class="form-control" value="<?=$flat['panorama_image']?>" style="width:80%;float:left">
                            <input type="button" class="btn btn-success" value="Вставить" onclick="BrowseServer('flat_panorama_image')">
                            <div id="flat_panorama_image_thumb">
                                <img src="/<?=$flat['panorama_image']?>" width="150" <? if (empty($flat['panorama_image'])) {echo 'style="display:none"';}?> >
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th width="150px">Панорама объекта 360 <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Ссылка на панораму"></span></th>
                        <td><input type="text" name="flat[panorama_360]" class="form-control" value="<?=$flat['panorama_360']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Застройщик <span class="glyphicon glyphicon-info-sign" title="" data-original-title="Информация от застройка. Вставлять сылку"></span></th>
                        <td><input type="text" name="flat[link_developer]" class="form-control"  value="<?=$flat['link_developer']?>"></td>
                    </tr>
                    <tr>
                        <th width="150px">Доход годовых (в процентах)<span class="glyphicon glyphicon-info-sign" title="" data-original-title="Процент дохода годовых для клиент"></span></th>
                        <td><input type="number" name="flat[advantage_for_client]" class="form-control" value="<?=$flat['advantage_for_client']?>"></td>
                    </tr>
                </table>

                <h3>Выгрузка в Plectan</h3>
                <table class="table table-bordered table-condensed table-striped">
                    <tr>
                        <th width="250px">Название района области</th>
                        <td><input type="text" name="flat[plectan_district]" id="plectan_district" readonly class="form-control" value="<?=$flat['plectan_district']?>" required></td>
                    </tr>
                    <tr>
                        <th width="250px">Название города</th>
                        <td>
                            <select name="flat[plectan_city]" id="plectan_city" class="form-control" data-live-search="true" data-size="10" required>
                                <?php foreach($plectan_city as $district => $cities) : ?>
                                    <optgroup label="<?php echo $district; ?>">
                                        <?php foreach($cities as $c) : ?>
                                            <option value="<?=$c;?>" <?=($c === $flat['plectan_city'] ? 'selected' : '')?> ><?=$c;?></option>
                                        <?php endforeach;?>
                                    </optgroup>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr id="plectan_area" style="<?= ($flat['plectan_city']==='Харків' ? '' : 'display:none;') ?>">
                        <th width="250px">Район города</th>
                        <td>
                            <select name="flat[plectan_area]" class="form-control" data-live-search="true" data-size="10">
                                <?php foreach($plectan_area as $district => $areas) : ?>
                                    <optgroup label="<?php echo $district; ?>">
                                        <?php foreach($areas as $c) : ?>
                                            <option value="<?=$c;?>" <?=($c === $flat['plectan_area'] ? 'selected' : '')?> ><?=$c;?></option>
                                        <?php endforeach;?>
                                    </optgroup>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- ====== КОНТЕНТ ====== -->
            <div class="tab-pane" id="content">
                <ul class="nav nav-tabs" id="langTabs">
                    <?php foreach($flatStrings as $key => $value):?>
                        <li class="<?= $key == 'ru' ? 'active' : ''?>"><a href="#tab_content_<?= $key ?>" data-toggle="tab"><?= strtoupper($key) ?></a></li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content" style="margin-top:10px">
                    <?php foreach($flatStrings as $key => $value): ?>
                        <div class="tab-pane <?= ($key=='ru'?'active':'') ?>" id="tab_content_<?= $key ?>">
                            <table class="table table-bordered table-condensed table-striped">
                                <tr>
                                    <td width="200"><b>Описание</b></td>
                                    <td>
                                        <textarea name="flatString[<?= $key ?>][description]" id="description" class="form-control tiny col-xs-12" rows="10"><?=
                                            htmlspecialchars(isset($value['description']) ? $value['description'] : '', ENT_QUOTES, 'UTF-8')
                                        ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Расходы на содержание объекта</b></td>
                                    <td>
                                        <textarea name="flatString[<?= $key ?>][consumption]" id="consumption" class="form-control tiny" rows="10"><?=
                                            htmlspecialchars(isset($value['consumption']) ? $value['consumption'] : '', ENT_QUOTES, 'UTF-8')
                                        ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Налогообложение</b></td>
                                    <td>
                                        <textarea name="flatString[<?= $key ?>][taxation]" id="taxation" class="form-control tiny" rows="10"><?=
                                            htmlspecialchars(isset($value['taxation']) ? $value['taxation'] : '', ENT_QUOTES, 'UTF-8')
                                        ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ====== SEO ====== -->
            <div class="tab-pane" id="seo">
                <ul class="nav nav-tabs" id="seoLangTabs">
                    <?php foreach ($flatStrings as $key => $value): ?>
                        <li class="<?= ($key === 'ru' ? 'active' : '') ?>">
                            <a href="#tab_seo_<?= $key ?>" data-toggle="tab"><?= strtoupper($key) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="tab-content" style="margin-top:10px">
                    <?php foreach ($flatStrings as $key => $value): ?>
                        <div class="tab-pane <?= ($key === 'ru' ? 'active' : '') ?>" id="tab_seo_<?= $key ?>">
                            <table class="table table-bordered table-condensed table-striped">
                                <tr>
                                    <td width="200px"><b>Title</b></td>
                                    <td><input type="text" name="flatString[<?= $key ?>][product_seo_title]" id="seo_title_<?= $key ?>" class="form-control" value="<?= htmlspecialchars(isset($value['product_seo_title']) ? $value['product_seo_title'] : '', ENT_QUOTES, 'UTF-8') ?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Keywords</b></td>
                                    <td><input type="text" name="flatString[<?= $key ?>][product_seo_keywords]" id="seo_keywords_<?= $key ?>" class="form-control" value="<?= htmlspecialchars(isset($value['product_seo_keywords']) ? $value['product_seo_keywords'] : '', ENT_QUOTES, 'UTF-8') ?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Description</b></td>
                                    <td><input type="text" name="flatString[<?= $key ?>][product_seo_description]" id="seo_description_<?= $key ?>" class="form-control" value="<?= htmlspecialchars(isset($value['product_seo_description']) ? $value['product_seo_description'] : '', ENT_QUOTES, 'UTF-8') ?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Robots</b></td>
                                    <td><input type="text" name="flatString[<?= $key ?>][product_seo_robots]" id="seo_robots_<?= $key ?>" class="form-control" value="<?= htmlspecialchars(isset($value['product_seo_robots']) ? $value['product_seo_robots'] : '', ENT_QUOTES, 'UTF-8') ?>"></td>
                                </tr>
                                <tr>
                                    <td><b>Canonical</b></td>
                                    <td><input type="text" name="flatString[<?= $key ?>][product_seo_canonical]" id="seo_canonical_<?= $key ?>" class="form-control" value="<?= htmlspecialchars(isset($value['product_seo_canonical']) ? $value['product_seo_canonical'] : '', ENT_QUOTES, 'UTF-8') ?>"></td>
                                </tr>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ====== ГАЛЕРЕЯ (НОВЫЙ ЗАГРУЗЧИК) ====== -->
            <div class="tab-pane" id="gallery">
								<!-- КНОПКА ЗАГРУЗКИ (добавили data-total) -->
								<a href="#" id="uploader"
								   class="btn btn-warning"
								   data-endpoint="index.php?a=112&id=7&b=flat&c=upload"
								   data-folder="<?= (int)$flat['id'] ?>"
								   data-size="106428800"
								   data-concurrency="2"
								   data-retries="2"
								   data-total="100">
								    <span class="js-up-text">Загрузить</span>
								    <span class="js-up-spin" style="display:none;">Загружаем… <span class="js-up-count"></span></span>
								</a>

								<!-- СЧЁТЧИК ФОТО -->
								<small style="margin-left:8px;opacity:.8">
								  Фото: <strong><span class="js-img-count">0</span></strong><span class="js-img-total"></span>
								</small>

                <input id="uploader-input" type="file" multiple accept="image/*"
                       style="position:absolute; left:-9999px; width:1px; height:1px; opacity:0;">

                <div id="upload-overlay" style="display:none; position:fixed; inset:0; background:rgba(255,255,255,.6); z-index:9999;">
                    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); padding:12px 16px; background:#fff; border:1px solid #ddd; border-radius:8px;">
                        Идёт загрузка… <span class="js-up-count"></span>
                    </div>
                </div>

                <ul class="images"></ul>
                <input type="hidden" name="flat[preview]" id="cover" value="<?=$flat['preview']?>" />
                <div style="clear:both"></div>
            </div>

            <!-- ====== ХАРАКТЕРИСТИКИ ====== -->
            <div class="tab-pane" id="character">
                <div class="panel panel-default">
                    <div class="panel-heading">Основные характеристики</div>
                    <div class="panel-body">
                        <table class="table table-bordered table-condensed table-striped">
                            <tr>
                                <th></th>
                                <td><strong>Українська</strong></td>
                                <td style="width: 88px; text-align:center;"><span title="Стрілка копіює текст зліва направо">UA → RU</span></td>
                                <td><strong>Русский</strong></td>
                            </tr>
                            <tr>
                                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="1-я cтрока краткого описания"></span></th>
                                <td><input type="text" name="flatString[ua][character][]" class="form-control" value="<?=isset($flatStrings['ua']['character'][0])?$flatStrings['ua']['character'][0]:''?>"></td>
                                <td style="width: 88px;"><a class="btn js-copy-right" href="" title="Скопіювати цей рядок з української в російську">UA → RU</a></td>
                                <td><input type="text" name="flatString[ru][character][]" class="form-control" value="<?=isset($flatStrings['ru']['character'][0])?$flatStrings['ru']['character'][0]:''?>"></td>
                            </tr>
                            <tr>
                                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="2-я cтрока краткого описания"></span></th>
                                <td><input type="text" name="flatString[ua][character][]" class="form-control" value="<?=isset($flatStrings['ua']['character'][1])?$flatStrings['ua']['character'][1]:''?>"></td>
                                <td style="width: 88px;"><a class="btn js-copy-right" href="" title="Скопіювати цей рядок з української в російську">UA → RU</a></td>
                                <td><input type="text" name="flatString[ru][character][]" class="form-control" value="<?=isset($flatStrings['ru']['character'][1])?$flatStrings['ru']['character'][1]:''?>"></td>
                            </tr>
                            <tr>
                                <th>Краткое описание <span class="glyphicon glyphicon-info-sign" title="3-я cтрока краткого описания"></span></th>
                                <td><input type="text" name="flatString[ua][character][]" class="form-control" value="<?=isset($flatStrings['ua']['character'][2])?$flatStrings['ua']['character'][2]:''?>"></td>
                                <td style="width: 88px;"><a class="btn js-copy-right" href="" title="Скопіювати цей рядок з української в російську">UA → RU</a></td>
                                <td><input type="text" name="flatString[ru][character][]" class="form-control" value="<?=isset($flatStrings['ru']['character'][2])?$flatStrings['ru']['character'][2]:''?>"></td>
                            </tr>

                            <?php if($flat['type'] == 1 || $flat['type'] == 4 || $flat['type'] == 5  || $flat['type'] == 6): ?>
                                <tr>
                                    <th width="175px">Этаж</th>
                                    <td colspan="3"><input type="text" name="flat[floor]" class="form-control" value="<?=$flat['floor']?>"></td>
                                </tr>
                                <tr>
                                    <th width="175px">Этажность</th>
                                    <td colspan="3"><input type="text" name="flat[floors_total]" class="form-control" value="<?=$flat['floors_total']?>"></td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <th width="170px">Общая площадь</th>
                                <td colspan="3"><input type="text" name="flat[area]" class="form-control" value="<?=$flat['area']?>"></td>
                            </tr>
                            <tr>
                                <th width="170px">Код</th>
                                <td colspan="3"><input type="text" name="flat[code]" class="form-control" value="<?=$flat['code']?>"></td>
                            </tr>
                            <tr>
                                <th width="170px">Кол-во кроватей</th>
                                <td colspan="3"><input type="text" name="flat[beds]" class="form-control" value="<?=$flat['beds']?>"></td>
                            </tr>
                            <tr>
                                <th width="170px">Кол-во ванн</th>
                                <td colspan="3"><input type="text" name="flat[bath]" class="form-control" value="<?=$flat['bath']?>"></td>
                            </tr>

                            <?php if($flat['type'] == 2 || $flat['type'] == 3): ?>
                                <tr>
                                    <th width="170px">Площадь участка</th>
                                    <td colspan="3"><input type="text" name="flat[h_area]" class="form-control" value="<?=$flat['h_area']?>"></td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">Характеристики по типу объекта</div>
                    <div class="panel-body">
                        <input type="hidden" name="flat[properties][]" value="">
                        <table class="table table-bordered table-condensed table-striped js_type_character">
                            <tr>
                                <td width="200px"><b>Тип объекта</b></td>
                                <td>
                                    <select name="flat[type]" class="form-control" required>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['type'] == 1 ? 'selected' : '')?>>Квартира</option>
                                        <option value="2" <?=($flat['type'] == 2 ? 'selected' : '')?>>Дом</option>
                                        <option value="3" <?=($flat['type'] == 3 ? 'selected' : '')?>>Участок</option>
                                        <option value="4" <?=($flat['type'] == 4 ? 'selected' : '')?>>Аренда</option>
                                        <option value="5" <?=($flat['type'] == 5 ? 'selected' : '')?>>Бизнес</option>
                                        <option value="6" <?=($flat['type'] == 6 ? 'selected' : '')?>>Зарубежная недвижимость</option>
                                    </select>
                                </td>
                            </tr>

                            <!-- Квартиры -->
                            <tr data-type="1" <?=($flat['type'] == '1' ? '' : 'style="display:none;"')?>>
                                <td><b>Кол-во комнат</b></td>
                                <td>
                                    <select name="flat[rooms]" class="form-control" <?=($flat['type'] == '1' ? '' : 'disabled')?>>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['rooms'] == 1 ? 'selected' : '')?>>1</option>
                                        <option value="2" <?=($flat['rooms'] == 2 ? 'selected' : '')?>>2</option>
                                        <option value="3" <?=($flat['rooms'] == 3 ? 'selected' : '')?>>3</option>
                                        <option value="4" <?=($flat['rooms'] == 4 ? 'selected' : '')?>>4+</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="1" <?=($flat['type'] == '1' ? '' : 'style="display:none;"')?>>
                                <td><b>Тип квартиры</b></td>
                                <td>
                                    <?php if($flat['type'] == 1) { $flat['rooms_type'] = explode(",", $flat['rooms_type']);} ?>
                                    <select name="flat[rooms_type][]" class="form-control" <?=($flat['type'] == '1' ? '' : 'disabled')?> multiple="multiple">
                                        <option value="5" <?=(is_array($flat['rooms_type']) && in_array(5, $flat['rooms_type']) ? 'selected' : '')?>>Премиум-класс</option>
                                        <option value="6" <?=(is_array($flat['rooms_type'])  && in_array(6, $flat['rooms_type']) ? 'selected' : '')?>>Двухуровневые</option>
                                        <option value="7" <?=(is_array($flat['rooms_type'])  && in_array(7, $flat['rooms_type']) ? 'selected' : '')?>>В современном доме</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="1" <?=($flat['type'] == '1' ? '' : 'style="display:none;"')?>>
                                <td><b>Район</b></td>
                                <td>
                                    <select name="flat[district]" class="form-control" <?=($flat['type'] == '1' ? '' : 'disabled')?>>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['district'] == 1 ? 'selected' : '')?>>Центр</option>
                                        <option value="2" <?=($flat['district'] == 2 ? 'selected' : '')?>>Метро Научная</option>
                                        <option value="3" <?=($flat['district'] == 3 ? 'selected' : '')?>>В спальных районах</option>
                                        <option value="4" <?=($flat['district'] == 4 ? 'selected' : '')?>>Современный дом - Павлово Поле</option>
                                        <option value="5" <?=($flat['district'] == 5 ? 'selected' : '')?>>Современный дом - Алексеевка</option>
                                        <option value="6" <?=($flat['district'] == 6 ? 'selected' : '')?>>Современный дом - Холодная гора</option>
                                        <option value="7" <?=($flat['district'] == 7 ? 'selected' : '')?>>Соврменный дом - Новостройки в других районах</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="1" <?=($flat['type'] == '1' ? '' : 'style="display:none;"')?>>
                                <th>Свойства объекта</th>
                                <td>
                                    <select name="flat[properties][]" class="form-control" multiple="">
                                        <?php foreach($properties1 as $p) : ?>
                                            <option value="<?=$p['id']?>" <?=(array_key_exists($p['id'], $flat['properties']) ? 'selected' : '')?>><?=$p['pagetitle']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <!-- Дом -->
                            <tr data-type="2" <?=($flat['type'] == '2' ? '' : 'style="display:none;"')?>>
                                <td><b>Кол-во спален</b></td>
                                <td>
                                    <select name="flat[rooms]" class="form-control" <?=($flat['type'] == '2' ? '' : 'disabled')?>>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['rooms'] == 1 ? 'selected' : '')?>>1</option>
                                        <option value="2" <?=($flat['rooms'] == 2 ? 'selected' : '')?>>2</option>
                                        <option value="3" <?=($flat['rooms'] == 3 ? 'selected' : '')?>>3</option>
                                        <option value="4" <?=($flat['rooms'] == 4 ? 'selected' : '')?>>4+</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="2" <?=($flat['type'] == '2' ? '' : 'style="display:none;"')?>>
                                <td><b>Тип дома </b></td>
                                <td>
                                    <?php if($flat['type'] == 2) { $flat['rooms_type'] = explode(",", $flat['rooms_type']);} ?>
                                    <select name="flat[rooms_type][]" class="form-control" <?=($flat['type'] == '2' ? '' : 'disabled')?> multiple="multiple">
                                        <option value="1" <?=(is_array($flat['rooms_type'])  &&  in_array(1, $flat['rooms_type']) ? 'selected' : '')?>>В городе</option>
                                        <option value="2" <?=(is_array($flat['rooms_type'])  &&  in_array(2, $flat['rooms_type']) ? 'selected' : '')?>>В пригороде</option>
                                        <option value="3" <?=(is_array($flat['rooms_type'])  &&  in_array(3, $flat['rooms_type']) ? 'selected' : '')?>>В коттеджном поселке</option>
                                        <option value="4" <?=(is_array($flat['rooms_type'])  &&  in_array(4, $flat['rooms_type']) ? 'selected' : '')?>>С выходом к воде</option>
                                        <option value="5" <?=(is_array($flat['rooms_type'])  &&  in_array(5, $flat['rooms_type']) ? 'selected' : '')?>>С камином</option>
                                        <option value="6" <?=(is_array($flat['rooms_type'])  &&  in_array(6, $flat['rooms_type']) ? 'selected' : '')?>>С бассейном</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="2" <?=($flat['type'] == '2' ? '' : 'style="display:none;"')?>>
                                <th>Свойства объекта</th>
                                <td>
                                    <select name="flat[properties][]" class="form-control" multiple="">
                                        <?php foreach($properties2 as $p) : ?>
                                            <option value="<?=$p['id']?>" <?=(array_key_exists($p['id'], $flat['properties']) ? 'selected' : '')?>><?=$p['pagetitle']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <!-- Участок -->
                            <tr data-type="3" <?=($flat['type'] == '3' ? '' : 'style="display:none;"')?>>
                                <td><b>Категория</b></td>
                                <td>
                                    <?php if($flat['type'] == 3) { $flat['rooms_type'] = explode(",", $flat['rooms_type']);} ?>
                                    <select name="flat[rooms_type][]" class="form-control" <?=($flat['type'] == '3' ? '' : 'disabled')?> multiple="multiple">
                                        <option value="1" <?=(is_array($flat['rooms_type'])  &&  in_array(1, $flat['rooms_type']) ? 'selected' : '')?>>под жилую застройку в городе</option>
                                        <option value="2" <?=(is_array($flat['rooms_type'])  &&  in_array(2, $flat['rooms_type']) ? 'selected' : '')?>>под жилую застройку в пригороде</option>
                                        <option value="3" <?=(is_array($flat['rooms_type'])  &&  in_array(3, $flat['rooms_type']) ? 'selected' : '')?>>под жилую застройку в коттеджном поселке</option>
                                        <option value="4" <?=(is_array($flat['rooms_type'])  &&  in_array(4, $flat['rooms_type']) ? 'selected' : '')?>>под жилую застройку с выходом к воде</option>
                                        <option value="5" <?=(is_array($flat['rooms_type'])  &&  in_array(5, $flat['rooms_type']) ? 'selected' : '')?>>под коммерческую застройку</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="3" <?=($flat['type'] == '3' ? '' : 'style="display:none;"')?>>
                                <th>Свойства объекта</th>
                                <td>
                                    <select name="flat[properties][]" class="form-control" multiple="">
                                        <?php foreach($properties5 as $p) : ?>
                                            <option value="<?=$p['id']?>" <?=(array_key_exists($p['id'], $flat['properties']) ? 'selected' : '')?>><?=$p['pagetitle']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <!-- Аренда -->
                            <tr data-type="4" <?=($flat['type'] == '4' ? '' : 'style="display:none;"')?>>
                                <td><b>Кол-во комнат</b></td>
                                <td>
                                    <select name="flat[rooms]" class="form-control" <?=($flat['type'] == '4' ? '' : 'disabled')?>>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['rooms'] == 1 ? 'selected' : '')?>>1</option>
                                        <option value="2" <?=($flat['rooms'] == 2 ? 'selected' : '')?>>2</option>
                                        <option value="3" <?=($flat['rooms'] == 3 ? 'selected' : '')?>>3</option>
                                        <option value="4" <?=($flat['rooms'] == 4 ? 'selected' : '')?>>4+</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="4" <?=($flat['type'] == '4' ? '' : 'style="display:none;"')?>>
                                <td><b>Месторасположение</b></td>
                                <td>
                                    <select name="flat[rooms_type]" class="form-control" <?=($flat['type'] == '4' ? '' : 'disabled')?>>
                                        <option value=""> - </option>
                                        <option value="1" <?=($flat['rooms_type'] == 1 ? 'selected' : '')?>>В городе</option>
                                        <option value="2" <?=($flat['rooms_type'] == 2 ? 'selected' : '')?>>В пригороде</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="4" <?=($flat['type'] == '4' ? '' : 'style="display:none;"')?>>
                                <td><b>Тип</b></td>
                                <td>
                                    <select name="flat[district]" class="form-control" <?=($flat['type'] == '4' ? '' : 'disabled')?>>
                                        <option value=""></option>
                                        <option value="1" <?=($flat['district'] == 1 ? 'selected' : '')?>>Жилая недвижимость - Квартира</option>
                                        <option value="2" <?=($flat['district'] == 2 ? 'selected' : '')?>>Жилая недвижимость - Дом</option>
                                        <option value="3" <?=($flat['district'] == 3 ? 'selected' : '')?>>Нежилая недвижимость</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="4" <?=($flat['type'] == '4' ? '' : 'style="display:none;"')?>>
                                <th>Свойства объекта</th>
                                <td>
                                    <select name="flat[properties][]" class="form-control" multiple="">
                                        <?php foreach($properties3 as $p) : ?>
                                            <option value="<?=$p['id']?>" <?=(array_key_exists($p['id'], $flat['properties']) ? 'selected' : '')?>><?=$p['pagetitle']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>

                            <!-- Бизнес -->
                            <tr data-type="5" <?=($flat['type'] == '5' ? '' : 'style="display:none;"')?>>
                                <? $flat['rooms_type'] = explode(",", $flat['rooms_type']);?>
                                <td><b>Параметры</b></td>
                                <td>
                                    <select name="flat[rooms_type][]" class="form-control" <?=($flat['type'] == '5' ? '' : 'disabled')?> multiple="">
                                        <option value=""> - </option>
                                        <option value="1" <?=(in_array(1, $flat['rooms_type']) ? 'selected' : '')?>>Помещения на первых этажах</option>
                                        <option value="2" <?=(in_array(2, $flat['rooms_type']) ? 'selected' : '')?>>Офисы</option>
                                        <option value="3" <?=(in_array(3, $flat['rooms_type']) ? 'selected' : '')?>>Отдельно стоящие здания</option>
                                        <option value="4" <?=(in_array(4, $flat['rooms_type']) ? 'selected' : '')?>>Промышленные объекты</option>
                                        <option value="5" <?=(in_array(5, $flat['rooms_type']) ? 'selected' : '')?>>Продажа готового бизнеса</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="5" <?=($flat['type'] == '5' ? '' : 'style="display:none;"')?>>
                                <?php if ($flat['rooms_type_vhn'] == '') {$flat['rooms_type_vhn'] = 4;} ?>
                                <td><b>Категория коммерческого объекта (для выгрузки vhn.ua)</b></td>
                                <td>
                                    <select name="flat[rooms_type_vhn]" class="form-control" <?=($flat['type'] == '5' ? '' : 'disabled')?> required>
                                        <option value="0"  <?=($flat['rooms_type_vhn'] == 0 ? 'selected': "")?>> - </option>
                                        <option value="1" <?=($flat['rooms_type_vhn'] == 1 ? 'selected': "")?>>ресторан</option>
                                        <option value="2" <?=($flat['rooms_type_vhn'] == 2 ? 'selected': "")?>>гостинница</option>
                                        <option value="3" <?=($flat['rooms_type_vhn'] == 3 ? 'selected': "")?>>бар</option>
                                        <option value="4" <?=($flat['rooms_type_vhn'] == 4 ? 'selected': "")?>>парикмахерская</option>
                                        <option value="5" <?=($flat['rooms_type_vhn'] == 5 ? 'selected': "")?>>дом пристарелых</option>
                                        <option value="6" <?=($flat['rooms_type_vhn'] == 6 ? 'selected': "")?>>спортивный зал</option>
                                        <option value="7" <?=($flat['rooms_type_vhn'] == 7 ? 'selected': "")?>>продуктовый магазин</option>
                                        <option value="8" <?=($flat['rooms_type_vhn'] == 8 ? 'selected': "")?>>киоск</option>
                                        <option value="9" <?=($flat['rooms_type_vhn'] == 9 ? 'selected': "")?>>мастерская</option>
                                        <option value="10" <?=($flat['rooms_type_vhn'] == 10 ? 'selected': "")?>>объект сельскохозяйственного назначения</option>
                                        <option value="11" <?=($flat['rooms_type_vhn'] == 11 ? 'selected': "")?>>помещение промышленного назначения</option>
                                        <option value="12" <?=($flat['rooms_type_vhn'] == 12 ? 'selected': "")?>>больница</option>
                                        <option value="13" <?=($flat['rooms_type_vhn'] == 13 ? 'selected': "")?>>салон красоты</option>
                                        <option value="14" <?=($flat['rooms_type_vhn'] == 14 ? 'selected': "")?>>спортклуб</option>
                                        <option value="15" <?=($flat['rooms_type_vhn'] == 15 ? 'selected': "")?>>закусочная</option>
                                        <option value="16" <?=($flat['rooms_type_vhn'] == 16 ? 'selected': "")?>>павильон</option>
                                        <option value="17" <?=($flat['rooms_type_vhn'] == 17 ? 'selected': "")?>>столовая</option>
                                        <option value="18" <?=($flat['rooms_type_vhn'] == 18 ? 'selected': "")?>>кондитерский цех</option>
                                        <option value="19" <?=($flat['rooms_type_vhn'] == 19 ? 'selected': "")?>>магазин</option>
                                        <option value="20" <?=($flat['rooms_type_vhn'] == 20 ? 'selected': "")?>>склад</option>
                                        <option value="21" <?=($flat['rooms_type_vhn'] == 21 ? 'selected': "")?>>офис</option>
                                    </select>
                                </td>
                            </tr>
                            <tr data-type="5" <?=($flat['type'] == '5' ? '' : 'style="display:none;"')?>>
                                <th>Свойства объекта</th>
                                <td>
                                    <select name="flat[properties][]" class="form-control" multiple="">
                                        <?php foreach($properties4 as $p) : ?>
                                            <option value="<?=$p['id']?>" <?=(array_key_exists($p['id'], $flat['properties']) ? 'selected' : '')?>><?=$p['pagetitle']?></option>
                                        <?php endforeach; ?>
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
                                        <input type="checkbox" class="js_status_appartament" <?=(isset($flat['appartament'][3]) ? 'checked' : "")?> >
                                    </div>
                                </div>
                                <? foreach($appartament as $a) : ?>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label><?=$a['pagetitle']?></label>
                                            <input min="0" type="number" name="flat[appartament][3][<?=$a['id']?>]" class="form-control" placeholder="<?=$a['pagetitle']?>" value="<?=(isset($flat['appartament'][3][$a['id']]) ? $flat['appartament'][3][$a['id']] : '')?>" <?=(!isset($flat['appartament'][3]) ? 'disabled' : "")?>>
                                        </div>
                                    </div>
                                <? endforeach; ?>
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
                                        <input type="checkbox" class="js_status_appartament" <?=(isset($flat['appartament'][2]) ? 'checked' : "")?> >
                                    </div>
                                </div>
                                <? foreach($appartament as $a) : ?>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label><?=$a['pagetitle']?></label>
                                            <input min="0" type="number" name="flat[appartament][2][<?=$a['id']?>]" class="form-control" placeholder="<?=$a['pagetitle']?>" value="<?=(isset($flat['appartament'][2][$a['id']]) ? $flat['appartament'][2][$a['id']] : '')?>" <?=(!isset($flat['appartament'][2]) ? 'disabled' : "")?>>
                                        </div>
                                    </div>
                                <? endforeach; ?>
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
                                        <input type="checkbox" class="js_status_appartament" <?=(isset($flat['appartament'][1]) ? 'checked' : "")?> >
                                    </div>
                                </div>
                                <? foreach($appartament as $a) : ?>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label><?=$a['pagetitle']?></label>
                                            <input min="0" type="number" name="flat[appartament][1][<?=$a['id']?>]" class="form-control" placeholder="<?=$a['pagetitle']?>" value="<?=(isset($flat['appartament'][1][$a['id']]) ? $flat['appartament'][1][$a['id']] : '')?>" <?=(!isset($flat['appartament'][1]) ? 'disabled' : "")?>>
                                        </div>
                                    </div>
                                <? endforeach; ?>
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
                                        <input type="checkbox" class="js_status_appartament" <?=(isset($flat['appartament'][0]) ? 'checked' : "")?> >
                                    </div>
                                </div>
                                <? foreach($appartament as $a) : ?>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <label><?=$a['pagetitle']?></label>
                                            <input min="0" type="number" name="flat[appartament][0][<?=$a['id']?>]" class="form-control" placeholder="<?=$a['pagetitle']?>" value="<?=(isset($flat['appartament'][0][$a['id']]) ? $flat['appartament'][0][$a['id']] : '')?>" <?=(!isset($flat['appartament'][0]) ? 'disabled' : "")?>>
                                        </div>
                                    </div>
                                <? endforeach; ?>
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
                        <td><input type="text" name="flat[owner_name]" class="form-control" value="<?=$flat['owner_name']?>"></td>
                    </tr>
                    <tr>
                        <td><b>Телефон владельца</b></td>
                        <td><input type="text" name="flat[owner_phone]" class="form-control" value="<?=$flat['owner_phone']?>"></td>
                    </tr>
                    <tr>
                        <td><b>Комментарии</b></td>
                        <td><textarea name="flat[owner_comment]" cols="30" rows="10" class="form-control"><?=$flat['owner_comment']?></textarea></td>
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
                    <? if(count($files) > 0) :
                        foreach ($files as $f) :
                            $f = explode("/", $f);
                            $f = end($f); ?>
                            <li>
                                <a href="<?=$modx->config['site_url']?>assets/files/flat/<?=$flat['id']?>/<?=$f?>" target="_blank"><?=$f?></a>
                                <a class="btn btn-primary btn-xs" data-file-delete="<?=$f?>"><span class="glyphicon glyphicon-trash"></span></a>
                            </li>
                        <? endforeach;
                    endif; ?>
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
                        <a href="<?=$modx->makeUrl(134).$flat['url']."/"?>" class="btn btn-info btn-lg" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> Просмотр</a>
                        <?php if($_SESSION['mgrRole'] != 2 ) { ?>
                            <a href="<?=$url?>b=flat&c=delete&i=<?=$flat['id']?>" class="btn btn-danger btn-lg pull-right" data-confirm="Точно удалить ?"><span class="glyphicon glyphicon-remove"></span> Удалить</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// =========================
//  Общие мелкие хелперы
// =========================

// Копирование значения из левого поля (UA) в правое (RU) по клику на стрелку
$(document).on('click', '.js-copy-right', function (e) {
  e.preventDefault();
  var $tr    = $(this).closest('tr');
  var $cells = $tr.children('td');
  var $src = $cells.eq(0).find('input, textarea, select').first();
  var $dst = $cells.eq($cells.length - 1).find('input, textarea, select').first();
  if ($src.length && $dst.length) {
    $dst.val($src.val()).trigger('input').trigger('change');
  }
});

// Ограничение рейтинга 0..5
$(document).on("input", "[name='flat[rating]']", function() {
  var v = parseFloat($(this).val());
  if (v > 5) $(this).val(5);
  else if (v < 0) $(this).val(0);
});

// Подтверждение удаления сущностей (кнопки с data-confirm)
$(document).on("click", "[data-confirm]", function() {
  var _c = $(this).attr('data-confirm');
  if(!confirm(_c)) return false;
});

// =========================
//  Галерея: получение/рендер
// =========================

// Глобальная функция (используется после аплоада/удаления)
window.updateImages = function(){
  return $.ajax({
    url:"index.php",
    type:"GET",
    data:{
      a:112, id:7, b:'flat', c:'getImages',
      folder: $("#uploader").data("folder") || "tmp"
    },
    success:function(ajax){
      $("#upload-overlay").hide();
      $(".images").html(ajax);
      $(".images img[alt='"+$("#cover").val()+"']").addClass("cover");
      try { $(".images").sortable(); $(".images").disableSelection(); } catch(e){}
      try { window.updateCounterUI(); } catch(e){}
    }
  });
};

// Выбор обложки кликом по миниатюре или кнопке
$(document).on("click", ".images .btn-primary, .images img", function(e) {
  e.preventDefault();
  var _img = $(this).parents("li").find("img");
  $("#cover").val(_img.attr('alt'));
  $(".images .cover").removeClass("cover");
  _img.addClass("cover");
});

// Удаление фото
$(document).on("click", ".images .btn-danger", function() {
  if (!confirm("Удалить ?")) return false;
  var $img = $(this).prevAll("img");
  if ($img.hasClass("cover")) {
    $("#cover").val('');
    $img.removeClass("cover");
  }
  $.ajax({
    url:"index.php",
    type:"GET",
    data:{
      a:112, id:7, b:'flat', c:'delete_image',
      folder: $("#uploader").data("folder") || "tmp",
      delete:$img.attr("alt")
    },
    success:function(){
      $img.parents("li").fadeOut(150, function(){
        $(this).remove();
        try { window.updateCounterUI(); } catch(e){}
      });
    }
  });
});

// Переключение типа изображения (оставлен один обработчик)
$(document).on("click", "[data-type]", function() {
  $.ajax({
    url:"index.php",
    type:"GET",
    data:{
      a:112, id:7, b:'flat', c:'chageTypeImage',
      i: $("#uploader").data("folder") || "0",
      t: $(this).attr("data-type"),
      image: $(this).attr("data-image")
    },
    success:function(){ updateImages(); }
  });
});

// =========================
//  Plectan: автоподстановка района
// =========================
$(document).on('change', '#plectan_city', function () {
  var elt   = this;
  var label = $(elt.options[elt.selectedIndex]).closest('optgroup').prop('label');
  $("#plectan_district").val(label);
  if ($(this).val() === 'Харків' ) $('#plectan_area').show(); else $('#plectan_area').hide();
});

// =========================
//  FileManager (панорама/файлы)
// =========================
var lastImageCtrl;
var lastFileCtrl;

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
  var w = screen.width * 0.5;
  var h = screen.height * 0.5;
  OpenServerBrowser('/manager/media/browser/mcpuk/browser.php?Type=images', w, h);
}
function BrowseFileServer(ctrl) {
  lastFileCtrl = ctrl;
  var w = screen.width * 0.5;
  var h = screen.height * 0.5;
  OpenServerBrowser('/manager/media/browser/mcpuk/browser.php?Type=files', w, h);
}
function SetUrlChange(el) {
  if ('createEvent' in document) {
    var evt = document.createEvent('HTMLEvents');
    evt.initEvent('change', false, true);
    el.dispatchEvent(evt);
  } else {
    el.fireEvent('onchange');
  }
  if (el.value) {
    $('#flat_panorama_image_thumb').find('img').attr('src','/'+el.value).show();
  } else {
    $('#flat_panorama_image_thumb').find('img').hide();
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
  } else {
    return;
  }
}

// =========================
//  Галерея: аплоад с лимитами
//  (Лимиты касаются ТОЛЬКО фото)
// =========================
(function (window, document, $) {
  if (!$) return;

  var $btn     = $("#uploader");
  var $input   = $("#uploader-input");
  var $overlay = $("#upload-overlay");
  var $spinTxt = $(".js-up-count");
  var $spinOn  = $(".js-up-spin");
  var $spinOff = $(".js-up-text");

  if (!$btn.length || !$input.length) return;

  var ENDPOINT    = $btn.data("endpoint")    || "index.php?a=112&id=7&b=flat&c=upload";
  var FOLDER      = $btn.data("folder")      || "tmp";
  var SIZE_LIM    = parseInt($btn.data("size"), 10) || 3048576; // байт
  var CONCURRENCY = parseInt($btn.data("concurrency"), 10) || 2;
  var RETRIES     = parseInt($btn.data("retries"), 10) || 2;
  var ALLOWED_RE  = /\.(jpg|jpeg|png|gif|webp|bmp)$/i;
  var MAX_FILES   = 100;

  // Общий лимит фотографий в галерее берём из data-total на кнопке.
  // Если data-total нет — лимит бесконечен.
  var TOTAL_LIMIT = (function(v){ v=parseInt($btn.data("total"),10); return isNaN(v) ? Infinity : v; })();

  // ---- Счётчик фото (только для галереи) ----
  window.updateCounterUI = function(){
    var cnt = $(".images li").length || 0;
    $(".js-img-count").text(cnt);
    if (isFinite(TOTAL_LIMIT)) {
      $(".js-img-total").text(" / " + TOTAL_LIMIT);
    } else {
      $(".js-img-total").text("");
    }
  };

  function getExistingCount(){
    return $(".images li").length || 0;
  }

  // Нормализуем и обрезаем список файлов по:
  // 1) разрешённым расширениям
  // 2) мягкому лимиту MAX_FILES за один выбор
  // 3) общему лимиту TOTAL_LIMIT (учитывая уже загруженные)
  // 4) лимиту размера файла SIZE_LIM (если указан)
  function normalizeAndCapFileList(listLike) {
    var arr = listLike ? Array.prototype.slice.call(listLike) : [];

    // по расширениям
    arr = arr.filter(function (f) { return ALLOWED_RE.test((f && f.name) ? f.name : ""); });

    // мягкий лимит за один выбор
    if (arr.length > MAX_FILES) {
      try { alert("Вы выбрали " + arr.length + " файлов. Будут загружены только первые " + MAX_FILES + "."); } catch(e){}
      arr = arr.slice(0, MAX_FILES);
    }

    // общий лимит для галереи
    if (isFinite(TOTAL_LIMIT)) {
      var existing  = getExistingCount();
      var remaining = Math.max(0, TOTAL_LIMIT - existing);
      if (remaining <= 0) {
        alert("Достигнут общий лимит изображений (" + TOTAL_LIMIT + "). Удалите лишние, чтобы загрузить новые.");
        return [];
      }
      if (arr.length > remaining) {
        alert("Можно добавить не более " + remaining + " фото (общий лимит " + TOTAL_LIMIT + ").");
        arr = arr.slice(0, remaining);
      }
    }

    // по размеру файла
    arr = arr.filter(function (f) { return (typeof f.size === "number" ? f.size <= SIZE_LIM : true); });

    return arr;
  }

  // Клик по кнопке — открываем системный диалог выбора
  $btn.on("click", function(e){
    e.preventDefault();
    if ($btn.prop("disabled")) return;
    this.blur();
    if ($input[0] && $input[0].click) $input[0].click();
  });

  // Выбор файлов
  $input.on("change", function(){
    var files = normalizeAndCapFileList(this.files);
    if (!files.length) return;
    setUploadingUI(true);
    runQueue(files, CONCURRENCY, RETRIES, function(){
      setUploadingUI(false);
      $input.val("");
      try { updateImages(); } catch(e){}
      try { window.updateCounterUI(); } catch(e){}
    });
  });

  // Поддержка drag&drop на кнопке и глушение событий на документе
  enableDropZone($btn);

  // Очередь загрузок с параллельностью и ретраями
  function runQueue(files, concurrency, retries, doneCb) {
    var total = files.length, inFlight = 0, finished = 0, okCount=0, failCount=0, index = 0;
    tick(); pump();

    function pump() {
      while (inFlight < concurrency && index < total) {
        (function (file, attemptLeft) {
          inFlight++; tick();
          uploadOne(file, attemptLeft, function (ok) {
            inFlight--; finished++; ok ? okCount++ : failCount++; tick();
            if (finished === total) {
              if (failCount) try { alert("Загружено: " + okCount + ", ошибок: " + failCount); } catch(e){}
              return doneCb();
            }
            pump();
          });
        })(files[index++], retries);
      }
    }
    function tick(){ $spinTxt.text(finished + " / " + total + (inFlight ? " " : "")); }
  }

  // Загрузка одного файла
  function uploadOne(file, retriesLeft, cb) {
    var fd = new FormData();
    fd.append("uploader[]", file, file.name || "file");
    fd.append("size",  SIZE_LIM);
    fd.append("folder", FOLDER);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", ENDPOINT, true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      var ok = false;
      try {
        var r = JSON.parse(xhr.responseText || "{}");
        ok = (xhr.status >= 200 && xhr.status < 300 && r && r.ok === true);
      } catch(e) { ok = false; }

      if (ok) return cb(true);

      if (retriesLeft > 0) {
        backoff(function(){ uploadOne(file, retriesLeft - 1, cb); });
      } else {
        try { console.warn("Upload failed:", file && file.name, xhr.status, xhr.responseText); } catch (e) {}
        cb(false);
      }
    };

    xhr.onerror = function(){
      if (retriesLeft > 0) {
        backoff(function(){ uploadOne(file, retriesLeft - 1, cb); });
      } else {
        try { console.warn("Network error:", file && file.name); } catch(e){}
        cb(false);
      }
    };

    xhr.send(fd);
  }

  // Экспоненциальный бэкофф
  function backoff(fn){
    var base = 200 + Math.floor(Math.random() * 200);
    setTimeout(fn, base * (1 + Math.floor(Math.random() * 3)));
  }

  // Переключение UI загрузки
  function setUploadingUI(isOn) {
    if (isOn) {
      $("#upload-overlay").show();
      $spinOn.show();
      $spinOff.hide();
      $btn.prop("disabled", true).attr('aria-busy','true');
    } else {
      $("#upload-overlay").hide();
      $spinOn.hide();
      $spinOff.show().text("Загрузить");
      $btn.prop("disabled", false).removeAttr('aria-busy');
    }
  }

  // DnD
  function enableDropZone($zone) {
    var zone = $zone && $zone[0] ? $zone[0] : document.body;
    function prevent(e){ e.preventDefault(); e.stopPropagation(); }
    function addHover(){ $zone.addClass("is-dragover"); }
    function rmHover(){ $zone.removeClass("is-dragover"); }

    zone.addEventListener("dragenter", function(e){ prevent(e); addHover(); }, false);
    zone.addEventListener("dragover",  function(e){ prevent(e); addHover(); }, false);
    zone.addEventListener("dragleave", function(e){ prevent(e); rmHover();  }, false);
    zone.addEventListener("drop",      function(e){
      prevent(e); rmHover();
      if ($btn.prop("disabled")) return;
      var dt = e.dataTransfer;
      if (!dt || !dt.files || !dt.files.length) return;
      var files = normalizeAndCapFileList(dt.files);
      if (!files.length) return;
      setUploadingUI(true);
      runQueue(files, CONCURRENCY, RETRIES, function(){
        setUploadingUI(false);
        try { updateImages(); } catch(err){}
        try { window.updateCounterUI(); } catch(err){}
      });
    }, false);

    ["dragenter","dragover","dragleave","drop"].forEach(function(ev){
      document.addEventListener(ev, function(e){ e.preventDefault(); }, false);
    });
  }

  // Первичный подсчёт уже отрендеренных фото
  try { window.updateCounterUI(); } catch(e){}
})(window, document, window.jQuery);

// =========================
//  Старт: подгружаем текущие фото и вешаем прочее
// =========================
$(document).ready(function(){
  try { updateImages(); } catch(e){}
});
</script>
<style>
    #price-history-table tbody tr.ph-row.ph-dragging { opacity: .85; }
    #price-history-table tbody tr.ph-row.placeholder {
      height: 44px;
      background: #f7f7f7;
      border: 1px dashed #ccc;
    }
  </style>

  <script>
    (function($){

      function renumberPriceHistory(){
        $('#price-history-table tbody tr.ph-row').each(function(idx){
          $(this).attr('data-index', idx);

          $(this).find('.ph-price')
            .attr('name', 'flatPrice[price_history]['+idx+'][price]');

          $(this).find('.ph-date')
            .attr('name', 'flatPrice[price_history]['+idx+'][start_date]');

          // пересобираем кнопки очистить/удалить
          var $actions = $(this).find('td:last');
          $actions.find('.ph-clear, .ph-delete').remove();

          if (idx === 0) {
            $actions.append(' <button type="button" class="btn btn-warning btn-sm ph-clear">Очистить</button>');
          } else {
            $actions.append(' <button type="button" class="btn btn-danger btn-sm ph-delete">Удалить</button>');
          }
        });
      }

      function initSortable(){
        if (!$.fn.sortable) return;

        $('#price-history-table tbody').sortable({
          handle: '.ph-handle',
          items:  'tr.ph-row',
          axis:   'y',
          tolerance: 'pointer',
          helper: function(e, tr){
            var $orig = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index){
              $(this).width($orig.eq(index).width());
            });
            return $helper;
          },
          placeholder: 'placeholder',
          start: function(e, ui){ ui.item.addClass('ph-dragging'); },
          stop:  function(e, ui){ ui.item.removeClass('ph-dragging'); },
          update: function(){
            renumberPriceHistory();
          }
        });
      }

      $(document).ready(function(){

        $('#ph-add-row').on('click', function(){
          var $last = $('#price-history-table tbody tr.ph-row:last');
          var $new  = $last.clone();

                                              $new.find('.ph-price').val('');
                                              $new.find('.ph-date').val('');

                                              $('#price-history-table tbody').append($new);
                                              renumberPriceHistory();
                                            });

                                            $(document).on('click', '.ph-clone', function(){
                                              var $row = $(this).closest('tr.ph-row');
                                              var $new = $row.clone();
                                              $row.after($new);
                                              renumberPriceHistory();
                                            });

                                            $(document).on('click', '.ph-delete', function(){
                                              $(this).closest('tr.ph-row').remove();
                                              renumberPriceHistory();
                                            });

                                            $(document).on('click', '.ph-clear', function(){
                                              var $row = $(this).closest('tr.ph-row');
                                              $row.find('.ph-price').val('');
                                              $row.find('.ph-date').val('');
                                            });

                                            renumberPriceHistory();
                                            initSortable();
                                          });

                                        })(jQuery);
                                      </script>