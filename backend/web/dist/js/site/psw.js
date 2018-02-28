$('#update_psw_btn').click(function (e) {
    $('.popover').hide();
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var old_password = $("#old_password").val();
    if (old_password.replace(/(^\s*)|(\s*$)/g,"")=="") {
        $('#old_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">旧密码不能为空</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(old_password)) {
        $('#old_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">旧密码不能包含<>()=|"$%\/</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    var new_password = $("#new_password").val();
    if (new_password.replace(/(^\s*)|(\s*$)/g,"")=="") {
        $('#new_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">新密码不能为空</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(new_password)) {
        $('#new_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">新密码不能包含<>()=|"$%\/</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (new_password.length<6 || new_password.length>30 ) {
        $('#new_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">密码长度需要大于6个字符，小于30个字符</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (old_password==new_password) {
        $('#new_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">新旧密码不能相同</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    var confirm_password = $("#confirm_password").val();
    if (confirm_password.replace(/(^\s*)|(\s*$)/g,"")=="") {
        $('#confirm_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">确认密码不能为空</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(confirm_password)) {
        $('#confirm_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">确认密码不能包含<>()=|"$%\/</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (new_password!=confirm_password) {
        $('#confirm_password').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">两次密码不相同</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    $("#msg_info").addClass('hide');
    $.ajax({
        type: "post",
        dataType:"json",
        url: PSW_SAVE,
        data: {"old_password": old_password, "new_password": new_password, "confirm_password":confirm_password, "_csrf": csrfToken},
        cache: false,
        beforeSend:function () {
            $('.popover').hide();
        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            $("#msg_info").removeClass('hide').html("出错了，" + textStatus);
        },
        success: function (data) {
            if(data.code == 0){
                $("#msg_info").removeClass('hide').html("修改密码成功");
                $("#old_password").val("");
                $("#new_password").val("");
                $("#confirm_password").val("");
                window.location.href = LOGOUT;
                return false;
            } else {
                var json = data.data;
                var _errors = '';
                for(var key in json){
                    if (isUndefined( $('#' + key).html())) {
                        _errors += (_errors=='' ? '':',' )+json[key];
                    } else {
                        $('#' + key).attr({'data-placement':'bottom', 'data-content':json[key], 'data-toggle':'popover'}).addClass('popover-show').popover('show');
                    }
                }
                if (!(_errors=='')) $('#user_role').attr({'data-placement':'bottom', 'data-content':'<span class="text-danger">'+_errors+'</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
            }
        }
        });
});