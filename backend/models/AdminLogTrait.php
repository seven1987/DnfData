<?php
namespace backend\models;

use Yii;

trait AdminLogTrait
{

    /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType()
    {
        return array(
            'id' => 'bigint',
            'controller_id' => 'string',
            'action_id' => 'string',
            'url' => 'string',
            'module_name' => 'string',
            'func_name' => 'string',
            'right_name' => 'string',
            'client_ip' => 'string',
            'create_user' => 'string',
            'create_date' => 'datetime',
        );
    }


    /**
     * 返回当前表所采用数据库
     */
    public static function getDb()
    {
        return \Yii::$app->get('db_dm_admin');
    }

    /**
     * 返回当前表字段名,包括_id
     */
    public function attributes()
    {
        return ['_id', 'id', 'controller_id', 'action_id', 'url', 'module_name', 'func_name', 'right_name', 'client_ip', 'create_user', 'create_date',];
    }

    /**
     * 返回表主键
     */
    public static function primaryKey()
    {
        return ['id',];
    }
}
