<?php
namespace backend\models;

use Yii;

trait AdminAnnounceTrait{

  /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType(){
        return array(
        'id' => 'integer',
		'type' => 'integer',
		'title' => 'string',
		'content' => 'string',
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
        return ['_id','id','type','title','content','create_date',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['id',];
    }
}
