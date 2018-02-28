<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2016/12/31
 * Time: 18:12
 */
$conn = new \Riak\Connection("192.168.10.200", 8098);

$bucket = new \Riak\Bucket($conn, 'Bucket1');

// 设置属性,关闭siblings
$newProps = new \Riak\BucketPropertyList();
$newProps->setAllowMult(0);
$bucket->setPropertyList($newProps);

// 写入数据
$keyName = '/'.time().'/gg.gif';
$obj = new \Riak\Object($keyName);
$content = "aaaaaa";
$obj->setContent($content);
$obj->setContentType("text");
$bucket->put($obj);

echo 'finish';
?>