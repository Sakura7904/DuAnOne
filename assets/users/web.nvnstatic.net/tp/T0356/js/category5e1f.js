var storeId = $('#psStoreId').val();
$(document).ready(function() {
    $('.category-filter .toggle').click(function() {
        $(this).toggleClass('active');
        $(this).next().slideToggle();
    });

    $('.headMobile ul li .titleDrop').click(function() {
        $(this).toggleClass('active');
        $('.labelButton .check-box-list').slideUp();
        $(this).next().slideToggle();
    });
    $('.titleCategory').click(function() {
        $('.labelButton .check-box-list').slideUp();
    });

    $('.filter-item').click(function () {
        location.href = $(this).attr('data-filter')
    });

    if ($('#slider-range').length) {
        var price_max = $('#price_to');
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: price_max.attr('data-max'),
            values: [parseInt($('#price_form').attr('data-size')), parseInt($('#price_to').attr('data-size'))],
            slide: function (event, ui) {
                $('#price_form').text($.number(ui.values[0]) + 'đ').attr('data-size', ui.values[0]);
                $('#price_to').text($.number(ui.values[1]) + 'đ').attr('data-size', ui.values[1]);
                window.location.href = addFilter('price', ui.values[0] + ':' + ui.values[1], 3);
            }
        });
    }

    $('.fillterButonMobile').click(function() {
        $('body').addClass('open_drawerFilter');
    });
    $('.closeFilter').click(function() {
        $('body').removeClass('open_drawerFilter');
    });

    if ($(window).width() < 768) {
        if ($('.auto-paginator').length) {
            var isLoadPage = 1;
            var next = parseInt($('input.data-page-next').val()),
                max = parseInt($('input.data-page-last').val()),
                href = addFilter('page', $('input.data-page-next').val(), 3);
            if (next > 1) {
                $('.loadMoreProduct.loading').click(function () {
                    console.log('next ' + next);
                    console.log('max ' + max);
                    console.log('isLoadPage ' + isLoadPage);

                    next = parseInt($('input.data-page-next').val()),
                        href = addFilter('page', $('input.data-page-next').val(), 3);

                    if (next <= max && next > isLoadPage) {
                        isLoadPage = next;
                        $('.loadMoreProduct.loading').hide();
                        $.post(href, {template: 'home/loadview/category', terminal: true, loadview: 'default'},
                            function (rs) {
                                if (rs) {
                                    $('.appendResultProduct').append(rs);
                                    $('.data-page-next').val(1 + next);
                                    if ((1 + next) <= max) {
                                        $('.loadMoreProduct.loading').show();
                                    }
                                }
                            }
                        );
                    }
                });
            } else {
                $('.loadMoreProduct.loading').hide();
            }
        }
    }
});