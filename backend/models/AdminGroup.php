<?php
namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_group".
 *
 * @property integer $group_id
 * @property string $group_name
 * @property string $des
 * @property string $create_user
 * @property string $create_date
 * @property string $update_user
 * @property string $update_date
 * @property string $code
 */
class AdminGroup extends \backend\models\BaseModel
{
    use AdminGroupTrait;

    const GROUP_STATUS_ACTIVATION = 1; //已激活状态
    const GROUP_STATUS_UN_ACTIVATION = 0;//未激活状态



//    const GROUP_HANDER   = 'group_hander';//操盘手
//    const GROUP_BACKEND_ADMIN = 'group_backend_admin';//后台管理员
//    const GROUP_PRODUCT_MANAGER = 'group_product_manager'; //产品经理
//    const GROUP_TEST = 'group_test'; //测试
//    const GROUP_AGENT ='group_agent';//代理
//    const GROUP_SYSTEM = 'group_system';//系统管理员
//    const GROUP_OPERATION_MANAGER ='group_operation_manager';//运营主管
//    const GROUP_SITE_CONTENT = 'group_site_content';//网站内容
//    const GROUP_SITE_SERVICE = 'group_site_service';//客服


    const GROUP_SYSTEM_ADMIN   = 'group_system_admin'; //系统管理员
    const GROUP_PLATFORM_ADMIN = 'group_platform_admin'; //平台管理员
    const GROUP_HANDICAP_ADMIN = 'group_handicap_admin'; //盘方管理员
    const GROUP_INFORMATION    = 'group_infomation';// 信息录入员
    const GROUP_REVIEW_ADMIN   = 'group_review_admin';//复审管理员
    const GROUP_HANDER          = 'group_hander';//操盘手
    const GROUP_RECKON_ADMIN   = 'group_reckon_admin';//结算管理员
    const GROUP_OPERATION_MANAGER ='group_operation_manager';//运营主管
    const GROUP_SITE_CONTENT    = 'group_site_content';//运营专员
    const GROUP_SITE_SERVICE    = 'group_site_service';//客服


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id','code'], 'required'],
			[['code','group_name'], 'unique'],
            [['group_id','status'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['group_name', 'create_user', 'update_user','code'], 'string', 'max' => 50],
            [['des'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => '组ID',
            'group_name' => '组名',
            'des' => '描述',
            'status' => '状态',
            'code'=>'分组代码',
            'create_user' => '创建人',
            'create_date' => '创建时间',
            'update_user' => '修改人',
            'update_date' => '修改时间',
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
        'group_id' => array(
                        'name' => 'group_id',
                        'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => 'group_id',
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
                        'label'=>$this->getAttributeLabel('group_id'),
                        'inputType' => 'hidden',
                        'isEdit' => true,
                        'isSearch' => true,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
		'group_name' => array(
                        'name' => 'group_name',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '分组名称',
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
                        'label'=>$this->getAttributeLabel('group_name'),
                        'inputType' => 'text',
                        'isEdit' => true,
                        'isSearch' => false,
                        'isDisplay' => true,
                        'isSort' => true,
//                         'udc'=>'',
                    ),
            'status' => array(
                'name' => 'status',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '状态',
//                         'dbType' => "smallint(6)",
                'defaultValue' => '0',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '6',
                'scale' => '',
                'size' => '6',
                'type' => 'smallint',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('status'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
		'des' => array(
                        'name' => 'des',
                        'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '分组描述',
//                         'dbType' => "varchar(200)",
                        'defaultValue' => '',
                        'enumValues' => null,
                        'isPrimaryKey' => false,
                        'phpType' => 'string',
                        'precision' => '200',
                        'scale' => '',
                        'size' => '200',
                        'type' => 'string',
                        'unsigned' => false,
                        'label'=>$this->getAttributeLabel('des'),
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
