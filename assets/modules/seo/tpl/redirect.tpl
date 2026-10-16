<p>
	<a href="#myModal" data-toggle="modal" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Добавить перенаправление</a>
</p>

<table class="table table-condensed table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th>id</th>
			<th>code</th>
			<th>source</th>
			<th>target</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? while ($r = $modx->db->getRow($redirects)): ?>
			<tr>
				<td><?=$r['redirect_id']?></td>
				<td>
					<select name="edit[code]" class="form-control">
						<option value="301">301</option>
						<option value="302">302</option>
					</select>
				</td>
				<td><input type="text" name="edit[source]" value="<?=$r['redirect_source']?>" class="form-control" /></td>
				<td><input type="text" name="edit[target]" value="<?=$r['redirect_target']?>" class="form-control" /></td>
				<td>
					<input type="hidden" name="edit[redirect]" value="<?=$r['redirect_id']?>">
					<a href="<?=$url?>&get=redirect" title="Обновить" class="btn btn-small btn-success update"><span class="glyphicon glyphicon-repeat"></span></a>
					<a href="<?=$url?>&get=redirect&delete=<?=$r['redirect_id']?>" title="Удалить" class="btn btn-small btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
			</tr>
		<? endwhile ?>
	</tbody>

</table>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Добавить перенаправление</h4>
      </div>
      <form action="<?=$url?>&get=redirect" method="post">
      	<div class="modal-body">
      	  <table class="table table-condensed table-bordered table-hover table-striped">
			<tr>
				<td>Код перенаправления</td>
				<td>
				    <select name="add[code]" class="form-control">
						<option value="301">301 - permanent</option>
						<option value="302">302 - temporary</option>
						<option value="404">404 - not found</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Перенаправить с</td>
				<td><input type="text" name="add[source]" class="form-control" value="" /></td>
			</tr>
			<tr>
				<td>на</td>
				<td><input type="text" name="add[target]" class="form-control" value="" /></td>
			</tr>
		</table>
      	</div>
      	<div class="modal-footer">
      	  <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span> Отмена</button>
      	  <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-ok-sign"></span> Сохранить</button>
      	</div>
      </form>
    </div>
  </div>
</div>