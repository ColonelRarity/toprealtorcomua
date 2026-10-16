// preloader
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



$(document).ready(function () {
    var yearBlock = document.querySelector('.yearN'),
    yearNow = new Date().getFullYear().toString();
    if (yearNow.length) {
        yearBlock.innerText = yearNow;
    }
    $("#slider1 div.sld").owlCarousel({
        nav       : true,
        navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
        items     : 1,
        margin    : 0,
        loop      : true,
        navRewind : false,
        smartSpeed: 1500,
        autoplay  : true
    });
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
    // mobile nav
    if (_user == 'false') {
        var _link2menu = [
        "<a href='#enter' class='js_close-mm' data-toggle='modal'>Вход</a>",
        "<a href='#registration' class='js_close-mm' data-toggle='modal'>Регистрация</a>",
        ];
    } else {
        var _link2menu = [
        "<a href='/?logout'>Выход</a>"];
    }
    $('#menu').mmenu({
        "navbars"   : [
        {
            "position": "bottom",
            "content" : [
            "<a href='https://www.facebook.com/' target='_blank' rel='nofollow' class='icon-facebook'></a>",
            "<a href='https://twitter.com/?lang=ru' target='_blank' rel='nofollow' class='icon-twitter'></a>",
            ]
        },
        {
            "position": "top",
            "content" : _link2menu,
        }
        ],
        "extensions": [
                // "effect-panels-zoom",

                "pagedim-black", "fx-listitems-slide", "fx-menu-slide"
                ],
                navbar      : {
                    'title': 'Меню'
                },
                counters    : true,
            },
            {
                classNames: {
                    selected: "act"
                }
            });
    var API = $("#menu").data("mmenu");
    $(".js_close-mm").click(function () {
        API.close();
    });
    // var api = $("#menu").data( "mmenu" );
    // $('.mm-navbar-top a').on('click',function(){
    //      api.close();
    // })
    // api.bind('opening', function () {
    //     var bodyTop = window.pageYOffset ? window.pageYOffset : (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);;
    //     $('.header').css('top',bodyTop);
    // });
    // mobile nav
    $('.more_view').on('click', function () {
        if ($(this).hasClass('act')) {
            $(this).removeClass('act');
            $('.object-description').find('p').removeClass('visible');
            $(this).text('Читать далее')
        } else {
            $(this).addClass('act');
            $('.object-description').find('p').addClass('visible');
            $(this).text('Свернуть');
        }
        return false;
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
        alert('Объект добавлен в избранное.', '');
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
                alert('Объект добавлен в избранное.', '');
            } else {
                f.splice(fl, 1);
                if (f.length == 0) {
                    $(".js_favorites_many").hide();
                    $(".js_favorites_null").show();
                }
                alert('Объект удален из избранного.', '');
                $(this).removeClass('liked');
            }
        } else {
            f = [];
            f.push(_i);
            $(this).addClass('liked');
            $(".js_favorites_many").show();
            $(".js_favorites_null").hide();
            alert('Объект добавлен в избранное.', '');
        }
        var date = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000);
        setCookie('favorites', JSON.stringify(f), 'expires=' + date.toUTCString());
        return false;
    });
    /* delete object favorites from page favorites*/
    $(document).on("click", '.js_favorites_del', function () {
        $(this).parents('.flat-item').remove();
        if ($(".flat-item").length == 0)
            $(".flat-box .row").text('Вы ничего не добавили в избранное.');
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
                // initial();
            })
            $(document).on('click', '.map-one-object', function () {
               $(this).hide();
                initial();
            });

        }
    /* map search */
    if ($('#map-search').length > 0) {
        // initializ();
        $(document).on('click', '.map-search', function () {
            $(this).hide();
            initializ();
        });
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
    var selector = '.slick-slide:not(.slick-cloned)';

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






    $(".gallery-images").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        infinite : false,
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
            items     : 1,
            margin    : 30,
            // loop      : true,
            // navRewind: false,
            smartSpeed: 450,
            autoplay  : false
        });
    } else {
        owl.owlCarousel({
            nav       : true,
            navText   : ["<span class='icon-arrow'>", "<span class='icon-arrow'>"],
            items     : 1,
            margin    : 30,
            loop      : false,
            // navRewind: false,
            smartSpeed: 450,
            autoplay  : false
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
            data    : 'ajax=info&type=' + $(this).attr("data-info") + "&i=" + $(this).attr("data-i"),
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
    var contentmap = '<div class="objectmap">' +
    '<span href="" class="objectmap__head">Адрес агентства: ' + address + '.</span>' +
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
            reg = /[0-9 -()+]{11}$/;
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
    if (_c == 2) {
        max       = max * 25;
        step      = 20000 * 25;
        last_step = 500000 * 25;
    } else {
        step      = 20000;
        last_step = 500000;
    }
    if ($('#js_nonlinear-cost').length > 0) {
        var nonLinearSlider = document.getElementById('js_nonlinear-cost');
        noUiSlider.create(nonLinearSlider, {
            connect  : true,
            behaviour: 'tap',
            tooltips : true,
            handles  : 1,
            start    : [curr_min, curr_max],
            range    : {
                'min': [min, step],
                '95%': [last_step, step],
                'max': [max]
            },
            slide    : function (event, ui) {
                $('.min-value').text(mi).css({left: $('#slider-container .ui-slider-handle:nth-child(2)').css('left')});
                $('.max-value').text(mx).css({left: $('#slider-container .ui-slider-handle:last-child').css('left')});
            },
            format   : {
                to  : function (value) {
                    var _c_t = $('[name=course]').val();
                    if (_c_t == 2 && value == '25000000') {
                        $("#js_nonlinear-cost .noUi-handle-upper .noUi-tooltip").text("больше 25 млн");
                    }
                    if (_c_t != 2 && value == '1000000') {
                        $("#js_nonlinear-cost .noUi-handle-upper .noUi-tooltip").text("больше 1 млн");
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
            if (c == 'UAH') {
                step      = 20000 * 25;
                last_step = 500000 * 25;
                max       = 135000000;
            } else {
                step      = 20000;
                last_step = 500000;
                max       = 5000000;
            }
            nonLinearSlider.noUiSlider.updateOptions({
                range: {
                    'min': [min, step],
                    '95%': [last_step, last_step],
                    'max': max
                }
            });
            nonLinearSlider.noUiSlider.set([min, max]);
        }

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
                if (value == 1)
                    $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('неделя');
                if (value == 2)
                    $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('месяц');
                if (value == 3)
                    $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('год');
                if (value == 4)
                    $("#js_nonlinear-time .noUi-handle-lower .noUi-tooltip").text('больше года');
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
function initial() {
    var coord = $("#map-one-object").attr("data-coord");
    if (coord != '') {
        $("#map-one-object").attr("data-coord", "");
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
        var mapElement = document.getElementById('map-one-object');
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
        var panorama   = $("[data-panorama]").attr("data-panorama");
        if (panorama != '')
            panorama = '<a href="' + panorama + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
        var cat             = '';
        var app_appartament = '';
        var b               = $("[data-b]").attr("data-b");
        var type            = $("[data-b]").attr("data-type");
        if (b == '1') {
            if (type == '1')
                cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать квартиры в этом доме</a>';
            if (type == '2')
                cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать дома в этом районе</a>';
            if (type == '3')
                cat = '<a href="' + $('[data-cat]').attr('data-cat') + '" class="btn btn-object btn-objectmap">Показать участки в этом районе</a>';
        }
        if (type != '3')
            app_appartament = '<li><span class="icon-bath"></span>' + $('[data-bath]').attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $('[data-beds]').attr("data-beds") + "</li>";
        if ($("[data-type]").attr('data-type') == 3)
            type_area = ' соток ';
        else
            type_area = ' м<sup>2</sup> ';
        var contentmap = '<div class="objectmap">' +
        '<div class="objectmap__head">' + $(".js_map_name").text() + '</div>' +
        '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $('[data-area-map]').attr("data-area-map") + type_area +
        '</li>' +
        app_appartament + '</ul>' +
        cat +
        panorama;
        for (i in markers) {
            infowindow[i] = new google.maps.InfoWindow({
                content: contentmap
            });
            var marker    = new google.maps.Marker({
                position        : new google.maps.LatLng(markers[i].latLng[0], markers[i].latLng[1]),
                map             : map,
                icon            : '/images/mark.png',
                preserveViewport: false
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    closeInfoWindows();
                    infowindow[i].open(map, marker);
                    map.setCenter(marker.getPosition());
                    map.setZoom(15);
                };
            })(marker, i));
        }
    }
}

/* --------------- end map to one object --------------*/

/* --------------- map to list objects --------------*/
function initializ() {

    var mapOptions    = {
        zoom            : 13,
        scrollwheel     : false,
        draggable       : true,
        disableDefaultUI: false,
        mapTypeId       : google.maps.MapTypeId.ROADMAP,
        center          : new google.maps.LatLng(50.00492056, 36.23600491),
        styles          : [{
            "stylers": [
            {
                "hue": "#dce4e9"
            }
            ]
        }]
    };
    var mapElement    = document.getElementById('map-search');
    var map           = new google.maps.Map(mapElement, mapOptions);
    var markersBounds = new google.maps.LatLngBounds();
    var infowindow    = [];

    function closeInfoWindows() {
        for (var j = 0; j < infowindow.length; j++) {
            infowindow[j].close();
        }
    }

    var i            = 0;
    var lastPosition = '';
    var coordsArray  = [];
    $("[data-coord]").each(function () {
        coord = $(this).attr("data-coord").replace(/(.*)(\d{2}\.\d{2,7},\s*\d{2}\.\d{2,7})(.*)/g, '$2');
        // var exists = false;
        // coordsArray.map((curr)=>{
        //     if(curr == coord)
        //         exists = true;
        // })
        // if(!exists){}
        coordsArray.push(coord);
        coord = coord.split(',');
        if (coord.length == 2) {
            if ($(this).attr('data-type') == 3)
                type_area = ' соток ';
            else
                type_area = ' м<sup>2</sup> ';
            var contentmap = '<div class="objectmap">' +
            '<span class="objectmap__head">' + $(this).attr("data-name") + '</span>' +
            '<ul class="objectmap__info"><li><span class="icon-house"></span>' + $(this).attr("data-area-map") + type_area +
            '</li>' + ($(this).attr("data-object-type") != 1 ? '<li><span class="icon-bath"></span>' + $(this).attr("data-bath") + '</li><li><span class="icon-bed"></span>' + $(this).attr("data-beds") +
                '</li>' : '') + '</ul>' +
            '<div class="objectmap__cost">' + $(this).attr("data-price-map") + '</div>' +
            '<a href="' + $(this).attr("data-url") + '" class="btn btn-object btn-objectmap">Подробнее</a>';
            var m          = new google.maps.InfoWindow({
                content: contentmap
            });

            infowindow.push(m);
            var marker = new google.maps.Marker({
                position        : new google.maps.LatLng(coord[0], coord[1]),
                map             : map,
                icon            : '/images/mark.png',
                preserveViewport: false
            });
            markersBounds.extend(marker.getPosition());
            lastPosition = marker.getPosition();
            // map.setCenter(marker.getPosition());
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    closeInfoWindows();
                    infowindow[i].open(map, marker);
                    //map.setCenter(marker.getPosition());
                    //map.setZoom(15);
                };
            })(marker, i));
            i++;
        }
    });
    if (i > 1)
        map.setCenter(markersBounds.getCenter(), map.fitBounds(markersBounds));
    else
        map.setCenter(lastPosition);
}

/* --------------- end map to list objects --------------*/

/* --------------- map to list category --------------*/
function init() {
    var mapOptions = {
        zoom            : 13,
        scrollwheel     : false,
        draggable       : true,
        disableDefaultUI: false,
        mapTypeId       : google.maps.MapTypeId.ROADMAP,
        center          : new google.maps.LatLng(50.00492056, 36.23600491),
        styles          : [{
            "stylers": [
            {
                "hue": "#dce4e9"
            }
            ]
        }]
    };
    var mapElement = document.getElementById('map-objects');
    var map        = new google.maps.Map(mapElement, mapOptions);
    var infowindow = [];

    function closeInfoWindows() {
        for (var j = 0; j < infowindow.length; j++) {
            infowindow[j].close();
        }
    }

    var i = 0;
    $("[data-coord]").each(function () {
        coord = $(this).attr("data-coord");
        if (coord != '') {
            coord        = coord.split(',');
            var panorama = $(this).attr("data-panorama");
            if (panorama != '')
                panorama = '<a href="' + panorama + '" class="btn btn-object btn-objectmap" target="_blank">Панорама</a>';
            var contentmap = '<div class="objectmap">' +
            '<div class="objectmap__head">' + $(this).find('.objects-item__title').text() + '</div>' +
            '<a href="' + $(this).attr("data-cat") + '" class="btn btn-object btn-objectmap">Показать квартиры в этом доме</a>' +
            panorama;
            var m          = new google.maps.InfoWindow({
                content: contentmap
            });
            infowindow.push(m);
            var marker = new google.maps.Marker({
                position        : new google.maps.LatLng(coord[0], coord[1]),
                map             : map,
                icon            : '/images/mark.png',
                preserveViewport: false
            });
            map.setCenter(marker.getPosition());
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    closeInfoWindows();
                    infowindow[i].open(map, marker);
                    map.setCenter(marker.getPosition());
                    map.setZoom(15);
                };
            })(marker, i));
            i++;
        }
    });
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
    if (lang_pair.value)
        lang_pair = lang_pair.value;
    if (lang_pair == '')
        return;
    if (location.hostname == 'toprealtor.com.ua' && lang_pair == 'ru|ru')
        return;
    var lang = lang_pair.split('|')[1];
    if (location.hostname != 'toprealtor.com.ua' && lang_pair == 'ru|ru')
        location.href = unescape(gfg('u'));
    else if (location.hostname == 'toprealtor.com.ua' && lang_pair != 'ru|ru')
        location.href = 'http://translate.google.com/translate?client=tmpg&hl=en&langpair=' + lang_pair + '&u=' + escape(location.href);
    else location.href = 'http://translate.google.com/translate?client=tmpg&hl=en&langpair=' + lang_pair + '&u=' + unescape(gfg('u'));
}

function gfg(name) {
    name        = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS  = "[\\?&]" + name + "=([^&#]*)";
    var regex   = new RegExp(regexS);
    var results = regex.exec(location.href);
    if (results == null)
        return '';
    return results[1];
}

/* --------------- end change lang --------------*/
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
 var placeholder = 'Меня заинтересовал объект ' + "(" + text + ' ,' + code + ")" + '\nСвяжитесь со мной для получения более подробной информации';
 // var text1 = " Свяжитесь со мной для получения более подробной информации"
    console.log(text);
    $("#request").find("textarea").attr("placeholder",placeholder);
    $(".comments").on('focus', function () {
            $("textarea").attr('placeholder', '');
    });
    $('.comments').blur(function() {
        $("textarea").attr("placeholder", placeholder);
    });
    });


$('.js-openpl').on('click', function () {
    var text =  $(this).closest('.flat-item').find('p:first').text().replace(/\s+/g, " ");
    var code = $(this).closest('.flat-item').find('.object-code').text().replace(/\s+/g, " ");
    var placeholder = 'Меня заинтересовал объект ' + "(" + text + ' ,' + code + ")" + '\nСвяжитесь со мной для получения более подробной информации';
    // var text1 = " Свяжитесь со мной для получения более подробной информации"
    console.log(text);
    console.log(code);
    $("#request").find("textarea").attr("placeholder",placeholder);
    $(".comments").on('focus', function () {
        $("textarea").attr('placeholder', '');
    });
    $('.comments').blur(function() {
        $("textarea").attr("placeholder", placeholder);
    });
});


// текст обьекта в модалку
