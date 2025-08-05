var storeId = $('#psStoreId').val();
var $btnCart = $('#addQuickCart');
var $qty = $(' .qty-view');



$(document).ready(function () {
    if ($(window).width() > 768) {
        $('.cloudzoom').CloudZoom({zoomPosition: 'inside', zoomOffsetX: 0});
    }
    initMainCarousel()
    $(".image-frame").fancybox({
        fitToView: true, closeBtn: false, padding: 0
    });
    $(".imageRateFancybox").fancybox({
        fitToView: true, closeBtn: false, padding: 0
    });
    $('.agencyButton').click(function () {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    });
    //--- tự động dếm số chi nhánh còn hàng
    if ($('.hidden-totalAvaiableStores').length) {
        $('.numberStore>span').html($('.hidden-totalAvaiableStores').val());
    }

    /*----------- change depot Inventories ----------------------*/
    var city = $('#cityIdIvt');
    if (city.length) {
        city.select2();
        city.change(function () {
            showStore($(this).val());
        });
    }
    /*----------- change depot Inventories ----------------------*/
    if ($(window).width() < 768) {
        $(".productSuggest").removeClass('owl-carousel');
        if ($('.productSuggest .productItem').length > 6){
            $('#flip').addClass('open');
        }
        $("#flip").on('click',function () {
            var $this = $(this), $description = $('.productSuggest');
            $this.addClass('active');
            $description.addClass('fill');
            $description.slideDown();
        });
    }else {
        $(".productSuggest").addClass('owl-carousel');
        if ($(".productSuggest .productItem").length) {
            $(".productSuggest").owlCarousel({
                items: 6,
                nav: false,
                dots: true,
                autoplay: false,
                margin: 18,
                loop: false,
                responsive: {
                    0: {
                        items: 2
                    },
                    320: {
                        items: 2,
                        margin: 8,
                        dots: false,
                    },
                    480: {
                        items: 2,
                        margin: 8,
                        dots: false,
                    },
                    768: {
                        items: 2,
                        margin: 8,
                        dots: false,
                    },
                    992: {
                        items: 6
                    }
                },
                navText: ["<i class='fal fa-angle-left'></i>", "<i class='fal fa-angle-right'></i>"]
            });
        }
    }
    $('.title-content').click(function () {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    });

    $('.starbap-subtab__name .selectBox').click(function () {
        $(this).find('.listCorlor').slideToggle();
    });
    // User Ratting Comment
    $('.starbap-write-rev-link').click(function () {
        $('.product-user-vote').slideToggle();
    });

    var vote = $('#userVoteView i');
    vote.hover(
        function () {
            $(this).addClass('voteHover');
            $('#userVoteView i:lt(' + $(this).index() + ')').addClass('voteHover');
            $('#userVoteView i:gt(' + $(this).index() + ')').removeClass('voteHover');
        },

        function () {
            $(this).removeClass('voteHover');
            $('#userVoteView i:lt(' + $(this).index() + ')').removeClass('voteHover');
            $('#userVoteView i:gt(' + $(this).index() + ')').removeClass('voteHover');
        }
    );

    vote.click(function () {
        vote.removeClass('active voted');
        $(this).addClass('active voted');
        $(this).parents('#userVoteView').find('#ratePoint').attr('value',$(this).attr('data-rate'));
        $('#userVoteView i:lt(' + $(this).index() + ')').addClass('active');
    });

    reviewProduct.vote('#btnUserVote','#ratePoint','#title','#commentText','#imageUpload',true);

    $('.req a').tooltip({
        position: {
            my: "center bottom-10",
            at: "center top",
            using: function (position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
            }
        }
    });

    $('.btnAtc').tooltip({
        position: {
            my: "center bottom-10",
            at: "center top",
            using: function (position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
            }
        }
    });

    $('.btn-minus-view').click(function () {
        var v = parseInt($('.qty-view').attr('value'));
        var min = 1;
        if (v > min) {
            $('.qty-view').attr('value', v-1);
            $('.qty-view').html(v - 1);
        }else {
            alert('Bạn phải đặt số lượng tối thiểu là 1 sản phẩm !');
        }
    });
    $('.btn-plus-view').click(function () {
        var max = parseInt($('.qty-view').attr('max'));
        var v = parseInt($('.qty-view').attr('value'));
        if (v < max) {
            $('.qty-view').attr('value', v+1);
            $('.qty-view').html(v + 1);
        }
        else{
            alert('Bạn không thể đặt quá số lượng còn lại của sản phẩm !');
        }
    });
    $('.qty-view').keyup(function () {
        var t = $(this), max = parseInt(t.attr('max')), v = parseInt(t.val());
        if (v >= max) {
            alert('Bạn không thể đặt quá số lượng còn lại của sản phẩm !');
            t.val(max);
        }
        if (v < 1){
            alert('Bạn phải đặt số lượng tối thiểu là 1 sản phẩm !');
            t.val(1);
        }
    });
    $btnCart.click(function () {
        var t = $(this);
        if (t.attr('ck') == 1) {
            // loadings(true);
            let qty = parseInt($qty.val()),
                products = [{id: t.attr('selId'), quantity: qty}],
                color = $('.itemColor a.active').attr('title'),
                size = $('.sizePicker a.active').text(),
                name = $('.infoProductMobile .title-head').text(),
                category = $('#categoryName').val(),
                price = $('.price-box .tp_product_detail_price').attr('data-price');

            if($('.sizePicker a.active').attr('data-price') > 0){
                price = $('.sizePicker a.active').attr('data-price');
            }
            addToCart(products, 1, function(rs){
                // loadings();
                if (rs.status == 1) {
                    if(typeof ga !== 'undefined') {
                        ga('ec:addProduct', {
                            // productFieldObject stores product click and other details
                            'id': t.attr('selId'), // Product ID/SKU – Type: string
                            'name': '' + name + '', // Product name – Type: string
                            'category': '' + category + '', // Product category – Type: string
                            'variant': '' + color + ',' + size + '', // Variant of the product like color, size etc – Type: string
                            'price': price, // Product price – Type: numeric
                            'quantity': qty, // Product quantity – Type: numeric
                        });
                        ga('ec:setAction', 'add'); // measure product add to cart
                        ga('send', 'event', 'enhanced ecommerce', 'button click', 'add to Cart'); // Send 'add to cart' using an event.
                    }
                    var countCart = $('.header__cart-count');
                    countCart.html(parseInt(countCart.html()) + qty);
                    // $('.mini-cart-content').html(rs);
                    if ($(window).width() > 768) {
                        ajaxLoadView({
                            view: 'cartHeader',
                            onSuccess: function (rs) {
                                $(".cartReload").html(rs);
                                $('.cartHeaderContent').slideDown();
                                setTimeout(function () {
                                    $('.cartHeaderContent').slideUp();
                                }, 3000);
                            }
                        });
                    }else {
                        window.location.href = "/cart";
                    }
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        'event': 'addToCart',
                        'ecommerce': {
                            'add': {
                                'products': [{
                                    'name': rs.data.name,
                                    'id': rs.data.id,
                                    'price': rs.data.price,
                                    'brand': '',
                                    'category': $('#categoryName').val(),
                                    'position': 1,
                                    'variant': '',
                                }]
                            }
                        }
                    });
                } else {
                    alert(msgSizeColorProduct);
                }
            });
        }
    });
    $('#buyNow').click(function () {
        var t = $(this);
        if (t.attr('ck') == 1) {
            // loadings(true);
            var qty = parseInt($qty.val()),
                products = [{id: t.attr('selId'), quantity: qty}];
            addToCart(products, 1, function(rs){
                // loadings();
                if (rs.status == 1) {
                    window.location.href = "/cart";
                } else {
                    alert(msgSizeColorProduct);
                }
            });
        }
    });

    $('.childProducts').change(function () {
        var t = $(this),
            val = t.val();
        $.post(
            '/product/child?childId=' + val +'&storeId='+$('#psStoreId').val(),
            function (rs) {
                if (rs.code == 1) {
                    $btnCart.removeAttr('title').removeAttr('data-original-title').attr('ck', 1).attr('selid', rs.data.id);
                    $('#selectPsId').val(rs.data.id);
                    $btnCart.attr('selId', rs.data.id);
                    if(rs.data.moneyDiscount > 0){
                        $('.price-box').html('<span class="discountPrice tp_product_detail_price">'+$.number(rs.data.price) + ' đ</span><del class="product-price-old tp_product_detail_price_old">'+$.number(rs.data.price + rs.data.moneyDiscount) + ' đ</del>');
                    }else if(rs.data.oldPrice > 0){
                        $('.price-box').html('<span class="discountPrice tp_product_detail_price">'+$.number(rs.data.price) + ' đ</span><del class="product-price-old tp_product_detail_price_old">'+$.number(rs.data.oldPrice) + ' ₫</del>');
                    }else{
                        $('.price-box').html('<span class="discountPrice tp_product_detail_price">'+$.number(rs.data.price) + ' đ</span>');
                    }
                } else {
                    $btnCart.attr('title','Vui lòng chọn loại sản phẩm!').attr('data-original-title','Vui lòng chọn loại sản phẩm!').attr('ck', 0);
                }

                // if (rs.data.image != null) {
                //     $('div.swiper-feature img').attr('style', 'opacity:0.8');
                //     setTimeout(function () {
                //         $('div.swiper-feature img').attr('src', rs.data.image).attr('style', 'opacity:1');
                //     }, 150);
                // }
                // $('div.swiper-feature img').attr('data-cloudzoom', "useZoom:'.cloudzoom', image:'" + rs.data.image + "',zoomImage:'" + rs.data.image + "'");
                // $('.cloudzoom').CloudZoom({zoomPosition:'inside', zoomOffsetX:0});
                // if ($('ul.slides li').hasClass('active')) {
                //     $('.slides').trigger('slideTo', '[class$="active"]');
                // }
                // getChildProductImages(val,rs.data.image);
            },
            'json'
        );
    });


    $('.size a').click(function () {
        var t = $(this), size = $('.size a'), qtt = $('.qty-view'), atc = $('#addQuickCart, #buyNow'),attrs = {};
        var colorAt = $('.color a.active');
        if (!t.hasClass('active')) {
            size.removeClass('active');
            atc.attr('title', 'Vui lòng chọn màu sắc hoặc kích cỡ!').attr('ck', 0).addClass('unsel');
            if ($('.color').length && !$('.color a.active').length) {
                size.attr('title', 'Vui lòng chọn màu trước!');
            } else {
                if (t.attr('qtt')) {
                    t.addClass('active');
                    attrs[$('.color').attr('column')] = colorAt.attr('value');
                    attrs[$('.size').attr('column')] = t.attr('value');
                    AppAjax.post(
                        '/product/child?psId=' + atc.attr('psid'),
                        {'attrs': attrs},
                        function (rs) {
                            if (rs.code == 1) {
                                if (rs.data.available > 0) {
                                    if(t.attr('data-price') > 0) {
                                        $('.price-box .discountPrice').text($.number(t.attr('data-price')) + 'đ');
                                        $('.price-box .product-price-old').show();
                                        if (t.attr('data-price') == t.attr('Old-price')||t.attr('Old-price')==0){
                                            $('.price-box .product-price-old').hide();
                                        }else {
                                            $('.price-box .product-price-old').text($.number(t.attr('Old-price')) + 'đ');
                                        }
                                    }else {
                                        $('.price-box .discountPrice').text('Liên hệ');
                                        $('.price-box .product-price-old').hide();
                                    }
                                    qtt.attr('max',rs.data.available);
                                    atc.attr('selId', rs.data.id).removeAttr('title').removeAttr('data-original-title').attr('ck', 1).removeClass('unsel');
                                }else{
                                    atc.attr('title', 'Sản phẩm tạm thời hết hàng!');
                                }

                            } else {
                                atc.attr('title', 'Sản phẩm tạm thời hết hàng!');
                            }
                        },
                        'json'
                    );
                }
            }
        }
    });

    $('.color a').click(function () {
        var t = $(this), size = $('.size a'), qtt = $('.qty-view'), atc = $('#addQuickCart, #buyNow '), attrs = {},src = $(this).attr('data-src');
        if (!t.hasClass('active')) {
            $('.thumbnailImage[data-src="' + src + '"]').click();
            $('.color a').removeClass('active');
            atc.attr('title', 'Vui lòng chọn màu sắc hoặc kích cỡ!').attr('ck', 0).addClass('unsel');
            if (size.length > 1) {
                size.removeClass('active deactive');
                t.addClass('active');
                size.removeAttr('title');
                attrs[$('.color').attr('column')] = t.attr('value');
                size.each(function () {
                    var t = $(this);
                    attrs[$('.size').attr('column')] = t.attr('value');
                    AppAjax.post(
                        '/product/child?psId=' + $('#addQuickCart').attr('psid'),
                        {'attrs': attrs},
                        function (rs) {
                            if (rs.code == 1 && rs.data.available > 0) {
                                t.attr('qtt', rs.data.available).attr('selId', rs.data.id).attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);
                            } else {
                                t.addClass('deactive').attr('title', 'Sản phẩm tạm thời hết hàng!').removeAttr('qtt');
                            }
                        },
                        'json'
                    );
                });

            } else {

                if (t.attr('qtt')) {
                    t.addClass('active');
                    atc.attr('selId', t.attr('selId')).removeAttr('title').attr('ck', 1).removeClass('unsel');
                    atc.removeAttr('data-original-title');
                    qtt.attr('max', t.attr('qtt'));
                    if(t.attr('data-price') > 0) {
                        $('.price-box .discountPrice').text($.number(t.attr('data-price')) + 'đ');
                        $('.price-box .product-price-old').show();
                        if (t.attr('data-price') == t.attr('Old-price')||t.attr('Old-price')==0){
                            $('.price-box .product-price-old').hide();
                        }else {
                            $('.price-box .product-price-old').text($.number(t.attr('Old-price')) + 'đ');
                        }
                    }else {
                        $('.price-box .discountPrice').text('Liên hệ');
                        $('.price-box .product-price-old').hide();
                    }
                }
            }

            // /***
            //  * @type {Array}
            //  * code: 1-get all;
            //  * code: 2-get one
            //  * bothImageSrc: Lấy cả getSrcUri + getThumbnailUri
            //  * */
            // var ps = [{
            //     id: t.attr('data-pids').split(','),
            //     bothImageSrc: true,
            //     code: 1,
            //     storeId: storeId
            // }];
            //
            // if (ps.length) {
            //     getallchildimg(ps, function (rs) {
            //         var src = $(this).attr('data-src'),
            //             proThumList = $('.zoomer'),
            //             smallOwl = $('.listImgZoom'),
            //             mergerOwl = $('.zoomer, .listImgZoom');
            //
            //
            //         $('.zoomer').slick('unslick').empty();
            //         $('.listImgZoom').slick('unslick').empty();
            //
            //         if (rs.images) {
            //             mergerOwl.empty();
            //
            //             var k = 0;
            //             $.each(rs.images, function (vl) {
            //                 k++;
            //                 var srcUri = rs.images[vl].srcUri,
            //                     thumbUri = rs.images[vl].thumbnailUri;
            //
            //                 proThumList.append('<a href="'+ srcUri +'" class="image-frame" data-href="'+ srcUri +'"><img class="cloudzoom" src="'+ srcUri +'" alt="ThumbnailUri" /></a>');
            //                 smallOwl.append('<li data-src="'+ srcUri +'"><a><img src="'+ thumbUri +'" alt="#'+k+'"/></a></li>');
            //             });
            //
            //         }else{
            //             proThumList.append('<a href="'+ src +'" class="image-frame"><img class="cloudzoom" src="'+ src +'" alt="ThumbnailUri" /></a>');
            //         }
            //         initMainCarousel();
            //         if ($(window).width() > 768) {
            //             $('.cloudzoom').CloudZoom({zoomPosition: 'inside', zoomOffsetX: 0});
            //         }
            //         $('.zoomer a').each(function () {
            //             $(this).attr('href',$(this).attr('data-href'));
            //         });
            //     });
            // }

        }
    });

    $('.itemsSize').click(function () {
        if ($(this).hasClass('active')) {
            $(this).closest('ul').slideToggle();
            return false;
        }
        let value = $(this).attr('data-id'),
            column =$(this).attr('data-column'),
            t = $(this),
            color = $('.itemsColor.dataList'),
            colorAt = $('.itemsColor.active'),
            attrs = {},star = '';

        if($('.itemsStar.active').attr('data-value')){
            star = $('.itemsStar.active').attr('data-value')
        }

        $('.itemsSize').removeClass('active');
        t.addClass('active');

        $(this).parents('.chooseSize').find('.sizeName').html($(this).text());
        if(value){
            attrs[column] = value;
            if(colorAt.length > 0 && colorAt.attr('data-id')){
                attrs[colorAt.attr('data-column')] = colorAt.attr('data-id');
                postFiterRate($('.productId').val(), star, attrs);
            }
            else{
                color.each(function () {
                    var t = $(this);
                    attrs[$('.chooseColor').attr('data-column')] = t.attr('data-id');
                    postFiterRate($('.productId').val(), star, attrs);
                });
            }
        }
        else{
            postFiterRate($('.productId').val(), star, attrs);
        }

    });

    $('.itemsColor').click(function () {
        if ($(this).hasClass('active')) {
            $(this).closest('ul').slideToggle();
            return false;
        }
        let value = $(this).attr('data-id'),
            column =$(this).attr('data-column'),
            $this = $(this),
            sizeAt = $('.itemsSize.active'),
            size = $('.itemsSize.dataList'),
            attrs = {},star = '';

        if($('.itemsStar.active').attr('data-value')){
            star = $('.itemsStar.active').attr('data-value')
        }

        $('.itemsColor').removeClass('active');
        $this.addClass('active');

        $(this).parents('.chooseColor').find('.activeName').html($(this).find('.colorName').text());
        $(this).parents('.chooseColor').find('.activeColor').attr('style',$(this).find('.colorContent').attr('style'));


        if(value) {
            attrs[column] = value;
            if (sizeAt.length > 0 && sizeAt.attr('data-id')) {
                attrs[$('.chooseSize').attr('data-column')] = sizeAt.attr('data-id');

                postFiterRate($('.productId').val(), star, attrs);

            } else {
                size.each(function () {
                    let t = $(this);
                    attrs[$('.chooseSize').attr('data-column')] = t.attr('data-id');

                    postFiterRate($('.productId').val(), star, attrs);
                });
            }
        }else{
            postFiterRate($('.productId').val(), star, attrs);
        }


    });

    $('.itemsStar').click(function () {
        if ($(this).hasClass('active')) {
            $(this).closest('ul').slideToggle();
            return false;
        }

        $('.itemsStar').removeClass('active');
        $(this).addClass('active');

        let value = '', attrs = {};

        $(this).parents('.chooseStar').find('.starName').html($(this).find('.colorName').text());

        if ($(this).attr('data-value')) {
            value = $(this).attr('data-value');
        }

        if ($('.itemsColor.active').attr('data-id')) {
            attrs[$('.chooseColor').attr('data-column')] = $('.itemsColor.active').attr('data-id');
            if ($('.itemsSize.active').attr('data-id')) {
                attrs[$('.chooseSize').attr('data-column')] = $('.itemsSize.active').attr('data-id');
                postFiterRate($('.productId').val(), value, attrs);
            } else {
                $('.itemsSize.dataList').each(function () {
                    let t = $(this);
                    attrs[$('.chooseSize').attr('data-column')] = t.attr('data-id');
                    postFiterRate($('.productId').val(), value, attrs);
                });
            }
        } else if (($('.itemsColor.active').attr('data-id'))) {
            attrs[$('.chooseSize').attr('data-column')] = $('.itemsSize.active').attr('data-id');
            $('.itemsColor.dataList').each(function () {
                var t = $(this);
                attrs[$('.chooseColor').attr('data-column')] = t.attr('data-id');
                postFiterRate($('.productId').val(), value, attrs);
            })
        } else {
            postFiterRate($('.productId').val(), value, attrs);
        }

    });
    if ($('.product-view-vote-percent-list').length) {
        $('.product-view-vote-percent-list').each(function () {
            $('.show-vote-' + $(this).attr('data-point')).html($(this).html())
        });
    }
    checkInv();
    if ($(window).width() < 768) {
        $(window).scroll(function () {
            var headerHeight = parseInt($('.fixedViewTop').height());
            if ($(window).scrollTop() > headerHeight) {
                $('.fixedViewTop').addClass("fixed");
            } else {
                $('.fixedViewTop').removeClass("fixed");
            }
        });
        $('.childView').click(function () {
            var type = $(this).attr('data-type');
            $('.childView').removeClass('active');
            $(this).addClass('active');
            $('html, body').animate({
                scrollTop: $('.boxItem[data-name="' + type + '"]').offset().top - 230
            }, 1000)
        });
    }
    var prdInfo = $('#prdInfo');
    dataLayer.push({ ecommerce: null });
    dataLayer.push({
        'ecommerce': {
            'detail': {
                'actionField': {'list': 'Apparel Gallery'},
                'products': [{
                    'name': prdInfo.attr('data-name'),
                    'id': prdInfo.attr('data-id'),
                    'price': prdInfo.attr('data-price'),
                    'brand': '',
                    'category': prdInfo.attr('data-category'),
                    'variant': ''
                }]
            }
        }
    });



});
function initMainCarousel(){
    $('.zoomer').slick({
        Swipe: true,
        arrows:false,
        infinite: false,
        touchMove: true,
        draggable: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        vertical: false,
        Accessibility: true,
        adaptiveHeight:false,
        nextArrow: '<button type="button" class="slick-next"><i class="far fa-angle-right"></i></button>',
        prevArrow: '<button type="button" class="slick-prev"><i class="far fa-angle-left"></i></button>',
        asNavFor: ".listImgZoom",
        responsive:[
            {
                breakpoint: 1025,
                settings:{ slidesToShow: 1}
            },{
                breakpoint: 768,
                settings:{slidesToShow: 1, arrows:true}
            }]
    });
    $('.listImgZoom').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        vertical: true,
        verticalSwiping: true,
        arrows:false,
        Swipe: true,
        infinite: false,
        touchMove: true,
        draggable: true,
        Accessibility: true,
        adaptiveHeight:false,
        focusOnSelect: true,
        // centerMode: true,
        // centerPadding :50,
        // prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
        // nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
        asNavFor: ".zoomer",
        responsive:[
            {
                breakpoint: 1025,
                settings:{ slidesToShow: 5}
            },{
                breakpoint: 768,
                settings:{slidesToShow: 4, vertical:false}
            }]
    });
}
function checkInv() {
    var req = $('.req').length, attrs = {}, atc = $('#addQuickCart , #buyNow'), qtt = $('.qty-view');
    if (req == 1) {

        if ($('.color').length) {
            if ($('.color a.active').length) {
                attrs[$('.color').attr('column')] = $('.color a.active').attr('value');
                AppAjax.post(
                    '/product/child?psId=' + atc.attr('psid'),
                    {'attrs': attrs},
                    function (rs) {
                        if (rs.code == 1 && rs.data.available > 0) {
                            qtt.attr('max', rs.data.available);
                            atc.attr('selId', rs.data.id).removeAttr('title').attr('ck', 1).removeClass('unsel');
                            atc.removeAttr('data-original-title');
                        } else {
                            atc.attr('title', 'Sản phẩm tạm thời hết hàng!');
                        }
                    },
                    'json'
                );

            } else {
                $('.color a').each(function () {
                    var t = $(this);
                    attrs[$('.color').attr('column')] = t.attr('value');
                    AppAjax.post(
                        '/product/child?psId=' + atc.attr('psid'),
                        {'attrs': attrs},
                        function (rs) {
                            if (rs.code == 1 && rs.data.available > 0) {
                                t.attr('qtt', rs.data.available).attr('selId', rs.data.id).attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);;
                            } else {
                                t.addClass('deactive').attr('title', 'Sản phẩm tạm thời hết hàng!');
                            }
                        },
                        'json'
                    );
                });
            }
        } else {
            if ($('.size a.active').length) {
                attrs[$('.size').attr('column')] = $('.size a.active').attr('value');
                AppAjax.post(
                    '/product/child?psId=' + atc.attr('psid'),
                    {'attrs': attrs},
                    function (rs) {
                        if (rs.code == 1 && rs.data.available > 0) {
                            qtt.attr('max', rs.data.available);
                            atc.attr('selId', rs.data.id).removeAttr('title').attr('ck', 1).removeClass('unsel');
                            atc.removeAttr('data-original-title');
                        } else {
                            atc.attr('title', 'Sản phẩm tạm thời hết hàng!');
                        }
                    },
                    'json'
                );
            } else {
                $('.size a').each(function () {
                    var t = $(this);
                    attrs[$('.size').attr('column')] = t.attr('value');
                    AppAjax.post(
                        '/product/child?psId=' + atc.attr('psid'),
                        {'attrs': attrs},
                        function (rs) {
                            if (rs.code == 1 && rs.data.available > 0) {
                                t.attr('qtt', rs.data.available).attr('selId', rs.data.id).attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);;
                            } else {
                                t.addClass('deactive').attr('title', 'Sản phẩm tạm thời hết hàng!');
                            }
                        },
                        'json'
                    );
                });
            }
        }
        return false;
    }
    if (req > 1) {
        var colorAt = $('.color a.active'), sizeAt = $('.size a.active');
        if (colorAt.length && sizeAt.length) {
            attrs[$('.color').attr('column')] = colorAt.attr('value');
            attrs[$('.size').attr('column')] = sizeAt.attr('value');
            AppAjax.post(
                '/product/child?psId=' + atc.attr('psid'),
                {'attrs': attrs},
                function (rs) {
                    if (rs.code == 1 && rs.data.available > 0) {
                        $('.size a').attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);
                        if($('.size a').attr('data-price') > 0){
                            $('.price-box .discountPrice').text($.number($('.size a').attr('data-price')) + 'đ');
                        }
                        qtt.attr('max', rs.data.available);
                        atc.attr('selId', rs.data.id).removeAttr('title').attr('ck', 1).removeClass('unsel');
                        atc.removeAttr('data-original-title');
                    } else {
                        atc.attr('title', 'Sản phẩm tạm thời hết hàng!');
                    }
                },
                'json'
            );
            return false;
        }
        if (colorAt.length && !sizeAt.length) {
            attrs[$('.color').attr('column')] = colorAt.attr('value');
            $('.size a').each(function () {
                var t = $(this);
                attrs[$('.size').attr('column')] = t.attr('value');
                AppAjax.post(
                    '/product/child?psId=' + atc.attr('psid'),
                    {'attrs': attrs},
                    function (rs) {
                        if (rs.code == 1 && rs.data.available > 0) {
                            t.attr('qtt', rs.data.available).attr('selId', rs.data.id).attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);;
                        } else {
                            t.addClass('deactive').attr('title', 'Sản phẩm tạm thời hết hàng!');
                        }
                    },
                    'json'
                );
            });
            return false;
        }
        if (!colorAt.length && sizeAt.length) {
            attrs[$('.size').attr('column')] = sizeAt.attr('value');
            $('.color a').each(function () {
                var t = $(this);
                attrs[$('.color').attr('column')] = t.attr('value');
                AppAjax.post(
                    '/product/child?psId=' + atc.attr('psid'),
                    {'attrs': attrs},
                    function (rs) {
                        if (rs.code == 1) {
                            t.attr('qtt', rs.data.available).attr('selId', rs.data.id).attr('data-price', rs.data.price).attr('Old-price', rs.data.oldPrice);
                        } else {
                            t.addClass('deactive').attr('title', 'Sản phẩm tạm thời hết hàng!');
                        }
                    },
                    'json'
                );
            });
            return false;
        }
    }
}
function getIvtDepots(psid) {
    ajaxLoadView({
        view: 'loadInventory&psId=' + psid,
        onSuccess: function (rs) {
            $('#stock-box').html(rs);
        }
    });
}
function showStore (cityId) {
    if (cityId) {
        AppAjax.post('/store/depotproduct', {
                cityId: cityId,
                storeId: $('#psStoreId').val(),
            },
            function (rs) {
                $("#stock-box").empty();
                if(rs.length){
                    var inner="";
                    for(var i = 0; i < rs.length; i++) {

                        var obj = rs[i];
                        inner += '<div class="stock">';
                        inner += '<span class="dist"><img src="/tp/T0356/img/maps-and-flags.png" alt="icon store">' +obj.depotName+ ':</span>\n' +
                            '<span class="street">'+obj.phone+'</span>';

                        if (obj.showOnlineQtt > 0){
                            inner += '<span class="timeStore">' + obj.address + ' <strong style="color: #289633;">(Còn hàng)</strong></span>';
                        } else{
                            inner += '<span class="timeStore">' + obj.address + ' <strong class="red">(Hết hàng)</strong></span>';
                        }

                        inner += '</div>';
                    }
                    $("#stock-box").append(inner);
                }else{
                    $("#stock-box").append('<span style="display: block;text-align: center; font-weight: normal">Chưa có cửa hàng nào !!!</span>');
                }
            }
        );
    }
}

function postFiterRate(productId,star,attrs) {
    let inner = '';
    $('.starbap-rev-widg__reviews').empty();
    AppAjax.post(
        '/rating/filterRate?psId=' + productId + '&getStar='+star,
        {'attrs': attrs},
        function (rs) {
            if (rs.data) {
                $.each(rs.data, function(i,p){
                    inner += '<div class="starbap-rev starbap-divider-top clearfix">';
                    inner += '<div class="starbap-rev__header col-lg-2 col-md-2 col-xs-12 col-sm-12">';
                    inner += '<div class="starbap-rev__icon">';
                    inner += '<div class="nameRater">'+p.commentator+'</div>';
                    inner += '<div class="starbap-rev__title">'+p.title+'</div>';
                    inner += '</div>';
                    inner += '</div>';
                    inner += '<div class="starbap-rev__content col-lg-8 col-md-8 col-xs-12 col-sm-12">';
                    inner += '<div class="col-lg-7 col-md-7 col-xs-12 col-sm-12 noPadding">';
                    inner += '<span class="starbap-rev__rating voteView'+p.points+'">';
                    inner += '<a class="starbap-star starbap--off star-1"><i class="fa fa-star"></i></a>';
                    inner += '<a class="starbap-star starbap--off star-2"><i class="fa fa-star"></i></a>';
                    inner += '<a class="starbap-star starbap--off star-3"><i class="fa fa-star"></i></a>';
                    inner += '<a class="starbap-star starbap--off star-4"><i class="fa fa-star"></i></a>';
                    inner += '<a class="starbap-star starbap--off star-5"><i class="fa fa-star"></i></a>';
                    inner += '</span>';
                    inner +='<div class="starbap-rev__body">'+p.comment;
                    inner += '<div class="colorWrapper"> Sản phẩm: <span class="colorText">'+p.options.productName+'</span></div>';
                    inner +='</div>';
                    inner += '</div>';
                    inner += '<div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 noPadding">';
                    inner += '<div class="starbap-rev_imageBox">';
                    if(p.options.rateImages){
                        $.each(p.options.rateImages, function(v,m){
                            inner += '<img class="col-lg-4 col-md-4 col-xs-6 col-sm-6" src="'+m.image+'">';
                        })
                    }
                    inner += '</div>';
                    inner += '</div>';
                    inner += '</div>';
                    inner += '<div class="starbap-rev starbap-divider-bottom col-lg-12 col-md-12 col-xs-12 col-sm-12">';
                    inner += '<span class="starbap-rev__timestamp">'+p.createdDate+'</span>';
                    inner += '</div>';
                    inner += '</div>';
                });
                $('.starbap-rev-widg__reviews').append(inner);
            }
        },
        'json'
    );

}





