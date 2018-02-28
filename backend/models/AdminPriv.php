<?php
namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_priv".
 *
 * @property integer $priv_id
 * @property integer $module_id
 * @property integer $menu_id
 * @property string $priv_name
 * @property string $priv_url
 * @property string $create_user
 * @property string $create_date
 * @property string $update_user
 * @property string $update_date
 */
class AdminPriv extends \backend\models\BaseModel
{
    use AdminPrivTrait;

    //权限状态 1 已激活
    const STATUS_ACTIVE = 1;
	//权限状态 0 未激活
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_priv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priv_id','priv_name','module_id','menu_id','priv_url'], 'required'],
			[['priv_name','priv_url'], 'unique'],
            [['priv_id', 'module_id', 'menu_id'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['priv_name', 'create_user', 'update_user'], 'string', 'max' => 50],
            [['priv_url'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'priv_id' => '权限ID',
            'module_id' => '模块ID',
            'menu_id' => '菜单ID',
            'priv_name' => '权限名称',
            'priv_url' => '权限路径',
            'create_user' => '创建人',
            'create_date' => '创建时间',
            'update_user' => '更新人',
            'update_date' => '更新时间',
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
        'priv_id' => array(
                        'name' => 'priv_id',
                        'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '权限ID',
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
                        'label'=>$this->getAttributeLabel('priv_id'),
                        'inputType' => 'hidden',
                        'isEdit' => true,
                        'isSearch' => true,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'module_id' => array(
                        'name' => 'module_id',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '模块ID',
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
                        'label'=>$this->getAttributeLabel('module_id'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'menu_id' => array(
                        'name' => 'menu_id',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '菜单ID（一级菜单）',
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
                        'label'=>$this->getAttributeLabel('menu_id'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'priv_name' => array(
                        'name' => 'priv_name',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '权限名称',
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
                        'label'=>$this->getAttributeLabel('priv_name'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'priv_url' => array(
                        'name' => 'priv_url',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '权限路径',
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
                        'label'=>$this->getAttributeLabel('priv_url'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'create_user' => array(
                        'name' => 'create_user',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '创建人',
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
                        'label'=>$this->getAttributeLabel('create_user'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'create_date' => array(
                        'name' => 'create_date',
                        'allowNull' => false,
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
                        'label'=>$this->getAttributeLabel('create_date'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'update_user' => array(
                        'name' => 'update_user',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '修改人',
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
                        'label'=>$this->getAttributeLabel('update_user'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'update_date' => array(
                        'name' => 'update_date',
                        'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '修改时间',
//                         'dbType' => "timestamp",
                        'defaultValue' => '0000-00-00 00:00:00',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '',
                        'scale' => '',
                        'size' => '',
                        'type' => 'timestamp',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('update_date'),
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
