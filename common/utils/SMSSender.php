<?php

/**
 * 云通讯短信验证码平台调用接口
 * @see http://www.yuntongxun.com/
 * @author  zhenda.li
 */

namespace common\utils;

include_once(COMMON_PATH . "sdk/CCPRestSDK.php");

use Yii;

class SMSSender
{
    const TYPE_SIGNUP = 1;          // 验证码类型：注册
    const TYPE_RESETPWD = 2;        // 验证码类型：找回密码
    const TYPE_BIND_MOBILE = 3;     // 验证码类型：绑定手机号

    const verifyTime = 10;          // 短信验证码有效时间（分钟）

    const accountSID = '8aaf07085d106c7f015d35bf2bf20dd9';          // 主帐号
    const accountToken= 'bcbe0a79f78043f8b8d5cdb073f2bd0d';         // 主帐号 Token
    const appID = '8aaf07085d106c7f015d35bf2c390dde';               // 应用 ID
    const serverIP = 'app.cloopen.com';                             // 请求地址，格式如下，不需要写 https://
    const serverPort = '8883';                                      // 请求端口
    const softVersion = '2013-12-26';                               // REST版本号

    /**
     * 发送模板短信
     *
     * @param   integer $type   类型
     * @param   string  $mobile 手机号
     * @return  array           发送是否成功
     */
    public static function sendSMS($type, $mobile)
    {
        $today = date("Y-m-d");
        $key = self::getRedisKey($type) . ':' . $mobile . ':' . $today;

        $count = Yii::$app->redisService->getRedis()->get($key);
        if ($count && (int)$count >= 5) {
            return ['code' => -1, 'msg' => '您获取验证码的次数过多，可选择明天继续哦'];
        }
        if ($count == FALSE) {
            $count = 0;
        }

        $code = self::initCode($type, $mobile);
        $rest = self::initRest();
        $id = self::getTemplateID($type);
        $result = $rest->sendTemplateSMS($mobile, [$code, self::verifyTime], $id);
        if ($result == NULL || $result['statusCode'] != 0) {
            return ['code' => $result['statusCode'], 'msg' => $result['statusMsg']];
        }

        $count = (int)$count + 1;
        Yii::$app->redisService->getRedis()->set($key, $count, 60 * 60 * 24);

        return ['code' => 0];
    }

    // 初始化 SDK
    private static function initRest()
    {
        $rest = new \REST(self::serverIP,self::serverPort,self::softVersion);
        $rest->setAccount(self::accountSID,self::accountToken);
        $rest->setAppId(self::appID);
        return $rest;
    }

    // 初始化短信验证码，随机的 4 位数字，存入 redis 中，10 分钟过期
    private static function initCode($type, $mobile)
    {
        $code = rand(1000, 9999);
        Yii::$app->redisService->getRedis()->set(self::getRedisKey($type) . $mobile, $code, 60 * self::verifyTime);
        return $code;
    }

    // 获取已发送给用户的短信验证码
    public static function getCode($type, $mobile)
    {
        $code = Yii::$app->redisService->getRedis()->get(self::getRedisKey($type) . $mobile);
        return $code;
    }

    // 根据类型获取短信验证码平台的模板ID （在云通讯的模板列表中查看）
    private static function getTemplateID($type)
    {
        switch ($type) {
            case self::TYPE_SIGNUP:
                return 191827;
            case self::TYPE_RESETPWD:
                return 191828;
            case self::TYPE_BIND_MOBILE:
                return 191829;
        }
    }

    // 根据类型获取 redis 的 Key
    private static function getRedisKey($type)
    {
        switch ($type) {
            case self::TYPE_SIGNUP:
                return RedisKeys::KEY_SMS_SIGNUP;
            case self::TYPE_RESETPWD:
                return RedisKeys::KEY_SMS_RESETPWD;
            case self::TYPE_BIND_MOBILE:
                return RedisKeys::KEY_SMS_BIND_MOBILE;
        }
    }
}