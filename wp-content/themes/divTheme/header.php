<?php

$pageID = get_the_ID();
$class = '';

if($pageID == 16){
    $class = 'header-fix';
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.cdnfonts.com/css/inter" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <title>divProject</title>
    <?php wp_head(); ?>
</head>
<body>
<header class="header <?= $class ?>">
    <div class="container">
        <div class="header__container">
            <a href="/" class="header__logo">
                <img src="<?= CFS()->get('logo',16) ?>" alt="логотип">
            </a>
            <nav class="header__menu">
                <?php
                    wp_nav_menu( [
                        'menu'            => 'header',
                        'menu_class'      => 'menu',
                        'menu_id'         => 'menu',
                        'echo'            => true,
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    ] );
                ?>
                <div class="header__btn header__btn-mobile">
                    <a href="/catalog">В каталог</a>
                </div>
            </nav>
            <div class="header__right">
                <div class="header__btn">
                    <a href="/cart">Корзина (<span class="cart-count"><?= WC()->cart->get_cart_contents_count(); ?></span>)</a>
                </div>
                <div class="header__btn-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</header>


