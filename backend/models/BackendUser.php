<?php
namespace backend\models;

use backend\services\AdminService;
use common\services\UserLoginLogService;
use common\utils\CommonFun;
use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use backend\services\SystemModuleService;
use backend\services\AdminModuleService;

/**
 * 后台用户模型.
 */
class BackendUser extends \backend\models\BaseModel implements IdentityInterface
{
    use AdminUserTrait;

    const STATUS_DELETED = 0;

    const STATUS_ACTIVE = 10;

    public $_menus;

    public $_rightUrls;

    /**
     * 管理员用户登录.
     * @param string $username
     * @param string $password
     * @param int $rememberMe
     * @return boolean whether the user is logged in successfully
     */
    public static function login($username, $password, $rememberMe)
    {
        $user = AdminUser::findByUsername($username);
        if (self::validatePassword($user, $password) == true) {
            if (Yii::$app->user->login($user, $rememberMe ? 3600 * 24 * 30 : 0) == true) {
//                UserLoginLogService::addLog($user->user_id); //报错user_id 非属性
//                UserLoginLogService::addLog($user->id);
//                $user->lazyInitMenu();
               // 处理session 用户信息存session
               Yii::$app->session['user_info'] = ['uname'=>$user->uname, 'password'=>$user->password];
                return true;
            }
        }
        return false;
    }

    /**
     * 根据 username 和 status 查找用户
     *
     * @param string $username 用户名
     * @return static|null  用户有效性
     */
    public static function findByUsername($username)
    {
        return AdminUser::findOne([
            'uname' => $username,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * 校验用户密码
     * @param string $user
     * @param string $password
     * @return boolean
     */
    public static function validatePassword($user, $password)
    {
        return ($user != null && Yii::$app->getSecurity()->validatePassword($password, $user->password));
    }

    public function lazyInitMenu()
    {
        if ($this->_menus == null) {
            $all = AdminService::getUserMenuList($this->id);
            $this->_menus = $all['modules'];
            $this->_rightUrls = $all['urls'];
        }
    }

    /**
     * 初始化用户模块
     * @return array
     */
    public function initUserModuleList()
    {
        $this->lazyInitMenu();
        return $this->_menus;
    }

    /**
     * 获取用户可用选项
     * @param int $userId
     * @return array
     */
    public function initUserUrls($userId = 0)
    {
        $this->lazyInitMenu();
        return $this->_rightUrls;
    }

    /**
     * 根据 id 和 status 查找用户
     * @param int|string $id 用户id
     * @return null|static  用户有效性
     */
    public static function findIdentity($id)
    {
        return self::findOne([
            'id' => $id,
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * 根据token查找管理员用户
     * @param string $token
     * @param int $type
     * @return backendUser
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 获取当前用户 id
     *
     * @see \yii\web\IdentityInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * cookie登录需要实现
     *
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * cookie登录需要实现
     *
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 获取当前用户菜单
     */
    public function getSystemMenus()
    {
        return $this->initUserModuleList();
//        if(isset(Yii::$app->session['system_menus_'.$this->id]) == false){
//            $this->initUserModuleList();
//        }
//        return Yii::$app->session['system_menus_'.$this->id];
    }

    /**
     * 获取当前用户权限
     */
    public function getSystemRights()
    {
        return $this->initUserUrls();
//        if(isset(Yii::$app->session['system_rights_'.$this->id]) == false){
//            $this->initUserUrls();
//        }
//        return Yii::$app->session['system_rights_'.$this->id];
    }

    /**
     * 清除 session 数据
     */
    public function clearUserSession()
    {
        Yii::$app->session['system_menus_' . $this->id] = null;
        Yii::$app->session['system_rights_' . $this->id] = null;
    }
}

?>