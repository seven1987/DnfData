<?php

namespace backend\controllers\rights;

use backend\services\AdminModulesService;
use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * 管理模块GRUD控制器.
 */
class AdminModulesController extends BaseController
{
	public $layout = "lte_main";

	public function behaviors ()
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
	 * 获取管理模块数据列表 new
	 * @return mixed
	 */
	public function actionIndex()
	{
		parent::initMenus();
		//获取每页显示条数
		$perPage = $this->get('per_page', 50);
		$querys = $this->get('query', []);
		$orderby = ['display_order' => SORT_ASC];
		//菜单管理页面数据
		$models = AdminModulesService::getAdminModuleList($querys, $perPage, $orderby);
		return $this->render('index', $models);
	}


	/**
	 * 获取管理模块属性.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = $this->post('id');
		return AdminModulesService::getAdminModuleView($id);
	}

	/**
	 * 新增管理模块.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$data = $this->post();
		return AdminModulesService::getAdminmoduleCreate($data);
	}

	/**
	 * 修改管理模块属性.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$moduleId = $this->post('module_id');
		$data = $this->post();
		return AdminModulesService::getAdminModuleUpdate($moduleId, $data);
	}
}