$(document).ready(function () {
    $('.subMenu').hide();
    let menus = $('.menu');
    menus.mouseenter(function () {
        let $this = $(this);
        $this.removeClass('closing');
        $this.addClass('opening');
        $this.children('.subMenu').stop(true, true).fadeIn(300, function () {
            $this.removeClass('opening');
            $this.addClass('open');
        });
    });
    menus.mouseleave(function () {
        let $this = $(this);
        $this.removeClass('opening');
        $this.addClass('closing');
        $this.children('.subMenu').stop(true, true).fadeOut(300, function () {
            $this.removeClass('closing');
            $this.removeClass('open');
        });
    });
    let navBar = $('nav');
    let offset = navBar.offset().top;
    window.onscroll = function() {
        if(window.pageYOffset >= offset) {
            navBar.addClass('sticky');
        } else {
            navBar.removeClass('sticky');
        }
    }
});