<?php

namespace backend\controllers\rights;


use backend\controllers\BaseController;
use backend\services\AdminGroupService;
use backend\services\AdminUserGroupService;
use common\utils\DataPackager;
use yii\filters\VerbFilter;

/**
 * 管理员分组列表控制器
 * Class AdminGroupController
 * @package backend\controllers\rights
 */
class AdminGroupController extends BaseController
{
    public $layout = "lte_main";

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'user' => ['get'],
                    'create' => ['post'],
                    'update' => ['post'],
                    'view' => ['post'],
                    'relation' => ['post'],
                    'release' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 分组管理列表页
     * @return string
     */
    public function actionIndex()
    {
        parent::initMenus();

        //获取每页显示条数
        $perPage = $this->get('per_page', 50);
        $query = $this->get('query', []);
        $orderBy = $this->get('orderby', 'id DESC');

        return $this->render('index', AdminGroupService::getIndexDate($perPage, $query, $orderBy));
    }

    /**
     * 新增分组
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $data = $this->post();
        return AdminGroupService::adminGroupCreate($data);
    }

    /**
     * 修改分组属性.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $group_id
     * @param array $data
     * @return mixed
     */
    public function actionUpdate()
    {
        $id = $this->post('group_id');
        $data = $this->post();
        return AdminGroupService::adminGroupUpdate($id, $data);
    }

    /**
     * 分组view
     * @param integer $group_id
     * @return mixed
     */
    public function actionView()
    {
        $id = $this->post('group_id');
        return AdminGroupService::getAdminGroupView($id);
    }

    /**
     * 分组用户列表页
     * @return string
     */
    public function actionUser()
    {
        parent::initMenus();

        $groupId = $this->get('group_id', 0);
        if (empty($groupId)) {
            $this->goBack();
        }
        //获取每页显示条数
        $perPage = $this->get('per_page', 50);
        $query = $this->get('query', []);
        $orderBy = $this->get('orderby', 'id DESC');
        return $this->render('user', AdminUserGroupService::getIndexDate($groupId, $perPage, $query, $orderBy));
    }

    /**
     * 分组关联用户
     *
     * @return string
     */
    public function actionRelation()
    {
        $data = $this->post();
        if (empty($data) || !isset($data['groupId']) || empty($data['groupId']) || !isset($data['userIds']) || empty($data['userIds'])) {
            return DataPackager::pack($data, 2, '参数错误');
        }
        return AdminUserGroupService::relationUserGroup($data);
    }

    /**
     * 分组解除关联用户
     *
     * @return string
     */
    public function actionRelease()
    {
        $data = $this->post();
        if (empty($data) || !isset($data['groupId']) || empty($data['groupId']) || !isset($data['userIds']) || empty($data['userIds'])) {
            return DataPackager::pack($data, 2, '参数错误');
        }
        return AdminUserGroupService::releaseUserGroup($data);
    }
}
