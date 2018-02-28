//userLoginLog js

function changePerPage(page){
    $("#per_page").val(page);
    $('#user-login-log-search-form').submit();
}
function orderby(field, op){
    var url = window.location.search;
    var optemp = field + " desc";
    if(url.indexOf('orderby') != -1){
        url = url.replace(/orderby=([^&?]*)/ig,  function($0, $1){
            var optemp = field + " desc";
            optemp = decodeURI($1) != optemp ? optemp : field + " asc";
            return "orderby=" + optemp;
        });
    }
    else{
        if(url.indexOf('?') != -1){
            url = url + "&orderby=" + encodeURI(optemp);
        }
        else{
            url = url + "?orderby=" + encodeURI(optemp);
        }
    }
    window.location.href=url;
}
function searchAction(){
    $('#user-login-log-search-form').submit();
}
function viewAction(id){
    initModel(id, 'view', 'fun');
}

function initEditSystemModule(data, type){
    if(type == 'create'){
        $("#id").val('');
        $("#user_id").val('');
        $("#ip").val('');
        $("#logtype").val('');
        $("#logintoken").val('');
        $("#createtime").val('');

    }
    else{
        $("#id").val(data.id);
        $("#user_id").val(data.user_id);
        $("#ip").val(data.ip);
        $("#logtype").val(data.logtype);
        $("#logintoken").val(data.logintoken);
        $("#createtime").val(data.createtime);
    }
    if(type == "view"){
        $("#id").attr({readonly:true,disabled:true});
        $("#user_id").attr({readonly:true,disabled:true});
        $("#ip").attr({readonly:true,disabled:true});
        $("#logtype").attr({readonly:true,disabled:true});
        $("#logintoken").attr({readonly:true,disabled:true});
        $("#createtime").attr({readonly:true,disabled:true});
        $('#edit_dialog_ok').addClass('hidden');
    }
    else{
        $("#id").attr({readonly:false,disabled:false});
        $("#user_id").attr({readonly:false,disabled:false});
        $("#ip").attr({readonly:false,disabled:false});
        $("#logtype").attr({readonly:false,disabled:false});
        $("#logintoken").attr({readonly:false,disabled:false});
        $("#createtime").attr({readonly:false,disabled:false});
        $('#edit_dialog_ok').removeClass('hidden');
    }
    $('#edit_dialog').modal('show');
}

function initModel(id, type, fun){

    $.ajax({
        type: "GET",
        url: "<?=Url::toRoute('user-login-log/view')?>",
        data: {"id":id},
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

function deleteAction(id){
    var ids = [];
    if(!!id == true){
        ids[0] = id;
    }
    else{
        var checkboxs = $('#data_table :checked');
        if(checkboxs.size() > 0){
            var c = 0;
            for(i = 0; i < checkboxs.size(); i++){
                var id = checkboxs.eq(i).val();
                if(id != ""){
                    ids[c++] = id;
                }
            }
        }
    }
    if(ids.length > 0){
        admin_tool.confirm('请确认是否删除', function(){
            $.ajax({
                type: "GET",
                url: "<?=Url::toRoute('user-login-log/delete')?>",
                data: {"ids":ids},
                cache: false,
                dataType:"json",
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    admin_tool.alert('msg_info', '出错了，' + textStatus, 'warning');
                },
                success: function(data){
                    for(i = 0; i < ids.length; i++){
                        $('#rowid_' + ids[i]).remove();
                    }
                    admin_tool.alert('msg_info', '删除成功', 'success');
                    window.location.reload();
                }
            });
        });
    }
    else{
        admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
    }

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
        if(i != $("input[name='user_id']").size()-1)
        {
            value += ",";
        }
    });
    return value;
}

$('#edit_dialog_ok').click(function (e) {
    e.preventDefault();
    $('#user-login-log-form').submit();
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#delete_btn').click(function (e) {
    e.preventDefault();
    deleteAction('');
});

$('#user-login-log-form').bind('submit', function(e) {

    $("#user-login-log-form").data('yiiActiveForm').validated = true;

    e.preventDefault();
    var id = $("#user_id").val();
    var action = id == "" ? "<?=Url::toRoute('user-login-log/create')?>" : "<?=Url::toRoute('user-login-log/update')?>";
    $(this).ajaxSubmit({
        type: "post",
        dataType:"json",
        url: action,
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function(value)
        {
            if(value.errno == 0){
                $('#edit_dialog').modal('hide');
                var optype = value.type;
                if (optype==1) {   //create
                    admin_tool.alert('msg_info', '添加成功', 'success');
                    window.location.reload();
                }else if (optype==2){       //update, 局部更新
                    var json = value.data;
                    for(var key in json){
                        var tagname = "dm_"+key+"_"+json["user_id"];
                        var tagvalue = json[key];
                        $('#' + tagname).html(tagvalue);
                    }
                }
            }
            else if(value.errno == 1){
                alert("数据保存出错: "+value.errors);
            }
            else if(value.errno == 2){
                alert('提交数据有误: ');
                var json = value.data;
                for(var key in json){
                    $('#' + key).attr({'data-placement':'bottom', 'data-content':json[key], 'data-toggle':'popover'}).addClass('popover-show').popover('show');

                }
            }

        }
    });
});
