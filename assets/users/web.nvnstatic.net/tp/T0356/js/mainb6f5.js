var storeId = $('#psStoreId').val();
$(document).ready(function() {
    $('.searchBtn .openSearch').click(function() {
        $('.boxSearchHeader').addClass('activeSearch');
    });
    $('.searchBtn .closeSearch').click(function() {
        $('.boxSearchHeader').removeClass('activeSearch');
    });

    $(document).on('click', '.iconLeft .bar-tool a', function(){
        $('body').addClass('open_drawer');
    });
    // $(document).on('click', '.closeBar', function(){
    //     $('body').removeClass('open_drawer');
    // });
    $('.closeBar').click(function() {
        $('body').removeClass('open_drawer');
    });
    $('.overlay_chir.menu').click(function() {
        $('body').removeClass('open_drawer');
        $('body').removeClass('open_drawerSearch');
        $('body').removeClass('open_drawerFilter');
    });
    $(document).on('click', '.wrapBoxSearch .closeSearch', function(){
        $('body').removeClass('open_drawerSearch');
    });
    $(document).on('click', '.chir_menu_mobile li a i', function(e){
        e.preventDefault();
        $(this).parent().toggleClass('open').next().slideToggle();
    });
    $(document).on('click', '.openSearchMobile', function(){
        $('body').addClass('open_drawerSearch');
    });
    $(document).on('click', '.cartBtn .cartBtnOpen', function(){
        // $('body').addClass('open_drawerCart');
        $('.cartHeaderContent').slideToggle();
        $('.userBox').css({'display' : 'none'});
    });
    $(document).on('click', '.userBtn .userBtnOpen', function(){
        // $('body').addClass('open_drawerCart');
        $('.userBox').slideToggle();
        $('.cartHeaderContent').css({'display' : 'none'});
    });

    if ($('.cartReload').length) {
        ajaxLoadView({
            view: 'cartHeader',
            delay: 4000,
            onSuccess: function (rs) {
                $(".cartReload").html(rs);
            }
        });
    }

    $(".fancybox-album").fancybox({
        fitToView: true, closeBtn: false, padding: 0
    });

    $('.footer-registration__btn').click(function (e) {
        e.preventDefault();
        $.post('/newsletter/subscribe', {
                mail: $('.footer-registration__input').val(),
            },
            function (rs) {
                if (rs.code == 1) {
                    $('.footer-registration__input').val('');
                    window.location.reload();
                }
                alert(rs.message);
            });
    });

    $('.mobile-registration__btn').click(function (e) {
        e.preventDefault();
        $.post('/newsletter/subscribe', {
                mail: $('.mobile-registration__input').val(),
            },
            function (rs) {
                if (rs.code == 1) {
                    $('.mobile-registration__input').val('');
                    window.location.reload();
                }
                alert(rs.message);
            });
    });

    $(".color-swatches li").hover(function (e) {
        e.preventDefault();
        $(this).parents(".color-swatches").find("li").removeClass("swatch-active");
        $(this).addClass("swatch-active");
        var imgVariant = $(this).attr("data-img");
        $(this).parents(".productItem").find(".productImage a img").attr("src", imgVariant);
        $(this).parents(".productItem").find(".productImage a img").attr("src", imgVariant);
    });


    var ps = [];
    $('.productItem').each(function () {
        var t = $(this);
        ps.push({id: t.attr('data-id')});
    });
    WishListLoad(ps);
    /*****************************************************
     * Product Whishlist Cookie
     * ****************************************************/
    $('.wishlistItems').click(function () {
        var t = $(this);
        if(t.hasClass('active')){
            window.location.href = '/wishlist'
        } else {
            $.post(
                '/product/wishlistcookie', {
                    'productId': t.attr('data-id'),
                    'type': 5
                },
                function (rs) {
                    var mes = $('#dialogMessage');
                    if (rs.code == 1) {
                        t.addClass('active');
                        alert("Thêm vào danh sách yêu thích thành công");
                        $('.wishlistCount').html(parseInt($('.wishlistCount').html()) + 1);
                    } else {
                        alert("Thêm vào danh sách yêu thất bại");
                    }
                },
                'json'
            );
            setTimeout(function () {
                $('.tooltip.left').hide();
            },2000);
        }
    });

    $(document).on('click', '.removeWishlistItem', function () {
        if (confirm('Xóa sản phẩm khỏi danh sách yêu thích?') == true) {
            // Xóa yêu thích
            $.post('/product/wishlistcookie', {
                    // 'productId': psId,
                    'productId' : $(this).attr('data-id'),
                    'type': 6
                },
                function (rs) {
                    if (rs.code == 1) {
                        location.reload();
                    }
                },
                'json'
            );

        }
    });

    $(window).scroll(function () {
        var headerHeight = parseInt($('.main_nav_header').height());
        if ($(window).scrollTop() > headerHeight) {
            $('.botHeader').addClass("fixed");
            $('.botHeaderMobile').addClass("fixed");
        } else {
            $('.botHeader').removeClass("fixed");
            $('.botHeaderMobile').removeClass("fixed");
        }
    });

    if ($('body .productItem').length) {
        var ps = [];
        $('.productItem').each(function () {
            ps.push({storeId: storeId, id: $(this).attr('data-id')});
        });
        if (ps.length) {
            checkInventory(ps, function (rs) {
                if (rs.inventories != "") {
                    $.each(rs.inventories, function (key, vl) {
                        if (vl <= 0) {
                            $('.prd'+key).find('.productImage').append('<span class="wrappLabel"><span class="label-sold">Hết hàng</span></span>');
                        }
                    });
                }
            });
        }
    }
});
function WishListLoad(ps) {
    if (ps.length) {
        if($('.checkCookies').val() != "") {
            var esult = JSON.parse($('.checkCookies').val());
            $.each(esult, function (key, vl) {
                if (vl <= 0) {
                    $('.prd' + key + ' .wishlistItems').removeClass('active');
                    $('.prdView' + key + ' .wishlistItems').removeClass('active');
                } else {
                    $('.prd' + key + ' .wishlistItems').addClass('active');
                    $('.prdView' + key + ' .wishlistItems').addClass('active');
                }
            });
        }
    }
}
var prdListInfo = $('#prdListInfo')
if(typeof dataLayer !== 'undefined') {
    dataLayer.push({ecommerce: null});
    dataLayer.push({

        'ecommerce': {
            'currencyCode': '',
            'impressions': [
                prdListInfo
            ]
        }
    });
}
$('.productItem ').click(function (){
    let pClick = $(this).find('.pclickGA');
    if(typeof dataLayer !== 'undefined') {
        dataLayer.push({ecommerce: null});
        dataLayer.push({
            'event': 'productClick',
            'ecommerce': {
                'click': {
                    'actionField': {'list': 'Search Results'},
                    'products': [{
                        'name': pClick.attr('data-name'),
                        'id': pClick.attr('data-id'),
                        'price': pClick.attr('data-price'),
                        'brand': '',
                        'category': pClick.attr('data-category'),
                        'variant': '',
                        'position': ''
                    }]
                }
            }
        });
    }
})
