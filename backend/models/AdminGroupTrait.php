<?php
namespace backend\models;

use Yii;

trait AdminGroupTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'group_id' => 'integer',
		'group_name' => 'string',
		'des' => 'string',
		'status' => 'smallint',
		'create_user' => 'string',
		'create_date' => 'timestamp',
		'update_user' => 'string',
		'update_date' => 'timestamp',
        'code'=>'string',
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
        return ['_id','group_id','group_name','des','status', 'create_user','create_date','update_user','update_date','code'];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['group_id',];
    }
}
