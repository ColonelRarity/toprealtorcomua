<style>
  body {
    font-size: 10px;
    padding-top: 47px;
    padding-bottom: 0px;
	}
  .container {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: auto;
    margin-left: auto;
}
.container {
    width: 99%;
}
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    position: relative;
    min-height: 0px;
    padding-right: 15px;
    padding-left: 15px;
}
.custom-width{
    width: auto !important;
}

  @media print
  {
      .no-print, .no-print *
      {
          display: none !important;
      }
  }
  
  </style>
  <form action="index.php" method="get" id="search_form" style="display: block;">
    <?php foreach ($_GET as $key => $value) : ?>
        <?php if($key == 'page'){
            $value = 1;
        }?>

        <?php if(!empty($value)){?>
            <input type="hidden" name="<?=$key?>" value="<?=$value?>">
        <?php } ?>

        <?php
            if($value != 'setXmlActive' && $key != 'act') {
                $url_xml .= '&'.$key.'='.$value;
            }
        ?>
    <?php endforeach ?>

    <!--<h3>Поиск</h3>-->
      <div class="row">
        <!-- <div class="col-md-3 col-sm-3 col-xs-3">
        </div> -->
        <div class="col-md-2 col-sm-2 col-xs-2 custom-width">
           <h6><?=M_TYPE?></h6>
           <select class="form-control" name="sort_one" id="one_dept" data-live-search="true">
                <option value=""><?=$sortValueOne; ?></option>
                <!-- <option value=""> <?=$_SESSION['sort']['sort_one']?></option>
                <?php foreach ($categories_one_dept as $key => $value) { ?>
                <option value="<?=$value['id']; ?>"
                <?=($_GET['sort_one'] == $value['name'] ? "selected" : "")?>> <?php echo $value['name']; ?></option>
                <?php } ?> -->
            </select>  
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 custom-width">
            <h6>Категория 1 уровня</h6>
            <select class="form-control" name="sort_two" id="two_dept" data-live-search="true">
                <option value=""><?=$sortValueTwo; ?></option>
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 custom-width">
            <h6>Категория 2 уровня</h6>
            <select class="form-control" name="sort_three" id="three_dept" data-live-search="true">
                <option value=""><?=$sortValueThree; ?></option>
            </select>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 custom-width">
            <h6>Код</h6>
            <input type="text" name="code" placeholder="Код объекта"
                   class="form-control" <?=(isset($_SESSION['sort']['code']) ? 'value="'.$_SESSION['sort']['code'].'"' : '')?>>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-2 custom-width">
            <h6>Статус</h6>
            <select class="form-control" name="status" data-live-search="true">
                <option value=""><?=$status ?></option>
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
    </div>

    <?php /*
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <?=(isset($sortValueOne) && $sortValueOne != '' ? '<h5>'.M_TYPE.': '.$sortValueOne.'</h5>' : '')?>
            <?=(isset($sortValueTwo) && $sortValueTwo != '' ? '<h5>Категория 1 уровня: '.$sortValueTwo.'</h5>' : '')?>
            <?=(isset($sortValueThree) && $sortValueThree != '' ? '<h5>Категория 2 уровня: '.$sortValueThree.'</h5>' : '')?>
            <?=(isset($_SESSION['sort']['code']) && $_SESSION['sort']['code'] != '' ? '<h5>Код: '.$_SESSION['sort']['code'].'</h5>' : '')?>
            <?=(isset($status) && $status != '' ? '<h5>Статус: '.$status.'</h5>' : '')?>
        </div>
    </div>
    */ ?>


    <br/>


    
    

    <!-- <div class="row">

        <div class="col-md-12">
            <p>
                <br>
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span>
                    <b>Поиск</b></button>
            </p>
        </div>
    </div> -->
</form>
<? if ($flash['success'] != ''): ?>
<div class="alert alert-success alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <?=$flash['success']?>
</div>
<?php endif; ?>
 <p> 
    <a style="float:right; " href="<?=$url?>b=flat&c=save_xml&type=exel" id="edit" class="btn download-xml btn-success no-print"><span
                class="glyphicon glyphicon-download-alt"></span> Сохранить в файл</a>
    <a style="float:right;" href="<?=$url?>b=flat" id="print" class="btn download-xml btn-success no-print"><span
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
            <th  class="js_show_second" style="width: 70%">Описание</th>
            <th width="200px" class="js_show_second" style="width: 30%" >Информация о владельце</th>
        </tr>
    </thead>
    <tbody>
    <?php $flats_ids = array(); ?>
    <?php foreach ($flats as $flat) : ?>

    <?php if($flats['xml_active'] == 0): ?>
        <?php array_push($flats_ids, $flat['id']); ?>
    <?php endif; ?>

    <tr class="<?=($flat['active'] == 1 ? " success " : "danger")?>">
    <td><?=$flat['id']?></td>
    <td><?=$flat['name']?></td>
    <td><?=$flat['addr']?></td>
    <td>
        <?php
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
    <td>$&nbsp;<?=$flat['price']?></td>
    <td><?=$flat['code']?></td>
    
    <!-- <td class="js_hide"headers="">
        <div class="make-switch" data-on="success" data-off="danger">
            <input <?=($_GET['status'] == '4' ? "disabled" : "")?> <?=($_GET['status'] == '3' ? "disabled" : "")?>  class="js_status" type="checkbox"
                   name="flat[active]" <?= (date('Y-m-d', strtotime($flat['updated_at'])) == date('Y-m-d') ? "checked" : "") ?>
            data-status="<?=$flat['id']?>" value="1">
        </div>
        <input type="text" class="js_flat_status_input" disabled
               value="<?= date('Y-m-d', strtotime($flat['updated_at'])) ?>">
    </td> -->
    <td width="600px" class="js_show_second">
        <?=$flat['description']; ?>
    </td >
    <td class="js_show_second" >
        <table  width="100%">
            <tbody>
                <tr>
                    <td>Имя:<?=$flat['owner_name']?></td>          
                </tr>
                <tr>      
                    <td>Телефон: <?=$flat['owner_phone']?></td> 
                </tr>
                <tr>    
                    <td>Комментарий: <?=$flat['owner_comment'] ?></td>   
                </tr> 
            </tbody>
        </table>
    </td >
    <td class="hide_print no-print">
        <a href="<?=$url?>b=flat&c=edit&i=<?=$flat['id']?>" class="btn btn-sm btn-primary"><span
                    class="glyphicon glyphicon-pencil"></span></a>
        <a href="<?=$modx->makeUrl(134);?><?=$flat['url']?>/" target="_blank" class="btn btn-sm btn-info"><span
                    class="glyphicon glyphicon-eye-open"></span></a>

        <?php if($flat['active'] == 1) { ?>
        <a href="<?=$url?>b=flat&c=ban&i=<?=$flat['id']?>" class="btn btn-sm btn-danger"><span
                    class="glyphicon glyphicon-eye-close"></span></a>
        <?php } else { ?>
        <a href="<?=$url?>b=flat&c=unban&i=<?=$flat['id']?>" class="btn btn-sm btn-success"><span
                    class="glyphicon glyphicon-eye-open"></span></a>
        <?php } ?>
    </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<nav class="nav_hide">
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
                console.log(result);
                for(i = 1; i < temp.length; i++){
                    $('#two_dept').prepend('<option value ='+result[i].id+'> WAY</option>');
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
    $('#edit-and-print').on('click', function () {
        document.location.reload();
        $('.make-switch').hide();
        $('.js_flat_status_input').hide();
        $('.js_hide').hide();
        $('#edit-and-print').hide();
        $('#edit').show();
        $('#print').show();
        $('.js_show_second').show();
    });

    $('#print').on('click', function () {
        //$('#search_form').hide();
        $('#edit').hide();
        $('#print').hide();
        $('.btn download-xml btn-success').hide();
        $('p').hide();
        $('#hide_js_print').hide();
        $('.hide_print').hide();
        $('.nav_hide').hide();
        $('.col-md-12').hide();
        $('input[name=flat]').hide();
        $.ajax({
            dataType: "json",
            url: 'index.php?<?=$url_xml?>&c=setExelActive&act=off_page'
            
        });
        print();
    });

</script>

