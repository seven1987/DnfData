<?php

namespace common\utils;

use backend\models\AdminGroup;
use backend\models\AdminLogs;
use backend\services\AdminPrivService;
use yii\base\Object;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class CommonFun extends Object
{
	public static $header = [
		'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
		'Accept-Encoding: gzip, deflate, sdch, br',
		'Accept-Language: zh-CN,zh;q=0.8',
		'Connection: keep-alive',
		'Content-Type: text/html; charset=UTF-8',
		'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2950.5 Safari/537.36',
		'Pragma: no-cache',
		'Host:lol.esportsmatrix.com',
		'Cookie:preferred_culture=zh-CN; Hm_lvt_90643c19d2b60d869ed81b0e1e41ef2f=1499137148,1499154110; Hm_lpvt_90643c19d2b60d869ed81b0e1e41ef2f=1499172839',
	];

    /*
    * 二维数组按照指定的键值进行排序
    */
    public static function arraySort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if (strtolower($type) == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }


    //单位转换
    public static function sizecount($filesize)
    {
        if ($filesize >= 1073741824) {
            $filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
        } elseif ($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
        } elseif ($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
            $filesize = $filesize . ' Bytes';
        }
        return $filesize;
    }

    /**
     * 获取客户端IP
     * @return string 返回ip地址,如127.0.0.1
     */
    public static function getClientIp()
    {
        $onlineip = 'Unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $real_ip = $ips['0'];
            if ($_SERVER['HTTP_X_FORWARDED_FOR'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $real_ip)) {
                $onlineip = $real_ip;
            } elseif ($_SERVER['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
                $onlineip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['HTTP_CDN_SRC_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CDN_SRC_IP'])) {
            $onlineip = $_SERVER['HTTP_CDN_SRC_IP'];
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['HTTP_NS_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_NS_IP'])) {
            $onlineip = $_SERVER ['HTTP_NS_IP'];
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['REMOTE_ADDR']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['REMOTE_ADDR'])) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['remote_addr']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['remote_addr'])) {
            $onlineip = $_SERVER['remote_addr'];
        }
        return $onlineip;
    }

    /**
     * 读取文本末尾n行
     * @param string $fileName
     * @param int $n
     * @param int $base
     * @return array:
     */
    public static function tail($fileName, $n, $base = 5)
    {
        $fp = fopen($fileName, "r+");
        $pos = $n + 1;
        $lines = array();
        while (count($lines) <= $n) {
            try {
                fseek($fp, -$pos, SEEK_END);
            } catch (\Exception $e) {
                fseek($fp, 0);
                break;
            }
            $pos *= $base;
            while (!feof($fp)) {
                array_unshift($lines, fgets($fp));
            }
        }
        //echo implode ( "", array_reverse ( $lines ) );
        return array_reverse(array_slice($lines, 0, $n));
    }


    public static function sortClass($orderby, $key)
    {
        $data = explode(' ', $orderby);
        $sortClass = 'class="sorting"';
        if (count($data) > 0) {
            if (empty($data[0]) == false && $data[0] == $key) {
                if (empty($data[1]) == false && $data[1] == 'desc') {
                    $sortClass = 'class="sorting_desc"';

                } else {
                    $sortClass = 'class="sorting_asc"';
                }
            }
        }
        return $sortClass;
    }

    /**
     * 全概率计算
     *
     * @param array $ps ('a'=>0.5,'b'=>0.2,'c'=>0.4)
     * @return string 返回上面数组的key
     */
    public static function random($ps)
    {
        static $arr = array();
        $key = md5(serialize($ps));

        if (!isset($arr[$key])) {
            $max = array_sum($ps);
            foreach ($ps as $k => $v) {
                $v = $v / $max * 10000;
                for ($i = 0; $i < $v; $i++) $arr[$key][] = $k;
            }
        }
        return $arr[$key][mt_rand(0, count($arr[$key]) - 1)];
    }

    public static function get_rand($proArr)
    {
        $result = '';

        //概率数组的总概率精度
        $proSum = array_sum($proArr);

        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);

        return $result;
    }

    /* @desc 格式化数组为某个字段作为key的关联数组-处理列表二维数组用（用于控制器拼装数据, 避免嵌套循环）
     * @author  ldm
     * @update 2015-8-8
     * @access  public
     * @param array $array
     * @param string $field
     * @return array
     */
    public static function buildRelationArray($array, $field)
    {
        if (empty($array) || empty($field)) {
            return array();
        }

        $returnList = array();
        foreach ($array as $key => $value) {
            if (isset($value[$field]) && $value[$field]) {
                $returnList[$value[$field]] = $value;
            }
        }

        return $returnList;
    }

    static $beginTime = array();
    static $endTime = array();
    static $beginIndex = 0;
    static $endIndex = 0;

    public static function b()
    {
        CommonFun::$beginTime = explode(' ', microtime());
        CommonFun::$beginTime[CommonFun::$beginIndex] = CommonFun::$beginTime[1] + CommonFun::$beginTime[0];
        CommonFun::$beginIndex = CommonFun::$beginIndex + 1;
    }

    public static function e($msg = "")
    {
        CommonFun::$endTime = explode(' ', microtime());
        CommonFun::$endTime[CommonFun::$endIndex] = CommonFun::$endTime[1] + CommonFun::$endTime[0];
        CommonFun::$endIndex = CommonFun::$endIndex + 1;
        if ($msg == "") {
            $msg = "use time:";
        }
        echo $msg . round(CommonFun::$endTime[CommonFun::$endIndex - 1] - CommonFun::$beginTime[CommonFun::$beginIndex - 1], 3) . "\n";
        CommonFun::$endIndex = CommonFun::$endIndex - 1;
        CommonFun::$beginIndex = CommonFun::$beginIndex - 1;
        echo "\n";
    }

    /**
     * 浏览器友好的变量输出
     * @param mixed $var 变量
     * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
     * @param string $label 标签 默认为空
     * @param boolean $strict 是否严谨 默认为true
     * @return void|string
     */
    public static function dump($var, $echo = true, $label = null, $strict = true)
    {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        } else {
            return $output;
        }
    }

    /**
     * 用户输入转义，防xss、sql注入
     *
     * @param string||array $data
     *
     * @return string||array
     */
    public static function inputEncode($data = null)
    {
        if (!is_array($data)) {
            $data = preg_replace('/=|\(|\)|\||\$|\%|\\|\//i', '', $data);
            $data = \yii\helpers\Html::encode($data);
        } else {
            foreach ($data as $k => $v) {
                if (is_array($v)) {
                    $data[$k] = CommonFun::inputEncode($v);
                } else {
                    $v = preg_replace('/=|\(|\)|\||\$|\%|\\|\//i', '', $v);
                    $data[$k] = \yii\helpers\Html::encode($v);
                }
            }
        }
        return $data;
    }

    /**
     * 验证手机号是否正确（国内）
     *
     * 移动：134、135、136、137、138、139、150、151、152、157、158、159、182、183、184、187、188、178(4G)、147(上网卡)；
     * 联通：130、131、132、155、156、185、186、175、176(4G)、145(上网卡)；
     * 电信：133、153、180、181、189 、177(4G)；
     * 卫星通信：1349
     * 虚拟运营商：170 171 173
     *
     * @param string $mobile
     * @return boolean
     */
    public static function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^((1[38]\d{9})|(((14[57])|(17[0135678])|(15[^4]))\d{8}))$#', $mobile) ? true : false;
    }

    /**
     * 防止MongoDB的Object方式注入
     *
     * @param array|string $data
     * @return array|string
     */
    public static function arrayValueToString($data)
    {
        if (!is_array($data)) {
            return (string)$data;
        } else {
            $isObject = false;
            foreach ($data as $k => $v) {
                // 对象方式传参 最后的key是含$认为是注入
                if (preg_match('/\$/i', $k)) {
                    $isObject = true;
                    break;
                } else {
                    if (is_array($v)) {
                        $data[$k] = CommonFun::arrayValueToString($v);
                    } else {
                        $data[$k] = (string)$v;
                    }
                }
            }
            if ($isObject) {
                $data = json_encode($data);
            }
            return $data;
        }
    }

    /**
     * 生成一个不重复的邀请码
     *
     * @return string
     */
    public static function getInviteCode()
    {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0, 25)]
            . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5)
            . substr(microtime(), 2, 5)
            . sprintf('%02d', rand(0, 99));
        for (
            $a = md5($rand, true),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord($a[$f]),
            $d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],
            $f++
        ) ;
        return $d;
    }

    /**
     * 获取 Geetest 验证码数据
     *
     * @param string $ip
     * @return array
     */
    public static function getGeetest($ip) {
        $geetest = new GeetestLib('b913fe2c86f0bccff94832ea4ff52090', '9c49a64b4a6bc9f71b86f57effcd42bd');
        $geetest->pre_process([
            'user_id' => 'test',
            'client_type' => 'web',
            'ip_address' => $ip,
        ]);
        return $geetest->get_response();
    }

    /**
     * 获取字符串中英文混合长度，一个中文长度设为 2
     * 先把计算所有字符的个数，在匹配出中文的个数（utf-8）,然后相加
     * 问题：中文符号未计算在中文个数里面！
     *
     * @param string $str 字符串
     * @return integer
     */
    public static function byteLen($str) {
        preg_match_all("/./us", $str, $match1);
        preg_match_all("/[\\x{4e00}-\\x{9fa5}]/u", $str, $match2);
        return count($match1[0]) + count($match2[0]);
    }

    /**
     * 读取前端所需的语言包
     * @param string $category
     * @return string
     */
    public static function loadLang($category='frontend_js')
    {
        $file = Yii::getAlias(Yii::$app->i18n->getMessageSource($category)->basePath) . '/' . Yii::$app->language . '/' . $category . '.php';
        if(!is_file($file))
        {
            return '';
        }
        $langData = include($file);
        return json_encode($langData);
    }
    public static function ObjToArray($obj)
    {
        foreach ($obj as $val){
            $result[] = $val->attributes;
        }
        return $result;
    }

	/**
	 * 配置项写入文件, 支持返回一维数组的配置文件
	 * @param        $file    配置文件完整路径
	 * @param        $config  example: ['配置名称1' => '配置值1', '配置名称2' => '配置值2']
	 * @param string $comment 注释
	 * @return bool|int
	 */
    public static function writeConfigFile($file, $config, $comment='This is a dynamic create config file.')
	{
		if(!is_file($file))
		{
			file_put_contents($file,"<?php\r\n\r\n//" . $comment . " \r\n\r\n return [];");
		}

		if(!is_file($file) || !is_array($config) || empty($config))
		{
			return false;
		}
		$arrayContent = include $file;
		$arrayContent = $arrayContent ? (array)$arrayContent : [];
		$existsKeys = array_keys($arrayContent);

		$isFixed = false;
		foreach ($config as $name => $value)
		{
			if(!in_array($name, $existsKeys))
			{
				$arrayContent[$name] = $value;
				$isFixed = true;
			}
		}

		if(!$isFixed)
		{
			return true;
		}

		$stringContent = var_export($arrayContent, true);
		$content =  "<?php\r\n\r\n//" . $comment . " \r\n\r\n return " . $stringContent . ';';
		return file_put_contents($file, $content);
	}

	/**
	 * 目录文件复制， 递归复制
	 * @param $src 原目录
	 * @param $dst 复制到的目录
	 */
	public static function recurseCopy($src,$dst)
	{
		//打开文件夹
		$dir = opendir($src);
		//创建文件
		@mkdir($dst, 0777);
		//读取文件夹内容
		while(false !== ( $file = readdir($dir)) ) {
			//跳过. 和..
			if (( $file != '.' ) && ( $file != '..' )){
				//查看文件是否是目录
				if ( is_dir($src . '/' . $file) ) {
					//如果是，递归调用本函数，继续读取
					static::recurseCopy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					//否则复制文件到目标文件夹
//					Yii::info("recurseCopy:".$src . '/' . $file . ' --> ' .$dst . '/' . $file, 'backend.recurseCopy');
					copy($src . '/' . $file, $dst . '/' . $file);
				}
			}
		}
		//关闭文件
		closedir($dir);
	}

	/**
	 * 获取后台所有控制器.
	 * @param
	 * @return mixed
	 */
	public static function getAllController()
	{
		$classfiles = array();
		$queue = array(ROOT_PATH . 'backend/controllers');
		$controllerDatas = [];
		while ($data = each($queue)) {
			$path = $data['value'];
			if (is_dir($path) && $handle = opendir($path)) {
				while ($file = readdir($handle)) {
					if ($file == '.' || $file == '..')
						continue;
					$real_path = $path . '/' . $file;
					if (is_dir($real_path))
						$queue[] = $real_path;
					else {
						$fullPath = $path . '/' . $file;
						$classfiles[] = $path . '/' . $file;
						$info = pathinfo($fullPath);
						$controllerClass = str_replace(ROOT_PATH, "", $path) . '/' . $info['filename'];
						$controllerDatas[$info['filename']] = str_replace("/", "\\", $controllerClass);
					}
				}
				closedir($handle);
			}
		}

		$rightActionData = [];
		foreach ($controllerDatas as $c) {
			if (StringHelper::startsWith($c, 'backend\controllers') == true && $c != 'backend\controllers\BaseController') {
				$controllerName = substr($c, 0, strlen($c) - 10);
				$cUrl = Inflector::camel2id(StringHelper::basename($controllerName));
				$methods = get_class_methods($c);
				//控制器
				$rightTree = ['text' => $c, 'controller_path' =>static::getPrivControllerPath($c), 'selectable' => false, 'state' => ['checked' => false], 'type' => 'r'];
				if (!$methods) {
				    continue;
                }
				foreach ($methods as $m) {
					if ($m != 'actions' && StringHelper::startsWith($m, 'action') !== false) {
						$actionName = substr($m, 6, strlen($m));
						$aUrl = Inflector::camel2id($actionName);
						$privUrl = static::getPrivUrl($c, $actionName);//权限路径
						//方法
						$actionTree = ['text' => $aUrl . "&nbsp;&nbsp;($cUrl/$aUrl)", 'c' => $cUrl, 'a' => $aUrl, 'priv_url' => $privUrl, 'selectable' => true, 'state' => ['checked' => false], 'type' => 'a'];
						if (isset($rightUrls[$cUrl . '/' . $aUrl]) == true) {
							$actionTree['state']['checked'] = true;
							$rightTree['state']['checked'] = true;
						}
						$rightTree['nodes'][] = $actionTree;
					}
				}
				$rightActionData[] = $rightTree;
			}
		}
		return $rightActionData;
	}

	/**
	 * 获取权限控制器的路径
	 * @param $controllerPath
	 * @return string
	 */
	public static function getPrivControllerPath($controllerPath)
	{
		$controllerName = substr($controllerPath, 0, strlen($controllerPath) - 10);
		$pre="";
		$prefix = substr($controllerName,stripos($controllerName,"\\")+1);
		$prefix = substr($prefix,stripos($prefix,"\\")+1);

		if(stripos($prefix,"\\")){
			$pre = substr($prefix,0,stripos($prefix,"\\")+1);
			$pre= str_replace("\\","/",$pre);
		}
		$path = $pre.Inflector::camel2id(StringHelper::basename($controllerName));
		return strtolower(trim($path,'/'));
	}

	/**
	 * 通过控制器路径和方法名，计算权限完整路径
	 * @param $controllerPath
	 * @param $action
	 * @return string
	 */
	public static function getPrivUrl($controllerPath, $action)
	{
		$controllerPath = static::getPrivControllerPath($controllerPath);
		$privUrl = trim($controllerPath, '/') . '/' . $action;
		return strtolower($privUrl);
	}

	/**
	 * 修正redis自增ID与数据库差异方法
	 * 由于保存mongodb的时候，bigint类型会转换为string类型， 所以此方法对主键为bingint类型的表无效
	 */
	public static function updateModelIdGenerator()
	{
		$classfiles = array();
		$queue = array(ROOT_PATH . 'backend/models', ROOT_PATH . 'common/models');
		$modelData = [];
		while ($data = each($queue)) {
			$path = $data['value'];
			if (is_dir($path) && $handle = opendir($path)) {
				while ($file = readdir($handle)) {
					if ($file == '.' || $file == '..' || substr($file, 0, 1) === '.')
						continue;
					$real_path = $path . '/' . $file;
					if (is_dir($real_path))
						$queue[] = $real_path;
					else {
						$fullPath = $path . '/' . $file;
						$classfiles[] = $path . '/' . $file;
						$info = pathinfo($fullPath);
						$modelClass = str_replace(ROOT_PATH, "", $path) . '/' . $info['filename'];
						if(substr($info['filename'],-5) != 'Trait')
						{
							$modelData[$info['filename']] = str_replace("/", "\\", $modelClass);
						}
					}
				}
				closedir($handle);
			}
		}

		echo '<pre>';
		foreach ($modelData as $modelName => $m) {
			if(!in_array($modelName, ['BackendUser', 'BaseModel', 'Test', 'Base', 'IDGenerator', 'LoginForm','Match']))
			{
				$fullModel = '\\' . $m;
				if(!class_exists($fullModel))
				{
					var_dump("模型 $fullModel 不存在");
					continue;
				}
				$model = new $fullModel();
				if(!method_exists($model, 'tableName'))
				{
					var_dump("模型 $fullModel 不存在方法：tableName");
					continue;
				}

				//使用自增主键
				if (count($model->primaryKey()) == 1 && strstr($model->primaryKey()[0], "id") != false && $model->primaryKey()[0] != '_id') {
					$name = $model->primaryKey()[0];

					$tableName = $model->tableName();
					//当前自增ID
					$currId = Yii::$app->redisService->getRedisCurrID($tableName);
					$currId = method_exists($fullModel, 'typecast') ? $fullModel::typecast($name, $currId) : (int)$currId;
					$isBiggerModel = $fullModel::find()->where(['>', $name, $currId])->orderBy("$name DESC")->limit(1)->one();
					if(!empty($isBiggerModel))
					{
						$newId = $isBiggerModel[$name];
						//设置自动ID
						if((int)$newId <= (int)$currId)
						{
							Yii::info("newId is not bigger than currId", 'backend.update.idgenerator');
							continue;
						}
						$ret = Yii::$app->redisService->getRedisSetID($tableName, $newId);
						$result = [
							'code' => (int)$ret,
							'table' => $tableName,
							'msg' => '旧自增ID：'. $currId .' -- 设置新自增ID为:' .$newId,
						];
						Yii::info($result, 'backend.update.idgenerator');
						var_dump($result);
					}
					else
					{
						Yii::info("table : $tableName not-thing to do!", 'backend.update.idgenerator');
					}
				}
			}

		}
	}

	/**
	 * 判断用户是否拥有该权限
	 * @param string $privUrl
	 * @return bool
	 */
	public static function hasPriv($privUrl='')
	{
		static $privList = null;
		static $allPrivList = null;
		if(Yii::$app->user->isGuest)
		{
			return false;
		}

		if(in_array(Yii::$app->user->identity->uname, ['admin']))
		{
			return true;
		}

		//需验证的权限
		$privUrl = AdminPrivService::getShowPrivUrl($privUrl);

		//所有系统已定义的权限列表
		if(is_null($allPrivList))
		{
			//获取所有的权限列表
			$allPrivList = AdminPrivService::getList();
			foreach ($allPrivList as & $allPriv)
			{
				$allPriv['priv_url'] = AdminPrivService::getShowPrivUrl($allPriv['priv_url']);
			}
			$allPrivList = CommonFun::buildRelationArray($allPrivList, 'priv_url');
		}

		//如果没有定义该权限， 则通过
		if(!array_key_exists($privUrl, $allPrivList))
		{
			return true;
		}

		//用户拥有的权限列表
		if(is_null($privList))
		{
			$privList = AdminPrivService::getAdminUserPrivList(Yii::$app->user->identity->id);
		}

		//判断是否拥有权限
		return array_key_exists($privUrl, $privList);
	}

	/**
	 * 模板渲染，输出某字段
	 * @param        $data
	 * @param string $field
	 * @return mixed|string
	 */
	public static function printEditField($data, $field='')
	{
		if(empty($data))
		{
			return '';
		}
		if(is_object($data))
		{
			return isset($data->$field) ? $data->$field : '';
		}
		if(is_array($data))
		{
			return isset($data[$field]) ? $data[$field] : '';
		}
		return '';
	}

	/**
	 * curl get
	 * @param       $url 请求地址
	 * @param array $data 发送的数据
	 * @param array $header 发送头信息
	 * @param boole $debug 是否开启调试信息
	 * @return mixed
	 */
	public static function curlGet( $url, $data = [], $header = [], $debug = false){
		try
		{
			$url .= $data ? http_build_query($data) : '';
			$ret = null;
			for ($i=0; $i< 5; $i++)
			{
				$ch = static::curlInit($url, $data, $header);
				$ret = static::curlExec($ch, $debug);
				if(!empty($ret))
				{
					break;
				}
				sleep(2);
			}
			return $ret;
		}
		catch (\Throwable $e)
		{
			return null;
		}
	}

	/**
	 * curl post
	 * @param       $url 请求地址
	 * @param array $data 发送的数据
	 * @param array $header 发送头信息
	 * @param boole $debug 是否开启调试信息
	 * @return mixed
	 */
	public static function curlPost($url, $data = [], $header = [], $debug = false){
		try
		{
			$ret = null;
			for ($i=0; $i< 5; $i++)
			{
				$ch = static::curlInit($url, $data, $header);
				curl_setopt($ch, CURLOPT_POST, true);
				$ret = static::curlExec($ch, $debug);
				if(!empty($ret))
				{
					break;
				}
				sleep(2);
			}
			return $ret;
		}
		catch (\Throwable $e)
		{
			return null;
		}
	}

	/**
	 * 初始化curl请求
	 * @param       $url
	 * @param       $data
	 * @param array $header
	 * @return resource
	 */
	private static function curlInit($url, $data, $header = []){
		ini_set("memory_limit", "1024M");
		$ch = curl_init();
		$header = $header ? $header: static::$header;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, 'gzip');


//		curl_setopt($ch, CURLOPT_HEADER, true);       //返回头信息
//		curl_setopt($ch, CURLOPT_VERBOSE, true);

		if (strpos($url, 'https://') !== false) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSLVERSION, true);
		}

		if ($header){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		if ($data){
			curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
		}

		return $ch;
	}

	/**
	 * 执行curl请求， 返回结果， 显示错误
	 * @param      $ch
	 * @param bool $debug
	 * @return mixed
	 */
	protected  static function curlExec($ch, $debug=false){

		$response = curl_exec($ch);

		//调试或错误信息
		$debugInfo = [];
		if( curl_errno( $ch) ) {
			$debugInfo['error'] = curl_error($ch);
		}

		if($debug)
		{
			$debugInfo['info'] = curl_getinfo($ch);
		}
		curl_close($ch);

		if(!empty($debugInfo))
		{
			foreach ($debugInfo as $k => $item) {
				if (is_array($item)){
					print_r($item);
				}else{
					echo "$k: " . $item . PHP_EOL;
				}
			}
		}
		return $response;
	}

    /**
     * 根据关联数组获取渲染的select列表
     * @param $list  key value 列表
     * @param $querys 当前搜索参数
     * @param string $fieldName 搜索的字段
     * @return array
     */
	public static function getRenderSelectListFromKeyValue($list, $querys, $fieldName='')
	{
	    $result = [];
        foreach ($list as $key => $value) {
            $option = [];
            $option['option'] = $key;
            $option['name'] = $value;
            $option['select'] = "";
            if (isset($querys[$fieldName]) && $querys[$fieldName] == $key) {
                $option['select'] = "selected";
            }
            $result[] = $option;
        }
        return $result;
	}

	public static function checkIdnumber($idcard)
    {
        if(empty($idcard)){
            return false;
        }

        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $iSum = 0;
        $idCardLength = strlen($idcard);
        //长度验证
        if(!preg_match('/^\d{17}(\d|x)$/i',$idcard) and!preg_match('/^\d{15}$/i',$idcard))
        {
            return false;
        }
        //地区验证
        if(!array_key_exists(intval(substr($idcard,0,2)),$City))
        {
            return false;
        }
        // 15位身份证验证生日，转换为18位
        if ($idCardLength == 15)
        {
            $sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
            echo $sBirthday;die;
            $d = new \DateTime($sBirthday);
            $dd = $d->format('Y-m-d');

            if($sBirthday != $dd){
                return false;
            }
            $idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
            $Bit18 = self::getVerifyBit($idcard);//算出第18位校验码
            $idcard = $idcard.$Bit18;
        }
        // 判断是否大于2078年，小于1900年
        $year = substr($idcard,6,4);
        if ($year<1900 || $year>2078 )
        {
            return false;
        }

        //18位身份证处理
        $sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
        $d = new \DateTime($sBirthday);
        $dd = $d->format('Y-m-d');

        if($sBirthday != $dd){
            return false;
        }
        //身份证编码规范验证
        $idcard_base = substr($idcard,0,17);

        if(strtoupper(substr($idcard,17,1)) != self::getVerifyBit($idcard_base))
        {

            return false;
        }
        return true;
    }
    // 计算身份证校验码，根据国家标准GB 11643-1999
    public static function getVerifyBit($idcard_base){
        if(strlen($idcard_base) != 17)
        {
            return false;
        }

        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;

        for ($i = 0; $i < strlen($idcard_base); $i++)
        {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }

        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];

        return $verify_number;
    }
    // 根据vip规则和经验值判断vip等级
    public static function getVipInfo($rule,$experience=0){
        if($experience<=0){
            return 0;
        }
        $len=count($rule);
        if($experience>=(int)$rule[$len-1]['vip_experience']){
            return (int)$rule[$len-1]['vip_rank'];
        }
        foreach($rule as $k => $v){
            if($k==0){
                if($experience<(int)($v['vip_experience']))
                    return (int)$v['vip_rank']-1;
            }else{
                if($experience >= (int)$rule[$k-1]['vip_experience'] && $experience < (int)$v['vip_experience'] ){
                    return $v['vip_rank']-1;
                }
            }
        }
    }
}
