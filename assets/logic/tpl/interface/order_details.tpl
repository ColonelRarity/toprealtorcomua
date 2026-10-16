
<form action="<?=$url?>b=orders" method="post">
  <div class="row">
    <h3 class="col-md-12"></h3>
    <div class="col-md-6 rel">
      <div id="ac_result"></div>
      <div class="alert alert-warning hide"><?=$_lang["shop_on_request"]?> <b></b> <?=$_lang["shop_not_found"]?></div>
      <h4 class="text-muted">Клиент</h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th width="120px">ФИО</th>
          <td><?=$order['order_name']?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?=$order['order_email']?></td>
        </tr>
        <tr>
          <th>Телефон</th>
          <td><?=$order['order_phone']?></td>
        </tr>
      </table>
      <h4 class="text-muted">Заказ</h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th>Дата</th>
          <td><?=$order['order_created']?></td>
        </tr>
        <tr>
          <th>Статус</th>
          <td>
            <select name="order[order_status]" class="form-control" data-active="<?=$order['order_status']?>">
              <option value="0">Удален</option>
              <option value="1">Новый</option>
              <option value="2">Отправлен</option>
              <option value="3">Доставлен</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Оплата</th>
          <td>
            <select name="order[order_status_pay]" class="form-control" data-active="<?=$order['order_status_pay']?>">
              <option value="0">Не оплачен</option>
              <option value="1">Оплачен</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Тип оплата</th>
          <td>
          <?php 
          switch ($order['order_payment']) {
            case 1: $pay = "Наложеный платеж";break;
            case 2: $pay = "Visa / MasterCard";break;
          }
          echo $pay;
          ?>
          <!--<select class="form-control" name="order[order_payment]" id="delivery" data-active="<?=$order['order_payment']?>">
              <option value="1">Наложеный платеж</option>
              <option value="2">Visa / MasterCard</option>
            </select>-->
          </td>
        </tr>
        <?php if($order['order_fio'] != '') { ?>
        <tr>
          <th>Кто порекомендовал</th>
          <td>
          <p><?php echo $order['order_fio'];?></p>
          </td>
        </tr>
        <?php } ?>
        <tr>
          <th>Комментарий клиента</th>
          <td>
            <textarea class="form-control" name="order[order_comment]"><?=$order['order_comment']?></textarea>
          </td>
        </tr>
      </table>
      <h4 class="text-muted">Доставка</h4>
      <table class="table table-bordered table-condensed">
        <tr>
          <th width="120px">Способ</th>
          <td>
            <select class="form-control nc" name="order[delivery][type]" id="delivery" data-active="<?=$delivery['type']?>">
              <option value="1">Самовывоз</option>
              <option value="2">Новая почта</option>
              <option value="3">DHL</option>
              <option value="4">Курьером</option>
            </select>
          </td>
        </tr>
        <tr>
          <th>Город</th>
          <td><input class="form-control" type="text" name="order[delivery][city]" value="<?=$delivery['city']?>"/></td>
        </tr>
        <tr>
          <th>Улица</th>
          <td><input class="form-control" type="text" name="order[delivery][ave]" value="<?=$delivery['ave']?>"/></td>
        </tr>
        <!--<tr>
          <th>Дом</th>
          <td><input class="form-control" type="text" name="order[delivery][house]" value="<?=$delivery['house']?>"/></td>
        </tr>
        <tr>
          <th>Квартира</th>
          <td><input class="form-control" type="text" name="order[delivery][room]" value="<?=$delivery['room']?>"/></td>
        </tr>-->
      </table>
    </div>
    <div class="col-md-6 rel">
      <h4 class="text-muted">Товары (<?=$modx->db->getRecordCount($items)?>)</h4>
      <table class="table table-bordered table-condensed" id="items">
        <thead>
          <th>Наименование</th>
          <th width="100px">Цена</th>
          <th width="100px">Кол-во</th>
          <th width="100px">Стомость</th>
          <th width="50px"></th>
        </thead>
        <tbody>
        <? while($item = $modx->db->getRow($items)):?>
          <tr>
            <td><?=$item['pagetitle_'.$modx->config['lang']]?></td>
            <td><input class="form-control" type="text" name="order[item][price][]" value="<?=$item['product_price']?>"/></td>
            <td><input class="form-control" type="text" name="order[item][count][]" value="<?=$item['product_count']?>"/></td>
            <td><input class="form-control" type="text" readonly value="<?=str_replace(',', '.', $item['product_count'] * $item['product_price'])?>"/></td>
            <td>
              <span class="btn btn-danger js_remove"><span class="glyphicon glyphicon-remove-sign"></span></span>
              <input name="order[item][id][]" value="<?=$item['product_id']?>" type="hidden"/>
            </td>
          </tr>
        <? endwhile ?>
        </tbody>
        <tfoot>
          <!--<tr>
            <td align="center" colspan="5"><span class="btn btn-success" id="item_add"><span class="glyphicon glyphicon-plus-sign"></span> Добавить товар в заказ</span></td>
          </tr>-->
          <tr>
            <th colspan="3">Итого</th>
            <th colspan="2"><span></span> $</th>
          </tr>
        </tfoot>
      </table>
      <div class="panel panel-default bottom">
        <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> Уведомления</div>
        <div class="panel-body">
          <label><input type="checkbox" name="order[notify][status]" value="yes"/> Уведомить клиента о смене статуса заказа по email</label>
        </div>
      </div>
    </div>
  </div>
  <input name="order[order_id]" value="<?=$order['order_id']?>" type="hidden"/>
  <button class="btn btn-primary btn-lg" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Обновить</button>
</form>
<script>
  function calcOrder() {
    _total = 0;
    $("#items tbody tr").each(function(){
      _price = parseFloat($(this).find("td:eq(1) input").val());
      _count = parseInt($(this).find("td:eq(2) input").val());
      _cost  = _price * _count;
      if (!isNaN(_cost)) {
        $(this).find("td:eq(3) input").val(_cost.toFixed(2));
        _total += parseFloat(_cost);
      }
    })

   //_total += parseInt($("#delivery [data-delivery]:selected").attr("data-delivery"));
    $("#items tfoot tr:last span").html(_total.toFixed(2));
  }
  $(document).ready(function(){
    calcOrder();
    $("#delivery").on("change", function(){calcOrder()});
    $("#items").on("keyup", "input", function(){ calcOrder(); });
    $("#item_add").on("click", function(){
      $("#items tbody").append(' <tr> <td><input class="form-control js_autocomplete" type="text" placeholder="<?=$_lang["shop_name"]?>"/></td> <td><input class="form-control" type="text" name="order[item][price][]" placeholder="<?=$_lang["shop_price"]?>"/></td> <td><input class="form-control" type="text" name="order[item][count][]" placeholder="<?=$_lang["shop_count"]?>"/></td> <td><input class="form-control" type="text" readonly placeholder="<?=$_lang["shop_cost"]?>"/></td> <td><span class="btn btn-danger js_remove"><span class="glyphicon glyphicon-remove-sign"></span></span><input type="hidden" name="order[item][id][]"/></td> </tr>')
    })
    $("#items").on("keyup", ".js_autocomplete", function(){
      _this = $(this);
      if (_this.val().length < 3) return false;
      $.ajax({
        url:"index.php",
        dataType:"json",
        data:"a=255&b=find_items&query="+_this.val()+"&order=<?=$order['order_id']?>",
        success:function(_json){
          $("#ac_result").html('');
          if (_json == null) {
            $(".alert b").html(_this.val());
            $(".alert").removeClass("hide");
            return false;
          }
          $(".alert").addClass("hide");
          $("#ac_result").html('');
          for(_i in _json) {
            _item = {
              name : _json[_i].product_name,
              price : _json[_i].product_price,
              id : _json[_i].product_id
            };
            $("#ac_result").append('<div class="panel panel-default"> <div class="panel-heading">'+_json[_i].product_name+' <small class="pull-right">'+_json[_i].product_code+'</small></div> <div class="panel-body row"> <div class="col-md-3"> <img src="'+_json[_i].product_cover+'" alt="" /></div> <div class="col-md-7"><small>'+_json[_i].product_introtext+'</small></div> <div class="col-md-2"><small><b>'+_json[_i].price+'</b></<small><span class="btn btn-success" data-add=\''+JSON.stringify(_item)+'\'><span class="glyphicon glyphicon-plus"></span></span></small></div></div> </div>');
          }
        }
      })
    })
    $("#ac_result").on("click", "[data-add]", function(){
      _row  = $(".js_autocomplete:last").closest("tr");
      _json = $.parseJSON($(this).attr('data-add'));
      _row.find("td:eq(0) input").val(_json.name);
      _row.find("td:eq(1) input").val(_json.price);
      _row.find("td:eq(2) input").val(1);
      _row.find("[type=hidden]").val(_json.id);
      $("#ac_result").html('');
      calcOrder();
    })
    $("#items").on("click", ".js_remove", function(){
      if (confirm("<?=$_lang['shop_order_delete']?>")) {
        $(this).closest("tr").remove();
        calcOrder();
      }
    })
  });
</script>