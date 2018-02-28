<?php
/**
 * Created by PhpStorm.
 * User: ldm
 */

namespace backend\widgets;

use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;

class KeditorWidget extends Widget
{
	public $id;
	public $type;
    public $name;
    public $fileDataField;
    public $width;
    public $height;
    public $item;
    public $allowImageUpload=null;
	public $content;


    /**
     * 返回keditor编辑器
	 * @param $type 编辑器类型  simple normal
	 * @param $id 用于区分不同的编辑器， 必须为英文字母的字符串
	 * @param $name 字段name 必填
     * @param $fileDataField 表单上传字段
     * @param $width  编辑器宽度
	 * @param $height 编辑器高度
     * @return string 编辑器内容， 直接输出即可
	 *
	 * example:
	 * <?php echo \backend\widgets\KeditorWidget::widget(["type" => "normal","name" => "News[content]", "id" => "content"]);?>
     */
    public function run()
    {
    	if(empty($this->name) || empty($this->id))
		{
			throw new yii\base\Exception("editor id and name necessay");
		}
		$editorName = 'editor_' . $this->name;
		$editorName = preg_replace("/\[|\]|-|\\\\/", "", $editorName);
    	$type = !empty($this->type) ? $this->type : Yii::$app->params['keditor_type'];//编辑器显示类型 简单模式 正常模式等
        $fileDataField = !empty($this->fileDataField) ? $this->fileDataField : Yii::$app->params['upload_config']['image']['file_data_field'];
        $width = !empty($this->width) ? $this->width : '800px';
        $height = !empty($this->height) ? $this->height : '400px';
        //normal编辑类型渲染功能  multiimage(多图片上传)
        $defaultItemNormal = <<<EOF
        [
                    'source', '|', 'undo', 'redo', '|', 'preview', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'flash', 'insertfile', 'table', 'hr', 'emoticons', 'pagebreak',
                    'anchor', 'link', 'unlink'
        ]
EOF;
        //simple编辑类型渲染功能
		$defaultItemSimple = <<<EOF
        [
		    'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright'
        ]
EOF;
		$defaultItem = $type == 'simple' ? $defaultItemSimple : $defaultItemNormal;
        $item = !empty($this->item) ? $this->item : $defaultItem;
        $allowImageUpload = !is_null($this->allowImageUpload) ? (bool)$this->allowImageUpload : true;

        //构造返回数据
        $editorHtml = '';
        $editorHtml .= '<textarea name="'.$this->name.'" id="'.$this->id.'" style="width:'.$width.';height:'.$height.';visibility:hidden;">'.stripslashes($this->content).'</textarea>';
        $script = <<<EOF
<link href='%s' rel="stylesheet">
<script src='%s'></script>
<script src='%s'></script>
<script>
    var $editorName;
    KindEditor.ready(function(K) {
        $editorName = K.create('textarea[id="%s"]', {
            allowFileManager : true,
            filePostName: '%s',
            uploadJson : '%s',
            urlType:'domain',
            extraFileUploadParams: {
                '%s': '%s',
                'file_data_field': '%s',
                'redirect': '%s',
                'act':'bi_form',
                'tk':$('#upload_key').val()
            },
            cssData: 'body{background-color:#737373;color:#fff;}',
            items:%s,
            allowImageUpload:%s,
            allowFileManager:false,
            fileManagerJson:'%s',
            syncType: 'form',
            allowFlashUpload: false,
            allowMediaUpload: false,
            afterUpload: function(data){
            	//console.log(data);
            },
            afterBlur: function () { this.sync(); },
            afterCreate : function()
            {
                var doc = this.edit.doc;
                var cmd = this.edit.cmd;
                if(!K.WEBKIT && !K.GECKO)
                {
                    var pasted = false;
                    $(doc.body).bind('paste', function(ev)
                    {
                        pasted = true;
                        return true;
                    });
                    setTimeout(function()
                    {
                        $(doc.body).bind('keyup', function(ev)
                        {
                            if(pasted)
                            {
                                pasted = false;
                                return true;
                            }
                            if(ev.keyCode == 86 && ev.ctrlKey) alert('您的浏览器不支持粘贴图片！');
                        })
                    }, 10);
                }
                /* Paste in chrome.*/
                /* Code reference from http://www.foliotek.com/devblog/copy-images-from-clipboard-in-javascript/. */
                if(K.WEBKIT)
                {
                    $(doc.body).bind('paste', function(ev)
                    {
                        var \$this    = \$(this);
                        var original = ev.originalEvent;
                        var file     = original.clipboardData.items[0].getAsFile();
                        if(file)
                        {
                            var reader = new FileReader();
                            reader.onload = function(evt)
                            {
                                var result = evt.target.result;
                                var result = evt.target.result;
                                var arr    = result.split(",");
                                var data   = arr[1]; // raw base64
                                var contentType = arr[0].split(";")[0].split(":")[1];

                                html = result;
                                $.post('%s', {%s: html,%s:'%s','file_data_field': '%s', act:'encode', tk:$('#upload_key').val()}, function(data){
                                	if(data.code == 0)
                                	{
                                	   cmd.inserthtml("<img src='" + data.data.url +  "' alt=''/>");
                                	}
                                	else
                                	{
                                		alert(data.msg);
                                		return;
                                	}
                                },'json');
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }
                /* Paste in firfox and other firfox.*/
                else
                {
                    K(doc.body).bind('paste', function(ev)
                    {
                        setTimeout(function()
                        {
                            var html = K(doc.body).html();
                            if(html.search(/<img src="data:.+;base64,/) > -1)
                            {
                                K(doc.body).html(html.replace(/<img src="data:.+;base64,.*".*\/>/, ''));
                                $.post('%s', {%s: html,%s:'%s','file_data_field': '%s',act:'encode', tk:$('#upload_key').val()}, function(data){
                                	if(data.code == 0)
                                	{
                                	   cmd.inserthtml("<img src='" + data.data.url +  "' alt=''/>");
                                	}
                                	else
                                	{
                                		alert(data.msg);
                                	}
                                	
                                },'json');
                            }
                        }, 80);
                    });
                }
                /* End */
            },
        });
    });
</script>
EOF;

        //图片上传地址
        //1 本地测试，上传本地
        if(
            strpos($_SERVER['HTTP_HOST'] , 'localhost') !== false
            || strpos($_SERVER['HTTP_HOST'] , '127.0.0.1') !== false
        )
        {
            $uploadServiceUrl = Url::toRoute('upload/keditupload');//本地上传
            $uploadServiceUrlEncode = Url::toRoute('upload/keditorbyteupload');
        }
        else
        {
            $uploadServiceUrl = Yii::$app->params['uploadService'];//图片服务器
            $uploadServiceUrlEncode = Yii::$app->params['uploadService'];//图片服务器 base编码上传 通过post传参act = encode
        }

        $script = sprintf(
            $script,//字符串
			Url::base() . "/plugins/kindeditor-4.1.10/themes/default/default.css",//kindeditor编辑器 css
			Url::base() . "/plugins/kindeditor-4.1.10/kindeditor-all.js",//kindeditor编辑器 js
			Url::base() . "/plugins/kindeditor-4.1.10/lang/zh_CN.js",//kindeditor编辑器 语言包
            $this->id,//当前字段名称
            $fileDataField,//文件上传表单字段
            $uploadServiceUrl,//上传脚本处理

            Yii::$app->request->csrfParam,//csrf参数名
            Yii::$app->request->getCsrfToken(),//csrf参数值
			$fileDataField,//文件上传表单字段
            Yii::$app->request->hostInfo . '/redirect.html',//重定向地址， kindeditor跨域上传专用
            $item,//编辑器操作项
            $allowImageUpload,//是否允许上传图片
			Url::toRoute('upload/keditfilemanage'),//上传脚本处理

			//chrome 粘贴图片上传
            $uploadServiceUrlEncode,//图片服务器地址 通过post传参act = encode

			$fileDataField,
			Yii::$app->request->csrfParam,//csrf参数名
			Yii::$app->request->getCsrfToken(),//csrf参数值
			$fileDataField,//文件上传表单字段

			//firfox 粘贴图片上传
            $uploadServiceUrlEncode,//字节流上传脚本处理

			$fileDataField,
			Yii::$app->request->csrfParam,//csrf参数名
			Yii::$app->request->getCsrfToken(),//csrf参数值
			$fileDataField//文件上传表单字段
        );
        $editorHtml .= $script;

        return $editorHtml;
    }

}