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
 */
function upload(fileid,multi,imagePath,displayImage,list,hiddenname, image_type){
    var image_type = arguments[6] != undefined ? arguments[6] : 2;
    $(function () {
        $('#'+fileid).uploadify({
            'formData': {
                'image_type': image_type,
                '_csrf': csrf,
                'act' : 'form',
                'tk':$('#upload_key').val()
            },
            'fileSizeLimit' : '2000KB',
            'multi':multi,
            'fileTypeExts':'*.gif;*.jpg;*.jpeg;*.png;*.bmp',
            'swf': '/plugins/uploadify/uploadify.swf',
            'removeTimeout':2,
            'uploader': imagePath,
            'buttonText' : '选择图片',
            'onUploadSuccess': function (file, data, response) {
                var back_data = data;
                data = typeof data == 'object' || $.parseJSON(data);
                if (!data || typeof data.code == 'undefined') {
                    alert('上传出错');
                    return;
                }
                if (data.code != 0) {
                    alert(data.msg);
                    return false;
                }
                if(displayImage){
                    if (image_type==2) {
                        teamImgDisplay(back_data,list,hiddenname,multi);
                    } else {
                        checkdisplayImage(back_data,list,hiddenname,multi);
                    }
                }

            }
        });
    });
}
/**
 * 战队图标处理
 * @param data
 * @param list
 * @param hiddenname
 * @param multi
 */
function teamImgDisplay(data,list,hiddenname,multi){
    var result = JSON.parse(data);
    var content = "";
    content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
    content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
    content +='<img src="'+result.data.url+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
    content +='</div>';
    var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
    var value = hiddenvalue+ (hiddenvalue!='' ? ',': '') +result.data.url;
    if(multi){
        $("#"+list).append(content);
        $(":input[name='"+hiddenname+"']").val(value);
    }else{
        $("#"+list).html(content);
        $(":input[name='"+hiddenname+"']").val(result.data.url);
    }

    var pos = result.data.url.lastIndexOf("/");
    var file_name = result.data.url.substring(pos+1);
    $('.team_img_list').find('div:first').before('<div style="display: inline-block;max-width: 75px;"><input type="radio" name="Team[logo]" value="'+result.data.url+'"  checked ><img src="'+result.data.url+'"><p style="color: rgb(181,190,210)" title="'+file_name+'">'+file_name+'</p></div>');

    closeimage(hiddenname);
}
/**
 * 创建标签和url
 * @param data
 * @param list
 */
function checkdisplayImage(data,list,hiddenname,multi){
    var result = JSON.parse(data);
    var content = "";
    content +='<div class="imageone" style="display:inline-block;position: relative;width:70px;height:70px;margin-right: 10px;">';
    content +='<label  class="closeimg" style="width:10px;height:10px;cursor:pointer;position: absolute;top:0;right:3px;z-index: 1000">×</label>';
    content +='<img src="'+result.data.url+'"'+'style="border: 1px solid #FFFFFF;position: absolute;width: 100%;height:100%"/>';
    content +='</div>';
    var hiddenvalue =  $(":input[name='"+hiddenname+"']").val();
    var value = hiddenvalue+ (hiddenvalue!='' ? ',': '') +result.data.url;
    if(multi){
        $("#"+list).append(content);
        $(":input[name='"+hiddenname+"']").val(value);
    }else{
        $("#"+list).html(content);
        $(":input[name='"+hiddenname+"']").val(result.data.url);
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





