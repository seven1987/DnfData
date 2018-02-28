/**
 * Created by Dell on 2017/4/25.
 */

$(".content-wrapper").scroll(function(event){
    var formHeight = $(".row form").height()+15;
    var formWidth = $(".row form").width();
    //$(".row form").css({"position":"fixed","z-index":10000,"background-color":"#325382","padding-left":10,"padding-right":10});
    var top = 50;
    if ($("#head-scroll").offset()==undefined) return false;
    var offSetTop = $("#head-scroll").offset().top;
    var width = $("#head-scroll").width();
    var scrollH = $(this).scrollTop();
    if(formHeight<scrollH){
        var firstTr = $("#data_table tbody tr:eq(0)>td");
        var headerTr = $("#head-scroll tr:eq(0)>th");
        var widthTr;
        for(var i=0;i<firstTr.length;i++){
            widthTr = $(firstTr[i]).width();
            $(headerTr[i]).css({"width":widthTr,"padding-left":8,"padding-right":10});
            $(firstTr[i]).css({"width":widthTr,"padding-left":8});
        }
        $(".row form").css({"position":"fixed","z-index":10,"background-color":"#325382","padding-left":10,"padding-top":11,"padding-bottom":4,'margin-top':-11,'margin-left':-10,"width":width+15});
        $("#head-scroll").css({"position":"fixed","top":formHeight+top,"width":width});
    }else{
        if (scrollH <=0) {
            $(".row form").css({"position":"static",'padding':0,'margin':0,'width':formWidth-15});
            $("#head-scroll").css({"position":"static"});
        }
    }
});


$(function ($) {
    window.admin_tool = function () {
        return {
            confirm: function (content, ok_fun) {
                $('#confirm_content').html(content);
                $('#confirm_dialog_ok').off("click").click(function () {
                    ok_fun();
                    $('#confirm_dialog').modal('hide');
                });
                $('#confirm_dialog').modal('show');



                $('.changesort').keyup(function(){
                    var data=$(this).val();
                    var newdata = data.replace(/[^\d.]/g,'');
                    $(this).val(newdata);
                });

                //绑定修改赔率时间
                $("#confirm_content .changeodd ").keyup(function(){
                    var data=$(this).val();
                    var newdata = data.replace(/[^\d.]/g,'');
                    $(this).val(newdata);
                    var partodds = $("#confirm_content .changeodd");
                    var returnrate = 0;
                    for(var i=0;i<partodds.length;i++){
                        if(partodds[i].value>0){
                            returnrate += 1/(partodds[i].value);
                        }
                    }
                    if(returnrate>0){
                        returnrate = 1/returnrate;
                    }else{
                        returnrate=0;
                    }
                    $(".changodd_returnrate").html((returnrate*100).toFixed(2));
                });


            },
            alert: function (id, msg, type) {
                var alert_type = '';
                switch (type) {
                    case 'success':
                        alert_type = 'alert-success';
                        break;
                    case 'warning':
                        alert_type = 'alert-warning';
                        break;
                    case 'danger':
                        alert_type = 'alert-danger';
                        break;
                    default:
                        alert_type = 'alert-info';
                }
                $('#' + id).html('<div class="alert ' + alert_type + ' alert-dismissable">'
                    + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + msg + '</div>');
            }
        };
    }();

    // 全选
    $('#data_table_check').click(function () {
        var b = this.checked;
        $('#data_table tbody :checkbox').each(function (i) {
            this.checked = b;
        });
    });

});
function alertbody(content,title){

    var alerttitle=$("#alerttitle");
    var alertcontent = $("#alertcontent");
    if(title){
        $(alerttitle).html(title);
    }else{
        $(alerttitle).html("提示信息");
    }
    $(alertcontent).html(content);
    $("#alertbody").addClass("alertshow");

    $("#alertconfirm").click(function(){
        $("#alertbody").removeClass("alertshow");
//                $("#alertbody").css("display","none");
    });
}


/**
 * 自定义分页js部分
 * @param page
 */

$(".page_menu,.page_icon").click(function(event){
    var status=$(".page_list").css("display");
    if(status=="none"){
        $(".page_list").css("display","block");
        event.stopPropagation();
        $(document).one("click",function(){
            $(".page_list").css("display","none");
            event.stopPropagation();
        })
    }else{
        $(".page_list").css("display","none");

    }
});


!function () {
    //laydate.skin('molv');//切换皮肤，请查看skins下面皮肤库
    laydate({elem: '#begin'});//绑定元素
    laydate({elem: '#end'});//绑定元素
    laydate({elem: '#colse'});//绑定元素
    laydate({elem: '.begin'});//绑定元素
}();
// 验证url
function checkUrl(urlString) {
    if (urlString != "") {
        var reg = /((http|ftp|https):\/\/)?[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/;
        if (!reg.test(urlString)) {
            alert("输入网址不正确");
            return false;
        }
        return true;
    }
}
//验证输入字符长度
function checklengh(str, limitlength, title) {
    if(typeof str == 'undefined')
    {
        return true;
    }
    var len = str.length;
    if (len > limitlength) {
        alert(title + " : " + "输入字符不能超过" + limitlength);
        return false;
    }
    return true;
}
//验证字符字节数
function checkBytelengh(str, limitlength, title) {
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        var a = str.charAt(i);
        if (a.match(/[^\x00-\xff]/ig) != null) {
            len += 2;
        }
        else {
            len += 1;
        }
    }
    if (len > limitlength) {
        alert(title + " : " + "输入字符不能超过" + limitlength + "个" + "(或汉字不能超过" + limitlength / 2 + "个)");
        return false;
    }
    return true;
}

//验证字符字节范围
function checkByteRange(str, minlength,maxlength, title) {
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        var a = str.charAt(i);
        if (a.match(/[^\x00-\xff]/ig) != null) {
            len += 2;
        }
        else {
            len += 1;
        }
    }

    if (len < minlength) {
        alert(title + " : " + "输入字符不能少于" + minlength + "个" + "(或汉字不能少于" + minlength/ 2 + "个)");
        return false;
    }

    if (len > maxlength) {
        alert(title + " : " + "输入字符不能超过" + maxlength+ "个" + "(或汉字不能超过" + maxlength / 2 + "个)");
        return false;
    }
    return true;
}

//验证输入数字的长度
function checknumber(number, limitnumber, title) {
    var reg = new RegExp("^[0-9]*$");
    if (!reg.test(number)) {
        alert(title + " : " + "请输入长度不超过" + limitnumber + "位的数字");
        return false;
    } else {
        if (number.length > limitnumber) {
            alert(title  + "不超过" + limitnumber + "位数");
            return false;
        }
        return true;
    }
}

//验证是否正确的IP地址
function isIp(ip){
    var reg = /^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/;
    return reg.test(ip);
}

//验证是否正确的邮箱格式
function isEmail(str){
    var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
    return reg.test(str);
}

//
function checkMobile(str) {
    var re = /^1\d{10}$/;
    return re.test(str);
}


//去除字符首位空值
function checkvalue(val) {
    return val.replace(/^\s+|\s+$/g, '');
}


//返回当前系统时间
function nowTime(){
    var myDate= new Date();
    var year=myDate.getFullYear();
    var month=myDate.getMonth()+1;
    var date =myDate.getDate();
    var d=myDate.getDay();
    var h=myDate.getHours();
    var m=myDate.getMinutes();
    var s=myDate.getSeconds();
    var timestring;
    if(month<10){
        month="0"+month;
    }
    if(date<10){
        date="0"+date;
    }
    if(h<10){
        h="0"+h;
    }
    if(m<10){
        m="0"+m;
    }
    if(s<10){
        s="0"+s;
    }

    timestring=year+"-"+month+"-"+date+" "+h+":"+m+":"+s;
    return timestring;
}


var default_siderbar;
if (typeof (Storage) !== "undefined") {
    default_siderbar = localStorage.getItem("siderbar");
    if (default_siderbar == "" || default_siderbar == null) {
        default_siderbar = 0;
    }
    localStorage.setItem("siderbar", default_siderbar);
    if (default_siderbar == 1) {
        $("body").addClass("sidebar-collapse");
    } else {
        $("body").removeClass("siderbar-collapse");
    }
} else {
    console.log("test");
}
$(".sidebar-toggle").click(function () {
    var data = localStorage.getItem("siderbar");
    if (data == 1) {
        localStorage.setItem("siderbar", 0);
    } else {
        localStorage.setItem("siderbar", 1);
    }
});

//清除非零数值
function clearNoNum(obj,num,isInt){
    num = typeof num == 'undefined' ? 0 : parseInt(num);
    isInt = typeof isInt == 'undefined' ? false : isInt;

    if(!isInt)//小数
    {
        obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符
        obj.value = obj.value.replace(/^\./g,"");  //验证第一个字符是数字而不是.
        obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的.
        obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
        if(num > 0 && obj.value.match(/^\d{1,}\.\d{2}\d/g))
        {
            obj.value = (parseFloat(obj.value)).toFixed(num);
        }
    }
    else//整数
    {
        obj.value = obj.value.replace(/[^\d]/g,"");  //清除“数字”和“.”以外的字符
    }
}

/**
 * js判断管理用户权限
 * @param privUrl
 * @param hasPrivList
 * @returns {boolean}
 */
function hasPriv(privUrl, hasPrivList){
    //拥有权限
    if(hasPrivList[privUrl]){
        return true;
    }
    //权限为定义，返回true
    if(!hasPrivList.hasOwnProperty(privUrl) ){
        return true;
    }
    //无权限
    return false;
}
