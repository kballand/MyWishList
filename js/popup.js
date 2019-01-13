$(document).ready(function () {
    let popupOpener = $('.popupOpener');
    popupOpener.click(function (e) {
        e.preventDefault();
        let popup = $(this).next('.popup');
        popup.stop(true, true).fadeIn(300, function () {
            $(this).addClass('displayedPopup');
        });
    });
    let popupCloser = $('.popupCloser');
    popupCloser.click(function (e) {
        e.preventDefault();
        let popup = $(this).closest('.popup');
        popup.stop(true, true).fadeOut(300, function () {
            $(this).removeClass('displayedPopup');
        });
    });
    $(window).click(function (event) {
        let popup = $('.displayedPopup .popupContent');
        popup.each(function () {
            if (!$(event.target).closest($(this)).length) {
                $(this).parent().stop(true, true).fadeOut(300, function () {
                    $(this).removeClass('displayedPopup');
                });
            }
        });
    });
    let textCopier = $('.textCopier');
    textCopier.click(function () {
        let copiedText = $(this).prev('.copiedText');
        copiedText.select();
        document.execCommand('copy');
    });
});