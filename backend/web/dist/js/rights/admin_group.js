var csrf = $("#_csrf").val();
//自定义分页
function changePerPage(page){
    $("#per_page").val(page);
    $('#admin-group-search-form').submit();
}

function searchAction() {
    $('#admin-group-search-form').submit();
}
function viewAction(id) {
    initModel(id, 'view', 'fun');
}

function initEditSystemModule(data, type) {
    if (type == 'create') {
        $("#group_id").val('');
        $("#group_name").val('');
        $("#des").val('');
        $("#status").val(0);
        $("#create_user").val('');
        $("#create_date").val('');
        $("#update_user").val('');
        $("#update_date").val('');
        $("#admin_group_one_title").html("新增分组");
    }
    else {
        $("#group_id").val(data.group_id);
        $("#group_name").val(data.group_name);
        $("#des").val(data.des);
        $("#status").val(data.status);
        $("#create_user").val(data.create_user);
        $("#create_date").val(data.create_date);
        $("#update_user").val(data.update_user);
        $("#update_date").val(data.update_date);
        $("#admin_group_one_title").html("编辑分组");
        $("#group_code").val(data.code);
    }
    if (type == "view") {
        $("#group_id").attr({readonly: true, disabled: true});
        $("#group_name").attr({readonly: true, disabled: true});
        $("#des").attr({readonly: true, disabled: true});
        $("#status").attr({readonly:true,disabled:true});
        $("#create_user").attr({readonly: true, disabled: true});
        $("#create_user").parent().parent().show();
        $("#create_date").attr({readonly: true, disabled: true});
        $("#create_date").parent().parent().show();
        $("#update_user").attr({readonly: true, disabled: true});
        $("#update_user").parent().parent().show();
        $("#update_date").attr({readonly: true, disabled: true});
        $("#update_date").parent().parent().show();
        $('#edit_dialog_ok').addClass('hidden');
    }
    else {
        $("#group_id").attr({readonly: false, disabled: false});
        $("#group_name").attr({readonly: false, disabled: false});
        $("#des").attr({readonly: false, disabled: false});
        $("#status").attr({readonly:false,disabled:false});
        $("#create_user").attr({readonly: false, disabled: false});
        $("#create_user").parent().parent().hide();
        $("#create_date").attr({readonly: false, disabled: false});
        $("#create_date").parent().parent().hide();
        $("#update_user").attr({readonly: false, disabled: false});
        $("#update_user").parent().parent().hide();
        $("#update_date").attr({readonly: false, disabled: false});
        $("#update_date").parent().parent().hide();
        $('#edit_dialog_ok').removeClass('hidden');
    }
    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun) {

    $.ajax({
        type: "POST",
        url: ADMIN_GROUP_VIEW,
        data: {"group_id": id, "_csrf":csrf},
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
    e.preventDefault();
    $('#admin-group-form').submit();
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#delete_btn').click(function (e) {
    e.preventDefault();
    deleteAction('');
});

$('#admin-group-form').bind('submit', function (e) {
    $("#admin-group-form").data('yiiActiveForm').validated = true;
    e.preventDefault();
    var group_name = checkvalue($("#group_name").val());
    if (group_name == "") {
        alert("分组名称");
        return false;
    } else if (!checkBytelengh(group_name, 16, "分组名称")) {
        return false;
    }
    var des = checkvalue($("#des").val());
    if (!checkBytelengh(des, 80, "分组描述")) {
        return false;
    }
    var group_id = $("#group_id").val();
    var action = group_id == "" ? ADMIN_GROUP_CREATE : ADMIN_GROUP_UPDATE;
    $(this).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: action,
        data: {group_id: group_id},
        success: function (data) {
            if (data.code == 0) {
                $('#edit_dialog').modal('hide');
                admin_tool.alert('msg_info', '添加成功', 'success');
                window.location.reload();
            } else if(data.code == 1) {
                $('#edit_dialog').modal('hide');
                window.location.reload();
            }else if(data.code == 2) {
                alert(data.msg);
            } else {
                var json = data.data;
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


var csrf = $("#_csrf").val();
var groupId = $('#group_id').val();
selectType = function (type) {
    $('#query_type').val(type);
    searchAction();
}
// 关联用户
relationGroupUser = function (userIds) {
    if (!userIds.length > 0) {
        return false;
    }
    $.ajax({
        type: "POST",
        url: RELATION_GROUP_USER,
        data: {'groupId': groupId, 'userIds': userIds, "_csrf": csrf},
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (data) {
            if (data.code === 0) {
                window.location.reload();
            } else if(data.code === 1){
                if(confirm(data.msg)){
                    window.location.reload();
                }
            } else {
                alert(data.msg);
            }
            return false;
        }
    });
}
$("#relation_btn").click(function () {
    var id = getId();
    if (id === false) {
        return false;
    }
    relationGroupUser(id);
});
getId = function () {
    var checkboxs = $('tbody :checked');
    if (checkboxs.length <= 0) {
        alert('您至少得选择一个用户');
        return false;
    }
    var id = '';
    for (i = 0; i < checkboxs.length; i++) {
        var value = checkboxs.eq(i).val();
        if (value != "") {
            id = id + value + ",";
        }
    }
    return id.substring(0, id.length - 1);
}
// 解除关联用户
releaseGroupUser = function (userIds) {
    if (!userIds.length > 0) {
        return false;
    }
    $.ajax({
        type: "POST",
        url: RELEASE_GROUP_USER,
        data: {'groupId': groupId, 'userIds': userIds, "_csrf": csrf},
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (data) {
            if (data.code === 0) {
                window.location.reload();
            } else {
                alert(data.msg);
            }
            return false;
        }
    });
}
$("#release_btn").click(function () {
    var id = getId();
    if (id === false) {
        return false;
    }
    releaseGroupUser(id);
});

//自定义分页
function changePerPage(page) {
    $("#per_page").val(page);
    searchAction();
}
function searchAction() {
    $('#admin-user-group-search-form').submit();
}
editUserGroupAction = function (userId, type) {
    if (type === 0) {
        relationGroupUser(userId);
    }
    if (type === 1) {
        releaseGroupUser(userId);
    }
}