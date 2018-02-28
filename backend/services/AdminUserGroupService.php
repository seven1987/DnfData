<?php

namespace backend\services;

use backend\models\AdminGroup;
use backend\models\AdminUser;
use backend\models\AdminUserGroup;
use common\utils\DataPackager;
use yii;
use yii\data\Pagination;

class AdminUserGroupService
{

    /**
     * 获取分组用户首页数据
     *
     * @param $perPage
     * @param $perPage
     * @param $query
     * @param $orderBy
     * @return array
     */
    public static function getIndexDate($groupId, $perPage, $query, $orderBy)
    {
        $adminUserGroup = AdminUserGroup::find()->select(['admin_user_id'])->where(['group_id' => (int)$groupId])->asArray()->all();
        // adminUser 中id  string
        $adminUserGroupIds = [];
        foreach ($adminUserGroup as $v) {
            $adminUserGroupIds[] = (string)$v['admin_user_id'];
        }
        $model = AdminUser::find();
        $type = '1';
        if (count($query) > 0) {
            foreach ($query as $key => $value) {
                $value = trim($value);
                if ($key == "type") {
                    $type = $value;
                } elseif ($key == "uname") {
                    if (!empty($value)) {
                        $model->andWhere(['like', 'uname', $value]);
                    }
                }
            }
        }
        if ($type === '1') {
            $model->andWhere(['in', 'id', $adminUserGroupIds]);
        } else {
            $model->andWhere(['not in', 'id', $adminUserGroupIds]);
        }
        $pagination = new Pagination([
                'totalCount' => $model->count(),
                'pageSize' => $perPage,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page'
            ]
        );

        if (empty($orderBy) == false) {
            $model = $model->orderBy($orderBy);
        }

        $list = $model->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
        // 生成分页信息
        $pageInfo = BaseService::getPageInfo($pagination, $perPage);

        // 获取表头信息
        $adminUser = new AdminUser();
        $labels = $adminUser->attributeLabels();

        // 获取分组名称
        $adminGroup = AdminGroup::findOne(['group_id' => (int)$groupId]);

        return [
            'list' => $list,
            'pageInfo' => $pageInfo,
            'query' => $query,
            'perPage' => $perPage,
            'labels' => $labels,
            'groupId' => $groupId,
            'groupName' => $adminGroup->group_name
        ];
    }

    /**
     * 分组关联用户
     * @param $data
     * @return string
     */
    public static function relationUserGroup($data)
    {
        if (empty($data) || !isset($data['groupId']) || empty($data['groupId']) || !isset($data['userIds']) || empty($data['userIds'])) {
            return DataPackager::pack($data, 2, '参数错误');
        }
        $groupId = (int)$data['groupId'];
        $adminGroup = AdminGroup::findOne(['group_id' => $groupId]);
        if (empty($adminGroup)) {
            return DataPackager::pack($data, 2, '分组不存在');
        }
        $adminUserGroup = AdminUserGroup::find()->select(['admin_user_id'])->where(['group_id' => $groupId])->asArray()->all();
        $adminUserGroupIds = array_column($adminUserGroup, 'admin_user_id');
        $userId = explode(',', $data['userIds']);
        $failUser = [];
        foreach ($userId as $v) {
            if (!in_array($v, $adminUserGroupIds)) {
                if (!static::createUserGroup($groupId, (int)$v)) {
                    $failUser[] = $v;
                }
            }
        }
        if (count($failUser) > 0) {
            return DataPackager::pack($failUser, 1, '部分操作成功');
        }
        return DataPackager::pack($data, 0, '成功操作');
    }

    /**
     * 创建关联关系
     *
     * @param $groupId
     * @param $adminUserId
     * @return bool
     */
    private static function createUserGroup($groupId, $adminUserId)
    {
        $adminUserGroup = new AdminUserGroup();
        $adminUserGroup->group_id = $groupId;
        $adminUserGroup->admin_user_id = $adminUserId;
        $adminUserGroup->create_user = Yii::$app->user->identity->uname;
        $adminUserGroup->create_date = date('Y-m-d H:i:s');
        $adminUserGroup->update_user = $adminUserGroup->create_user;
        $adminUserGroup->update_date = $adminUserGroup->create_date;
        if (!$adminUserGroup->validators || !$adminUserGroup->save()) {
            return false;
        }
        return true;
    }

    /**
     * 分组解除关联用户
     * @param $id
     * @param $data
     * @return string
     */
    public static function releaseUserGroup($data)
    {
        if (empty($data) || !isset($data['groupId']) || empty($data['groupId']) || !isset($data['userIds']) || empty($data['userIds'])) {
            return DataPackager::pack($data, 2, '参数错误');
        }
        $groupId = (int)$data['groupId'];
        $adminGroup = AdminGroup::find()->where(['group_id' => $groupId])->asArray()->all();
        if (empty($adminGroup)) {
            return DataPackager::pack($data, 2, '分组不存在');
        }
        $adminUserGroup = AdminUserGroup::find()->select(['admin_user_id'])->where(['group_id' => $groupId])->asArray()->all();
        $adminUserGroupIds = array_column($adminUserGroup, 'admin_user_id');
        $userId = explode(',', $data['userIds']);
        foreach ($userId as $v) {
            if (in_array($v, $adminUserGroupIds)) {
                AdminUserGroup::deleteAll(['admin_user_id' => (int)$v, 'group_id' => $groupId]);
            }
        }
        return DataPackager::pack($data, 0, '已成功解除关联');
    }

}
