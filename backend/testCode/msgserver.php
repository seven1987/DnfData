<?php
require_once ROOT_PATH . "/common/services/RabbitMqService.php";

$table = new swoole_table(1024);
$table->column('fd', swoole_table::TYPE_INT);
$table->create();

$ws = new Swoole\Websocket\Server("192.168.10.11", 9501);
$ws->table = $table;;

//client connect
$ws->on('Open', function ($ws, $request) { 

 $ws->table->set($request->fd, array('fd' => $request->fd));//��ȡ�ͻ���id����table
  echo 'connect a client';
}); 

//receive client msg
$ws->on('Message', function ($ws, $frame) {
//echo $frame->fd.":{$frame->data}";

//foreach ($ws->table as $u) {
//$ws->push($u['fd'], json_encode(["hello111", "world222"]) );//��Ϣ�㲥�����пͻ���
//    usleep(800000);
//}
});

$mqconfig = array(
        'host' => '192.168.10.11',
        'port' => '5672',
        'login' => 'guest',
        'password' => 'admin',
        'vhost'=>'/');

$rabbitObj = new common\services\RabbitMqService($mqconfig);
$rabbitObj->subscribeManageQueues();
$rabbitObj->subscribeClientQueues();

$process = new swoole_process(function($process) use ($ws,$rabbitObj) {
    while (true) {
        $msges = $rabbitObj->receiveAll();//获取所有消息
//        $msg = json_encode(["colen", "colen333"]);
//        $msg = $msgData;
		foreach ($ws->table as $u) {
            foreach ($msges as $msg){
                $ws->push($u['fd'], $msg );
            }
		}
       // echo 'p\n';
		usleep(100000);
    }
});

$ws->addProcess($process);

$ws->on('Close', function ($ws, $fd) {
    echo "client-{$fd} is closed\n";
    $ws->table->del($fd);
});

echo 'web socket start';

$ws->start();


?>