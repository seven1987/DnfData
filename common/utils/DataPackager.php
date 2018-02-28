<?php

namespace common\utils;

use common\services\RedisService;
use Yii;

class DataPackager
{
    //系统错误码信息， 对应的多语言翻译， 在common/languages/{LANG}/SysCode.php 下定义
    static $MSGS = [
        SysCode::PARAMETER_LOSE => '参数错误',
        
        SysCode::FRONTEND_SUCCESS => '',
        SysCode::FRONTEND_ERROR_SQL => '写入数据库报错',
        SysCode::FRONTEND_ERROR_PHP => 'PHP代码执行报错',
        SysCode::FRONTEND_ERROR_JSON => '提交数据错误，请检查',
        SysCode::FRONTEND_LOGIN_INVALID => '你未登录，请先登录',
        SysCode::FRONTEND_LOGIN_FAILED => '登录失败，请重试',
        SysCode::FRONTEND_LOGIN_USER_NOT_FOUND => '登录失败，用户不存在',
        SysCode::FRONTEND_LOGIN_USER_INVALID => '登录失败，用户未激活',
        SysCode::FRONTEND_LOGIN_USER_PASSWORD_ERROR => '登录失败，密码错误',
        SysCode::FRONTEND_SIGNUP_MOBILE_ERROR => '错误的手机号码，请检查',
        SysCode::FRONTEND_SIGNUP_MOBILE_REGISTERED => '该手机号已注册',
        SysCode::FRONTEND_SIGNUP_CODE_ERROR => '验证码错误，请重试',
        SysCode::FRONTEND_SIGNUP_NAME_REGISTERED => '该昵称已被注册，请重新输入',
        SysCode::FRONTEND_SIGNUP_NAME_LEN_ERROR => '注册失败，用户名长度错误',
        SysCode::FRONTEND_SIGNUP_PASSWORD_LEN_ERROR => '注册失败，密码长度错误',
        SysCode::FRONTEND_SIGNUP_QQ_LEN_ERROR => '注册失败，QQ号码长度错误',
        SysCode::FRONTEND_SIGNUP_AGENT_ERROR => '注册失败，代理商错误',

        SysCode::FRONTEND_RESETPWD_MOBILE_ERROR => '手机号码错误',
        SysCode::FRONTEND_RESETPWD_CODE_ERROR => '验证码错误',
        SysCode::FRONTEND_RESETPWD_PASSWORD_LEN_ERROR => '密码修改失败，新密码长度错误',

        SysCode::FRONTEND_BINDMOBILE_CODE_ERROR => '验证码错误',
        SysCode::FRONTEND_BINDIDNUMBER_CODE_ERROR => '身份证号码错误',
        SysCode::FRONTEND_BINDIDNUMBER_NAME_ERROR => '姓名太长或为空或含特殊字符错误',
        SysCode::FRONTEND_CAPTCHA_CODE_ERROR => '验证码错误',

        SysCode::FRONTEND_BET_AMOUNT_NOT_POSITIVE_INT => '注单金额必须为整数',
        SysCode::FRONTEND_BET_AMOUNT_INVALID => '注单金额有误，不在限额区间内',
        SysCode::FRONTEND_BET_USER_INVALID => '用户状态未激活',
        SysCode::FRONTEND_BET_AGENT_INVALID => '用户所属代理已被冻结',
        SysCode::FRONTEND_BET_MATCH_INVALID => '赛事无效',
        SysCode::FRONTEND_BET_RACE_INVALID => '比赛无效',
        SysCode::FRONTEND_BET_HANDICAP_INVALID => '盘口已关闭或结束',
        SysCode::FRONTEND_BET_AGENT_HANDICAP_CLOSE => '代理商已关闭该盘口',
        SysCode::FRONTEND_BET_HANDICAP_MONEY_MAX => '已达盘口总限额',
        SysCode::FRONTEND_BET_RACE_MONEY_MAX => '已超过该场比赛最高下注金额',
        SysCode::FRONTEND_BET_USER_MONEY_NOT_ENOUGH => 'B币余额不足,请充值',
        SysCode::FRONTEND_ICOIN_BET_USER_MONEY_NOT_ENOUGH => 'i币余额不足',
        SysCode::FRONTEND_BET_FREQUENCY => '下单频率过快',
        SysCode::FRONTEND_BET_ODDS_INVALID => '注单赔率已过期',
        SysCode::FRONTEND_BET_DEDUCT_MONEY_FAILED => '下单扣钱失败',
        SysCode::FRONTEND_BET_USER_HANDICAP_MONEY_MAX => '已达盘口单会员总限额',
        SysCode::FRONTEND_ACTIVITY_SIGNED_REPEAT => '今天已签到，不用再试啦',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_NOT_START => '签到活动未开始',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_FINISHED => '签到活动已结束',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_RECEIVE_NOT_START => '未到领取i币大奖时间',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_RECEIVE_NO_CHANCE => '无可领取i币大奖',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_RECEIVE_SUCCESS => '成功领取i币大奖',
        SysCode::FRONTEND_ACTIVITY_SIGNIN_RECEIVE_FAIL => '领取i币大奖失败',
        SysCode::FRONTEND_LOGIN_USER_EMAIL_ERROR => '邮箱错误',
        SysCode::FRONTEND_RESPWD_USER_ULR_ERROR => '链接错误',
        SysCode::FRONTEND_RESPWD_USER_EMAIL_EXPIRE => '找回密码URL过期',
        SysCode::FRONTEND_RESPWD_USER_EMAIL_CODE => '找回密码code错误',
        SysCode::FRONTEND_RESPWD_USER_INFO_ERROR => '重置密码信息为空',
        SysCode::FRONTEND_RESPWD_USER_PWD_DIFF => '输入的新登录的两次密码不一致',
        SysCode::FRONTEND_RESETPWD_PASSWORD_ERROR => '修改密码失败，输入的旧密码错误',
        SysCode::FRONTEND_LOGIN_USER_EMAIL_NOT_FIND => '输入信息为空或用户不存在',
        SysCode::FRONTEND_BET_STRING_RACE_ERROR => '同一比赛不允许串注',
        SysCode::FRONTEND_BET_STRING_TEAM_ERROR => '同一战队相关盘口不允许串注',
        SysCode::FRONTEND_SWITCH_LANG_FAILED => '语言切换失败',
        SysCode::FRONTEND_BET_STRING_ERROR => '串注赔率最高不超过100,000',
        SysCode::FRONTEND_PLATFORM_AUTH_ERROR => '没有权限访问该接口，请检查参数是否正确',

        SysCode::FRONTEND_ERROR_BAD_REQUEST => '非法请求',
        SysCode::FRONTEND_ERROR_SERVER_INTERNAL => '服务器内部错误',
        SysCode::FRONTEND_ERROR_BAD_GATEWAY => '网关错误',
	
        //资讯平台错误信息
        SysCode::FRONTEND_GET_DATA_PARAM_ERROR => '获取数据参数错误',

        // 兑换商城错误信息
        SysCode::SHOP_GOODS_NOT_FOUND => '商品不存在',
        SysCode::SHOP_GOODS_NO_SELL => '商品已下架',
        SysCode::SHOP_GOODS_NULL_NUMBER => '兑换数量错误',
        SysCode::SHOP_ORDERS_FAILED => '下单失败',
        SysCode::SHOP_GOODS_HAS_BEEN_MODIFIED => '商品被篡改',
        SysCode::SHOP_GOODS_UPDATE_FAILED => '商品信息更新失败'
    ];

    /**
     * 数据按格式封装后输出
     *
     * @param array $data 数据集合
     * @param integer $code 消息代号，在 SysCode 中定义
     * @param array|string $msg 错误提示
     * @return string json
     */
    public static function pack($data, $code = SysCode::FRONTEND_SUCCESS, $msg = '')
    {
        return json_encode(static::rawPack($data, $code, $msg));
    }

    /**
     * 数据按格式封装后输出 (报错用，data = null)
     *
     * @param integer $code 消息代号，在 SysCode 中定义
     * @param array|string $msg 错误提示
     * @return string json
     */
    public static function error($code, $msg = '')
    {
        return json_encode(['data' => null, 'code' => $code, 'msg' => $msg == '' ? Yii::t('SysCode', static::$MSGS[$code]) : $msg]);
    }

    /**
     * 数据按格式封装后输出
     *
     * @param array $data 数据集合
     * @param integer $code 消息代号，在 SysCode 中定义
     * @param array|string $msg 错误提示
     * @return array
     */
    public static function rawPack($data, $code = SysCode::FRONTEND_SUCCESS, $msg = '')
    {
        if ($data == []) {
            return ['data' => null, 'code' => $code, 'msg' => $msg == '' ? Yii::t('SysCode', static::$MSGS[$code]) : $msg];
        }
    	//返回结果统一进行翻译
		$data = static::translate($data);
        return ['code' => $code, 'msg' => $msg == '' ? Yii::t('SysCode', static::$MSGS[$code]) : $msg, 'data' => $data];
    }

	/**
	 * 使用Yii::t( 对接口返回数据， 中文部分进行翻译)
	 * @param array $data
	 * @return array|string
	 */
    public static function translate($data=[])
	{
		$backendInput = isset(Yii::$app->params['backend_input']) ? Yii::$app->params['backend_input'] : 'backend_input';

		//数组
		if(is_array($data))
		{
			foreach ($data as $key => $value)
			{
				if(is_array($value))
				{
					$data[$key] = static::translate($value);
				}
				elseif(is_string($value))
				{
					$value = preg_match('/[\x{4e00}-\x{9fa5}]/u ', $value) ? Yii::t($backendInput, $value) : $value;
					$data[$key] = $value;
				}
			}
		}
		elseif(is_string($data))//字符串
		{
			$data = preg_match('/[\x{4e00}-\x{9fa5}]/u ', $data) ? Yii::t($backendInput, $data) : $data;
		}

		return $data;
	}

   /**
    * 数据压缩
    */
    public static function compress()
    {
    }

    /**
     * 数据解压
     */
    public static function uncompress()
    {
    }

    /**
     * 数据加密
     */
    public static function encode()
    {
    }

    /**
     * 数据解密
     */
    public static function decode()
    {
    }
}