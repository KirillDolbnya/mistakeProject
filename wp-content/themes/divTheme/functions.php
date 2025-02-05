<?php

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function theme_name_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_script( 'js', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );
    wp_localize_script('js', 'ajaxData', [
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
        $cart_contents = $cart->get_cart();
        $cart_quantity = $cart_contents[$cart_item_key]['quantity'];

        wp_send_json_success([
            'cart_quantity' => $cart_quantity,
            'product_id' => $product_id
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
                wp_send_json_success(['message' => 'Quantity updated', 'new_quantity' => $new_quantity]);
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

    wp_send_json_success([
        'message' => 'Количество обновлено',
        'new_total_price' => wc_price($cart->get_cart_contents_total()),
        'cart_item_key' => $cart_item_key,
        'new_quantity' => $new_quantity
    ]);
}