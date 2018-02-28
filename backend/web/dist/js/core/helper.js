/**
 * JavaScript Helper Utils
 *
 * 自定义的工具帮助类，包含一些常用的全局函数
 *
 * @author : zhenda.li
 * @date : 2016.12.15
 */

var isFunction = function (target) {
    return typeof(target) === "function";
};

var isNumber = function (target) {
    return typeof(target) === "number";
};

var isString = function (target) {
    return typeof(target) === "string";
};

var isBoolean = function (target) {
    return typeof(target) === "boolean";
};

var isObject = function (target) {
    return typeof(target) === "object";
};

var isUndefined = function (target) {
    return typeof(target) === "undefined";
};

var isNull = function (target) {
    return target === null;
};

var isEmpty = function (value) {
    if (value == null) return true;

    var type;
    type = Object.prototype.toString.call(value).slice(8, -1);
    switch (type) {
        case 'String':
            return !$.trim(value);
        case 'Array':
            return !value.length;
        case 'Object':
            return $.isEmptyObject(value);
        default:
            return false;
    }
};

/**
 * 断言
 */
var assert = function (expression, message) {
    console.assert(expression, message);
};

/**
 * 控制台打印日志，可以是控制是否生效
 */
var log = function (/* ... */) {
    console.log.apply(console, arguments);
};

/**
 * 重写提示窗口
 */
var alert = function (message) {
    layui.use(['layer'], function () {
        layui.layer.alert("<span id='alert_message'>" + message + "</span>", {
            title: '提示',
            icon: 0,
            anim: 4,
            closeBtn: 0
        });
    });
};

/**
 * 自动消失的提示框
 */
var tip = function (message) {
    layui.use(['layer'], function () {
        layui.layer.msg(message, {
            time: 2000,
            anim: 3
        });
    });
};

/**
 * 字符串前面补零
 * e.g : pad(100, 4) => '0100'
 */
var pad = function (tb) {
    return function (num, n) {
        return (0 >= (n = n - num.toString().length)) ? num : (tb[n] || (tb[n] = new Array(n + 1).join('0'))) + num;
    }
}([]);

/**
 * 获取当前 URL 参数值
 *
 * e.g : var id = getURLParam('id');
 *
 * @param {string} name 参数名称
 * @return {string} 参数值
 *
 */
var getURLParam = function(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
};

/**
 * 跳转到页面顶部
 */
var jumpTop = function () {
    scrollTo(0, 0);
};

/**
 * 对Date的扩展，将 Date 转化为指定格式的String
 * 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
 *
 * e.g : (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") => 2006-07-02 08:09:04.423
 *       (new Date()).Format("yyyy-M-d h:m:s.S")      => 2006-7-2 8:9:4.18
 */
Date.prototype.format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S": this.getMilliseconds()
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};

/**
 * 判断传入的时间是否是今天
 */
var isToday = function (date) {
    return new Date().toLocaleDateString() == date.toLocaleDateString();
};

$.format = function (source, params) {
    if (arguments.length == 1)
        return function () {
            var args = $.makeArray(arguments);
            args.unshift(source);
            return $.format.apply(this, args);
        };
    if (arguments.length > 2 && params.constructor != Array) {
        params = $.makeArray(arguments).slice(1);
    }
    if (params.constructor != Array) {
        params = [params];
    }
    $.each(params, function (i, n) {
        source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
    });
    return source;
};