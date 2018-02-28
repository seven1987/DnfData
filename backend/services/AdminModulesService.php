<?php
namespace backend\services;

use Yii;
use backend\models\AdminMenus;
use backend\models\AdminModules;
use backend\models\AdminPriv;
use common\utils\CommonFun;
use common\utils\DataPackager;

class AdminModulesService extends AdminModules{

	/**
	 * 获取模块列表
	 */
	public static function getList($status=[1], $order='display_order ASC')
	{
		if(!is_array($status))
		{
			$status = [(int)$status];
		}
		return AdminModules::find()->where(['in','status',$status])->orderBy($order)->asArray()->all();
	}

	/**
	 * 获取菜单状态描述
	 * @param null $status
	 * @return array|mixed
	 */
	public static function getStatusDes($status=null)
	{
		$statusDes = [
			AdminModules::STATUS_ACTIVE => '已激活',
			AdminModules::STATUS_UNACTIVE => '未激活',
		];
		if(is_null($status))
		{
			return $statusDes;
		}
		return $statusDes[(int)$status];
	}

	/**
	 * 模块首页列表
	 * @param $querys
	 * @param $perPage
	 * @param $orderBy
	 * @return array
	 */
	public static function getAdminModuleList($querys, $perPage, $orderBy)
	{
		//初始化数据
		$query = AdminModules::find();
		$AdminModule = new AdminModules();

		//查询条件
		static::fetchIndexCondition($query, $querys, $orderBy);

		//分页实例
		$count = $query->count();
		$pageInfo = BaseService::getPageInfos($count, $perPage);

		//列表查询
		$models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();
		$models = static::formatAdminModuleList($models);
//		CommonFun::dump($models);
		return [
			'models' => $models,
			'pageInfo' => $pageInfo,
			'query' => $querys,
			'per_page' => $perPage,
			'labels' => $AdminModule->attributeLabels(),
			'status_des' => static::getStatusDes(),
		];
	}
	private static function formatAdminModuleList($models=[])
	{
		if(empty($models))
		{
			return [];
		}

		foreach ($models as $key => $model)
		{
			$model['status_des'] = static::getStatusDes((int)$model['status']);

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
					if ($key == "module_id") {
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
	 * 返回菜单管理view
	 * @param $id
	 * @return string
	 */
	public static function getAdminModuleView($id)
	{
		$model = AdminModules::findOne($id);
		return $model->getAttributes();
	}

	/**
	 * 新增模块
	 * @param $data
	 * @return string
	 */
	public static function getAdminmoduleCreate($data)
	{
		$model = new AdminModules();
		if ($model->load($data)) {
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
			return DataPackager::rawPack('', 1, '数据出错');
		}
	}

	/**
	 * 更新模块
	 * @param $id
	 * @param $data
	 * @return string
	 */
	public static function getAdminModuleUpdate($moduleId, $data)
	{
		$model = AdminModules::findOne($moduleId);
		if ($model->load($data)) {
			$model->update_user = Yii::$app->user->identity->uname;
			$model->update_date = date('Y-m-d H:i:s');
			if ($model->validate() == true && $model->save()) {
				return DataPackager::rawPack('', 0, '保存成功');
			} else {
				$error = $model->getErrors();
				return DataPackager::rawPack('', 1, array_pop($error));
			}
		} else {
			return DataPackager::rawPack('', 1, '数据出错');
		}
	}

}
