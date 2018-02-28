/**
 * Created by xiaoda on 2017/4/25.
 */

//自定义分页
function changePerPage(page) {
    $("#per_page").val(page);
    $('#admin-log-search-form').submit();
}

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
        if (url.indexOf('?') != -1) {
            url = url + "&orderby=" + encodeURI(optemp);
        }
        else {
            url = url + "?orderby=" + encodeURI(optemp);
        }
    }
    window.location.href = url;
}

function searchAction() {
    $('#admin-log-search-form').submit();
}

function viewAction(id, url) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        type: "post",
        url: url,
        data: {"id": id, "_csrf": csrfToken},
        cache: false,
        dataType: "json",
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
        success: function (data) {
            if (data.code == 0) {
                initEditSystemModule(data.data);
            } else {
                alert("Code:" + data.code);
            }
        }
    });
}

function initEditSystemModule(data) {
    $("#id").val(data.id);
    $("#controller_id").val(data.controller_id);
    $("#action_id").val(data.action_id);
    $("#url").val(data.url);
    $("#module_name").val(data.module_name);
    $("#func_name").val(data.func_name);
    $("#right_name").val(data.right_name);
    $("#client_ip").val(data.client_ip);
    $("#create_user").val(data.create_user);
    $("#create_date").val(data.create_date);

    $("#id").attr({readonly: true, disabled: true});
    $("#controller_id").attr({readonly: true, disabled: true});
    $("#action_id").attr({readonly: true, disabled: true});
    $("#url").attr({readonly: true, disabled: true});
    $("#module_name").attr({readonly: true, disabled: true});
    $("#func_name").attr({readonly: true, disabled: true});
    $("#right_name").attr({readonly: true, disabled: true});
    $("#client_ip").attr({readonly: true, disabled: true});
    $("#create_user").attr({readonly: true, disabled: true});
    $("#create_date").attr({readonly: true, disabled: true});

    $('#edit_dialog_ok').addClass('hidden');
    $('#edit_dialog').modal('show');
}
