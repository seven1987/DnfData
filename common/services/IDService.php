<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/1/7
 * Time: 11:11
 */

namespace common\services;


use common\models\IDGenerator;
use Yii;

class IDService {

    //获取下一个id:
    public static function getNextID($tableName)
    {
        return Yii::$app->redisService->getRedisNextID($tableName);
    }

    //申请一批id:
    public static function getNextIDs($tableName,$count)
    {
        return Yii::$app->redisService->getRedisNextID($tableName, $count);
    }

    public static function loadTable($tableName)
    {
        $model = IDGenerator::findOne(["tablename"=>$tableName]);
        if ($model==null){
            $model = new IDGenerator();
            $model->tablename = $tableName;
            $model->currentid = 1;
            $model->save();
        }else {
            $model->currentid += 1;
            $model->save();
        }
        return $model->currentid;
    }

    public static function saveTable($tableName, $value)
    {
        $model = IDGenerator::findOne(["tablename"=>$tableName]);
        if ($model) {
            $model->currentid = $value;
            $model->save();
        }
    }
}
