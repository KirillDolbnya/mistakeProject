jQuery( document ).ready(function($) {

    $('.header__btn-menu').click(function (){
        if($('.header__menu').is(':hidden')){
            $('.header__menu').slideDown();
            $('body').css('overflow', 'hidden');
        }else{
            $('.header__menu').slideUp();
            $('body').css('overflow', 'visible');
        }
    });

    $('.add-to-cart').on('click', function() {
        let productID = $(this).data('product-id');
        let button = $(this);

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'add_to_cart',
                product_id: productID,
                quantity: 1
            },
            success: function (response) {
                if (response.success) {
                    button.replaceWith(`
                    <div class="quantity-control" data-product-id="${response.product_id}">
                        <button class="decrease-qty">-</button>
                        <span class="product-qty">${response.cart_quantity}</span>
                        <button class="increase-qty">+</button>
                    </div>
                `);
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $(document).on('click', '.quantity-control .increase-qty, .quantity-control .decrease-qty', function() {
        let productID = $(this).closest('.quantity-control').data('product-id');
        let qtyElement = $(this).siblings('.product-qty');
        let newQty = parseInt(qtyElement.text());

        if ($(this).hasClass('increase-qty')) {
            newQty++;
        } else {
            newQty = Math.max(0, newQty - 1);
        }

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'update_product_quantity',
                product_id: productID,
                quantity: newQty
            },
            success: function(response) {
                if (response.success) {
                    if (newQty > 0) {
                        qtyElement.text(newQty);
                    } else {
                        $('.quantity-control[data-product-id="' + productID + '"]').replaceWith(`
                            <button class="add-to-cart" data-product-id="${productID}">Add to cart</button>
                        `);
                    }
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $(document).on('click', '.cart-item .increase-qty, .cart-item .decrease-qty', function() {
        let button = $(this);
        let cartItem = button.closest('.cart-item');
        let cartItemKey = cartItem.attr('data-cart-item-key');
        let quantityElement = cartItem.find('input');
        let quantity = parseInt(quantityElement.val());

        if (!cartItemKey) {
            console.error('Ошибка: cart_item_key не найден в HTML', cartItem);
            return;
        }

        if (button.hasClass('increase-qty')) {
            quantity++;
        } else {
            quantity = Math.max(0, quantity - 1);
        }

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'update_cart_quantity',
                cart_item_key: cartItemKey,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    if (quantity > 0) {
                        quantityElement.val(quantity);
                        cartItem.find('.cart-total-price').text(response.new_total_price);
                    } else {
                        cartItem.remove();
                    }
                } else {
                    console.error('Ошибка от сервера:', response.data);
                }
            }
        });
    });
});