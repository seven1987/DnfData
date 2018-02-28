<?php

namespace backend\services;

use common\models\SystemLog;
use Yii;

class SystemLogService{

    /**
     * 获得错误级别列表
     * @return array
     */
    public static function getLevel()
    {
        return [
            SystemLog::LEVEL_ERROR,
            SystemLog::LEVEL_WARMING,
            SystemLog::LEVEL_INFO,
        ];
    }


    /**
     * 获得日志会员类型
     * @return array
     */
    public static function getUserType()
    {
        return [
            SystemLog::USER_TYPE_NULL => '无操作用户',
            SystemLog::USER_TYPE_ADMIN => '管理员',
            SystemLog::USER_TYPE_USER => '普通会员',
        ];
    }

    /**
     * 列表
     * @param $perPage
     * @param $querys
     * @param $orderBy
     * @return array
     */
    public static function getIndexData($perPage,$querys,$orderBy)
    {
        //系统管理员才允许的权限， 暂时屏蔽
//        if (!AdminUserService::getIsAdmingRight(Yii::$app->user->getId())) {
//            echo '无权限';
//            exit;
//        }

        //初始化数据
        $query = SystemLog::find();
        $systemLogModel = new SystemLog();

        //查询条件
        static::fetchIndexCondition($query, $querys, $orderBy);

        //分页实例
        $count = $query->count();
        $pageInfo = BaseService::getPageInfos($count, $perPage);

        //列表查询
        $models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();

        //格式化列表
        $list = static::_formatIndexList($models);


        return [
            'list' => $list,
            'models' => $models,
            'pageinfo' => $pageInfo,
            'query' => $querys,
            'per_page' => $perPage,
            'user_type' => static::getUserType(),//用户类型
            'category_list' => static::getAllCategory(),//分类
            'level_list' => static::getLevel(),//错误级别
            'labels'=>$systemLogModel->attributeLabels(),
        ];
    }

    /**
     * 构造查询
     * @param $query
     * @param $querys
     * @param $orderBy
     */
    public static function fetchIndexCondition(&$query, $querys, $orderBy)
    {
        if (count($querys) > 0) {
            $condition = array();
            foreach ($querys as $key => $value) {
                $value = trim($value);
                if ($key == "user_type" && $value === '0') {
                    $condition[$key] = ['=', $key, (int)$value];
                }
                if (empty($value) == false) {
                    if ($key == "name") {
                        $condition[$key] = ['like', $key, $value];
                    }
                    if ($key == "category") {
                        $condition[$key] = ['=', $key, $value];
                    }
                    if ($key == "level") {
                        $condition[$key] = ['=', $key, $value];
                    }
                    if (in_array($key, ['user_id', 'user_type', 'log_id'])) {
                        $condition[$key] = ['=', $key, (int)$value];
                    }
                }
            }
            if (count($condition) > 0) {
                foreach ($condition as $val) {
                    $query->andWhere($val);
                }
            }
        }

        if (empty($orderBy) == false) {
            $query = $query->orderBy($orderBy);
        }
    }

	/**
	 * 获取所有分类
	 * @return array
	 */
	public static function getAllCategory()
	{
		return SystemLog::find()->select(['category'])->distinct('category');
	}


    /**
     * 格式化列表
     * @param array $models
     * @return array
     */
    private static function _formatIndexList($models = [])
    {
        if (empty($models)) {
            return [];
        }

        $systemLogModel = new SystemLog();
        $userTypeList = static::getUserType();//用户类型

        $list = [];
        foreach ($models as $model) {
            $data = $model;
            //用户类型格式化
            if (isset($userTypeList[$model['user_type']])) {
                $data['user_type'] = $userTypeList[$model['user_type']];
            }

            $list[] = $data;
        }
        return $list;
    }
}