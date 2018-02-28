<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/3/2
 * Time: 16:54
 */

namespace common\services;

use common\models\UserLoginLog;
use common\utils\CommonFun;


class UserLoginLogService {

    /**
     * 添加用户登录日志
     * @param $user_id
     * @return bool
     */
    public static function addLog($user_id) {
        $newLog = new UserLoginLog();
        $newLog->user_id = $user_id;
        $newLog->ip = CommonFun::getClientIp();
        $newLog->logtype = 1;
//        $newLog->logintoken = ; //@todo add login token
        $newLog->createtime = date('Y-m-d H:i:s',time());
        if ($newLog->save()) {
            return true;
        } else {
            return false;
        }
    }
}