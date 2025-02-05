<?php
/*
Template Name: cart
*/

get_header();

$cart = WC()->cart->get_cart();
?>

    <div class="cart">
        <div class="container">
            <div class="cart__container">
                <?php
                if (!empty($cart)){
                ?>
                <div class="cart-items">
                    <?php
                    foreach ($cart as $cart_item_key => $cart_item):
                        $product = wc_get_product($cart_item['product_id']);
                        $product_price = $product->get_price();
                        $product_name = $product->get_name();
                        $product_image = get_the_post_thumbnail_url($cart_item['product_id'], 'thumbnail');
                        $quantity = $cart_item['quantity'];
                        $total_price = $product_price * $quantity;
                    ?>
                    <div class="cart-item" data-cart-item-key="<?= $cart_item_key ?>">
                        <div class="cart-item__image">
                            <img src="<?= esc_url($product_image); ?>" alt="<?= esc_attr($product_name); ?>">
                        </div>
                        <div class="cart-item__info">
                            <p><?= esc_html($product_name); ?></p>
                            <p>Цена за шт.: <?= wc_price($product_price); ?></p>
                            <div class="cart-item__quantity">
                                <button class="decrease-qty" data-product-id="<?= $cart_item['product_id']; ?>">-</button>
                                <input type="text" value="<?= esc_attr($quantity); ?>" readonly>
                                <button class="increase-qty" data-product-id="<?= $cart_item['product_id']; ?>">+</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-info">
                    <p>Общая цена: <span class="cart-total-price"><?= WC()->cart->get_cart_contents_total(); ?></span> ₽</p>
                    <p>Общее кол-во: <span class="cart-total-qty"><?= WC()->cart->get_cart_contents_count(); ?></span></p>
                    <div class="cart-discount">
                        <input type="text" id="promo-code" placeholder="Введите промокод">
                        <button id="apply-promo">Применить</button>
                        <div id="promo-message"></div>
                    </div>
                    <form id="checkout-form">
                        <input placeholder="name" type="text" name="billing_first_name">
                        <input placeholder="surname" type="text"  name="billing_last_name">
                        <input placeholder="email" type="email" name="billing_email">
                        <input placeholder="phone" type="text" name="billing_phone">
                        <input placeholder="address" type="text" name="billing_address_1">
                        <button type="submit">Оформить</button>
                    </form>
                    <div id="order-result"></div>
                </div>
                <?php
                }else{
                ?>
                <p>Ваша Корзина Пуста</p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>


<?php
get_footer();
