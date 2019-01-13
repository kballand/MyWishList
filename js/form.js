$(document).ready(function() {
    let checker = [];
    let selector = '.errorDisplayedField .notEmptyField:not([type="date"])';
    let fields = $(selector);
    let listener = function() {
        let error = $(this).next('.displayedError.fieldEmptyError');
        let text = $(this).val();
        if(text.trim().length === 0) {
            $(this).attr('aria-invalid', 'true');
            if(!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if(error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.keyup(listener);
    fields.blur(listener);
    fields.change(listener);
    selector = '.errorDisplayedField .notEmptyField[type="date"]';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.fieldEmptyError');
        let date = $(this).val();
        if(!date) {
            $(this).attr('aria-invalid', 'true');
            if(!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if(error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.blur(listener);
    fields.change(listener);
    fields = $('form');
    fields.submit(function(e) {
        checkIn();
        let errorFields = $(this).find('.errorDisplayedField *[aria-invalid="true"]');
        if(errorFields.length > 0) {
            e.preventDefault();
            let first = errorFields.first().nextAll('.displayedError.displayed');
            $('html, body').animate({ scrollTop: (first.offset().top - 60) }, 'slow');
            return false;
        }
    });
    selector = '.errorDisplayedField .ulteriorDate';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.incorrectDateError');
        if($(this).val()) {
            let parts = $(this).val().split('-');
            let setYear = parseInt(parts[0]);
            let setMonth = parseInt(parts[1]);
            let setDay = parseInt(parts[2]);
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth() + 1;
            let currentDay = currentDate.getDate();
            if(currentYear > setYear || (currentYear === setYear && currentMonth > setMonth) || (currentYear === setYear && currentMonth === setMonth && (currentDay + 1) > setDay)) {
                $(this).attr('aria-invalid', 'true');
                if (!error.hasClass('displayed')) {
                    error.addClass('displayed');
                }
            } else {
                $(this).attr('aria-invalid', 'false');
                if(error.hasClass('displayed')) {
                    error.removeClass('displayed');
                }
            }
        } else {
            $(this).attr('aria-invalid', 'true');
            if(error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .limitedPrice';
    fields = $(selector);
    listener = function() {
        let error = $(this).nextAll('.displayedError.incorrectPriceError');
        let price = $(this).val();
        let min = Number($(this).attr('min'));
        let max = Number($(this).attr('max'));
        if(!price || Number(price) < min || Number(price) > max) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if(error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.keyup(listener);
    fields.change(listener);
    fields.blur(listener);
    function checkIn() {
        for(let key in checker) {
            $(checker[key]).trigger("change");
        }
    }
});