<?php

namespace backend\controllers;

use backend\services\AdminLogsService;
use backend\services\AdminPrivService;
use backend\services\AdminUserService;
use backend\services\SiteService;
use common\utils\DataPackager;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\response;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;
use backend\models\AdminUser;
use backend\models\AdminLog;
use common\utils\CommonFun;
use backend\services\AdminWhiteListService;

/**
 * 后台控制器基类.
 */
class BaseController extends Controller
{
    public $system_menus;
    public $system_rights;
    public $uploadToken;
    /**
     * 是否代理商角色
     * @var int
     */
    public $is_agent_role = 0;

    public function initMenus($refererUrl=null)
    {
        if (Yii::$app->user->identity) {
            //系统原有权限菜单获取
//			$this->system_menus = Yii::$app->user->identity->getSystemMenus();
//			$this->system_rights = Yii::$app->user->identity->getSystemRights();

            //权限新版
            //登录后，获得显示的菜单和权限信息
            $adminPriv = AdminPrivService::getAdminUserPrivList(Yii::$app->user->identity->id);
            $adminModule = AdminPrivService::getAdminUserModuleMenu($adminPriv);
            $adminModule = AdminPrivService::formtAdminUserModuleMenu($adminModule,$refererUrl);
//			CommonFun::dump($adminModule);
            $this->system_rights = $adminPriv;
            $this->system_menus = $adminModule;
            $cookies = Yii::$app->request->cookies;
            $token = $cookies->get('userToken');
            if (empty($token)) {
                Yii::$app->user->logout();
                $this->redirect(Url::toRoute('site/index'));
                return false;
            }
            $this->uploadToken = $token;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        //登录状态进行验证
        if (!Yii::$app->user->isGuest) {

            $this->_loginInit();
            $this->checkUserStatus();
        }

//    	@TODO 性能测试直接返回 true，上线要改回来
//        return true;
        if (parent::beforeAction($action)) {
            //旧权限验证方法
//			return $this->verifyPermission($action);

            //新权限验证方法
            if (!$this->verifyPrivileges()) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 验证管理权限 (新权限控制) 2017-06-01
     * @param $privList 权限列表
     * @return bool
     */
    private function verifyPrivileges()
    {
        //当前访问路径
        $route = AdminPrivService::getShowPrivUrl($this->route);
        //没有登录
        if (Yii::$app->user->isGuest) {
            $guestAllowUrl = [
                'site/logout',
                'site/index',
                'site/login',
                'site/forgetpwd',
                'site/resetpwd',
                'site/updatereset',
                'site/savereset'
            ];
            if (in_array($route, $guestAllowUrl) == false) {
                $this->redirect(Url::toRoute('site/index'));
                return false;
            }
        } else {
            //获取管理权限列表
            $privList = AdminPrivService::getAdminUserPrivList(Yii::$app->user->identity->id);

            //获取所有的权限列表
            $allPrivList = AdminPrivService::getList();
            foreach ($allPrivList as & $allPriv) {
                $allPriv['priv_url'] = AdminPrivService::getShowPrivUrl($allPriv['priv_url']);
            }
            $allPrivList = CommonFun::buildRelationArray($allPrivList, 'priv_url');

            //白名单验证
//            if (!AdminWhiteListService::checkIP()) {
//                header("Content-type: text/html; charset=utf-8");
//                exit('非白名单用户，请检查权限是否拥有权限或已过期');
//            }

            //登录以后允许访问的列表， 不验证权限
            $loginAllowUrl = ['site/index', 'site/logout', 'site/psw', 'site/psw-save', 'site/logout',];

            //权限验证条件
            if (
                //拥有所有权限
                $privList == 'all'
                //登录以后不验证的方法
                || in_array($route, $loginAllowUrl)
                //权限验证通过,权限统一去除横杠，并转为小写后闲置
                || array_key_exists(AdminPrivService::getShowPrivUrl($route), $privList)
                //只验证已经添加在权限列表的权限， 由于有的控制权限是不需要
                || !array_key_exists(AdminPrivService::getShowPrivUrl($route), $allPrivList)
            ) {
                //验证成功， 记录日志
                //验证通过，记录管理员访问日志
                $noLogUrl = ['logs/systemlog/index', 'logs/adminlogs/index', 'logs/userloginlog/index'];
                if (!in_array($route, $noLogUrl)) {
                    $privInfo = isset($privList[$route]) ? $privList[$route] : [];//兼容管理员没有权限信息情况
                    $logData = [
                        'module_name' => isset($privInfo['module_name']) ? $privInfo['module_name'] : '',
                        'menu_name' => isset($privInfo['menu_name']) ? $privInfo['menu_name'] : '',
                        'priv_name' => isset($privInfo['priv_name']) ? $privInfo['priv_name'] : '',
                        'priv_url' => isset($privInfo['priv_url']) ? $privInfo['priv_url'] : $route,
                        'client_ip' => CommonFun::getClientIp(),
                        'request_data' => json_encode([$this->get(), $this->post()]),
                        'create_user' => Yii::$app->user->identity->uname,
                        'create_date' => date("Y-m-d H:i:s"),

                    ];
                    AdminLogsService::addLog($logData);
                }
                return true;
            } else {
                //权限验证失败
                $this->deny();
                return false;
            }
        }
        return true;
    }

    /**
     * 系统禁止访问返回信息， 区分同步和异步请求
     * @param string $msg
     */
    protected function deny($msg = '没有权限访问。')
    {
        if (Yii::$app->request->isAjax) {
            exit(DataPackager::pack('', 1, $msg));
        }
        $refererUrl = Yii::$app->request->referrer ? Yii::$app->request->referrer : Yii::$app->request->baseUrl;
        header("Content-type: text/html; charset=utf-8");
        $content = '<p>' . $msg . '</p>';
        $content .= '<script>setTimeout(function(){location.href = "' . $refererUrl . '"}, 3000)</script>';
        exit($content);
    }

    /**
     * 判断当前用户信息是否正确，[用户名，密码]
     *
     * @return bool
     */
    private function checkUserStatus()
    {
        $sessionUser = Yii::$app->session['user_info'];
        $user = AdminUser::findOne(['id' => Yii::$app->user->identity->id]);
        if ($user->uname !== $sessionUser['uname']) {
            Yii::$app->user->logout();
            $this->redirect(Url::toRoute('site/index'));
            return false;
        }
        if ($user->password !== $sessionUser['password']) {
            Yii::$app->user->logout();
            $this->redirect(Url::toRoute('site/index'));
            return false;
        }

    }

    /**
     * 校验url 权限 (旧权限控制)
     * @param action $action
     * @return mixed
     */
    private function verifyPermission($action)
    {
        $route = $this->route;

        // 检查是否已经登录
        if (Yii::$app->user->isGuest) {
            $allowUrl = [
                'site/logout',
                'site/index',
                'site/login',
                'site/forgetpwd',
                'site/resetpwd',
                'site/updatereset',
                'site/savereset'
            ];
            if (in_array($route, $allowUrl) == false) {
                $this->redirect(Url::toRoute('site/index'));
                return false;
            }
        } else {
            $system_rights = Yii::$app->user->identity->getSystemRights();

            $loginAllowUrl = ['site/index', 'site/logout', 'site/psw', 'site/psw-save', 'site/logout'];

            //判断用户所在的ip段
//              if(!AdminWhiteListService::checkIP()){
//                  exit('非白名单用户，请检查权限是否拥有权限或已过期');
//              }

            if (in_array($route, $loginAllowUrl) == false && !Yii::$app->request->isAjax) {
                if ((empty($system_rights) == true || empty($system_rights[$route]) == true)) {
                    header("Content-type: text/html; charset=utf-8");
                    exit('没有权限访问, 你已被记录黑名单。.');
                }
                $rights = $system_rights[$route];
                if (!in_array($route, ['system-log/index', 'admin-log/index'])) {
                    $systemLog = new AdminLog();
                    $systemLog->url = $route;
                    $systemLog->controller_id = $action->controller->id;
                    $systemLog->action_id = $action->id;
                    $systemLog->module_name = $rights['module_name'];
                    $systemLog->func_name = $rights['menu_name'];
                    $systemLog->right_name = $rights['right_name'];
                    $systemLog->create_date = date('Y-m-d H:i:s');
                    $systemLog->create_user = Yii::$app->user->identity->uname;
                    $systemLog->client_ip = CommonFun::getClientIp();
                    $systemLog->save();
                }
            }

        }
        return true;
    }

    /**
     * 登录后公共内容设置
     */
    protected function _loginInit()
    {
        //判断是否代理商角色
//        $isAgentRole = AdminUserService::getIsAgentRight(Yii::$app->user->identity->id);
//        $this->is_agent_role = $isAgentRole ? 1 : 0;
          $this->is_agent_role = 0;//BIgame 不区分代理
    }

    protected function getAllController()
    {
        return CommonFun::getAllController();
    }

    /**
     * 返回特定格式的json数据到客户端
     * 成功请求响应
     * @param int $ret 错误码
     * @param string $msg 错误信息
     * @param json $data 数据
     */
    protected function return_response($ret = 0, $msg = '', $data = array())
    {

        echo json_encode(array('ret' => $ret, 'msg' => $msg, 'data' => $data));
        exit;
    }

    /**
     * @param  array $data
     * 成功响应
     */
    protected function return_success($data)
    {

        $this->return_response(1, 'ok', $data);
    }

    /**
     * @param  string $msg
     * 失败响应
     */
    protected function return_error($msg)
    {

        $this->return_response(-1, $msg);
    }

    /**
     * overwrite system get
     *
     * @param string|null $name
     * @param string|null $defaultValue
     * @return string|array
     */
    protected function get($name = null, $defaultValue = null)
    {
        return \common\utils\CommonFun::inputEncode(Yii::$app->request->get($name, $defaultValue));
    }

    /**
     * overwrite system post
     *
     * @param string|null $name
     * @param string|null $defaultValue
     * @return string|array
     */
    protected function post($name = null, $defaultValue = null)
    {
        return \common\utils\CommonFun::inputEncode(\common\utils\CommonFun::arrayValueToString(Yii::$app->request->post($name,
            $defaultValue)));
    }


}

?>