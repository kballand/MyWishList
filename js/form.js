$(document).ready(function () {
    let checker = [];
    let selector = '.errorDisplayedField .notEmptyField:not([type="date"])';
    let fields = $(selector);
    let listener = function () {
        let error = $(this).next('.displayedError.fieldEmptyError');
        let text = $(this).val();
        if (text.trim().length === 0) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
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
        if (!date) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.blur(listener);
    fields.change(listener);
    fields = $('form');
    fields.submit(function (e) {
        checkIn();
        let errorFields = $(this).find('.errorDisplayedField *[aria-invalid="true"]');
        if (errorFields.length > 0) {
            e.preventDefault();
            let first = errorFields.first().nextAll('.displayedError.displayed');
            $('html, body').animate({scrollTop: (first.offset().top - 60)}, 'slow');
            return false;
        }
    });
    selector = '.errorDisplayedField .ulteriorDate';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.incorrectDateError');
        if ($(this).val()) {
            let parts = $(this).val().split('-');
            let setYear = parseInt(parts[0]);
            let setMonth = parseInt(parts[1]);
            let setDay = parseInt(parts[2]);
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth() + 1;
            let currentDay = currentDate.getDate();
            if (currentYear > setYear || (currentYear === setYear && currentMonth > setMonth) || (currentYear === setYear && currentMonth === setMonth && (currentDay + 1) > setDay)) {
                $(this).attr('aria-invalid', 'true');
                if (!error.hasClass('displayed')) {
                    error.addClass('displayed');
                }
            } else {
                $(this).attr('aria-invalid', 'false');
                if (error.hasClass('displayed')) {
                    error.removeClass('displayed');
                }
            }
        } else {
            $(this).attr('aria-invalid', 'true');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .limitedPrice';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.incorrectPriceError');
        let price = $(this).val();
        let min = Number($(this).attr('min'));
        let max = Number($(this).attr('max'));
        if (!price || Number(price) < min || Number(price) > max) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.keyup(listener);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .usernameUniqueField';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.usernameUniqueError');
        let username = $(this).val();
        if (username.trim().length === 0) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre nom d\'utilisateur ne peut être vide !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else if (username.indexOf(' ') >= 0) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre nom d\'utilisateur ne doit pas contenir d\'espaces !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else if (username.length < 5) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre nom d\'utilisateur doit contenir au moins 5 caractères !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            let $this = $(this);
            $.ajax({
                type: 'POST',
                url: window.location.origin + window.location.pathname + '/check_username',
                data: 'username=' + username,
                success: function() {
                    $this.attr('aria-invalid', 'false');
                    if (error.hasClass('displayed')) {
                        error.removeClass('displayed');
                    }
                },
                error: function () {
                    $this.attr('aria-invalid', 'true');
                    error.find('.displayedMessage').text('Ce nom d\'utilisateur est déjà pris !');
                    if (!error.hasClass('displayed')) {
                        error.addClass('displayed');
                    }
                }
            });
        }
    };
    checker.push(selector);
    fields.keyup(listener);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .emailField';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.emailInvalidError');
        let email = $(this).val();
        if (!validateEmail(email)) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .passwordField';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.passwordInvalidError');
        let password = $(this).val();
        if (password.trim().length === 0) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre mot de passe ne doit pas être vide !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else if (password.length < 7) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre mot de passe doit contenir au minimum 7 caractères !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre mot de passe doit contenir au moins une majuscule et une minuscule !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else if (!/[0-9]/.test(password)) {
            $(this).attr('aria-invalid', 'true');
            error.find('.displayedMessage').text('Votre mot de passe doit contenir au moins un chiffre !');
            if (!error.hasClass('displayed')) {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.keyup(listener);
    fields.change(listener);
    fields.blur(listener);
    selector = '.errorDisplayedField .passwordVerifyField';
    fields = $(selector);
    listener = function () {
        let error = $(this).nextAll('.displayedError.passwordVerifyInvalidError');
        let passwordVerify = $(this).val();
        let passwordField = $(this).closest('.registerPart').find('.passwordField');
        let password = passwordField.val();
        if(password && password !== passwordVerify) {
            $(this).attr('aria-invalid', 'true');
            if (!error.hasClass('displayed') && passwordField.attr('aria-invalid') === 'false') {
                error.addClass('displayed');
            }
        } else {
            $(this).attr('aria-invalid', 'false');
            if (error.hasClass('displayed')) {
                error.removeClass('displayed');
            }
        }
    };
    checker.push(selector);
    fields.change(listener);
    fields.blur(listener);
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
        checkPart();
        let errorFields = $(this).closest('.multipartForm').find('.registerPart').eq(currentPart).find('.errorDisplayedField *[aria-invalid="true"]');
        if (errorFields.length > 0) {
            let first = errorFields.first().nextAll('.displayedError.displayed');
            $('html, body').animate({scrollTop: (first.offset().top - 60)}, 'slow');
        } else {
            if (currentPart < parts.length - 1) {
                if (currentPart === 0) {
                    prev.show();
                    next.css('margin-left', '12%');
                }
                steps.eq(currentPart).removeClass('stepActive');
                steps.eq(currentPart).addClass('stepFinish');
                parts.eq(currentPart).hide();
                ++currentPart;
                if (currentPart === parts.length - 1) {
                    next.text('Créer mon compte');
                }
                parts.eq(currentPart).show();
                steps.eq(currentPart).addClass('stepActive');
            } else {
                $('#registerForm').submit();
            }
        }
    });
    $('#registerForm').submit(function () {
        this.submit();
    });
    prev.click(function () {
        if (currentPart !== 0) {
            if (currentPart === parts.length - 1) {
                next.text('Next');
            }
            steps.eq(currentPart).removeClass('stepActive');
            parts.eq(currentPart).hide();
            --currentPart;
            if (currentPart === 0) {
                next.css('margin-left', '0');
                prev.hide();
            }
            parts.eq(currentPart).show();
            steps.eq(currentPart).addClass('stepActive');
        }
    });

    function checkIn() {
        for (let key in checker) {
            $(checker[key]).trigger("change");
        }
    }

    function checkPart() {
        for (let key in checker) {
            if ($(checker[key]).closest('.registerPart').prevAll('.registerPart').length === currentPart) {
                $(checker[key]).trigger("change");
            }
        }
    }

    function validateEmail(email) {
        let regExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regExp.test(String(email).toLowerCase());
    }
});