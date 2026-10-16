<div class="clear"></div><br>
<form action="<?=$url?>b=recalls" method="post" enctype="multipart/form-data" class="pull-right">
	<table class="table-condensed">
		<tr>
			<td class="text-right">с</td>
			<td>
				<div id="datetimepicker" class="input-append date">
				  <input data-format="yyyy-MM-dd" type="text" name="date_from" id="datetimepicker" class="form-control add-on" style="width: 100px;" value="<?=$_REQUEST['date_from']?>"></input>
				</div>
			</td>
			<td>по</td>
			<td>
				<div id="datetimepicker2" class="input-append date">
				  <input data-format="yyyy-MM-dd" type="text" name="date_to" id="datetimepicker2" class="form-control add-on" style="width: 100px;" value="<?=$_REQUEST['date_to']?>"></input>
				</div>
			</td>
		</tr>
		<tr>
			<td>Статус</td>
			<td colspan="3">
				<select name="status" class="nc form-control">
					<option value="0" <?php if($_REQUEST['status'] == 0) echo 'selected';?> >Неопубликованы</option>
					<option value="1" <?php if($_REQUEST['status'] == 1) echo 'selected';?> >Опубликованы</option>
					<option value="-1" <?php if($_REQUEST['status'] == -1) echo 'selected';?> >Все </option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="text-right">
				<button name="b" value="recalls" class="btn btn-primary"><span class="glyphicon glyphicon-filter"></span> Фильтровать</button>
			</td>
		</tr>
	</table>
</form>
<table class="table table-condensed table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th width="50px">ID</th>
			<th><?=$_lang["shop_recall_name"];?></th>
			<th>Страница</th>
			<th width="110px">Email пользователя</th>
			<th width="20px">Язык</th>
			<th width="120px">Дата отзыва</th>
			<th width="120px">Статус</th>
			<th width="260px"></th>
		</tr>
	</thead>
	<tbody>
		<? 
			if ($modx->db->getRecordCount($recalls) == 0):
		?>
			<tr>
				<th colspan="6" class="text-center"><h4><?=$_lang["shop_on_request"];?> <i><u><?=$_GET['s']?></u></i> <?=$_lang["shop_not_found"];?></h4></th>
			</tr>
		<?
			else: 
			while ($recall = $modx->db->getRow($recalls)): 
				      
      
			$i++;
		?>
			<tr>				
				<td><?=$recall['recall_id']?></td>
				<td><?=$recall['recall_name']?></td>
				<td><a href="<?=$modx->makeUrl($recall['recall_page'])?>" target="_blank"><?=$recall['pagetitle']?></a></td>
				<td><?=$recall['recall_email']?></td>
				<td><?=$recall['recall_lang']?></td>
				<td><?=$recall['recall_pub_date']?></td>
				<td>
					<b>
						<? if ($recall['recall_moderated'] == 0): ?><span class="label label-danger">Не опубликован</span><? endif ?>
						<? if ($recall['recall_moderated'] == 1): ?><span class="label label-success">Oпубликован</span><? endif ?>
					</b>
				</td>
				<td>
					<a href="<?=$url?>b=recalls&c=edit&i=<?=$recall['recall_id']?>" class="btn btn-mini btn-primary"><span class="glyphicon glyphicon-edit"></span> Редактировать </a>
					<a href="<?=$url?>b=recalls&c=delete&i=<?=$recall['recall_id']?>" class="btn btn-mini btn-danger"><span class="glyphicon glyphicon-remove-sign"></span> Удалить </a>
				</td>
			</tr>
		<? endwhile;endif; ?>		
	</tbody>
</table>
<ul class="pagination"><?=$pagin;?></ul>