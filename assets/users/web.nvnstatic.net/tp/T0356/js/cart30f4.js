var storeId = $('#psStoreId').val();
$(document).ready(function() {
	$('.remove-item-cart').click(function(){
		var psId = $(this).attr('data-id');
		if(psId > 0){
			var check = confirm('Bạn muốn xóa sản phẩm ra khỏi giỏ hàng ?');
			if (check) {
				if(typeof dataLayer !== 'undefined') {
					dataLayer.push({ecommerce: null});
					dataLayer.push({
						'event': 'removeFromCart',
						'ecommerce': {
							'remove': {
								'products': [{
									'name': $(this).attr('data-name'),
									'id': $(this).attr('data-id'),
									'price': $(this).attr('data-price'),
									'brand': '',
									'category': '',
									'variant': '',
									'quantity': 1
								}]
							}
						}
					});
				}
				$.post(
					'/cart/remove',
					{
						'psId': psId
					},
					function (rp) {
						window.location.reload();
					}
				);
			}
		}
	});
	$('input.js-quantity-product').keyup(function () {
		var t = $(this), max = parseInt(t.attr('maxlength')), v = parseInt(t.val());
		if (v >= max) {
			alert('Bạn không thể đặt quá số lượng còn lại của sản phẩm !');
			t.val(max);
		}
	});
	$('.btn-minus').on('click', function () {
		var item = $(this).next('input.js-quantity-product'),
			min = 1, qty = parseInt(item.val())
		if (qty > min) {
			qty--;
			item.val(qty);
			changeQtyCartIndex(item.attr('data-id'), qty);

		} else {
			alert('Bạn phải đặt số lượng tối thiểu là 1 sản phẩm !');
		}
	});

	$('.btn-plus').on('click', function () {
		var item = $(this).prev('input.js-quantity-product'),
			max = parseInt(item.attr('maxlength')),
			qty = parseInt(item.val());
		qty++;
		if (qty <= max) {
			item.val(qty);
			changeQtyCartIndex(item.attr('data-id'), qty);

		} else {
			alert('Bạn không thể đặt quá số lượng còn lại của sản phẩm !');
		}
	});
});
/*** CART SCRIPT ***/

function changeQtyCartIndex(id, qty) {
	var products = [{id: id, quantity: qty}];
	addToCart(products, 2, function (rs) {
		if (rs.status) {
			window.location.reload();
		}
	});
}


let cartInfo = JSON.parse($("#cartInfo").val());
$(".btn-checkout").click(function () {
	if(typeof dataLayer !== 'undefined') {
		dataLayer.push({ecommerce: null});
		dataLayer.push({
			'event': 'checkout',
			'ecommerce': {
				'add': {
					'products': cartInfo
				}
			}
		})
	}
});