<?php
wp_footer();

$cleanedPhone = str_replace([' ', '-', '+'], '', CFS()->get('phone', 35));
?>
<footer class="footer">
    <div class="container">
        <div class="footer__container">
            <div class="footer__top">
                <a href="/" class="footer__logo">
                    <img src="<?= CFS()->get('logo',16) ?>" alt="">
                </a>
                <div class="footer__nav">
                    <?php
                    wp_nav_menu( [
                        'menu'            => 'header',
                        'menu_class'      => 'menu',
                        'menu_id'         => 'menu',
                        'echo'            => true,
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    ] );
                    ?>
                </div>
            </div>
            <div class="footer__center">
                <div class="footer__center_item footer__contacts">
                    <p class="footer__label">Поддержка клиентов:</p>
                    <a class="footer__value" href="mailto:<?= CFS()->get('email',35) ?>"><?= CFS()->get('email',35) ?></a>
                    <a class="footer__value" href="tel:+<?= $cleanedPhone ?>"><?= CFS()->get('phone',35) ?></a>
                </div>
                <div class="footer__center_item footer__address">
                    <p class="footer__label">Адрес:</p>
                    <p class="footer__value"><?= CFS()->get('address',35) ?></p>
                </div>
                <div class="footer__center_item footer__social">
                    <p class="footer__label">Социальные сети:</p>
                    <a class="footer__social_item" target="_blank" href="<?= CFS()->get('telegram',35) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3.57665 9.49463L0.291291 8.42419C0.291291 8.42419 -0.101346 8.2649 0.0250829 7.90367C0.0511091 7.82918 0.10361 7.7658 0.260665 7.65687C0.988614 7.14947 13.7344 2.56829 13.7344 2.56829C13.7344 2.56829 14.0943 2.44702 14.3065 2.52768C14.359 2.54394 14.4063 2.57385 14.4434 2.61435C14.4806 2.65486 14.5063 2.7045 14.518 2.75822C14.5409 2.85309 14.5505 2.95069 14.5465 3.04821C14.5455 3.13257 14.5353 3.21076 14.5275 3.33337C14.4499 4.58589 12.1268 13.9338 12.1268 13.9338C12.1268 13.9338 11.9878 14.4808 11.4899 14.4995C11.3675 14.5035 11.2456 14.4827 11.1314 14.4386C11.0172 14.3944 10.913 14.3277 10.8252 14.2424C9.84796 13.4018 6.47039 11.1319 5.72404 10.6327C5.70721 10.6212 5.69303 10.6063 5.68246 10.5889C5.6719 10.5715 5.66519 10.552 5.66279 10.5318C5.65236 10.4791 5.70957 10.414 5.70957 10.414C5.70957 10.414 11.5908 5.18628 11.7473 4.63749C11.7594 4.59497 11.7137 4.574 11.6522 4.59262C11.2616 4.73632 4.49004 9.01259 3.74268 9.48454C3.68889 9.50081 3.63203 9.50427 3.57665 9.49463Z" fill="#239CFF"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="footer__bottom">
                <div class="footer__docs">
                    <a target="_blank" href="<?= CFS()->get('privacy-policy',35) ?>">Политика конфиденциальности</a>
                    <p>|</p>
                    <a target="_blank" href="<?= CFS()->get('offer-agreement',35) ?>">Договор оферты</a>
                </div>
                <div class="footer__year">
                    <p>2024</p>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>