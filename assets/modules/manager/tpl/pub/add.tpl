<form action="<?=$url?>b=pub&c=save" method="POST" enctype="multipart/form-data">
	<div class="row">
		<div>

		 	<!-- Nav tabs -->
		  	<ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#basic" aria-controls="home" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-cog"></span> <?=M_PUB_BASIC_SETT?></a></li>
			    <li role="presentation"><a href="#desc" aria-controls="home" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> <?=M_PUB_DESC?></a></li>
		  	</ul>

	  		<!-- Tab panes -->
	  		<div class="tab-content">
		    	<div role="tabpanel" class="tab-pane active" id="basic">
					<table class="table table-bordered table-condensed table-striped">
						<tr>
							<td><b><?=M_USER_ACTIVE?></b></td>
							<td>
								<div class="make-switch" data-on="success" data-off="danger">
									<input type="checkbox" name="pub[status]" checked value="yes">
								</div>
							</td>
						</tr>
						<tr>
							<th width="200px"><?=M_DATE?></th>
							<td><input class="form-control dt" type="text" name="pub[date]" placeholder="<?=M_DATE?>" required value="<?=date("Y-m-d H:i:s");?>"></td>
						</tr>
						<tr>
							<th width="200px"><?=M_TYPE?></th>
							<td>
								<select name="pub[type]" id="" class="form-control" data-live-search="true">
									<option value="0" <?=($pub['type'] == 0 ? "selected" : "")?>><?=M_PUB_NEWS?></option>
									<option value="1" <?=($pub['type'] == 1 ? "selected" : "")?>><?=M_PUB_PROJECT?></option>
									<option value="2" <?=($pub['type'] == 2 ? "selected" : "")?>><?=M_PUB_PUBLIC?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th width="200px"><?=M_USER?></th>
							<td>
								<select name="pub[user]" id="" class="form-control" >
									<option value=""><?=M_PUB_AUTHOR?></option>
									<? while($user = $modx->db->getRow($users)) : ?>
										<option value="<?=$user['id']?>"><?=$user['name']?> (<?=$user['email']?>)</option>
									<? endwhile;?>
								</select>
							</td>
						</tr>
						<tr>
							<th width="200px"><?=M_PRACTICE?></th>
							<td>
								<select name="pub[practika]" id="" class="form-control" data-live-search="true">
									<? while ($practik = $modx->db->getRow($practiks)) : ?>
				                        <option value="<?=$practik['id']?>" <?=($practik['id'] == $pub['practika'] ? "selected" : "")?>><?=$practik['pagetitle']?></option>
				                    <? endwhile; ?>
								</select>
							</td>
						</tr>
						<tr>
							<th width="200px"><?=M_PUB_URL?></th>
							<td><input class="form-control" type="text" name="pub[url]" placeholder="<?=M_PUB_URL?>"></td>
						</tr>
						<tr>
							<td><b><?=M_PUB_VIEW_INDEX?></b></td>
							<td>
								<div class="make-switch" data-on="success" data-off="danger">
									<input type="checkbox" name="pub[view_index]" value="yes">
								</div>
							</td>
						</tr>
					</table>
		    	</div>
		    	<div role="tabpanel" class="tab-pane" id="desc">
		    		<table class="table table-bordered table-condensed table-striped">
		    			<tr>
							<td><b><?=M_TITLE?> en</b></td>
							<td><input class="form-control" type="text" name="pub[title_en]" value="<?=$pub['title_en']?>"></td>
						</tr>
		    			<tr>
							<td><b><?=M_CONTENT?> en</b></td>
							<td>
								<textarea id="content_en" name="pub[content_en]" class="form-control tiny col-xs-12" id="" cols="30" rows="10"><?=$pub['content_en']?></textarea>
							</td>
						</tr>
						<tr>
							<td><b><?=M_TITLE?> ru</b></td>
							<td><input class="form-control" type="text" name="pub[title_ru]" value="<?=$pub['title_ru']?>"></td>
						</tr>
						<tr>
							<td><b><?=M_CONTENT?> ru</b></td>
							<td>
								<textarea id="content_ru" name="pub[content_ru]" class="form-control tiny" id="" cols="30" rows="10"><?=$pub['content_ru']?></textarea>
							</td>
						</tr>
						<tr>
							<td><b><?=M_TITLE?> ua</b></td>
							<td><input class="form-control" type="text" name="pub[title_ua]" value="<?=$pub['title_ua']?>"></td>
						</tr>
						<tr>
							<td><b><?=M_CONTENT?> ua</b></td>
							<td>
								<textarea id="content_ua" name="pub[content_ua]" class="form-control tiny" id="" cols="30" rows="10"><?=$pub['content_ua']?></textarea>
							</td>
						</tr>
					</table>
		    	</div>
	  		</div>

		</div>
	</div>

	<input type="hidden" name="pub[id]" value="<?=$pub['id']?>">
	<div class="row">
		<div class="">
			<div class="panel panel-default">
				<div class="panel-body">
					<button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span> <b>Сохранить</b></button>
				</div>
			</div>
		</div>
	</div>
</form>