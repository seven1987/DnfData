<?php

namespace backend\controllers;

use common\services\UploadService;
use common\utils\CommonFun;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;


/**
 * 上传控制器.
 */
class UploadController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!Yii::$app->user->isGuest) {
                return true;
            }
        }
        return false;
    }

    /**
     * Kinkeditor编辑器文件上传
     */
    public function actionKeditupload()
    {
        //上传表单名称
        $fileDataField = \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post('file_data_field', 'Filedata')));

        //执行上传
        $type = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
        $uploadParams = [
            'file_data_field' => $fileDataField,
            'type' => $type,
			'rename' => 1,
        ];
        $uploadRet = UploadService::upload($uploadParams);
        $uploadRet['error'] = $uploadRet['errno'];
		$uploadRet['message'] = $uploadRet['msg'];
        echo json_encode($uploadRet);
    }

	/**
	 * 上传字节流文件
	 */
    public function actionKeditorbyteupload()
	{
		//上传表单名称
		$fileDataField = \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post('file_data_field', 'Filedata')));
		$uploadRet = UploadService::uploadImgByte($fileDataField);
		$uploadRet['error'] = $uploadRet['errno'];
		echo json_encode($uploadRet);
	}

	/**
	 * kindeditor 使用 文件管理器
	 * @param $dir image
	 * @param $order  name size type
	 *
	 */
	public function actionKeditfilemanage()
	{
		$php_path = dirname(__FILE__) . '/';
		$php_url = dirname($_SERVER['PHP_SELF']) . '/';




		//执行上传
		$type = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);//上传类型  images|file  默认图片类型
		$allowUploadTypes = array_keys(Yii::$app->params['upload_config']);//允许上传的类型
		if(!in_array($type, $allowUploadTypes))
		{
			return '';
		}

		//参数赋值
		$typeConfig = Yii::$app->params['upload_config'][$type];
		$ext_arr = isset($params['ext']) ? $params['ext'] : $typeConfig['ext']; //是允许上传的文件扩展名 默认图片扩展
		$ext_arr = explode(",",$ext_arr);
		$uploadPath = isset($params['upload_path']) ? $params['upload_path'] : $typeConfig['upload_path']; //上传目录， 默认为全局图片上传目录
		$uploadPath = str_replace("\\","/", $uploadPath);

		//获取文件完整url路径
		$documentRoot = str_replace("\\","/", $_SERVER['DOCUMENT_ROOT']);
		$baseUrl = str_replace($documentRoot, "", $uploadPath);


//根目录路径，可以指定绝对路径，比如 /var/www/attached/
		$root_path = $uploadPath;
//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
		$root_url = $baseUrl;

		$current_path = realpath($root_path) . '/';
		$current_url = $root_url;
		$current_dir_path = '';
		$moveup_dir_path = '';

		//排序形式，name or size or type
		$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path)) {
			echo 'Access is not allowed.';
			exit;
		}
//最后一个字符不是/
		if (!preg_match('/\/$/', $current_path)) {
			echo 'Parameter is not valid.';
			exit;
		}
//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path)) {
			echo 'Directory does not exist.';
			exit;
		}
//遍历目录取得文件信息
		$file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}

//排序
		usort($file_list, 'cmp_func');

		$result = array();
//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
//当前目录的URL
		$result['current_url'] = $current_url;
//文件数
		$result['total_count'] = count($file_list);
//文件列表数组
		$result['file_list'] = $file_list;

//输出JSON字符串
		header('Content-type: application/json; charset=UTF-8');
		echo json_encode($result);
	}
    /**
     * 上传单个图片公用方法
     * POST参数:
     * $_csrf  跨域脚本验证， ** 必须传 **
     * $file_data_field 上传文件的文件域名称
     * $imgage_type 上传类型 1-普通普通 2-战队编辑图片
     * @return string json  {"errno":0, "msg":"message","data":"maybe some data"}   errno:  0-success | other fail
     */
    public function actionImg()
    {
        //所有参数
        $fileDataField = \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post('file_data_field', 'Filedata')));//上传文件的文件域名称
        $imageType = \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post('imgage_type', 1)));//上传类型 1-普通普通 2-战队编辑图片  默认1

        $rename = in_array($imageType, [2]) ? 0 : 1;//不进行重命名的图片类型
        $uploadPath = $this->_getImageSavePath($imageType); // 根据上传类型获得保存的路径
        //不重命名的时候， 验证名称不能含中文
        if (!$rename && isset($_FILES[$fileDataField]['name'])) {
            if (preg_match("/[\x{4e00}-\x{9fa5}]/u", $_FILES[$fileDataField]['name'])) {
                echo json_encode(['errno' => 1, 'msg' => '文件名不能包含中文']);
                exit;
            }
        }
        //执行上传
        $uploadParams = [
            'file_data_field' => $fileDataField,
            'type' => 'image',
            'upload_path' => $uploadPath,
            'rename' => 0,
        ];
        $uploadRet = UploadService::upload($uploadParams);

        //战队图片上传， 执行后端战队图片同步到前端
		if($imageType == 2)
		{
			CommonFun::recurseCopy($uploadPath, $this->_getImageSavePath(3));
		}

        echo json_encode($uploadRet);
        exit;
    }

    /**
     * 上传单个文件公用方法
     * POST参数:
     * $_csrf  跨域脚本验证， ** 必须传 **
     * $file_data_field 上传文件的文件域名称
     * @return string json  {"errno":0, "msg":"message","data":"maybe some data"}   errno:  0-success | other fail
     */
    public function actionFile()
    {
        //所有参数
        $fileDataField = \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post('file_data_field', 'Filedata')));//上传文件的文件域名称

        //执行上传
        $uploadParams = [
            'file_data_field' => $fileDataField,
            'type' => 'file',
        ];
        $uploadRet = UploadService::upload($uploadParams);
        echo json_encode($uploadRet);
        exit;
    }

    /**
     * 根据上传类型， 获得上传的目录
     * @param int $imageType
     * @return bool|string
     */
    private function _getImageSavePath($imageType = 1)
    {
        switch ($imageType) {
            case 1://默认图片保存路径
                return Yii::$app->params['upload_config']['image']['upload_path'];
            case 2://战队编辑图片保存路径
                return ROOT_PATH . 'backend/web/dist/img/team';
            case 3: //用户端战队图片保存路径
                return ROOT_PATH . 'frontend/public/img/common/team';
            default:
                return false;
        }
    }
}
