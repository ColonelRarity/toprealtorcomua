<form action="<?=$url?>&get=config" method="post">
	<table class="table table-bordered table-striped table-hover table-condensed">
		<tr>
			<td><?=$_lang["mailer_server"]?></td>
			<td><input type="text" name="mailer_smtp_server" value="<?=$modx->config['mailer_smtp_server']?>" class="span4"></td>
		</tr>
		<tr>
			<td><?=$_lang["mailer_port"]?></td>
			<td><input type="text" name="mailer_smtp_port" value="<?=$modx->config['mailer_smtp_port']?>" class="span1"></td>
		</tr>
		<!-- <tr>
			<td>Шифрование</td>
			<td>
				<label class="pull-left span1"><input type="checkbox" name="smtp_tls" value="1" <?=$modx->config['smtp_tls'] ? "checked='checked'" : ""?>> TLS</label>
				<label class="pull-left span1"><input type="checkbox" name="smtp_ssl" value="1" <?=$modx->config['smtp_ssl'] ? "checked='checked'" : ""?>> SSL</label>
			</td>
		</tr> -->
		<tr>
			<td><?=$_lang["mailer_login"]?></td>
			<td><input type="text" name="mailer_smtp_login" value="<?=$modx->config['mailer_smtp_login']?>" class="span4"></td>
		</tr>
		<tr>
			<td><?=$_lang["mailer_pass"]?></td>
			<td><input type="text" name="mailer_smtp_pass" value="<?=$modx->config['mailer_smtp_pass']?>" class="span4"></td>
		</tr>
		<tr>
			<td><?=$_lang["mailer_from"]?></td>
			<td><input type="text" name="mailer_smtp_name" value="<?=$modx->config['mailer_smtp_name']?>" class="span4"></td>
		</tr>
		<tr>
			<td><?=$_lang["mailer_mail_cnt"]?></td>
			<td><input type="text" name="mailer_smtp_limit" value="<?=$modx->getConfig('mailer_smtp_limit')?>" class="span2"></td>
		</tr>
	</table>
	<input type="submit" value="<?=$_lang["mailer_save"]?>" class="btn btn-large btn-primary">
</form>