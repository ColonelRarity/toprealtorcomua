/*
made by langaner 2013
Bootstrap 3

color - цвет элементов
tokens - json элементов
block_width - ширина блока
symbol_min - минимальный ввод символов
ajax_url - url на который будет отправлен ajax
ajax_data - дополнительные параметры для ajax
not_found - сообщение "нет результата" 
autocomplete - автокомплит включен/выключен (true/false)
duplicate - дубликаты включен/выключен (false/true)
placeholder - текст плейсхолдера в поле ввода
add_new -добавлять новые включен/выключен (true/false)
prefix - префикс для новых элементов
onChange - событие на изменение (data - измененный блок)
onRemove - удаление элемента (name - имя, key - ключ)
*/
jQuery.fn.mmAutocomplete = function(options){
  var options = jQuery.extend({
    color        : 'primary',
    tokens       : '',
    block_width  : '300px',
    symbol_min   : 3,
    ajax_url     : '',
    ajax_data    : '',
    not_found    : 'По вашему запросу ничего не найдено',
    autocomplete : true,
    duplicate    : false,
    placeholder  : "Введите искомое слово",
    add_new      : true,
    prefix       : "",
    onChange: function(block) {},
    onRemove: function(name, key) {}
  },options);
  return this.each(function() {
      function tokenBuild(block, name, key, color) {
          return "<div class=\"mm-ac-block-token\" mm-ac-name=\""+name+"\" mm-ac-value=\""+key+"\"><span class=\"label label-"+color+"\">"+name+" <span class=\"glyphicon glyphicon-remove mm-ac-remove-token\"></span> </span></div>";
      };
      function searchItemBuild(name, key) {
          return "<li mm-ac-name=\""+name+"\" mm-ac-key=\""+key+"\" class=\"mm-ac-list-item\">"+name+"</li>";
      };
      function rebuildAc(block) {
          var values = [],
              _this  = block.prev("input");
          $.each(block.find('.mm-ac-block-token'), function(i, val) {
              values[i] = $(this).attr('mm-ac-value');
          });
          _this.attr("value", values.join(','));
      };
      function rebuildTokens(block) {
          block.find(".mm-ac-remove-token").on("click", function(){
              _this = $(this)
              options.onRemove(_this.parents(".mm-ac-block-token").attr("mm-ac-name"), _this.parents(".mm-ac-block-token").attr("mm-ac-value"));
              _this.parents(".mm-ac-block-token").remove();
              rebuildAc(block);
              options.onRemove();
          });
      };
      function listAction(block, duplicate) {
          block.find(".mm-ac-list li").on("click", function(){
            var _this = $(this),
                flag  = false;
            if (duplicate == true) {
                flag = duplicateView(block, _this.attr("mm-ac-key"));
            }
            if (flag == false) {
                block.find(".mm-ac-block").append(tokenBuild(block, _this.attr("mm-ac-name"), _this.attr("mm-ac-key"), options.color));
                block.find(".mm-ac-input").val("");
                block.find(".mm-ac-list").html("");
                block.find(".mm-ac-list").hide();
                rebuildTokens(block);
                rebuildAc(block);
            } else {
                block.find(".mm-ac-input").val("");
            }
        });
      };
      function duplicateView(block, value) {
          var input  = block.prev("input").val(),
              flag   = false;
          if (input != '') {
              items = input.split(',');
              for(i = 0; i < items.length; i++){
                  if (items[i] == value) {
                      return true;
                  }
              }
          }
          return false;
      };

      var that = $(this),
          container = '',
          token_items = '',
          block = '';
      that.hide();

      if (options.tokens != '' && options.tokens != '[]') {
          var data = jQuery.parseJSON(options.tokens);
          for(var i in data) {
              token_items += tokenBuild(block, data[i].name, data[i].key, options.color);
          }
      }
      container = "<div class=\"mm-ac\" style=\"width:"+options.block_width+";\"><div class=\"form-control mm-ac-block\"><input type=\"text\" class=\"form-control input-sm mm-ac-input\" placeholder=\""+options.placeholder+"\">"+token_items+"</div><ul class=\"mm-ac-list\" style=\"width:"+options.block_width+";\"></ul></div>";
      that.after(container);

      var block = that.next('.mm-ac');
      rebuildTokens(block);
      rebuildAc(block);

      if (options.add_new == true) {
          block.find(".mm-ac-input").on("keyup", function(e){
              var _this = $(this),
                  flag  = false;
              if (e.keyCode == 13 && _this.val() != "") {
                  if (options.duplicate == true) {
                      flag = duplicateView(block, _this.val());
                  }
                  if (flag == false) {
                      block.find(".mm-ac-block").append(tokenBuild(block, _this.val(), _this.val(), options.color));
                      _this.val("");
                      block.find(".mm-ac-list").html("");
                      block.find(".mm-ac-list").hide();
                      rebuildTokens(block);
                      rebuildAc(block);
                  } else {
                      block.find(".mm-ac-input").val("");
                  }
              }
          });
      }

      $(document).mouseup(function (e) {
          e.preventDefault();
          var container = block.find(".mm-ac-list");
          if (container.find('mm-ac-list-item').length == 0 && $(e.target).attr('class') != 'mm-ac-list'){
              container.hide();
              block.find(".mm-ac-input").val("");
          } else {
              container.show();
          }
      });

      if (options.autocomplete == true) {
          listAction(block, options.duplicate);
          block.find(".mm-ac-input").on("keyup", function(){
              var _this = $(this),
                  value = _this.val();
              if (_this.val().length >= options.symbol_min) {
                  block.find('.mm-ac-list').show();
                  if (options.ajax_url != '') {
                      jQuery.ajax({
                          url     : options.ajax_url,
                          type    : "GET",
                          data    : "word="+value,
                          cache   : false,
                          success : function(html){
                              data = jQuery.parseJSON(html);
                              var items = '';
                              for(var i in data) {
                                  items += searchItemBuild(data[i].name, data[i].key);
                              }
                              if (items == '') {
                                  $('.mm-ac-list').html("<li class=\"mm-ac-message\">"+options.not_found+"</li>");
                              } else {
                                  $('.mm-ac-list').html(items);
                              }
                              listAction(block, options.duplicate);
                          }
                      });
                  }
                  options.onChange(block);
              } else {
                  block.find('.mm-ac-list').hide();
              }
          }); 
      }
  });
};
