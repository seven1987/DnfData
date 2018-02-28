
var _csrf = $("#_csrf").val();
var editor;
KindEditor.ready(function(K) {
    editor = K.create('textarea[name="content"]', {
        readonlyMode : true,
        resizeType : 0,
        allowPreviewEmoticons : false,
        allowImageUpload : false,
        items : [
            'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright']
    });
    // 取消只读状态
    K('a[id=editanouce]').click(function() {
        editor.readonly(false);
    });
});

$("#editanouce").click(function(){
    $("#editcontent").addClass("addedit");
    $("#editcontent").css('opacity',1);
});

$("#closewindow").click(function(){
    $("#editcontent").removeClass("addedit");
    $("#editcontent").css('opacity',0);
});


$("#commontype").click(function(){
    $(this).addClass("typeselect");
    $("#agenttype").removeClass("typeselect");
    $("#anouncecommon").addClass("anouceactive");
    $("#anounceagent").removeClass("anouceactive");
    $("#contenttype").val("anouncecommon");
});
$("#agenttype").click(function(){
    $(this).addClass("typeselect");
    $("#commontype").removeClass("typeselect");
    $("#anounceagent").addClass("anouceactive");
    $("#anouncecommon").removeClass("anouceactive");
    $("#contenttype").val("anounceagent");
});

$('#saveanouce').click(function (e) {
    e.preventDefault();
    $("#editcontent").removeClass("addedit");
    $("#editcontent").css('opacity',0);

    var type=$("#contenttype").val();
    var editcontent=editor.html();
    var title =$(".titletext").val();
    var nowtime = nowTime();
    $("#edittext").html(editcontent);

    $.ajax({
        type: "post",
        dataType:"json",
        url: SITE_VIEW,
        data:{'_csrf':_csrf, 'title':title, 'content':editcontent, 'contenttype':type, 'updatetime':nowtime},
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            admin_tool.confirm("出错了，" + textStatus, function () {});
        },
        success: function(data)
        {
            console.log(data);
            if (data.code == 0) {
                admin_tool.confirm(data.msg, function () {
                    if(type=="anouncecommon"){
                        $("#anoucetitle").html(editcontent);
                        $("#anouncetime").html(nowTime());
                        $("#updatetime").val(nowTime());
                        $("#titlecommon").html(title);
                    }else{
                        $("#anoucetitleagent").html(editcontent);
                        $("#anouncetimeagent").html(nowTime());
                        $("#updatetime").val(nowTime());
                        $("#titlecagent").html(title);
                    }
                });
            } else {
                admin_tool.confirm(data.msg, function () {});
            }
        }
    });
});

function connectWebSocket() {
    websocket = new WebSocket(url);
    websocket.onopen = function(evt) {
        var tag = document.getElementById("msgconnect");
        tag.innerHTML = "(连接成功)";
    };
}

connectWebSocket();