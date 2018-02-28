<?php
namespace backend\services;

use Yii;
use backend\models\AdminMenus;
use common\utils\CommonFun;
use common\utils\DataPackager;

class AdminMenusService extends AdminMenus{

	/**
	 * 通过模块id获取菜单列表
	 * @param int    $moduleId
	 * @param array  $status
	 * @param string $order
	 * @return array|\yii\mongodb\ActiveRecord
	 */
	public static function getMenuByModuleId($moduleId=0, $status=[1], $order='display_order ASC')
	{
		if(empty($moduleId))
		{
			return [];
		}
		if(!is_array($moduleId))
		{
			$moduleId = [(int)$moduleId];
		}
		return  AdminMenus::find()->where(['in', 'module_id', $moduleId])->andWhere(['in','status',$status])->orderBy($order)->asArray()->all();
	}

	/**
	 * 获取菜单列表
	 * @param array  $status
	 * @param string $order
	 * @return mixed
	 */
	public static function getList($status=[1], $order='display_order ASC')
	{
		if(!is_array($status))
		{
			$status = [(int)$status];
		}
		return AdminMenus::find()->where(['in','status',$status])->orderBy($order)->asArray()->all();
	}

	/**
	 * 菜单首页列表
	 * @param $querys
	 * @param $per_page
	 * @param $orderby
	 * @param $mid
	 * @param $controllers
	 * @return array
	 */
	public static function getAdminMenuList($querys, $perPage,  $orderBy, $moduleId)
	{

		//初始化数据
		$query = AdminMenus::find()->andWhere(['module_id' => (int)$moduleId]);
		$AdminMenus = new AdminMenus();

		//查询条件
		static::fetchIndexCondition($query, $querys, $orderBy);

		//分页实例
		$count = $query->count();
		$pageInfo = BaseService::getPageInfos($count, $perPage);

		//列表查询
		$models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();
		$models = static::formatAdminMenuList($models);
//CommonFun::dump($models);
		return [
			'module_id' => $moduleId,
			'models' => $models,
			'pageInfo' => $pageInfo,
			'query' => $querys,
			'per_page' => $perPage,
			'labels' => $AdminMenus->attributeLabels(),
			'controllerData' => CommonFun::buildRelationArray(CommonFun::getAllController(), 'text'),
			'status_des' => static::getStatusDes(),
		];

	}
	private static function formatAdminMenuList($models=[])
	{
		if(empty($models))
		{
			return [];
		}

		foreach ($models as $key => $model)
		{
			$model['status_des'] = static::getStatusDes($model['status']);

			$models[$key] = $model;
		}
		return $models;
	}
	protected static function fetchIndexCondition($query, $querys, $orderBy)
	{
		if (count($querys) > 0) {
			$condition = array();
			foreach ($querys as $key => $value) {
				$value = trim($value);
				if (empty($value) == false) {
					if ($key == "menu_id") {
						$condition[$key] = [$key => (int)$value];
					} else {
						$condition[$key] = [$key => (string)$value];
					}

				}
			}
			if (count($condition) > 0) {
				foreach ($condition as $value) {
					$query->andWhere($value);
				}
			}
		}
		$query->orderBy($orderBy);
	}

	/**
	 * 获取菜单状态描述
	 * @param null $status
	 * @return array|mixed
	 */
	public static function getStatusDes($status=null)
	{
		$statusDes = [
			AdminMenus::STATUS_ACTIVE => '已激活',
			AdminMenus::STATUS_UNACTIVE => '未激活',
		];
		if(is_null($status))
		{
			return $statusDes;
		}
		return $statusDes[(int)$status];
	}

	/**菜单view
	 * @return string
	 */
	public static function getAdminMenuView($id)
	{
		$model = AdminMenus::findOne($id);
		return $model->getAttributes();
	}

	/**
	 * 创建菜单
	 * @param $data
	 * @return array
	 */
	public static function getAdminMenuCreate($data)
	{
		$model = new AdminMenus();

//		var_dump($data);exit;
		if(empty($data['controller']) || empty($data['action']))
		{
			return DataPackager::rawPack('',1,'请选择控制器和方法');
		}

		$controller = $data['controller'];
		$action = $data['action'];

		if ($model->load($data)) {
			$model->priv_url = CommonFun::getPrivUrl($controller, $action);
			$model->create_user = Yii::$app->user->identity->uname;
			$model->create_date = date('Y-m-d H:i:s');
			$model->update_user = Yii::$app->user->identity->uname;
			$model->update_date = date('Y-m-d H:i:s');

			if ($model->save()) {
				return DataPackager::rawPack('', 0, '保存成功');
			} else {
				$error = $model->getErrors();
				return DataPackager::rawPack('', 1, array_pop($error));
			}
		} else {
			$error = $model->getErrors();
			$msg = array_pop($error) ? array_pop($error) : '数据出错';
			return DataPackager::rawPack('', 1, $msg);
		}
	}

	/**
	 * 编辑菜单
	 * @param $id
	 * @param $data
	 * @return string
	 */
	public static function getAdminMenuUpdate($menuId, $data){

		$model = AdminMenus::findOne($menuId);
		$privUrl = null;
		if(!empty($data['controller']) && !empty($data['action']))
		{
			$controller = $data['controller'];
			$action = $data['action'];
			$privUrl = CommonFun::getPrivUrl($controller, $action);
		}

		if ($model->load($data)) {
			if(!is_null($privUrl))
			{
				$model->priv_url = $privUrl;
			}
			$model->update_user = Yii::$app->user->identity->uname;
			$model->update_date = date('Y-m-d H:i:s');
//			var_dump([$model->getAttributes(), $data]);exit;
			if ($model->save()) {
				return DataPackager::rawPack('', 0, '保存成功');
			} else {
				$error = $model->getErrors();
				return DataPackager::rawPack('', 1, array_pop($error));
			}
		} else {
			$error = $model->getErrors();
			$msg = array_pop($error) ? array_pop($error) : '数据出错';
			return DataPackager::rawPack('', 1, $msg);
		}
	}

}
