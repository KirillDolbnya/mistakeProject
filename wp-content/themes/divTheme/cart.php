<?php
/*
Template Name: cart
*/

get_header();
?>

    <div class="cart">
        <div class="container">
            <div class="cart__container">
                <?php
                $cart = WC()->cart->get_cart();

                if (!empty($cart)):
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
                                <p>Общая цена: <span class="cart-total-price"><?=wc_price($total_price); ?></span></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Ваша корзина пуста.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>


<?php
get_footer();
