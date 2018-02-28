<?php

namespace backend\services;

use backend\models\AdminRole;
use backend\models\AdminUser;
use backend\models\BackendUser;
use backend\models\AdminAnnounce;
use common\services\MailService;
use common\utils\CommonFun;
use common\utils\DataPackager;
use RabbitMqService;
use robot\Data;
use Yii;
use yii\web\Request;
use backend\models\AdminGroup;

//require_once(ROOT_PATH . '/common/services/RabbitMqService.php');
/*为了单元测试控制器的报错*/
require_once(dirname(dirname(__DIR__)) . '/common/services/RabbitMqService.php');

class SiteService
{
    public function __construct()
    {
    }

    /**
     * 首页数据获取
     *
     * @return array
     */
    public static function getIndexData()
    {
        $anounce = array();
        $type = 0;
//        $menus = Yii::$app->user->identity->getSystemMenus();
        $sysInfo = static::getSystemInfo();
        if (YII_ENV_DEV) {
            $anounce = static::getAnouceContent();

            $isSysRole = AdminUserService::getUserGroupCheck(AdminGroup::GROUP_SYSTEM_ADMIN);
//            $isAgent = AdminUserService::getUserGroupCheck(AdminGroup::GROUP_AGENT);（BIGame不区分带来注释）
            $isAgent = 0;
            if ($isSysRole == 1) {
                $type = 1;
            } elseif ($isAgent == 1) {
                $type = 2;
            } else {
                $type = 3;
            }
        }
        return [
//            'system_menus' => $menus,
            'url' => '',// Yii::$app->params['backendSwoole'],
            'sysInfo' => $sysInfo,
            'anounce' => $anounce,
            'type' => $type,
        ];
    }

    /**
     * 获取系统运行信息
     *
     * @return array
     */
    private static function getSystemInfo()
    {
        $sysInfo = [
            ['name' => '操作系统', 'value' => php_uname('s')],
            //'value'=>php_uname('s').' '.php_uname('r').' '.php_uname('v')],
            ['name' => 'PHP版本', 'value' => phpversion()],
            ['name' => 'Yii版本', 'value' => Yii::getVersion()],
            ['name' => 'DataCenter版本号', 'value' => static::getDataCenterVersion()],
            ['name' => '版本环境', 'value' => YII_ENV_DEV],
        ];
        if (YII_ENV_DEV) {
            try {
//                $mq = new RabbitMqService(Yii::$app->params["rabbitmq"]);
                $redis = new \Redis();
                $redis->connect(Yii::$app->components["redis"]["hostname"], Yii::$app->components["redis"]["port"]);
                $sessionRedis = new \Redis();
                $sessionRedis->connect(Yii::$app->components["session"]["redis"]["hostname"],
                    Yii::$app->components["session"]["redis"]["port"]);


                $sysInfo[] = ['name' => 'redis(已连上)', 'value' => json_encode(Yii::$app->components["redis"])];
                $sysInfo[] = ['name' => 'session(已连上)', 'value' => json_encode(Yii::$app->components["session"])];

                $sysInfo[] = [
                    'name' => 'db_dm_game',
                    'value' => json_encode(Yii::$app->components["db_dm_game"]["dsn"])
                ];
                $sysInfo[] = [
                    'name' => 'db_dm_admin',
                    'value' => json_encode(Yii::$app->components["db_dm_admin"]["dsn"])
                ];


            } catch (\Exception $e) {
                echo "connection error: " . $e->getTraceAsString();
            }
        }
        return $sysInfo;
    }

    /**
     * 获取数据库版本
     * @return mixed
     */
    private function getDbVersion()
    {
        $driverName = Yii::$app->db->driverName;
        if (strpos($driverName, 'mysql') !== false) {
            $v = Yii::$app->db->createCommand('SELECT VERSION() AS v')->queryOne();
            $driverName = $driverName . '_' . $v['v'];
        }
        return $driverName;
    }


    /**
     * 返回BiGame版本号
     */
    public static function getDataCenterVersion()
    {
        $data = "";
        $filePath = "./dist/text";
        $DataCenterPath = scandir($filePath);
        if (empty($DataCenterPath)) {
            return $data;
        } else {
            if (in_array('DataCenterVersion.txt', $DataCenterPath)) {
                $content = file_get_contents($filePath . '/DataCenterVersion.txt');
                $data = $content;
            }
        }
        return empty($data)?"1.0.0":$data;
    }

    /**
     * 获取首页公共
     *
     * @return array
     */
    private static function getAnouceContent()
    {

        $data = AdminAnnounce::find()
            ->where(['or', ['type' => AdminAnnounce::ADMIN_ANNOUNCE_ADMIN], ['type' => AdminAnnounce::ADMIN_ANNOUNCE_AGENT]])
            ->asArray()->all();

        if (empty($data)){
            return [];
        }

        foreach($data  as $key =>$value){
            if(isset($value['type']) && $value['type']==AdminAnnounce::ADMIN_ANNOUNCE_AGENT){
                $data['agent']['title'] = $value['title'];
                $data['agent']['content']= $value['content'];
                $data['agent']['time'] = $value['create_date'];
            }
            if(isset($value['type']) && $value['type'] == AdminAnnounce::ADMIN_ANNOUNCE_ADMIN){
                $data['common']['title'] = $value['title'];
                $data['common']['content']= $value['content'];
                $data['common']['time'] = $value['create_date'];
            }

        }
        return $data;

    }


    /**
     * 公告编辑
     *
     * @param $type
     * @param $time
     * @param $content
     * @param $title
     * @return string
     */
    public static function saveNotice($type, $time, $content, $title)
    {
        $type = $type=='anouncecommon'?AdminAnnounce::ADMIN_ANNOUNCE_ADMIN:AdminAnnounce::ADMIN_ANNOUNCE_AGENT;
        $data = AdminAnnounce::deleteAll(['type'=>(int)$type]);
        $model = new AdminAnnounce();
        $model->type = $type;
        $model->title = $title;
        $model->content = $content;
        $model->create_date = $time;
        if($model->validate() && $model->save()){
            return DataPackager::pack('',0,'保存成功');
        }else{
            return DataPackager::pack('',1,'保存失败');
        }
    }


    /**
     * @param $password 用户传入密码
     * @param $encrypt 解密秘钥 csrf值前16位
     * @return bool|string 解密后密码
     */
    private static function decryptPassword($password, $encrypt)
    {
        if (empty($password) || empty($encrypt)) {
            return false;
        }
        $iv = substr(\Yii::$app->params['IV'], 0, 16);
//        $password = urldecode($password);
        $password = trim(@mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encrypt, base64_decode($password), MCRYPT_MODE_CBC, $iv));
        return $password;
    }

    /**
     * 登录验证
     *
     * @param $postData
     * @return string
     */
    public static function login($postData)
    {
        if ($postData && isset($postData['password']) && isset($postData['username'])) {
            $username = $postData['username'];

            //获取csrf值前16位作为解密需要的encrypt
            $encrypt = strtolower(substr($postData['_csrf'], 0, 16));
            //对用户密码进行解密 获得解密后的用户密码
            $password = static::decryptPassword($postData['password'], $encrypt);

            $rememberMe = (isset($postData['remember']) && $postData['remember'] == "y") ? true : false;
            if (AdminUser::login($username, $password, $rememberMe) == true) {
                AdminUser::updateAll(
                    ['last_ip' => CommonFun::getClientIp()],
                    ['uname' => $username]
                );
                $user = AdminUser::findOne(['id' => Yii::$app->user->identity->id]);
                $user->is_online = 1;
                $user->save();

                $token =  \common\services\UserService::newToken($user->id);
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie(['name' => 'userToken','value' => $token]));


                return DataPackager::pack('', 0, '登录成功');
            } else {
                return DataPackager::pack('', 2, '登录失败');
            }
        } else {
            return DataPackager::pack('', 2, '登录失败');
        }
    }

    /**
     * 重置密码
     *
     * @param $old_password
     * @param $new_password
     * @param $confirm_password
     * @return string
     */
    public static function pswSave($old_password, $new_password, $confirm_password)
    {
        if (empty($old_password) == true) {
            return DataPackager::pack(['old_password' => '旧密码不能为空'], 2, '旧密码不能为空');
        }
        if (empty($new_password) == true) {
            return DataPackager::pack(['new_password' => '新密码不能为空'], 2, '新密码不能为空');
        }
        if (strlen($new_password) < 6 || strlen($new_password) > 30) {
            return DataPackager::pack(['new_password' => '新密码长度为6~30字符'], 2, '新密码长度为6~30字符');
        }
        if (empty($confirm_password) == true) {
            return DataPackager::pack(['confirm_password' => '确认密码不能为空'], 2, '确认密码不能为空');
        }
        if ($new_password != $confirm_password) {
            return DataPackager::pack(['confirm_password' => '两次新密码不相同'], 2, '两次新密码不相同');
        }
        $user = AdminUser::findByUsername(Yii::$app->user->identity->uname);
        if (BackendUser::validatePassword($user, $old_password) == true) {
            $user->password = Yii::$app->security->generatePasswordHash($new_password);
            $user->save();
            if ($user->errors) {
                $errorsArray = [];
                foreach ($user->errors as $key => $val) {
                    $errorsArray[$key] = $val[0];
                }
                return DataPackager::pack($errorsArray, 2, '');
            } else {
                return DataPackager::pack('', 0, '保存成功');
            }
        } else {
            return DataPackager::pack(['old_password' => '旧密码不正确'], 2, '旧密码不正确');
        }
    }

    /**
     * 找回密码验证和发送邮件
     *
     * @param $username
     * @param $email
     * @return string
     */
    public static function resetCheckSend($username, $email)
    {
        if (empty($username) || empty($email)) {
            return DataPackager::pack('', 1, '数据不能为空');
        }
        $result = AdminUser::find()->where(['uname' => $username, 'email' => $email])->one();
        if (empty($result)) {
            return DataPackager::pack('', 2, '用户与邮箱不匹配');
        } else {
            $url = Yii::$app->request->hostInfo . Yii::$app->user->returnUrl;
            $code = md5($result->password);
            $url .= "site/updatereset.html?uid=" . $result->id . "&code=$code";
            MailService::sendResetPwd($result->id, '找回密码', $url);
            return DataPackager::pack('', 0, '邮箱已发送,请打开邮箱点击链接进行重置');
        }
    }

    /**
     *  保存重置密码
     *
     * @param $resetData post数据
     * @return string
     */
    public static function saveReset($resetData)
    {
        if (!isset($resetData['uid']) || !isset($resetData['code'])) {
            return DataPackager::pack('', 1, '链接已失效或已经重置');
        }
        $uid = $resetData['uid'];
        $code = $resetData['code'];
        $adminUser = AdminUser::find()->where(['id' => (string)$uid])->one();
        $realcode = md5($adminUser->password);
        if ($code != $realcode) {
            return DataPackager::pack('', 1, '链接已失效或已经重置');
        }
        $password = empty($resetData['password']) ? "" : trim($resetData['password']);
        $passwordconfirm = empty($resetData['passwordconfirm']) ? "" : trim($resetData['passwordconfirm']);
        if (strlen($password) < 6 || strlen($password) > 30) {
            return DataPackager::pack('', 1, '新密码长度为6~30字符');
        }
        if (strlen($passwordconfirm) < 6 || strlen($passwordconfirm) > 30) {
            return DataPackager::pack('', 1, '确认密码设置有误，请保持和新密码一致');
        }
        if ($password != $passwordconfirm) {
            return DataPackager::pack('', 2, '两次输入密码不一致');
        }
        $adminUser->password = Yii::$app->security->generatePasswordHash($password);
        if ($adminUser->save()) {
            return DataPackager::pack('', 0, '保存成功');
        } else {
            return DataPackager::pack('', 3, '保存失败');
        }
    }
}

?>