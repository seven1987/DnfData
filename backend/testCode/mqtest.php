<?php
require_once ROOT_PATH . "/common/services/RabbitMqService.php";

/*************************************
 * PHP amqp(RabbitMQ) Demo - consumer
 * Author: Linvo
 * Date: 2012/7/30
 *************************************/
//配置信息
$conn_args = array(
    'host' => '192.168.10.11',
    'port' => '5672',
    'login' => 'guest',
    'password' => 'admin',
    'vhost'=>'/'
);
 $service = new RabbitMqService($conn_args);
$service->subscribeFrontendQueues();     //会员端订阅;
$service->subscribeBackendQueues();      //管理段订阅;

//会员下注通知:
$bet = array('msg_id'=>RabbitMqService::MSG_ID_ADDBET,'han_id'=>1,'amount'=>300,'user_id'=>1,'part_id'=>2);
//$service->publishAddBet(json_encode($bet));
//echo 'bet added '.$service->receiveAddBet();

//盘口赔率变化通知:
$oddsChange = array('msg_id'=>RabbitMqService::MSG_ID_ODDS_CHANGE,'han_id'=>1,'part_id'=>2,'part_odds'=>3.0);
$service->publishOddsChange(json_encode($oddsChange));
//echo 'odds change '.$service->receiveOddsChange();

//结算结果通知
$hanReckon = array('msg_id'=>RabbitMqService::MSG_ID_HAND_RESULT,'han_id'=>11,'part_id'=>1);
$service->publishHanResult(json_encode($hanReckon));
echo 'send a msg ';


?>