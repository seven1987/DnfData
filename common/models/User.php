<?php

namespace common\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $mobile
 * @property string $username
 * @property string $password
 * @property integer $usertype
 * @property integer $status
 * @property integer $is_login
 * @property string $createtime
 * @property string $updatetime
 * @property integer $agent_id
 * @property string $agent_name
 * @property integer $admin_user_id
 * @property string $lastlogintime
 * @property string $ip_whitelist
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $qq
 * @property string $email
 * @property string $address
 * @property string $win_rate
 * @property int $sex
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $idnumber
 * @property string $truename
 * @property string $invitcode
 * @property integer $invitnum
 * @property integer $rechargenum
 * @property integer $rechargenum2
 * @property integer $rechargenum3
 * @property string $frominvitcode
 * @property integer $experience
 * @property integer $vip_rank
 * @property string $signature
 * @property string $head_icon
 *
 */
class User extends Base implements IdentityInterface
{
    use UserTrait;

    const STATUS_CANCEL = 0; //冻结状态
    const STATUS_ACTIVE = 1; //启用状态

    const ONLINE = 1; // 在线状态
    const OFFLINE = 0; // 离线

    //用户类型:
    const USERTYPE_MEMBER = 1;  // 会员
    const USERTYPE_AGENT  = 2;  // 代理
    const USERTYPE_ROBOT  = 3;  // 机器人
    const USERTYPE_TEST   = 4;  // 测试账号

    //用户性别:
    const USERSEX_MAN = 0;      // 男
    const USERSEX_GIRL = 1;     // 女
    const USERSEX_SECRET = 2;   // 保密

    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usertype', 'status', 'is_login', 'agent_id', 'admin_user_id', 'sex'], 'integer'],
            [['lastlogintime'], 'safe'],
            [['qq'], 'string', 'max' => 18],
            [['username', 'win_rate'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 100],
            [['agent_name', 'email','receiver_name'], 'string', 'max' => 50],
            [['ip_whitelist', 'password_reset_token', 'auth_key', 'address', 'city', 'province', 'country'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', '用户ID'),
            'username' => Yii::t('app', '会员账户'),
            'mobile' => Yii::t('app', '手机号'),
            'password' => Yii::t('app', '密码'),
            'usertype' => Yii::t('app', '类型'),
            'status' => Yii::t('app', '会员状态'),
            'is_login' => Yii::t('app', '在线状态'),
            'createtime' => Yii::t('app', '创建时间'),
            'updatetime' => Yii::t('app', 'Updatetime'),
            'agent_id' => Yii::t('app', 'agent_id'),
            'agent_name' => Yii::t('app', '所属代理'),
            'admin_user_id' => Yii::t('app', 'Admin User ID'),
            'lastlogintime' => Yii::t('app', '最近登录时间'),
            'ip_whitelist' => Yii::t('app', 'Ip Whitelist'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),

            'qq' => Yii::t('app', 'QQ'),
            'address' => Yii::t('app', '收货地址'),
            'win_rate' => Yii::t('app', '胜率'),
            'b_balance'=> Yii::t('app', 'B币余额'),
            'i_balance'=> Yii::t('app', 'I币余额'),
            'total_charge'=> Yii::t('app', '累计充值RMB'),
            'receiver_name'=> Yii::t('app', '收货人'),
            'head_icon'=> Yii::t('app', '用户头像'),
            'vip_rank'=> Yii::t('app', 'VIP等级'),
            'sex'=> Yii::t('app', '性别'),
            'signature'=> Yii::t('app', '个性签名'),
            'status_desc'=> Yii::t('app', '会员状态'),
            'user_balance_b'=> Yii::t('app', 'B币余额'),
        ];
    }

    /**
     * 获取状态描述文本
     */
    public function getStatusDesc()
    {
        if ($this->status == User::STATUS_CANCEL)
            return Yii::t("app", "注销");
        else
            return Yii::t("app", "激活");
    }

    /**
     * 获取在线描述文本
     */
    public function getIsLoginDesc()
    {
        if ($this->is_login == 1)
            return Yii::t("app", "是");
        else
            return Yii::t("app", "否");
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
    public function getTableColumnInfo()
    {
        return array(
            'user_id' => array(
                'name' => 'user_id',
                'allowNull' => false,
//              'autoIncrement' => true,
//              'comment' => '',
//              'dbType' => "bigint(20)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => true,
                'phpType' => 'string',
                'precision' => '20',
                'scale' => '',
                'size' => '20',
                'type' => 'bigint',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('user_id'),
                'inputType' => 'hidden',
                'isEdit' => true,
                'isSearch' => true,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'username' => array(
                'name' => 'username',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '用户名',
//              'dbType' => "varchar(30)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '30',
                'scale' => '',
                'size' => '30',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('username'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'password' => array(
                'name' => 'password',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '用户密码',
//              'dbType' => "varchar(100)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '100',
                'scale' => '',
                'size' => '100',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('password'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'usertype' => array(
                'name' => 'usertype',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '用户类型: 会员;代理;厅主',
//              'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('usertype'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'status' => array(
                'name' => 'status',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '状态',
//              'dbType' => "tinyint(4)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '4',
                'scale' => '',
                'size' => '4',
                'type' => 'smallint',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('status'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'is_login' => array(
                'name' => 'is_login',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '是否在线',
//              'dbType' => "tinyint(4)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '4',
                'scale' => '',
                'size' => '4',
                'type' => 'smallint',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('is_login'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'createtime' => array(
                'name' => 'createtime',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '创建时间',
//              'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('createtime'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'updatetime' => array(
                'name' => 'updatetime',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '',
//              'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('updatetime'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'agent_id' => array(
                'name' => 'agent_id',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '',
//              'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('agent_id'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'agent_name' => array(
                'name' => 'agent_name',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '代理名',
//              'dbType' => "varchar(50)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '30',
                'scale' => '',
                'size' => '30',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('agent_name'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'admin_user_id' => array(
                'name' => 'admin_user_id',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '操作管理员',
//              'dbType' => "int(11)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'integer',
                'precision' => '11',
                'scale' => '',
                'size' => '11',
                'type' => 'integer',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('admin_user_id'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'lastlogintime' => array(
                'name' => 'lastlogintime',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '最后登录时间',
//              'dbType' => "timestamp",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '',
                'scale' => '',
                'size' => '',
                'type' => 'timestamp',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('lastlogintime'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'ip_whitelist' => array(
                'name' => 'ip_whitelist',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => 'ip白名单',
//              'dbType' => "varchar(300)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '300',
                'scale' => '',
                'size' => '300',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('ip_whitelist'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'password_reset_token' => array(
                'name' => 'password_reset_token',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '',
//              'dbType' => "varchar(300)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '300',
                'scale' => '',
                'size' => '300',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('password_reset_token'),
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
//              'autoIncrement' => false,
//              'comment' => '',
//              'dbType' => "varchar(300)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '300',
                'scale' => '',
                'size' => '300',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('auth_key'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
            'win_rate' => array(
                'name' => 'win_rate',
                'allowNull' => true,
//              'autoIncrement' => false,
//              'comment' => '',
//              'dbType' => "varchar(300)",
                'defaultValue' => '',
                'enumValues' => null,
                'isPrimaryKey' => false,
                'phpType' => 'string',
                'precision' => '30',
                'scale' => '',
                'size' => '30',
                'type' => 'string',
                'unsigned' => false,
                'label' => $this->getAttributeLabel('win_rate'),
                'inputType' => 'text',
                'isEdit' => true,
                'isSearch' => false,
                'isDisplay' => true,
                'isSort' => true,
//              'udc'=>'',
            ),
        );

    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 根据 user_id 获取用户数据
     * @inheritdoc
     */
    public static function findIdentity($user_id)
    {
        return static::findOne(['user_id' => $user_id]);
    }

    /**
     * 根据 token 来获取数据，有需要的时候加实现
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

}
