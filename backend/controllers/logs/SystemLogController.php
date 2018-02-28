<?php

namespace backend\controllers\logs;

use backend\controllers\BaseController;
use backend\services\SystemLogService;
use Yii;
use yii\filters\VerbFilter;
/**
 * SystemLogController implements the CRUD actions for SystemLog model.
 */
class SystemLogController extends BaseController
{
    public $layout = "lte_main";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'], // 控制请求通过 post 才能访问
                    'view' => ['post'],
                    'create' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all SystemLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        $querys = $this->get('query', []);
        $per_page = $this->get('per_page', 50);
        $orderby = $this->get('orderby', 'logtime desc');

        return $this->render('index', SystemLogService::getIndexData($per_page,$querys,$orderby));
    }
}
