<?php
namespace backend\models;

use Yii;

trait AdminPrivTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'priv_id' => 'integer',
		'module_id' => 'integer',
		'menu_id' => 'integer',
		'priv_name' => 'string',
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
        return ['_id','priv_id','module_id','menu_id','priv_name','priv_url','status', 'create_user','create_date','update_user','update_date',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['priv_id',];
    }
}
