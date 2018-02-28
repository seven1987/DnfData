/**
 * Created by Dell on 2017/4/26.
 */
//自定义分页样式
function changePerPage(page) {
    $("#per_page").val(page);
    $('#app-update-switch-search-form').submit();
}

//列表显示排序
function orderby(field, op) {
    var url = window.location.search;
    var optemp = field + " desc";
    if (url.indexOf('orderby') != -1) {
        url = url.replace(/orderby=([^&?]*)/ig, function ($0, $1) {
            var optemp = field + " desc";
            optemp = decodeURI($1) != optemp ? optemp : field + " asc";
            return "orderby=" + optemp;
        });
    }
    else {
        var url = url.indexOf('?') != -1 ? url + "&orderby=" + encodeURI(optemp) : url + "?orderby=" + encodeURI(optemp);
    }
    window.location.href = url;
}
//搜索查询
function searchAction() {
    $('#app-update-switch-search-form').submit();
}

//初始化迭代包新增或编辑窗口
function initEditSystemModule(datas, type) {
    if (type == 'create') {
        $("#app_type").val("").removeAttr("disabled").css("background-color", "");
        $("#channel").val("").removeAttr("disabled").css("background-color", "");
        $("#product_name").val("");
        $("#newest_ver").val("").removeAttr("disabled").css("background-color", "");
        $("#download_url").val("");
        $("#audit_switch2").prop("checked", true);
        $("#audit_switch_div").hide();
        $("#audit_ver_div").hide();
        $("#update_switch_div").hide();
        $("#update_ver_div").hide();
        $("#update_type_div").hide();
    } else {
        var data = datas.data.model;
        $("#app_update_switch_edit_title").html('迭包后台开关修改');
        $("#update_id").val(data.update_id);
        $("#app_type").val(data.app_type).attr("disabled", "disabled").css("background-color", "#999");
        $("#channel").val(data.channel).attr("disabled", "disabled").css("background-color", "#999");
        $("#product_name").val(data.product_name);
        $("#newest_ver").val(data.newest_ver).attr("disabled", "disabled").css("background-color", "#999");
        $("#download_url").val(data.download_url);
        if (data.audit_switch == 1) {
            $("#audit_switch1").prop("checked", true);
            $("#audit_ver_div").show();
        } else {
            $("#audit_switch2").prop("checked", true);
            $("#audit_ver_div").hide();
        }
        $("#audit_ver").val(data.audit_ver);
        $("#update_switch_div").show();
        if (data.update_switch == 1) {
            $("#update_switch1").prop("checked", true);
            $("#update_ver_div").show();
            $("#update_type_div").show();
        } else {
            $("#update_switch2").prop("checked", true);
            $("#update_ver_div").hide();
            $("#update_type_div").hide();
        }
        $("#update_ver_from").val(data.update_ver_from);
        $("#update_ver_to").val(data.update_ver_to);
        if (data.suggest_update == 1) {
            $("#suggest_update").prop("checked", true);
        }
        if (data.force_update == 1) {
            $("#force_update").prop("checked", true);
        }
        data.app_type == 'ios' ? $("#audit_switch_div").show() : $("#audit_switch_div").hide();
    }
    $('#edit_dialog').modal({
        show: true,
        backdrop: false
    });
}

//审核开关
$("#audit_switch_div input[type='radio']").click(function () {
    $(this).val() == 1 ? $("#audit_ver_div").show() : $("#audit_ver_div").hide();
});

//版本更新
$("#update_switch_div input[type='radio']").click(function () {
    if ($(this).val() == 1) {
        $("#update_ver_div").show();
        $("#update_type_div").show();
    } else {
        $("#update_ver_div").hide();
        $("#update_type_div").hide();
    }
});

function initModel(id, type) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "post",
        url: viewUrl,
        data: {"id": id, "_csrf": csrfToken},
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

//编辑迭代包信息
function editAction(id) {
    initModel(id, 'edit');
}

$("#app_type").change(function () {
    $(this).val() == 'ios' ? $("#audit_switch_div").show() : $("#audit_switch_div").hide();
});

//更新迭代包开关操作
/*function switchAction(update_id, type) {
    if ($.trim(update_id) == "") {
        alert('参数缺失');
        return false;
    }
    admin_tool.confirm("请确认是否开启或关闭", function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "post",
            url: switchUrl,
            data: {"id": update_id, "type": type, "_csrf": csrfToken},
            cache: false,
            dataType: "json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                admin_tool.alert('msg_info', '出错了，' + textStatus, 'warning');
            },
            success: function (data) {
                window.location.reload();
            }
        });
    });
}*/

//点击确认保存迭代包信息
$('#edit_dialog_ok').click(function (e) {
    e.preventDefault();
    $('#app-update-switch-form').submit();
});
//添加盘口点击处理事件
$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});
//点击关闭和取消页面刷新信息
$(".btn-default,.close").click(function (e) {
    window.location.reload();
});

//迭代包录入提交
$('#app-update-switch-form').bind('submit', function (e) {
    e.preventDefault();
    $("#app-update-switch-form").data('yiiActiveForm').validated = true;
    var app_type = $("#app_type").val();
    var channel = $("#channel").val();
    var product_name = $("#product_name").val();
    var newest_ver = $("#newest_ver").val();
    //非空校验
    if (app_type == '' || app_type == null) {
        alert("平台不能为空");
        return false;
    }
    if (channel == '' || channel == null) {
        alert("渠道不能为空");
        return false;
    }
    if (product_name == '' || product_name == null) {
        alert("产品名称不能为空");
        return false;
    }
    if (newest_ver == '' || newest_ver == null) {
        alert("最新版本不能为空");
        return false;
    }

    submit_check($(this));

});

function submit_check(obj) {
    var confirmcontent = "确认增加新APP迭代后台开关?" + "<br/>";
    var app_type = $("#app_type").find("option:selected").text();
    var channel = $("#channel").val();
    var product_name = $("#product_name").val();
    var newest_ver = $("#newest_ver").val();
    var download_url = $("#download_url").val();
    var audit_switch = $("#audit_switch_div input:radio:checked").val();
    confirmcontent += "平    台: " + app_type + "<br/>";
    confirmcontent += "渠    道 :" + channel + "<br/>";
    confirmcontent += "产品名称 :" + product_name + "<br/>";
    confirmcontent += "最新版本 :" + newest_ver + "<br/>";
    confirmcontent += "下载地址 :" + download_url + "<br/>";
    if (audit_switch == 1) {
        audit_switch = "打开";
        var audit_ver = $("#audit_ver").val();
        confirmcontent += "审核开关 :" + audit_switch + "<br/>";
        confirmcontent += "审核版本 :" + audit_ver + "<br/>";
    } else {
        audit_switch = "关闭";
        confirmcontent += "审核开关 :" + audit_switch + "<br/>";
    }
    var id = $("#update_id").val();
    var action = id == "" ? createUrl : updateUrl;

    if (id == "") {
        admin_tool.confirm(confirmcontent, function () {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $('#app-update-switch-form').ajaxSubmit({
                type: "post",
                data: {"_csrf": csrfToken},
                dataType: "json",
                url: action,
                error: function (xmlHttpRequest, textStatus, errorThrown) {
                    alert("出错了，" + textStatus);
                },
                success: function (value) {
                    if (value.code == 0) {
                        window.location.reload();
                    } else {
                        alert(value.msg);
                    }
                }
            });
        });
    } else {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $('#app-update-switch-form').ajaxSubmit({
            type: "post",
            data: {"_csrf": csrfToken},
            dataType: "json",
            url: action,
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function (value) {
                if (value.code == 0) {
                    window.location.reload();
                } else {
                    alert(value.msg);
                }
            }
        });
    }
}