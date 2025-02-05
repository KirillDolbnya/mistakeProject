jQuery( document ).ready(function($) {
    $("body").on('submit', '#checkout-form', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'process_checkout',
                form_data: formData
            },
            // beforeSend: function() {
            //     $('#order-result').html('<p>Обработка заказа...</p>');
            // },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alert(`Заказ успешно оформлен! №\' + ${response.data.order_id}`)
                    $('.cart__container').html('<p>Ваша корзина пуста</p>');
                } else {
                    $('#order-result').html('<p style="color: red;">Ошибка: ' + response.data.message + '</p>');
                }
            },
            error: function() {
                $('#order-result').html('<p style="color: red;">Ошибка сервера</p>');
            }
        });
    });

    $("body").on('click', '#apply-promo', function(e) {
        e.preventDefault();

        let promoCode = $('#promo-code').val();

        if (!promoCode) {
            $('#promo-message').html('<p style="color: red;">Введите промокод</p>');
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajaxData.ajaxurl,
            data: {
                action: 'apply_coupon',
                coupon_code: promoCode
            },
            beforeSend: function() {
                $('#promo-message').html('<p>Проверка промокода...</p>');
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    $('#promo-message').html('<p style="color: green;">Купон применен! Скидка: ' + response.data.discount + '</p>');
                    $('.cart-total-price').text(response.data.total_price);
                } else {
                    $('#promo-message').html('<p style="color: red;">' + response.data.message + '</p>');
                }
            }
        });
    });
});