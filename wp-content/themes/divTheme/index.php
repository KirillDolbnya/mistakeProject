<?php
/*
Template Name: Каталог
*/

get_header();

$args = [
    'post_type' => 'product',
    'posts_per_page' => -1,
];

$product = new WP_Query($args);

?>

    <div class="catalog catalog-page">
        <div class="container">
            <div class="catalog__container">
                <div class="catalog-page__head">
                    <h2>Каталог</h2>
                </div>

                <?php
                if (!empty($product->posts)){
                    ?>
                    <div class="catalog__items">
                        <?php
                        foreach ($product->posts as $post){
                            $cart = WC()->cart->get_cart();
                            $product_in_cart = false;
                            $cart_quantity = 0;

                            foreach ($cart as $cart_item) {
                                if ($cart_item['product_id'] == get_the_ID()) {
                                    $product_in_cart = true;
                                    $cart_quantity = $cart_item['quantity'];
                                    break;
                                }
                            }
                        ?>
                            <div class="productCard">
                                <div class="productCard_img">
                                    <img src="<?= get_the_post_thumbnail_url($post->ID) ?>" alt="">
                                </div>
                                <div class="productCard__content">
                                    <div class="productCard_name">
                                        <p><?= $post->post_title ?></p>
                                    </div>
                                    <?php if ($product_in_cart): ?>
                                        <div class="quantity-control" data-product-id="<?= get_the_ID(); ?>">
                                            <button class="decrease-qty">-</button>
                                            <span class="product-qty"><?= $cart_quantity; ?></span>
                                            <button class="increase-qty">+</button>
                                        </div>
                                    <?php else: ?>
                                        <button class="add-to-cart productCard_btn" data-product-id="<?= get_the_ID(); ?>">Add to cart</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
<?php
get_footer();