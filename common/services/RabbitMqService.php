<?php
/**
 * rabbitmq 消息服务.
 * User: colen
 * Date: 2016/12/25
 * Time: 9:41
 */

class RabbitMQException extends Exception {
    public function __construct($message, $code = 323, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ":[" . $this->getCode() . "]:" . $this->getMessage() . "\n" . "Stack:\n" . $this->getTraceAsString() . "\n";
    }
}

class RabbitMqService
{
    //消息id:下注
    const  MSG_ID_ADDBET = 1;
    //消息id:赔率变动
    const MSG_ID_ODDS_CHANGE = 2;
    //消息id:盘口状态变更
    const MSG_ID_HAN_STATUS_CHANGE = 3;
    //消息id:盘口结算请求
    const MSG_ID_HAN_RECKON_REQ = 4;
    //消息id:注单状态变动
    const MSG_ID_BET_STATUS_CHANGE = 5;
    //消息id:盘口注单新增:只发送到操盘手管理端
    const MSG_ID_SYS_BET_INFORM = 6;
    //消息id:比赛状态变更
    const MSG_ID_RACE_STATUS_CHANGE = 7;
    //消息id:websocket握手
    const MSG_ID_WEBSOCKET_HAND_UP = 8;
    //消息id:websocket心跳
    const MSG_ID_HEART_BEAT = 9;
    //消息id:后端通知前端的消息
    const MSG_ID_MESSAGE_FRONT = 10;
    //消息id:盘口注单变化(多条记录)
    const MSG_ID_HANS_BET_CHANGE = 11;
    //消息id:结算报表通知
    const MSG_ID_RECKON_REPORT = 12;
    //消息id:自动赔率变动
//    const MSG_ID_AUTO_ODDS_CHANGE = 13;
    //消息队列id:单注限额变动
    const MSG_ID_HANS_BET_AMOUNT_LIMIT_CHANGE = 14;

    //赔率变化队列.
    const QUEUE_ODDS_CHANGE = "q_odds_change";
    //下注队列.
    const QUEUE_BET_ADD = "q_bet_add";
    //盘口修改队列.
    const QUEUE_HAN_CHANGE = "q_han_change";
    //盘口修改前端接收队列.
    const QUEUE_HAN_CHANGE_FRONT = "q_han_change_front";
    //盘口修改后端接收队列.
    const QUEUE_HAN_CHANGE_BACK = "q_han_change_back";
    //盘口修改系统接收队列.
    const QUEUE_HAN_CHANGE_SYS = "q_han_change_sys";
    //盘口结果队列.
    const QUEUE_RECKON = "q_reckon";
    //报表队列.
    const QUEUE_REPORT = "q_report";
    //系统消息队列.
    const QUEUE_SYSTEM = "q_system";
    //比赛队列.
    const QUEUE_RACE = "q_race";
    //前端比赛比赛队列.
    const QUEUE_RACE_FRONT = "q_race_front";
    //后端比赛比赛队列.
    const QUEUE_RACE_BACKEND = "q_race_backend";
    //后端盘口注单队列.
    const QUEUE_BACKEND_BET = "q_backend_bet";
    //系统服务注单队列.
    const QUEUE_SYSSERVER_BET = "q_sysserver_bet";
    //消息通知队列
    const QUEUE_MESSAGE_FRONT = "q_message_front";


    //主交换机
    const EXCHANGE_MAIN = "e_main";
    //注单交换机
    const EXCHANGE_BET = "e_bet";
    //后端注单交换机
    const EXCHANGE_BACKEND_BET = "e_backend_bet";

    private $queues;
    private $exchanges;

    public function __construct($conn_args, $conn_type = 0)
    {
        //连接rabbitmq server
        $this->channel = $this->connect($conn_args, $conn_type);
        if (!$this->channel) {
            echo 'Cannot Connect to the mq server';
            throw new RabbitMQException('The Rabbitmq Server could not connected');
        }


        //赔率，结算共用一个exchange,等需要时再拆分
        $ex_main = new \AMQPExchange($this->channel);
        $ex_main->setName($this::EXCHANGE_MAIN);
        $ex_main->setType(AMQP_EX_TYPE_TOPIC); //topic 类型
        $ex_main->setFlags(AMQP_DURABLE); //持久化
        $ex_main->declareExchange();

        //注单消息大，单独交换机
        $ex_bet = new \AMQPExchange($this->channel);
        $ex_bet->setName($this::EXCHANGE_BET);
        $ex_bet->setType(AMQP_EX_TYPE_DIRECT); //direct类型,精确匹配
        $ex_bet->setFlags(AMQP_DURABLE); //持久化
        $ex_bet->declareExchange();

        //后台注单交换机，用于注单新增，注单审核
        $ex_backend_bet = new \AMQPExchange($this->channel);
        $ex_backend_bet->setName($this::EXCHANGE_BACKEND_BET);
        $ex_backend_bet->setType(AMQP_EX_TYPE_DIRECT); //direct类型,精确匹配
        $ex_backend_bet->setFlags(AMQP_DURABLE); //持久化
        $ex_backend_bet->declareExchange();

        //exchange init:
        $this->exchanges = array(
            $this::EXCHANGE_MAIN => $ex_main,
            $this::EXCHANGE_BET => $ex_bet,
            $this::EXCHANGE_BACKEND_BET => $ex_backend_bet
        );
    }

    /**
     *链接RabbitMq
     * @param $conn_args
     * @param $conn_type
     * @return AMQPChannel
     */
    protected function connect($conn_args, $conn_type = 0)
    {
        $conn = new \AMQPConnection($conn_args);
        if ($conn_type == 1) {
            $conn->pdisconnect();
            if (!$conn->pconnect()) {
                return false;
            }
        } else {
            if (!$conn->connect()) {
                return false;
            }
        }
        $channel = new \AMQPChannel($conn);
        return $channel;
    }


    /*
     *订阅队列
     */
    public function subscribeQueue($q_name, $queue_key = '')
    {
        $channel = $this->channel;
        $q = new \AMQPQueue($channel);
        $q->setName($q_name);
        $q->setFlags(AMQP_DURABLE);
        $q->declareQueue();
        $bindExchange = static::EXCHANGE_MAIN;
        switch ($q_name) {
            case $this::QUEUE_BET_ADD:                  //注单提交, 注单交换机
                $bindExchange = static::EXCHANGE_BET;
                break;
            case $this::QUEUE_BACKEND_BET:              //盘口注单修改，盘口注单交换机
            case $this::QUEUE_SYSSERVER_BET:
                $bindExchange = static::EXCHANGE_BACKEND_BET;
                break;
        }
        if (strlen($queue_key) == 0) {
            $queue_key = $q_name;
        }
        $q->bind($bindExchange, $queue_key);

        //queues init:
        if (!$this->queues)
            $this->queues = array();
        $this->queues[$q_name] = $q;
    }

    /*
     *发布消息
     */
    public function publishMain($message, $q_name)
    {
        return $this->exchanges[$this::EXCHANGE_MAIN]->publish($message, $q_name);
    }

    /*
     *发布后端注单消息
     */
    public function publishBackendBet($message, $q_name)
    {
        return $this->exchanges[$this::EXCHANGE_BACKEND_BET]->publish($message, $q_name);
    }

    /*
     *获取RabbitMq Message
     */
    public function receive($q_name)
    {
        try {
            $q = $this->queues[$q_name];
            if (!$q) {
                echo 'queue does not exist';
                return '';
            }

            $msg = $q->get(AMQP_AUTOACK);
            if (is_object($msg))
                return $msg->getBody();
        } catch (Exception $e) {
            throw new RabbitMQException("receive error", 1, $e);
        }
    }

    /*
     *获取带deliveryTag JSON format RabbitMq Message
     */
    public function receiveMsg($q_name)
    {
        try {
            $q = $this->queues[$q_name];
            if (!$q) {
                // echo 'queue does not exist';
                return null;
            }

            $envelope = $q->get();
            if (is_object($envelope)) {
                $msg = json_decode($envelope->getBody(), true);
                $msg["deliveryTag"] = $envelope->getDeliveryTag();
                return $msg;
            }
        } catch (Exception $e) {
            throw new RabbitMQException("receiveMsg error", 2, $e);
        }
    }

    /*
     *根据q_name,deliveryTag做ack
     */
    public function doAck($q_name, $deliveryTag)
    {
        try {
            $q = $this->queues[$q_name];
            if (!$q) {
                // echo 'queue does not exist';
                return;
            }
            $q->ack($deliveryTag);
        } catch (Exception $e) {
            throw new RabbitMQException("doAck error", 3, $e);
        }
    }

    /*
     *根据q_name,deliveryTag做ack
     */
    public function doNack($q_name, $deliveryTag)
    {
        try {
            $q = $this->queues[$q_name];
            if (!$q) {
                // echo 'queue does not exist';
                return;
            }
            $q->nack($deliveryTag);
        } catch (Exception $e) {
            throw new RabbitMQException("doNack error", 4, $e);
        }
    }

    /*
     *获取所有队列RabbitMq Message
     */
    public function receiveAll()
    {
        $messages = array();
        foreach ($this->queues as $q) {
            //  echo 'a\n';
            $message = $q->get(AMQP_AUTOACK);
            if (is_object($message))
                array_push($messages, $message->getBody());
        }
        // array_push($messages,$this->receiveHanResult());
        return $messages;
    }

    /*
     *订阅客户端队列
     */
    public function subscribeFrontendQueues()
    {
        $this->subscribeQueue($this::QUEUE_ODDS_CHANGE);
        $this->subscribeQueue($this::QUEUE_HAN_CHANGE_FRONT, $this::QUEUE_HAN_CHANGE);
        $this->subscribeQueue($this::QUEUE_MESSAGE_FRONT);
        $this->subscribeQueue($this::QUEUE_RACE_FRONT,$this::QUEUE_RACE);
    }

    /*
     *订阅管理段队列
     */
    public function subscribeBackendQueues()
    {
        $this->subscribeQueue($this::QUEUE_BACKEND_BET);
        $this->subscribeQueue($this::QUEUE_HAN_CHANGE_BACK, $this::QUEUE_HAN_CHANGE);
        $this->subscribeQueue($this::QUEUE_SYSTEM);
        $this->subscribeQueue($this::QUEUE_RACE_BACKEND,$this::QUEUE_RACE);
    }

    /*
     *订阅系统服务队列
     */
    public function subscribeSysQueues()
    {
        $this->subscribeQueue($this::QUEUE_BET_ADD);
        $this->subscribeQueue($this::QUEUE_SYSSERVER_BET);
        $this->subscribeQueue($this::QUEUE_RECKON);
        $this->subscribeQueue($this::QUEUE_HAN_CHANGE_SYS, $this::QUEUE_HAN_CHANGE);
    }

    /*
     *赔率变化消息发送
     */
    public function publishOddsChange($message)
    {
        return $this->publishMain($message, $this::QUEUE_ODDS_CHANGE);
    }

    /*
     *盘口赛果发生变化消息推送接口
     */
    public function publishHanResult($message)
    {
        return $this->publishMain($message, $this::QUEUE_RECKON);
    }

    /*
     *盘口结算失败消息发送
     */
    public function publishReckon($message)
    {
        return $this->publishMain($message, $this::QUEUE_SYSTEM);
    }

    /*
     *盘口结算完成，通知报表处理消息接口
     */
    public function publishReckonReport($message)
    {
        return $this->publishMain($message, $this::QUEUE_REPORT);
    }

    /*
     *比赛状态发生变化消息推送接口
     */
    public function publishRaceChange($message)
    {
        return $this->publishMain($message, $this::QUEUE_RACE);
    }

    /*
     *盘口状态发生变化消息推送接口
     */
    public function publishHanChange($message)
    {
        return $this->publishMain($message, $this::QUEUE_HAN_CHANGE);
    }

    /*
     *注单限额发生变化消息推送接口
     */
    public function publishBetLimitAmountChange($message)
    {
        return $this->publishMain($message, $this::QUEUE_HAN_CHANGE);
    }

    /*
    * 给用户端发送实时消息的消息推送接口
    */
    public function publishUserMessage($message)
    {
        return $this->publishMain($message, $this::QUEUE_MESSAGE_FRONT);
    }

    /*
     *下注消息发送
     */
    public function publishAddBet($message)
    {
        return $this->exchanges[$this::EXCHANGE_BET]->publish($message, $this::QUEUE_BET_ADD);
    }

    /*
     *管理端消息接收
     */
    public function receiveBackendMsgs()
    {
        $messages = "";
        $msg = $this->receive($this::QUEUE_HAN_CHANGE_BACK);
        if ($msg) {
            $messages .= $msg;
        }
        $msg = $this->receive($this::QUEUE_SYSTEM);
        if ($msg) {
            if (strlen($messages) > 0)
                $messages .= ",";
            $messages .= $msg;
        }

        $msg = $this->receive($this::QUEUE_RACE_BACKEND);
        if ($msg) {
            if (strlen($messages) > 0)
                $messages .= ",";
            $messages .= $msg;
        }
        return $messages;
    }

    /*
     *管理端系统消息接收
     */
    public function receiveBackendSysMsg()
    {
        $messages = "[";
        $msg = $this->receive($this::QUEUE_SYSTEM);
        if (!empty($msg))
            $messages .= $msg;
        $messages .= "]";
        return $messages;
    }

    /*
     *会员端消息接收
     */
    public function receiveSysMsgs()
    {
        $messages = "[";
        $msg = $this->receive($this::QUEUE_BET_ADD);
        if (!empty($msg))
            $messages .= $msg;
        $msg = $this->receive($this::QUEUE_RECKON);
        if (!empty($msg)) {
            if (strlen($messages) > 1)
                $messages .= ",";
            $messages .= $msg;
        }
        $msg = $this->receive($this::QUEUE_HAN_CHANGE_SYS);
        if (!empty($msg)) {
            if (strlen($messages) > 1)
                $messages .= ",";
            $messages .= $msg;
        }
        $messages .= "]";
        return $messages;
    }

    /*
     *会员端消息接收
     */
    public function receiveFrontendMsgs()
    {
        $messages = "";
        $msg = $this->receive($this::QUEUE_ODDS_CHANGE);
        if (!empty($msg))
            $messages .= $msg;
        $msg = $this->receive($this::QUEUE_HAN_CHANGE_FRONT);
        if (!empty($msg)) {
            if (strlen($messages) > 1)
                $messages .= ",";
            $messages .= $msg;
        }
        $msg = $this->receive($this::QUEUE_MESSAGE_FRONT);
        if (!empty($msg)) {
            if (strlen($messages) > 1)
                $messages .= ",";
            $messages .= $msg;
        }
        $msg = $this->receive($this::QUEUE_RACE_FRONT);
        if (!empty($msg)) {
            if (strlen($messages) > 1)
                $messages .= ",";
            $messages .= $msg;
        }
        return $messages;
    }

    /**
     * only use for test
     */
//    public function closeConnect()
//    {
//        $this->channel->getConnection()->pdisconnect();
//    }

}
