<?php

namespace common\utils;

use Yii;

class Captcha
{
    const TYPE_SIGNUP = 1;              // 验证码类型：注册
    const TYPE_RESETPWD = 2;            // 验证码类型：找回密码
    const TYPE_BIND_MOBILE = 3;         // 验证码类型：绑定手机号

    const num = 4;                      // 验证码字符个数
    const verifyTime = 60;              // 验证码有效时间（秒）

    const CHARS = "abcdefghijklmnopqrstuvwxyz1234567890";   // 字符集

    // 创建一个验证码
    public static function create($type, $key)
    {
        $width = 120;
        $length = 36;
        $code = '';

        $image = imagecreatetruecolor($width, $length);
        $color = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $color);

        // 随机数
        for ($i = 0; $i < self::num; $i++) {
            $fontSize = rand(15, 25);
            $fontColor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));

            $fontContent = substr(self::CHARS, rand(0, strlen(self::CHARS)), 1);
            $code .= $fontContent;

            $x = ($i * $width / 4) + rand($length / 6, $length / 3);
            $y = rand(20, $length - 5);

            @imagefttext($image, $fontSize , 0, $x, $y, $fontColor, ROOT_PATH . 'fonts/Arial Monospaced.ttf', $fontContent);

//            imagestring($image, $fontSize, $x, $y, $fontContent, $fontColor);
        }

        // 存储生成的验证码
        self::setCode($type, $key, $code);

        // 干扰点
        for ($i = 0; $i < 200; $i++) {
            $pointColor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
            imagesetpixel($image, rand(1, $width - 1), rand(1, $width - 1), $pointColor);
        }

        // 干扰线
        for ($i = 0; $i < 2; $i++) {
            $lineColor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
            imageline($image, rand(1, $width - 1), rand(1, $width / 3 - 1), rand(1, $width / 2), rand(1, $width / 3 - 1), $lineColor);
        }

        // 贯穿字符的干扰线
        for ($i = 0; $i < 2; $i++) {
            $lineColor = imagecolorallocate($image, rand(80, 220), rand(80, 220), rand(80, 220));
            imageline($image, 1, $length / 2 + rand(-5, 5), $width, $length / 2 + rand(-5, 5), $lineColor);
        }

        return $image;
    }

    // 验证码存入 redis 中，60 秒过期
    private static function setCode($type, $key, $code)
    {
        Yii::$app->redisService->getRedis()->set(self::getRedisKey($type) . $key, $code, self::verifyTime);
    }

    // 获取已发送给用户的验证码
    public static function getCode($type, $key)
    {
        $code = Yii::$app->redisService->getRedis()->get(self::getRedisKey($type) . $key);
        return $code;
    }

    // 校验验证码是否正确
    public static function verifyCode($type, $key, $code)
    {
        return $code == Yii::$app->redisService->getRedis()->get(self::getRedisKey($type) . $key);
    }

    // 根据类型获取 redis 的 Key
    private static function getRedisKey($type)
    {
        switch ($type) {
            case self::TYPE_SIGNUP:
                return RedisKeys::KEY_CAPTCHA_SIGNUP;
            case self::TYPE_RESETPWD:
                return RedisKeys::KEY_CAPTCHA_RESETPWD;
            case self::TYPE_BIND_MOBILE:
                return RedisKeys::KEY_CAPTCHA_BIND_MOBILE;
        }
    }

}