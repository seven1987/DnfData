$("#username").focus();
document.onkeydown = function (e) {
    if (!e) e = window.event;
    if ((e.keyCode || e.which) == 13) {
        $('#login-form').submit();
    }
};

$('#login_btn').click(function (e) {
    e.preventDefault();
    $('#login-form').submit();
});


$('#login-form').bind('submit', function (e) {
    var username = $("#username").val();
    if (username.replace(/(^\s*)|(\s*$)/g, "") == "") {
        $('#username').attr({
            'data-placement': 'top',
            'data-content': '<span class="text-danger">用户名为空</span>',
            'data-toggle': 'popover'
        }).addClass('popover-show').popover({html: true}).popover('show');
        return false;
    }
    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(username)) {
        $('#username').attr({
            'data-placement': 'top',
            'data-content': '<span class="text-danger">用户名含有非法字符</span>',
            'data-toggle': 'popover'
        }).addClass('popover-show').popover({html: true}).popover('show');
        return false;
    }
    var password = $('#password').val();
    if (password.replace(/(^\s*)|(\s*$)/g, "") == "") {
        $('#password').attr({
            'data-placement': 'top',
            'data-content': '<span class="text-danger">密码为空</span>',
            'data-toggle': 'popover'
        }).addClass('popover-show').popover({html: true}).popover('show');
        return false;
    }

    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(password)) {
        $('#password').attr({
            'data-placement': 'top',
            'data-content': '<span class="text-danger">密码含有非法字符</span>',
            'data-toggle': 'popover'
        }).addClass('popover-show').popover({html: true}).popover('show');
        return false;
    }
    e.preventDefault();

    $(this).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: ADMIN_LOGIN_URL,
        beforeSerialize: function () {
            var encryptKey = CSRF_TOKEN;
            encryptKey = CryptoJS.enc.Utf8.parse(encryptKey);
            var iv = IV;
            iv = CryptoJS.enc.Utf8.parse(iv);
            var magic = CryptoJS.AES.encrypt(password, encryptKey, {
                iv: iv,
                mode: CryptoJS.mode.CBC,
                padding: CryptoJS.pad.ZeroPadding
            }).toString();
            $('#password').val(encodeURI(magic));
        },
        clearForm: true,
        success: function (value) {
            if (value.code === 0) {
                window.location.reload();
            } else {
                $('#username').attr({
                    'data-placement': 'top',
                    'data-content': '<span class="text-danger">用户名或密码错误</span>',
                    'data-toggle': 'popover'
                }).addClass('popover-show').popover({html: true}).popover('show');
            }
        }
    });
});
