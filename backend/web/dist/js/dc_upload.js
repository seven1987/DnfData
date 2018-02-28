var token =  $('#upload_key').val();

var MATCH_FOLDER = 'match';
var TEAM_FOLDER = 'team';
var ROLE_FOLDER  = 'role';
var ZONE_FOLDER  = 'zone';
var PLAYER_FOLDER  = 'player';

/**
 * 上传一个图片
 * @param file_tag_id   文件输入标签ID
 * @param server_url    图片服务器地址
 * @param img_tag_id    图片标签ID
 * @param hidden_tag_name  表单中隐藏传递图片url的标签名
 */
function upload_one_img(file_tag_id, server_url,img_tag_id,hidden_tag_name,img_folder){
    var fileSizeLimit = 2048*1024;
    var file_limt = 1;

    $(function () {
        var input = document.getElementById(file_tag_id);

        if($("#"+file_tag_id).attr('multiple')!=undefined) {
            input.removeAttribute('multiple');
        }
        var flag = true;
        if (typeof FormData === 'undefined') {
            alert("抱歉，你的浏览器不支持 FormData");
            input.setAttribute('disabled', 'disabled');
            flag = false;
            return false;
        }
        if (flag) {
            if($("#"+file_tag_id).html() != undefined) {
                input.addEventListener('change', readFile, false);
            }
        }
        function readFile() {
            if (file_limt<this.files.length) {
                alert("只允许上传"+file_limt+"张图片");
                return false;
            }
            var fd = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                //*.gif;*.jpg;*.jpeg;*.png;*.bmp
                if (!input['value'].match(/.jpg|.jpeg|.gif|.png|.bmp/i)) {　　//判断上传文件格式
                    alert("上传的图片格式不正确，请重新选择");
                    return false;
                }
            }

            fd.append('Filedata', this.files[0]);
            fd.append('fileSizeLimit', fileSizeLimit);
            fd.append('multi', false);
            fd.append('image_type', img_folder);
            fd.append('act', 'bi_form');
            fd.append('tk', token);
            $.ajax({
                url: server_url,
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
                    displayImage(data,img_tag_id,hidden_tag_name);

                    return false;
                }
            });
        }
    });
}


/**
 * 创建标签和url
 * @param data
 * @param img_tag_id
 * @param hidden_tag_name
 */
function displayImage(data,img_tag_id,hidden_tag_name){
    $(":input[name='"+hidden_tag_name+"']").val(data.data.url);
    $("#"+img_tag_id).attr('src',data.data.url);
}


//update_one_img("file_upload",UPLOAD, 'role_avatar_show','Role[avatar]',ROLE_FOLDER);
