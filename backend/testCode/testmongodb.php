<?php
/*************************************
 * PHP amqp(RabbitMQ) Demo - publisher
 * Author: Linvo
 * Date: 2012/7/30
 *************************************/
//配置信息
echo "bbb";
//echo $redis->get('library');
$connectDSN = "mongodb://192.168.10.200:27017";

try {
//    $db_dm_game = new MongoDB\Driver\Manager("mongodb://root:dm_game123456@192.168.10.200:27017/dm_game");
//    $db_dm_member = new Mongo("mongodb://root:dm_member123456@192.168.10.200:27017/dm_member");
//    $db_dm_data = new Mongo("mongodb://root:dm_data123456@192.168.10.200:27017/dm_data");
//    $db_dm_admin = new Mongo("mongodb://root:dm_admin123456@192.168.10.200:27017/dm_admin");
    $conn = new MongoDB\Driver\Manager($connectDSN);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY);

    echo "success connect sql\n";
} catch (PDOException $e) {
    echo $e->getMessage();
    return;
}

//获取已开盘并显示的盘口数据
function getHandicapData($conn)
{
    $query = new MongoDB\Driver\Query([]);
    $cursor = $conn->executeQuery('dm_data.handicap', $query);
    //$cursor->toArray()
    return $cursor;
}

$method = $_GET['method'];
$method = (int)$method;
switch ($method){
    case 1:         //load
        testLoad();
        break;
    case 2:
        testInsert();
        break;
}

function testInsert(){
    Global $conn;
    Global $writeConcern;

    $test = ["name"=>"333"];
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert($test);
    $result = $conn->executeBulkWrite('dm_admin.test', $bulk, $writeConcern);
}

function testLoad(){
    Global $conn;

    $handicapData = getHandicapData($conn);

    foreach ($handicapData as $data) {
    echo json_encode($data)."\n";
    }

}
?>