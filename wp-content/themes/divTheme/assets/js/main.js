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
});