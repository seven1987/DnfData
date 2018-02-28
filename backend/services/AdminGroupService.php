<?php

namespace backend\services;

use backend\models\AdminGroup;
use backend\models\AdminUser;
use backend\models\AdminUserGroup;
use Codeception\Lib\Generator\Group;
use common\services\IDService;
use common\utils\CommonFun;
use common\utils\DataPackager;
use common\utils\SysCode;
use yii;
use yii\data\Pagination;

class AdminGroupService
{

    /**
     * 获取分组列表
     * @param array $status
     * @return array|yii\mongodb\ActiveRecord
     */
    public static function getList($status = [1])
    {
        if (!is_array($status)) {
            $status = [(int)$status];
        }
        return AdminGroup::find()->where(['in', 'status', $status])->asArray()->all();
    }

    /**
     * 获取分组首页数据
     *
     * @param $perPage
     * @param $query
     * @param $orderBy
     * @return array
     */
    public static function getIndexDate($perPage, $query, $orderBy)
    {

        static::addAgentGroup(190);

        $model = AdminGroup::find();

        if (count($query) > 0) {
            $condition = array();
            foreach ($query as $key => $value) {
                $value = trim($value);
                if (empty($value) == false) {
                    if ($key == "group_id") {
                        $condition[$key] = [$key => (int)$value];
                    } else {
                        $condition[$key] = ['like', $key, $value];
                    }
                }
            }
            if (count($condition) > 0) {
                foreach ($condition as $value) {
                    $model->andWhere($value);
                }
            }
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

        //判断当前用户权限是否系统管理员角色
        $isAllRight = AdminUserService::getIsAdmingRight(Yii::$app->user->identity->id);

        // 生成分页信息
        $pageInfo = BaseService::getPageInfo($pagination, $perPage);

        // 获取表头信息
        $adminGroup = new AdminGroup();
        $labels = $adminGroup->attributeLabels();

        return [
            'list' => $list,
            'pageInfo' => $pageInfo,
            'query' => $query,
            'isAllRight' => $isAllRight,
            'perPage' => $perPage,
            'labels' => $labels,
            'status' => static::getGroupStatus(),
        ];
    }

    /**
     * 获取分组的状态
     * @return array
     */
    private static function getGroupStatus()
    {
        return [
            AdminGroup::GROUP_STATUS_ACTIVATION => '激活',
            AdminGroup::GROUP_STATUS_UN_ACTIVATION => '未激活',
        ];
    }

    /**
     * 新增分组
     * @param $data
     * @return string
     */
    public static function adminGroupCreate($data)
    {
        $model = new AdminGroup();

        if ($model->load($data)) {
            $code = $model->code;
            $model->group_id = IDService::getNextID($model::tableName());
            $model->create_user = Yii::$app->user->identity->uname;
            $model->create_date = date('Y-m-d H:i:s');
            $model->update_user = Yii::$app->user->identity->uname;
            $model->update_date = date('Y-m-d H:i:s');

            $isExit = static::checkUniqueCode($code);
            if ($isExit) {
                return DataPackager::pack('', 2, '分组代码已存在');
            }

            if ($model->validate() == true && $model->save()) {
                return DataPackager::Pack($data, SysCode::OK, '保存成功');
            } else {
                $errors = $model->getErrors();
                return DataPackager::Pack($errors, 2, array_pop($errors));
            }
        } else {
            return DataPackager::Pack($data, 2, '数据出错');
        }
    }


    /**
     * 修改分组
     * @param $id
     * @param $data
     * @return string
     */
    public static function adminGroupUpdate($groupId, $data)
    {
        $model = AdminGroup::findOne($groupId);
        if ($model->load($data)) {
            $model->update_user = Yii::$app->user->identity->uname;
            $model->update_date = date('Y-m-d H:i:s');
            $code = $model->code;
            $isExit = static::checkUniqueCode($code, $groupId);
            if ($isExit) {
                return DataPackager::pack('', 2, '分组代码已存在');
            }
            if ($model->validate() == true && $model->save()) {
                return DataPackager::Pack($data, 1, '修改成功');
            } else {
                $errors = $model->getErrors();
                return DataPackager::Pack($errors, 2, array_pop($errors));
            }
        } else {
            return DataPackager::Pack($data, 2, '数据出错');
        }
    }


    /**
     * 分组管理view
     * @param $id
     * @return string
     */
    public static function getAdminGroupView($groupId)
    {
        $model = AdminGroup::findOne($groupId);
        return json_encode($model->getAttributes());
    }

    /**
     * 返回当前用户的 groupCode
     * @return array
     */
    public static function getUserGroupCode($userId = "")
    {
        if (empty($userId)) {
            $id = Yii::$app->user->identity->id;
        } else {
            $id = $userId;
        }

        if (empty($id)) {
            return [];
        }
        $groupData = AdminUserGroup::find()->where(['admin_user_id' => (int)$id])->asArray()->all();
        $groupId = empty($groupData) ? [] : array_column($groupData, 'group_id');
        $groupCode = AdminGroup::find()->where(['in', 'group_id', $groupId])->asArray()->all();
        return empty($groupCode) ? [] : array_column($groupCode, 'code');
    }


    /**
     * 分组code唯一性验证
     * @param $code
     * @param int $groupId
     * @return bool
     */
    public static function checkUniqueCode($code, $groupId = 0)
    {
        if (!empty($groupId)) {
            $data = AdminGroup::find()->andwhere([
                '<>',
                'group_id',
                (int)$groupId
            ])->andWhere(['code' => $code])->asArray()->one();
        } else {
            $data = AdminGroup::find()->where(['code' => $code])->asArray()->one();
        }
        return empty($data['code']) ? false : true;
    }


    /**
     * 管理用户分组分组
     * @param $userId
     * @param string $groupCode
     * @return bool
     */
    public static function addAgentGroup($userId, $groupCode = AdminGroup::GROUP_HANDER)
    {
        if (empty($userId)) {
            return false;
        }
        //是否已分配
        $checkExit = AdminUserGroup::find()->where(['admin_user_id' => $userId])->asArray()->one();
        if (!empty($checkExit)) {
            return false;
        }
        //是否存在当前分组
        $checkGroup = AdminGroup::find()->where(['code' => $groupCode])->asArray()->one();
        if (empty($checkGroup)) {
            return false;
        }
        $model = new AdminUserGroup();
        $model->group_id = (int)$checkGroup['group_id'];
        $model->admin_user_id = $userId;
        $model->create_user = Yii::$app->user->identity->uname;
        $model->create_date = date('Y-m-d H:i:s');
        $model->update_user = $model->create_user;
        $model->update_date = $model->create_date;
        if ($model->validators && $model->save()) {
            return true;
        }
        return false;
    }

    /**
     * 根据code向用户组添加用户
     * @param int $userId
     * @param string $code
     * @return bool
     */
    public static function addUserGroup($userId, $code)
    {
        if (empty($userId) || empty($code)) {
            return false;
        }

        //根据code获取分组
        $adminGroup = AdminGroup::findOne(['code' => $code]);

        //不存在当前分组
        if (empty($adminGroup)) {
            return false;
        }

        //检查是否已经存在关联
        $checkGroup = AdminUserGroup::find()->where(['code' => $code, 'admin_user_id' => $userId])->asArray()->one();

        if ($checkGroup) {
            return false;
        }

        $model = new AdminUserGroup();
        $model->group_id = (int)$adminGroup->group_id;
        $model->admin_user_id = $userId;
        $model->create_user = Yii::$app->user->identity->uname;
        $model->create_date = date('Y-m-d H:i:s');
        $model->update_user = $model->create_user;
        $model->update_date = $model->create_date;
        if ($model->validators && $model->save()) {
            return true;
        }
        return false;
    }

    /**
     * 根据code向用户组删除用户关联
     * @param int $userId
     * @param string $code
     * @return bool
     */
    public static function removeUserGroup($userId, $code)
    {
        if (empty($userId) || empty($code)) {
            return false;
        }

        //根据code获取分组
        $adminGroup = AdminGroup::findOne(['code' => $code]);

        //不存在当前分组
        if (empty($adminGroup->group_id)) {
            return false;
        }
        $return = AdminUserGroup::deleteAll(['group_id' => $adminGroup->group_id, 'admin_user_id' => (int)$userId]);
        if ($return >= 1) {
            return true;
        } else {
            return false;
        }

    }


}
