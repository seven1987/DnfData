/**
 * JavaScript Event Manager
 *
 * 全局事件管理器，多对多，可以注册事件，回调事件
 *
 * e.g : 添加 -> Event.add(Event.XXX, callback); 一个名字对应多个事件回调
 *       删除 -> Event.remove(Event.XXX, index); 删除事件，index 不传则删除所有，传了则删除单个
 *       派发 -> Event.dispatch(Event.XXX);
 *
 * @author : zhenda.li
 * @date : 2016.12.16
 */

var Event = Class.extend({
    BET_VIEW_ADD_SINGLE : 'BET_VIEW_ADD_SINGLE',
    BET_VIEW_REMOVE_SINGLE : 'BET_VIEW_REMOVE_SINGLE',
    BET_VIEW_ADD_STRING : 'BET_VIEW_ADD_STRING',
    BET_VIEW_REMOVE_STRING : 'BET_VIEW_REMOVE_STRING',
    USER_VIEW_UPDATE_BALANCE : 'USER_VIEW_UPDATE_BALANCE',

    RACE_VIEW_UPDATE_PART_SELECT : 'RACE_VIEW_UPDATE_PART_SELECT',

    //page component event
    PAGE_CHANGE: 'PAGE_CHANGE',

//    socket event
    WEB_SOCKET_OPEN: 'WEB_SOCKET_OPEN',
    WEB_SOCKET_CLOSE: 'WEB_SOCKET_CLOSE',
    WEB_SOCKET_MESSAGE: 'WEB_SOCKET_MESSAGE',
    WEB_SOCKET_ERROR: 'WEB_SOCKET_ERROR',
    WEB_SOCKET_UPDATE_ODDS: 'WEB_SOCKET_UPDATE_ODDS',   //更新赔率
    // WEB_SOCKET_UPDATE_AUTO_ODDS: 'WEB_SOCKET_UPDATE_AUTO_ODDS',   //更新自动赔率
    WEB_SOCKET_GAME_RESULT: 'WEB_SOCKET_GAME_RESULT',   //更新赛果
    WEB_SOCKET_ADD_BET: 'WEB_SOCKET_ADD_BET',            //有新的注单
    WEB_SOCKET_HANDICAP_CHANGE: 'WEB_SOCKET_HANDICAP_CHANGE',//盘口状态变更
    WEB_SOCKET_SYS_BET_INFORM:'WEB_SOCKET_SYS_BET_INFORM',//盘口注单新增:只发送到操盘手管理端
    WEB_SOCKET_HANS_BET_CHANGE:'WEB_SOCKET_HANS_BET_CHANGE',//消息id:盘口注单变化(多条记录)
    WEB_SOCKET_RACE_STATUS_CHANGE:'WEB_SOCKET_RACE_STATUS_CHANGE'//
});

Event = Event.extend({

    _events: [],

    /**
     * 添加事件
     *
     * @param {string} name 事件名。格式：Event.XXX，在 Event 中预定义
     * @param {Class} self 目标对象
     * @param {function} callback 回调函数
     */
    add: function (name, self, callback) {
        assert(!isUndefined(this[name]), "[Event] (add) The name is undefined, you need register in Event Class first.");

        if (isUndefined(this[name])) return;

        if (!this._events[name]) {
            this._events[name] = [];
        }

        this._events[name][self._uid] = {self: self, callback: callback};
    },

    /**
     * 删除事件
     *
     * @param name 事件名。格式：Event.XXX，在 Event 中预定义
     * @param self [Class] (可选) 目标对象，不传则删除整个事件名回调数组，传了则删除该事件名数组中的对应回调
     */
    remove: function (name, self) {
        assert(!isUndefined(this[name]), "[Event] (add) The name is undefined, you need register in Event Class first.");

        if (isUndefined(this[name]) || isUndefined(this._events[name])) return;

        if (isUndefined(self)) {
            delete this._events[name];
        } else {
            if (isUndefined(this._events[name][self._uid]) || this._events[name][self._uid].self !== self) return;

            delete this._events[name][self._uid];
        }
    },

    /**
     * 派发事件，一对多，多对多
     *
     * @param name 事件名
     * @param ... 可以变参数列表，可以传多个或不传
     */
    dispatch: function (name /* ... */) {
        //assert(!isUndefined(this[name]), "[Event] (dispatch) The name is undefined, you need register in Event Class first.");
        //assert(!isUndefined(this._events[name]), "[Event] (dispatch) You need call Event.add() first.");

        if (isUndefined(this[name]) || isUndefined(this._events[name])) return;

        var params = [];
        for (var i in arguments) {
            if (i < 1) continue;
            params.push(arguments[i]);
        }

        for (var key in this._events[name]) {
            var event = this._events[name][key];
            event.callback.apply(event.self, params);
        }
    }

});

Event = new Event();