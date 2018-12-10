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
    };
    let currentPart = 0;
    let prev = $('#registerPreviousStep');
    prev.hide();
    let next = $('#registerNextStep');
    next.css('margin-left', '0');
    let steps = $('.registerStep');
    steps.first().addClass('stepActive');
    let parts = $('.registerPart');
    parts.hide();
    parts.first().show();
    next.click(function () {
        if(currentPart < parts.length - 1) {
            if(currentPart === 0) {
                prev.show();
                next.css('margin-left', '12%');
            }
            steps.eq(currentPart).removeClass('stepActive');
            steps.eq(currentPart).addClass('stepFinish');
            parts.eq(currentPart).hide();
            ++currentPart;
            if(currentPart === parts.length - 1) {
                next.text('Submit');
            }
            parts.eq(currentPart).show();
            steps.eq(currentPart).addClass('stepActive');
        } else {
            $('#registerForm').submit();
        }
    });
    prev.click(function () {
        if(currentPart !== 0) {
            if(currentPart === parts.length - 1) {
                next.text('Next');
            }
            steps.eq(currentPart).removeClass('stepActive');
            parts.eq(currentPart).hide();
            --currentPart;
            if(currentPart === 0) {
                next.css('margin-left', '0');
                prev.hide();
            }
            parts.eq(currentPart).show();
            steps.eq(currentPart).addClass('stepActive');
        }
    });

});