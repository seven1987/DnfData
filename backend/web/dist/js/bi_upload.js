/**
 * Created by SCF on 2017/6/26.
 */

/**
 *
 * @param fileid 绑定id
 * @param multi 多个图片true单个false
 * @param imagePath 处理图片的路径
 * @param displayImage 是否显示上传后图片
 * @param list 显示上传后图片的位置
 * @param hiddenname 上传后图片隐藏的表单项名
 * @param image_type 图片默认是普通图片，用户端战队图片 3  普通图片是 1 战队编辑图片 2 其他 是公共图片
 * @param fileSizeLimit 上传图片最大限制
 * @param file_limit 多图片上传最大个数
 * @param radio_name 图片radio名
 */
function bi_upload(fileid,multi,imagePath,displayImage,list,hiddenname, image_type,fileSizeLimit, file_limit,radio_name){
    var image_type = arguments[6] != undefined ? arguments[6] : 2;
    var fileSizeLimit = arguments[7] != undefined ? arguments[7] : 2048*1024;
    var file_limit = arguments[8] != undefined ? arguments[8] : 5;
    var radio_name = arguments[9] != undefined ? arguments[9] : 'Team[logo]';
    $(function () {
        var input = document.getElementById(fileid);
        var file_limt = multi ? file_limit : 1;
        if (multi) {
            input.setAttribute('multiple', 'multiple');
        } else {
            if($("#"+fileid).attr('multiple')!=undefined) {
                input.removeAttribute('multiple');
            }
        }
        var flag = true;
        if (typeof FormData === 'undefined') {
            alert("抱歉，你的浏览器不支持 FormData");
            input.setAttribute('disabled', 'disabled');
            flag = false;
            return false;
        }
        if (flag) {
            if($("#"+fileid).html() != undefined) {
                input.addEventListener('change', readFile, false);
            }
        }
        function readFile() {
            if (file_limt<this.files.length) {
                alert("目前只允许上传"+file_limt+"图片");
                return false;
            }
            var fd = new FormData();
            if (multi) {
                for (var i = 0; i < this.files.length; i++) {
                    //*.gif;*.jpg;*.jpeg;*.png;*.bmp
                    if (!input['value'].match(/.jpg|.jpeg|.gif|.png|.bmp/i)) {　　//判断上传文件格式
                        alert("上传的图片格式不正确，请重新选择");
                        return false;
                    }
                    fd.append('Filedata[]', this.files[i]);
                }
            } else {
                fd.append('Filedata', this.files[0]);
            }
            fd.append('fileSizeLimit', fileSizeLimit);
            fd.append('multi', multi);
            fd.append('image_type', image_type);
            fd.append('act', 'bi_form');
            fd.append('tk', $('#upload_key').val());
            $.ajax({
                url: imagePath,
                type: 'post',
                data: fd,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.code != 0) {
                        alert(data.msg);
                        return false;
                    }
                    if (multi && data.msg.indexOf('部分') > 0) {
                        alert(data.msg);
                    }
                    if(displayImage){
                        if (image_type==2) {
                            teamImgDisplay(data,list,hiddenname,multi,radio_name);
                        } else {
                            checkdisplayImage(data,list,hiddenname,multi);
                        }
                    }
                    return false;
                }
            });
        }
    });
}
/**
 * 战队图标处理
 * @param data
 * @param list
 * @param hiddenname
 * @param multi
 * @param radio_name
 */
function teamImgDisplay(data,list,hiddenname,multi,radio_name){
    var url = data.data.url;
    var content = "";
    var before_content = "";
    if(multi){
        for(var i in url) {
            content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
            content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
            content +='<img src="'+url[i]+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
            content +='</div>';
            var pos = url[i].lastIndexOf("/");
            var file_name = url[i].substring(pos+1);
            if (i==0) {
                var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
                var value = hiddenvalue+ (hiddenvalue!='' ? ',': '') +url[i];
                $(":input[name='"+hiddenname+"']").val(value);
                before_content += '<div style="display: inline-block;max-width: 75px;"><input type="radio" name="'+radio_name+'" value="'+url[i]+'"  checked ><img src="'+url[i]+'"><p style="color: rgb(181,190,210)" title="'+file_name+'">'+file_name+'</p></div>';
            } else {
                before_content += '<div style="display: inline-block;max-width: 75px;"><input type="radio" name="'+radio_name+'" value="'+url[i]+'"><img src="'+url[i]+'"><p style="color: rgb(181,190,210)" title="'+file_name+'">'+file_name+'</p></div>';
            }
        }
    }else{
        content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
        content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
        content +='<img src="'+url+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
        content +='</div>';
        var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
        var value = hiddenvalue+ (hiddenvalue!='' ? ',': '') +url;
        $(":input[name='"+hiddenname+"']").val(url);
        var pos = url.lastIndexOf("/");
        var file_name = url.substring(pos+1);
        before_content = '<div style="display: inline-block;max-width: 75px;"><input type="radio" name="'+radio_name+'" value="'+url+'"  checked ><img src="'+url+'"><p style="color: rgb(181,190,210)" title="'+file_name+'">'+file_name+'</p></div>';
    }
    $("#"+list).html(content);
    $('.team_img_list').find('div:first').before(before_content);
    closeimage(hiddenname);
}
/**
 * 创建标签和url
 * @param data
 * @param list
 */
function checkdisplayImage(data,list,hiddenname,multi){
    var content = "";
    var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
    var value = hiddenvalue;
    if(multi){
        var url = data.data.url;
        for(var i in url) {
            content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
            content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
            content +='<img src="'+url[i]+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
            content +='</div>';
            value += (value != '' ? ',': '') +url[i];
        }
        $("#"+list).append(content);
        $(":input[name='"+hiddenname+"']").val(value);
    }else{
        content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
        content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
        content +='<img src="'+data.data.url+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
        content +='</div>';
        value = (value!='' ? ',': '') +data.data.url;
        $("#"+list).html(content);
        $(":input[name='"+hiddenname+"']").val(data.data.url);
    }
    closeimage(hiddenname);
}
/**
 * 点击删除图片
 * @param hiddenname
 */
function closeimage(hiddenname){
    $(".closeimg").click(function(){
        $(this).parent().css("display","none");
        var deleteimg = $(this).next("img").attr("src");
        var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
        if (!(hiddenvalue.indexOf(deleteimg+',') < 0)) {
            hiddenvalue = hiddenvalue.replace(deleteimg+',',"");
        } else if(!(hiddenvalue.indexOf(','+deleteimg) < 0)) {
            hiddenvalue = hiddenvalue.replace(','+deleteimg,"");
        } else {
            hiddenvalue = hiddenvalue.replace(deleteimg,"");
        }
        $(":input[name='"+hiddenname+"']").val(hiddenvalue);
    });
}





