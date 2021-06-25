jQuery(document).ready(function ($) {

    $(".site-header .form-section").on( 'click', function () {
        $(".site-header .form-section .form-holder").css("display", "block");
        $("body").addClass("menu-open");
    });

    $('.form-holder .btn-form-close').on( 'click', function () {
        $(".site-header .form-section .form-holder").css("display", "none");
        $("body").removeClass("menu-open");
    });

    $('.overlay').on( 'click', function () {
        $('.site-header .form-section .form-holder').css("display", "none");
        $('body').removeClass("menu-open");
    });

    $('.site-header .form-section .form-holder').on( 'click', function (event) {
        event.stopPropagation();
    });

    var winWidth = $(window).width();
    if (winWidth < 1025) {

        $('#toggle-button').on( 'click', function () {
            $('body').addClass('menu-open');
            $('.mobile-navigation').addClass('open');
            $('.mobile-navigation').addClass('toggled');
            $('#mobile-site-navigation .primary-menu-list').addClass('toggled');

        });

        $('.close').on( 'click', function () {
            $('body').toggleClass('menu-open');
            $('.mobile-navigation').removeClass('open');
            $('.mobile-navigation').removeClass('toggled');
            $('#mobile-site-navigation .primary-menu-list').removeClass('toggled');

        });

        $('.overlay').on( 'click', function () {
            $('body').removeClass('menu-open');
            $('.mobile-navigation').removeClass('open');
            $('.mobile-navigation').removeClass('toggled');
                $('#mobile-site-navigation .primary-menu-list').removeClass('toggled');
        });
    }

    //menu accessibility
    $('<button class="angle-down"><i class="fas fa-chevron-down"></i></button>').insertAfter($('.mobile-navigation ul .menu-item-has-children > a'));
    $('.mobile-menu ul li .angle-down').on( 'click', function () {
        $(this).next().slideToggle();
        $(this).toggleClass('active');
    });
     
    //mobile menu

    $('#toggle-button').on( 'click', function () {
        $('body').addClass('menu-open');
        $('.mobile-navigation').addClass('open');
        $('.mobile-navigation').addClass('toggled');
        $('#mobile-site-navigation .primary-menu-list').addClass('toggled');

    });

    $('.close').on( 'click', function () {
        $('body').removeClass('menu-open');
        $('.mobile-navigation').removeClass('open');
        $('.mobile-navigation').removeClass('toggled');
        $('#mobile-site-navigation .primary-menu-list').removeClass('toggled');

    });

    $(window).on("load, resize", function() {
        var viewportWidth = $(window).width();
        if (viewportWidth < 1025) {
            $('.overlay').on( 'click', function () {
                $('body').removeClass('menu-open');
                $('.mobile-navigation').removeClass('open');
                $('.mobile-navigation').removeClass('toggled');
                    $('#mobile-site-navigation .primary-menu-list').removeClass('toggled');
            });
        }
        else if (viewportWidth> 1025) {
            $('body').removeClass('menu-open');
            $('.mobile-navigation').removeClass('open');
            $('.mobile-navigation').removeClass('toggled');
                $('#mobile-site-navigation .primary-menu-list').removeClass('toggled');
        }
    });



    if (winWidth > 1024) {
        $("#site-navigation ul li a").on( 'focus', function () {
            $(this).parents("li").addClass("focus");
        }).on( 'blur', function () {
            $(this).parents("li").removeClass("focus");
        });
    }

    // cart open-close
    $(".site-header .right .tools .cart").on( 'click', function () {
        $(".site-header .right .tools .cart .product-holder").slideToggle();
    });

    /* Portfolio Isotope Filter */
    if (($('.page-template-portfolio').length > 0) && (digital_download.is_rtc_active == 1)) {
        // init Isotope
        var $grid = $('.item-holder').imagesLoaded(function () {
            $grid.isotope({
                // options
            });
            // filter items on select
            $('.filter-button-group').on('click', 'button', function () {
                $('.filter-button-group button').removeClass('is-active');
                $(this).addClass('is-active');
                var filterValue = $(this).attr('data-filter');
                $grid.isotope({ filter: filterValue });
            });
        });
    }
});
