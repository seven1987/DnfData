<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2016/12/31
 * Time: 18:12
 */
use Yii;

$mail= Yii::$app->mailer->compose();
$mail->setTo('781550090@qq.com');
$mail->setSubject("邮件测试");
//$mail->setTextBody('zheshisha ');   //发布纯文字文本
$mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
if($mail->send())
    echo "success";
else
    echo "failse";
die();

?>