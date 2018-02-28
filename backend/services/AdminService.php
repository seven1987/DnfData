<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/2/20
 * Time: 13:56
 */

namespace backend\services;

use backend\models\AdminMenu;
use backend\models\AdminModule;
use backend\models\AdminRight;
use backend\models\AdminRightUrl;
use backend\models\AdminRoleRight;
use backend\models\AdminUserRole;
use common\utils\CommonFun;
use Yii;
class AdminService {
    public static function getUserMenuList($user_id)
    {
//        CommonFun::b();
//        $userRole = AdminUserRole::find()->where(['user_id' => $user_id]);
//        $roleRight = AdminRoleRight::find()->all();
//        $rights = AdminRight::find()->all();
//        $rightUrl = AdminRightUrl::find()->all();
//        $menu = AdminMenu::find()->all();
//        $module = AdminModule::find()->all();
//        CommonFun::e("all fount");
//        CommonFun::b();
        $roles = AdminUserRole::find()
            ->with([
                'roleRights' => function($query) {
                    $query
//                        ->select(['role_id', 'right_id'])
                        ->with([
                        'rights' => function($query) {
                            $query
//                                ->select(['id', 'menu_id', 'right_id'])
                                ->with([
                                'adminRightUrl',
                                'menu' => function($query) {
                                    $query
//                                        ->select(['id', 'module_id', 'display_label', 'entry_url', 'display_order', 'menu_name'])
                                        ->with(['module']);
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->where(['user_id' => $user_id])
            ->all();
        $modules = array();
        $urls = array();
        foreach ($roles as $role) {
            foreach ($role['roleRights'] as $roleRight) {
                foreach ($roleRight['rights'] as $right) {
                    $menu = $right['menu'];
                    $adminRightUrl = $right['adminRightUrl'];

                    foreach ($menu as $value) {
                        $funcList = array(
                            'label'=>$value['display_label'],
                            'url'=>$value['entry_url'],
                            'orderby'=>$value['display_order'],
                        );
                        $moduleID = $value['module_id'];
                        if (!isset($modules[$moduleID])) {
                            $module = $value['module'];
                            $modules[$moduleID] = array(
                                'label'=>$module['display_label'],
                                'url'=>$module['entry_url'],
                                'orderby'=>$module['display_order'],
                                'funcList'=>array($funcList),
                            );
                        } else {
                            $isNew = true;
                            foreach ($modules[$moduleID]['funcList'] as $l) {
                                if ($l == $funcList) {
                                    $isNew = false;
                                    break;
                                }
                            }
                            if ($isNew)
                                $modules[$moduleID]['funcList'][] = $funcList;
                        }

                        //获取用户所有的 url
                        foreach ($adminRightUrl as $rightUrl) {
                            $urls[$rightUrl['url']] = array(
                                'right_name' => $right['right_name'],
                                'entry_url' => $value['entry_url'],
                                'menu_name' => $value['menu_name'],
                                'module_name' => $modules[$moduleID]['label'],
                            );
                        }
                    }
                }
            }
        }

        //sort
        if(!empty($modules)){
            foreach ($modules as $key => $value) {
                $volume[$key] = $value['orderby'];
            }
            array_multisort($volume, SORT_ASC, $modules);

            foreach ($modules as $k => $val) {
                $menu_order = $menu_data = array();
                $menu_data = $val['funcList'];
                foreach ($menu_data as $ke => $v) {
                    $menu_order[$ke] = (int)$v['orderby'];
                }
                array_multisort($menu_order, SORT_ASC, $menu_data);
                $modules[$k]['funcList'] = $menu_data;
            }
        }


//        CommonFun::e("getuserMenuList:");
        return array('modules'=>$modules, 'urls'=>$urls);
    }
}