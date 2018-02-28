<?php

namespace backend\controllers;

use backend\models\AdminUser;
use backend\models\AdminUserRole;
use backend\services\MessageService;
use backend\services\SiteService;
use common\utils\DataPackager;
use Yii;

/**
 * 站点入口控制器
 */
class SiteController extends BaseController
{
    public $layout = "lte_main";

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 展现后台首页.
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        if (Yii::$app->user->isGuest) {
            echo "guest";
            $this->layout = "lte_main_login";
            return $this->render('login',[
                'iv' => \Yii::$app->params['IV'],
            ]);
        } else {
            return $this->render('index', SiteService::getIndexData());
        }
    }

    /**
     * 后台登陆处理.
     * @return mixed
     */
    public function actionLogin()
    {
        $postData = $this->post();
        return SiteService::login($postData);
    }

    /**
     * 后台登出处理.
     * @return mixed
     */
    public function actionLogout()
    {
        if (isset(Yii::$app->user->identity->id)) {
            $user = AdminUser::findOne(['id' => Yii::$app->user->identity->id]);
            if(!$user)
			{
				return $this->goHome();
			}
            $user->is_online = 0;
            $user->save();
            Yii::$app->user->identity->clearUserSession();
            Yii::$app->user->logout();
            $cookies = Yii::$app->response->cookies;
            $cookies->remove('userToken');
        }
        return $this->goHome();
    }

    /**
     * 修改密码页面呈现.
     * @return mixed
     */
    public function actionPsw()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = "lte_main_login";
            return $this->render('login');
        } else {
            parent::initMenus();
            return $this->render('psw', [
                'user_role' => Yii::$app->user->identity->uname,
            ]);
        }
    }

    /**
     * 保存新密码
     * @return mixed
     */
    public function actionPswSave()
    {

        if (Yii::$app->user->isGuest) {
            return DataPackager::pack('', 2, '请先登录');
        }
        $postDate = $this->post();
        $old_password = isset($postDate['old_password']) ? (string)$postDate['old_password'] : '';
        $new_password = isset($postDate['new_password']) ? (string)$postDate['new_password'] : '';
        $confirm_password = isset($postDate['confirm_password']) ? (string)$postDate['confirm_password'] : '';
        return SiteService::pswSave($old_password, $new_password, $confirm_password);
    }

    public function actionMessage()
    {
        if (Yii::$app->request->isPost) {
            $data = MessageService::getAgentMessages();
            return json_encode($data);
        }
    }

    /**忘记密码页面
     * @return string
     */
    public function actionForgetpwd()
    {
        $this->layout = "lte_main_login";
        return $this->render('forgetpwd');
    }

    /**
     * 发送找回面验证
     */
    public function actionResetpwd()
    {
        $resetData = $this->post();
        $username = empty($resetData['username']) ? "" : trim($resetData['username']);
        $email = empty($resetData['email']) ? "" : trim($resetData['email']);
        return SiteService::resetCheckSend($username, $email);
    }

    public function actionLoginview()
    {
        $this->layout = "lte_main_login";
        return $this->render('login');
    }

    /**重置密码页面显示
     * @return string
     */
    public function actionUpdatereset()
    {
        $this->layout = "lte_main_login";
        $getData = $this->get();
        $uid = isset($getData['uid']) ? $getData['uid'] : "";
        $code = isset($getData['code']) ? $getData['code'] : "";
        if ($uid == "" || $code == "") {
            return $this->render('login');
        }
        $adminUser = AdminUser::find()->where(['id' => (string)$uid])->one();
        if (empty($adminUser)) {
            return $this->render('login');
        }
        $realcode = md5($adminUser->password);
        if ($code != $realcode) {
            return $this->render('login');
        }
        $this->layout = "lte_main_login";
        return $this->render('resetpwd', [
            'uid' => $uid,
            'code' => $code,
        ]);
    }

    /**
     * 重置密码保存
     */
    public function actionSaveReset()
    {
        return SiteService::saveReset($this->post());
    }

    //保存写入的公告内容
    public function actionSaveNotice()
    {
        $postdata = Yii::$app->request->post();
        $type = empty($postdata['contenttype']) ? "" : $postdata['contenttype'];
        $time = empty($postdata['updatetime']) ? "" : $postdata['updatetime'];
        $content = empty($postdata['content']) ? "" : $postdata['content'];
        $title = empty($postdata['title']) ? "" : $postdata['title'];
        return SiteService::saveNotice($type, $time, $content, $title);
    }


}
