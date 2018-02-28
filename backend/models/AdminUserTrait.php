<?php
namespace backend\models;

use Yii;

trait AdminUserTrait
{

    /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType()
    {
        return array(
            'id' => 'bigint',
            'uname' => 'string',
            'password' => 'string',
            'admintype' => 'smallint',
            'agent_id' => 'integer',
            'auth_key' => 'string',
            'last_ip' => 'string',
            'is_online' => 'char',
            'domain_account' => 'string',
            'status' => 'smallint',
            'create_user' => 'string',
            'create_date' => 'datetime',
            'update_user' => 'string',
            'update_date' => 'datetime',
            'email' => 'string',
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
        return ['_id', 'id', 'uname', 'password', 'admintype', 'agent_id', 'auth_key', 'last_ip', 'is_online', 'domain_account', 'status', 'create_user', 'create_date', 'update_user', 'update_date', 'email'];
    }

    /**
     * 返回表主键
     */
    public static function primaryKey()
    {
        return ['id',];
    }
}
