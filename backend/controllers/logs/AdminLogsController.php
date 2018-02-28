<?php

namespace backend\controllers\logs;

use backend\services\AdminLogService;
use backend\services\AdminLogsService;
use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;

/**
 * 管理后台日志GRUD控制器.
 */
class AdminLogsController extends BaseController
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
	 * 获取日志数据列表.
	 * @return mixed
	 */
	public function actionIndex()
	{
		parent::initMenus();
		$perPage = $this->get('per_page', 50);
		$allQuery = $this->get('query', []);
		$showOrder = $this->get('orderby', 'id desc');
		return $this->render('index', AdminLogsService::getIndexData($perPage, $allQuery, $showOrder));
	}

	/**
	 * 获取日志属性.
	 * @return mixed
	 */
	public function actionView()
	{
		$id = $this->post('id');
		return AdminLogsService::getLogAttribute($id);
	}

}
