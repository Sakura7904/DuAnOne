var storeId = $('#psStoreId').val();
$(document).ready(function() {
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
                        $.post(href, {template: 'home/loadview/promotion', terminal: true, loadview: 'default'},
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