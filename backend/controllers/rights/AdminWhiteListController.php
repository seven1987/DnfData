<?php

namespace backend\controllers\rights;

use backend\controllers\BaseController;
use backend\models\AdminWhiteList;
use yii\filters\VerbFilter;
use backend\services\AdminWhiteListService;
use common\utils\CommonFun;

class AdminWhiteListController extends BaseController
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
     * 白名单列表加载
     * @return string
     */
    public function actionIndex(){
        parent::initMenus();

        //获取每页显示条数
        $perPage = $this->get('per_page', 50);
        $querys = $this->get('query', []);
        $orderBy = $this->get('orderby', 'create_time desc');
        //获取白名单页面数据
        $models = AdminWhiteListService::getAdminWhiteList($querys, $perPage, $orderBy);
        return $this->render('index',$models );
    }

    /**
     * Displays a single Zone model.
     * @param integer $zone_id
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->post("id");
        return AdminWhiteListService::getAdminWhiteListView($id);
    }

    /**
     * Creates a new Zone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $data = $this->post();
        return AdminWhiteListService::getAdminWhiteListCreate($data);
    }

    /**
     * Updates an existing Zone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $zone_id
     * @return mixed
     */
    public function actionUpdate()
    {
        $id = $this->post('white_list_id');
        $data = $this->post();
        return AdminWhiteListService::getAdminWhiteListUpdate($id, $data);
    }


}