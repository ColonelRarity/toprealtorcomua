// preloader
const htmlLangRaw = (document.documentElement.lang || '').toLowerCase();
const LANG = htmlLangRaw.split('-')[0] || 'ru';

var hellopreloader = document.getElementById("hellopreloader_preload");

function fadeOutnojquery(el) {
    el.style.opacity        = 1;
    var interhellopreloader = setInterval(function () {
        el.style.opacity = el.style.opacity - 0.05;
        if (el.style.opacity <= 0.05) {
            clearInterval(interhellopreloader);
            hellopreloader.style.display = "none";
        }
    }, 16);
}

$('body').addClass('loading');
window.onload = function () {
    setTimeout(function () {
        if (document.getElementById("hellopreloader_preload") != undefined) {
            fadeOutnojquery(hellopreloader);
        }

    }, 1000);
    $('link[rel="preload"]').each(function(){
        $(this).attr('rel', 'stylesheet');
    });
    var lazyLoad = new LazyLoad({
        elements_selector: "img",
    });
};
 // $(window).on("load",function(){
 // 	setTimeout (function(){
 // $(".Scrollbar").mCustomScrollbar();}, 2000);   
 //        });

var selector = '.slick-slide:not(.slick-cloned)';



    $(".gallery-images").slick({
        lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        infinite : true,
        fade: false,
        swipe:true,
        // speed: 1000,
        asNavFor: '.gallery-thumbs-list',
        responsive: [
        {
         breakpoint: 991,
         settings: {
            swipe:true,
            dots: true
        }
    },
    {
     breakpoint: 767,
     settings: {
        swipe:true,
        dots:false
    }
}

]

});

    // // slider object end big
    // // slider object small

    $('.gallery-thumbs-list').slick({
        lazyLoad: 'progressive',
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.gallery-images',
        focusOnSelect: true,
        vertical: true,
        verticalSwiping: true,
        responsive: [
        {
         breakpoint: 767,
         settings: {
            vertical: false,
            verticalSwiping: false,
            slidesToShow:3,
            slidesToScroll: 1,
            centerMode: true,
            centerPadding: '0px',
        }
    },
            {
                breakpoint: 601,
                settings: {
                    vertical: false,
                    verticalSwiping: false,
                    slidesToShow:2,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: '0px',
                }
            }
    ]
});

$(document).ready(function () {
    var yearBlock = document.querySelector('.yearN'),
    yearNow = new Date().getFullYear().toString();
    if (yearNow.length) {
        yearBlock.innerText = yearNow;
    }
    var $hero = $("#slider1 div.sld");
    $hero.owlCarousel({
        nav       : true,
        navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
        items     : 1,
        margin    : 0,
        loop      : true,
        navRewind : false,
        smartSpeed: 1000,
        autoplay  : true,
        autoplayTimeout: 8500,
        animateOut: 'fadeOut',
        animateIn : 'fadeIn'
    });
    function openHeroCaption() {
        $hero.find(".caption-slider").removeClass("is-open");
        var $active = $hero.find(".owl-item.active .caption-slider");
        if (!$active.length) return;
        $active[0].offsetHeight;
        $active.addClass("is-open");
    }
    $hero.on("translate.owl.carousel", function () {
        $hero.find(".caption-slider").removeClass("is-open");
    });
    $hero.on("translated.owl.carousel", openHeroCaption);
    setTimeout(openHeroCaption, 400);
    $('.object__on-map').on('click', function () {
        $('html, body').animate({
            scrollTop: $(".area-info").offset().top - 70 + 'px'
        }, 1000);
        return false;
    });
    // setTimeout(function () {
    //     $('#gallery-thumbs .thumb-item').each(function (i,e) {
    //         var thumbThis = $(e).index();
    //         $(e).attr('data-slide-index', thumbThis)
    //         console.log(thumbThis)
    //     })
    // },5000)



    $('input, textarea').placeholder();
    $(".js_owl-carousel-head").owlCarousel({
        nav       : false,
        loop      : true,
        items     : 1,
        // animateOut: 'fadeOutRight',
        // animateIn: 'fadeInLeft',
        animateOut: 'fadeOut',
        animateIn : 'fadeIn',
        margin    : 30,
        smartSpeed: 450,
        autoplay  : true
    });
    $(".js_owl-carousel-head__mob").owlCarousel({
        nav       : false,
        loop      : true,
        // animateOut: 'slideOutDown'
        animateOut: 'fadeOut',
        animateIn : 'fadeIn',
        items     : 1,
        margin    : 30,
        smartSpeed: 450,
        autoplay  : true
    });
    setTimeout(function () {
        $('#hellopreloader_preload').hide();
    }, 4000);
    if ($.browser.mozilla) {
        if ($.browser.version > '30') {
            $('.let-star').css(
                'animation', "none"
                );
        } else {
            $('body').addClass('no-animate');
        }
    }
    $(document).on('change', '.js_curr_info', function () {
        if ($(this).val() == "USD" || $(this).val() == "EURO") {
            $('#modal-currency').modal('show');
            setTimeout(function () {
                $('#modal-currency').modal('hide');
            }, 10000);
        }
    });

    $(document).on('click', '.js_check_currency', function (e) {
        e.preventDefault();
        if ($(this).attr('href') == "USD" || $(this).attr('href') == "EURO") {
            $('#modal-currency').modal('show');
        }
        $.ajax({
            data: "ajax=setCurrency&curr=" + $(this).attr('href'), success: function () {
                setTimeout(function () {
                    window.location.reload();
                }, 1000);
            }
        })

    });
    // ---- i18n helpers ----
// const htmlLangRaw = (document.documentElement.lang || '').toLowerCase();
// const LANG = htmlLangRaw.split('-')[0] || 'ru'; // 'ru' | 'uk' (fallback: 'ru')

// словарь текстов
const I18N = {
  ru: {
    menuTitle: 'Меню',
    login: 'Вход',
    register: 'Регистрация',
    logout: 'Выход',
    readMore: 'Читать далее',
    readLess: 'Свернуть',
    twitterLangParam: 'ru',
  },
  ua: {
    menuTitle: 'Меню',          // если нужно: 'Меню' → 'Меню' ок, или 'Меню' украинское как 'Меню'
    login: 'Вхід',
    register: 'Реєстрація',
    logout: 'Вихід',
    readMore: 'Читати далі',
    readLess: 'Згорнути',
    twitterLangParam: 'uk',
  }
};
const T = I18N[LANG] || I18N.ru;

// ---- mobile nav ----
var _link2menu;
if (_user == 'false') {
  _link2menu = [
    `<a href="#enter" class="js_close-mm" data-toggle="modal">${T.login}</a>`,
    `<a href="#registration" class="js_close-mm" data-toggle="modal">${T.register}</a>`,
  ];
} else {
  _link2menu = [
    `<a href="/?logout">${T.logout}</a>`
  ];
}

$('#menu').mmenu({
  navbars: [
    {
      position: "bottom",
      content: [
        `<a href="https://www.facebook.com/" target="_blank" rel="nofollow" class="icon-facebook"></a>`,
        `<a href="https://twitter.com/?lang=${encodeURIComponent(T.twitterLangParam)}" target="_blank" rel="nofollow" class="icon-twitter"></a>`,
      ]
    },
    {
      position: "top",
      content: _link2menu,
    }
  ],
  extensions: [
    "pagedim-black", "fx-menu-slide"
  ],
  offCanvas: {
    position: "right",
    zposition: "front"
  },
  navbar: { title: T.menuTitle },
  counters: false
}, {
  classNames: { selected: "act" }
});

var API = $("#menu").data("mmenu");
$(document).on('click', '.js_close-mm', function () {
  API.close();
});

// ---- "Читать далее" / "Свернуть" на двух языках ----
$('.more_view').on('click', function () {
  const $btn = $(this);
  const $p = $('.object-description').find('p');

  if ($btn.hasClass('act')) {
    $btn.removeClass('act');
    $p.removeClass('visible');
    $btn.text(T.readMore);
  } else {
    $btn.addClass('act');
    $p.addClass('visible');
    $btn.text(T.readLess);
  }
  return false;
});

// При загрузке страницы (если нужно задать начальный текст кнопки)
$('.more_view').each(function(){
  const $btn = $(this);
  $btn.text($btn.hasClass('act') ? T.readLess : T.readMore);
});


    /* --------------- start validation --------------*/
    $(".js_validate button, .js_validate input[type=submit]").on("click", function () {
        if (!validate($(this).parents(".js_validate")))
            return false;
    });
    /* --------------- end validation --------------*/
    /* change course */
    $(document).on("click", '[data-course]', function () {
        $.ajax({
            url     : '/',
            type    : 'POST',
            dataType: 'json',
            data    : 'ajax=course&c=' + $(this).attr("data-course"),
            success : function (response) {
                if (response.status == true)
                    location.reload();
            }
        })
        return false;
    });
    /* change course in form search */
    $("[name='course']").on('change', function () {
        if($(this).val() == "UAH")
            $(".js_search_course").text($(this).find("option:selected").attr("data-course"));
    });
    /* social network */

    Share = {
        vkontakte    : function (purl, ptitle, pimg, text) {
            url = 'http://vkontakte.ru/share.php?';
            url += 'url=' + encodeURIComponent(purl);
            url += '&title=' + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&image=' + encodeURIComponent(pimg);
            url += '&noparse=true';
            Share.popup(url);
        },
        odnoklassniki: function (purl, text) {
            url = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
            url += '&st.comments=' + encodeURIComponent(text);
            url += '&st._surl=' + encodeURIComponent(purl);
            Share.popup(url);
        },

        // old
        //facebook: function(purl, ptitle, pimg, text) {
        //    url  = 'http://www.facebook.com/sharer.php?s=100';
        //    url += '&p[title]='     + encodeURIComponent(ptitle);
        //    url += '&p[summary]='   + encodeURIComponent(text);
        //    url += '&p[url]='       + encodeURIComponent(purl);
        //	url += '&p[images][0]=' + encodeURIComponent(pimg);
        //    Share.popup(url);
        //},
        // new
        facebook: function (siteUrl, picture, title, caption, descr, winWidth, winHeight) {
            event.preventDefault();
            var winTop  = (screen.height / 2) - (winHeight / 2);
            var winLeft = (screen.width / 2) - (winWidth / 2);
            window.open('http://www.facebook.com/sharer.php?s=100&u=' + siteUrl + '&picture=' + picture + '&title=' + title + '&caption=' + caption, '&description=' + descr, 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
            return false;
        },


        twitter: function (purl, ptitle) {
            url = 'http://twitter.com/share?';
            url += 'text=' + encodeURIComponent(ptitle);
            url += '&url=' + encodeURIComponent(purl);
            url += '&counturl=' + encodeURIComponent(purl);
            Share.popup(url);
        },
        mailru : function (purl, ptitle, pimg, text) {
            url = 'http://connect.mail.ru/share?';
            url += 'url=' + encodeURIComponent(purl);
            url += '&title=' + encodeURIComponent(ptitle);
            url += '&description=' + encodeURIComponent(text);
            url += '&imageurl=' + encodeURIComponent(pimg);
            Share.popup(url)
        },
        popup  : function (url) {
            console.log(url);
            window.open(url, '', 'toolbar=0,status=0,width=626,height=436');
        },
        gg     : function (_options) {
            var options = $.extend({
                url: location.href
            }, _options);
            url         = 'https://plus.google.com/share?url='
            + encodeURIComponent(options.url);
            Share.popup(url);
        },
        li     : function (_options) {
            var options = $.extend({
                url  : location.href,
                title: document.title,
                text : ''
            }, _options);
            return 'http://www.linkedin.com/shareArticle?mini=true'
            + '&url=' + encodeURIComponent(options.url)
            + '&title=' + encodeURIComponent(options.title)
            + '&summary=' + encodeURIComponent(options.text);
        },
    };
    /* favorites */
    $(document).on("click", "[data-favorites]", function () {
        if(LANG == 'ua'){
            alert("Об'єкт додано до обраного.", '');
        } else {
            alert('Объект добавлен в избранное.', '');
        }
        var _i = $(this).attr("data-favorites");
        var f  = getCookie('favorites');
        if (f != undefined) {
            f  = JSON.parse(f);
            fl = f.indexOf(_i);
            if (fl == '-1') {
                f.push(_i);
                $(this).addClass('liked');
                $(".js_favorites_many").show();
                $(".js_favorites_null").hide();
                        if(LANG == 'ua'){
                            alert("Об'єкт додано до обраного.", '');
                        } else {
                            alert('Объект добавлен в избранное.', '');
                        }
            } else {
                f.splice(fl, 1);
                if (f.length == 0) {
                    $(".js_favorites_many").hide();
                    $(".js_favorites_null").show();
                }
                if(LANG == 'ua'){
                    alert("Об'єкт видалено з обраного.", '');
                } else {
                    alert('Объект удален из избранного.', '');
                }

                $(this).removeClass('liked');
            }
        } else {
            f = [];
            f.push(_i);
            $(this).addClass('liked');
            $(".js_favorites_many").show();
            $(".js_favorites_null").hide();
            if(LANG == 'ua'){
                alert("Об'єкт додано до обраного.", '');
            } else {
                alert('Объект добавлен в избранное.', '');
            }
        }
        var date = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000);
        setCookie('favorites', JSON.stringify(f), 'expires=' + date.toUTCString());
        return false;
    });
    /* delete object favorites from page favorites*/
    $(document).on("click", '.js_favorites_del', function () {
        $(this).parents('.flat-item').remove();
        if ($(".flat-item").length == 0)
             if(LANG == 'ua'){
                $(".flat-box .row").text('Ви нічого не додали до вибраного.');
            } else {
                $(".flat-box .row").text('Вы ничего не добавили в избранное.');
            }
            
    });
    /* form search */
    if ($("select[data-active]").length > 0) {
        
        $("select[data-active]").each(function () {
            _this   = $(this);
            _active = _this.attr("data-active");
            if (_active != '') {
                _active = _active.split(",");
                for (i in _active) {
                    _this.find("option[value=" + _active[i] + "]").attr("selected", "selected");
                   
                }
            }
        });
    }
    // search button accordion
    $('.js_open-search').on('click', function () {
        if ($(this).parents('.search-box').find('.search-menu').hasClass('visible')) {
            $(this).parents('.search-box').find('.search-menu').removeClass('visible');
            $('.search-box').removeClass('open');
        } else {
            $('.search-box').addClass('open');
            $(this).parents('.search-box').find('.search-menu').addClass('visible');
        }
        return false;
    });
    if ($(".js_search_options").length > 0) {
        // тип объекта
        $(document).on("change", ".js_search_options [name=type]", function () {
            $("[data-type] option").removeAttr("selected");
            var _val = $(this).val();
            $("[data-type]").attr("disabled", "disabled").parents('.search-col ').hide('slow');
            $("[data-price]").show('slow');
            $("[data-area], [data-lot]").hide('slow');
            //$("[name=price_min],[name=price_max],[name=area_min],[name=area_max]").attr("disabled", "disabled");
            $(".js_cottage").hide();
            switch (_val) {
                case '1': // Кввартира
                $("[data-type=1][name='rooms[]']").removeAttr("disabled").parents('.search-col').show('slow');
                    // $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                    break;
                case '2': // Дом
                $(".js_cottage").show();
                $("[data-type=2][name='rooms_type[]'], [data-type=2][name='rooms[]']").removeAttr("disabled").parents('.search-col ').show('slow');
                    // $("[data-price]").show('slow');
                    $("[data-area]").show('slow');
                    $('[name=price_min],[name=price_max], [name=area_min], [name=area_max]').removeAttr("disabled");
                    break;
                case '3': // Участок
                $(".js_cottage").show();
                $("[data-type=3][name='rooms_type']").removeAttr("disabled").parents('.search-col ').show('slow');
                    // $("[data-price]").show('slow');
                    $("[data-lot]").show('slow');
                    $('[name=price_min],[name=price_max], [name=area_min], [name=area_max]').removeAttr("disabled");
                    break;
                case '4': // аренда
                $("[data-type=4][name='rooms_type'], [data-type=4][name='district'], [data-type=4][name='rooms[]']").removeAttr("disabled").parents('.search-col ').show('slow');
                if ($("[data-type=4][name='district']").val() == 4) {
                    $("[data-type=4][name='district_detail']").removeAttr("disabled").parents('.search-col ').show();
                } else {
                    $("[data-type=4][name='district_detail']").attr("disabled", "disabled").parents('.search-col ').hide();
                }
                break;
                case '5': // бизнес
                $("[data-type=5]").removeAttr("disabled").parents('.search-col ').show('slow');
                    // $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                    break;
                case '6': // зарубежная недвижемость
                $("[data-type=6]").removeAttr("disabled").parents('.search-col ').show('slow');
                break;
                default:
                break;
            }
            $(".js_selectpicker").selectpicker('refresh');
        });
        // Квартира -> кол-во комнат/тип квартир -> район
        $(document).on("change", '[data-type="1"][name="rooms[]"]', function () {
            var _val = $(this).val();
            if (_val == null) {
                $("[data-type=1][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
            } else
            $("[data-type=1][name='district[]']").removeAttr("disabled").parents('.search-col ').show('slow');
            // дополнительные районы в квартирах для современного дома
            if ($("[data-type=1][name='rooms[]'] option[value=7]:selected").length == 0) {
                $("[data-new-house=7]").attr("disabled", "disabled").hide();
            } else {
                $("[data-new-house=7]").removeAttr("disabled").show();
            }
            // другие районы
            if ($("[data-type=1][name='rooms[]'] option[value=7]:selected").length == 1 && $("[data-type=1][name='rooms[]'] option[value=6]:selected").length == 0 && $("[data-type=1][name='rooms[]'] option[value=5]:selected").length == 0) {
                $("[data-new-house=1]").attr("disabled", "disabled").hide();
            } else {
                $("[data-new-house=1]").removeAttr("disabled").show();
            }
            $(".js_selectpicker").selectpicker('refresh');
        });
        // Дом -> в городе -> район
        $(document).on("change", '[data-type="2"][name="rooms_type[]"]', function () {
            if ($("[data-type=2][name='rooms_type[]'] option[value=1]:selected").length) {
                $("[data-type=4][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
                $("[data-type=2][name='district[]']").removeAttr("disabled").parents('.search-col ').show('slow');
            } else if ($("[data-type=2][name='rooms_type[]'] option[value=2]:selected").length) {
                $("[data-type=2][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
                $("[data-type=4][name='district[]']").removeAttr("disabled").parents('.search-col ').show('slow');
            } else {
                $("[data-type=2][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
                $("[data-type=4][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
            }
            $(".js_selectpicker").selectpicker('refresh');
        });
        // Участок -> тип -> место
        $(document).on("change", '[data-type="3"][name="rooms_type"]', function () {
            if ($("[data-type=3][name='rooms_type'] option[value=1]:selected").length == 0)
                $("[data-type=3][name='rooms_type_detail[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
            else
                $("[data-type=3][name='rooms_type_detail[]']").removeAttr("disabled").parents('.search-col ').show('slow');
            $(".js_selectpicker").selectpicker('refresh');
        });
        $(document).on('change', '[data-type="3"][name="rooms_type_detail[]"]', function () {
            if ($("[data-type=3][name='rooms_type_detail[]'] option[value=1]:selected").length == 0)
                $("[data-type=3][name='district[]']").attr("disabled", "disabled").parents('.search-col ').hide('slow');
            else
                $("[data-type=3][name='district[]']").removeAttr("disabled").parents('.search-col ').show('slow');
            $(".js_selectpicker").selectpicker('refresh');
        });
        // аренда
        $(document).on('change', '[data-type="4"][name="district"]', function () {
            if ($("[data-type=4][name='district']").val() == 4) {
                $("[data-type=4][name='district_detail']").removeAttr("disabled").parents('.search-col ').show();
            } else {
                $("[data-type=4][name='district_detail']").attr("disabled", "disabled").parents('.search-col ').hide();
            }
            $(".js_selectpicker").selectpicker('refresh');
        });
        // отображение select при загрузке страници
        switch ($("[name=type]").val()) {
            case '1': // квартира
            $("[data-type=1][name='rooms[]']").removeAttr("disabled").parents('.search-col ').show();
            var _rooms = $("[data-type=1][name='rooms[]']").val();
                if (_rooms != null) { // выводим районы
                    $("[data-type=1][name='district[]']").removeAttr("disabled").parents('.search-col').show();
                    // дополнительные районы в квартирах для современного дома
                    if ($("[data-type=1][name='rooms[]'] option[value=7]:selected").length == 0) {
                        $("[data-new-house=7]").attr("disabled", "disabled").hide();
                    } else {
                        $("[data-new-house=7]").removeAttr("disabled").show();
                    }
                }
                // cost
                // if($("[name=price_min]").val() != '' && $('[name=price_max]').val() != ''){
                    $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                // }
                break;
            case '2': // дом
            $(".js_cottage").show();
            $("[data-type=2][name='rooms_type[]'], [data-type=2][name='rooms[]']").removeAttr("disabled").parents('.search-col ').show();
            if ($("[data-type=2][name='rooms_type[]'] option[value=1]:selected").length > 0)
                $("[data-type=2][name='district[]']").removeAttr("disabled").parents('.search-col ').show();
                // cost
                // if($("[name=price_min]").val() != '' && $('[name=price_max]').val() != ''){
                    $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                // }
                // area
                // if($("[name=area_min]").val() != '' && $('[name=area_max]').val() != '') {
                    $("[data-area]").show('slow');
                    $('[name=area_min], [name=area_max]').removeAttr("disabled");
                // }
                break;
            case '3': // участок
            $(".js_cottage").show();
            $("[data-type=3][name='rooms_type']").removeAttr("disabled").parents('.search-col ').show();
            if ($("[data-type=3][name='rooms_type'] option[value=1]:selected").length > 0)
                $("[data-type=3][name='rooms_type_detail[]'], [data-type=3][name='district[]']").removeAttr("disabled").parents('.search-col ').show();
                // cost
                // if($("[name=price_min]").val() != '' && $('[name=price_max]').val() != '') {
                    $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                // }
                // area
                // if($("[name=lot_min]").val() != '' && $('[name=lot_max]').val() != '') {
                    $("[data-lot]").show('slow');
                    $('[name=area_min], [name=area_max]').removeAttr("disabled");
                // }
                break;
            case '4': // аренда
            $("[data-price]").show('slow');
            $("[data-type=4][name='rooms_type'], [data-type=4][name='district'], [data-type=4][name='rooms[]']").removeAttr("disabled").parents('.search-col ').show();
            if ($("[data-type=4][name='district']").val() == 4) {
                $("[data-type=4][name='district_detail']").removeAttr("disabled").parents('.search-col ').show();
            } else {
                $("[data-type=4][name='district_detail']").attr("disabled", "disabled").parents('.search-col ').hide();
            }
            $(".js_selectpicker").selectpicker('refresh');
            break;
            case '5': // бизнес
            $("[data-type=5]").removeAttr("disabled").parents('.search-col ').show();
                // cost
                // if($("[name=price_min]").val() != '' && $('[name=price_max]').val() != '') {
                    $("[data-price]").show('slow');
                    $('[name=price_min],[name=price_max]').removeAttr("disabled");
                // }
                break;
            case '6': // зарубежная
                // $("[data-type=6]").removeAttr("disabled").parents('.search-col ').show();
                $("[data-type=6]").removeAttr("disabled").parents('.search-col ').show('slow');
                $("[data-price]").show('slow');
                break;
            }
        }
        /* map  category */
        if ($('#map-objects').length > 0) {
            // init();
        }
        /* map one object */
        if ($('#map-one-object').length > 0) {
            $(window).scroll(function () {
                //DG_init_object();
                // initial();
            })
            // $(document).on('click', '.map-one-object', function () {
            //    $(this).hide();
            //     initial();
            // });

        }
    /* map search */
    if ($('#map-search').length > 0) {
    	//DG_init_search();
        // initializ();
        // $(document).on('click', '.map-search', function () {
        //     $(this).hide();
        //     initializ();
        // });
    }
        /* оставить заявку */
        $(document).on('click', '[data-agent]', function () {
            var a = $(this).attr("data-agent");
            var i = $(this).attr("data-i");
            $("#request input[name='agent[id]']").val(a);
            $("#request input[name='agent[object]']").val(i);
            $("#request").modal('show');
            return false;
        });
        /* template list objects */

        $(document).on("scroll", function () {
            if ($(document).scrollTop() > 10) {
                $("header").addClass("shrink");
            } else {
                $("header").removeClass("shrink");
            }
        });
    // shrink header end
    // scxroll
    $(window).load(function () {
    	setTimeout (function(){
        $(".object-scroll").mCustomScrollbar({
            axis         : "y",
            scrollInertia: 160,
            // autoHideScrollbar: true,
        });
       }, 1000);
    });
    // var thumbsSlider  = $('#gallery-thumbs');
    // var gallerySlider = $('#gallery');
    // gallerySlider     = $('#gallery').bxSlider({
    //     mode    : 'fade',
    //     controls: true,

    //     pager: true,

    //     speed: 500,

    //     nextText: '',

    //     prevText: '',

    //     infiniteLoop: true,
    //     // onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //     //     var sld = newIndex - 1;
    //     //     if (sld < 0)  newIndex = 0
    //     //     thumbsSlider.goToSlide(sld);
    //     //     $('.thumb-item').removeClass('active-slide');

    //     //     $('[data-slide-index=' + newIndex  + ']').addClass('active-slide');
    //     // }
    //     touchEnabled: false

    // });
    // if ($(window).width() < 768) {

    //     thumbsSlider = $('#gallery-thumbs').bxSlider({

    //         pager: false,

    //         slideWidth: 100,

    //         minSlides: 3,

    //         maxSlides: 3,

    //         nextText: '',

    //         infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //         prevText: '',


    //         moveSlides: 1,

    //         onSliderLoad: function () {
    //             $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');

    //             // $('#gallery-thumbs>li:not(.bx-clone)').eq(1).addClass('active-slide');

    //             // gallerySlider.goToSlide(1);

    //         },

    //         onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //             $('.thumb-item').removeClass('active-slide');

    //             $($slideElement).next().addClass('active-slide');

    //         },

    //         onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //             $('.thumb-item').removeClass('active-slide');

    //             $($slideElement).next().addClass('active-slide');

    //         },

    //         onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //             gallerySlider.goToSlide(newIndex + 1);

    //         }


    //     });
    // } else {

    //     thumbsSlider = $('#gallery-thumbs').bxSlider({

    //         controls: true,

    //         pager: false,

    //         mode: 'vertical',

    //         slideMargin: 15,

    //         nextText: '',

    //         prevText: '',

    //         minSlides: 3,

    //         maxSlides: 3,

    //         preventDefaultSwipeX: true,

    //         preventDefaultSwipeY: true,

    //         infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //         moveSlides: 1,

    //         onSliderLoad: function ($slideElement) {

    //             $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');

    //             // gallerySlider.goToSlide(1);

    //         },

    //         onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //             $('.thumb-item').removeClass('active-slide');

    //             $($slideElement).next().addClass('active-slide');

    //         },

    //         onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //             $('.thumb-item').removeClass('active-slide');

    //             $($slideElement).next().addClass('active-slide');


    //         },

    //         onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //             var sld = $($slideElement).next().attr('data-slide-index');

    //             gallerySlider.goToSlide(sld);

    //         },

    //     });

    // }

    // setTimeout(function () {

    //     var width = $(window).width();

    //     if ($('#gallery-thumbs').length > 0) {

    //         $.ajax({

    //             url: document.location.href,

    //             type: "POST",

    //             dataType: "json",

    //             data: "ajax=slider&i=" + $(".object-info").attr("data-id") + "&watermark=" + $("[data-watermark]").attr("data-watermark"),

    //             success: function (response) {
    //                 $("#gallery").append(response.bigSlider);

    //                 $("#gallery-thumbs").append(response.smallSlider);


    //                 var thumb = $('#gallery-thumbs').find('.thumb');

    //                 gallerySlider.reloadSlider();

    //                 if (width < 768) {
    //                     thumbsSlider.destroySlider();
    //                     $('#gallery-thumbs .thumb-item').each(function (i, item) {
    //                         $(item).attr('data-slide-index', i)
    //                     });
    //                     thumbsSlider = $('#gallery-thumbs').bxSlider({

    //                         pager: false,

    //                         slideWidth: 100,

    //                         minSlides: 3,

    //                         maxSlides: 3,

    //                         nextText: '',

    //                         infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //                         prevText: '',


    //                         moveSlides: 1,

    //                         //  onSliderLoad: function () {

    //                         //     $('#gallery-thumbs>li:not(.bx-clone)').eq(1).addClass('active-slide');

    //                         //     gallerySlider.goToSlide(1);

    //                         // },

    //                         onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //                             $('.thumb-item').removeClass('active-slide');

    //                             $($slideElement).next().addClass('active-slide');

    //                         },

    //                         onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //                             $('.thumb-item').removeClass('active-slide');

    //                             $($slideElement).next().addClass('active-slide');

    //                         },

    //                         onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //                             gallerySlider.goToSlide(newIndex + 1);

    //                         }


    //                     });

    //                 } else {
    //                     thumbsSlider.destroySlider();
    //                     $('#gallery-thumbs .thumb-item').each(function (i, item) {
    //                         $(item).attr('data-slide-index', i)
    //                     });
    //                     thumbsSlider.reloadSlider({

    //                         controls: true,

    //                         pager: false,

    //                         mode: 'vertical',

    //                         slideMargin: 15,

    //                         nextText: '',

    //                         prevText: '',

    //                         minSlides: 3,

    //                         maxSlides: 3,

    //                         preventDefaultSwipeX: true,

    //                         preventDefaultSwipeY: true,

    //                         infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //                         moveSlides: 1,

    //                         // onSliderLoad: function ($slideElement) {

    //                         //     $('#gallery-thumbs>li:not(.bx-clone)').eq(1).addClass('active-slide');

    //                         //     // gallerySlider.goToSlide(1);

    //                         // },

    //                         onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //                             $('.thumb-item').removeClass('active-slide');

    //                             $($slideElement).next().addClass('active-slide');

    //                         },

    //                         onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //                             $('.thumb-item').removeClass('active-slide');

    //                             $($slideElement).next().addClass('active-slide');


    //                         },

    //                         onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //                             var sld = $($slideElement).next().attr('data-slide-index');

    //                             gallerySlider.goToSlide(sld);

    //                         },

    //                     });

    //                 }

    //                 $(document).on('click', '#gallery-thumbs .thumb-item', function () {
    //                     // var thumbThis = $(this).index();

    //                     var indexSLider = parseInt($(this).attr('data-slide-index'));
    //                     var idx         = (parseInt($(this).attr('data-slide-index')) + thumbsSlider.getSlideCount() - 1) % thumbsSlider.getSlideCount();
    //                     thumbsSlider.goToSlide(idx);
    //                     /*console.log(idx)*/
    //                     $('#gallery-thumbs .thumb-item').removeClass('active-slide');

    //                     $('[data-slide-index=' + indexSLider + ']').addClass('active-slide');
    //                     // $(this).addClass('active-slide');
    //                     /* console.log(indexSLider)*/

    //                     gallerySlider.goToSlide(indexSLider);

    //                 });


    //                 $(window).resize(function () {

    //                     if ($(window).width() < 768) {

    //                         thumbsSlider.destroySlider();

    //                         thumbsSlider = $('#gallery-thumbs').bxSlider({

    //                             pager: false,

    //                             slideWidth: 100,

    //                             minSlides: 3,

    //                             maxSlides: 3,

    //                             nextText: '',

    //                             infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //                             prevText: '',


    //                             moveSlides: 1,

    //                             //  onSliderLoad: function () {

    //                             //     $('#gallery-thumbs>li:not(.bx-clone)').eq(1).addClass('active-slide');

    //                             //     gallerySlider.goToSlide(1);

    //                             // },

    //                             onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //                                 $('.thumb-item').removeClass('active-slide');

    //                                 $($slideElement).next().addClass('active-slide');

    //                             },

    //                             onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //                                 $('.thumb-item').removeClass('active-slide');

    //                                 $($slideElement).next().addClass('active-slide');

    //                             },

    //                             onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //                                 gallerySlider.goToSlide(newIndex + 1);

    //                             }


    //                         });

    //                     } else {

    //                         thumbsSlider.destroySlider();

    //                         thumbsSlider = $('#gallery-thumbs').bxSlider({

    //                             controls: true,

    //                             pager: false,

    //                             mode: 'vertical',

    //                             slideMargin: 15,

    //                             nextText: '',

    //                             prevText: '',

    //                             minSlides: 3,

    //                             maxSlides: 3,

    //                             preventDefaultSwipeX: true,

    //                             preventDefaultSwipeY: true,

    //                             infiniteLoop: $('#gallery-thumbs .thumb-item').length > 3 ? true : false,

    //                             moveSlides: 1,

    //                             // onSliderLoad: function ($slideElement) {

    //                             //     $('#gallery-thumbs>li:not(.bx-clone)').eq(1).addClass('active-slide');

    //                             //     gallerySlider.goToSlide(1);


    //                             // },

    //                             onSlideNext: function ($slideElement, oldIndex, newIndex) {

    //                                 $('.thumb-item').removeClass('active-slide');

    //                                 $($slideElement).next().addClass('active-slide');

    //                             },

    //                             onSlidePrev: function ($slideElement, oldIndex, newIndex) {

    //                                 $('.thumb-item').removeClass('active-slide');

    //                                 $($slideElement).next().addClass('active-slide');


    //                             },

    //                             onSlideAfter: function ($slideElement, oldIndex, newIndex) {

    //                                 var sld = $($slideElement).next().attr('data-slide-index');

    //                                 gallerySlider.goToSlide(sld);

    //                             },

    //                         });

    //                     }

    //                 }); //end resize function

    //                 // $(".gallery-preview").remove();

    //             }

    //         });

    //     }

    // }, 3000);
    // //     setTimeout(function(){
    // //         //thumbsSlider.destroySlider();
    // //         //gallerySlider.destroySlider();
    // //     var thumbsSlider = $('#gallery-thumbs');
    // //     var width = $(window).width();
    // //     if ($('#gallery-thumbs').length > 0) {
    // //         $.ajax({
    // //             url: document.location.href,
    // //             cache: false,
    // //             type: "POST",
    // //             dataType: "json",
    // //             data: "ajax=slider&i="+$("[data-id]").attr("data-id")+"&watermark="+$("[data-watermark]").attr("data-watermark")+"&m="+(width < 1024 ? 'mobile' : ''),
    // //             success: function(response){
    // //                $(".gallery-preview").remove();
    // //                 $("#gallery").html(response.bigSlider);
    // //                 $("#gallery-thumbs").html(response.smallSlider);
    // //                 //$("#gallery").html("ajax=slider&i="+$("[data-i]:first").attr("data-i")+"&watermark="+$("[data-watermark]").attr("data-watermark"));
    // //                 var thumb = $('#gallery-thumbs').find('.thumb');
    // //                 var gallerySlider = $('#gallery').bxSlider({
    // //                     controls: true,
    // //                     pager: true,
    // //                     speed: 500,
    // //                     nextText:'',
    // //                     prevText:'',
    // //                     infiniteLoop:false
    // //                     // hideControlOnEnd: true,
    // //                 });
    // //                 if( width < 768){
    // //                     thumbsSlider = $('#gallery-thumbs').bxSlider({
    // //                         pager:false,
    // //                         slideWidth: 100,
    // //                         minSlides: 3,
    // //                         maxSlides: 3,
    // //                         nextText:'',
    // //                         prevText:'',
    // //                         // slideMargin: 5,
    // //                         // hideControlOnEnd: true,
    // //                         moveSlides: 1,
    // //                          onSliderLoad: function () {
    // //                             $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');
    // //                             gallerySlider.goToSlide(0);
    // //                         },
    // //                         onSlideNext: function ($slideElement, oldIndex, newIndex) {
    // //                             $('.thumb-item').removeClass('active-slide');
    // //                             $($slideElement).next().addClass('active-slide');
    // //                         },
    // //                         onSlidePrev: function ($slideElement, oldIndex, newIndex) {
    // //                             $('.thumb-item').removeClass('active-slide');
    // //                             $($slideElement).next().addClass('active-slide');
    // //                         },
    // //                         onSlideAfter: function ($slideElement, oldIndex, newIndex) {
    // //                             gallerySlider.goToSlide(newIndex + 1);
    // //                         }
    // //                     });
    // //                 } else {
    // //                     thumbsSlider = $('#gallery-thumbs').bxSlider({
    // //                         controls: true,
    // //                         pager: false,
    // //                         mode: 'vertical',
    // //                         slideMargin: 15,
    // //                         nextText:'',
    // //                         prevText:'',
    // //                         minSlides: 3,
    // //                         maxSlides: 3,
    // //                         preventDefaultSwipeX: true,
    // //                         preventDefaultSwipeY: true,
    // //                         infiniteLoop:true,
    // //                         // hideControlOnEnd: true,
    // //                         // displaySlideQty: visibleThumbs,
    // //                         // moveSlideQty: visibleThumbs,
    // //                         moveSlides: 1,
    // //                         onSliderLoad: function ($slideElement) {
    // //                             $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');
    // //                             gallerySlider.goToSlide(0);
    // //                         },
    // //                         onSlideNext: function ($slideElement, oldIndex, newIndex) {
    // //                             $('.thumb-item').removeClass('active-slide');
    // //                             $($slideElement).next().addClass('active-slide');
    // //                         },
    // //                         onSlidePrev: function ($slideElement, oldIndex, newIndex) {
    // //                             $('.thumb-item').removeClass('active-slide');
    // //                             $($slideElement).next().addClass('active-slide');
    // //                         },
    // //                         onSlideAfter: function ($slideElement, oldIndex, newIndex) {
    // //                             var sld = $($slideElement).next().attr('data-slide-index');
    // //                             gallerySlider.goToSlide(sld);
    // //                         },
    // //                     });
    // //                 }
    // //                 $(document).on('click','#gallery-thumbs .thumb-item',function(){
    // //                     var indexSLider = $(this).attr('data-slide-index');
    // //                     var idx = (parseInt($(this).attr('data-slide-index')) + thumbsSlider.getSlideCount() - 1) % thumbsSlider.getSlideCount();
    // //                     thumbsSlider.goToSlide(idx);
    // //                     $('#gallery-thumbs .thumb-item').removeClass('active-slide');
    // //                     $('[data-slide-index=' + indexSLider + ']').addClass('active-slide');
    // //                     gallerySlider.goToSlide(indexSLider);
    // //                 });
    // //                 $(window).resize(function(){
    // //                     if ($(window).width() < 768) {
    // //                         //thumbsSlider.destroySlider();
    // //                         thumbsSlider = $('#gallery-thumbs').bxSlider({
    // //                             pager:false,
    // //                             slideWidth: 100,
    // //                             minSlides: 3,
    // //                             maxSlides: 3,
    // //                             nextText:'',
    // //                             infiniteLoop:true,
    // //                             prevText:'',
    // //                             // slideMargin: 5,
    // //                             // hideControlOnEnd: true,
    // //                             moveSlides: 1,
    // //                              onSliderLoad: function () {
    // //                                 $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');
    // //                                 gallerySlider.goToSlide(0);
    // //                             },
    // //                             onSlideNext: function ($slideElement, oldIndex, newIndex) {
    // //                                 $('.thumb-item').removeClass('active-slide');
    // //                                 $($slideElement).next().addClass('active-slide');
    // //                             },
    // //                             onSlidePrev: function ($slideElement, oldIndex, newIndex) {
    // //                                 $('.thumb-item').removeClass('active-slide');
    // //                                 $($slideElement).next().addClass('active-slide');
    // //                             },
    // //                             onSlideAfter: function ($slideElement, oldIndex, newIndex) {
    // //                                 gallerySlider.goToSlide(newIndex + 1);
    // //                             }
    // //                         });
    // //                     } else {
    // //                         thumbsSlider.destroySlider();
    // //                         thumbsSlider = $('#gallery-thumbs').bxSlider({
    // //                             controls: true,
    // //                             pager: false,
    // //                             mode: 'vertical',
    // //                             slideMargin: 15,
    // //                             nextText:'',
    // //                             prevText:'',
    // //                             minSlides: 3,
    // //                             maxSlides: 3,
    // //                             preventDefaultSwipeX: true,
    // //                             preventDefaultSwipeY: true,
    // //                             infiniteLoop:true,
    // //                             // hideControlOnEnd: true,
    // //                             // displaySlideQty: visibleThumbs,
    // //                             // moveSlideQty: visibleThumbs,
    // //                             moveSlides: 1,
    // //                             onSliderLoad: function ($slideElement) {
    // //                                 $('#gallery-thumbs>li:not(.bx-clone)').eq(0).addClass('active-slide');
    // //                                 gallerySlider.goToSlide(0);
    // //                             },
    // //                             onSlideNext: function ($slideElement, oldIndex, newIndex) {
    // //                                 $('.thumb-item').removeClass('active-slide');
    // //                                 $($slideElement).next().addClass('active-slide');
    // //                             },
    // //                             onSlidePrev: function ($slideElement, oldIndex, newIndex) {
    // //                                 $('.thumb-item').removeClass('active-slide');
    // //                                 $($slideElement).next().addClass('active-slide');
    // //                             },
    // //                             onSlideAfter: function ($slideElement, oldIndex, newIndex) {
    // //                                 var sld = $($slideElement).next().attr('data-slide-index');
    // //                                 gallerySlider.goToSlide(sld);
    // //                             },
    // //                         });
    // //                     }
    // //                 }); //end resize function

    // //             }
    // //         });
    // //     }
    // // }, 3000);



    // // slider object big

    // $().fancybox({
    //     selector : '.gallery-images .slick-slide:not(.slick-cloned)',
    //     backFocus : false
    // });
    // $(document).on('click', '.slick-cloned', function(e) {
    //     var $slides = $(this)
    //         .parent()
    //         .children('.slick-slide:not(.slick-cloned)');
    //
    //     $slides
    //         .eq( ( $(this).attr("data-slick-index") || 0) % $slides.length )
    //         .trigger("click.fb-start", { $trigger: $(this) });
    //
    //     return false;
    // });
    // Init fancybox
// =============
 

// Skip cloned elements
//     $().fancybox({
//         selector : selector,
//         backFocus : false
//     });

// Attach custom click event on cloned elements,
// trigger click event on corresponding link


    // $().fancybox({
    //     selector : '.gallery-images .slick-slide:not(.slick-cloned)',
    //     backFocus : false
    // });
    // $(document).on('click', '.slick-cloned', function(e) {
    //     var $slides = $(this)
    //         .parent()
    //         .children('.slick-slide:not(.slick-cloned)');
    //
    //     $slides
    //         .eq( ( $(this).attr("data-slick-index") || 0) % $slides.length )
    //         .trigger("click.fb-start", { $trigger: $(this) });
    //
    //     return false;
    // });


   
    // // slider object small
    //drop list for currency
    $('.js_foot-menu').on('click', function () {
        if ($('.foot-menu-m').hasClass('open')) {
            $('.foot-menu-m').removeClass('open');
            $(this).find('.fa').removeClass('rot')
        } else {
            $('.foot-menu-m').addClass('open');
            $(this).find('.fa').addClass('rot')
        }
    });
    //drop list for language
    $('.js_language').on('click', function () {
        if ($('.languages_list').hasClass('open')) {
            $('.languages_list').removeClass('open');
            $('.languages').removeClass('open');
        } else {
            $('.languages_list').addClass('open');
            $('.languages').addClass('open');
        }
    });
    $('.js_cur').on('click', function () {
        if ($('.currency_list').hasClass('open')) {
            $('.currency_list').removeClass('open');
            $('.currency').removeClass('open');
        } else {
            $('.currency_list').addClass('open');
            $('.currency').addClass('open');
        }
    });
    $('.js_curr').on('click', function () {
        if ($('.currency_list').hasClass('open')) {
            $('.currency_list').removeClass('open');
            $('.currencies').removeClass('open');
        } else {
            $('.currency_list').addClass('open');
            $('.currencies').addClass('open')
        }

    });

    // customs tabs
    if ($(window).width() < 769) {
    $('.object-tab--custom a').click(function (e) {
        var tab = $(this);
        if(tab.parent('li').hasClass('active')){
            window.setTimeout(function(){
                $(".tab-pane").removeClass('active');
                tab.parent('li').removeClass('active');
            },1);
        }
    });
     };





    // customs tabs

    // $(".js_check_currency").on("click", function(e){
    //     e.preventDefault();
    //     $.ajax({data:"ajax=setCurrency&curr="+$(this).attr('href'),success:function(){
    //             window.location.reload();
    //         }})
    // })

    //slider revolution
    //  jQuery("#slider1").revolution({
    //      startwidth: 1170,
    //      startheight: 630,
    //      hideThumbs: 10,
    //      fullWidth: "on",
    //      delay: 10000,
    //      touchenabled: 'off',
    //      navigationArrows: "solo",
    //      gridwidth: 1230,
    //      gridheight: 720
    //  });
    //  $(window).on('resize load', (function() {
    //      var fn = function() {
    //          var winH = $(window).height(),
    //              winW = $(window).width(),
    //              colH = $('.cost-col').height();
    //              if (winW > 1980) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-400',
    //                  'data-voffset': '-170'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              })
    //          }
    //              if (winW > 1920) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-400',
    //                  'data-voffset': '-150'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              })
    //          }
    //          if (winW < 1700) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-250',
    //                  'data-voffset': '-170'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              })
    //          }
    //          if (winW < 1550) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-175',
    //                  'data-voffset': '-170'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              })
    //          }
    //          if (winW < 1365) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-50',
    //                  'data-voffset': '-170'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              })
    //          }
    //          if (winW < 1200) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '20',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-125',
    //                  'data-voffset': '-170'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '20',
    //                  'data-voffset': '0'
    //              })
    //          }
    // if (winW < 1030) {
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-50',
    //                  'data-voffset': '-170'
    //              });
    //          }
    // if (winW < 999) {
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '-50',
    //                  'data-voffset': '20'
    //              });
    //          }
    //          if (winW < 600) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '50',
    //                  'data-voffset': '80'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '50',
    //                  'data-voffset': '80'
    //              })
    //          }
    //          if (winW < 480) {
    //              $('.banner-top').find('.tp-caption.ltl').attr({
    //                  'data-hoffset': '20',
    //                  'data-voffset': '250'
    //              });
    //              $('.banner-top').find('.tp-caption.image').attr({
    //                  'data-hoffset': '0',
    //                  'data-voffset': '0'
    //              });
    //              $('.banner-top').find('.tp-caption.sfr').attr({
    //                  'data-hoffset': '150',
    //                  'data-voffset': '250'
    //              })
    //          }
    //      };
    //      fn();
    //      return fn;
    //  })());
    //slider revolution
    //slider novelty
    var owl = $('.js_owl-carousel-novelty');
    if (owl.children().length > 1) {
        owl.owlCarousel({
            nav       : true,
            navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
            items     : 3,
            margin    : 20,
            // loop      : true,
            // navRewind: false,
            smartSpeed: 450,
            autoplay  : false,
            responsive: { 0: { items: 1, stagePadding: 36 }, 768: { items: 2, stagePadding: 0 }, 1100: { items: 3, stagePadding: 0 } }
        });
    } else {
        owl.owlCarousel({
            nav       : true,
            navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
            items     : 3,
            margin    : 20,
            loop      : false,
            // navRewind: false,
            smartSpeed: 450,
            autoplay  : false,
            responsive: { 0: { items: 1, stagePadding: 36 }, 768: { items: 2, stagePadding: 0 }, 1100: { items: 3, stagePadding: 0 } }
        });
    }
    //slider novelty
    /*fancybox*/
    $('.js-fancy').fancybox({
        padding: 0,
        helpers: {
            overlay: {
                locked: false
            }
        }
    });
    // $(".fancybox").fancybox({
    //     padding   : 0,
    //     margin    : 5,
    //     autoCenter: false,
    //     afterLoad : function () {
    //         $.extend(this, {
    //             aspectRatio: false,
    //             type       : 'html',
    //             width      : '100%',
    //             height     : '100%',
    //             content    : '<div class="fancybox-image" style="background-image:url(' + this.href + '); background-size: cover; background-position:50% 50%;background-repeat:no-repeat;height:100%;width:100%;" /></div>'
    //         });
    //     }
    // });
    $("[data-fancybox]").fancybox({
        loop     : true,
        idleTime  : 20,
        afterClose: function (instance, current, e) {
			if (typeof panoramaReinit == 'function') {
            	panoramaReinit(instance, current, e);
        	}
        },
		beforeShow:  function ( instance, slide ) {
			if (typeof panoramabeforeShow == 'function') {
            	panoramabeforeShow( instance, slide );
        	}
        },
		afterShow:  function ( instance, slide ) {
			if (typeof panoramaafterShow == 'function') {
            	panoramaafterShow( instance, slide );
        	}
        },
		
    });
    // video
    $('.video-pres').click(function () {
        this.paused ? this.play() : this.pause();
    });
    //video
    // $('.js_selectpicker').selectpicker();

    // $('.js_selectpicker').selectpicker('refresh');
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $('.js_selectpicker').selectpicker('mobile');
    }
    else {
         $('.js_selectpicker').selectpicker();
    }

    // slider inform
    // $(".js_owl-carousel-inform").owlCarousel({
    //     nav       : true,
    //     navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
    //     items     : 1,
    //     margin    : 30,
    //     loop      : true,
    //     // navRewind: false,
    //     smartSpeed: 450,
    //     autoplay  : false
    // });
    var owlInform = $('.js_owl-carousel-inform');
    if (owlInform.children().length > 1) {
        owlInform.owlCarousel({
         nav       : true,
         navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
         items     : 1,
         margin    : 30,
         loop      : true,
         smartSpeed: 450,
         autoplay  : false
     });
    } else {
        owlInform.owlCarousel({
            nav       : false,
            items     : 1,
            margin    : 30,
            loop      : false,
            // navRewind: false,
            smartSpeed: 450,
            autoplay  : false
        });
    }
    //slider inform
    //mob nav
    $('li.drop .mob-butt').on('click', function () {
        if ($(this).hasClass('act-mob-nav')) {
            $(this).removeClass('act-mob-nav');
            $('.first-ul').hide();
        } else {
            $('.first-ul').hide();
            $('li.drop .mob-butt').removeClass('act-mob-nav');
            $(this).addClass('act-mob-nav');
            $(this).parent().find('.first-ul').show();
        }
    });
    $('li.drop-sec .mob-butt1').on('click', function () {
        if ($(this).hasClass('act-mob-nav')) {
            $(this).removeClass('act-mob-nav');
            $('.second-ul').hide();
        } else {
            $('.second-ul').hide();
            $('li.drop-sec .mob-butt1').removeClass('act-mob-nav');
            $(this).addClass('act-mob-nav');
            $(this).parent().find('.second-ul').show();
        }
    });
    $('li.drop-third .mob-butt2').on('click', function () {
        if ($(this).hasClass('act-mob-nav')) {
            $(this).removeClass('act-mob-nav');
            $('.third-ul').hide();
        } else {
            $('.third-ul').hide();
            $('li.drop-third .mob-butt2').removeClass('act-mob-nav');
            $(this).addClass('act-mob-nav');
            $(this).parent().find('.third-ul').show();
        }
    });
    // mob nav
    $('.share').on('click', function () {
        if ($(this).find('.share-box').hasClass('open')) {
            $(this).find('.share-box').removeClass('open');
        }
        else {
            $(this).find('.share-box').addClass('open');
        }
    });
    $('input[type="file"]').on('change', function () {
        var filename = $('.upload').val().split('\\').pop();
        $(this).parent().find('mark').text(filename);
    });
    $('.obj-hover').hover(
        function () {
            $(this).find('.item-hidden').addClass('visible');
        },
        function () {
            $(this).find('.item-hidden').removeClass('visible');
        }
        );
    // go to top
    $(function () {
        $(window).scroll(function () {
            var top = $(document).scrollTop();
            if (top < 100) {
                $(".go-to-top").fadeOut(400);
            } else {
                $(".go-to-top").show(400);
            }
        });
        $('.go-to-top').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    });
    // go to top
    // same height
    $('.why-item').on('hover', function () {
        if ($(this).find('.why-hide').hasClass('active')) {
            $(this).find('.why-hide').removeClass('active');
        } else {
            $('.why-item').find('.why-hide').removeClass('active')
            $(this).find('.why-hide').addClass('active');
        }
    });

    function heightEL() {
        var elH       = document.getElementsByClassName("why-item");
        var maxHeight = 0;
        for (var i = 0; i < elH.length; ++i) {
            elH[i].style.height = "";
            // elH[i].style.lineHeight = "";
            if (maxHeight < elH[i].clientHeight) {
                maxHeight = elH[i].clientHeight;
            }
        }
        for (var i = 0; i < elH.length; ++i) {
            elH[i].style.height = maxHeight + "px";
            // elH[i].style.lineHeight = maxHeight + "px";
        }
    }

    $(window).on('resize', (function () {
        setTimeout(function(){
            heightEL()
        }, 100);
    }));
    $(window).on('load', (function () {
        setTimeout(function(){
            heightEL()
        }, 100);
    }));


    function heightProd() {
        $('.js_height-block').each(function (i, e) {
                var elH = e.getElementsByClassName("height-prod");
                var maxHeight = 0;
                for (var i = 0; i < elH.length; ++i) {
                    elH[i].style.height = "";
// elH[i].style.lineHeight = "";
                    if (maxHeight < elH[i].clientHeight) {
                        maxHeight = elH[i].clientHeight;
                    }
                }
                for (var i = 0; i < elH.length; ++i) {
                    elH[i].style.height = maxHeight + "px";
                }
            }
        )}
    $(window).on('resize', (function () {
        setTimeout( heightProd(),2000)
    }));
    $(window).on('load', (function () {
        setTimeout( heightProd(),2000)
    }));




    // same height
    // info flat
    $(document).on('click', '[data-info]', function () {
        $.ajax({
            url     : '/',
            type    : 'POST',
            dataType: 'json',
            data    : 'ajax=info&lang='+LANG+'&type=' + $(this).attr("data-info") + "&i=" + $(this).attr("data-i"),
            success : function (response) {
                if (response.status == true) {
                    $("#modal-info .text-center").html(response.mess);
                    $("#modal-info").modal('show');
                }
            }
        })
        return false;
    });


    //floor info
    // $(document).on('click', '[data-info="flat"]', function () {
    //     $.ajax({
    //         url     : '/',
    //         type    : 'POST',
    //         dataType: 'json',
    //         data    : 'ajax=info&type=' + $(this).attr("data-info") + "&i=" + $(this).attr("data-i"),
    //         success : function (response) {
    //             if (response.status == true) {
    //                 $("#modal-info .text-center").html(response.mess);
    //                 $("#modal-info").modal('show');
    //             }
    //         }
    //     })
    //     return false;
    // });


    $(document).on('submit', '.js_sendmail', function () {
        var data = $(this).serialize();
        data += '&link=' + window.location.href;

        $.ajax({
            url     : '/',
            type    : 'POST',
            dataType: 'json',
            data    : data,
            success : function (response) {
                if (response.status == true) {
                    $("#sendEmail").modal('hide');
                    //$("#modal-info-flat .text-center").html(response.mess);
                    alert(response.mess);
                    $("#modal-info-flat").modal('show');
                }
            }
        })
        return false;
    });

    // новая расылка новости
    $(document).on('click', '.js_new_send_subs', function () {
        $.ajax({
            url     : '/',
            type    : 'POST',
            dataType: 'json',
            data    : 'ajax=new_send_subs&i=' + $(this).attr("data-i"),
            success : function (response) {
                if (response.status == true) {
                    $("#modal-info-flat .text-center").html(response.mess);
                    $("#modal-info-flat").modal('show');
                }
            }
        })
        return false;
    });
    $('#modal-call').on('shown.bs.modal', function (event) {
        $('#modal-ask').hide();
    });
    // hide section news on home page
    if ($(".js_index_news .slidernov-item").length == 0)
        $(".js_index_news").hide();
});
$(function (window, document, $) {
    // Opera Mini v7 doesn t support placeholder although its DOM seems to indicate so
    var isOperaMini         = Object.prototype.toString.call(window.operamini) == '[object OperaMini]';
    var isInputSupported    = 'placeholder' in document.createElement('input') && !isOperaMini;
    var isTextareaSupported = 'placeholder' in document.createElement('textarea') && !isOperaMini;
    var prototype           = $.fn;
    var valHooks            = $.valHooks;
    var propHooks           = $.propHooks;
    var hooks;
    var placeholder;
    if (isInputSupported && isTextareaSupported) {
        placeholder = prototype.placeholder = function () {
            return this;
        };
        placeholder.input = placeholder.textarea = true;
    } else {
        placeholder = prototype.placeholder = function () {
            var $this = this;
            $this
            .filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
            .not('.placeholder')
            .bind({
                'focus.placeholder': clearPlaceholder,
                'blur.placeholder' : setPlaceholder
            })
            .data('placeholder-enabled', true)
            .trigger('blur.placeholder');
            return $this;
        };
        placeholder.input    = isInputSupported;
        placeholder.textarea = isTextareaSupported;
        hooks                = {
            'get': function (element) {
                var $element       = $(element);
                var $passwordInput = $element.data('placeholder-password');
                if ($passwordInput) {
                    return $passwordInput[0].value;
                }
                return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
            },
            'set': function (element, value) {
                var $element       = $(element);
                var $passwordInput = $element.data('placeholder-password');
                if ($passwordInput) {
                    return $passwordInput[0].value = value;
                }
                if (!$element.data('placeholder-enabled')) {
                    return element.value = value;
                }
                if (value == '') {
                    element.value = value;
                    // Issue #56: Setting the placeholder causes problems if the element continues to have focus.
                    if (element != safeActiveElement()) {
                        // We can't use `triggerHandler` here because of dummy text/password inputs :(
                        setPlaceholder.call(element);
                    }
                } else if ($element.hasClass('placeholder')) {
                    clearPlaceholder.call(element, true, value) || (element.value = value);
                } else {
                    element.value = value;
                }
                // `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
                return $element;
            }
        };
        if (!isInputSupported) {
            valHooks.input  = hooks;
            propHooks.value = hooks;
        }
        if (!isTextareaSupported) {
            valHooks.textarea = hooks;
            propHooks.value   = hooks;
        }
        $(function () {
            // Look for forms
            $(document).delegate('form', 'submit.placeholder', function () {
                // Clear the placeholder values so they don't get submitted
                var $inputs = $('.placeholder', this).each(clearPlaceholder);
                setTimeout(function () {
                    $inputs.each(setPlaceholder);
                }, 10);
            });
        });
        // Clear placeholder values upon page reload
        $(window).bind('beforeunload.placeholder', function () {
            $('.placeholder').each(function () {
                this.value = '';
            });
        });
    }

    function args(elem) {
        // Return an object of element attributes
        var newAttrs      = {};
        var rinlinejQuery = /^jQuery\d+$/;
        $.each(elem.attributes, function (i, attr) {
            if (attr.specified && !rinlinejQuery.test(attr.name)) {
                newAttrs[attr.name] = attr.value;
            }
        });
        return newAttrs;
    }

    function clearPlaceholder(event, value) {
        var input  = this;
        var $input = $(input);
        if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
            if ($input.data('placeholder-password')) {
                $input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
                // If `clearPlaceholder` was called from `$.valHooks.input.set`
                if (event === true) {
                    return $input[0].value = value;
                }
                $input.focus();
            } else {
                input.value = '';
                $input.removeClass('placeholder');
                input == safeActiveElement() && input.select();
            }
        }
    }

    function setPlaceholder() {
        var $replacement;
        var input  = this;
        var $input = $(input);
        var id     = this.id;
        if (input.value == '') {
            if (input.type == 'password') {
                if (!$input.data('placeholder-textinput')) {
                    try {
                        $replacement = $input.clone().attr({
                            'type': 'text'
                        });
                    } catch (e) {
                        $replacement = $('<input>').attr($.extend(args(this), {
                            'type': 'text'
                        }));
                    }
                    $replacement
                    .removeAttr('name')
                    .data({
                        'placeholder-password': $input,
                        'placeholder-id'      : id
                    })
                    .bind('focus.placeholder', clearPlaceholder);
                    $input
                    .data({
                        'placeholder-textinput': $replacement,
                        'placeholder-id'       : id
                    })
                    .before($replacement);
                }
                $input = $input.removeAttr('id').hide().prev().attr('id', id).show();
                // Note: `$input[0] != input` now!
            }
            $input.addClass('placeholder');
            $input[0].value = $input.attr('placeholder');
        } else {
            $input.removeClass('placeholder');
        }
    }

    function safeActiveElement() {
        try {
            return document.activeElement;
        } catch (err) {
        }
    }
}(this, document, jQuery));

function heightELem() {
    var elH       = document.getElementsByClassName("height");
    var maxHeight = 0;
    for (var i = 0; i < elH.length; ++i) {
        elH[i].style.height = "";
        // elH[i].style.lineHeight = "";
        if (maxHeight < elH[i].clientHeight) {
            maxHeight = elH[i].clientHeight;
        }
    }
    for (var i = 0; i < elH.length; ++i) {
        elH[i].style.height = maxHeight + "px";
        // elH[i].style.lineHeight = maxHeight + "px";
    }
}

$(window).on('resize', (function () {
    heightELem()
}));
$(window).on('load', (function () {
    heightELem()
}));

//maps on the page Contact
function initialize(coord, address, phone) {
    coord          = coord.split(',');
    var mapOptions = {
        zoom            : 14,
        scrollwheel     : false,
        draggable       : true,
        disableDefaultUI: false,
        mapTypeId       : google.maps.MapTypeId.ROADMAP,
        center          : new google.maps.LatLng(coord[0], coord[1]),
        styles          : [{
            "stylers": [
            {
                "hue": "#dce4e9"
            }
            ]
        }]
    };
    var mapElement = document.getElementById('map-contacts');
     var map        = new google.maps.Map(mapElement, mapOptions);
    var markers    = [{
        latLng: [coord[0], coord[1]]
    }];

    function closeInfoWindows() {
        for (var i = 0; i < infowindow.length; i++) {
            infowindow[i].close();
        }
    }

    var infowindow = [];

    if(LANG == 'ua'){
        var address_office = 'Адрес агентства';
    } else {
        var address_office = 'Адреса агентства';
    }

    var contentmap = '<div class="objectmap">' +
    '<span href="" class="objectmap__head">'+ address_office +': ' + address + '.</span>' +
    '<div class="objectmap__cost">тел.' + phone + '</div>'
    for (i in markers) {
        infowindow[i] = new google.maps.InfoWindow({
            content: contentmap
        });
        var marker    = new google.maps.Marker({
            position        : new google.maps.LatLng(markers[i].latLng[0], markers[i].latLng[1]),
            map             : map,
            icon            : 'images/mark.png',
            preserveViewport: false
        });
        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow[i].open(map, marker);
                map.setCenter(marker.getPosition());
                map.setZoom(15);
            };
        })(marker, i));
    }
}

/* --------------- alert --------------*/
function alert(mess, title) {
    $("#modal-thanks .modal-heading span").text(title);
    $("#modal-thanks .color-white").text(mess);
    $("#modal-thanks").modal("show");
}

/* --------------- end alert --------------*/

/* --------------- start validation --------------*/
function validate(form) {
    var error_class = "error";
    var norma_class = "pass";
    var item        = form.find("[required]");
    var e           = 0;
    var reg         = undefined;

    function mark(object, expression) {
        if (expression) {
            object.parents('.form-group').addClass(error_class).removeClass(norma_class).find('.error-b').show();
            e++;
        } else
        object.parents('.form-group').addClass(norma_class).removeClass(error_class).find('.error-b').hide();
    }

    form.find("[required]").each(function () {
        switch ($(this).attr("data-validate")) {
            case undefined:
            mark($(this), $.trim($(this).val()).length == 0);
            break;
            case "email":
            reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            mark($(this), !reg.test($.trim($(this).val())));
            break;
            case "phone":
                reg = /^\+38\s?\(0\d{2}\)\s?\d{3}-\d{2}-\d{2}$/;
                mark($(this), !reg.test($.trim($(this).val())));
                break;
            default:
            reg = new RegExp($(this).attr("data-validate"), "g");
            mark($(this), !reg.test($.trim($(this).val())));
            break
        }
    })
    e += form.find("." + error_class).size();
    if (e == 0)
        return true;
    else {
        $('.js_alert_error').show();
        setTimeout(function () {
            $('.js_alert_error').hide();
        }, 4000);
        form.find("." + error_class + ":first").focus();
        return false;
    }
}

/* --------------- end validation --------------*/

/* --------------- cost object --------------*/
function object_cost(curr_min, curr_max, min, max) {
    curr_min = (curr_min != null && curr_min != '') ? parseInt(curr_min) : parseInt(min);
    curr_max = (curr_max != null && curr_max != '') ? parseInt(curr_max) : parseInt(max);
    min      = parseInt(min);
    max      = parseInt(max);
    var _c   = $('[name=course]').val();
    var rent = String($('select[name=type]').val()) === '4';
    function uahRate() {
        var raw = $('[name=course] option:selected').attr('data-course');
        var rate = parseFloat(raw);
        return rate > 5 ? rate : 42;
    }
    function ceilTo(n, step) {
        step = step || 1;
        return Math.ceil(n / step) * step;
    }
    function priceRange(course) {
        var isUah = course == 2 || course === '2' || course === 'UAH';
        var rate = isUah ? uahRate() : 1;
        var bands = rent
            ? [[0, 10], [200, 10], [500, 25], [1000, 100], [5000, 5000], [50000]]
            : [[0, 1000], [25000, 2000], [75000, 5000], [200000, 100000], [5000000, 500000], [10000000]];
        var keys = ['min', '25%', '50%', '75%', '99%', 'max'];
        var round = isUah ? (rent ? 50 : 1000) : 1;
        var range = {};
        bands.forEach(function (band, i) {
            var value = isUah ? ceilTo(band[0] * rate, round) : band[0];
            if (i === bands.length - 1) {
                range[keys[i]] = value;
            } else {
                var step = isUah ? Math.max(round, ceilTo(band[1] * rate, round)) : band[1];
                range[keys[i]] = [value, step];
            }
        });
        return range;
    }
    if ($('#js_nonlinear-cost').length > 0) {
        var nonLinearSlider = document.getElementById('js_nonlinear-cost');
        noUiSlider.create(nonLinearSlider, {
            connect  : true,
            behaviour: 'tap',
            tooltips : true,
            handles  : 1,
            start    : [curr_min, curr_max],
            range    : priceRange(_c),
            slide    : function (event, ui) {
                $('.min-value').text(mi).css({left: $('#slider-container .ui-slider-handle:nth-child(2)').css('left')});
                $('.max-value').text(mx).css({left: $('#slider-container .ui-slider-handle:last-child').css('left')});
            },
            format   : {
                to  : function (value) {
                    var _c_t = $('[name=course]').val();
                    var ua = (document.documentElement.lang || '').toLowerCase().indexOf('ua') === 0;
                    if (_c_t == 2 && value == '25000000') {
                        $("#js_nonlinear-cost .noUi-handle-upper .noUi-tooltip").text(ua ? "більше 25 млн" : "больше 25 млн");
                    }
                    if (_c_t != 2 && value == '1000000') {
                        $("#js_nonlinear-cost .noUi-handle-upper .noUi-tooltip").text(ua ? "більше 1 млн" : "больше 1 млн");
                    }
                    return parseInt(value);
                },
                from: function (value) {
                    return parseInt(value);
                }
            }
        });
        nonLinearSlider.noUiSlider.on('update', function (values, handle) {
            if (!handle)
                $("[name=price_min]").val(values[handle]);
            else
                $("[name=price_max]").val(values[handle]);
        });

        function updateSliderRange(min, max, c) {
            rent = String($('select[name=type]').val()) === '4';
            nonLinearSlider.noUiSlider.updateOptions({
                range: priceRange(c)
            });
            var ends = priceRange(c);
            var top = ends.max;
            nonLinearSlider.noUiSlider.set([min, top]);
        }

        $(document).on('change', 'select[name=type]', function () {
            updateSliderRange(0, max, $('[name=course]').val());
        });

        $(document).on('change', '[name=course]', function () {
            var _c = $(this).val();
            switch (_c) {
                case '2':
                c_max = max * 25;
                break;
                default:
                c_max = max;
                break;
            }
            updateSliderRange(min, c_max, _c);
        });
    }
}

/* --------------- end cost object --------------*/

/* --------------- area object --------------*/
function object_area(curr_min, curr_max, min, max) {
    curr_min            = (curr_min != null && curr_min != '') ? parseInt(curr_min) : parseInt(min);
    curr_max            = (curr_max != null && curr_max != '') ? parseInt(curr_max) : parseInt(max);
    min                 = parseInt(min);
    max                 = parseInt(max);
    var nonLinearSlider = document.getElementById('js_nonlinear-metr');
    noUiSlider.create(nonLinearSlider, {
        connect  : true,
        behaviour: 'tap',
        tooltips : true,
        start    : [curr_min, curr_max],
        step     : 50,
        range    : {
            'min': [min],
            '66%': [300, 50],
            'max': [max, 100]
        },
        format   : {
            to  : function (value) {
                if (value == '1000') {
                    $("#js_nonlinear-metr .noUi-handle-upper .noUi-tooltip").text("больше 1000");
                }
                return parseInt(value);
            },
            from: function (value) {
                return parseInt(value);
            }
        }
    });
    nonLinearSlider.noUiSlider.on('update', function (values, handle) {
        if (!handle)
            $("[name=area_min]").val(Math.round(values[handle]));
        else
            $("[name=area_max]").val(Math.round(values[handle]));
    });
    $("[name='area_min']").val(curr_min);
    $("[name='area_max']").val(curr_max);
}

/* --------------- end area object --------------*/

/* --------------- lot object --------------*/
function object_lot(curr_min, curr_max, min, max) {
    curr_min            = (curr_min != null && curr_min != '') ? parseInt(curr_min) : parseInt(min);
    curr_max            = (curr_max != null && curr_max != '') ? parseInt(curr_max) : parseInt(max);
    min                 = parseInt(min);
    max                 = parseInt(max);
    var nonLinearSlider = document.getElementById('js_nonlinear-lot');
    noUiSlider.create(nonLinearSlider, {
        connect  : true,
        behaviour: 'tap',
        tooltips : true,
        start    : [curr_min, curr_max],
        step     : 50,
        range    : {
            'min': [min, 5],
            'max': [max]
        },
        format   : {
            to  : function (value) {
                if (value == '100') {
                    $("#js_nonlinear-lot .noUi-handle-upper .noUi-tooltip").text("больше 100");
                }
                return parseInt(value);
            },
            from: function (value) {
                return parseInt(value);
            }
        }
    });
    nonLinearSlider.noUiSlider.on('update', function (values, handle) {
        if (!handle)
            $("[name=area_min]").val(Math.round(values[handle]));
        else
            $("[name=area_max]").val(Math.round(values[handle]));
    });
    $("[name='area_min']").val(curr_min);
    $("[name='area_max']").val(curr_max);
}

/* --------------- end lot object --------------*/

/* --------------- lot object --------------*/
function object_time(curr) {
    if (curr == '')
        curr = 1;
    var nonLinearSlider = document.getElementById('js_nonlinear-time');
    noUiSlider.create(nonLinearSlider, {
        behaviour: 'tap',
        tooltips : true,
        start    : [curr],
        range    : {
            'min': [1, 1],
            'max': [4]
        },
        format   : {
            to  : function (value) {
                    if(LANG == 'ua'){
                        if (value == 1)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('тиждень');
                        if (value == 2)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('місяць');
                        if (value == 3)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('рік');
                        if (value == 4)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('більше року');
                    } else {
                        if (value == 1)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('неделя');
                        if (value == 2)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('месяц');
                        if (value == 3)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('год');
                        if (value == 4)
                            $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('больше года');
                    }

                return parseInt(value);
            },
            from: function (value) {
                return parseInt(value);
            }
        }
    });
    nonLinearSlider.noUiSlider.on('update', function (values, handle) {
        if (!handle)
            $("[name=time]").val(Math.round(values[handle]));
        else
            $("[name=time]").val(Math.round(values[handle]));
    });
}

/* --------------- end lot object --------------*/

/* --------------- get cookie --------------*/
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

/* --------------- end get cookie --------------*/

/* --------------- set cookie --------------*/
function setCookie(name, value, options) {
    options     = options || {};
    var expires = options.expires;
    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }
    value             = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;
    updatedCookie += ';path=/';
    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }
    document.cookie = updatedCookie;
}

/* --------------- end set cookie --------------*/

/* --------------- map to one object --------------*/


// запускаем после загрузки DOM
$(function () {
    var $mapWrapper = $('#map-one-object');
    if (!$mapWrapper.length) return;

    $mapWrapper.on('click', '.btn-object', function (e) {
        var href = $(this).attr('href') || '';
        if (/^https?:/i.test(href) || $(this).is('[data-panorama], .js-panorama')) {
            return;
        }
        e.preventDefault();

        var $overlayElems = $mapWrapper.find('.map-one-object, .btn-object');

        // Лёгкий эффект ожидания
        $overlayElems.addClass('is-loading');

        // Инициализация карты
        initObjectMap($mapWrapper[0], function () {

            // 👉 Плавное исчезновение за ~2 секунды
            $overlayElems.fadeOut(2000, function () {
                $(this).remove();
            });

        });
    });
});


$(function () {
    var $mapWrapper = $('#map-search');
    if (!$mapWrapper.length) return;

    $mapWrapper.on('click', '.btn-object', function (e) {
        var href = $(this).attr('href') || '';
        if (/^https?:/i.test(href) || $(this).is('[data-panorama], .js-panorama')) {
            return;
        }
        e.preventDefault();

        var $overlayElems = $mapWrapper.find('.map-one-object, .btn-object');

        // Лёгкий эффект ожидания
        $overlayElems.addClass('is-loading');

        // Инициализация карты
        initializ($mapWrapper[0], function () {

            // 👉 Плавное исчезновение за ~2 секунды
            $overlayElems.fadeOut(2000, function () {
                $(this).remove();
            });

        });
    });
});



function initObjectMap(mapElement, onReady) {
    var $el = $(mapElement);
    var coordStr = $.trim($el.attr('data-coord') || '');
    if (!coordStr) return;
   
    // чтобы не инициализировать повторно
    $el.attr('data-coord', '');

    var parts = coordStr.split(',');
    var lat   = parseFloat(parts[0]);
    var lng   = parseFloat(parts[1]);

    if (isNaN(lat) || isNaN(lng)) return;

    var centerLatLng = new google.maps.LatLng(lat, lng);

    var mapOptions = {
        zoom            : 14,
        scrollwheel     : false,
        draggable       : true,
        disableDefaultUI: false,
        mapTypeId       : google.maps.MapTypeId.ROADMAP,
        center          : centerLatLng,
        styles          : [{
            stylers: [{ hue: "#dce4e9" }]
        }]
    };

    var map = new google.maps.Map(mapElement, mapOptions);

    // 👉 как только все тайлы карты догрузятся
    google.maps.event.addListenerOnce(map, 'tilesloaded', function () {
        if (typeof onReady === 'function') {
            onReady();
        }
    });

    // дальше твоя логика маркера и infowindow:
    var panoramaUrl   = $.trim($el.attr('data-panorama') || '');
    var catUrl        = $.trim($el.attr('data-cat') || '');
    var baths         = $.trim($el.attr('data-bath') || '');
    var beds          = $.trim($el.attr('data-beds') || '');
    var area          = $.trim($el.attr('data-area-map') || '');
    var b             = $.trim($el.attr('data-b') || '');
    var type          = $.trim($el.attr('data-type') || '');

    var panoramaLink = '';
    if (panoramaUrl !== '') {
        panoramaLink = '<a href="' + panoramaUrl + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
    }

    var catLink = '';
    if (b === '1' && catUrl !== '') {
        if (typeof LANG === 'undefined') {
            LANG = 'ru';
        }

        if (LANG === 'ua') {
            if (type === '1')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показати квартири в цьому будинку</a>';
            if (type === '2')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показати будинки в цьому районі</a>';
            if (type === '3')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показати ділянки у цьому районі</a>';
        } else {
            if (type === '1')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показать квартиры в этом доме</a>';
            if (type === '2')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показать дома в этом районе</a>';
            if (type === '3')
                catLink = '<a href="' + catUrl + '" class="btn btn-object btn-objectmap">Показать участки в этом районе</a>';
        }
    }

    var appApartment = '';
    if (type !== '3') {
        appApartment =
            '<li><span class="icon-bath"></span>' + baths + '</li>' +
            '<li><span class="icon-bed"></span>' + beds + '</li>';
    }

    var typeArea = (type === '3') ? ' соток ' : ' м<sup>2</sup> ';
    var title    = $('.js_map_name').text();

    var contentmap =
        '<div class="objectmap">' +
            '<div class="objectmap__head">' + title + '</div>' +
            '<ul class="objectmap__info">' +
                '<li><span class="icon-house"></span>' + area + typeArea + '</li>' +
                appApartment +
            '</ul>' +
            catLink +
            panoramaLink +
        '</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentmap,
        disableAutoPan: true

    });

    var marker = new google.maps.Marker({
        position        : centerLatLng,
        map             : map,
        icon            : '/images/mark.png',
        preserveViewport: false
    });

    google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
        map.setCenter(marker.getPosition());
        map.setZoom(15);
    });
}



// function initial() {
//     var coord = $("#map-one-object").attr("data-coord");
//     if (coord != '') {
//         $("#map-one-object").attr("data-coord", "");
//         coord          = coord.split(',');
//         var mapOptions = {
//             zoom            : 14,
//             scrollwheel     : false,
//             draggable       : true,
//             disableDefaultUI: false,
//             mapTypeId       : google.maps.MapTypeId.ROADMAP,
//             center          : new google.maps.LatLng(coord[0], coord[1]),
//             styles          : [{
//                 "stylers": [
//                 {
//                     "hue": "#dce4e9"
//                 }
//                 ]
//             }]
//         };
//         var mapElement = document.getElementById('map-one-object');
//         var map        = new google.maps.Map(mapElement, mapOptions);
//         var markers    = [{
//             latLng: [coord[0], coord[1]]
//         }];

//         function closeInfoWindows() {
//             for (var i = 0; i < infowindow.length; i++) {
//                 infowindow[i].close();
//             }
//         }

//         var infowindow = [];
//         var panorama   = $("[data-panorama]").attr("data-panorama");
//         if (panorama != '')
//             panorama = '<a href="' + panorama + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
//         var cat             = '';
//         var app_appartament = '';
//         var b               = $("[data-b]").attr("data-b");
//         var type            = $("[data-b]").attr("data-type");
//         if (b == '1') {


//             if(LANG == 'ua'){
//                 if (type == '1')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати квартири в цьому будинку</a>';
//                 if (type == '2')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати будинки в цьому районі</a>';
//                 if (type == '3')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати ділянки у цьому районі</a>';
//             } else {
//                 if (type == '1')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать квартиры в этом доме</a>';
//                 if (type == '2')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать дома в этом районе</a>';
//                 if (type == '3')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать участки в этом районе</a>';
//             }
//         }
//         if (type != '3')
//             app_appartament = '<li><span class="icon-bath"></span>' + $('[data-bath]').attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $('[data-beds]').attr("data-beds") + "</li>";
//         if ($("[data-type]").attr('data-type') == 3)
//             type_area = ' соток ';
//         else
//             type_area = ' м<sup>2</sup> ';
//         var contentmap = '<div class="objectmap">' +
//         '<div class="objectmap__head">' + $(".js_map_name").text() + '</div>' +
//         '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $('[data-area-map]').attr("data-area-map") + type_area +
//         '</li>' +
//         app_appartament + '</ul>' +
//         cat +
//         panorama;
//         for (i in markers) {
//             infowindow[i] = new google.maps.InfoWindow({
//                 content: contentmap
//             });
//             var marker    = new google.maps.Marker({
//                 position        : new google.maps.LatLng(markers[i].latLng[0], markers[i].latLng[1]),
//                 map             : map,
//                 icon            : '/images/mark.png',
//                 preserveViewport: false
//             });
//             google.maps.event.addListener(marker, 'click', (function (marker, i) {
//                 return function () {
//                     closeInfoWindows();
//                     infowindow[i].open(map, marker);
//                     map.setCenter(marker.getPosition());
//                     map.setZoom(15);
//                 };
//             })(marker, i));
//         }
//     }
// }

// function DG_init_object() {
//     let coord = $("#map-one-object").attr("data-coord").replace(/(.*)(\d{2}\.\d{2,7},\s*\d{2}\.\d{2,7})(.*)/g, '$2');
//     coord = coord.split(',');

//     if (coord.length == 2) {
//         DG.then(function () {
//             let map = DG.map('map-one-object', {
//                 center: [coord[0], coord[1]],
//                 zoom: 14
//             });

//             let myIcon = DG.icon({
//                 iconUrl: '/images/mark.png',
//                 iconRetinaUrl: '/images/mark.png',
//                 iconSize: [45, 49],
//                 iconAnchor: [23, 48],
//                 //popupAnchor: [-3, -76],
//             });

//             var panorama   = $("[data-panorama]").attr("data-panorama");
//             if (panorama != '')
//                 panorama = '<a href="' + panorama + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
//             var cat             = '';
//             var app_appartament = '';
//             var b               = $("[data-b]").attr("data-b");
//             var type            = $("[data-b]").attr("data-type");
//             if (b == '1') {
//                  if(LANG == 'ua'){
//                 if (type == '1')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати квартири в цьому будинку</a>';
//                 if (type == '2')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати будинки в цьому районі</a>';
//                 if (type == '3')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показати ділянки у цьому районі</a>';
//             } else {
//                 if (type == '1')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать квартиры в этом доме</a>';
//                 if (type == '2')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать дома в этом районе</a>';
//                 if (type == '3')
//                     cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать участки в этом районе</a>';
//             }
//             }
//             if (type != '3')
//                 app_appartament = '<li><span class="icon-bath"></span>' + $('[data-bath]').attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $('[data-beds]').attr("data-beds") + "</li>";
//             if ($("[data-type]").attr('data-type') == 3)
//                 type_area = ' соток ';
//             else
//                 type_area = ' м<sup>2</sup> ';
//             var contentmap = '<div class="objectmap">' +
//                 '<div class="objectmap__head">' + $(".js_map_name").text() + '</div>' +
//                 '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $('[data-area-map]').attr("data-area-map") + type_area +
//                 '</li>' +
//                 app_appartament + '</ul>' +
//                 cat +
//                 panorama;

//             DG.marker(coord, {icon: myIcon}).addTo(map).bindPopup(contentmap);
//         });
//     }
// }

/* --------------- end map to one object --------------*/

function DG_init_search() {
	DG.then(function() {
	    let map = DG.map('map-search', {
	        center: [50.00492056, 36.23600491],
	        zoom: 13
	    });

        map.status_popup = 1;

        let myIcon = DG.icon({
            iconUrl: '/images/mark.png',
            iconRetinaUrl: '/images/mark.png',
            iconSize: [45, 49],
            iconAnchor: [23, 48],
            //popupAnchor: [-3, -76],
        });

        map.on('popupopen', function(e) {
            map.status_popup = 0;
        });

        map.on('popupclose', function(e) {
            map.status_popup = 1;
        });

		$("[data-coord]").each(function () {
        	coord = $(this).attr("data-coord").replace(/(.*)(\d{2}\.\d{2,7},\s*\d{2}\.\d{2,7})(.*)/g, '$2');
			coord = coord.split(',');

	        if (coord.length == 2) {
	            if ($(this).attr('data-type') == 3) {
	                type_area = ' соток ';
	            } else {
	                type_area = ' м<sup>2</sup> ';
	            }
                 if(LANG == 'ua'){
                    var more = "Докладніше";
                 } else {
                    var more = "Подробнее";
                 }
	            let contentmap = '<div class="objectmap">' +
	            '<span class="objectmap__head">' + $(this).attr("data-name") + '</span>' +
	            '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $(this).attr("data-area-map") + type_area +
	            '</li>' + ($(this).attr("data-object-type") != 1 ? '<li><span class="icon-bath"></span>' + $(this).attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $(this).attr("data-beds") +
	                '</li>' : '') + '</ul>' +
	            '<div class="objectmap__cost">' + $(this).attr("data-price-map") + '</div>' +
	            '<a href="' + $(this).attr("data-url") + '" class="btn btn-object btn-objectmap">'+more+'</a>';

	            let marker = DG.marker(coord, {icon: myIcon}).addTo(map).bindPopup(contentmap);

                marker.on('mouseover', function(e) {
                    if (map.status_popup) {
                        marker.openPopup();
                        map.setView([e.latlng.lat, e.latlng.lng]);
                    }
                });

                // marker.on('mouseover', function(e) {
                //
                //     console.log('mouseover');
                // });

                marker.on('click', function(e) {
                    marker.openPopup();
                    map.setView([e.latlng.lat, e.latlng.lng]);
                });
	        }
        });
	});
}

/* --------------- map to list objects --------------*/
// function initializ() { // Поиск

//     var mapOptions    = {
//         zoom            : 13,
//         scrollwheel     : false,
//         draggable       : true,
//         disableDefaultUI: false,
//         mapTypeId       : google.maps.MapTypeId.ROADMAP,
//         center          : new google.maps.LatLng(50.00492056, 36.23600491),
//         styles          : [{
//             "stylers": [
//             {
//                 "hue": "#dce4e9"
//             }
//             ]
//         }]
//     };
//     var mapElement    = document.getElementById('map-search');
//     var map           = new google.maps.Map(mapElement, mapOptions);
//     var markersBounds = new google.maps.LatLngBounds();
//     var infowindow    = [];

//     function closeInfoWindows() {
//         for (var j = 0; j < infowindow.length; j++) {
//             infowindow[j].close();
//         }
//     }

//     var i            = 0;
//     var lastPosition = '';
//     var coordsArray  = [];
//     $("[data-coord]").each(function () {
//         coord = $(this).attr("data-coord").replace(/(.*)(\d{2}\.\d{2,7},\s*\d{2}\.\d{2,7})(.*)/g, '$2');
//         // var exists = false;
//         // coordsArray.map((curr)=>{
//         //     if(curr == coord)
//         //         exists = true;
//         // })
//         // if(!exists){}
//         console.log(coord);
//         coordsArray.push(coord);
//         coord = coord.split(',');
//         if (coord.length == 2) {
//             if ($(this).attr('data-type') == 3)
//                 type_area = ' соток ';
//             else
//                 type_area = ' м<sup>2</sup> ';
//             if(LANG == 'ua'){
//                     var more = "Докладніше";
//                  } else {
//                     var more = "Подробнее";
//                  }
//             var contentmap = '<div class="objectmap">' +
//             '<span class="objectmap__head">' + $(this).attr("data-name") + '</span>' +
//             '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $(this).attr("data-area-map") + type_area +
//             '</li>' + ($(this).attr("data-object-type") != 1 ? '<li><span class="icon-bath"></span>' + $(this).attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $(this).attr("data-beds") +
//                 '</li>' : '') + '</ul>' +
//             '<div class="objectmap__cost">' + $(this).attr("data-price-map") + '</div>' +
//             '<a href="' + $(this).attr("data-url") + '" class="btn btn-object btn-objectmap">'+more+'</a>';
//             var m          = new google.maps.InfoWindow({
//                 content: contentmap
//             });

//             infowindow.push(m);
//             var marker = new google.maps.Marker({
//                 position        : new google.maps.LatLng(coord[0], coord[1]),
//                 map             : map,
//                 icon            : '/images/mark.png',
//                 preserveViewport: false
//             });
//             markersBounds.extend(marker.getPosition());
//             lastPosition = marker.getPosition();
//             // map.setCenter(marker.getPosition());
//             google.maps.event.addListener(marker, 'click', (function (marker, i) {
//                 return function () {
//                     closeInfoWindows();
//                     infowindow[i].open(map, marker);
//                     //map.setCenter(marker.getPosition());
//                     //map.setZoom(15);
//                 };
//             })(marker, i));
//             i++;
//         }
//     });
//     if (i > 1)
//         map.setCenter(markersBounds.getCenter(), map.fitBounds(markersBounds));
//     else
//         map.setCenter(lastPosition);
// }

function initializ() { // Поиск (Google Maps)
  if (!window.google || !google.maps) {
    console.warn('Google Maps API is not loaded');
    return;
  }

  const mapElement = document.getElementById('map-search');
  if (!mapElement) {
    console.warn('#map-search not found');
    return;
  }

  // ===== Helpers =====
  const escapeHtml = (s) =>
    String(s ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');

  const parseCoordFromAttr = (raw) => {
    // Вытащим из строки именно "xx.xxxxx, yy.yyyyy" (как у вас было), но аккуратно.
    // Поддержим знак минус и пробелы.
    const str = String(raw ?? '');
    const m = str.match(/(-?\d{1,3}\.\d{2,7})\s*,\s*(-?\d{1,3}\.\d{2,7})/);
    if (!m) return null;

    const lat = parseFloat(m[1]);
    const lng = parseFloat(m[2]);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return null;

    return { lat, lng, key: `${lat.toFixed(7)},${lng.toFixed(7)}` };
  };

  // ===== Map options =====
  const map = new google.maps.Map(mapElement, {
    zoom: 13,
    center: { lat: 50.00492056, lng: 36.23600491 },
    scrollwheel: false,
    draggable: true,
    disableDefaultUI: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    styles: [{ stylers: [{ hue: '#dce4e9' }] }],
  });

  const bounds = new google.maps.LatLngBounds();
  const infoWindow = new google.maps.InfoWindow(); // один на все маркеры
  const markers = [];

  const moreText = (typeof LANG !== 'undefined' && LANG === 'ua') ? 'Докладніше' : 'Подробнее';

  // Если хотите не ставить несколько маркеров в одной точке:
  const DEDUPE_COORDS = false;
  const usedCoords = new Set();

  // ===== Markers build =====
  const $items = window.jQuery ? jQuery('[data-coord]') : null;
  if (!$items || !$items.length) {
    // Нет данных — просто оставляем карту в дефолтном центре
    return;
  }

  $items.each(function () {
    const $el = jQuery(this);

    const rawCoord = $el.attr('data-coord');
    const parsed = parseCoordFromAttr(rawCoord);
    if(rawCoord == ',') return;
    if (!parsed) return;

    if (DEDUPE_COORDS) {
      if (usedCoords.has(parsed.key)) return;
      usedCoords.add(parsed.key);
    }

    const typeArea = (String($el.attr('data-type')) === '3') ? ' соток ' : ' м<sup>2</sup> ';

    const name = escapeHtml($el.attr('data-name'));
    const area = escapeHtml($el.attr('data-area-map'));
    const objectType = String($el.attr('data-object-type'));
    const bath = escapeHtml($el.attr('data-bath'));
    const beds = escapeHtml($el.attr('data-beds'));
    const price = escapeHtml($el.attr('data-price-map'));
    const url = escapeHtml($el.attr('data-url'));

    const extraRooms =
      (objectType !== '1')
        ? `<li><span class="icon-bath"></span>${bath}</li>
           <li><span class="icon-bed"></span>${beds}</li>`
        : '';

    const contentHtml =
      `<div class="objectmap">
         <span class="objectmap__head">${name}</span>
         <ul class="objectmap__info">
           <li><span class="icon-house"></span>${area}${typeArea}</li>
           ${extraRooms}
         </ul>
         <div class="objectmap__cost">${price}</div>
         <a href="${url}" class="btn btn-object btn-objectmap">${moreText}</a>
       </div>`;

    const position = new google.maps.LatLng(parsed.lat, parsed.lng);

    const marker = new google.maps.Marker({
      position,
      map,
      icon: '/images/mark.png',
      // clickable: true, // по умолчанию true
    });

    marker.addListener('click', () => {
      infoWindow.close();
      infoWindow.setContent(contentHtml);
      infoWindow.open({ map, anchor: marker });
    });

    markers.push(marker);
    bounds.extend(position);
  });

  // ===== Fit / Center =====
  if (markers.length === 0) {
    // Нечего показывать
    map.setCenter({ lat: 50.00492056, lng: 36.23600491 });
    map.setZoom(13);
    return;
  }

  if (markers.length === 1) {
    map.setCenter(markers[0].getPosition());
    map.setZoom(13); // или 15 если хочешь ближе
  } else {
    map.fitBounds(bounds);
    // опционально: ограничить максимальный зум после fitBounds
    google.maps.event.addListenerOnce(map, 'idle', () => {
      const maxZoom = 16;
      if (map.getZoom() > maxZoom) map.setZoom(maxZoom);
    });
  }
}

/* --------------- end map to list objects --------------*/

/* --------------- map to list category --------------*/
// function init() {
//     var mapOptions = {
//         zoom            : 13,
//         scrollwheel     : false,
//         draggable       : true,
//         disableDefaultUI: false,
//         mapTypeId       : google.maps.MapTypeId.ROADMAP,
//         center          : new google.maps.LatLng(50.00492056, 36.23600491),
//         styles          : [{
//             "stylers": [{
//                 "hue": "#dce4e9"
//             }]
//         }]
//     };
//     var mapElement = document.getElementById('map-objects');
//     var map        = new google.maps.Map(mapElement, mapOptions);
//     var infowindow = [];

//     function closeInfoWindows() {
//         for (var j = 0; j < infowindow.length; j++) {
//             infowindow[j].close();
//         }
//     }

//     var i = 0;
//     $("[data-coord]").each(function () {
//         coord = $(this).attr("data-coord");
//          console.log(coord);
//         if (coord != '' && coord != ',') {
//             coord        = coord.split(',');
//             var panorama = $(this).attr("data-panorama");
//             if(LANG == 'ua'){
//                 var show = "Показати квартири в цьому будинку";
//             } else {
//                 var show = "Показать квартиры в этом доме";
//             }
//             if (panorama != '')
//                 panorama = '<a href="' + panorama + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
//             var contentmap = '<div class="objectmap">' +
//             '<div class="objectmap__head">' + $(this).find('.objects-item__title').text() + '</div>' +
//             '<a href="' + $(this).attr("data-cat") + '" class="btn btn-object btn-objectmap">'+show+'</a>' +
//             panorama;
//             var m          = new google.maps.InfoWindow({
//                 content: contentmap
//             });
//             infowindow.push(m);
//             var marker = new google.maps.Marker({
//                 position        : new google.maps.LatLng(coord[0], coord[1]),
//                 map             : map,
//                 icon            : '/images/mark.png',
//                 preserveViewport: false
//             });
//             map.setCenter(marker.getPosition());
//             google.maps.event.addListener(marker, 'click', (function (marker, i) {
//                 return function () {
//                     closeInfoWindows();
//                     infowindow[i].open(map, marker);
//                     map.setCenter(marker.getPosition());
//                     map.setZoom(15);
//                 };
//             })(marker, i));
//             i++;
//         }
//     });
// }
function init() {
  'use strict';

  const mapElement = document.getElementById('map-objects');
  if (!mapElement) return;

  const defaultCenter = { lat: 50.00492056, lng: 36.23600491 };

  const map = new google.maps.Map(mapElement, {
    zoom: 13,
    center: defaultCenter,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false,
    draggable: true,
    disableDefaultUI: false,
    styles: [{ stylers: [{ hue: '#dce4e9' }] }],
  });

  // Один InfoWindow на все маркеры
  const infoWindow = new google.maps.InfoWindow();

  const bounds = new google.maps.LatLngBounds();
  const markers = [];

  const showText =
    (typeof LANG !== 'undefined' && LANG === 'ua')
      ? 'Показати квартири в цьому будинку'
      : 'Показать квартиры в этом доме';

  // Помощник: безопасный парсер "lat,lng"
  function parseCoord(coordStr) {
    if (!coordStr || coordStr === ',' ) return null;

    const parts = coordStr.split(',').map(s => s.trim());
    if (parts.length < 2) return null;

    const lat = Number(parts[0]);
    const lng = Number(parts[1]);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
    if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return null;

    return { lat, lng };
  }

  // Помощник: экранирование текста (если title берется из DOM — ок, но пусть будет правильно)
  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  // Создание контента инфоокна
  function buildInfoContent($el) {
    const title = $el.find('.objects-item__title').text().trim();
    const url = $el.attr('data-cat') || '#';
    const panoramaUrl = ($el.attr('data-panorama') || '').trim();

    const panoramaBtn = panoramaUrl
      ? `<a href="${panoramaUrl}" class="btn btn-object btn-objectmap" target="_blank" rel="noopener">Панорама</a>`
      : '';

    return `
      <div class="objectmap">
        <div class="objectmap__head">${escapeHtml(title)}</div>
        <a href="${url}" class="btn btn-object btn-objectmap">${showText}</a>
        ${panoramaBtn}
      </div>
    `;
  }

  // Сбор маркеров
  $('[data-coord]').each(function () {
    const $el = $(this);
    const coordStr = ($el.attr('data-coord') || '').trim();
    if(coordStr == ',') return;
    const coord = parseCoord(coordStr);

    if (!coord) return;

    const marker = new google.maps.Marker({
      position: coord,
      map,
      icon: '/images/mark.png',
      // optimize: true, // можно включить если маркеров много
    });

    markers.push(marker);
    bounds.extend(marker.getPosition());

    marker.addListener('click', () => {
      infoWindow.close();
      infoWindow.setContent(buildInfoContent($el));
      infoWindow.open({ map, anchor: marker });

      // Если хочешь — фокус/зум при клике:
      map.panTo(marker.getPosition());
      map.setZoom(Math.max(map.getZoom(), 15));
    });
  });

  // Если есть маркеры — подгоняем видимую область
  if (markers.length === 1) {
    map.setCenter(markers[0].getPosition());
    map.setZoom(15);
  } else if (markers.length > 1) {
    map.fitBounds(bounds);

    // Небольшой лимит чтобы fitBounds не улетал слишком близко
    google.maps.event.addListenerOnce(map, 'idle', () => {
      const z = map.getZoom();
      if (z > 16) map.setZoom(16);
    });
  }

  // Закрывать инфоокно по клику на карту
  map.addListener('click', () => infoWindow.close());

  // Если маркеров много — можно включить кластеризацию (нужно подключить библиотеку MarkerClusterer)
  // new markerClusterer.MarkerClusterer({ map, markers });
}

/* --------------- end map to list category --------------*/
/* --------------- change lang --------------*/
if (top.location != self.location)
    top.location = self.location;
window['_tipoff'] = function () {
};
window['_tipon']  = function (a) {
};

function doGTranslate(lang_pair) {
    if (lang_pair && lang_pair.value) {
        lang_pair = lang_pair.value;
    }
    if (!lang_pair) return;

    var parts   = lang_pair.split('|');
    var rawFrom = parts[0];
    var rawTo   = parts[1];

    // Маппинг наших кодов -> коды Google
    var langMap = {
        'ua': 'uk'
    };
    var from = langMap[rawFrom] || rawFrom;
    var to   = langMap[rawTo]   || rawTo;

    // Нормализуем hostname (без www)
    var host = location.hostname.replace(/^www\./, '');

    // Домены оригинального сайта
    var originalHosts = ['toprealtor.com.ua', 'new.toprealtor.com.ua'];
    var isOriginal = originalHosts.indexOf(host) !== -1;

    // Если выбрали тот же язык на оригинальном сайте — ничего не делаем
    if (isOriginal && from === to) {
        return;
    }

    // Базовый URL translate
    var baseUrl = 'https://translate.google.com/translate'
                + '?hl=' + from
                + '&sl=' + from
                + '&tl=' + to
                + '&u=';

    // Если мы на переводе и выбрали исходный язык — вернуть на оригинальный URL
    if (!isOriginal && from === to) {
        var origBack = getOriginalUrlFromTranslate();
        location.href = origBack;
        return;
    }

    if (isOriginal) {
        // Оригинальный сайт -> отправляем текущий URL в переводчик
        location.href = baseUrl + encodeURIComponent(location.href);
    } else {
        // Уже на зеркале translate.goog -> достаём оригинальный URL
        var originalUrl = getOriginalUrlFromTranslate();
        location.href = baseUrl + encodeURIComponent(originalUrl);
    }
}

// Вытаскиваем оригинальный URL, если мы на *.translate.goog
function getOriginalUrlFromTranslate() {
    try {
        var url = new URL(window.location.href);

        // Если это не translate.goog – просто вернём текущий URL
        if (!/\.translate\.goog$/.test(url.hostname)) {
            return url.toString();
        }

        // hostname вида new-toprealtor-com-ua.translate.goog
        var encoded = url.hostname.split('.translate.goog')[0]; // new-toprealtor-com-ua
        var parts = encoded.split('-');                         // ['new','toprealtor','com','ua']

        if (parts.length >= 3) {
            var tld1 = parts.pop(); // 'ua'
            var tld2 = parts.pop(); // 'com'
            var sub  = parts.join('.'); // 'new.toprealtor'
            url.hostname = sub + '.' + tld2 + '.' + tld1; // new.toprealtor.com.ua
        }

        // Убираем служебные параметры _x_tr_*
        var params = new URLSearchParams(url.search);
        var toDelete = [];
        params.forEach(function (val, key) {
            if (key.indexOf('_x_tr_') === 0) {
                toDelete.push(key);
            }
        });
        toDelete.forEach(function (k) { params.delete(k); });
        url.search = params.toString() ? '?' + params.toString() : '';

        return url.toString();
    } catch (e) {
        // На всякий случай fallback
        return window.location.href;
    }
}

function gfg(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regexS  = "[\\?&]" + name + "=([^&#]*)";
    var regex   = new RegExp(regexS);
    var results = regex.exec(location.href);
    if (results == null) return '';
    return results[1];
}

/* --------------- end change lang --------------*/
(function () {
    // Ничего не делаем, если это не translate.goog
    if (!/\.translate\.goog$/.test(location.hostname)) {
        return;
    }

    // Восстановить оригинальный домен из new-toprealtor-com-ua.translate.goog
    function getOriginalHost() {
        var h = location.hostname.split('.translate.goog')[0]; // new-toprealtor-com-ua
        var parts = h.split('-');                              // ['new','toprealtor','com','ua']
        if (parts.length < 3) return h;                        // на всякий случай

        var tld1 = parts.pop(); // ua
        var tld2 = parts.pop(); // com
        var sub  = parts.join('.'); // new.toprealtor
        return sub + '.' + tld2 + '.' + tld1; // new.toprealtor.com.ua
    }

    var originalHost = getOriginalHost();
    var proto = location.protocol; // https:

    // RUS (корень)
    var ruLink = document.querySelector('a.lang-ru');
    if (ruLink) {
        ruLink.href = proto + '//' + originalHost + '/';
    }

    // UA (/ua/)
    var uaLink = document.querySelector('a.lang-ua');
    if (uaLink) {
        uaLink.href = proto + '//' + originalHost + '/ua/';
    }
})();


// Определяем текущий язык интерфейса (RUS, UKR, FRA, POL, GER)
function getCurrentLangCode() {
    var host = location.hostname.replace(/^www\./, '');
    var originalHosts = ['toprealtor.com.ua', 'new.toprealtor.com.ua'];
    var isOriginal = originalHosts.indexOf(host) !== -1;

    if (isOriginal) {
        // На оригинальном сайте: /ua/... → UKR, иначе RUS
        return location.pathname.indexOf('/ua/') === 0 ? 'UKR' : 'RUS';
    }

    // На переводе через *.translate.goog
    var params = new URLSearchParams(location.search);
    var tl = params.get('_x_tr_tl') || params.get('tl'); // fr, pl, de, uk, ru и т.п.
    if (!tl) return null;

    switch (tl) {
        case 'uk': return 'UKR';
        case 'ru': return 'RUS';
        case 'fr': return 'FRA';
        case 'pl': return 'POL';
        case 'de': return 'GER';
        case 'en': return 'ENG';
        default:   return null;
    }
}

// Подсветка активного языка и обновление текста в .js_language
(function () {
    var current = getCurrentLangCode();
    if (!current) return;

    // 1) Подсветить пункт в выпадающем списке
    var links = document.querySelectorAll('.languages_list a[data-lang]');
    links.forEach(function (link) {
        if (link.getAttribute('data-lang') === current) {
            link.classList.add('languages-box--active');
        } else {
            link.classList.remove('languages-box--active');
        }
    });

    // 2) Обновить подпись в верхней кнопке (js_language)
    var labelSpan = document.querySelector('.js_language > span');
    if (labelSpan) {
        labelSpan.textContent = current;
    }
})();


// if ($.browser.mozilla) {
//     if ($.browser.version < '35') {
//         alert('Ваш браузер устарел, пожалуйста обновите его').modal('show');
//     }
// }
// if ($.browser.opera) {
//     if ($.browser.version < '23') {
//         alert('Ваш браузер устарел, пожалуйста обновите его').modal('show');
//     }
// }
// if ($.browser.safari) {
//     if ($.browser.version == '5.0') {
//         alert('Ваш браузер устарел, пожалуйста обновите его').modal('show');
//     }
// }

// текст обьекта в модалку
$('.js__placeholder').on('click', function () {
 var text =  $('.object__name--height').text().replace(/\s+/g, " ");
 var code = $(".nov-right--object").find('.object-code').text();

 if(LANG == 'ua'){
    var part1 = "Мене зацікавив об'єкт";
    var part2 = "Зв'яжіться зі мною для отримання більш детальної інформації";
} else {
    var part1 = "Меня заинтересовал объект";
    var part2 = "Свяжитесь со мной для получения более подробной информации";
}
 var placeholder = part1+' ' + "(" + text + ' ,' + code + ")" + '\n'+part2;
 // var text1 = " Свяжитесь со мной для получения более подробной информации"
    console.log(text);
    $("#request").find("textarea[name='agent[message]']").attr("placeholder",placeholder);
	$("#request").find("textarea[name='agent[message]']").val(placeholder);
    $(".comments").on('focus', function () {
            $("textarea").attr('placeholder', '');
    });
    $('.comments').blur(function() {
        $("textarea[name='agent[message]']").attr("placeholder", placeholder);
        $("textarea[name='agent[message]']").val(placeholder);
    });
});


$('.js-openpl').on('click', function () {
    var text =  $(this).closest('.flat-item').find('p:first').text().replace(/\s+/g, " ");
    var code = $(this).closest('.flat-item').find('.object-code').text().replace(/\s+/g, " ");
     if(LANG == 'ua'){
        var part1 = "Мене зацікавив об'єкт";
        var part2 = "Зв'яжіться зі мною для отримання більш детальної інформації";
    } else {
        var part1 = "Меня заинтересовал объект";
        var part2 = "Свяжитесь со мной для получения более подробной информации";
    }
    var placeholder = part1+' ' + "(" + text + ' ,' + code + ")" + '\n'+part2;
    // var text1 = " Свяжитесь со мной для получения более подробной информации"
    console.log(text);
    console.log(code);
    $("#request").find("textarea").attr("placeholder",placeholder);
	$("#request").find("textarea").val(placeholder);
    $(".comments").on('focus', function () {
        $("textarea").attr('placeholder', '');
    });
    $('.comments').blur(function() {
        $("textarea").attr("placeholder", placeholder);
        $("textarea").val( placeholder);
    });
});

$(function () {
    var $panel = $('#collapseFloor').closest('.panel');
    var $body  = $('#collapseFloor .panel-body');

    if (!$body.length) return;

    var text = $.trim($body.text().replace(/\s+/g, ''));

    if (text.length < 4) {
        $panel.hide();
    }
});
/* TR_HOME_JS_START */
/* Visual hooks for the homepage layer. Old selectors stay in place. */
(function () {
    if (/\/o-nas\/?/.test(location.pathname)) document.documentElement.classList.add('tr-about-soon');
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    ready(function () {
        var header = document.getElementById('header');
        var spacer = document.querySelector('.headerh');

        function syncHeader() {
            if (!header || !spacer) return;
            spacer.style.height = header.offsetHeight + 'px';
        }

        syncHeader();
        window.addEventListener('resize', syncHeader);

        if (window.jQuery) {
            window.jQuery('.js_owl-carousel-head, .js_owl-carousel-head__mob').each(function () {
                var $el = window.jQuery(this);
                if ($el.data('owl.carousel')) {
                    $el.trigger('destroy.owl.carousel');
                }
                $el.removeClass('owl-hidden owl-loaded owl-carousel owl-theme');
                this.style.setProperty('display', 'block', 'important');
            });
        }
        var motto = document.querySelector('.header-slider');
        if (motto) {
            motto.classList.remove('owl-hidden', 'owl-loaded', 'owl-carousel', 'owl-theme');
            motto.style.setProperty('display', 'block', 'important');
        }

        var isUa = (document.documentElement.getAttribute('lang') || '').toLowerCase().indexOf('ua') === 0;
        function t(ru, ua) { return isUa ? ua : ru; }

        function formatPhone(text) {
            var digits = (text || '').replace(/\D/g, '');
            if (digits.indexOf('380') === 0 && digits.length >= 12) {
                return '+38 ' + digits.slice(2, 5) + ' ' + digits.slice(5, 8) + ' ' + digits.slice(8, 10) + ' ' + digits.slice(10, 12);
            }
            return (text || '').replace(/\s+/g, ' ').trim();
        }

        var phoneLink = document.querySelector('.ft-phone a, a[href^="tel:"]');
        var phoneHost = document.querySelector('.bottom-header__right');
        if (phoneLink && phoneHost) {
            var phoneText = formatPhone(phoneLink.textContent);
            if (phoneText) phoneHost.setAttribute('data-tr-phone', phoneText);
        }
        document.querySelectorAll('.ft-phone a').forEach(function (a) {
            Array.prototype.forEach.call(a.childNodes, function (node) {
                if (node.nodeType === 3 && /\d{6,}/.test(node.nodeValue)) {
                    node.nodeValue = formatPhone(node.nodeValue);
                }
            });
        });

        /* Hero: slide counter and pause button next to the arrows. */
        function setupHero() {
            var heroNav = document.querySelector('#slider1 .owl-nav');
            var $hero = window.jQuery && window.jQuery('#slider1 .sld');
            if (!heroNav || !$hero || !$hero.length) {
                if (document.getElementById('slider1')) window.setTimeout(setupHero, 250);
                return;
            }
            if (heroNav.querySelector('.tr-slide-count')) return;
            var count = document.createElement('span');
            count.className = 'tr-slide-count';
            heroNav.insertBefore(count, heroNav.firstChild);
            var pause = document.createElement('div');
            pause.className = 'tr-slide-pause';
            pause.setAttribute('role', 'button');
            pause.setAttribute('aria-label', t('Остановить смену слайдов', 'Зупинити зміну слайдів'));
            heroNav.appendChild(pause);

            function pad(n) { return (n < 10 ? '0' : '') + n; }
            function updateCount() {
                var owl = $hero.data('owl.carousel');
                var total = $hero.find('.owl-item:not(.cloned)').length;
                var current = 0;
                if (owl && typeof owl.relative === 'function' && typeof owl.current === 'function') {
                    current = owl.relative(owl.current());
                } else {
                    var items = $hero.find('.owl-item:not(.cloned)').toArray();
                    current = Math.max(0, items.indexOf($hero.find('.owl-item.active')[0]));
                }
                if (!total) return;
                var index = ((current % total) + total) % total;
                count.textContent = pad(index + 1) + ' / ' + pad(total);
            }
            updateCount();
            $hero.on('changed.owl.carousel translated.owl.carousel', function () {
                window.setTimeout(updateCount, 0);
            });
            document.addEventListener('visibilitychange', function () {
                $hero.trigger(document.hidden ? 'stop.owl.autoplay' : 'play.owl.autoplay');
            });

            var paused = false;
            pause.addEventListener('click', function () {
                paused = !paused;
                pause.classList.toggle('is-paused', paused);
                pause.setAttribute('aria-label', paused ? t('Продолжить смену слайдов', 'Продовжити зміну слайдів') : t('Остановить смену слайдов', 'Зупинити зміну слайдів'));
                $hero.trigger(paused ? 'stop.owl.autoplay' : 'play.owl.autoplay');
            });
        }
        setupHero();

        var about = document.querySelector('.about-info');
        if (about && !about.querySelector('.tr-about-copy')) {
            var copy = document.createElement('div');
            copy.className = 'tr-about-copy';
            Array.prototype.slice.call(about.children).forEach(function (el) {
                if (el.classList.contains('about-info__title') || el.tagName === 'H1') return;
                copy.appendChild(el);
            });
            about.appendChild(copy);
            placeAboutLead(copy);
            if (document.getElementById('slider1') && !about.querySelector('.tr-about-more')) {
                var more = document.createElement('button');
                more.type = 'button';
                more.className = 'tr-about-more';
                more.textContent = t('Читать далее', 'Читати далі');
                more.setAttribute('aria-expanded', 'false');
                more.addEventListener('click', function () {
                    var open = copy.classList.toggle('is-open');
                    more.classList.toggle('is-open', open);
                    more.setAttribute('aria-expanded', open ? 'true' : 'false');
                    more.textContent = open ? t('Свернуть', 'Згорнути') : t('Читать далее', 'Читати далі');
                });
                copy.parentNode.insertBefore(more, copy.nextSibling);
            }
        } else if (about && document.getElementById('slider1') && about.querySelector('.tr-about-copy') && !about.querySelector('.tr-about-more')) {
            var existingCopy = about.querySelector('.tr-about-copy');
            placeAboutLead(existingCopy);
            var moreExisting = document.createElement('button');
            moreExisting.type = 'button';
            moreExisting.className = 'tr-about-more';
            moreExisting.textContent = t('Читать далее', 'Читати далі');
            moreExisting.setAttribute('aria-expanded', 'false');
            moreExisting.addEventListener('click', function () {
                var open = existingCopy.classList.toggle('is-open');
                moreExisting.classList.toggle('is-open', open);
                moreExisting.setAttribute('aria-expanded', open ? 'true' : 'false');
                moreExisting.textContent = open ? t('Свернуть', 'Згорнути') : t('Читать далее', 'Читати далі');
            });
            existingCopy.parentNode.insertBefore(moreExisting, existingCopy.nextSibling);
        }

        function placeAboutLead(copy) {
            if (!copy || copy.querySelector('.tr-about-lead')) return;
            var lead = document.createElement('div');
            lead.className = 'tr-about-lead';
            lead.innerHTML = isUa
                ? '<h3>Нерухомість у Харкові</h3><p>Квартири й будинки в найкращих районах Харкова, новобудови європейського рівня та житло в історичному центрі міста. TopRealtor працює на українському ринку з 1992 року й спеціалізується на елітній нерухомості.</p><p>Купівля житла — відповідальний крок. Досвід нашої команди допомагає зробити цей процес зрозумілим, приємним і надійним.</p><h3>Індивідуальний підхід</h3><p>Підбираємо об’єкти під конкретні побажання клієнта: квартири первинного й вторинного ринку, будинки преміум-класу та ділянки під житлову й комерційну забудову.</p><h3>Співпраця з TopRealtor</h3><p>База об’єктів регулярно оновлюється. Консультуємо клієнтів від першої заявки до новосілля, організовуємо перегляди та супроводжуємо підготовку до угоди.</p>'
                : '<h3>Недвижимость в Харькове</h3><p>Квартиры и дома в лучших районах Харькова, новостройки европейского уровня и жильё в историческом центре. TopRealtor работает на украинском рынке с 1992 года и специализируется на элитной недвижимости.</p><p>Покупка жилья — ответственный шаг. Опыт команды помогает сделать этот процесс понятным, приятным и надёжным.</p><h3>Индивидуальный подход</h3><p>Подбираем объекты под конкретные пожелания клиента: квартиры первичного и вторичного рынка, дома премиум-класса и участки под жилую и коммерческую застройку.</p><h3>Сотрудничество с TopRealtor</h3><p>База объектов регулярно обновляется. Консультируем клиентов от первой заявки до новоселья, организуем просмотры и сопровождаем подготовку к сделке.</p>';
            copy.insertBefore(lead, copy.firstChild);
            Array.prototype.forEach.call(copy.children, function (el) {
                if (el !== lead) el.classList.add('tr-about-rest');
            });
        }

        var why = document.querySelector('.why');
        if (why) why.classList.add('tr-benefits');
        document.querySelectorAll('.why-item').forEach(function (el) {
            el.classList.add('tr-benefit');
        });
        document.querySelectorAll('.how-item').forEach(function (el) {
            el.classList.add('tr-step');
        });
        var how = document.querySelector('.how');
        if (how) how.classList.add('tr-steps');
        var stepLabels = isUa ? [
            'Заявка',
            'Підбір об’єкта',
            'Договір на обслуговування',
            'Перегляд об’єкта',
            'Перевірка документів',
            'Попередній договір',
            'Підготовка документів до угоди',
            'Оформлення купівлі-продажу'
        ] : [
            'Заявка',
            'Подбор объекта',
            'Договор на обслуживание',
            'Просмотр объекта',
            'Проверка документов',
            'Предварительный договор',
            'Подготовка документов к сделке',
            'Оформление купли-продажи'
        ];
        document.querySelectorAll('.how-item').forEach(function (el, index) {
            var label = el.querySelector('p') || el;
            if (stepLabels[index]) label.textContent = stepLabels[index];
        });
        document.querySelectorAll('.inform-item').forEach(function (el) {
            el.classList.add('tr-info');
        });
        /* Cards: title + compact features like the mockup; extra CMS copy stays hidden. */
        document.querySelectorAll('.slidernov-item').forEach(function (el) {
            el.classList.add('tr-card');
            var right = el.querySelector('.nov-right');
            var features = el.querySelector('.nov-information__mob');
            var price = right && right.querySelector('.nov-right__price');
            var scroll = right && right.querySelector('.object-scroll, .mCustomScrollbar');
            if (right && scroll && !right.querySelector('.tr-card-title')) {
                var anchor = scroll;
                while (anchor.parentNode && anchor.parentNode !== right) anchor = anchor.parentNode;
                var raw = (scroll.textContent || '').replace(/\s+/g, ' ').trim();
                var codeM = raw.match(/(?:код(?:\s+об['’ʼ]?єкта)?|code)\s*[:№]?\s*(\d{4,})/i) || raw.match(/\b(\d{5,})\b/);
                var firstP = scroll.querySelector('p');
                var titleText = firstP ? firstP.textContent.replace(/\s+/g, ' ').trim() : '';
                if (!titleText || /площа|код/i.test(titleText)) {
                    var named = el.closest('.flat-item');
                    var fromData = named && named.getAttribute('data-name');
                    var fromImg = (el.querySelector('img') || {}).alt || '';
                    titleText = (fromData || fromImg.replace(/\s*\|\s*.*$/, '') || titleText || '').replace(/\s+/g, ' ').trim();
                }
                if (codeM) {
                    var code = document.createElement('div');
                    code.className = 'tr-card-code';
                    code.textContent = t('КОД ', 'КОД ') + codeM[1];
                    right.insertBefore(code, anchor);
                }
                if (titleText) {
                    var title = document.createElement('h3');
                    title.className = 'tr-card-title';
                    title.textContent = titleText;
                    right.insertBefore(title, anchor);
                }
            }
            if (right && features && features.parentNode !== right) {
                right.insertBefore(features, price || null);
            }
            if (features) {
                var featureMenu = features.querySelector('.nov-bottom__menu');
                var typeLabel = el.querySelector('.nov__title');
                if (featureMenu && typeLabel && !featureMenu.querySelector('.tr-feature-type')) {
                    var typeItem = document.createElement('li');
                    typeItem.className = 'tr-feature-type';
                    typeItem.textContent = typeLabel.textContent.replace(/\s+/g, ' ').trim();
                    featureMenu.appendChild(typeItem);
                }
                Array.prototype.slice.call(features.querySelectorAll('.nov-bottom__menu li')).forEach(function (li) {
                    var cls = li.className || '';
                    var icon = (li.querySelector('.icon') || {}).className || '';
                    if (/pdf|print|icon-bath|icon-bed|icon-ok/i.test(cls + ' ' + icon)) {
                        li.classList.add('tr-feature-hide');
                    }
                });
            }
            var share = el.querySelector('.nov-bottom__menu li.share, .nov-bottom__menu .share');
            if (share && !share.querySelector('.tr-share-label')) {
                share.classList.remove('tr-feature-hide');
                var kept = share.querySelector('.share-box');
                var icon = share.querySelector('.icon');
                share.textContent = '';
                if (icon) share.appendChild(icon);
                if (kept) share.appendChild(kept);
                var shareLabel = document.createElement('span');
                shareLabel.className = 'tr-share-label';
                shareLabel.textContent = t('Поделиться', 'Поділитися');
                share.appendChild(shareLabel);
            }
            if (right && !right.querySelector('.tr-card-tools')) {
                var tools = document.createElement('div');
                tools.className = 'tr-card-tools';
                if (share) tools.appendChild(share);
                Array.prototype.slice.call(el.querySelectorAll('.nov-bottom__menu li')).forEach(function (li) {
                    var a = li.querySelector('a[href*="pdf="], a[href*="print="]');
                    if (!a) return;
                    var isPdf = /pdf=/.test(a.getAttribute('href') || '');
                    a.className = isPdf ? 'tr-tool tr-tool--pdf' : 'tr-tool tr-tool--print';
                    a.textContent = isPdf ? 'PDF' : t('Печать', 'Друк');
                    tools.appendChild(a);
                    if (li.parentNode) li.parentNode.removeChild(li);
                });
                if (tools.childNodes.length) right.appendChild(tools);
            }
            Array.prototype.forEach.call(el.querySelectorAll('.nov_butt .btn'), capitalize);
            Array.prototype.forEach.call(el.querySelectorAll('.price-aft p, .price-bef p'), function (p) {
                Array.prototype.forEach.call(p.childNodes, function (node) {
                    if (node.nodeType === 3 && /\d{2}\/\d{2}\/\d{4}/.test(node.nodeValue)) {
                        node.nodeValue = node.nodeValue.replace(/(\d{2})\/(\d{2})\/(\d{4})/g, '$1.$2.$3');
                    }
                });
                var txt = (p.textContent || '').trim();
                if (txt && !/^ціна\s/i.test(txt) && !/^цена\s/i.test(txt)) {
                    p.insertBefore(document.createTextNode(t('Цена ', 'Ціна ')), p.firstChild);
                }
            });
        });

        /* Headlines come lowercase from the CMS; the mockup uses sentence case. */
        document.querySelectorAll('.objects .page-title, .novelty .page-title, .why .page-title, .how .page-title').forEach(capitalize);

        function capitalize(el) {
            for (var i = 0; i < el.childNodes.length; i++) {
                var node = el.childNodes[i];
                if (node.nodeType !== 3) continue;
                var m = /^(\s*)(\S)([\s\S]*)$/.exec(node.nodeValue);
                if (!m) continue;
                node.nodeValue = m[1] + m[2].toUpperCase() + m[3];
                return;
            }
        }

        /* Info tiles: section title like the mockup, clean escaped quotes from CMS. */
        var informRow = document.querySelector('.inform .row');
        if (informRow && !document.querySelector('.inform .page-title')) {
            var informTitle = document.createElement('div');
            informTitle.className = 'page-title';
            informTitle.textContent = t('Полезно знать', 'Корисно знати');
            informRow.parentNode.insertBefore(informTitle, informRow);
        }
        document.querySelectorAll('.inform-item__title span').forEach(function (el) {
            if (el.textContent.indexOf('\\"') !== -1) {
                el.textContent = el.textContent.replace(/\\"/g, '"');
            }
        });
        if (isUa) {
            document.querySelectorAll('.inform-item').forEach(function (el) {
                var title = ((el.querySelector('.inform-item__title') || {}).textContent || '').toLowerCase();
                var note = el.querySelector('p');
                if (!note) return;
                if (title.indexOf('статт') !== -1 || /здесь вы можете|мире недвижимости/i.test(note.textContent)) {
                    note.textContent = 'Актуальні новини світу нерухомості';
                }
            });
        }

        /* Footer: brand column, one menu column, information column with a heading. */
        var footCont = document.querySelector('#footer .foot-cont');
        if (footCont && !footCont.querySelector('.tr-foot-brand')) {
            var col1 = footCont.querySelector('.col-foot--1 .foot-nav');
            var col2 = footCont.querySelector('.col-foot--2');
            var col3 = footCont.querySelector('.col-foot--3');
            if (col1 && col2) {
                Array.prototype.slice.call(col2.querySelectorAll('.foot-nav__item')).forEach(function (li) {
                    col1.appendChild(li);
                });
                col2.parentNode.removeChild(col2);
            }
            if (col3 && !col3.querySelector('.foot__head')) {
                var head = document.createElement('span');
                head.className = 'foot__head';
                head.textContent = t('Информация', 'Інформація');
                col3.insertBefore(head, col3.firstChild);
            }
            var brand = document.createElement('div');
            brand.className = 'col-foot tr-foot-brand';
            var brandLink = document.createElement('a');
            brandLink.className = 'tr-foot-brand__name';
            brandLink.href = isUa ? '/ua/' : '/';
            brandLink.innerHTML = 'TopRealtor<span>.</span>';
            var motto = document.createElement('p');
            motto.innerHTML = t('Ваша недвижимость.<br>Ваш статус.', 'Ваша нерухомість.<br>Ваш статус.');
            brand.appendChild(brandLink);
            brand.appendChild(motto);
            var menuCol = footCont.querySelector('.col-foot--1');
            var infoCol = footCont.querySelector('.col-foot--3');
            var contacts = footCont.querySelector('.right-foot');
            if (menuCol && !menuCol.querySelector('.foot__head')) {
                var menuHead = document.createElement('span');
                menuHead.className = 'foot__head';
                menuHead.textContent = t('Меню', 'Меню');
                menuCol.insertBefore(menuHead, menuCol.firstChild);
            }
            [brand, menuCol, infoCol, contacts].forEach(function (node) {
                if (node) footCont.appendChild(node);
            });
        }

        function labelFields() {
            var labelMap = {
                'категорія': t('Категория', 'Категорія'),
                'категория': t('Категория', 'Категорія'),
                'пошук за кодом або за словом': t('Код или слово', 'Код або слово'),
                'поиск по коду или по слову': t('Код или слово', 'Код або слово'),
                'вибір валюти': t('Валюта', 'Валюта'),
                'выбор валюты': t('Валюта', 'Валюта')
            };
            document.querySelectorAll('.main_search .search-col').forEach(function (col) {
                if (col.querySelector('.btn-search')) return;
                var select = col.querySelector('select');
                var input = col.querySelector('input[type="text"], input[type="search"], input.search-code');
                var text = '';
                if (select) text = (select.getAttribute('title') || '').trim();
                if (input && (input.name === 'code' || input.classList.contains('search-code'))) {
                    text = t('Код или слово', 'Код або слово');
                }
                if (!text && input) text = (input.getAttribute('placeholder') || '').trim();
                if (!text) return;
                var mapped = labelMap[text.toLowerCase()] || labelMap[text.toLowerCase().replace(/\s+/g, ' ')];
                if (mapped) text = mapped;
                var label = col.querySelector('.tr-label');
                if (!label) {
                    label = document.createElement('span');
                    label.className = 'tr-label';
                    col.insertBefore(label, col.firstChild);
                }
                label.textContent = text;
                if (input && /код|слово|code/i.test(text)) {
                    input.setAttribute('placeholder', t('Например, Сокольники или 31206', 'Наприклад, Сокольники або 31206'));
                }
            });
        }

        var crumbLinks = document.querySelectorAll('.breadcrumbs-block a, ._breadcumbs a');
        if (!document.getElementById('slider1') && crumbLinks.length >= 2 && document.querySelector('.objects .objects-item img') && !document.querySelector('.flat-box')) {
            document.body.classList.add('tr-photo-hub');
        }

        labelFields();
        var searchMenu = document.querySelector('.main_search .search-menu');
        if (searchMenu && window.MutationObserver) {
            var pending = false;
            new MutationObserver(function () {
                if (pending) return;
                pending = true;
                window.requestAnimationFrame(function () {
                    pending = false;
                    labelFields();
                });
            }).observe(searchMenu, {childList: true, subtree: true});
        }

        document.querySelectorAll('.price-aft span, .price-bef span, .price-aft del, .price-bef del').forEach(function (el) {
            if (el.closest && el.closest('.object__price')) return;
            Array.prototype.forEach.call(el.childNodes, function (node) {
                if (node.nodeType !== 3) return;
                var raw = node.nodeValue;
                if (!raw || /\d\s\d{3}/.test(raw)) return;
                node.nodeValue = raw.replace(/\d{4,}/g, function (num) {
                    return num.replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0');
                });
            });
            /* Currency sign as a light suffix, like "210 000 $" in the mockup. */
            if (el.tagName === 'SPAN' && !el.querySelector('.tr-cur')) {
                var last = el.lastChild;
                if (last && last.nodeType === 3) {
                    var cm = /^([\s\S]*?\d)\s*([$€₴]|грн\.?|usd|eur|uah)\s*$/i.exec(last.nodeValue);
                    if (cm) {
                        last.nodeValue = cm[1] + ' ';
                        var cur = document.createElement('em');
                        cur.className = 'tr-cur';
                        cur.textContent = cm[2];
                        el.appendChild(cur);
                    }
                }
            }
        });

        /* Partner bar: drop the agency credit and keep the rieltor.ua badge in flow. */
        document.querySelectorAll('#footer a[href*="artjoker"]').forEach(function (a) {
            var col = a.closest ? a.closest('.col-md-6, .col-sm-6, .pull-right') : a.parentNode;
            if (col && col.parentNode) col.parentNode.removeChild(col);
        });
        var partnerBadge = document.getElementById('rieltor_ua_81771');
        if (partnerBadge) {
            partnerBadge.style.setProperty('position', 'static', 'important');
            partnerBadge.style.setProperty('top', 'auto', 'important');
            partnerBadge.style.setProperty('left', 'auto', 'important');
            partnerBadge.style.setProperty('right', 'auto', 'important');
            partnerBadge.style.setProperty('max-width', 'none', 'important');
            partnerBadge.style.setProperty('width', 'auto', 'important');
            window.setTimeout(function () {
                if (partnerBadge.querySelector('img')) return;
                var img = document.createElement('img');
                img.alt = 'RIELTOR.UA Platinum Partner';
                img.src = 'https://market-images.lunstatic.net/lun-ua/origin/images/static-content/1779366028928717.png';
                partnerBadge.appendChild(img);
            }, 800);
        }

        /* Menu: redesign accordion dialog. The CMS list stays the source of the links. */
        (function setupMenu() {
            var source = document.querySelector('.navigation-menu.menu-desc');
            if (!source || !source.children.length || document.getElementById('tr-menu')) return;

            function labelOf(li) {
                var kids = li.children;
                for (var i = 0; i < kids.length; i++) {
                    if (kids[i].tagName === 'A' || kids[i].tagName === 'SPAN') return kids[i];
                }
                return null;
            }
            function listOf(li) {
                var kids = li.children;
                for (var i = 0; i < kids.length; i++) {
                    if (kids[i].tagName === 'UL') return kids[i];
                }
                return null;
            }
            function fromLi(li, isRoot) {
                var labelEl = labelOf(li);
                var sub = listOf(li);
                var text = labelEl ? labelEl.textContent.replace(/\s+/g, ' ').trim() : '';
                if (!text) return null;
                var href = labelEl && labelEl.tagName === 'A' ? labelEl.getAttribute('href') : '';
                if (sub && sub.children.length) {
                    var details = document.createElement('details');
                    var summary = document.createElement('summary');
                    if (href) {
                        var link = document.createElement('a');
                        link.href = href;
                        link.textContent = text;
                        summary.appendChild(link);
                    } else {
                        summary.appendChild(document.createTextNode(text));
                    }
                    var box = document.createElement('div');
                    for (var i = 0; i < sub.children.length; i++) {
                        var child = fromLi(sub.children[i], false);
                        if (child) box.appendChild(child);
                    }
                    details.appendChild(summary);
                    details.appendChild(box);
                    return details;
                }
                if (href) {
                    var leaf = document.createElement('a');
                    leaf.href = href;
                    leaf.textContent = text;
                    if (isRoot) leaf.className = 'tr-menu-root-link';
                    return leaf;
                }
                var here = document.createElement('span');
                here.className = isRoot ? 'tr-menu-root-link tr-menu-here' : 'tr-menu-here';
                here.textContent = text;
                return here;
            }

            var dialog = document.createElement('dialog');
            dialog.className = 'tr-drawer';
            dialog.id = 'tr-menu';
            dialog.setAttribute('aria-label', t('Меню', 'Меню'));

            var head = document.createElement('div');
            head.className = 'tr-drawer-head';
            var title = document.createElement('h2');
            title.textContent = t('Меню', 'Меню');
            var closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'tr-icon-button';
            closeBtn.setAttribute('aria-label', t('Закрыть меню', 'Закрити меню'));
            closeBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>';
            head.appendChild(title);
            head.appendChild(closeBtn);

            var nav = document.createElement('nav');
            nav.setAttribute('aria-label', t('Полное меню сайта', 'Повне меню сайту'));
            for (var n = 0; n < source.children.length; n++) {
                var item = fromLi(source.children[n], true);
                if (item) nav.appendChild(item);
            }
            dialog.appendChild(head);
            dialog.appendChild(nav);

            var auth = document.createElement('div');
            auth.className = 'tr-drawer-auth';
            document.querySelectorAll('.head-form a').forEach(function (src) {
                var a = document.createElement('a');
                a.href = src.getAttribute('href') || '#';
                a.textContent = (src.textContent || '').replace(/\s+/g, ' ').trim();
                if (src.getAttribute('data-toggle')) a.setAttribute('data-toggle', src.getAttribute('data-toggle'));
                if (a.textContent) auth.appendChild(a);
            });
            if (auth.children.length) dialog.appendChild(auth);
            document.body.appendChild(dialog);

            var closing = false;
            function openMenu() {
                closing = false;
                if (!dialog.open) dialog.showModal();
                window.requestAnimationFrame(function () {
                    window.requestAnimationFrame(function () {
                        if (!closing) dialog.classList.add('is-in');
                    });
                });
            }
            function closeMenu() {
                if (!dialog.open || closing) return;
                closing = true;
                dialog.classList.remove('is-in');
                var finished = false;
                function done() {
                    if (finished) return;
                    finished = true;
                    closing = false;
                    if (dialog.open) dialog.close();
                }
                dialog.addEventListener('transitionend', function onEnd(e) {
                    if (e.target !== dialog || e.propertyName !== 'transform') return;
                    dialog.removeEventListener('transitionend', onEnd);
                    done();
                });
                window.setTimeout(done, 420);
            }

            closeBtn.addEventListener('click', closeMenu);
            dialog.addEventListener('cancel', function (e) {
                e.preventDefault();
                closeMenu();
            });
            dialog.addEventListener('click', function (e) {
                if (e.target === dialog) {
                    closeMenu();
                    return;
                }
                var link = e.target.closest ? e.target.closest('a') : null;
                if (!link || !dialog.contains(link)) return;
                var href = link.getAttribute('href') || '';
                if (link.getAttribute('data-toggle') === 'modal' || href.charAt(0) === '#') closeMenu();
            });

            document.addEventListener('click', function (e) {
                var btn = e.target.closest && e.target.closest('.toggle-navigation-button');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();
                if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                if (dialog.classList.contains('is-in')) closeMenu();
                else openMenu();
            }, true);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && dialog.open) {
                    e.preventDefault();
                    closeMenu();
                }
            });

            try {
                var api = window.jQuery && window.jQuery('#menu').data('mmenu');
                if (api && api.close) api.close();
            } catch (err) {}
            if (window.location.hash === '#menu') {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, '', window.location.pathname + window.location.search);
                }
            }
            document.documentElement.classList.add('tr-drawer-ready');
        })();

        (function cascadeCategoryMenus() {
            var menu = document.querySelector('.navigation-menu.menu-desc');
            if (!menu) return;
            var tree = {};
            function walk(ul, bucket) {
                Array.prototype.forEach.call(ul.children, function (li) {
                    var a = null;
                    var sub = null;
                    Array.prototype.forEach.call(li.children, function (child) {
                        if (child.tagName === 'A') a = child;
                        if (child.tagName === 'UL') sub = child;
                    });
                    if (!a) return;
                    var href = a.getAttribute('href') || '';
                    var kids = [];
                    if (sub) walk(sub, kids);
                    bucket.push({href: href, text: (a.textContent || '').replace(/\s+/g, ' ').trim(), kids: kids});
                    if (href) tree[href.replace(/^\/+/, '')] = kids;
                });
            }
            var roots = [];
            walk(menu, roots);
            function keyOf(href) {
                return (href || '').replace(/^https?:\/\/[^/]+/i, '').replace(/^\/+/, '');
            }
            document.querySelectorAll('.objects-box .item-hidden li').forEach(function (li) {
                if (li.querySelector(':scope > ul')) return;
                var a = li.querySelector('a');
                if (!a) return;
                var kids = tree[keyOf(a.getAttribute('href'))];
                if (!kids || !kids.length) return;
                function list(items) {
                    var ul = document.createElement('ul');
                    ul.className = 'tr-fly';
                    items.forEach(function (item) {
                        var row = document.createElement('li');
                        var link = document.createElement('a');
                        link.href = item.href;
                        link.textContent = item.text;
                        row.appendChild(link);
                        if (item.kids && item.kids.length) row.appendChild(list(item.kids));
                        ul.appendChild(row);
                    });
                    return ul;
                }
                li.classList.add('tr-has-sub');
                li.appendChild(list(kids));
            });
        })();

        (function similarCards() {
            var box = document.querySelector('.flat-box .js_owl-carousel-novelty, .flat-box .novelty-slider');
            if (!box || !document.body.classList.contains('tr-object') && !document.querySelector('.flat-box')) return;
            if (window.jQuery) {
                var $c = window.jQuery('.flat-box .js_owl-carousel-novelty');
                if ($c.length && $c.data('owl.carousel')) $c.trigger('destroy.owl.carousel');
                $c.removeClass('owl-loaded owl-drag');
            }
            var host = document.querySelector('.flat-box .js_owl-carousel-novelty') || box;
            host.classList.add('tr-similar-grid');
        })();

        (function setupPages() {
            var cat = document.querySelector('.objects .row.category');
            if (cat && /квартир/i.test((cat.querySelector('h1') || cat).textContent || '') && !cat.querySelector('.tr-cat-short')) {
                var full = document.createElement('div');
                full.className = 'tr-cat-full';
                while (cat.firstChild) full.appendChild(cat.firstChild);
                var short = document.createElement('div');
                short.className = 'tr-cat-short';
                short.innerHTML = isUa
                    ? '<h2>Продаж елітних квартир у Харкові</h2><p>З 1992 року спеціалізація агентства TopRealtor — продаж елітної нерухомості в Харкові. Підбираємо квартири в сучасних будинках, історичному центрі та спальних районах відповідно до побажань клієнта.</p><h2>TopRealtor — продаж елітної нерухомості</h2><p>Квартири поділено на чотири категорії: преміум-клас, сучасні будинки, вторинний ринок у центрі Харкова та квартири у спальних районах. Інформація й статті залишаються окремими розділами.</p><h2>Купити елітну квартиру простіше, ніж ви думаєте</h2><p>Від заявки й підбору об’єкта до перевірки документів, підготовки угоди та оформлення купівлі-продажу — команда супроводжує кожен етап.</p>'
                    : '<h2>Продажа элитных квартир в Харькове</h2><p>С 1992 года специализация агентства TopRealtor — продажа элитной недвижимости в Харькове. Подбираем квартиры в современных домах, историческом центре и спальных районах по пожеланиям клиента.</p><h2>TopRealtor — продажа элитной недвижимости</h2><p>Квартиры разделены на четыре категории: премиум-класс, современные дома, вторичный рынок в центре Харькова и квартиры в спальных районах.</p><h2>Купить элитную квартиру проще, чем вы думаете</h2><p>От заявки и подбора объекта до проверки документов, подготовки сделки и оформления купли-продажи — команда сопровождает каждый этап.</p>';
                var more = document.createElement('button');
                more.type = 'button';
                more.className = 'tr-cat-more';
                more.textContent = t('Читать далее', 'Читати далі');
                more.addEventListener('click', function () {
                    var open = cat.classList.toggle('is-open');
                    more.textContent = open ? t('Меньше текста', 'Менше тексту') : t('Читать далее', 'Читати далі');
                });
                cat.appendChild(short);
                cat.appendChild(full);
                cat.appendChild(more);
            }

            if (/\/o-nas\/?/.test(location.pathname) && document.querySelector('.news-content') && !document.getElementById('tr-design-about')) {
                document.body.classList.add('tr-about-page');
                var news = document.querySelector('.news-content');
                var source = news.querySelector('.one-news__info') || news;
                var chunks = [];
                var cur = null;
                Array.prototype.forEach.call(source.querySelectorAll('h2, p, ul, ol'), function (el) {
                    if (el.tagName === 'H2') {
                        cur = {title: (el.textContent || '').replace(/\s+/g, ' ').trim(), html: ''};
                        chunks.push(cur);
                    } else if (cur && (el.textContent || '').replace(/\s+/g, '').length) {
                        cur.html += el.outerHTML;
                    }
                });
                var introHtml = (chunks[0] && chunks[0].html) || '';
                var chapters = chunks.slice(1).filter(function (c) {
                    return !/^колектив/i.test(c.title);
                });
                var base = '/assets/tr/about/';
                function personCard(file, name, role, desc, office, lead) {
                    return '<article class="tr-ab-person' + (lead ? ' tr-ab-person-lead' : '') + '"><div class="tr-ab-person-photo"><img alt="' + name + '" src="' + base + file + '">' + (lead ? '<span class="tr-ab-person-tag">' + role + '</span>' : '') + '</div><div class="tr-ab-person-copy"><p class="tr-ab-person-office">' + office + '</p><h3>' + name + '</h3><p class="tr-ab-person-role">' + role + '</p>' + (desc ? '<p class="tr-ab-person-description">' + desc + '</p>' : '') + '</div></article>';
                }
                var chapterHtml = chapters.map(function (c, idx) {
                    var num = (idx + 2 < 10 ? '0' : '') + (idx + 2);
                    var image = /нагород/i.test(c.title) ? '<figure class="tr-ab-photo"><img alt="Нагороди" src="' + base + 'archive-3.jpg"></figure>' : '';
                    return '<section class="tr-ab-chapter' + (image ? '' : ' tr-ab-chapter-text') + '"><div class="tr-ab-chapter-heading"><p class="tr-ab-label"><span>' + num + '</span>Переваги співпраці</p><h2>' + c.title + '</h2>' + image + '</div><div class="tr-ab-prose">' + c.html + '</div></section>';
                }).join('');
                var mount = document.createElement('div');
                mount.className = 'tr-about-page';
                mount.id = 'tr-design-about';
                mount.innerHTML = '<div class="tr-wrap">'
                    + '<section class="tr-ab-hero"><div><p class="tr-ab-kicker"><span></span> TopRealtor · Про нас</p><h1>За кожною<br>угодою —<br><em>люди.</em></h1><p class="tr-ab-hero-description">Агентство елітної нерухомості в Харкові.<br>Досвід, якому довіряють. Команда, яка поруч.</p><a class="tr-button" href="#tr-about-team">Познайомитися з командою</a><div class="tr-ab-hero-facts"><div><strong>1992</strong><span>рік початку нашої історії</span></div><div><strong>02</strong><span>офіси у Харкові</span></div></div></div>'
                    + '<figure class="tr-ab-hero-photo"><img alt="Команда TopRealtor разом" src="' + base + 'team.jpg"><figcaption><span>Одна команда.<br>Спільні цінності.</span><span class="tr-ab-photo-dot"></span></figcaption></figure></section>'
                    + '<section class="tr-ab-intro"><div><p class="tr-ab-label"><span>01</span>Наша історія</p><h2>Преміальний сервіс.<br>Безпека.<br><span>Репутація.</span></h2></div><div class="tr-ab-prose">' + introHtml + '</div></section>'
                    + '<div class="tr-ab-photo-pair"><figure class="tr-ab-photo"><img alt="Офіс TopRealtor" src="' + base + 'archive-1.webp"></figure><figure class="tr-ab-photo"><img alt="Вхід до офісу на Сумській" src="' + base + 'archive-2.webp"></figure><span class="tr-ab-photo-pair-caption">Наш простір. Наш Харків.</span></div>'
                    + chapterHtml
                    + '<section class="tr-ab-brand-interlude"><figure class="tr-ab-brand-scene"><img alt="Логотип TopRealtor в офісі" src="' + base + 'brand-scene.webp"></figure></section>'
                    + '<section class="tr-ab-team-heading" id="tr-about-team"><div><p class="tr-ab-label"><span>07</span>Колектив агентства TopRealtor</p><h2>Два офіси.<br><span>Одна команда.</span></h2></div><div><p>Знайомтеся з людьми, які допомагають вам купувати, продавати й орендувати нерухомість.</p></div></section>'
                    + '<section class="tr-ab-branch" id="tr-office-sumska"><div class="tr-ab-branch-header"><div><p class="tr-ab-label"><span>01</span>Харків · наша команда</p><h2>Сумська, <span>126</span></h2><p>Засновник компанії та команда офісу на Сумській.</p></div></div>'
                    + '<div class="tr-ab-leads tr-ab-one-lead">' + personCard('vitalii.webp', 'Віталій Топчій', 'Засновник та CEO', 'Стратегія розвитку компанії, управління агентством та експертиза ринку нерухомості.', 'Сумська, 126', true)
                    + '<div class="tr-ab-founder-note"><span class="tr-ab-quote-mark">“</span><p>Від першої консультації<br>до вашого нового дому.</p><span>Досвід, відповідальність і увага до кожної угоди.</span></div></div>'
                    + '<div class="tr-ab-team-grid">'
                    + personCard('andrii.webp', 'Андрій Панченко', 'Спеціаліст з продажу квартир і будинків', '', 'Сумська, 126', false)
                    + personCard('ivan-v2.webp', 'Іван Тузов', 'Спеціаліст з продажу квартир і будинків', '', 'Сумська, 126', false)
                    + personCard('anna-v2.webp', 'Анна', 'Офіс-менеджерка', '', 'Сумська, 126', false)
                    + personCard('yuliia.webp', 'Юлія Топчій', 'Спеціалістка з продажу квартир і будинків', '', 'Сумська, 126', false)
                    + '</div></section>'
                    + '<section class="tr-ab-branch" id="tr-office-danylevskoho"><div class="tr-ab-branch-header"><div><p class="tr-ab-label"><span>02</span>Харків · наша команда</p><h2>Данилевського, <span>38</span></h2><p>У 2026 році ми відкрили нову філію. Тепер ще ближче до вас.</p></div><span class="tr-ab-new">Відкрили у 2026</span></div>'
                    + '<div class="tr-ab-leads">'
                    + personCard('pavlo.webp', 'Павло Топчій', 'Операційний директор агентства', 'Директор філії на Данилевського. Спеціаліст з продажу квартир і будинків.', 'Данилевського, 38', true)
                    + personCard('yana.webp', 'Яна Безрук', 'Керівниця команди та маркетингу', 'Спеціалістка з продажу квартир і будинків.', 'Данилевського, 38', true)
                    + '</div><div class="tr-ab-team-grid">'
                    + personCard('olena.webp', 'Олена Мицькевич', 'Спеціалістка з оренди квартир', '', 'Данилевського, 38', false)
                    + personCard('renata.webp', 'Рената Грудєва', 'Спеціалістка з оренди квартир', '', 'Данилевського, 38', false)
                    + personCard('daryna-v2.webp', 'Даріна Бреславська', 'Спеціалістка з продажу квартир і будинків', '', 'Данилевського, 38', false)
                    + personCard('daria-v2.webp', 'Дар’я Медкова', 'Спеціалістка з оренди квартир', '', 'Данилевського, 38', false)
                    + personCard('anastasiia-v2.webp', 'Анастасія Медкова', 'Спеціалістка з оренди квартир', '', 'Данилевського, 38', false)
                    + '</div></section>'
                    + '<section class="tr-ab-contact"><div><p class="tr-ab-label"><span>08</span>Будемо раді знайомству</p><h2>Поговорімо про<br>вашу нерухомість.</h2><div class="tr-ab-contact-actions"><a class="tr-button" href="tel:+380675749577">+38 067 574 95 77</a></div></div><div class="tr-ab-contact-addresses"><p><span>Харків<br><b>вул. Сумська, 126</b></span></p><p><span>Харків<br><b>вул. Данилевського, 38</b></span></p></div></section>'
                    + '<section class="tr-ab-review" id="tr-ab-review-slot"><div><p class="tr-ab-label"><span>09</span>Ваш досвід</p><h2>Залишити відгук</h2></div></section>'
                    + '</div>';
                news.parentNode.insertBefore(mount, news);
                var slot = document.getElementById('tr-ab-review-slot');
                var form = document.querySelector('form.form-contacts, form.js_validate');
                if (slot && form) slot.appendChild(form);
                var why = document.querySelector('.why');
                var wrap = mount.querySelector('.tr-wrap');
                if (why && wrap) {
                    why.classList.add('tr-ab-why');
                    var whyLabel = document.createElement('p');
                    whyLabel.className = 'tr-ab-label';
                    whyLabel.innerHTML = '<span>10</span>TopRealtor';
                    var whyTitle = why.querySelector('.page-title');
                    if (whyTitle) {
                        whyTitle.textContent = t('Почему выбирают нас', 'Чому обирають нас');
                        why.insertBefore(whyLabel, whyTitle);
                    }
                    wrap.appendChild(why);
                }
            }

            var gallery = document.getElementById('gallery');
            if (gallery && document.querySelector('.object__name')) {
                document.body.classList.add('tr-object');
                var photos = [];
                Array.prototype.forEach.call(gallery.querySelectorAll('a'), function (a) {
                    if (a.classList.contains('panoramabox')) return;
                    var img = a.querySelector('img');
                    var full = a.getAttribute('href') || '';
                    var thumb = img ? (img.getAttribute('src') || '') : '';
                    if (/panorama\.jpg/i.test(full + ' ' + thumb)) return;
                    if (!/^https?:|^\//.test(full)) return;
                    photos.push({src: full, thumb: thumb || full, alt: img ? (img.getAttribute('alt') || '') : ''});
                });
                if (photos.length && !gallery.querySelector('.tr-stage')) {
                    var slides = photos.map(function (p) { return {type: 'photo', src: p.src, alt: p.alt}; });
                    var videoUrl = '';
                    Array.prototype.forEach.call(document.querySelectorAll('#video iframe, #video a, .object-description a, .object a'), function (node) {
                        if (videoUrl) return;
                        var url = node.getAttribute('src') || node.getAttribute('href') || '';
                        if (/youtube\.com|youtu\.be|instagram\.com\/reel|tiktok\.com/i.test(url) && !/top_realtor|TopRealtor_Bot/i.test(url)) videoUrl = url;
                    });
                    function embed(url) {
                        var m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{6,})/);
                        if (m) return 'https://www.youtube.com/embed/' + m[1];
                        m = url.match(/instagram\.com\/reel\/([\w-]+)/);
                        if (m) return 'https://www.instagram.com/reel/' + m[1] + '/embed';
                        m = url.match(/tiktok\.com\/@[^/]+\/video\/(\d+)/) || url.match(/tiktok\.com\/embed\/v2\/(\d+)/);
                        if (m) return 'https://www.tiktok.com/embed/v2/' + m[1];
                        return '';
                    }
                    var embedded = videoUrl ? embed(videoUrl) : '';
                    if (embedded) slides.push({type: 'video', src: embedded});
                    var mapEl = document.getElementById('map-one-object');
                    var coord = mapEl ? (mapEl.getAttribute('data-coord') || '').trim() : '';
                    if (coord.indexOf(',') !== -1) slides.push({type: 'map', src: coord});

                    var index = 0;
                    var stage = document.createElement('div');
                    stage.className = 'tr-stage';
                    function button(cls) {
                        var b = document.createElement('button');
                        b.type = 'button';
                        b.className = 'tr-shot tr-shot--' + cls;
                        return b;
                    }
                    var main = button('main');
                    var prev = button('prev');
                    var next = button('next');
                    main.setAttribute('aria-label', t('Открыть фотографии', 'Відкрити фотографії'));
                    prev.setAttribute('aria-label', t('Предыдущее фото', 'Попереднє фото'));
                    next.setAttribute('aria-label', t('Следующее фото', 'Наступне фото'));
                    stage.appendChild(main);
                    stage.appendChild(prev);
                    stage.appendChild(next);
                    var host = gallery.parentNode && gallery.parentNode.classList.contains('big-slider')
                        ? gallery.parentNode.parentNode
                        : gallery;
                    if (host && host.classList && host.classList.contains('gallery-container')) {
                        host.insertBefore(stage, host.firstChild);
                    } else {
                        gallery.insertBefore(stage, gallery.firstChild);
                    }
                    gallery.classList.add('tr-ready');
                    function at(i) {
                        var n = photos.length;
                        return photos[(i % n + n) % n];
                    }
                    function paint() {
                        main.style.backgroundImage = 'url("' + at(index).thumb + '")';
                        prev.style.backgroundImage = 'url("' + at(index - 1).thumb + '")';
                        next.style.backgroundImage = 'url("' + at(index + 1).thumb + '")';
                    }
                    prev.addEventListener('click', function () {
                        index = (index - 1 + photos.length) % photos.length;
                        paint();
                    });
                    next.addEventListener('click', function () {
                        index = (index + 1) % photos.length;
                        paint();
                    });
                    paint();

                    var lb = document.createElement('div');
                    lb.className = 'tr-lightbox';
                    lb.hidden = true;
                    lb.innerHTML = '<button type="button" class="tr-lightbox__close" aria-label="' + t('Закрыть', 'Закрити') + '">×</button><button type="button" class="tr-lightbox__prev" aria-label="' + t('Назад', 'Назад') + '">‹</button><button type="button" class="tr-lightbox__next" aria-label="' + t('Дальше', 'Далі') + '">›</button><div class="tr-lightbox__stage"></div><div class="tr-lightbox__count"></div>';
                    document.body.appendChild(lb);
                    var lbStage = lb.querySelector('.tr-lightbox__stage');
                    var lbCount = lb.querySelector('.tr-lightbox__count');
                    var lbIndex = 0;
                    function showLb() {
                        var slide = slides[lbIndex];
                        lbCount.textContent = (lbIndex + 1) + ' / ' + slides.length;
                        if (slide.type === 'photo') {
                            lbStage.innerHTML = '<img alt="' + (slide.alt || '').replace(/"/g, '') + '" src="' + slide.src + '">';
                        } else if (slide.type === 'video') {
                            lbStage.innerHTML = '<iframe allow="autoplay; encrypted-media" allowfullscreen src="' + slide.src + '"></iframe>';
                        } else {
                            lbStage.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + encodeURIComponent(slide.src) + '&z=15&output=embed"></iframe>';
                        }
                    }
                    function openLb(i) {
                        lbIndex = i;
                        lb.hidden = false;
                        document.documentElement.style.overflow = 'hidden';
                        showLb();
                    }
                    function closeLb() {
                        lb.hidden = true;
                        lbStage.innerHTML = '';
                        document.documentElement.style.overflow = '';
                    }
                    function step(dir) {
                        lbIndex = (lbIndex + dir + slides.length) % slides.length;
                        showLb();
                    }
                    main.addEventListener('click', function () { openLb(index); });
                    lb.querySelector('.tr-lightbox__close').addEventListener('click', closeLb);
                    lb.querySelector('.tr-lightbox__prev').addEventListener('click', function () { step(-1); });
                    lb.querySelector('.tr-lightbox__next').addEventListener('click', function () { step(1); });
                    lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
                    document.addEventListener('keydown', function (e) {
                        if (lb.hidden) return;
                        if (e.key === 'Escape') closeLb();
                        if (e.key === 'ArrowRight') step(1);
                        if (e.key === 'ArrowLeft') step(-1);
                    });
                }

                var icons = [
                    ['розумн', '<path d="M12 3v2M12 19v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M3 12h2M19 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/><circle cx="12" cy="12" r="3"/>'],
                    ['відео', '<path d="M3 8a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="m16 10 5-3v10l-5-3"/>'],
                    ['сигнал', '<path d="M12 3a8 8 0 0 1 8 8"/><path d="M12 7a4 4 0 0 1 4 4"/><circle cx="12" cy="17" r="1"/><path d="M5 10a7 7 0 0 1 2-5"/>'],
                    ['ліфт', '<rect x="6" y="3" width="12" height="18" rx="2"/><path d="M12 7v4M10 9l2-2 2 2M12 17v-4M10 15l2 2 2-2"/>'],
                    ['опален', '<path d="M12 12a4 4 0 0 0 4-4c0-3-4-6-4-6s-4 3-4 6a4 4 0 0 0 4 4z"/><path d="M8 16h8M9 20h6"/>'],
                    ['котель', '<rect x="4" y="8" width="16" height="10" rx="2"/><path d="M8 8V6M12 8V4M16 8V6"/>'],
                    ['конвектор', '<path d="M4 18h16M6 14c2-4 2-4 4 0s2 4 4 0 2-4 4 0"/>'],
                    ['лічильник', '<circle cx="12" cy="12" r="8"/><path d="M12 12l3-3M12 8v1"/>'],
                    ['двер', '<path d="M6 21V4a1 1 0 0 1 1-1h10v18"/><path d="M14 12h.01"/>'],
                    ['панорам', '<path d="M3 7h18v10H3z"/><path d="M3 12h18M8 7v10M16 7v10"/>'],
                    ['вікн', '<rect x="4" y="4" width="16" height="16" rx="1"/><path d="M12 4v16M4 12h16"/>'],
                    ['ролет', '<path d="M4 6h16v3H4zM6 9v9M10 9v9M14 9v9M18 9v9"/>'],
                    ['паркінг', '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 16V8h4a3 3 0 0 1 0 6H9"/>'],
                    ['гараж', '<path d="M4 20V10l8-6 8 6v10"/><path d="M8 20v-6h8v6"/>'],
                    ['стоян', '<path d="M5 17h14M7 17V8h6l2 3h4v6"/><circle cx="8" cy="17" r="1.5"/><circle cx="16" cy="17" r="1.5"/>'],
                    ['цегл', '<path d="M4 8h7V5H4zM13 8h7V5h-7zM4 14h7v-3H4zM13 14h7v-3h-7zM8 20h8v-3H8z"/>'],
                    ['колон', '<path d="M8 4h8M9 4v16M15 4v16M7 20h10"/>'],
                    ['балкон', '<path d="M4 10h16v8H4z"/><path d="M8 10V6h8v4M4 14h16"/>'],
                    ['лодж', '<path d="M6 20V8h12v12"/><path d="M4 8h16M9 12h6"/>'],
                    ['стел', '<path d="M4 18V8l8-4 8 4v10"/><path d="M12 4v14"/>'],
                    ['дворівн', '<path d="M4 20V10h8V4h8v16"/><path d="M4 14h8M12 10h8"/>'],
                    ['ремонт', '<path d="M14 6l4 4-9 9H5v-4z"/><path d="m12 8 4 4"/>'],
                    ['кондиці', '<path d="M4 8h16v6H4z"/><path d="M8 14v3M12 14v4M16 14v3"/>'],
                    ['вентиля', '<path d="M12 12a3 3 0 1 0 3-3"/><path d="M12 12c0 4 3 6 6 6M12 12C8 12 6 9 6 6"/>'],
                    ['ванн', '<path d="M5 12h14a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4z"/><path d="M7 12V7a2 2 0 0 1 4 0"/>'],
                    ['саун', '<path d="M8 18c0-4 8-4 8 0"/><path d="M9 10c.5-2 2-3 3-3s2.5 1 3 3M8 6c1-2 3-3 4-3"/>'],
                    ['терас', '<path d="M4 16h16M6 16V8h12v8M9 8V5h6v3"/>'],
                    ['вітальн', '<path d="M4 20V10l8-6 8 6v10"/><path d="M10 20v-6h4v6M12 4v6"/>'],
                    ['гардероб', '<path d="M6 3h12v18H6z"/><path d="M12 3v18M9 8h.01M15 8h.01"/>'],
                    ['персонал', '<circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0M17 11a3 3 0 1 0 0-6M21 20a5 5 0 0 0-4-4"/>'],
                    ['зимов', '<path d="M12 3v18M5 7l14 10M19 7 5 17"/>'],
                    ['кінотеатр', '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M8 20h8"/>'],
                    ['тепл', '<path d="M4 16c2-3 2-3 4 0s2 3 4 0 2-3 4 0 2 3 4 0"/><path d="M4 20h16"/>'],
                    ['хід', '<path d="M14 4h6v6"/><path d="M20 4 10 14"/><path d="M6 8v10h10"/>'],
                    ['камін', '<path d="M5 20h14M7 20V10h10v10"/><path d="M12 14c1-2 3-2 3-4 0-2-3-3-3-5 0 2-3 3-3 5 0 2 2 2 3 4"/>'],
                    ['пилосос', '<circle cx="8" cy="16" r="3"/><path d="M11 14l8-8M16 4l4 4"/>'],
                    ['мульти-рум', '<path d="M4 10v4M8 8v8M12 5v14M16 8v8M20 10v4"/>'],
                    ['еко', '<path d="M12 21C8 17 5 13 5 9a7 7 0 0 1 12-4c1 4-1 8-5 12z"/><path d="M12 21c2-4 6-6 9-6"/>'],
                    ['перекрит', '<path d="M4 8h16M4 12h16M4 16h16M8 8v8M16 8v8"/>'],
                    ['wi-fi', '<path d="M5 9a10 10 0 0 1 14 0"/><path d="M8 12a6 6 0 0 1 8 0"/><circle cx="12" cy="16" r="1"/>'],
                    ['телебач', '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M8 6l4-3 4 3"/>'],
                    ['вид на парк', '<path d="M12 21V10"/><path d="M12 13l-4-4M12 11l5-5"/><path d="M5 21h14"/>'],
                    ['вид на воду', '<path d="M3 16c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/><path d="M3 20c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2"/>'],
                    ['панорамний вид', '<path d="M3 16l5-8 4 5 3-4 6 7"/><path d="M3 20h18"/>'],
                    ['прогулян', '<circle cx="8" cy="5" r="2"/><path d="M6 21l2-7 3 2 2 5M8 14l4-2 3 2"/>'],
                    ['спорт', '<circle cx="12" cy="12" r="8"/><path d="M12 4c2 3 2 13 0 16M4 12h16"/>'],
                    ['школ', '<path d="M3 10l9-5 9 5-9 5z"/><path d="M7 12v5c2 2 8 2 10 0v-5"/>'],
                    ['садок', '<circle cx="12" cy="8" r="3"/><path d="M6 20c1-4 3-6 6-6s5 2 6 6"/>'],
                    ['автомий', '<path d="M5 16h14l-1-5H6z"/><circle cx="8" cy="16" r="1.5"/><circle cx="16" cy="16" r="1.5"/><path d="M8 6c1 2 2 2 4 2s3 0 4-2"/>'],
                    ['майданчик', '<path d="M6 20V9l6-4 6 4v11"/><path d="M10 20v-5h4v5"/>'],
                    ['ліс', '<path d="M12 21V12"/><path d="M12 14l-5 6h10zM12 8l-4 6h8z"/>'],
                    ['парк', '<circle cx="8" cy="16" r="2"/><circle cx="16" cy="14" r="3"/><path d="M8 16V9M16 14V8"/>'],
                    ['метро', '<circle cx="12" cy="12" r="8"/><path d="M8 16V9l4 4 4-4v7"/>'],
                    ['охоро', '<path d="M12 3l7 3v6c0 4-3 7-7 9-4-2-7-5-7-9V6z"/>'],
                    ['домофон', '<rect x="7" y="3" width="10" height="18" rx="2"/><circle cx="12" cy="16" r="1"/><path d="M10 7h4M10 10h4"/>'],
                    ['сучасн', '<path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/>']
                ];
                document.querySelectorAll('.object-list__item').forEach(function (li) {
                    if (li.querySelector('svg')) return;
                    var name = (li.textContent || '').toLowerCase();
                    var markup = '<circle cx="12" cy="12" r="8"/><path d="m9 12 2 2 4-4"/>';
                    for (var i = 0; i < icons.length; i++) {
                        if (name.indexOf(icons[i][0]) !== -1) {
                            markup = icons[i][1];
                            break;
                        }
                    }
                    li.insertAdjacentHTML('afterbegin', '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + markup + '</svg>');
                });
                if (isUa) {
                    var mapBtn = document.querySelector('.map-block .btn-object');
                    if (mapBtn && /карт/i.test(mapBtn.textContent || '')) mapBtn.textContent = 'Дивитися на карті';
                }
                var desc = document.querySelector('.col-sm-8 .object-description');
                var shortBox = document.querySelector('.object-shortinfo');
                var featureList = document.querySelector('.object-list');
                var story = desc && desc.querySelector('[itemprop="description"]');
                if (desc && featureList && story && featureList.compareDocumentPosition(story) & Node.DOCUMENT_POSITION_FOLLOWING) {
                    desc.insertBefore(featureList, story);
                }
                ['costs', 'tax', 'floor'].forEach(function (id) {
                    var link = document.querySelector('.object-tab a[href="#' + id + '"]');
                    var pane = document.getElementById(id);
                    if (!link || !pane) return;
                    var li = link.parentNode;
                    function filled() {
                        if (pane.querySelector('img, table, svg, iframe, canvas')) return true;
                        return (pane.textContent || '').replace(/\s+/g, '').length > 12;
                    }
                    function sync() { if (li) li.style.display = filled() ? '' : 'none'; }
                    sync();
                    if (window.MutationObserver) new MutationObserver(sync).observe(pane, {childList: true, subtree: true});
                });
                var menus = document.querySelectorAll('.nov-bottom__menu--object');
                for (var mi = 1; mi < menus.length; mi++) menus[mi].style.display = 'none';
                var offer = document.querySelector('.your-price');
                var priceWrap = document.querySelector('.object__price');
                if (offer && priceWrap && offer.parentNode !== priceWrap) priceWrap.appendChild(offer);
                var fav = document.querySelector('[data-favorites]');
                var flatId = fav ? fav.getAttribute('data-favorites') : '';
                if (flatId && window.jQuery && priceWrap && !document.querySelector('.ph-box')) {
                    window.jQuery.ajax({
                        url: '/',
                        type: 'POST',
                        dataType: 'json',
                        data: {ajax: 'priceHistory', id: flatId},
                        success: function (res) {
                            var rows = res && res.rows ? res.rows : [];
                            if (!rows.length) return;
                            var box = document.createElement('div');
                            box.className = 'ph-box';
                            var title = document.createElement('div');
                            title.className = 'ph-title';
                            title.textContent = t('История изменения цены', 'Історія зміни ціни');
                            box.appendChild(title);
                            rows.forEach(function (row, idx) {
                                var line = document.createElement('div');
                                line.className = 'ph-row';
                                var date = document.createElement('div');
                                date.className = 'ph-date';
                                var raw = row.start_date || '';
                                var m = /^(\d{4})-(\d{2})-(\d{2})/.exec(raw);
                                date.textContent = m ? m[3] + '.' + m[2] + '.' + m[1] : raw;
                                var delta = document.createElement('div');
                                delta.className = 'ph-delta';
                                if (rows[idx + 1]) {
                                    var diff = Math.round(parseFloat(row.price) - parseFloat(rows[idx + 1].price));
                                    if (diff) {
                                        delta.textContent = (diff > 0 ? '+' : '') + String(diff).replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                                        delta.className += diff > 0 ? ' up' : ' down';
                                    }
                                }
                                var price = document.createElement('div');
                                price.className = 'ph-price';
                                price.textContent = String(Math.round(parseFloat(row.price) || 0)).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' $';
                                line.appendChild(date);
                                line.appendChild(delta);
                                line.appendChild(price);
                                box.appendChild(line);
                            });
                            priceWrap.appendChild(box);
                        }
                    });
                }
                document.querySelectorAll('.object__price__new').forEach(function (el) {
                    var before = el.querySelector('.object__price__before');
                    var span = el.querySelector('[itemprop="price"]') || el;
                    var oldDigits = before ? (before.textContent || '').replace(/\D/g, '') : '';
                    var allDigits = (span.textContent || '').replace(/\D/g, '');
                    var current = allDigits;
                    if (oldDigits && allDigits.length > oldDigits.length && allDigits.slice(-oldDigits.length) === oldDigits) {
                        current = allDigits.slice(0, allDigits.length - oldDigits.length);
                    }
                    function grouped(num) {
                        return String(num).replace(/\B(?=(\d{3})+(?!\d))/g, '\u00a0');
                    }
                    if (span === el) {
                        el.innerHTML = grouped(current) + ' <em class="tr-cur">$</em>';
                    } else {
                        span.textContent = grouped(current) + ' ';
                    }
                    if (before && oldDigits) {
                        before.innerHTML = '<del>' + grouped(oldDigits) + ' $</del>';
                        el.insertBefore(before, el.firstChild);
                    }
                });
                if (desc) {
                    if (shortBox && !desc.querySelector('.tr-object-meta')) {
                        var area = shortBox.querySelector('.object-area');
                        var code = shortBox.querySelector('.object-code');
                        var bits = [];
                        if (area) bits.push(area.textContent.replace(/\s+/g, ' ').trim());
                        if (code) bits.push(code.textContent.replace(/\s+/g, ' ').trim());
                        if (bits.length) {
                            var meta = document.createElement('p');
                            meta.className = 'tr-object-meta';
                            meta.textContent = bits.join(' · ');
                            desc.insertBefore(meta, desc.firstChild);
                        }
                    }
                    if (shortBox && !desc.querySelector('.tr-object-points')) {
                        var heading = (document.querySelector('h1') || {}).textContent || '';
                        heading = heading.replace(/\s+/g, ' ').trim();
                        var points = [];
                        Array.prototype.forEach.call(shortBox.querySelectorAll('p'), function (p) {
                            if (p.classList.contains('object-area') || p.classList.contains('object-code')) return;
                            var text = (p.textContent || '').replace(/\s+/g, ' ').trim();
                            if (!text || text.length < 8 || text === heading) return;
                            points.push(text);
                        });
                        if (points.length) {
                            var list = document.createElement('ul');
                            list.className = 'tr-object-points';
                            points.forEach(function (text) {
                                var item = document.createElement('li');
                                item.textContent = text;
                                list.appendChild(item);
                            });
                            var title = desc.querySelector('.info-title');
                            if (title && title.nextSibling) desc.insertBefore(list, title.nextSibling);
                            else if (story) desc.insertBefore(list, story);
                            else desc.appendChild(list);
                        }
                    }
                    if (story && !desc.querySelector('.tr-desc-more')) {
                        desc.classList.add('is-clamped');
                        var descMore = document.createElement('button');
                        descMore.type = 'button';
                        descMore.className = 'tr-desc-more';
                        descMore.textContent = t('Читать далее', 'Читати далі');
                        descMore.addEventListener('click', function () {
                            var open = desc.classList.toggle('is-open');
                            desc.classList.toggle('is-clamped', !open);
                            descMore.textContent = open ? t('Меньше текста', 'Менше тексту') : t('Читать далее', 'Читати далі');
                        });
                        if (story.nextSibling) desc.insertBefore(descMore, story.nextSibling);
                        else desc.appendChild(descMore);
                    }
                }
            }
        })();


        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var blocks = document.querySelectorAll('.objects, .novelty, .inform, .about, .how, #footer');
        if (!reduce && 'IntersectionObserver' in window) {
            document.documentElement.classList.add('tr-motion');
            var seen = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('tr-in');
                    seen.unobserve(entry.target);
                });
            }, {threshold: 0.12});
            blocks.forEach(function (el) {
                el.classList.add('tr-reveal');
                seen.observe(el);
            });
            var aboutNodes = document.querySelectorAll('#tr-design-about .tr-ab-intro, #tr-design-about .tr-ab-chapter, #tr-design-about .tr-ab-photo-pair, #tr-design-about .tr-ab-brand-scene, #tr-design-about .tr-ab-team-heading, #tr-design-about .tr-ab-branch, #tr-design-about .tr-ab-person, #tr-design-about .tr-ab-contact, #tr-design-about .tr-ab-review, #tr-design-about .tr-ab-why, #tr-design-about .tr-ab-why .why-item');
            aboutNodes.forEach(function (el, index) {
                el.classList.add('tr-rise');
                el.style.setProperty('--d', ((index % 6) * 0.07) + 's');
            });
            window.setTimeout(function () {
                var aboutSeen = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-on');
                        aboutSeen.unobserve(entry.target);
                    });
                }, {threshold: 0.18, rootMargin: '0px 0px -10% 0px'});
                aboutNodes.forEach(function (el) { aboutSeen.observe(el); });
            }, 60);
        }

        syncHeader();
    });
})();
/* TR_HOME_JS_END */
