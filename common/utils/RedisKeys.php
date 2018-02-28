<?php

namespace common\utils;

//全局redis key 配置
class RedisKeys
{
    //系统日志key
    const KEY_SYSTEM_LOG = 'dm:log:systemlog:1';
    //新注单key
    const KEY_BET_NEW = "dm:bet:new";
    //(后台)待审核注单key
    const KEY_BET_CHECK = "dm:bet:check";
    //(后台)审核过注单key
    const KEY_BET_CONFIRM = "dm:bet:confirm";
    //审核通过串注注单key
    const KEY_BET_PASS_STRING = "dm:bet:pass_string";
    //审核通过注单key前缀
    const KEY_BET_PASS_PREFIX = "dm:bet:pass:";
    //审核通过I币串注注单key
    const KEY_ICOIN_BET_PASS_STRING = "dm:bet:pass_icoin_string";
    //审核通过I币注单key前缀
    const KEY_ICOIN_BET_PASS_PREFIX = "dm:bet:icoin_pass:";
    //盘口注单变化key
    const KEY_HAN_BET_CHANGE = "dm:handicap:bet:change";
    //表主键自增id key:
    const KEY_TABLE_ID_INC = "dm:table:idinc:";
    //盘口选项赔率变化，通知用户端：
    const KEY_ODDS_CHANGE_FRONT = "dm:handicap:odds:change_front";
    //盘口选项赔率变化，通知管理端：
    const KEY_ODDS_CHANGE_BACK = "dm:handicap:odds:change_back";
    //自动计算新赔率：
    const KEY_ODDS_AUTO_NEW = "dm:handicap:odds_auto_new";
    //盘口自动赔率开关状态
    const KEY_HANDICAP_AUTO_ODDS = "dm:handicap:auto:odds";
    //盘口自动赔率手动自动切换动作队列
    const KEY_HANDICAP_AUTO_ODDS_ACTION_LIST = "dm:handicap:autoodds:action_list";
    //盘口自动赔率相关修改动作队列
    const KEY_HANDICAP_AUTO_UPDATE_ACTION_LIST = "dm:handicap:auto_update:action_list";
    //盘口选项自动赔率变化，通知管理端
    const KEY_AUTO_ODDS_CHANGE_BACK = "dm:handicap:auto:odds:change_back";
    //盘口自动赔率修正值保存
    const KEY_HANDICAP_ODDS_FIX_MAP = "dm:handicap:odds:fixmap:";
    //盘口自动赔率盘口注单信息保存
    const KEY_HANDICAP_ODDS_BET_MAP = "dm:handicap:odds:betmap:";
    //盘口返回率信息保存
    const KEY_HANDICAP_RETURNRATE_MAP = "dm:handicap:returnrate:map";
    //盘口赔率信息保存
    const KEY_HANDICAP_ODDS_LIST_MAP = "dm:handicap:odds:list:map:";

    // 短信验证码，注册用
    const KEY_SMS_SIGNUP = 'dm:sms:signup:';
    // 短信验证码，找回密码
    const KEY_SMS_RESETPWD = 'dm:sms:resetpwd:';
    // 短信验证码，绑定手机号
    const KEY_SMS_BIND_MOBILE = 'dm:sms:bind_mobile:';

    // 图片验证码，注册用
    const KEY_CAPTCHA_SIGNUP = 'dm:captcha:signup:';
    // 图片验证码，找回密码
    const KEY_CAPTCHA_RESETPWD = 'dm:captcha:resetpwd:';
    // 图片验证码，绑定手机号
    const KEY_CAPTCHA_BIND_MOBILE = 'dm:captcha:bind_mobile:';

    // Token 有效性存储, userID 为 key
    const KEY_TOKEN = 'dm:token:';

// 短信验证码，注册用
    const KEY_BET_STRING_LIMIT_CHANGE_FRONT = 'dm:bet_string_set:limit';

//    // 活动积分排行榜
//    const KEY_ACTIVITY_RANKLIST = 'dm:activity:rank:';
//    const KEY_ACTIVITY_RANKLIST = 'dm:activity:ranklist';

	//多语言翻译缓存，前端多语言功能使用
	const KEY_LANG_TRANSLATE = 'dm:translate:list:1';

	//数据中心-爬取数据动作
	const KEY_DOSPIDER_ACTION = 'dm:dospider:list:1';

    static public function betPass($han_id, $index){
        return RedisKeys::KEY_BET_PASS_PREFIX."$han_id:$index";
    }

    static public function betPassPattern($han_id){
        return RedisKeys::KEY_BET_PASS_PREFIX."$han_id:*";
    }

    static public function betPassCounter($han_id){
        return "dm:bet:pass_counter:$han_id";
    }
}
