<form action="<?=$url?>&get=templates" method="post">
	<p><?=$_lang["mailer_tpl_i"]?>:</p>
	<ul>
		<li><?=$_lang["mailer_tpl_i2"]?> <b>{username}</b></li>
		<li><?=$_lang["mailer_tpl_i3"]?> <b>{unsubscribe_link}</b></li>
	</ul>
	<input type="text" name="theme" placeholder="<?=$_lang["mailer_mail_tpl_name"]?>" title="<?=$_lang["mailer_mail_tpl_name"]?>" autofocus class="span12" value="<?=$template['template_subject']?>">
	<br><br>
	<textarea name="post" class="span12"><?=$template['template_post']?></textarea>
	<div class="pull-left span4"><kbd>Ctrl</kbd> + <kbd>H</kbd> - <?=$_lang["mailer_tpl_i4"]?></div>
	<div class="pull-left span4"><kbd>Ctrl</kbd> + <kbd>,</kbd> - <?=$_lang["mailer_tpl_i5"]?></div>
	<p>&nbsp;</p>
	<input type="hidden" name="tpl" value="<?=$template['template_id']?>">
	<input type="submit" value="<?=$_lang["mailer_save"]?>" class="btn btn-large btn-primary">
</form>
<?=implode("",$modx->invokeEvent("OnTempFormRender"));?>