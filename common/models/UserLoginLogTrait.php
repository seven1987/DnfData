<?php
namespace common\models;

use Yii;

trait UserLoginLogTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'id' => 'integer',
		'user_id' => 'string',
		'ip' => 'string',
		'logtype' => 'smallint',
		'logintoken' => 'string',
		'createtime' => 'timestamp',
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
        return ['_id','id','user_id','ip','logtype','logintoken','createtime',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['id',];
    }
}
