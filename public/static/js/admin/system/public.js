$(function () {
    $('#login-form').validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            },
            captcha: {
                required: true
            }
        },
        messages: {
            username: {
                required: '请输入用户名'
            },
            password: {
                required: '请输入密码'
            },
            captcha: {
                required: '请输入验证码'
            }
        },
        submitHandler: function (e) {
            submitForm();
            return false;
        }
    });

    $('.captcha-box').on('click', 'img', function () {
        var src = $(this).data('src');
        if (src.indexOf('?') !== -1) {
            src += '&';
        } else {
            src += '?';
        }
        $(this).attr('src', src + new Date().getTime());
    });
});

function submitForm() {
    var form = $('#login-form');
    var data = form.serialize();
    $.loading('show');
    POST('/system/public/login', data, function (res) {
        $.loading('hide');
        if (res.code == 200) {
            $.success(res.message);
            setTimeout(function () {
                location.href = $('input[type=submit]').data('url');
            }, 2000)
        } else {
            $('.captcha-box').find('img').click();
            $.error(res.message);
        }
    }, 'json');
}