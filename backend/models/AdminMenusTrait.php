<?php
namespace backend\models;

use Yii;

trait AdminMenusTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'menu_id' => 'integer',
		'menu_name' => 'string',
		'module_id' => 'integer',
		'display_order' => 'integer',
		'priv_url' => 'string',
		'status' => 'smallint',
		'create_user' => 'string',
		'create_date' => 'timestamp',
		'update_user' => 'string',
		'update_date' => 'timestamp',
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
        return ['_id','menu_id','menu_name','module_id','display_order','priv_url','status', 'create_user','create_date','update_user','update_date',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['menu_id',];
    }
}
