var csrf = $("#_csrf").val();

//添加权限按钮
$('#create_priv_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

//确认添加权限按钮
$('#edit_dialog_ok').click(function (e) {
    e.preventDefault();
    $('#admin-priv-form').submit();
});

//编辑
function editPriv(id) {
    initModel(id, 'edit');
}

// function viewAction(id) {
//     initModel(id, 'view', 'fun');
// }

//显示新增权限选择框
function initEditSystemModule(data, type)
{
    //菜单显示
    var menuObj = $('#menu_id');
    //权限路径显示
    var privUrlObj = $('#priv_url');

    //编辑权限
    if(type == 'edit' && data)
    {
        var model = data.model;
        var menuList = data.menu_list;
        var actionList = data.action_list;


        menuObj.empty();
        var option = $("<option>").val(0).html('请选择');
        menuObj.append(option);
        for(i = 0; i < menuList.length; i++){
            var option = $("<option>").val(menuList[i].menu_id).html(menuList[i].menu_name);
            menuObj.append(option);
        }


        privUrlObj.empty();
        var option = $("<option>").val(0).html('请选择');
        privUrlObj.append(option);
        for(i = 0; i < actionList.length; i++){
            var option = $("<option>").val(actionList[i].priv_url).html(actionList[i].priv_url);
            privUrlObj.append(option);
        }

        data = model;
    }
    else
    {
        menuObj.empty();
        var option = $("<option>").val(0).html('请选择');
        menuObj.append(option);

        privUrlObj.empty();
        var option = $("<option>").val(0).html('请选择');
        privUrlObj.append(option);
    }


    if (type == 'create') {
        $("#priv_id").val('');
        $("#module_id").val('');
        $("#menu_id").val('');
        $("#priv_name").val('');
        $("#priv_url").val('');
        $("#status").val(1);
        $("#admin_priv_title").html("新增权限");
    }
    else {
        $("#priv_id").val(data.priv_id);
        $("#module_id").val(data.module_id);
        $("#menu_id").val(data.menu_id);
        $("#priv_name").val(data.priv_name);
        $("#priv_url").val(data.priv_url);
        $("#status").val(data.status);
        $("#admin_priv_title").html("编辑权限");
    }
    if (type == "view") {
        $("#priv_id").attr({readonly: true, disabled: true});
        $("#module_id").attr({readonly: true, disabled: true});
        $("#menu_id").attr({readonly: true, disabled: true});
        $("#priv_name").attr({readonly: true, disabled: true});
        $("#priv_url").attr({readonly: true, disabled: true});
        $("#status").attr({readonly: true, disabled: true});
        $('#edit_dialog_ok').addClass('hidden');
    }
    else {
        $("#priv_id").attr({readonly: false, disabled: false});
        $("#module_id").attr({readonly: false, disabled: false});
        $("#menu_id").attr({readonly: false, disabled: false});
        $("#priv_name").attr({readonly: false, disabled: false});
        $("#priv_url").attr({readonly: false, disabled: false});
        $("#status").attr({readonly: false, disabled: false});
        $('#edit_dialog_ok').removeClass('hidden');
    }

    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun) {
    $.ajax({
        type: "POST",
        url: ADMIN_PRIV_VIEW,
        data: {"priv_id": id, "_csrf":csrf},
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (ret) {
            if(ret.code != 0)
            {
                alert(ret.msg);
                return;
            }
            initEditSystemModule(ret.data, type);
        }
    });
}

//添加权限，提交
$('#admin-priv-form').bind('submit', function(e) {
    e.preventDefault();
    var privId = $('#priv_id').val();
    var action = privId == "" ? ADMIN_PRIV_CREATE : ADMIN_PRIV_UPDATE;
    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        url: action,
        data: {"priv_id": privId, "_csrf": csrf},
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(ret)
        {
            if(ret.code == 0){
                $('#edit_dialog').modal('hide');
                window.location.reload();
            }
            else{
                alert(ret.msg);
            }

        }
    });
});

//选择控制器，显示菜单列表
$("#module_id").change(function(){
    var moduleId = $(this).val();
    var menuObj = $("#menu_id");
    var privObj = $('#priv_url');
    var option = $("<option>").val(0).html("请选择");

    menuObj.empty();
    menuObj.append(option);
    privObj.empty();
    privObj.append(option);

    if(moduleId<=0)
    {
        return;
    }
    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        data:{'module_id':moduleId,"_csrf": csrf},
        url: ADMIN_PRIV_GET_MENU,
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(ret)
        {
            if(ret.code == 0){
                data = ret.data;

                menuObj.empty();
                var option = $("<option>").val(0).html("请选择");
                menuObj.append(option);
                for(i = 0; i < data.length; i++){
                    var menu = data[i];
                    var option = $("<option>").val(menu.menu_id).html(menu.menu_name);
                    menuObj.append(option);
                }
            }
            else{
                alert(ret.msg);
            }
        }
    });
});

//选择菜单， 显示权限列表
$("#menu_id").change(function(){
    var menuId = $(this).val();
    var privObj = $("#priv_url");
    var option = $("<option>").val(0).html("请选择");

    privObj.empty();
    privObj.append(option);
    if(menuId<=0)
    {
        return;
    }
    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        data:{'menu_id':menuId,"_csrf": csrf},
        url: ADMIN_PRIV_GET_PRIV,
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(ret)
        {
            if(ret.code == 0){
                data = ret.data;
                for(i = 0; i < data.length; i++){
                    var priv = data[i];
                    var option = $("<option>").val(priv.priv_url).html(priv.priv_url);
                    privObj.append(option);
                }
            }
            else{
                alert(ret.msg);
            }
        }
    });
});

//分组权限执行保存
$('#save_group_priv_ok').click(function (e) {
    e.preventDefault();

    var groupId = $('input[name="group_id"]').val();
    groupId = parseInt(groupId);
    var checkedPrivs = $('input[name="priv[]"]:checked').map(function () {
        return this.value
    }).get().join(',');
    var noCheckPrivs = $('input[name="priv[]"]').not("input:checked").map(function () {
        return this.value
    }).get().join(',');
    // console.log(checkedPrivs)
    // console.log(noCheckPrivs)

    if(!groupId)
    {
        return false;
    }

    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        url: ADMIN_PRIV_GROUP_SAVE_PRIV,
        data:{'group_id':groupId, 'checked_privs': checkedPrivs, 'no_check_privs': noCheckPrivs,"_csrf": csrf},
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(ret)
        {
            if(ret.code == 0){
                $('#edit_dialog').modal('hide');
                window.location.reload();
            }
            else{
                alert(ret.msg);
            }

        }
    });

});

//checkbox选择
function selectAll(checker, scope, type)
{
    if(scope)
    {
        if(type == 'button')
        {
            $('#' + scope + ' input').each(function()
            {
                $(this).prop("checked", true)
            });
        }
        else if(type == 'checkbox')
        {
            $('#' + scope + ' input').each(function()
            {
                $(this).prop("checked", checker.checked)
            });
        }
    }
    else
    {
        if(type == 'button')
        {
            $('input:checkbox').each(function()
            {
                $(this).prop("checked", true)
            });
        }
        else if(type == 'checkbox')
        {
            $('input:checkbox').each(function()
            {
                $(this).prop("checked", checker.checked)
            });
        }
    }
}

function editPriv2(obj, privId)
{
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    var privName = $(obj).html();
    var msg = "<br/>权限名称: <input name='priv_name' value='" + privName + "' class='new_priv_name'>";

    $(".confirm-content .modal-content .modal-header").html("修改权限名称").css("color", "#b5b1d2");

    admin_tool.confirm(msg, function () {
        var newPrivName = $(".new_priv_name").val();
        $.ajax({
            type: "post",
            url: ADMIN_PRIV_CHANGE_NAME,
            dataType: "json",
            data: {"priv_id": privId, 'priv_name': newPrivName,"_csrf": csrf},
            cache: false,
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                admin_tool.alert('msg_info', '出错了，' + textStatus, 'warning');
            },
            success: function (ret) {
                if(ret.code == 0){
                    window.location.reload();
                }
                else{
                    alert(ret.msg);
                }
            }
        });

    });
}

//删除权限
$('.delete_priv_btn').click(function(e){
    e.preventDefault();
    var privUrl = $('#priv_url').val();
    var privId = $('#priv_id').val();
    if(!privId)
    {
        return false;
    }
    admin_tool.confirm('确定删除权限' + privUrl + '吗？', function () {
        $.ajax({
            type: "post",
            url: ADMIN_PRIV_DELETE,
            dataType: "json",
            data: {"priv_id": privId, "_csrf": csrf},
            cache: false,
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                admin_tool.alert('msg_info', '出错了，' + textStatus, 'warning');
            },
            success: function (ret) {
                if(ret.code == 0){
                    admin_tool.alert('删除成功');
                    window.location.reload();
                }
                else{
                    alert(ret.msg);
                }
            }
        });

    });
});