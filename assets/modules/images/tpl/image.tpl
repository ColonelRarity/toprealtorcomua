<li class="image">

	<span class="btn btn-danger btn-xs delete" data-image-remove="<?=basename($image)?>" title="<?=$_lang['image_delete']?>"><span class="glyphicon glyphicon-remove-sign"></span></span>
	
	<span class="btn <?=($img['image_visible'] == 1 ? 'btn-success' : 'btn-danger')?> btn-xs visible" data-image-visible="<?=$img['image_visible']?>" data-image-i="<?=$img['image_id']?>" title="<?=($img['image_visible'] == 1 ? $_lang['image_show'] : $_lang['image_hide'])?>"><span class="<?=($img['image_visible'] == 1 ? 'glyphicon glyphicon-eye-open' : 'glyphicon glyphicon-eye-close')?>"></span></span>
	<span class="btn btn-warning btn-xs edit" title="<?=$_lang['image_delete']?>" data-toggle="modal" data-target="#modalImage" data-i="<?=$img['image_id']?>"><span class="glyphicon glyphicon-edit"></span></span>

	<img src="<?=$modx->runSnippet("R", Array("img" => $image, "folder" => "backend/".$_GET['category'], "opt" => "w=240&h=320&zc=1"))?>" alt="" class="thumbnail">
	<input style="margin-bottom: 3px;" type="hidden" name="image[href][]" value="<?=basename($image)?>">
</li>