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
    $("#log_id").val(data.log_id);
    $("#module_name").val(data.module_name);
    $("#menu_name").val(data.menu_name);
    $("#priv_name").val(data.priv_name);
    $("#priv_url").val(data.priv_url);
    $("#client_ip").val(data.client_ip);
    $("#request_data").val(data.request_data);
    $("#create_user").val(data.create_user);
    $("#create_date").val(data.create_date);

    $("#id").attr({readonly: true, disabled: true});
    $("#module_name").attr({readonly: true, disabled: true});
    $("#menu_name").attr({readonly: true, disabled: true});
    $("#priv_name").attr({readonly: true, disabled: true});
    $("#priv_url").attr({readonly: true, disabled: true});
    $("#client_ip").attr({readonly: true, disabled: true});
    $("#right_name").attr({readonly: true, disabled: true});
    $("#request_data").attr({readonly: true, disabled: true});
    $("#create_user").attr({readonly: true, disabled: true});
    $("#create_date").attr({readonly: true, disabled: true});

    $('#edit_dialog_ok').addClass('hidden');
    $('#edit_dialog').modal('show');
}
