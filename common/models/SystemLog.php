<?php
namespace common\models;

use Yii;

/**
 * This is the model class for table "system_log".
 *
 * @property integer $log_id
 * @property string $logtime
 * @property integer $user_id
 * @property integer $user_type
 * @property string $category
 * @property string $level
 * @property string $message
 */
class SystemLog extends Base
{
    use SystemLogTrait;

    //无操作用户
    const USER_TYPE_NULL = 0;
    //管理员用户（包括代理商）
    const USER_TYPE_ADMIN = 1;
    //普通会员
    const USER_TYPE_USER = 2;

    const LEVEL_ERROR = 'error';
    const LEVEL_WARMING = 'warning';
    const LEVEL_INFO = 'info';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_log';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id'], 'required'],
            [['log_id', 'user_id', 'user_type'], 'integer'],
            [['logtime'], 'safe'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 100],
            [['level'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'logtime' => '日志时间',
            'user_id' => '操作用户ID',
            'user_type' => '用户类型',
            'category' => '分类',
            'level' => '日志级别',
            'message' => '内容',
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
        'log_id' => array(
                        'name' => 'log_id',
                        'allowNull' => false,
//                         'autoIncrement' => false,
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
                        'label'=>$this->getAttributeLabel('log_id'),
                        'inputType' => 'hidden',
                        'isEdit' => true,
                        'isSearch' => true,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'logtime' => array(
                        'name' => 'logtime',
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
                        'label'=>$this->getAttributeLabel('logtime'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'user_id' => array(
                        'name' => 'user_id',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "int(11)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'integer',
                        'precision' => '11',
                        'scale' => '',
                        'size' => '11',
                        'type' => 'integer',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('user_id'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'user_type' => array(
                        'name' => 'user_type',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
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
                        'label'=>$this->getAttributeLabel('user_type'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'category' => array(
                        'name' => 'category',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
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
                        'label'=>$this->getAttributeLabel('category'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'level' => array(
                        'name' => 'level',
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
                        'label'=>$this->getAttributeLabel('level'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'message' => array(
                        'name' => 'message',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '',
//                         'dbType' => "text",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '',
                        'scale' => '',
                        'size' => '',
                        'type' => 'text',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('message'),
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
