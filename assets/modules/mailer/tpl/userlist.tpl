<p>
	<a href="<?=$url?>&get=userlist&i=import" class="btn btn-success"><span class="icon icon-white icon-repeat"></span> <?=$_lang["mailer_import_users"]?></a>
</p>
<table class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th><?=$_lang["mailer_name"]?></th>
			<th>Email</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? while($u = $modx->db->getRow($users)): ?>
		<tr>
			<td><?=$u['user_id']?></td>
			<td><?=$u['user_name']?></td>
			<td><?=$u['user_email']?></td>
			<td>
				<a href="<?=$url?>&get=userlist&d=<?=$u['user_id']?>" class="btn btn-mini btn-danger"><span class="icon icon-white icon-remove"></span> <?=$_lang["mailer_del_user"]?></a>
			</td>
		</tr>
		<? endwhile ?>
	</tbody>
</table>