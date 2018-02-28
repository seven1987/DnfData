<?php

namespace backend\controllers\logs;

use backend\services\UserLoginLogService;
use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;

/**
 * UserLoginLogController implements the CRUD actions for UserLoginLog model.
 */
class UserLoginLogController extends BaseController
{
    public $layout = "lte_main";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'], // 控制请求通过 post 才能访问
                ],
            ],
        ];
    }

    /**
     * 用户登录日志列表
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        $per_page = $this->get('per_page', 50);
        $querys = $this->get('query', []);
        $orderby = $this->get('orderby', '_id DESC');
        return $this->render('index', UserLoginLogService::getIndexData($per_page,$querys,$orderby));
    }
}
