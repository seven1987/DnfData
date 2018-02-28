<?php

namespace backend\models;
use Yii;
/**
 * 兑换商城-- 商品图片 models
 * This is the model class for table "goods_img".
 * @property integer $id
 * @property string $user_id
 * @property string $user_name
 * @property string $ip
 * @property integer $operation
 * @property string $content
 * @property string $createtime
 */
class MallLog extends \backend\models\BaseModel
{
    use MallLogTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'maill_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'string'],
            [['user_name'], 'string'],
            [['operation'], 'string'],
            [['createtime'], 'safe'],
            [['content'], 'string'],
            [['ip'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'user_name'=> '用户名',
            'ip' => '登录IP',
            'operation' => '详细操作',
            'content' => '操作内容',
            'createtime' => '创建时间',
        ];
    }

    /**
     * 返回数据库字段信息，仅在生成CRUD时使用，如不需要生成CRUD，请注释或删除该getTableColumnInfo()代码
     * COLUMN_COMMENT可用key如下:
     * label - 显示的label
     * inputType 控件类型, 暂时只支持text,hidden  // select,checkbox,radio,file,password,
     * isEdit   是否允许编辑，如果允许编辑将在添加和修改时输入
     * isSearch 是否允许搜索
     * isDisplay 是否在列表中显示
     * isOrder 是否排序
     * udc - udc code，inputtype为select,checkbox,radio三个值时用到。
     * 特别字段：
     * id：主键。必须含有主键，统一都是id
     * create_date: 创建时间。生成的代码自动赋值
     * update_date: 修改时间。生成的代码自动赋值
     */
    public function getTableColumnInfo(){
        return array(
            'id' => array(
                'name' => 'id',
                'allowNull' => false,
//                         'autoIncrement' => true,
//                         'comment' => '',
//                         'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => true,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('id'),
                'inputType' => 'hidden',
                'isEdit' => true,
                'isSearch' => true,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'user_id' => array(
                'name' => 'user_id',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "bigint(20)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '20',
                'scale' => '',
                'size' => '20',
                'type' => 'bigint',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('user_id'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'user_name' => array(
                'name' => 'user_name',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "varchar(30)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'string',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('logintoken'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'ip' => array(
                'name' => 'ip',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "varchar(30)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '30',
                'scale' => '',
                'size' => '30',
                'type' => 'string',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('ip'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'operation' => array(
                'name' => 'operation',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "varchar(30)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'string',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('logintoken'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'content' => array(
                'name' => 'content',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "varchar(30)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'string',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('logintoken'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'createtime' => array(
                'name' => 'createtime',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "timestamp",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'timestamp',
                'unsigned' => false,
                'label'=>$this->getAttributeLabel('createtime'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
        );

    }

}
