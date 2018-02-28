<?php

namespace backend\services;

use backend\models\MallLog;
use Yii;

class OplogService{
    /**
     * 列表
     * @param $perPage
     * @param $querys
     * @param $orderBy
     * @return array
     */
    public static function getIndexData($perPage,$querys,$orderBy)
    {

        //初始化数据
        $query = MallLog::find();
        $malllog = new MallLog();

        //查询条件
        static::fetchIndexCondition($query, $querys, $orderBy);

        //分页实例
        $count = $query->count();
        $pageInfo = BaseService::getPageInfos($count, $perPage);

        //列表查询
        $models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();
        return [
            'labels'=>$malllog->attributeLabels(),
            'models' => $models,
            'pageinfo' => $pageInfo,
            'query' => $querys,
            'per_page' => $perPage,
        ];
    }

    /**
     * 构造查询条件
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
                if (empty($value) == false) {
                    if ($key == "user_name") {
                        $condition[$key] = ['like', $key, $value];
                    }
                    if ($key == "id") {
                        $condition[$key] = ['=', $key, (int)$value];
                    }
                    if ($key == "user_id") {
                        $condition[$key] = ['=', $key, (string)$value];
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
}