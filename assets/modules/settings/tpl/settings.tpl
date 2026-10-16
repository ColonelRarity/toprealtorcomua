<form action="<?=$url?>&get=settings&action=update" method="post">
	<table class="table table-bordered table-condensed table-striped">
		<?php foreach ($settings as $setting): ?>
			<tr>
				<td width="200px">
					<b><?=$description[$setting['setting_name']];?></b><br />
					<small>[(<?=$setting['setting_name'];?>)]</small><br /><br />
					<a href="<?=$url?>&get=settings&action=delete&sid=<?=$setting['setting_name'];?>" class="btn btn-xs btn-danger" onclick="return confirm('Вы уверены, что хотите удалить настройку [(<?=$setting['setting_name'];?>)]?')"><span class="glyphicon glyphicon-white glyphicon-trash"></span> <?=$_lang["delete"]?></a>
				</td>
				<td><textarea name="<?=$setting['setting_name'];?>" class="form-control" cols="30" rows="4"><?=$setting['setting_value'];?></textarea></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<button type="submit" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-refresh"></span> <?=$_lang['lang_btn_update']?></button>
	<a href="#addSetting" class="btn btn-success pull-right" role="button" data-toggle="modal"><span class="glyphicon glyphicon-white glyphicon-plus-sign"></span> <?=$_lang['lang_btn_add']?></a>
</form>

<div class="modal fade" id="addSetting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="<?=$url?>&get=settings&action=add" method="post">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel"><?=$_lang['advanced_add']?></h4>
        </div>
        <div class="modal-body">
          
        <table class="table table-hover table-bordered table-striped table-condensed">
        	<tr>
        		<td width="200px"><b><?=$_lang['advanced_description']?></b></td>
				<td><input type="text" name="s_add[name]" class="form-control" /></td>
        	</tr>
        	<tr>
        		<td width="200px"><b><?=$_lang['advanced_code']?></b> <small>(<?=$_lang['advanced_no_prefix']?>)</small></td>
				<td><input type="text" name="s_add[code]" class="form-control" /></td>
        	</tr>
        	<tr>
        		<td width="200px"><b><?=$_lang['advanced_value']?></b></td>
				<td><textarea name="s_add[value]" class="form-control" cols="30" rows="3"></textarea></td>
        	</tr>
        </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang['lang_btn_cancel']?></button>
          <button type="submit" class="btn btn-primary pull-right"><?=$_lang['lang_btn_save']?> <span class="glyphicon glyphicon-ok-sign"></span> </button>
        </div>
      </div>
    </div>
  </form>
</div>