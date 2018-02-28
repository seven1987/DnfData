<?php
namespace common\models;

require_once "GameTrait.php";
use Yii;

/**
 * This is the model class for table "game".
 *
 * @property integer $game_id
 * @property string $name
 * @property string $cdesc
 * @property string $icon
 * @property integer $status
 * @property string $remark
 * @property string $createtime
 */
class Game extends Base
{
    use GameTrait;

    const GAME_STATUS_ACTIVATION = 1; //已激活状态
    const GAME_STATUS_NO_ACTIVATION = 0;//未激活状态
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * 返回对象属性规则.
     */
    public function rules()
    {
        return [
            [['status', 'game_id'], 'integer'],
            [['createtime'], 'safe'],
            [['game_id'], 'unique','message'=>'游戏编号已存在'],
            [['name'], 'string', 'max' => 50],
            [['cdesc', 'remark'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 120]
        ];
    }

    /**
     * 返回对象属性label.
     */
    public function attributeLabels()
    {
        return [
            'game_id' => Yii::t('app', '游戏编号'),
            'name' => Yii::t('app', '游戏名称'),
            'cdesc' => Yii::t('app', '概述'),
            'icon' => Yii::t('app', '游戏图标'),
            'status' => Yii::t('app', '状态'),
            'remark' => Yii::t('app', '备注'),
            'createtime' => Yii::t('app', 'Createtime'),
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
        'game_id' => array(
                        'name' => 'game_id',
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
                        'label'=>$this->getAttributeLabel('game_id'),
                        'inputType' => 'hidden',
                        'isEdit' => true,
                        'isSearch' => true,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'name' => array(
                        'name' => 'name',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '游戏名',
//                         'dbType' => "varchar(50)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '50',
                        'scale' => '',
                        'size' => '50',
                        'type' => 'string',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('name'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'cdesc' => array(
                        'name' => 'cdesc',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '游戏描述',
//                         'dbType' => "varchar(100)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '100',
                        'scale' => '',
                        'size' => '100',
                        'type' => 'string',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('cdesc'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'icon' => array(
                        'name' => 'icon',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '游戏icon',
//                         'dbType' => "varchar(120)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '120',
                        'scale' => '',
                        'size' => '120',
                        'type' => 'string',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('icon'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'status' => array(
                        'name' => 'status',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '状态',
//                         'dbType' => "tinyint(4)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'integer',
                        'precision' => '4',
                        'scale' => '',
                        'size' => '4',
                        'type' => 'smallint',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('status'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'remark' => array(
                        'name' => 'remark',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '备注',
//                         'dbType' => "varchar(100)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '100',
                        'scale' => '',
                        'size' => '100',
                        'type' => 'string',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('remark'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => false,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'createtime' => array(
                        'name' => 'createtime',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '创建时间',
//                         'dbType' => "timestamp",
                        'defaultValue' => 'CURRENT_TIMESTAMP',
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
                        'isDisplay' => false,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		        );
        
    }
 
}
