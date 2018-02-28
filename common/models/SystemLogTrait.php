<?php
namespace common\models;

use Yii;

trait SystemLogTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'log_id' => 'integer',
		'logtime' => 'timestamp',
		'user_id' => 'integer',
		'user_type' => 'smallint',
		'category' => 'string',
		'level' => 'string',
		'message' => 'text',
		     );
    }


    /**
    * 返回当前表所采用数据库
    */
    public static function getDb()
    {
        return \Yii::$app->get('db_dm_log');
    }

    /**
    * 返回当前表字段名,包括_id
    */
    public function attributes()
    {
        return ['_id','log_id','logtime','user_id','user_type','category','level','message',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['log_id',];
    }
}
