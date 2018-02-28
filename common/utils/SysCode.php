<?php
/**
 * User: lishijun
 * Date: 2017/7/24
 * Time: 14:28
 */

namespace common\utils;

//全局系统code
class SysCode
{

    // 全局性code:
    const OK = 0;       //no error
    const FRONTEND_SUCCESS = 0;                                                 // 成功

    // 全局-请求错误码101xxx
    const FRONTEND_ERROR_BAD_REQUEST = 101001;                                  // 非法请求
    const PARAMETER_LOSE = 101002;                                              // 参数错误
    const FRONTEND_ERROR_JSON = 101003;                                         // JSON 数据错误，字段缺失
    const MODEL_NOT_FOUND = 101004;                                             // 无此数据
    const FRONTEND_SWITCH_LANG_FAILED = 101005;                                 // 切换语言失败
    const FRONTEND_PLATFORM_AUTH_ERROR = 101006;                                //没有权限访问该接口，请检查参数是否正确

    // 全局-内部错误码102xxx
    const FRONTEND_ERROR_SERVER_INTERNAL = 102001;                              // 服务器内部错误
    const FRONTEND_ERROR_BAD_GATEWAY = 102002;                                  // 网关错误
    const FRONTEND_ERROR_SQL = 102003;                                          // 写入数据库报错
    const FRONTEND_ERROR_PHP = 102004;                                          // PHP代码执行报错

    // 业务-用户错误码201xxx
    // 用户端，登录、注册错误号
    const FRONTEND_LOGIN_INVALID = 201001;                                      // 登录已失效
    const FRONTEND_LOGIN_FAILED = 201002;                                       // 登录失败
    const FRONTEND_LOGIN_USER_NOT_FOUND = 201003;                               // 用户不存在
    const FRONTEND_LOGIN_USER_INVALID = 201004;                                 // 用户未激活
    const FRONTEND_LOGIN_USER_PASSWORD_ERROR = 201005;                          // 密码错误
    const FRONTEND_LOGIN_USER_EMAIL_ERROR = 201006;                             // 邮箱错误
    const FRONTEND_ERROR_SMS = 201007;                                          // 请求发送短信验证码返回错误
    const FRONTEND_CAPTCHA_CODE_ERROR = 201008;                                 // 图片验证码错误
    const FRONTEND_SIGNUP_CODE_ERROR = 201009;                                  // 手机验证码错误

    const FRONTEND_RESPWD_USER_ULR_ERROR = 201011;                              // 链接错误
    const FRONTEND_RESPWD_USER_EMAIL_EXPIRE = 201012;                           // 找回密码URL过期
    const FRONTEND_RESPWD_USER_EMAIL_CODE = 201013;                             // 找回密码code错误
    const FRONTEND_RESPWD_USER_INFO_ERROR = 201014;                             // 重置密码信息为空
    const FRONTEND_RESPWD_USER_PWD_DIFF = 201015;                               // 两次密码不一致
    const FRONTEND_LOGIN_USER_EMAIL_NOT_FIND = 201016;                          // 密码找回信息为空

    const FRONTEND_SIGNUP_MOBILE_ERROR = 201021;                                // 手机号码错误
    const FRONTEND_SIGNUP_MOBILE_REGISTERED = 201022;                           // 手机号码已注册
    const FRONTEND_SIGNUP_NAME_LEN_ERROR = 201023;                              // 用户名长度错误
    const FRONTEND_SIGNUP_PASSWORD_LEN_ERROR = 201024;                          // 密码长度错误
    const FRONTEND_SIGNUP_QQ_LEN_ERROR = 201025;                                // QQ号长度错误
    const FRONTEND_SIGNUP_AGENT_ERROR = 201026;                                 // 代理商错误
    const FRONTEND_SIGNUP_NAME_REGISTERED = 201027;                             // 用户名（昵称）已被注册

    const FRONTEND_BINDMOBILE_CODE_ERROR = 201031;                              // 绑定手机号，验证码错误
    const FRONTEND_BINDIDNUMBER_CODE_ERROR = 201032;                            // 绑定身份证，身份证号码错误
    const FRONTEND_BINDIDNUMBER_NAME_ERROR = 201033;                            // 绑定身份证，姓名太长或为空或含特殊字符错误
    // 重置密码
    const FRONTEND_RESETPWD_MOBILE_ERROR = 201041;                              // 手机号码错误
    const FRONTEND_RESETPWD_CODE_ERROR = 201042;                                // 验证码错误
    const FRONTEND_RESETPWD_PASSWORD_LEN_ERROR = 201043;                        // 密码长度错误
    const FRONTEND_RESETPWD_PASSWORD_ERROR = 201044;                        // 密码长度错误

    // 业务-注单错误码202xxx
    const FRONTEND_BET_USER_INVALID = 202001;                                   // 用户状态未激活
    const FRONTEND_BET_AGENT_INVALID = 202002;                                  // 用户所属代理已被冻结
    const FRONTEND_BET_USER_MONEY_NOT_ENOUGH = 202003;                          // 账户余额不足，请充值
    const FRONTEND_ICOIN_BET_USER_MONEY_NOT_ENOUGH = 202004;                    // 账户I币余额不足，请充值

    const FRONTEND_BET_MATCH_INVALID = 202011;                                  // 赛事无效
    const FRONTEND_BET_RACE_INVALID = 202012;                                   // 比赛无效
    const FRONTEND_BET_HANDICAP_INVALID = 202013;                               // 盘口已关闭或结束
    const FRONTEND_BET_AGENT_HANDICAP_CLOSE = 202014;                           // 代理商已关闭该盘口

    const FRONTEND_BET_AMOUNT_NOT_POSITIVE_INT = 202021;                        // 注单金额必须为整数
    const FRONTEND_BET_AMOUNT_INVALID = 202022;                                 // 注单金额有误，不在限额区间内
    const FRONTEND_BET_HANDICAP_MONEY_MAX = 202023;                             // 已达盘口总限额
    const FRONTEND_BET_USER_HANDICAP_MONEY_MAX = 202024;                        // 已达盘口单个会员总限额
    const FRONTEND_BET_RACE_MONEY_MAX = 202025;                                 // 已超过该场比赛最高下注金额
    const FRONTEND_BET_FREQUENCY = 202026;                                      // 下单频率过快
    const FRONTEND_BET_ODDS_INVALID = 202027;                                   // 注单赔率已过期
    const FRONTEND_BET_DEDUCT_MONEY_FAILED = 202028;                            // 下单扣钱失败

    const FRONTEND_BET_STRING_ERROR = 202031;                                    // 串注下注失败
    const FRONTEND_BET_STRING_RACE_ERROR = 202032;                              // 同一比赛不允许串注
    const FRONTEND_BET_STRING_TEAM_ERROR = 202033;                              // 同一战队相关盘口不允许串注

    // 业务-交易错误码203xxx
    const MEMBER_TRANSFER_MONEY_ERROR = 203001;                                 // 金额必须大于0
    const MEMBER_TRANSFER_TYPE_ERROR = 203002;                                  // 类型错误
    const MEMBER_TRANSFER_LOG_ERROR = 203003;                                   // 充值写 money log 失败
    const MEMBER_TRANSFER_COMMIT_ERROR = 203004;                                // 充值失败
    const MEMBER_TRANSFER_MONEY_TYPE = 203005;                                  // 充值金额必须为数字
    const MEMBER_TRANSFER_USER_ERROR = 203006;                                  // 用户不存在

    // 业务-活动中心错误码204xxx
    const FRONTEND_ACTIVITY_SIGNED_REPEAT = 204001;                             // 活动签到重复
    const FRONTEND_ACTIVITY_SIGNIN_NOT_START = 204002;                          // 活动签到时间未开始
    const FRONTEND_ACTIVITY_SIGNIN_FINISHED = 204003;                           // 活动签到时间已结束
    const FRONTEND_ACTIVITY_SIGNIN_RECEIVE_NOT_START = 204004;                  // 未到领取i币大奖时间
    const FRONTEND_ACTIVITY_SIGNIN_RECEIVE_NO_CHANCE = 204005;                  // 无领取i币大奖资格
    const FRONTEND_ACTIVITY_SIGNIN_RECEIVE_SUCCESS = 204006;                    // 领取i币大奖成功
    const FRONTEND_ACTIVITY_SIGNIN_RECEIVE_FAIL = 204007;                       // 领取i币大奖失败

    // 业务-商城中心错误码205xxx
    const SHOP_GOODS_NOT_FOUND = 205001;                                        // 商品不存在
    const SHOP_GOODS_NO_SELL = 205002;                                          // 商品已下架
    const SHOP_GOODS_NULL_NUMBER = 205003;                                      // 兑换数量错误
    const SHOP_ORDERS_FAILED = 205004;                                          // 下单失败
    const SHOP_GOODS_HAS_BEEN_MODIFIED = 205005;                                // 商品被篡改
    const SHOP_GOODS_UPDATE_FAILED = 205006;                                    // 商品信息更新失败

    // 业务-新闻中心错误码206xxx
    const NEWS_GAME_ID = 206001;                                                // 游戏ID不存在或非整数

    // 业务-新闻中心错误码207xxx
    const FRONTEND_GET_DATA_PARAM_ERROR = 207001;                               // 获取数据参数错误

    // 服务-系统服务301xxx
    const SYSSERVER_RECKON_HANDICAP_NOT_FOUND = 301001;                           //盘口不存在
    const SYSSERVER_RECKON_HANDICAP_NOT_CONFIRMED = 301002;                       //结算错误码:盘口未在审核通过状态
    const SYSSERVER_RECKONNULL_RESULT = 301003;                                   //结算错误码:盘口赛果为空
    const SYSSERVER_RECKON_WRONG_RESULT = 301004;                                 //结算错误码:盘口赛果无效
    const SYSSERVER_RECKON_HANDICAP_RECKONED = 301005;                            //结算错误码:盘口已结算过
    const SYSSERVER_RECKON_HANDICAP_RECKON_REPEATED = 301006;                     //结算错误码:盘口重复结算
    const SYSSERVER_RECKON_HANDICAP_HASH_INVALID = 301007;                        //结算错误码:盘口hash无效
    const SYSSERVER_RECKON_ROLLBACK_STEP_ERROR = 301008;                          //结算错误码:回滚步骤错误
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_ERR_DATA = 301009;                   //结算错误码:回滚资金流水错误数据
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_NO_DATA = 301010;                    //结算错误码:回滚资金流水无数据
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_ZERO_AMOUNT = 301011;                //结算错误码:回滚资金流水金额为零
    const SYSSERVER_RECKON_ROLLBACK_MONEY_NO_LOG = 301012;                        //结算错误码:回滚资金无相关流水
    const SYSSERVER_RECKON_ROLLBACK_MONEY_NO_DATA = 301013;                       //结算错误码:回滚资金无数据
    const SYSSERVER_RECKON_RECORD_NONE = 301014;                                  //结算错误码:结算记录不存在
    const SYSSERVER_RECKON_RECORD_EXIST = 301015;                                 //结算错误码:结算记录已存在
    const SYSSERVER_RECKON_PASSED_BET_EXIST_AFTER_RECKON = 301016;                //结算完成后仍存在审核通过注单
    const SYSSERVER_RECKON_TASK_EXCEPTION_THROW = 301017;                         //结算任务抛出异常
    const SYSSERVER_RECKON_UPDATERECORD_EXCEPTION_THROW = 301018;                 //更新结算记录抛出异常
    const SYSSERVER_RECKON_SUCCESS_EXCEPTION_THROW = 301019;                      //结算成功抛出异常
    const SYSSERVER_RECKON_ROLLBACK_EXCEPTION_THROW = 301020;                     //数据回滚抛出异常

    //赛事模块
    const MATCH_START = 1000;
    //操盘模块
    const HANDICAP_START = 1500;
    //注单模块
    const BET_START = 2500;
    //会员模块
    const MEMBER_START = 3500;
    //代理模块
    const AGENT_START = 4500;
    //消息模块
    const MESSAGE_START = 5000;
    //报表模块
    const _START = 6000;
    //系统服务模块:
    const SYSSERVER_START = 7000;
}