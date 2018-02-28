<?php
namespace backend\services;

use backend\models\AdminGroupPriv;

class AdminGroupPrivService extends AdminGroupPriv{

	/**
	 * 获取分组的权限列表
	 * @param int $groupId
	 * @return array|\yii\mongodb\ActiveRecord
	 */
	public static function getGroupPrivList($groupId=0)
	{
		$groupId = (int)$groupId;
		if(empty($groupId))
		{
			return [];
		}
		return AdminGroupPriv::find()->select(['_id'=>false, 'group_id', 'priv_url'])->where(['group_id' => $groupId])->asArray()->all();
	}
}
