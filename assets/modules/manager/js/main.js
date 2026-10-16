$(document).ready(function(){
	$(".dt").datetimepicker({format:'YYYY-MM-DD HH:mm:ss'});
	$(".birthday").datetimepicker({format:'YYYY-MM-DD',pickTime:false,viewMode:'years'});
	$(".month").datetimepicker({format:'YYYY-MM',pickTime:false,viewMode:'years'});

	//selectpicker
    $("select").selectpicker();

    if(document.location.hash != '') {
    	$("a[href='"+document.location.hash+"']").click();
    }
    $('.make-switch').bootstrapSwitch('setOnLabel', 'YES');
    $('.make-switch').bootstrapSwitch('setOffLabel', 'NO');

    if($(".js_character").length > 0) {
    	var character_copy = $(".js_character tbody tr:first").clone();
    	character_copy = character_copy.find('input').val("").parents("tr");
    	$(document).on("click", '.js_add_character', function(){
    		$(this).parents("tr").after(character_copy.clone());
    		return false;
    	});
    	$(document).on('click', '.js_del_character', function(){
    		if($('.js_del_character').length > 1)
    			$(this).parents("tr").remove();
    		else
    			$(this).parents("tr").find("input").val("");
    	})
    }

    $("[name='flat[type]']").on("change", function() {
        $("[data-type]").hide('slow');
        $("[data-type] select").attr('disabled', "disabled");
        $("[data-type='"+$(this).val()+"']").show('slow');
        $("[data-type='"+$(this).val()+"'] select").removeAttr("disabled");
        $(".js_type_character select").selectpicker('refresh');
    });

    $("[name='flat[rooms_type]']").on("change", function() {

    })

    $(".js_status_appartament").on('change', function(){
        if($(this).is(':checked'))
            $(this).parents('.panel-body').find('input').removeAttr("disabled");
        else 
            $(this).parents('.panel-body').find('input').attr("disabled", "disabled");
    });
    $("form").on('submit', function() {
        $("#preloader").show();
    });
}) // document.ready