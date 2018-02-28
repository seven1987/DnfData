/**
 * WebSocket Function
 *
 * 管理 WebSocket, 包含心跳机制, 检测连接失效后重建连接
 *
 * @author : xiaoda.zhuang, zhenda.li
 * @date : 2016.1.11
 */

var SocketMsgID = {
    MSG_ID_ADDBET: 1,              // 增加注单
    UPDATE_ODDS: 2,                // 赔率数值更新
    UPDATE_HANDICAP_STATUS: 3,     // 盘口状态更新

    MSG_ID_SYS_BET_INFORM:6,     //盘口注单新增:只发送到操盘手管理端
    MSG_ID_RACE_STATUS_CHANGE:7, //比赛状态变化
    MSG_ID_HANS_BET_CHANGE:11, //消息id:盘口注单变化(多条记录)

    // MSG_ID_UPDATE_AUTO_ODDS:13, //消息id: 自动赔率数值更新

    MSG_ID_WEBSOCKET_HAND_UP: 8 //websocket握手
};

var Socket = Class.extend({

    KEEP_ALIVE_TIME: 60000,          // 服务器发送心跳时间, 60秒
    KEEP_ALIVE_INTERVAL: 30000,      // 客户端检测心跳延时, 30秒

    lastAliveTime: 0,               // 上次心跳接收到的时间

    isConnect: false,               // 是否连接

    /**
     * @global url 服务器传来的地址, 在 index.html 中初始化
     */
    ctor: function () {
        this.init();
    },

    init: function () {
        this.isConnect =false;
        this.lastAliveTime = 0;

        this.socket = new WebSocket(url);

        this.socket.onopen = function (event) {
            log('[Socket] (open) event =', event);
            Socket.isConnect = true;
            var data = {"msg_id":SocketMsgID.MSG_ID_WEBSOCKET_HAND_UP, "user_id":user_id, "sessionId":sessionId, "token":token };
            Socket.send(JSON.stringify(data));
        };

        this.socket.onmessage = function (event) {
            log('[Sockets] (message) event =', event);
            Socket.check();

            var data = JSON.parse(event.data);
            for (var i = 0; i < data.length; i++) {
                switch (Number(data[i]['msg_id'])) {
                    case SocketMsgID.MSG_ID_ADDBET:
                        Event.dispatch(Event.WEB_SOCKET_ADD_BET, data[i]);
                        break;
                    case SocketMsgID.UPDATE_ODDS:
                        Event.dispatch(Event.WEB_SOCKET_UPDATE_ODDS, data[i]);
                        break;
                    case SocketMsgID.UPDATE_HANDICAP_STATUS:
                        Event.dispatch(Event.WEB_SOCKET_HANDICAP_CHANGE, data[i]);
                        break;
                    case SocketMsgID.MSG_ID_SYS_BET_INFORM:
                        Event.dispatch(Event.WEB_SOCKET_SYS_BET_INFORM, data[i]);
                        break;
                    case SocketMsgID.MSG_ID_HANS_BET_CHANGE:
                        Event.dispatch(Event.WEB_SOCKET_HANS_BET_CHANGE, data[i]);
                        break;
                    case SocketMsgID.MSG_ID_RACE_STATUS_CHANGE:
                        Event.dispatch(Event.WEB_SOCKET_RACE_STATUS_CHANGE, data[i]);
                        break;
                    // case SocketMsgID.MSG_ID_UPDATE_AUTO_ODDS:
                    //     Event.dispatch(Event.WEB_SOCKET_UPDATE_AUTO_ODDS, data[i]);
                    //     break;
                }
            }
        };

        this.socket.onerror = function (event) {
            log('[Socket] (error) event =', event);
            Socket.isConnect = false;
        };

        this.socket.onclose = function (event) {
            log('[Socket] (close) event =', event);
            Socket.isConnect = false;
        };
    },

    send: function (data) {
        if (this.isConnect) {
            log('[Socket] (send) data =', data);
            this.socket.send(data);
        }
    },

    close: function () {
        if (this.socket) {
            log('[Socket] (close)');
            this.socket.close();
        }
    },

    check: function () {
        if (this.lastAliveTime == 0) {
            log('[Socket] (check) start keep');
            setInterval(function () {
                Socket.keep()
            }, this.KEEP_ALIVE_TIME);
        }
        this.lastAliveTime = new Date().getTime();
    },

    keep: function () {
        var interval = new Date().getTime() - this.lastAliveTime;
        if (interval > this.KEEP_ALIVE_TIME + this.KEEP_ALIVE_INTERVAL) {
            log('[Socket] (keep) is die!');
            this.close();
            this.init();
        }
    }

});

Socket = new Socket();