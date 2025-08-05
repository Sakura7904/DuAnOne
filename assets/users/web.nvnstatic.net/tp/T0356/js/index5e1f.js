var storeId = $('#psStoreId').val();
$(document).ready(function() {
    if ($(".home-slider").length) {
        $(".home-slider").owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            lazyLoad: true,
            autoplay:true,
            loop:true,
            responsive: {
                0: {
                    items: 1
                },
                320: {
                    items: 1,
                    dots: true
                },
                480: {
                    items: 1,
                    dots: true,
                },
                768: {
                    items: 1,
                    dots: true
                },
                992: {
                    items: 1
                }
            }
        });
    }
    if ($('.productPromotionBox').length) {
        ajaxLoadView({
            view: 'loadPromotionProduct', delay: 500,
            onSuccess: function (rs) {
                $('.productPromotionBox').html(rs);
            }
        });
    }
    if ($('.productNewBox').length) {
        ajaxLoadView({
            view: 'loadNewProduct', delay: 600,
            onSuccess: function (rs) {
                $('.productNewBox').html(rs);
            }
        });
    }
    if ($('.productHotBox').length) {
        ajaxLoadView({
            view: 'loadHotProduct', delay: 1200,
            onSuccess: function (rs) {
                $('.productHotBox').html(rs);
            }
        });
    }
    if ($('.productCategoryBox').length) {
        ajaxLoadView({
            view: 'loadBoxCategoryProduct', delay: 1800,
            onSuccess: function (rs) {
                $('.productCategoryBox').html(rs);
            }
        });
    }
    if ($(window).width() <= 768 ){
        $(window).scroll(function () {
            $(window).scroll(function () {
                var height = $(window).scrollTop(),
                    catProductPosition = $('.productCategoryBox').offset().top;
                if (height > catProductPosition) {
                    $('.headTab').addClass('fixed');
                    $('.botHeaderMobile').addClass("close");
                } else {
                    $('.botHeaderMobile').removeClass("close");
                    $('.headTab').removeClass('fixed');
                }
            })
        });
        if ($(".listCate.mobile .wrap-item").length) {
            $(".listCate.mobile").owlCarousel({
                items: 4,
                lazyLoad: true,
                nav: false,
                dots: true,
                autoplay: false,
                margin: 16,
                loop: false,
                responsive: {
                    0: {
                        items: 4
                    },
                    320: {
                        items: 4,
                    },
                    480: {
                        items: 4,
                    },
                    768: {
                        items: 4,
                    },
                    992: {
                        items: 4
                    }
                },
                navText: ["<i class='fal fa-angle-left'></i>", "<i class='fal fa-angle-right'></i>"]
            });
        }
    }
    // if ($(window).width() <= 768 ){
    //     if($('#insPopupNewletter').length){
    //         $.fancybox({
    //             maxWidth    : 400,
    //             fitToView   : true,
    //             autoSize    : true,
    //             autoScale   : true,
    //             closeClick  : false,
    //             openEffect  : 'fade',
    //             closeEffect : 'fade',
    //             scrolling   : false,
    //             padding     : 0,
    //             content: $('#insPopupNewletter'),
    //         });
    //     }
    // }else{
    //     if($('#insPopupNewletter').length){
    //         $.fancybox({
    //             maxWidth    : 770,
    //             minHeight   : 250,
    //             fitToView   : false,
    //             autoSize    : true,
    //             autoScale   : true,
    //             closeClick  : false,
    //             openEffect  : 'fade',
    //             closeEffect : 'fade',
    //             scrolling   : false,
    //             padding     : 0,
    //             content: $('#insPopupNewletter'),
    //         });
    //     }
    // }
});
