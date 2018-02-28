<?php

namespace backend\services;

use backend\models\AdminLog;
use backend\models\AdminRole;
use common\utils\DataPackager;
use common\utils\SysCode;
use yii\data\Pagination;

class AdminLogService {
    /**
     * 列表
     * @param $perPage
     * @param $allQuery
     * @param $showOrder
     * @return array
     */
    static public function getIndexData($perPage, $allQuery, $showOrder)
    {
        //初始化数据
        $query = AdminLog::find();
        $adminLog = new AdminLog();

        //查询条件
        static::fetchIndexCondition($query, $allQuery, $showOrder);

        //分页实例
        $count = $query->count();
        $pageInfo = BaseService::getPageInfos($count, $perPage);

        //列表查询
        $models = $query->offset($pageInfo['offset'])->limit($pageInfo['limit'])->asArray()->all();

        return [
            'labels'=>$adminLog->attributeLabels(),
            'models' => $models,
            'pageinfo' => $pageInfo,
            'query' => $allQuery,
            'per_page' => $perPage,
        ];
    }

    /**
     * 单条记录
     * @param $id
     * @return string
     */
    static public function getLogAttribute($id)
    {
        $model = AdminLog::findOne($id);
        if ($model !== null)
            return DataPackager::pack($model->getAttributes());
        return DataPackager::pack("", SysCode::FRONTEND_ERROR_JSON);
    }

    /**
     * 构造查询条件
     * @param $query
     * @param $allQuery
     * @param $showOrder
     */
    public static function fetchIndexCondition(&$query, $allQuery, $showOrder)
    {
        //获取每页显示条数
        if (count($allQuery) > 0) {
            $condition = array();
            foreach ($allQuery as $key => $value) {
                $value = trim($value);
                if (empty($value) == false) {
                    if ($key == 'id') {
                        $condition[] = ['=', $key, (string)$value];
                    }
                    if ($key == 'create_user') {
                        $condition[] = ['=', $key, (string)$value];
                    }
                }
            }
            if (count($condition) > 0) {
                foreach ($condition as $v) {
                    $query->andWhere($v);
                }
            }
        }

        if (empty($showOrder) == false) {
            $query = $query->orderBy($showOrder);
        }

    }
}
