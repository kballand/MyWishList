$(document).ready(function () {
    let loginForm = $('#loginForm');
    loginForm.submit(function (e) {
        e.preventDefault();
       let username = $(this).find('#loginUsername').val();
       let password = $(this).find('#loginPassword').val();
       let form = this;
        $.ajax({
            type: 'POST',
            url: window.location.origin + window.location.pathname + '/check_login',
            data: {
                username: username,
                password: password
            },
            success: function() {
                form.submit();
            },
            error: function () {
                $(form).find('#badLoginMessage').addClass('displayed');
            }
        });
    });
});