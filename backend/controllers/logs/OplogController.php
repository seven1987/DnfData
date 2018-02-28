<?php
/**
 * Created by PhpStorm.
 * User: SCF
 * Date: 2017/7/5
 * Time: 21:12
 */
namespace backend\controllers\logs;
use backend\controllers\BaseController;
use backend\services\OplogService;
use common\utils\DataPackager;
use yii\filters\VerbFilter;
class OplogController extends BaseController{
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
     * 商城日志列表
     * @return mixed
     */
    public function actionIndex()
    {
        parent::initMenus();
        $per_page = $this->get('per_page', 50);
        $querys = $this->get('query', []);
        $orderby = $this->get('orderby', '_id DESC');
        return $this->render('index', OplogService::getIndexData($per_page,$querys,$orderby));
    }
}