<form action="<?=$url?>b=recalls&c=update" method="post">
	<h3 class="muted">Редактирование отзыва</h2>
	<table class="table table-bordered">
		<tr>
			<th>Дата отзыва</th>
			<td><?=$recall['recall_pub_date']?></td>
		</tr>
		<tr>
			<th>Язык</th>
			<td><?=$recall['recall_lang']?></td>
		</tr>
		<tr>
			<th width="200px">Имя пользователя</th>
			<td><input type="text" name="recall[name]" class="form-control" value="<?=$recall['recall_name']?>"></td>
		</tr>
		<tr>
			<th width="200px">Тема</th>
			<td><input type="text" name="recall[subject]" class="form-control" value="<?=$recall['recall_subject']?>"></td>
		</tr>
		<tr>
			<th width="200px">Текст отзыва</th>
			<td><textarea name="recall[text]" class="form-control"><?=$recall['recall_text']?></textarea></td>
		</tr>
		<tr>
			<th width="200px">Email</th>
			<td><textarea name="recall[email]" class="form-control"><?=$recall['recall_email']?></textarea></td>
		</tr>
<!-- 		<tr>
			<th width="200px">Отображать на главной</th>
			<td>
				<label><input type="radio" name="recall[main]" <?=($recall['recall_main'] == 0 ? 'checked' : '')?> value="0"> Не отображать </label><br>
				<label><input type="radio" name="recall[main]" <?=($recall['recall_main'] == 1 ? 'checked' : '')?> value="1"> Отображать </label><br>
			</td>
		</tr> -->
		<tr>
			<th>Статус</th>
			<td>
				<label><input type="radio" name="recall[moderated]" <?=($recall['recall_moderated'] == 0 ? 'checked' : '')?> value="0"> Не опубликован </label><br>
				<label><input type="radio" name="recall[moderated]" <?=($recall['recall_moderated'] == 1 ? 'checked' : '')?> value="1"> Опубликован </label><br>
			</td>
		</tr>
	</table>
	<!-- <input type="hidden" name="recall[cover]" id="cover" value="<?=$recall['recall_cover']?>" /> -->
	<!-- <a href="#" id="uploader" class="btn btn-warning">Загрузить изображение</a>
	<ul class="images image_recall">
	</ul> -->
	<input type="hidden" name="recall[id]" value="<?=$recall['recall_id']?>">

	<p style="clear: both"><input type="submit" value="Сохранить" class="btn btn-primary"></p>
</form>
<script src="/assets/site/ajaxupload.js"></script>
<script type="text/javascript">
	/* list image */
	function updateImages(){
        $.ajax({url:"index.php",type:"GET", data:"a=112&id=6&b=get_images_recall&folder=<?=$recall['recall_id']?>", success:function(ajax){
            $(".images").html(ajax);
            $("[title]").tooltip();
            $(".images img[alt='"+$("#cover").val()+"']").addClass("cover");
        }})
    }
    
    updateImages();

    /* Ajax upload */
	new AjaxUpload($("#uploader"), {
		action: "index.php?a=112&id=6&b=upload_recall",
		multiple: true,
		name: "uploader[]",
		 data: {
			"size"  : 2048576,
			"folder": '<?=$recall['recall_id']?>'
		},
		onSubmit: function(file, ext){
		    if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){ 
		        alert('<?=$_lang["shop_file_format"]?>');
		        return false;
		    }
		},
		onComplete: function(file, response){
		    updateImages();
		}
	});

	/* Удаление файла */
	$(document).on("click", ".images .btn-danger", function() {
        if (!confirm("Вы точно хотите удалить файл?")) return false; 
        _this = $(this);
        f = $(this).parents("li").find("img").attr("alt");
        $(this).parents("p").fadeOut().remove();
        $.ajax({url:"index.php",type:"GET", data:"a=112&id=6&b=delete_files&folder=<?=$recall['recall_id']?>&delete="+f, success:function(ajax){
           		_this.parents("li").fadeOut().remove();
        }})
    });

    /*  Cover */
    $(document).on("click", ".images .btn-primary, .image img", function(e) {
    	e.preventDefault();
    	_img = $(this).parents("li").find("img");
        $("#cover").val(_img.attr('alt'));
        $(".images .cover").removeClass("cover");
        _img.addClass("cover");
    });

</script>
			