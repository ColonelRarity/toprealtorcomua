<table class="table table-bordered table-striped table-hover table-condensed">
	<thead>
		<tr>
			<th>ID</th>
			<th><?=$_lang["mailer_name_tpl"]?></th>
			<th><?=$_lang["mailer_date_tpl"]?></th>
			<th><?=$_lang["mailer_mail_status"]?></th>
		</tr>
	</thead>
	<tbody>
		<? 
			while($s = $modx->db->getRow($stats)): 
				$mailed     = round(($s['mailed'] * 100) / $s['all']);
				$not_mailed = 100 - $mailed;
				$m          = $s['mailed'];
				$nm         = $s['all'] - $m;
		?>
		<tr>
			<td><?=$s['template_id']?></td>
			<td><?=$s['template_subject']?></td>
			<td><?=$s['template_date']?></td>
			<td width="60%">
				<div class="progress">
				  <div class="bar bar-success" title="<?=$_lang["mailer_sents"]?> <?=$m?> <?=$_lang["mailer_mails"]?>" style="width:<?=$mailed?>%;"><b><?=$mailed?>%</b></div>
				  <div class="bar bar-danger" title="<?=$_lang["mailer_remaining"]?> <?=$nm?> <?=$_lang["mailer_mails"]?>" style="width:<?=$not_mailed?>%;"><b><?=$not_mailed?>%</b></div>
				</div>
			</td>
		</tr>
		<? endwhile ?>
	</tbody>
</table>