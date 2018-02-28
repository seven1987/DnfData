//自定义分页
function changePerPage(page) {
    $("#per_page").val(page);
    $('#oplog-search-form').submit();
}
//搜索
function searchAction(){
    $('#oplog-search-form').submit();
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