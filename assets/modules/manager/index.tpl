<? if ($flash['success'] != ''): ?>
<div class="alert alert-success alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <?=$flash['success']?>
</div>
<? endif ?>

<form action="index.php" method="get" id="search_form" style="display: block;">

    <?php foreach ($_GET as $key => $value) : ?>
        <?php if($key == 'page'){
        $value = 1;
        }?>


    <?php if(!empty($value)){?>  
        <input type="hidden" name="<?=$key?>" value="<?=$value?>">
     <?php } ?>
    
    <?
    if($value != 'setXmlActive' && $key != 'act')
      $url_xml .= '&'.$key.'='.$value;
    ?>
    <?php endforeach ?>

    <h3>Поиск</h3>
    <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">
            <h4>Объект</h4>
            <input type="text" name="flat" placeholder="Название объекта" class="form-control" <?=(isset($_GET['flat']) ? 'value="'.$_GET['flat'].'"' : '')?>>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4><?=M_TYPE?></h4>
            <select class="form-control" name="type" data-live-search="true">
                <option value=""> -</option>
                <option value="1"
                <?=($_GET['type'] == 'Квартира' ? "selected" : "")?>>Квартира</option>
                <option value="2"
                <?=($_GET['type'] == 'Квартира' ? "selected" : "")?>>Дом</option>
                <option value="3"
                <?=($_GET['type'] == 'Участок' ? "selected" : "")?>>Участок</option>
                <option value="4"
                <?=($_GET['type'] == 'Аренда' ? "selected" : "")?>>Аренда</option>
                <option value="5"
                <?=($_GET['type'] == 'Бизнес' ? "selected" : "")?>>Бизнес</option>
                <option value="6"
                <?=($_GET['type'] == 'Зарубежная недвижимость' ? "selected" : "")?>>Зарубежная недвижимость</option>
            </select>
            <!-- <select class="form-control" name="type" id="one_dept"  data-live-search="true">
                <option value=""> -</option>
                <?php foreach ($categories_one_dept as $key => $value) { ?>
                <option value="<?=$value['id']; ?>"
                > <?php echo $value['name']; ?></option>
                <?php } ?>
                
            </select> -->
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4>Код</h4>
            <select class="form-control" name="type" id="two_dept"  data-live-search="true">
                <option value=""> -</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4>Статус</h4>
            <select class="form-control" name="status" data-live-search="true">
                <option value=""> -</option>
                <option value="1"
                <?=($_GET['status'] == '1' ? "selected" : "")?>>Продажа</option>
                <option value="2"
                <?=($_GET['status'] == '2' ? "selected" : "")?>>Аренда</option>
                <option value="3"
                <?=($_GET['status'] == '3' ? "selected" : "")?>>Снят с продажи</option>
                <option value="4"
                <?=($_GET['status'] == '4' ? "selected" : "")?>>Продан</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4>Продацев</h4>
            <input type="text" name="owner" placeholder="Продацев"
                   class="form-control" <?=(isset($_GET['owner']) ? 'value="'.$_GET['owner'].'"' : '')?>>
        </div>
    </div>


	
	<div class="row">
        <div class="col-md-3 col-sm-3 col-xs-3">

        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4>Категория недвижимости первого уровня</h4>
            <select class="form-control" name="type" id="two_dept"  data-live-search="true">
                <option value=""> -</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2">
            <h4>Категория недвижимости второго уровня</h4>
            <select class="form-control" name="type" id = "tree_dept"data-live-search="true">
                <option value=""> -</option>
                <?php foreach ($categories_tree_dept as $key => $value) { ?>
                <option value="<?=$value['id']; ?>"
                > <?php echo $value['name']; ?></option>
                <?php } ?>
                
            </select>
        </div>
        
    </div>

    <div class="row">

        <div class="col-md-12">
            <p>
                <br>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>
                    <b>Поиск</b></button>
            </p>
        </div>
    </div>
</form>
<p>
    <a href="<?=$url?>b=flat&c=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>
        Добавление</a>
    <!--       <a style="float:right;" href="<?=$url?>b=flat&c=save_xml&type=057" class="btn download-xml btn-success"><span class="glyphicon glyphicon-download-alt"></span> Выгрузка в XML (057)</a>
          <a style="float:right;" href="<?=$url?>b=flat&c=save_xml&type=ci.ua" class="btn download-xml btn-success"><span class="glyphicon glyphicon-download-alt"></span> Выгрузка в XML (ci.ua)</a> -->
    <a style="float:right;" href="<?=$url?>b=flat&c=save_xml&type=all" class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Выгрузка в XML</a>
    <a style="float:right;" href="<?=$url?>b=flat&c=save_xml&type=vhn" class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Выгрузка для vhn.ua</a>
    <a style="float:right;" href="<?=$url?>b=flat&c=save_xml&type=lun" class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Выгрузка в XML для lun</a>
    <a style="float:right;"  id ="edit-and-print" href="<?=$url?>b=flat&c=getPrint   class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Редактирование и печать</a>
    <a style="float:right; display: none;" href="<?=$url?>b=flat&c=save_xml&type=exel" id ="edit" class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Сохранить в файл</a>
    <a style="float:right; display: none;" href="<?=$url?>b=flat&c=save_xml&type=exel" onclick="print();" id ="print" class="btn download-xml btn-success"><span
                class="glyphicon glyphicon-download-alt"></span> Печать</a>
</p>
<table class="table table-condensed table-bordered table-striped">
    <thead>
    <tr>
        <th width="35px">ID</th>
        <th width="200px">Название</th>
        <th>Адрес</th>
        <th width="150px">Тип</th>
        <th width="150px">Цена</th>
        <th width="150px">Код</th>
       
        
        <th width="150px" class="js_hide">
            VHN<br>
            <a class="update-switch-vhn enable" href="index.php?<?=$url_xml?>&c=setXmlActiveVhn&act=on_all"
               data-action="on_all">Вкл. все</a><br>
            <a class="update-switch-vhn disable" href="index.php?<?=$url_xml?>&c=setXmlActiveVhn&act=off_all"
               data-action="off_all">Выкл. все</a><br>
            <a class="update-switch-vhn enable" href="index.php?<?=$url_xml?>&c=setXmlActiveVhn&act=on_page"
               data-action="on_page">Вкл. на странице</a><br>
            <a class="update-switch-vhn disable" href="index.php?<?=$url_xml?>&c=setXmlActiveVhn&act=off_page"
               data-action="off_page">Выкл. на странице</a><br>
        </th>
        <th width="150px" class="js_hide">
            XML<br>
            <a class="update-switch enable" href="index.php?<?=$url_xml?>&c=setXmlActive&act=on_all"
               data-action="on_all">Вкл. все</a><br>
            <a class="update-switch disable" href="index.php?<?=$url_xml?>&c=setXmlActive&act=off_all"
               data-action="off_all">Выкл. все</a><br>
            <a class="update-switch enable" href="index.php?<?=$url_xml?>&c=setXmlActive&act=on_page"
               data-action="on_page">Вкл. на странице</a><br>
            <a class="update-switch disable" href="index.php?<?=$url_xml?>&c=setXmlActive&act=off_page"
               data-action="off_page">Выкл. на странице</a><br>
        </th>
        
        <th width="150px" class="js_hide">
            LUN<br>
            <a class="update-switch-lun enable" href="index.php?<?=$url_xml?>&c=setXmlActiveLun&act=on_all"
               data-action="on_all">Вкл. все</a><br>
            <a class="update-switch-lun disable" href="index.php?<?=$url_xml?>&c=setXmlActiveLun&act=off_all"
               data-action="off_all">Выкл. все</a><br>
            <a class="update-switch-lun enable" href="index.php?<?=$url_xml?>&c=setXmlActiveLun&act=on_page"
               data-action="on_page">Вкл. на странице</a><br>
            <a class="update-switch-lun disable" href="index.php?<?=$url_xml?>&c=setXmlActiveLun&act=off_page"
               data-action="off_page">Выкл. на странице</a><br>
        </th>
        <th width="150px" class="js_hide">
            Распечатать<br>
            <a class="update-switch-print enable" href="index.php?<?=$url_xml?>&c=setExelActive&act=on_all"
               data-action="on_all">Вкл. все</a><br>
            <a class="update-switch-print disable" href="index.php?<?=$url_xml?>&c=setExelActive&act=off_all"
               data-action="off_all">Выкл. все</a><br>
            <a class="update-switch-print enable" href="index.php?<?=$url_xml?>&c=setExelActive&act=on_page"
               data-action="on_page">Вкл. на странице</a><br>
            <a class="update-switch-print disable" href="index.php?<?=$url_xml?>&c=setExelActive&act=off_page"
               data-action="off_page">Выкл. на странице</a><br>
        </th>
        <th class="js_show_second">Описание
        </th>
        <th class="js_show_second">Информация о владельце
        </th>

        <th width="150px">
            актуализация<br>
            <a href="#" class="btn btn-sm btn-success js_actualization"><span class="glyphicon glyphicon-eye-close">Актуализировать</span></a><br>
            <a class="update-status enable" href="index.php?<?=$url_xml?>&c=setActualization&act=on_all"
               data-action="on_all">АКТУАЛИЗИРОВАТЬ ВЕСЬ САЙТ</a><br>
            <a class="update-status-onpage enable" href="index.php?<?=$url_xml?>&c=setActualization&act=on_page"
               data-action="on_page">Вкл. на странице</a><br>
            <!--<a class="update-status disable" href="index.php?<?=$url_xml?>&c=setActualization&act=off_all" data-action="off_all">Выкл. все</a><br>
            <a class="update-status disable" href="index.php?<?=$url_xml?>&c=setActualization&act=off_page" data-action="off_page">Выкл. на странице</a><br> -->
        </th>

    </tr>
    </thead>
    <tbody>
    <?php $flats_ids = array(); ?>
    <?php foreach ($flats as $flat) : ?>

    <?php if($flats['xml_active'] == 0): ?>
    <?php array_push($flats_ids, $flat['id']); ?>
    <?php endif; ?>

    <tr class="<?=($flat['active'] == 1 ? " success
    " : "danger")?>">
    <td><?=$flat['id']?></td>
    <td><?=$flat['name']?></td>
    <td><?=$flat['addr']?></td>
    <td>
        <?
           switch($flat['type']){
                case 1: echo 'Квартира';break;
                case 2: echo 'Дом';break;
                case 3: echo 'Участок';break;
                case 4: echo 'Аренда';break;
                case 5: echo 'Бизнес';break;
                case 6: echo 'Зарубежная недвижемость';break;
           }
        ?>
    </td>
    <td>$ <?=$flat['price']?></td>
    <td><?=$flat['code']?></td>
    <td class="js_hide">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?> class="js_flat_xml_vhn" type="checkbox" name="flat[xml_active_vhn]"
                   data-flat-vhn="<?=$flat['id']?>" <?=($flat['xml_active_vhn'] == 1 ? "checked" : "")?>  value="1">
        </div>
    </td >
    <td class="js_hide">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?>  class="js_flat_xml" type="checkbox" name="flat[xml_active]"
                   data-flat="<?=$flat['id']?>" <?=($flat['xml_active'] == 1 ? "checked" : "")?>  value="1">
        </div>
    </td>
    <td class="js_hide">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?>  class="js_flat_xml_lun" type="checkbox" name="flat[xml_active_lun]"
                   data-flat-lun="<?=$flat['id']?>" <?=($flat['xml_active_lun'] == 1 ? "checked" : "")?>  value="1">
        </div>
    </td>
    <td class="js_hide">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?>  class="js_flat_exel" type="checkbox" name="flat[exel_active]"
                   data-flat-exel="<?=$flat['id']?>" <?=($flat['exel_active'] == 1 ? "checked" : "")?>  value="1">
        </div>
    </td>
    <!-- <td class="js_hide"headers="">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?>  class="js_status" type="checkbox"
                   name="flat[active]" <?= (date('Y-m-d', strtotime($flat['updated_at'])) == date('Y-m-d') ? "checked" : "") ?>
            data-status="<?=$flat['id']?>" value="1">
        </div>
        <input type="text" class="js_flat_status_input" disabled
               value="<?= date('Y-m-d', strtotime($flat['updated_at'])) ?>">
    </td> -->
    <td class="js_show_second">
        <p>Полная информация </p>

    </td >
     <td class="js_show_second">
        <p>Полная информация о владельце</p>
    </td >
    <td > 
        <a href="<?=$url?>b=flat&c=edit&i=<?=$flat['id']?>" class="btn btn-sm btn-primary"><span
                    class="glyphicon glyphicon-pencil"></span></a>
        <a href="<?=$modx->makeUrl(134);?><?=$flat['url']?>/" target="_blank" class="btn btn-sm btn-info"><span
                    class="glyphicon glyphicon-eye-open"></span></a>

        <? if($flat['active'] == 1) { ?>
        <a href="<?=$url?>b=flat&c=ban&i=<?=$flat['id']?>" class="btn btn-sm btn-danger"><span
                    class="glyphicon glyphicon-eye-close"></span></a>
        <? } else { ?>
        <a href="<?=$url?>b=flat&c=unban&i=<?=$flat['id']?>" class="btn btn-sm btn-success"><span
                    class="glyphicon glyphicon-eye-open"></span></a>
        <? } ?>
    </td>
    </tr>
    <? endforeach;?>
    </tbody>
</table>
<nav>
    <ul class="pagination">
        <?=$pagin?>
    </ul>
</nav>

<script type="text/javascript">
    $('[data-flat]').on('change', function (e) {
        id = $(this).attr('data-flat');
        $.ajax({
            url: '<?=$url?>b=flat&c=setXmlActive&act=one&i=' + id + '&xml_active=' + (e.target.checked ? 1 : 0),
            success: function () {
                location.reload();
            }
        });
    });

    $('[data-flat-lun]').on('change', function (e) {
        id = $(this).attr('data-flat-lun');
        $.ajax({
            url: '<?=$url?>b=flat&c=setXmlActiveLun&act=one&i=' + id + '&xml_active_lun=' + (e.target.checked ? 1 : 0),
            success: function () {
                location.reload();
            }
        });
    });

    $('[data-flat-vhn]').on('change', function (e) {
        id = $(this).attr('data-flat-vhn');
        console.log("df");
        $.ajax({
            url: '<?=$url?>b=flat&c=setXmlActiveVhn&act=one&i=' + id + '&xml_active_vhn=' + (e.target.checked ? 1 : 0),
            success: function () {
                location.reload();
            }
        });
    });
    $('[data-flat-exel]').on('change', function (e) {
        id = $(this).attr('data-flat-exel');
        $.ajax({
            url: '<?=$url?>b=flat&c=setExelActive&act=one&i=' + id + '&exel_active=' + (e.target.checked ? 1 : 0),
            success: function () {
                location.reload();
            }
        });
    });

    $('.js_actualization').on('click', function () {
        var checked = $('.js_status:checked'),
            ids = [];
        $(checked).each(function (i, el) {
            ids.push($(el).data('status'));
        })
        if (ids.length > 0)
            $.ajax({
                url: '<?=$url?>b=flat&c=setActualization&act=several&flats_ids=' + ids.join(','),
                success: function () {
                    location.reload();
                }
            });
        return false;
    });

    $('.js_flat_status').on('click', function (e) {
        var _this = $(this);
        id = $(_this).attr('data-status');
        e.preventDefault();
        $.ajax({
            url: '<?=$url?>b=flat&c=setActualization&act=one&i=' + id,
            success: function (result) {
                $(_this).closest('td').find('.js_flat_status_input').val(result);
            }
        });
    });

    $('a.update-switch').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('enable'))
            $('.js_flat_xml').closest('.switch-animate').removeClass('switch-off').addClass('switch-on');
        else
            $('.js_flat_xml').closest('.switch-animate').removeClass('switch-on').addClass('switch-off');
        // var flats_ids = '<?= json_encode($flats_ids) ?>';
        $.ajax({
            url: 'index.php?<?=$url_xml?>&c=setXmlActive&act=' + $(this).attr('data-action') + '&flats_ids=<?= json_encode($flats_ids) ?>',
            success: function (result) {
                location.reload();
            }
        });
    });

    $('a.update-switch-lun').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('enable'))
            $('.js_flat_xml_lun').closest('.switch-animate').removeClass('switch-off').addClass('switch-on');
        else
            $('.js_flat_xml_lun').closest('.switch-animate').removeClass('switch-on').addClass('switch-off');
        // var flats_ids = '<?= json_encode($flats_ids) ?>';
        $.ajax({
            url: 'index.php?<?=$url_xml?>&c=setXmlActiveLun&act=' + $(this).attr('data-action') + '&flats_ids=<?= json_encode($flats_ids) ?>',
            success: function (result) {
                location.reload();
            }
        });
    });

    $('a.update-switch-vhn').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('enable'))
            $('.js_flat_xml_vhn').closest('.switch-animate').removeClass('switch-off').addClass('switch-on');
        else
            $('.js_flat_xml_vhn').closest('.switch-animate').removeClass('switch-on').addClass('switch-off');
        // var flats_ids = '<?= json_encode($flats_ids) ?>';
        $.ajax({
            url: 'index.php?<?=$url_xml?>&c=setXmlActiveVhn&act=' + $(this).attr('data-action') + '&flats_ids=<?= json_encode($flats_ids) ?>',
            success: function (result) {
                location.reload();
            }
        });
    });
    $('a.update-switch-print').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('enable'))
            $('.js_flat_exel').closest('.switch-animate').removeClass('switch-off').addClass('switch-on');
        else
            $('.js_flat_exel').closest('.switch-animate').removeClass('switch-on').addClass('switch-off');
        // var flats_ids = '<?= json_encode($flats_ids) ?>';
        $.ajax({
            url: 'index.php?<?=$url_xml?>&c=setExelActive&act=' + $(this).attr('data-action') + '&flats_ids=<?= json_encode($flats_ids) ?>',
            success: function (result) {
                location.reload();
            }
        });
    });

    $('a.update-status, a.update-status-onpage').on('click', function (e) {
        e.preventDefault();
        $('.js_status').prop('checked', true);
        $('.js_status').closest('.switch-animate').removeClass('switch-off').addClass('switch-on');
        // var flats_ids = '<?= json_encode($flats_ids) ?>';
        //if ($(this).hasClass('update-status'))
            $.ajax({
                url: 'index.php?<?=$url_xml?>&c=setActualization&act=' + $(this).attr('data-action') + '&flats_ids=<?= json_encode($flats_ids) ?>',
                success: function (result) {
                    location.reload();
                }
            });
    })
    $('#one_dept').on('change', function(e){
        id_cat_one_dept = $('#one_dept').val();
    	console.log(id_cat_one_dept);
        e.preventDefault();
        $.ajax({
        	dataType: "json",
            url: 'index.php?<?=$url_xml?>&c=getCategory&cat='+id_cat_one_dept,
            success: function (result) {
            	temp = JSON.stringify(Object.keys(result));
            	//console.log(temp);
            	for(i = 1; i < temp.length; i++){
            		$('#two_dept').append('<option value ='+result[i].id+'> WAY</option>');
            	}
        	}
        });
    });
    /*$('#one_dept').on('change', function(){
    	id_cat_one_dept = $('#one_dept').val();
    	console.log(id_cat_one_dept);
    	$.ajax({
            url: '<?=$url?>b=flat&c=setXmlActive&act=one&i=' + id_cat_one_dept
			data: {"ajax" : "getCategory_two_dept","id_cat_one_dept":id_cat_one_dept},
            success: function (result) {

            }
        });
    });*/

    /*$('[data-flat]').on('change', function (e) {
        id = $(this).attr('data-flat');
        $.ajax({
            url: '<?=$url?>b=flat&c=setXmlActive&act=one&i=' + id + '&xml_active=' + (e.target.checked ? 1 : 0),
            success: function () {
                location.reload();
            }
        });
    });*/
        /*$.ajax({
	            url:window.location.href,
	            type:'index.php', 
	            data:{"ajax":"deleteValue","val_id":val_id}, 
	            dataType: "json",
	            success:function(result){
	            	//var html = jQuery.parseJSON(JSON.stringify(result));
	            	$( this ).closest( 'tr' ).remove(); // удаление строки с полями
		//если отрубить не выходит из формы
		//location.reload();
	        }});

    */
    /*$('#edit-and-print').on('click', function () {
       	$('.make-switch').hide();
       	$('.js_flat_status_input').hide();
       	$('.js_hide').hide();
       	$('#edit-and-print').hide();
       	$('#edit').show();
       	$('#print').show();
       	$('.js_show_second').show();*/
       

        
    });

</script>
