<?php

namespace backend\services;

use backend\models\AdminGroup;
use backend\models\AdminRightUrl;
use backend\models\AdminRole;
use backend\models\AdminRoleRight;
use backend\models\AdminUser;
use backend\models\AdminUserGroup;
use backend\models\AdminUserRole;
use common\utils\CommonFun;
use yii;
use yii\data\Pagination;

class AdminUserService extends AdminUser
{

    public static function getStatusName()
    {
        return [
            AdminUser::STATUS_INIT => "未初始化",
            AdminUser::STATUS_INACTIVE => "未激活",
            AdminUser::STATUS_ACTIVE => "激活",
        ];
    }


    /**
     * 判断用户是否用户当前组的权限
     * @param $groupCode当前组
     */
    public static function getUserGroupCheck($groupCode)
    {
        $userGroupCode = AdminGroupService::getUserGroupCode();
        $adminName = 'admin';
        $username = Yii::$app->user->identity->uname;
        if ($groupCode == AdminGroup::GROUP_SYSTEM_ADMIN && $adminName == $username) {
            return 1;
        }
        if (!in_array($groupCode, $userGroupCode)) {
            return 0;
        }
        return 1;
    }

    /**
     * @return array 返回除超级管理员之外的所有角色
     */
    public static function getAdminRole()
    {
        $data = array();
        $result = AdminRole::find()->select(["id", "name"])->asArray()->all();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                if ($value['id'] == AdminRole::AGENT_ROLE_ID || $value['id'] == AdminRole::ADMIN_ROLE_ID) {
                    continue;
                }
                $data[$value["id"]] = $value["name"];
            }
        }
        return $data;
    }


    /**
     * 用户管理列表
     * @param $querys
     * @param $perPage
     * @param $orderBy
     * @return array
     */
    public static function getAdminUserList($querys, $perPage, $orderBy)
    {
        $adminUser = new AdminUser();
        $query = AdminUser::find();
        $condition = array();
        if (count($querys) > 0) {
            foreach ($querys as $key => $value) {
                $value = trim($value);
                if (empty($value) == false) {
                    $condition[$key] = [$key => $value];
                }
            }
            if (count($condition) > 0) {
                foreach ($condition as $v) {
                    $query->andWhere($v);
                }
            }
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => $perPage,
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page'
            ]
        );


        if (empty($orderby) == false) {
            $query = $query->orderBy($orderBy);
        }
        $models = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $models = static::formatAdminUserList($models);

        $pageInfo = BaseService::getPageInfo($pagination, $perPage);
        return [
            'models' => $models,
            'pageinfo' => $pageInfo,
            'query' => $querys,
            'per_page' => $perPage,
            'labels' => $adminUser->attributeLabels(),
        ];

    }


    /**
     * 判断是否拥有系统管理员权限
     * @param int $user_id
     * @return int
     */
    public static function getIsAdmingRight($user_id = 0)
    {
        $isAllright = 0;
        $codeList = AdminGroupService::getUserGroupCode($user_id);
        $AdminUser = AdminUser::find()->where(['id' => $user_id])->one();
        if (empty($AdminUser)) {
            return 0;
        }
        if (in_array(AdminGroup::GROUP_SYSTEM_ADMIN, $codeList) || $AdminUser['uname'] == 'admin') {//系统角色id
            $isAllright = 1;
        };
        return $isAllright;
    }

    /**
     * 判断是否数据代理商权限
     * @param int $user_id
     */
//    public static function getIsAgentRight($user_id = 0)
//    {
//        $isRight = 0;
//        $codeList = AdminGroupService::getUserGroupCode($user_id);
//        if (in_array(AdminGroup::GROUP_AGENT, $codeList)) {//系统角色id
//            $isRight = 1;
//        };
//        return $isRight;
//    }


    /**
     * 后台用户view
     * @param $id
     * @return string
     */
    public static function getAdminUserView($id)
    {
        $model = AdminUser::findOne($id);

        //获取用户已有归属用户组
        $oldGroup = AdminGroupService::getUserGroupCode($id);
        //页面显示的可添加默认分组
        $defaulGroup = [AdminGroup::GROUP_PLATFORM_ADMIN, AdminGroup::GROUP_HANDER];

        //用户已拥有的默认分组
        $realDefaultGroup = array_intersect($oldGroup, $defaulGroup);

        return json_encode(array("userinfo" => $model->getAttributes(), 'defaultGroup' => $realDefaultGroup));
    }


    /**
     * 创建用户
     * @param $data
     * @throws yii\base\Exception
     */
    public static function getAdminUserCreate($data)
    {
        $model = new AdminUser();
        $admin_email = $data["AdminUser"]["email"];

        $checkAdminUser = static::checkAdminUserExit($data['AdminUser']['uname']);
        if (!empty($checkAdminUser)) {
            $msg = array('errno' => 1, 'msg' => "用户名已存在");
            return json_encode($msg);
        }

        $groupName = $data['groupName'];
        unset($data['groupName']);

        if ($model->load($data)) {
            $online = 1;
            if (empty($model->status) == true) {
                $model->status = -1;
            }
            $model->password = Yii::$app->security->generatePasswordHash($model->password);
            $model->create_user = Yii::$app->user->identity->uname;
            $model->create_date = date('Y-m-d H:i:s');
            $model->update_user = Yii::$app->user->identity->uname;
            $model->update_date = date('Y-m-d H:i:s');
            $model->is_online = (int)$online;
            $model->email = empty($admin_email) ? "" : $admin_email;
            if ($model->validate() == true && $model->save()) {

                $user = AdminUser::findOne(['uname' => $data['AdminUser']['uname']]);

                $result = static::saveUserDefaultGroup($user->id, $groupName);

                if (count($result['failRemove']) == 0 && count($result['failAdd']) == 0) {
                    $msg = array('errno' => 0, 'msg' => '保存成功');
                    return json_encode($msg);
                } else {
                    $msg = array(
                        'errno' => 2,
                        'data' => '部分数据出错',
                        'failAdd' => $result['failAdd'],
                        'failRemove' => $result['failRemove']
                    );
                    return json_encode($msg);
                }

            } else {
                $msg = array('errno' => 2, 'data' => $model->getErrors());
                return json_encode($msg);
            }
        } else {
            $msg = array('errno' => 2, 'msg' => '数据出错');
            return json_encode($msg);
        }
    }


    public static function getAdminUserUpdate($id, $data)
    {
        $admin_email = $data["AdminUser"]["email"];

        $result = static::saveUserDefaultGroup($id, $data['groupName']);

        $model = AdminUser::findOne($id);

        unset($data['groupName']);

        if ($model->load($data)) {
            $model->is_online = 1;
            $model->update_user = Yii::$app->user->identity->uname;
            $model->update_date = date('Y-m-d H:i:s');
            $model->email = empty($admin_email) ? "" : $admin_email;

            if ($model->validate() == true && $model->save() && count($result['failRemove']) == 0 && count($result['failAdd']) == 0) {
                $msg = array('errno' => 0, 'msg' => '保存成功');
                return json_encode($msg);
            } else {
                $msg = array(
                    'errno' => 2,
                    'data' => $model->getErrors(),
                    'failAdd' => $result['failAdd'],
                    'failRemove' => $result['failRemove']
                );
                return json_encode($msg);
            }
        } else {
            $msg = array('errno' => 2, 'msg' => '数据出错');
            return json_encode($msg);
        }
    }

    /**
     * 根据用户名和用户组code更新用户默认用户组组
     * @param $userId
     * @param $groupName
     * @return array
     */
    private static function saveUserDefaultGroup($userId, $groupName)
    {
        $groupName = explode(',', $groupName);

        //获取用户已有归属用户组
        $oldGroup = AdminGroupService::getUserGroupCode($userId);

        //页面显示的可添加默认分组
        $defaulGroup = [AdminGroup::GROUP_PLATFORM_ADMIN, AdminGroup::GROUP_HANDER];

        //获取希望解除关联的默认分组
        $diffGroup = array_diff($defaulGroup, $groupName);

        //获取已有归属分组和希望解除关联分组交集，作为实际需解除关联的分组数组
        $realRemoveGroup = array_intersect($oldGroup, $diffGroup);

        //获取希望关联的分组与已有归属分组差集，作为实际需要添加关联的分组数组
        $oldDefault = array_intersect($oldGroup, $defaulGroup);
        $realAddGroup = array_diff($groupName, $oldDefault);

        $failAdd = [];
        $failRemove = [];

        //解除用户和用户组关联
        foreach ($realRemoveGroup as $removeGroup) {
            if ($removeGroup) {
                $return = AdminGroupService::removeUserGroup($userId, $removeGroup);
                if (!$return) {
                    $failRemove[] = [
                        'userId' => $userId,
                        'groupCode' => $removeGroup
                    ];
                }
            }
        }

        //添加用户和用户组关联
        foreach ($realAddGroup as $addGroup) {
            if ($addGroup) {
                $return = AdminGroupService::addUserGroup($userId, $addGroup);
                if (!$return) {
                    $failAdd[] = [
                        'userId' => $userId,
                        'groupCode' => $addGroup
                    ];
                }
            }
        }

        return [
            'failAdd' => $failAdd,
            'failRemove' => $failRemove
        ];
    }

    public static function getRights($user_id)
    {
        $rows = $checkRightUnique = array();
        $roles = AdminUserRole::findAll(["user_id" => (string)$user_id]);
        foreach ($roles as $role) {
            $rights = AdminRoleRight::findAll(["role_id" => $role->role_id]);
            foreach ($rights as $right) {
                $rightUrl = AdminRightUrl::findOne(["right_id" => $right->right_id]);
                if (isset($rightUrl)) {
                    if (in_array($right->right_id, $checkRightUnique)) {
                        continue;
                    }
                    $checkRightUnique[] = $right->right_id;
                    $rows[] = ["role_id" => $role->role_id, "right_id" => $right->right_id, "user_id" => $user_id];
                }
            }
        }

        return $rows;
    }

    /**
     * 校验管理用户名的唯一性
     * @param $username
     * @return mixed|string
     */
    public static function checkAdminUserExit($username)
    {
        $data = AdminUser::find()->where(['uname' => $username])->asArray()->one();
        return empty($data) ? "" : $data['uname'];
    }

    public static function saveAdminUserRole($id, $value)
    {
        $admin_user_role = new AdminUserRole();
        $admin_user_role->user_id = $id;
        $admin_user_role->role_id = (int)$value;
        $admin_user_role->create_user = Yii::$app->user->identity->uname;
        $admin_user_role->create_date = date('Y-m-d H:i:s');
        $admin_user_role->update_user = Yii::$app->user->identity->uname;
        $admin_user_role->update_date = date('Y-m-d H:i:s');
        if ($admin_user_role->validate() == true && $admin_user_role->save()) {
            $msg = array('errno' => 0, 'msg' => '保存成功');
        } else {
            $msg = array('errno' => 1, 'msg' => '保存失败');
        }
        return $msg;
    }

    /**
     * 获取当前登录用户的代理ID, 如果没有则返回 null
     * @return integer|null
     */
    public static function getAgentID()
    {
        $userID = Yii::$app->user->getId();
        $adminUser = AdminUser::findOne($userID);
        return $adminUser->agent_id;
    }


    private static function formatAdminUserList($models = [])
    {
        if (empty($models)) {
            return [];
        }

        //所有分组列表
        $userGroupList = AdminUserGroup::find()->asArray()->all();
        $groupList = AdminGroupService::getList([0, 1]);
        $groupList = CommonFun::buildRelationArray($groupList, 'group_id');

        foreach ($models as & $model) {
            $model['is_online'] = $model['is_online'] == 1 ? '是' : '否';

            //管理员拥有的分组列表
            $groups = [];
            foreach ($userGroupList as $userGroup) {
                if ($userGroup['admin_user_id'] == $model['id'] && isset($groupList[$userGroup['group_id']])) {
                    $groups[] = $groupList[$userGroup['group_id']]['group_name'];
                }
            }
            $model['group_des'] = implode(',', $groups);
        }

        return $models;
    }
}
