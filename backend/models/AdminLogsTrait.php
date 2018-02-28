<?php
namespace backend\models;

use Yii;

trait AdminLogsTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'log_id' => 'bigint',
		'module_name' => 'string',
		'menu_name' => 'string',
		'priv_name' => 'string',
		'priv_url' => 'string',
		'client_ip' => 'string',
		'request_data' => 'text',
		'create_user' => 'string',
		'create_date' => 'timestamp',
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
        return ['_id','log_id','module_name','menu_name','priv_name','priv_url','client_ip','request_data','create_user','create_date',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['log_id',];
    }
}
