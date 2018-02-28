<?php

namespace common\services;

use common\models\User;
use common\utils\RedisKeys;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Yii;

class UserService
{
    /**
     * 根据 user_id 获取用户数据
     * @param string $user_id
     * @return User|null
     */
    public static function getUser($user_id)
    {
        return User::findOne(['user_id' => $user_id]);
    }

    /**
     * 根据 user_id 获取激活的用户数据
     * @param string $user_id
     * @return User|null
     */
    public static function getActiveUser($user_id)
    {
        return User::findOne(['user_id' => $user_id, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * 根据用户名获取用户数据
     *
     * @param string $username
     * @return User|null
     */
    public static function getUserByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    public static function newToken($userID)
    {
        // 生成 Token
        $signer = new Sha256();

        $token = (new Builder())
            ->setIssuedAt(time())
            ->setExpiration(time() + Yii::$app->params['tokenExpirationTime'])
            ->set('userID', $userID)
            ->sign($signer, Yii::$app->params['tokenSignerKey'])
            ->getToken();

        // 存入 redis, 为之后注销和修改密码做验证
        Yii::$app->redisService->getRedis()->set(RedisKeys::KEY_TOKEN . $userID, (string)$token, Yii::$app->params['tokenExpirationTime']);

        return (string)$token;
    }

    public static function TokenVerify($token)
    {
        do {
            try {
                $token = (new Parser())->parse($token);
            } catch (\Exception $e) {
                // token 内容错误，解析失败
                break;
            }

            $data = new ValidationData();

            // token 校验失败，或已过期
            if (!$token->validate($data)) break;

            $signer = new Sha256();

            // token 签名验证失败，数据被篡改
            if (!$token->verify($signer, Yii::$app->params['tokenSignerKey'])) break;

            $userID = $token->getClaim('userID');

            // 取出保存在 redis 中的 token，验证 token 是否有效
            $_token = Yii::$app->redisService->getRedis()->get(RedisKeys::KEY_TOKEN . $userID);

            if (!$_token || $_token != (string)$token) break;

            $GLOBALS['userID'] = $userID;

            return $token;

        }while(true);

        return false;
    }
}