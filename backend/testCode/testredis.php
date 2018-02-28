<?php
/*************************************
 * PHP amqp(RabbitMQ) Demo - publisher
 * Author: Linvo
 * Date: 2012/7/30
 *************************************/
//配置信息

//实例化
     $redis = new Redis();
     //连接数据库
     $redis->connect('192.168.10.11',6381);

echo "Server is running: " . $redis->ping();

$doc = array("ccc"=>123);

$t1 = microtime(true);
        for ($i=0;$i<5;$i++) {
//            $collection->insert($doc);
            $redis->set('ttttt',json_encode($doc));
        }
$t2 = microtime(true);
$data = $redis->get('ttttt');;
echo 'cost time '.round($t2-$t1,3).'second,data:'.$data;

//echo $redis->get('library');

?>