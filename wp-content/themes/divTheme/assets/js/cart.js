jQuery( document ).ready(function($) {
    $('body').on('click','.add-to-cart', function() {
        let productID = $(this).data('product-id');
        let button = $(this);
        let cartCount = $('.cart-count');

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'add_to_cart',
                product_id: productID,
                quantity: 1
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    button.replaceWith(`
                        <div class="quantity-control" data-product-id="${response.data.product_id}">
                            <button class="decrease-qty">-</button>
                            <span class="product-qty">${response.data.cart_quantity}</span>
                            <button class="increase-qty">+</button>
                        </div>
                    `);
                    cartCount.text(response.data.total_quantity);
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $('body').on('click', '.quantity-control .increase-qty, .quantity-control .decrease-qty', function() {
        let productID = $(this).closest('.quantity-control').data('product-id');
        let qtyElement = $(this).siblings('.product-qty');
        let newQty = parseInt(qtyElement.text());
        let cartCount = $('.cart-count');

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
                        cartCount.text(response.data.total_quantity);
                    } else {
                        $('.quantity-control[data-product-id="' + productID + '"]').replaceWith(`
                                <button class="add-to-cart productCard_btn" data-product-id="${productID}">Add to cart</button>
                            `);
                    }
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    $('body').on('click', '.cart-item .increase-qty, .cart-item .decrease-qty', function() {
        let button = $(this);
        let cartContainer = $('.cart__container');
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
                    if(response.data.total_qty > 0 ) {
                        if (quantity > 0) {
                            quantityElement.val(quantity);
                            $('.cart-total-price').text(response.data.total_price);
                            $('.cart-total-qty').text(response.data.total_qty);
                        } else {
                            cartItem.remove();
                        }
                    }else{
                        cartContainer.html(`
                                <p>Ваша Корзина Пуста</p>
                            `);
                    }
                } else {
                    console.error('Ошибка от сервера:', response.data);
                }
            }
        });
    });
});