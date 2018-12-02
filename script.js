$().ready(function () {
    $('nav .menu .sousMenu').hide();
    $('nav .menu').mouseenter(function () {
        let $this = $(this);
        $this.removeClass('closing');
       $this.addClass('opening');
       $this.children('.sousMenu').stop(true, true).fadeIn(300, function() {
           $this.removeClass('opening');
           $this.addClass('open');
       });
    });
    $('nav .menu').mouseleave(function () {
        let $this = $(this);
        $this.removeClass('opening');
        $this.addClass('closing');
        $this.children('.sousMenu').stop(true, true).fadeOut(300, function() {
            $this.removeClass('closing');
            $this.removeClass('open');
        });
    });
});