$().ready(function () {
    $('nav .menu .sousMenu').hide();
    $('nav .menu .titreMenu').mouseenter(function () {
        $(this).addClass('active').next('.sousMenu').fadeIn(300);
    });
    $('nav .menu').mouseleave(function () {
        $(this).children('.sousMenu').fadeOut(300);
        $(this).children('a').removeClass('active');
    });
});