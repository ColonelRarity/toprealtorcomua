<div class="tab-pane <?php if($modx->config['lang_default'] == $lang) echo 'active'?>" id="<?=$lang?>">
	<div class="form-group">
    	<label for="title"><?=$_lang['search_results_returned_title']?></label>
    	<input type="text" class="form-control" id="title" placeholder="<?=$_lang['search_results_returned_title']?>" name="image[<?=$lang?>][title]" value="<?=$image['image_title_'.$lang]?>">
  	</div>
  	<div class="form-group">
	    <label for="keywords"><?=$_lang['image_alt']?></label>
	    <input type="text" class="form-control" id="keywords" placeholder="<?=$_lang['image_alt']?>" name="image[<?=$lang?>][alt]" value="<?=$image['image_alt_'.$lang]?>">
  	</div>
</div>