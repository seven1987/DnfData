<?php
namespace backend\services;

use backend\models\AdminGroup;
use robot\Data;
use Yii;
use backend\models\AdminGroupPriv;
use backend\models\AdminMenus;
use backend\models\AdminModules;
use backend\models\AdminPriv;
use backend\models\AdminUserGroup;
use common\utils\CommonFun;
use common\utils\DataPackager;

class AdminPrivService extends AdminPriv
{

	/**
	 * 获取权限模板列表
	 * @param array $status
	 * @return array|\yii\mongodb\ActiveRecord
	 */
	public static function getList ($status = [1])
	{
		if (!is_array($status)) {
			$status = [(int)$status];
		}
		return AdminPriv::find()->where(['in', 'status', $status])->asArray()->all();
	}

	/**
	 * 权限分配列表页数据
	 * @param $groupId
	 * @return array
	 */
	public static function getIndexData ($groupId)
	{
		$groupModel = AdminGroup::findOne((int)$groupId);
		if (empty($groupModel)) {
			Yii::$app->controller->goBack();
		}
		//获取分组的权限列表
		$groupPrivList = AdminGroupPrivService::getGroupPrivList($groupId);
		$groupPrivList = CommonFun::buildRelationArray($groupPrivList, 'priv_url');
		//所有权限列表
		$allPrivList = AdminPrivService::getList();
		//模块列表
		$moduleList = AdminModulesService::getList([0, 1]);
		//菜单列表
		$menuList = AdminMenusService::getList([0, 1]);


		//格式化权限列表
		$list = static::formatIndexList($moduleList, $menuList, $allPrivList, $groupPrivList);

		return [
			'group_id' => $groupId,
			'list' => $list,
			'module_list' => $moduleList,
//			'menu_list' => $menuList,
		];
	}

	/**
	 * 获取权限编辑相关信息
	 * @param $privId
	 * @return array
	 */
	public static function getAdminPrivView ($privId)
	{
		//权限信息
		$model = AdminPriv::findOne($privId);
		if (empty($model)) {
			return DataPackager::rawPack('', 1, '权限不存在');
		}

		$privUrl = $model['priv_url'];
		//菜单列表
		$menuList = static::getPrivGetMenu($model['module_id']);
		$menuList = $menuList['code'] === 0 ? $menuList['data'] : [];
		//所有控制器方法数据列表
		$allControllerData = CommonFun::getAllController();
		$allControllerData = CommonFun::buildRelationArray($allControllerData, 'controller_path');
		foreach ($allControllerData as $controllerPath => $value) {
			$actionList = [];
			foreach ($value['nodes'] as $kk => $priv) {
				if ($priv['priv_url'] == $privUrl) {
					$actionList = $value['nodes'];
					break;
				}
			}
			if (!empty($actionList)) {
				break;
			}
		}

		$data = [
			'model' => $model->getAttributes(),
			'menu_list' => $menuList,
			'action_list' => $actionList
		];
		return DataPackager::rawPack($data, 0);
	}

	/**
	 * 添加权限
	 * @param $data
	 * @return array
	 */
	public static function getPrivCreate ($data)
	{
		$privUrl = isset($data['AdminPriv']['priv_url']) ? $data['AdminPriv']['priv_url'] : '';
		if (empty($privUrl)) {
			return DataPackager::rawPack('', 1, '请选择权限路径');
		}
		$existPriv = AdminPriv::find()->where(['priv_url' => $privUrl])->one();
		if (!empty($existPriv)) {
			return DataPackager::rawPack('', 1, '权限路径已存在');
		}

		$model = new AdminPriv();
		if (!$model->load($data)) {
			$error = $model->getFirstErrors();
			return DataPackager::rawPack('', 1, '参数错误:' . json_encode($error));
		}

		$model->status = AdminPriv::STATUS_ACTIVE;
		$model->create_user = Yii::$app->user->identity->uname;
		$model->create_date = date('Y-m-d H:i:s');
		$model->update_user = Yii::$app->user->identity->uname;
		$model->update_date = date('Y-m-d H:i:s');

		if ($model->save()) {
			return DataPackager::rawPack('', 0, '保存成功');
		} else {
			$error = $model->getFirstErrors();
			return DataPackager::rawPack('', 1, '保存失败:' . array_pop($error));
		}

	}

	/**
	 * 编辑权限
	 * @param $data
	 * @return array
	 */
	public static function getPrivUpdate ($privId, $data)
	{
		$privId = (int)$privId;
		$model = AdminPriv::findOne($privId);
		if (empty($model)) {
			return DataPackager::rawPack('', 1, '权限不存在');
		}

		$privName = isset($data['AdminPriv']['priv_name']) ? $data['AdminPriv']['priv_name'] : '';
		$privUrl = isset($data['AdminPriv']['priv_url']) ? $data['AdminPriv']['priv_url'] : '';
		if (empty($privUrl)) {
			return DataPackager::rawPack('', 1, '请选择权限路径');
		}
		//判断是否是重复权限
		$existPriv = AdminPriv::find()->where(['priv_url' => $privUrl])->andWhere(['!=', 'priv_id', $privId])->one();
//		CommonFun::dump([$existPriv, $privId]);
		if (!empty($existPriv)) {
			return DataPackager::rawPack('', 1, '权限路径已存在');
		}

		if (!$model->load($data)) {
			$error = $model->getFirstErrors();
			return DataPackager::rawPack('', 1, '参数错误:' . json_encode($error));
		}

		$model->update_user = Yii::$app->user->identity->uname;
		$model->update_date = date('Y-m-d H:i:s');

		if ($model->save()) {
			return DataPackager::rawPack('', 0, '保存成功');
		} else {
			$error = $model->getFirstErrors();
			return DataPackager::rawPack('', 1, '保存失败:' . array_pop($error));
		}

	}

	/**
	 * 删除权限方法
	 * @param $privId
	 * @return array
	 */
	public static function getPrivDelete($privId)
	{
		$privId = (int)$privId;
		$model = AdminPriv::findOne($privId);
		if (empty($model)) {
			return DataPackager::rawPack('', 1, '权限不存在');
		}

		//删除成功
		if($model->delete())
		{
			return DataPackager::rawPack('', 0, '删除成功');
		}

		//删除失败
		$error = $model->getFirstErrors();
		return DataPackager::rawPack('', 1, '删除失败:' . array_pop($error));
	}

	/**
	 * 获取菜单列表数据
	 * @param $moduleId
	 * @return array
	 */
	public static function getPrivGetMenu ($moduleId)
	{
		$menuList = AdminMenusService::getMenuByModuleId($moduleId, [0, 1]);
		return DataPackager::rawPack($menuList, 0);
	}


	/**
	 * 获取控制器的方法列表
	 * @param $menuId
	 * @return array
	 */
	public static function getPrivGetPriv ($menuId)
	{
		$menuId = (int)$menuId;
		if (empty($menuId)) {
			return DataPackager::rawPack('', 1, '错误请求');
		}
		$menu = AdminMenus::find()->where(['menu_id' => $menuId])->asArray()->one();
		if (empty($menu)) {
			return DataPackager::rawPack('', 1, '错误请求');
		}

		$privUrl = $menu['priv_url'];
		$privUrlArr = explode('/', $privUrl);
		if (count($privUrlArr) > 1) {
			array_pop($privUrlArr);
			$privUrl = implode('/', $privUrlArr);
		}
		$allControllerData = CommonFun::getAllController();
		$allControllerData = CommonFun::buildRelationArray($allControllerData, 'controller_path');
		$actionList = isset($allControllerData[$privUrl]['nodes']) ? $allControllerData[$privUrl]['nodes'] : [];
		return DataPackager::rawPack($actionList, 0);
	}

	/**
	 * 获取管理员的权限列表
	 * @param int $adminUserId
	 * @return array|string
	 */
	public static function getAdminUserPrivList ($adminUserId = 0)
	{
		//不验证权限的管理账号
		$allowAccount = ['admin'];
		if (in_array(Yii::$app->user->identity->uname, $allowAccount)) {
			return 'all';//all 代表拥有所有的权限
		}

		$adminUserId = (int)$adminUserId;
		if (empty($adminUserId)) {
			return [];
		}
		//获取管理员关联的分组列表
		$userGroupList = AdminUserGroup::find()->where(['admin_user_id' => $adminUserId])->asArray()->all();
		if (empty($userGroupList)) {
			return [];
		}
		$groupIdArr = array_unique(array_column($userGroupList, 'group_id'));

		//查询已激活的分组
		$activeGroupList = AdminGroup::find()->where([
			'in',
			'group_id',
			$groupIdArr
		])->andWhere(['status' => AdminGroup::GROUP_STATUS_ACTIVATION])->asArray()->all();
		if (empty($activeGroupList)) {
			return [];
		}
		$groupIdArr = array_unique(array_column($activeGroupList, 'group_id'));


		//获取所有分组的权限列表
		$groupPrivList = AdminGroupPriv::find()->select(['_id' => false, 'group_id', 'priv_url'])->where([
			'in',
			'group_id',
			$groupIdArr
		])->asArray()->all();
		if (empty($groupPrivList)) {
			return [];
		}
		$privList = array_unique(array_column($groupPrivList, 'priv_url'));

		//查权限的具体信息
		$privInfoList = AdminPriv::find()->select([
			'_id' => false,
			'module_id',
			'menu_id',
			'priv_name',
			'priv_url'
		])->where(['in', 'priv_url', $privList])->asArray()->all();
		$privInfoList = CommonFun::buildRelationArray($privInfoList, 'priv_url');

		//模块信息
		$moduleIdArr = array_column($privInfoList, 'module_id');
		$moduleInfoList = AdminModules::find()->select(['_id' => false, 'module_id', 'module_name'])->where([
			'in',
			'module_id',
			$moduleIdArr
		])->asArray()->all();
		$moduleInfoList = CommonFun::buildRelationArray($moduleInfoList, 'module_id');
		//菜单信息
		$menuIdArr = array_column($privInfoList, 'menu_id');
		$menuInfoList = AdminMenus::find()->select(['_id' => false, 'menu_id', 'menu_name'])->where([
			'in',
			'menu_id',
			$menuIdArr
		])->asArray()->all();
		$menuInfoList = CommonFun::buildRelationArray($menuInfoList, 'menu_id');

		$returnList = [];
		foreach ($privList as $privUrl) {
			$privInfo = isset($privInfoList[$privUrl]) ? $privInfoList[$privUrl] : [];
			if (empty($privInfo)) {
				continue;
			}
			$privInfo['module_name'] = isset($moduleInfoList[$privInfo['module_id']]['module_name']) ? $moduleInfoList[$privInfo['module_id']]['module_name'] : '';
			$privInfo['menu_name'] = isset($menuInfoList[$privInfo['menu_id']]['menu_name']) ? $menuInfoList[$privInfo['menu_id']]['menu_name'] : '';

			//权限统一去除横杠，并转为小写显示
			$privUrl = AdminPrivService::getShowPrivUrl($privUrl);
			$returnList[$privUrl] = $privInfo;
		}

		return $returnList;
	}

	/**
	 * 获取后台管理菜单
	 * @param array $privList
	 * @return array|\yii\mongodb\ActiveRecord
	 */
	public static function getAdminUserModuleMenu ($privList = [])
	{
		if (empty($privList)) {
			return [];
		}
		$moduleList = static::getModuleMenuShowList();
		//拥有所有权限
		if ($privList == 'all') {
			return $moduleList;
		}

		foreach ($moduleList as $key => $module) {
			if (empty($module['menu_list'])) {
				unset($moduleList[$key]);
				continue;
			}

			//判断菜单显示权限
			foreach ($module['menu_list'] as $kk => $menu) {
				$temPrivUrl = AdminPrivService::getShowPrivUrl($menu['priv_url']);
				if (empty($temPrivUrl) || !array_key_exists($temPrivUrl, $privList)) {
					unset($module['menu_list'][$kk]);
				}
			}

			$moduleList[$key] = $module;

			//如果模块下没有可用的菜单， 则隐藏该模块
			if (empty($module['menu_list'])) {
				unset($moduleList[$key]);
			}

		}
		return $moduleList;
	}

	/**
	 * 格式化后台显示菜单
	 * @param array $moduleList
	 * @return array
	 */
	public static function formtAdminUserModuleMenu ($moduleList=[],$refererUrl=null)
	{
		if(empty($moduleList))
		{
			return [];
		}

		$route = !empty(Yii::$app->controller->route) ? Yii::$app->controller->route : '';
		if(empty($route))
		{
			return $moduleList;
		}

        if($refererUrl==null){
            $refererUrl = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '';
            $refererUrl = \backend\services\AdminPrivService::getShowPrivUrl($refererUrl);
        }

		$isMenuActive = false;

		foreach ($moduleList as $key => $module) {

			$menuList = $module['menu_list'];
			foreach ($menuList as $kk => $menu) {
				if(AdminPrivService::getShowPrivUrl($menu['priv_url']) == AdminPrivService::getShowPrivUrl($route))
				{
					$menuList[$kk]['is_active'] = 1;
					$isMenuActive = true;
				}
				else
				{
					$menuList[$kk]['is_active'] = 0;
				}
			}

			$module['menu_list'] = $menuList;
			$moduleList[$key] = $module;
		}

		//根据上一个url判断当前激活菜单
		if(!$isMenuActive)
		{
			foreach ($moduleList as $key => $module)
			{
				$menuList = $module['menu_list'];
				foreach ($menuList as $kk => $menu) {
					$temPrivUrl = AdminPrivService::getShowPrivUrl($menu['priv_url']);
					if(!empty($refererUrl) && strpos($refererUrl, $temPrivUrl) !== false && $route != 'site/index')
					{
						$menuList[$kk]['is_active'] = 1;
					}
				}

				$module['menu_list'] = $menuList;
				$moduleList[$key] = $module;
			}
		}

		return $moduleList;
	}
	/**
	 * 分组保存管理权限
	 * @param $groupId
	 * @param $checkedPrivArr
	 * @param $noCheckedPrivArr
	 * @return array
	 */
	public static function getGroupSavePriv($groupId, $checkedPrivArr, $noCheckedPrivArr)
	{
//		CommonFun::dump([$checkedPrivArr,$noCheckedPrivArr]);
		$groupId = (int)$groupId;
		if(empty($groupId))
		{
			return DataPackager::rawPack('', 1, '错误分组请求');
		}


		$groupModel = AdminGroup::findOne($groupId);
		if(empty($groupModel))
		{
			return DataPackager::rawPack('', 1, '错误分组请求');
		}


		//删除未选中权限
		if(!empty($noCheckedPrivArr))
		{
			AdminGroupPriv::deleteAll(['and', ['group_id' => $groupId], ['in', 'priv_url', $noCheckedPrivArr]]);
		}

		//替换添加选中权限
		if(!empty($checkedPrivArr))
		{
			$nowDate = date("Y-m-d H:i:s");
			foreach ($checkedPrivArr as $privUrl) {
				$model = AdminGroupPriv::find()->where(['group_id' => $groupId])->andWhere(['priv_url' => $privUrl])->one();
				if (!$model) {
					$model = new AdminGroupPriv();
					$model->group_id = $groupId;
					$model->priv_url = strtolower($privUrl);
					$model->create_user = Yii::$app->user->identity->uname;
					$model->create_date = $nowDate;
				}
				$model->update_date = $nowDate;
				$model->update_user = Yii::$app->user->identity->uname;
				$model->save();
			}
		}

		return DataPackager::rawPack('', 0, '保存成功');
	}

	/**
	 * 修改权限名称
	 * @param int    $privId
	 * @param string $privName
	 * @return array
	 */
	public static function getChangeName($privId=0, $privName='')
	{
		$privId = (int)$privId;
		if(empty($privId) || empty($privName))
		{
			return DataPackager::rawPack('', 1, '错误权限请求');
		}

		$privModel = AdminPriv::findOne($privId);
		if(empty($privModel))
		{
			return DataPackager::rawPack('', 1, '权限不存在');
		}

		$privModel->priv_name = $privName;
		if($privModel->save())
		{
			return DataPackager::rawPack('', 0, '保存成功');
		}
		return DataPackager::rawPack('', 1, '保存失败');
	}

	/**
	 * 权限统一转换后显示，去掉横杠下划线等字符，避免由于url书写问题造成权限匹配不正确
	 * @param string $privUrl
	 * @return string
	 */
	public static function getShowPrivUrl($privUrl='')
	{
		return strtolower(preg_replace("/_|-/","",$privUrl));
	}

	/**
	 * 获得当前用户所有权限的准入列表
	 */
	public static function getAllHasPrivList()
	{
		$hasPriv = [];
		$allPrivList = AdminPrivService::getList();
		foreach ($allPrivList as $priv)
		{
			$privUrl = AdminPrivService::getShowPrivUrl($priv['priv_url']);
			$hasPriv[$privUrl] = (int)CommonFun::hasPriv($privUrl);
		}
		return $hasPriv;
	}

	/**
	 * 获取后台展示的菜单列表
	 * @return array|\yii\mongodb\ActiveRecord
	 */
	private static function getModuleMenuShowList()
	{
		//模块信息
		$moduleList = AdminModulesService::getList();
		$moduleIdArr = array_column($moduleList, 'module_id');
		//菜单信息
		$menuList = AdminMenusService::getMenuByModuleId($moduleIdArr);

		foreach ($moduleList as $key => $module)
		{
			$menus = [];
			foreach ($menuList as $menu)
			{
				if($module['module_id'] == $menu['module_id'])
				{
					$menus[] = $menu;
				}
			}
			$module['menu_list'] = $menus;
			$moduleList[$key] = $module;
		}
		return $moduleList;
	}

	/**
	 * 格式化权限分配列表
	 * @param $moduleList
	 * @param $menuList
	 * @param $allPrivList
	 * @param $privList
	 */
	private static function formatIndexList($moduleList, $menuList, $allPrivList, $privList)
	{
		if(empty($moduleList))
		{
			return [];
		}


		$showModuleList = [];

		//模块
		foreach ($moduleList as $module)
		{
			//对应模块

			//模块所属菜单列表
			$moduleMenuList = [];
			//菜单
			foreach ($menuList as $menu)
			{
				//菜单属于当前模块
				if($menu['module_id'] == $module['module_id'])
				{
					$menu['priv_list'] = [];
					//指定模块菜单的权限
					foreach ($allPrivList as $privForAll)
					{
						//菜单模板存在指定模块下指定菜单
						if($privForAll['module_id'] == $module['module_id'] && $privForAll['menu_id'] == $menu['menu_id'] )
						{
							$isValid = 0;//是否拥有该权限
							if(!empty($privList[$privForAll['priv_url']]))
							{
								$isValid = 1;
							}
							$privForAll['is_valid'] = $isValid;
							$menu['priv_list'][] = [
								'priv_id' => $privForAll['priv_id'],
//								'module_id' => $privForAll['module_id'],
//								'menu_id' => $privForAll['menu_id'],
								'priv_name' => $privForAll['priv_name'],
								'priv_url' => $privForAll['priv_url'],
								'is_valid' => $isValid,
							];
						}
					}
					$moduleMenuList[] = [
						'menu_id' => $menu['menu_id'],
						'menu_name' => $menu['menu_name'],
						'priv_list' => $menu['priv_list'],
					];
				}
			}

			//模块下菜单列表
			$showModuleList[] = [
				'module_id' => $module['module_id'],
				'module_name' => $module['module_name'],
				'menu_list' => $moduleMenuList,
			];
		}

		return $showModuleList;
	}
}
