<?php

namespace backend\models;

use backend\services\AdminUserService;
use Yii;
use backend\models\AdminUserRole;

/**
 * This is the model class for table "admin_user".
 *
 * @property string $id
 * @property string $uname
 * @property string $password
 * @property integer $admintype
 * @property integer $agent_id
 * @property string $auth_key
 * @property string $last_ip
 * @property string $is_online
 * @property string $domain_account
 * @property integer $status
 * @property string $create_user
 * @property string $create_date
 * @property string $update_user
 * @property string $update_date
 *
 * @property AdminUserRole[] $adminUserRoles
 * @property SystemUserRole[] $systemUserRoles
 */
class AdminUser extends BackendUser
{
    //管理员类型:管理员
    const ADMINTYPE_ADMIN = 1;
    //管理员类型:操作员
    const ADMINTYPE_OPERATOR = 2;
    //管理员类型:代理账户
    const ADMINTYPE_AGENT = 3;

    //管理员状态：新用户初始化
    const STATUS_INIT = -1;
    //管理员状态：未激活
    const STATUS_INACTIVE = 0;
    //管理员状态：激活
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * 返回对象属性规则.
     */
    public function rules()
    {
        return [
            [['uname', 'password', 'create_user', 'create_date', 'update_user', 'update_date', 'email'], 'required'],
            [['admintype', 'agent_id', 'status', 'is_online', 'id'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['uname', 'domain_account', 'create_user'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 200],
            [['auth_key', 'last_ip'], 'string', 'max' => 50],
//            [['is_online'], 'integer'],
            [['update_user'], 'string', 'max' => 101]
        ];
    }

    /**
     * 返回对象属性label.
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uname' => '用户名',
            'password' => '密码',
            'admintype' => '用户类型',
            'admin_role' => '分组类型',
            'agent_id' => '所属代理',
            'auth_key' => '自动登录key',
            'last_ip' => '最近一次登录ip',
            'is_online' => '是否在线',
            'domain_account' => '域账号',
            'status' => '状态',
            'create_user' => '创建人',
            'create_date' => '创建时间',
            'update_user' => '更新人',
            'update_date' => '更新时间',
            'email' => '邮箱',
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdminUserRoles()
    {
        return $this->hasMany(AdminUserRole::className(), ['user_id' => 'id']);
    }

    /**
     * 根据 id 获取管理员
     * @return \yii\db\ActiveQuery
     */
    public function getSystemUserRoles()
    {
        return $this->hasMany(SystemUserRole::className(), ['user_id' => 'id']);
    }

    /**
     * 返回数据库字段信息，仅在生成CRUD时使用，如不需要生成CRUD，请注释或删除该getTableColumnInfo()代码
     * COLUMN_COMMENT可用key如下:
     * label - 显示的label
     * inputType 控件类型, 包含text,select,checkbox,radio,file,password,hidden
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
    public function getTableColumnInfo()
    {
        return array(
            'id' => array(
                'name' => 'id',
                'allowNull' => false,
//                         'autoIncrement' => true,
//                         'comment' => '',
//                         'dbType' => "bigint(20) unsigned",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => true,
                'phpType' => 'string',
                'precision' => '20',
                'scale' => '',
                'size' => '20',
                'type' => 'bigint',
                'unsigned' => true,
                'label' => $this->getAttributeLabel('id'),
                'inputType' => 'hidden',
                'isEdit' => true,
                'isSearch' => true,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'uname' => array(
                'name' => 'uname',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '用户名',
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
                'label' => $this->getAttributeLabel('uname'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'password' => array(
                'name' => 'password',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '密码',
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
                'label' => $this->getAttributeLabel('password'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'admintype' => array(
                'name' => 'admintype',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '管理账户类型:1超级管理员，2平台操作员,3代理账户',
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
                'label' => $this->getAttributeLabel('admintype'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'agent_id' => array(
                'name' => 'agent_id',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => 'admintype=3时，代理id',
//                         'dbType' => "tinyint(4)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'int',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('agent_id'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'auth_key' => array(
                'name' => 'auth_key',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '自动登录key',
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
                'label' => $this->getAttributeLabel('auth_key'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'last_ip' => array(
                'name' => 'last_ip',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '最近一次登录ip',
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
                'label' => $this->getAttributeLabel('last_ip'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'is_online' => array(
                'name' => 'is_online',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '是否在线',
//                         'dbType' => "char(1)",
                'defaultValue' => 'n',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '1',
                'scale' => '',
                'size' => '1',
                'type' => 'smallint',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('is_online'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'domain_account' => array(
                'name' => 'domain_account',
                'allowNull' => true,
//                         'autoIncrement' => false,
//                         'comment' => '域账号',
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
                'label' => $this->getAttributeLabel('domain_account'),
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
                'defaultValue' => '10',
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
            'create_user' => array(
                'name' => 'create_user',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '创建人',
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
                'label' => $this->getAttributeLabel('create_user'),
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
//                         'dbType' => "datetime",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'datetime',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('create_date'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//                         'udc'=>'',
            ),
            'update_user' => array(
                'name' => 'update_user',
                'allowNull' => false,
//                         'autoIncrement' => false,
//                         'comment' => '更新人',
//                         'dbType' => "varchar(101)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '101',
                'scale' => '',
                'size' => '101',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('update_user'),
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
//                         'comment' => '更新时间',
//                         'dbType' => "datetime",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'datetime',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('update_date'),
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
