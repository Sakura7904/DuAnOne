$(document).ready(function() {
    $(document).on('click', '.removeFav', function () {
        // var t = $(this), psId = t.attr('data-psId');
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
})
