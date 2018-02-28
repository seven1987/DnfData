var csrf = $("#_csrf").val();

function changePerPage(page){
    $("#per_page").val(page);
    $('#admin-menu-search-form').submit();
}

function searchAction(){
    $('#admin-menu-search-form').submit();
}
function viewAction(id){
    initModel(id, 'view', 'fun');
}

function initEditSystemModule(data, type){
    if(type == 'create'){
        $("#menu_id").val('');
        $("#menu_name").val('');
        $("#display_order").val('');
        $("#action").val('');
        $("#controller").val('');
        $("#status").val(1);
    }
    else{
        $("#menu_id").val(data.menu_id);
        $("#menu_name").val(data.menu_name);
        $("#module_id").val(data.module_id);
        $("#display_order").val(data.display_order);
        $("#status").val(data.status);
        $("#action").val(data.action);
        $("#controller").val(data.controller);
    }
    if(type == "view"){
        $("#menu_id").attr({readonly:true,disabled:true});
        $("#menu_name").attr({readonly:true,disabled:true});
        $("#module_id").attr({readonly:true,disabled:true});
        $("#display_order").attr({readonly:true,disabled:true});
        $("#status").attr({readonly:true,disabled:true});
        $("#action").attr({readonly:true,disabled:true}).hide();
        $("#controller").attr({readonly:true,disabled:true}).hide();
        $("#action").parent().parent().hide();
        $("#controller").parent().parent().hide();

        $('#edit_dialog_ok').addClass('hidden');
    }
    else{
        $("#menu_id").attr({readonly:true,disabled:true});
        $("#menu_name").attr({readonly:false,disabled:false});
        $("#module_id").attr({readonly:false,disabled:false});
        $("#display_order").attr({readonly:false,disabled:false});
        $("#action").attr({readonly:false,disabled:false});
        $("#controller").attr({readonly:false,disabled:false});
        $("#status").attr({readonly:false,disabled:false});

        $('#edit_dialog_ok').removeClass('hidden');
    }
    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun){

    $.ajax({
        type: "POST",
        url: ADMIN_MENU_VIEW,
        data: {"id":id, "_csrf":csrf},
        cache: false,
        dataType:"json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(data){
            initEditSystemModule(data, type);
        }
    });
}

function editAction(id){
    initModel(id, 'edit');
}


function getSelectedIdValues(formId)
{
    var value="";
    $( formId + " :checked").each(function(i)
    {
        if(!this.checked)
        {
            return true;
        }
        value += this.value;
        if(i != $("input[name='id']").size()-1)
        {
            value += ",";
        }
    });
    return value;
}

$('#edit_dialog_ok').click(function (e) {
    e.preventDefault();
    $('#admin-menu-form').submit();
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#admin-menu-form').bind('submit', function(e) {

    $("#admin-menu-form").data('yiiActiveForm').validated = true;
    e.preventDefault();
    var menu_id = $("#menu_id").val();
    var action = menu_id == "" ? ADMIN_MENU_CREATE: ADMIN_MENU_UPDATE;
    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        url: action,
        data:{menu_id:menu_id, "_csrf":csrf},
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
$("#controller").change(function(){
    // 先清空第二个
    var controller = $(this).val();
    $("#action").empty();
    var option = $("<option>").html("请选择");
    $("#action").append(option);
    var actions = window.controllerData[controller];
    var nodes = actions.nodes;
    for(i = 0; i < nodes.length; i++){
        var action = nodes[i];
        var option = $("<option>").val(action.a).html(action.text);
        $("#action").append(option);
    }
});

