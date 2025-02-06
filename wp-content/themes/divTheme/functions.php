<?php

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function theme_name_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_script( 'js', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
    wp_enqueue_script( 'cart', get_template_directory_uri() . '/assets/js/cart.js', array(), '1.0.0', true );
    wp_enqueue_script( 'order', get_template_directory_uri() . '/assets/js/order.js', array(), '1.0.0', true );
    wp_localize_script('cart', 'ajaxData', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}

add_action( 'after_setup_theme', 'theme_register_nav_menu' );

function theme_register_nav_menu() {
    register_nav_menu( 'primary', 'Primary Menu' );
}

function remove_admin_bar() {
    return false;
}
add_filter('show_admin_bar', 'remove_admin_bar');



add_action('wp_ajax_add_to_cart', 'product_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'product_add_to_cart');

function product_add_to_cart() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(['message' => 'No product ID']);
        return;
    }

    $product_id = absint($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

    $cart = WC()->cart;
    $cart_item_key = $cart->add_to_cart($product_id, $quantity);

    if ($cart_item_key) {
        WC()->cart->calculate_totals();

        $cart_quantity = 0;
        foreach (WC()->cart->get_cart() as $item) {
            if ($item['product_id'] == $product_id) {
                $cart_quantity += $item['quantity'];
            }
        }

        $total_quantity = WC()->cart->get_cart_contents_count();

        wp_send_json_success([
            'cart_quantity' => $cart_quantity,
            'product_id' => $product_id,
            'total_quantity' => $total_quantity,
        ]);
    } else {
        wp_send_json_error(['message' => 'Could not add product']);
    }
}


add_action('wp_ajax_update_product_quantity', 'custom_ajax_update_quantity');
add_action('wp_ajax_nopriv_update_product_quantity', 'custom_ajax_update_quantity');

function custom_ajax_update_quantity() {
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        wp_send_json_error(['message' => 'Missing data']);
        return;
    }

    $product_id = absint($_POST['product_id']);
    $new_quantity = absint($_POST['quantity']);

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            if ($new_quantity > 0) {
                WC()->cart->set_quantity($cart_item_key, $new_quantity);
                $total_quantity = WC()->cart->get_cart_contents_count();
                wp_send_json_success([
                    'message' => 'Quantity updated',
                    'new_quantity' => $new_quantity,
                    'total_quantity' => $total_quantity,
                ]);
            } else {
                WC()->cart->remove_cart_item($cart_item_key);
                wp_send_json_success(['message' => 'Product removed']);
            }
            return;
        }
    }

    wp_send_json_error(['message' => 'Product not found']);
}

add_action('wp_ajax_update_cart_quantity', 'custom_ajax_update_cart_quantity');
add_action('wp_ajax_nopriv_update_cart_quantity', 'custom_ajax_update_cart_quantity');

function custom_ajax_update_cart_quantity() {
    error_log(print_r($_POST, true));

    if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error(['message' => 'Недостаточно данных', 'post_data' => $_POST]);
        return;
    }

    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $new_quantity = absint($_POST['quantity']);

    $cart = WC()->cart;

    if (!$cart->get_cart_item($cart_item_key)) {
        wp_send_json_error(['message' => 'Product not found in cart']);
        return;
    }

    if ($new_quantity > 0) {
        $cart->set_quantity($cart_item_key, $new_quantity);
    } else {
        $cart->remove_cart_item($cart_item_key);
    }

    $total_quantity = WC()->cart->get_cart_contents_count();
    $total_price = WC()->cart->get_cart_contents_total();


    wp_send_json_success([
        'message' => 'Количество обновлено',
        'total_price' => $total_price,
        'cart_item_key' => $cart_item_key,
        'new_quantity' => $new_quantity,
        'total_qty' => $total_quantity,
    ]);
}

add_action('wp_ajax_apply_coupon', 'apply_coupon');
add_action('wp_ajax_nopriv_apply_coupon', 'apply_coupon');

function apply_coupon() {
    if (!isset($_POST['coupon_code'])) {
        wp_send_json_error(['message' => 'Не указан промокод']);
        return;
    }

    $coupon_code = sanitize_text_field($_POST['coupon_code']);

    $coupon = new WC_Coupon($coupon_code);
    if (!$coupon->get_code()) {
        wp_send_json_error(['message' => 'Купон не найден']);
        return;
    }

    WC()->cart->apply_coupon($coupon_code);
    WC()->cart->calculate_totals();

    $total_price =  WC()->cart->get_cart_contents_total();
    $discount = WC()->cart->get_cart_discount_total();

    wp_send_json_success([
        'total_price' => $total_price,
        'discount' => $discount
    ]);
}


add_action('wp_ajax_process_checkout', 'process_checkout');
add_action('wp_ajax_nopriv_process_checkout', 'process_checkout');

function process_checkout() {
    parse_str($_POST['form_data'], $form_data);

    if (empty($form_data['billing_first_name']) || empty($form_data['billing_last_name']) || empty($form_data['billing_email']) || empty($form_data['billing_phone']) || empty($form_data['billing_address_1'])) {
        wp_send_json_error(['message' => 'Заполните все поля']);
        return;
    }

    $order = wc_create_order();
    $order->set_address([
        'first_name' => sanitize_text_field($form_data['billing_first_name']),
        'last_name'  => sanitize_text_field($form_data['billing_last_name']),
        'email'      => sanitize_email($form_data['billing_email']),
        'phone'      => sanitize_text_field($form_data['billing_phone']),
        'address_1'  => sanitize_text_field($form_data['billing_address_1']),
    ], 'billing');

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $order->add_product(wc_get_product($cart_item['product_id']), $cart_item['quantity']);
    }

    $applied_coupons = WC()->cart->get_applied_coupons();
    if (!empty($applied_coupons)) {
        foreach ($applied_coupons as $coupon_code) {
            $coupon = new WC_Coupon($coupon_code);
            $discount_amount = WC()->cart->get_coupon_discount_amount($coupon_code);

            if ($discount_amount > 0) {
                $order->add_coupon($coupon_code, $discount_amount);
            }
        }
        $order->update_meta_data('_used_coupons', implode(', ', $applied_coupons));
    }

    $discount_total = WC()->cart->get_cart_discount_total();
    if ($discount_total > 0) {
        $order->set_discount_total($discount_total);
    }

    $order->calculate_totals();
    $order->save();
    $order_id = $order->get_id();

    WC()->cart->empty_cart();

    wp_send_json_success([
        'order_id' => $order_id,
        'discount_total' => $discount_total,
        'applied_coupons' => $applied_coupons
    ]);
}