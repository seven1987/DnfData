var csrf = $("#_csrf").val();
//自定义分页
function changePerPage(page){
    $("#per_page").val(page);
    $('#admin-module-search-form').submit();
}

function searchAction() {
    $('#admin-module-search-form').submit();
}
function viewAction(id) {
    initModel(id, 'view', 'fun');
}

function initEditSystemModule(data, type) {
    if (type == 'create') {
        $("#module_id").val('');
        $("#module_name").val('');
        $("#display_order").val('');
        $("#status").val(1);
        $("#admin_module_title").html("新增菜单");
    }
    else {
        $("#module_id").val(data.module_id);
        $("#module_name").val(data.module_name);
        $("#display_order").val(data.display_order);
        $("#status").val(data.status);
        $("#admin_module_title").html("编辑菜单");
    }
    if (type == "view") {
        $("#module_id").attr({readonly: true, disabled: true});
        $("#module_name").attr({readonly: true, disabled: true});
        $("#display_order").attr({readonly: true, disabled: true});
        $("#status").attr({readonly: true, disabled: true});
        $('#edit_dialog_ok').addClass('hidden');
    }
    else {
        $("#module_id").attr({readonly: false, disabled: false});
        $("#module_name").attr({readonly: false, disabled: false});
        $("#display_order").attr({readonly: false, disabled: false});
        $("#status").attr({readonly: false, disabled: false});
        $('#edit_dialog_ok').removeClass('hidden');
    }
    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun) {
    $.ajax({
        type: "POST",
        url: ADMIN_MODULES_VIEW,
        data: {"id": id, "_csrf":csrf},
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
    $('#admin-module-form').submit();
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#delete_btn').click(function (e) {
    e.preventDefault();
    deleteAction('');
});

$('#admin-module-form').bind('submit', function (e) {
    $("#admin-module-form").data('yiiActiveForm').validated = true;
    e.preventDefault();
    var module_id = $("#module_id").val();
    var action = module_id == "" ? ADMIN_MODULES_CREATE : ADMIN_MODULES_UPDATE;
    $(this).ajaxSubmit({
        type: "post",
        dataType: "json",
        url: action,
        data: {module_id: module_id, "_csrf":csrf},
        success: function (ret) {
            if (ret.code == 0) {
                $('#edit_dialog').modal('hide');
                window.location.reload();
            }
            else {
                alert(ret.msg);
            }

        }
    });
});

