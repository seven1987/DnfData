<?php

namespace backend\controllers\rights;

use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use backend\services\AdminMenusService;
use yii\web\Response;


/**
 * 管理菜单GRUD控制器.
 */
class AdminMenusController extends BaseController
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
	 * 获取后台菜单数据列表.
	 * @return mixed
	 */
	public function actionIndex()
	{
		parent::initMenus();
		$moduleId = $this->get('module_id');
		$per_page = $this->get('per_page', 50);
		$querys = $this->get('query', []);
		$orderby = ['display_order' => SORT_ASC];

		//菜单页面数据
		$models = AdminMenusService::getAdminMenuList($querys, $per_page,  $orderby, $moduleId);
		return $this->render('index',$models);
	}

	/**
	 * 获取管理菜单属性.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = $this->post('id');
		return AdminMenusService::getAdminMenuView($id);
	}

	/**
	 * 新增管理菜单.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$data = $this->post();
		return AdminMenusService::getAdminMenuCreate($data);
	}

	/**
	 * 修改管理菜单属性.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$menuId = $this->post('menu_id');
		$data = $this->post();
		return AdminMenusService::getAdminMenuUpdate($menuId, $data);
	}




}
