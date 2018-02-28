<?php

namespace backend\controllers\rights;

use backend\models\AdminRightUrl;
use backend\models\AdminRole;
use backend\models\AdminUserRole;
use backend\services\AdminGroupService;
use common\utils\CommonFun;
use Yii;
use yii\data\Pagination;
use backend\models\AdminUser;
use yii\web\NotFoundHttpException;
use backend\services\AdminUserService;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;

/**
 * 管理员用户GRUD控制器.
 */
class AdminUserController extends BaseController
{
    public $layout = "lte_main";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'], // 控制请求通过 get才能访问
                    'view' => ['post'],
                    'create' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }
    /**
     * 获取管理用户数据列表.
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        //获取每页显示条数
        $per_page = $this->get('per_page', 50);
        $querys = $this->get('query', []);
        $orderby = $this->get('orderby', 'create_date DESC');
        $models = AdminUserService::getAdminUserList($querys, $per_page, $orderby);
        return $this->render('index',$models);
    }
    /**
     * 获取管理员用户属性.
     * @param string $id
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->post('id');
        return AdminUserService::getAdminUserView($id);
    }
    /**
     * 新增管理员用户.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $postData = $this->post();
        return AdminUserService::getAdminUserCreate($postData);
    }
    /**
     * 修改管理员用户属性.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $postData = $this->post();
        $id = $this->post('id');

        return AdminUserService::getAdminUserUpdate($id, $postData);
    }

}
