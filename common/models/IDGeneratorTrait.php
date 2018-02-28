<?php
namespace common\models;

use Yii;
trait IDGeneratorTrait
{
    public function insert($runValidation = true, $attributes = null)
    {
        $names = $this->attributes();
        foreach ($names as $name) {
            if ($name == "_id") continue;
            $value = static::typecast($name, $this->getAttribute($name));
            $this->setAttribute($name, $value);
        }

        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }
        $result = $this->insertInternal($attributes);

        return $result;
    }

    /**
     * 返回数据库字段信息，用于mongodb与php数据类型转换
     */
    public static function getColumnType()
    {
        return array
        (
            'tablename' => 'string',
            'currentid' => 'bigint',
            'updatetime' => 'timestamp',
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
        return ['_id','tablename','currentid','updatetime','createtime',];
    }

    /**
    * 返回表主键
    */
    public static function primaryKey()
    {
        return ['tablename'];
    }
}
