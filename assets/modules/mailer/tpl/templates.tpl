<p>
	<a href="<?=$url?>&get=templates&do=add" class="btn btn-success"><span class="icon icon-white icon-plus-sign"></span> <?=$_lang["mailer_add_tpl"]?></a>
</p>

<table class="table table-condensed table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th>id</th>
			<th><?=$_lang["mailer_name_tpl"]?> (<?=$_lang["mailer_theme_mail"]?>)</th>
			<th><?=$_lang["mailer_date_tpl"]?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<? while ($r = $modx->db->getRow($templates)): ?>
			<tr>
				<td><?=$r['template_id']?></td>
				<td><?=$r['template_subject']?></td>
				<td><?=$r['template_date']?></td>
				<td>
					<a href="<?=$url?>&get=templates&do=edit&t=<?=$r['template_id']?>" title="<?=$_lang["mailer_edit"]?>" class="btn btn-small btn-success update"><span class="icon icon-white icon-edit"></span> <?=$_lang["mailer_edit"]?></a>
					<a href="<?=$url?>&get=templates&delete=<?=$r['template_id']?>" title="<?=$_lang["mailer_delete"]?>" class="btn btn-small btn-danger"><span class="icon icon-white icon-remove-sign"></span> <?=$_lang["mailer_delete"]?></a>
				</td>
			</tr>
		<? endwhile ?>
	</tbody>

</table>