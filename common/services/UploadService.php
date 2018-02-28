<?php

namespace common\services;

use common\utils\Upload;
use Yii;
use yii\helpers\Url;

/**
 * 上传相关服务
 * Class UploadService
 * @package common\services
 */
class UploadService
{

    /**
     * 单个文件上传公用方法
     * @param array $params
     * @return array
     */
    public static function upload($params=[])
    {
        //验证上传类型
        $allowUploadTypes = array_keys(Yii::$app->params['upload_config']);//允许上传的类型
        $type = isset($params['type']) ? $params['type'] : 'image'; //上传类型  images|file  默认图片类型
        if(!in_array($type, $allowUploadTypes))
        {
            return ['errno'=>1, 'msg'=>'无效上传类型'];
        }

        //参数赋值
        $typeConfig = Yii::$app->params['upload_config'][$type];
        $fileDataField = isset($params['file_data_field']) ? $params['file_data_field'] :  $typeConfig['file_data_field']; //上传文件的文件域名称
        $rename = (isset($params['rename']) && $params['rename']==0) ? $params['rename'] : $typeConfig['rename']; //是否重命名, 0-否 1-是  默认0
        $ext = isset($params['ext']) ? $params['ext'] : $typeConfig['ext']; //是允许上传的文件扩展名 默认图片扩展
        $maxSize = isset($params['max_size']) ? $params['max_size'] : $typeConfig['max_size']; //最大文件大小, 默认500K
        $uploadPath = isset($params['upload_path']) ? $params['upload_path'] : $typeConfig['upload_path']; //上传目录， 默认为全局图片上传目录

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

		//方法1：使用ci上传类库上传 （更安全）
        $config = [
        	'upload_path' => $uploadPath,
			'allowed_types' => implode("|", explode(",", $ext)),
			'max_size' => $maxSize,
			'encrypt_name' => (bool)$rename,
		];
        $uploadModel = new Upload($config);
        $ret = $uploadModel->do_upload($fileDataField);
		if(!$ret)
		{
			return ['errno'=>1, 'msg'=> implode("|", $uploadModel->error_msg)];
		}
		$uploadData = $uploadModel->data();

		//获取文件完整url路径
		$fullPath = $uploadData['full_path'];
		$fullPath = str_replace("\\", "/", $fullPath);
		$documentRoot = str_replace("/","\\/",str_replace("\\","/", $_SERVER['DOCUMENT_ROOT']));
		$url = preg_replace('/^'.$documentRoot.'/',"",$fullPath);
//		$url = Url::base() . '/' .trim($url, '/');
		$url = Yii::$app->request->getHostInfo() . '/' .trim($url, '/');
		return ['errno'=>0, 'msg'=>'上传成功','url'=>$url];


		//方法2：常规上传
//		//PHP上传失败
//        if (!empty($_FILES[$fileDataField]['error'])) {
//            switch($_FILES[$fileDataField]['error']){
//                case '1':
//                    $error = '超过程序允许的大小。';
//                    break;
//                case '2':
//                    $error = '超过表单允许的大小。';
//                    break;
//                case '3':
//                    $error = '文件只有部分被上传。';
//                    break;
//                case '4':
//                    $error = '请选择文件。';
//                    break;
//                case '6':
//                    $error = '找不到临时目录。';
//                    break;
//                case '7':
//                    $error = '写文件到硬盘出错。';
//                    break;
//                case '8':
//                    $error = 'File upload stopped by extension。';
//                    break;
//                case '999':
//                default:
//                    $error = '未知错误。';
//            }
//            return ['errno'=>1, 'msg'=>$error];
//        }
//
//        //上传的临时文件
//        $tempFile = $_FILES[$fileDataField]['tmp_name'];
//        //上传的文件名
//        $name = $_FILES[$fileDataField]['name'];
//        //上传文件大小
//        $fileSize = $_FILES[$fileDataField]['size'];
//
//
//        //检查文件名
//        if (!$name) {
//            return ['errno'=>1, 'msg'=>'请选择文件'];
//        }
//        //检查目录
//        if (@is_dir($uploadPath) === false && @mkdir($uploadPath, 0755) === false) {
//            return ['errno'=>1, 'msg'=>'上传目录不存在'];
//        }
//        //检查目录写权限
//        if (@is_writable($uploadPath) === false) {
//            return ['errno'=>1, 'msg'=>'上传目录没有写权限'];
//        }
//        //检查是否已上传
//        if (@is_uploaded_file($tempFile) === false) {
//            return ['errno'=>1, 'msg'=>'上传失败'];
//        }
//        //检查文件大小
//        if ($fileSize > $maxSize) {
//            return ['errno'=>1, 'msg'=>'上传文件大小超过限制'];
//        }
//        //检查文件扩展名
//        $allowExts = $ext ? explode(',', $ext) : array('jpg','jpeg','gif','png'); // File extensions
//        $fileParts = pathinfo($_FILES[$fileDataField]['name']);
//        if(!in_array(strtolower($fileParts['extension']),$allowExts))
//        {
//            return ['errno'=>1, 'msg'=>"上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $allowExts) . "格式"];
//        }
//        //判断是否重命名文件名
//        if($rename)
//        {
//            $name = substr_replace($name, date('YmdHis') . rand(11111, 99999), 0, strrpos($name, '.'));
//        }
//
//        //获取文件完整url路径
//        $targetFile = str_replace("\\", "/", rtrim($uploadPath,'/') . '/' . $name);
//        $documentRoot = str_replace("/","\\/",str_replace("\\","/", $_SERVER['DOCUMENT_ROOT']));
//        $url = preg_replace('/^'.$documentRoot.'/',"",$targetFile);
//        $url = Url::base() . '/' .trim($url, '/');
//
//        if (move_uploaded_file($tempFile,$targetFile) === false) {
//            return ['errno'=>1, 'msg'=>'上传失败'];
//
//        }
//        //修改文件权限
//        @chmod($targetFile, 0755);
//
//        //上传成功
//        return ['errno'=>0, 'msg'=>'上传成功','url'=>$url];
    }

	/**
	 * 字节流上传
	 * @param string $fieldName
	 * @return array
	 */
    public static function uploadImgByte($fileDataField='')
	{
		$type = 'image';
		$typeConfig = Yii::$app->params['upload_config'][$type];
		$fileDataField = $fileDataField ? $fileDataField : Yii::$app->params['keditor_post_name']; //上传文件的文件域名称

		$file = $_POST[$fileDataField];
		if(empty($file))
		{
			return ['errno'=>1, 'msg'=>'无效上传'];
		}

//		//方法1 ：直接返回上传图片内容
//		if(!preg_match("/data:image\/(png|jpg|jpeg)/i", $file))
//		{
//			return ['errno'=>1, 'msg'=>'无效图片上传'];
//		}
//		$url = '<img src=\''.$file.'\' alt=\'\'>';
//		return ['errno'=>0, 'msg'=>'上传成功','url'=>$url];


		//方法2：保存为文件
		//参数赋值
		$maxSize = isset($params['max_size']) ? $params['max_size'] : $typeConfig['max_size']; //最大文件大小, 默认500K
		$uploadPath = isset($params['upload_path']) ? $params['upload_path'] : $typeConfig['upload_path']; //上传目录， 默认为全局图片上传目录

		//上传大小验证
		if(strlen($file) > $maxSize)
		{
			return ['errno'=>1, 'msg'=>'上传文件大小超过限制'];
		}

		if(preg_match("/<img\s*src=(\'|\")(data:image\/png;base64.*)?(\'|\").*?\/>/Ui", $file, $match))
		{
//			var_dump($match);exit;
			$file = $match[2];
		}

//		echo 222;exit;
		$file = base64_decode(str_replace('data:image/png;base64,', '', $file)); //截图得到的只能是png格式图片，所以只要处理png就行了
		$name = md5(time() . rand(100000,999999)) . '.png'; // 这里把文件名做了md5处理
		$tempName = md5(time() . rand(100000,999999) . rand(1,100000)) . '.png'; // 这里把文件名做了md5处理

		//获取文件完整url路径
		$targetFile = str_replace("\\", "/", rtrim($uploadPath,'/') . '/' . $name);
		$tempFile = str_replace("\\", "/", rtrim($uploadPath,'/') . '/' . $tempName);
        $documentRoot = str_replace("/","\\/",str_replace("\\","/", $_SERVER['DOCUMENT_ROOT']));
        $url = preg_replace('/^'.$documentRoot.'/',"",$targetFile);
        $url = Url::base() . '/' .trim($url, '/');
		//1 保存临时文件
		if (file_put_contents($tempFile, $file) == false) {
			return ['errno'=>1, 'msg'=>'上传失败'];
		}

		$im = imagecreatefrompng($tempFile);
		unlink($tempFile);//删除临时文件
		if($im){
			$sign = imagepng($im,$targetFile);//写入图片
			if($sign)
			{
				return ['errno'=>0, 'msg'=>'上传成功','url'=>$url];
			}
		}
		return ['errno'=>1, 'msg'=>'上传失败'];
	}
}