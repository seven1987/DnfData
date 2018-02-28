var csrf = $("#_csrf").val();

//自定义分页
function changePerPage(page) {
    $("#per_page").val(page);
    $('#admin-user-search-form').submit();
}

function searchAction() {
    $('#admin-user-search-form').submit();
}
function viewAction(id) {
    initModel(id, 'view', 'fun');
}
function initEditSystemModule(datas, type) {
    if (type == 'create') {
        $("#admintype").val(1);
        $("#id").val('');
        $("#uname").val('');
        $("#email").val('');
        $("#password").val('');
        $("#auth_key").val('');
        $("#last_ip").val('');
        $("#is_online").val('');
        $("#domain_account").val('');
        $("#status").val('');
        $("#create_user").val('');
        $("#create_date").val('');
        $("#update_user").val('');
        $("#update_date").val('');

        var groups = $("input[name='groupName']");
        groups.each(function () {
            $(this).prop('checked', false);
        });

        $("#admin_user_one_title").html("新增用户");

        $("input:radio[name='AdminUser[status]']").eq(0).attr("checked", true);
        selectAdminTypeAction();
    }
    else {
        var data = datas.userinfo;
        var group = datas.defaultGroup;
        $("#id").val(data.id);
        $("#email").val(data.email);
        $("#uname").val(data.uname);
        $("#password").val(data.password);
        $("#auth_key").val(data.auth_key);
        $("#last_ip").val(data.last_ip);
        $("#is_online").val(data.is_online);
        $("#domain_account").val(data.domain_account);
        $("#status").val(data.status);
        $("#create_user").val(data.create_user);
        $("#create_date").val(data.create_date);
        $("#update_user").val(data.update_user);
        $("#update_date").val(data.update_date);

        var groups = $("input[name='groupName']");
        groups.each(function () {
            if ($.inArray($(this).val(), group) != -1) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });

        if (data.status === 0) {
            $("input:radio[name='AdminUser[status]']").eq(1).attr("checked", true);
        } else if (data.status === 10) {
            $("input:radio[name='AdminUser[status]']").eq(2).attr("checked", true);
        } else {
            $("input:radio[name='AdminUser[status]']").eq(0).attr("checked", true);
        }
        $("#admin_user_one_title").html("编辑用户");
    }
    if (type == "view") {
        $("#id").attr({readonly: true, disabled: true});
        $("#uname").attr({readonly: true, disabled: true});
        $("#password").attr({readonly: true, disabled: true});
        $("#password").parent().parent().hide();
        $("#auth_key").attr({readonly: true, disabled: true});
        $("#auth_key").parent().parent().hide();
        $("#last_ip").attr({readonly: true, disabled: true});
        $("#is_online").attr({readonly: true, disabled: true});
        $("#domain_account").attr({readonly: true, disabled: true});
        $("#status").attr({readonly: true, disabled: true});
        $("#create_user").attr({readonly: true, disabled: true});
        $("#create_date").attr({readonly: true, disabled: true});
        $("#update_user").attr({readonly: true, disabled: true});
        $("#update_date").attr({readonly: true, disabled: true});

        $("#last_ip,#is_online,#domain_account,#create_user,#create_date,#update_user,#update_date").parent().parent().show();

        $('#edit_dialog_ok').addClass('hidden');
    }
    else {

        $("#id").attr({readonly: false, disabled: false});
        $("#uname").attr({readonly: true, disabled: true});
        if (type == "create") {
            $("#uname").attr({readonly: false, disabled: false});
            $("#password").attr({readonly: false, disabled: false});
            $("#password").parent().parent().show();
        }
        else {
            $("#uname").attr({readonly: true, disabled: true});
            $("#password").attr({readonly: true, disabled: true});
            $("#password").parent().parent().hide();
        }

        $("#auth_key").attr({readonly: true, disabled: true});
        $("#auth_key").parent().parent().hide();
        $("#last_ip").attr({readonly: true, disabled: true});
        $("#last_ip").parent().parent().hide();
        $("#is_online").attr({readonly: true, disabled: true});
        $("#is_online").parent().parent().hide();
        $("#domain_account").attr({readonly: false, disabled: false});
        $("#domain_account").parent().parent().hide();
        $("#status").attr({readonly: false, disabled: false});
        $("#create_user").attr({readonly: true, disabled: true});
        $("#create_user").parent().parent().hide();
        $("#create_date").attr({readonly: true, disabled: true});
        $("#create_date").parent().parent().hide();
        $("#update_user").attr({readonly: true, disabled: true});
        $("#update_user").parent().parent().hide();
        $("#update_date").attr({readonly: true, disabled: true});
        $("#update_date").parent().parent().hide();
        $('#edit_dialog_ok').removeClass('hidden');

        if (type == 'edit') {
            var radio = datas.userinfo.status;

            var radios = $("input[name='status']");

            radios.each(function () {
                if ($(this).val() == radio) {
                    $(this).prop('checked', true);
                }
            });
        }

    }
    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun) {
    $.ajax({
        type: "POST",
        url: ADMIN_USER_VIEW,
        data: {"id": id, "_csrf": csrf},
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (data) {
            initEditSystemModule(data, type);
        }
    });
}


function selectAdminTypeAction() {
    var type = $('#admintype').val();
    var content = "";
    if (type == 3) {
        content = "<input type=\"text\" id=\"agent_id\" name=\"AdminUser[agent_id]\" style=\"width:180px\"/>";
    }
    if (type == 2) {
        $("#admin_role").css("display", "block");
    } else {
        $("#admin_role").css("display", "none");
    }
    $('#admin_user_agent_id').html(content);
}

function editAction(id) {
    initModel(id, 'edit');
}


function getSelectedIdValues(formId) {
    var value = "";
    $(formId + " :checked").each(function (i) {
        if (!this.checked) {
            return true;
        }
        value += this.value;
        if (i != $("input[name='id']").size() - 1) {
            value += ",";
        }
    });
    return value;
}

$('#edit_dialog_ok').click(function (e) {
    var groups = $("input[name='groupName']");
    var groupName = '';
    groups.each(function () {
        if ($(this).prop('checked') == true) {
            groupName += $(this).val() + ',';
        }
    });
    groupName = groupName.substring(0, groupName.length - 1);
    var formData = {
        'id': $("#id").val(),
        'AdminUser': {
            'id': $("#id").val(),
            'uname': $("#uname").val(),
            'password': $("#password").val(),
            'email': $("#email").val(),
            'auth_key': $("#auth_key").val(),
            'last_ip': $("#last_ip").val(),
            'is_online': $("#is_online").val(),
            'domain_account': $("#domain_account").val(),
            'status': $('input[name="status"]:checked').val(),
            'create_user': $("#create_user").val(),
            'create_date': $("#create_date").val(),
            'update_user': $("#update_user").val(),
            'update_date': $("#update_date").val()
        },
        'groupName': groupName,
        '_csrf': csrf
    };
    var url = '';
    if ($("#id").val()) {
        url = ADMIN_USER_UPDATE;
        delete formData.AdminUser.password;
    } else {
        url = ADMIN_USER_CREATE;
    }

    var result = checkFromData();

    if (!result) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (value) {
            if (value.errno == 1) {
                alert(value.msg);
                return false;
            }
            if (value.errno == 0) {
                $('#edit_dialog').modal('hide');
                window.location.reload();
            }
            else {
                var json = value.data;
                for (var key in json) {
                    $('#' + key).attr({
                        'data-placement': 'bottom',
                        'data-content': json[key],
                        'data-toggle': 'popover'
                    }).addClass('popover-show').popover('show');

                }
            }

        }
    });
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#delete_btn').click(function (e) {
    e.preventDefault();
    deleteAction('');
});

var checkFromData = function () {
    var uname = $("#uname").val();
    if (checkvalue(uname) == '') {
        alert("用户名不能为空");
        return false;
    } else if (!checkBytelengh(uname, 50, "用户名")) {
        return false;
    }
    var id = $("#id").val();
    var passwd = $("#password").val();
    if (id == "") {
        if (checkvalue(passwd) == '') {
            alert("密码不能为空");
            return false;
        } else if (!checkBytelengh(passwd, 16, "密码")) {
            return false;
        }
        var len = 0;
        for (var i = 0; i < passwd.length; i++) {
            var a = passwd.charAt(i);
            if (a.match(/[^\x00-\xff]/ig) != null) {
                len += 2;
            }
            else {
                len += 1;
            }
        }
        if (len < 6) {
            alert("密码长度至少大于6个字符");
            return false;
        }
    }


    var email = $("#email").val();
    if (email == "" || email == null) {
        alert("邮箱不能为空");
        return false;
    } else if (!isEmail(email)) {
        alert("邮箱格式不正确");
        return false;
    }
    return true;
};

