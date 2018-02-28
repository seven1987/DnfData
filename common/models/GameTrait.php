<?php
namespace common\models;

use Yii;
trait GameTrait
{
    /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType()
    {
        return array
        (
            'game_id' => 'integer',
            'name' => 'string',
            'cdesc' => 'string',
            'icon' => 'string',
            'status' => 'smallint',
            'remark' => 'string',
            'createtime' => 'timestamp',
        );
    }


    /**
    * 返回当前表所采用数据库
    */
    public static function getDb()
    {
        return \Yii::$app->get('db_dm_game');
    }

    /**
    * 返回当前表字段名,包括_id
    */
    public function attributes()
    {
        return ['_id','game_id','name','cdesc','icon','status','remark','createtime',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['game_id'];
    }
}