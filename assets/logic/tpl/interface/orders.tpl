<table class="table table-condensed table-bordered table-striped">
  <thead>
    <tr>
      <th rowspan="2" width="50px">ID</th>
      <th rowspan="2">Дата создание </th>
      <th rowspan="2">Клиент</th>
      <th rowspan="2">Цена</th>
      <th colspan="2">Статус</th>
      <th rowspan="2" width="80px"></th>
    </tr>
    <tr>
      <th>Заказы</th>
      <th>Оплаты</th>
    </tr>
  </thead>
  <tbody>
    <? while ($o = $modx->db->getRow($orders)): ?>
    <tr>
      <td><?=str_pad($o['order_id'], 6, 0, STR_PAD_LEFT)?></td>
      <td><?=$V->formatDate($o['order_created'])?></td>
      <td><?=$o['order_name']?> (<?=$o['order_email']?>)</td>
      <td><?=$o['order_cost']?></td>
      <td>
        <? if ($o['order_status'] == 0): ?><span class="label label-default">Удален</span><? endif ?>
        <? if ($o['order_status'] == 1): ?><span class="label label-danger">Новый</span><? endif ?>
        <? if ($o['order_status'] == 2): ?><span class="label label-warning">Отправлен</span><? endif ?>
        <? if ($o['order_status'] == 3): ?><span class="label label-success">Доставлен</span><? endif ?>
      </td>
      <td>
        <? if ($o['order_status_pay'] == 0): ?><span class="label label-danger">Не оплачено</span><? endif ?>
        <? if ($o['order_status_pay'] == 1): ?><span class="label label-success">Оплачено</span><? endif ?>
      </td>
      <td><a href="<?=$url?>b=orders&order=<?=$o['order_id']?>" class="btn btn-mini btn-primary"><span class="glyphicon glyphicon-info-sign"></span>Детали </a></td>
    </tr>
    <? endwhile; ?>
  </tbody>
</table>
<ul class="pagination"><?=$pagin;?></ul>