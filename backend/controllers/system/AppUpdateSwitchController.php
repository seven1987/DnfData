<?php

namespace backend\controllers\system;

use backend\controllers\BaseController;
use backend\services\AppUpdateSwitchService;
use yii\filters\VerbFilter;

/**
 * APP设置GRUID控制器.
 */
class AppUpdateSwitchController extends BaseController
{
    public $layout = "lte_main";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'], // 控制请求通过 post 才能访问
                    'string-bet' => ['get'],
                    'bet-string-set' => ['post'],
                    'string-bet-set' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 获取app设置列表.
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        $perPage = $this->get('per_page', 50);
        $allQuery = $this->get('query', []);
        $showOrder = $this->get('orderby', 'update_id DESC');
        return $this->render('index', AppUpdateSwitchService::getIndexData($perPage, $allQuery, $showOrder));
    }

    /**
     * 获取盘口属性.
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->post('id');
        return AppUpdateSwitchService::getViewInfo($id);
    }

    /**
     * 保存app迭包后台开关.
     * @return mixed
     */
    public function actionCreate()
    {
        $createData = $this->post();
        return AppUpdateSwitchService::create($createData);
    }

    /**
     * 保存app迭包后台开关.
     * @return mixed
     */
    public function actionUpdate()
    {
        $updateData = $this->post();
        return AppUpdateSwitchService::update($updateData);
    }

    /*public function actionSwitch()
    {
        $Data = $this->post();
        return AppUpdateSwitchService::switch($Data);
    }*/
}

?>