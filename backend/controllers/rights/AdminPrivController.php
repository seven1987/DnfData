<?php

namespace backend\controllers\rights;


use backend\models\AdminGroup;
use backend\models\AdminGroupPriv;
use backend\models\AdminMenus;
use backend\models\AdminModules;
use backend\models\AdminPriv;
use backend\services\AdminGroupPrivService;
use backend\services\AdminMenusService;
use backend\services\AdminModulesService;
use backend\services\AdminPrivService;
use common\utils\CommonFun;
use Yii;
use backend\controllers\BaseController;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * 管理员权限列表控制器
 * Class AdminPrivController
 * @package backend\controllers\rights
 */
class AdminPrivController extends BaseController
{
	public $layout = "lte_main";

	public function behaviors ()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'index' => ['get'], // 控制请求通过 get才能访问
					'create' => ['post'],
					'getmenu' => ['post'],
					'getpriv' => ['post'],

				],
			],
		];
	}

	public function actionFixidgenerator()
	{
//		CommonFun::updateModelIdGenerator();
	}

	/**
	 * 权限列表
	 * @return mixed
	 */
	public function actionIndex ()
	{
		parent::initMenus();

		$groupId = $this->get('group_id',0);
		if(empty($groupId))
		{
			$this->goBack();
		}

		$data = AdminPrivService::getIndexData($groupId);
//		$this->initPrivTableData();//初始化所有的权限
		return $this->render('index',$data);
	}

	private function initPrivTableData()
	{
//		$allControllerData = CommonFun::getAllController();
//		$allControllerData = CommonFun::buildRelationArray($allControllerData, 'controller_path');
//		CommonFun::dump($allControllerData);

		//初始化权限模板表
		$moduleList = AdminModulesService::getList();
		foreach ($moduleList as $module)
		{
			//获取菜单列表
			$menuList = AdminPrivService::getPrivGetMenu($module['module_id']);
			$menuList = $menuList['data'];
//CommonFun::dump($menuList);
			foreach ($menuList as $menu)
			{
				$privList = AdminPrivService::getPrivGetPriv($menu['menu_id']);
				$privList = $privList['data'];

				//遍历添加权限表信息
				foreach($privList as $priv)
				{
					$privModel = AdminPriv::find()
						->where(['module_id' => $menu['module_id']])
						->andWhere(['menu_id' => $menu['menu_id']])
						->andWhere(['priv_url' => $priv['priv_url']])
						->one();
					if(empty($privModel) || empty($privModel->priv_name))
					{
						$privModel = empty($privModel) ? new AdminPriv() : $privModel;
						$privModel->module_id = $menu['module_id'];
						$privModel->menu_id = $menu['menu_id'];
						$privModel->priv_url = $priv['priv_url'];
						$privModel->priv_name = $priv['priv_url'];
						$privModel->status = 1;
						$privModel->create_user = Yii::$app->user->identity->uname;
						$privModel->create_date = date("Y-m-d H:i:s");
						$privModel->update_user = Yii::$app->user->identity->uname;
						$privModel->update_date = date("Y-m-d H:i:s");
						$privModel->save();
					}
				}

			}
		}
	}

	/**
	 * 获取管理模块属性.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$privId = $this->post('priv_id');
		return AdminPrivService::getAdminPrivView($privId);
	}

	/**
	 * 添加权限
	 * @return mixed
	 */
	public function actionCreate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$data = $this->post();
		return AdminPrivService::getPrivCreate($data);
	}

	/**
	 * 编辑权限
	 * @return mixed
	 */
	public function actionUpdate()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$privId = $this->post('priv_id');
		$data = $this->post();
		return AdminPrivService::getPrivUpdate($privId, $data);
	}

	/**
	 * 删除权限
	 * @return mixed
	 */
	public function actionDelete()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$privId = $this->post('priv_id');
		return AdminPrivService::getPrivDelete($privId);
	}

	/**
	 * AJAX: 获取模块下的菜单
	 * @return string
	 */
	public function actionGetmenu()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$moduleId = $this->post("module_id");
		return AdminPrivService::getPrivGetMenu($moduleId);
	}

	/**
	 * AJAX:获取控制器的方法列表
	 * @return array
	 */
	public function actionGetpriv()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$menuId = $this->post("menu_id");
		return AdminPrivService::getPrivGetPriv($menuId);
	}

	/**
	 * 分组保存管理权限
	 * @return array
	 */
	public function actionGroupsavepriv()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		//分组ID
		$groupId = $this->post('group_id','');
		//选中的权限
		$checkedPrivs = $this->post('checked_privs','');
		$checkedPrivArr = $checkedPrivs ? array_filter(explode(',',(string)$checkedPrivs)) : [];
		//未选中的权限
		$noCheckedPrivs = $this->post('no_check_privs','');
		$noCheckedPrivArr = $noCheckedPrivs ? array_filter(explode(',',(string)$noCheckedPrivs)) : [];

		return AdminPrivService::getGroupSavePriv($groupId, $checkedPrivArr, $noCheckedPrivArr);
	}

	/**
	 * 修改权限名称
	 * @return array
	 */
	public function actionChangename()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$privId = $this->post('priv_id',0);
		$privName = $this->post('priv_name','');

		return AdminPrivService::getChangeName($privId, $privName);
	}

}