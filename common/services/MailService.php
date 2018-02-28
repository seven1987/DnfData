<?php

namespace common\services;

use Yii;
use backend\models\adminUser;
use common\models\User;
//sendmailservice
class MailService
{
    /**
     * 用户找回密码发送邮件
     * @param $id
     * @param string $title
     * @param string $url
     * @return bool
     */
    public static function sendResetPwd($id,$title="",$url="",$type=0){
          if(empty($id)){
            return false;
          }
          if($type==0){
              $adminUser =AdminUser::find()->where(['id'=>(string)$id])->one();
              if(empty($adminUser)){
                  return false;
              }
              $body = static::resetPwdTep($adminUser->uname,$url);
              self::sendMail($adminUser->email,$body,$title);
          }else{
              $User =User::find()->where(['user_id'=>(string)$id])->one();
              if(empty($User)){
                  return false;
              }
              $body = static::resetPwdTep($User->username,$url);
              self::sendMail($User->qq."@qq.com",$body,$title);
          }

    }

    /**
     * 发送邮件
     * @param $email
     * @param $content
     * @param $title
     * @return bool
     */
    public static function sendMail($email,$content,$title)
    {
        if(empty($email) || empty($content) || empty($title)){
            return false;
        }
        $mail = Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject($title);
        $mail->setHtmlBody($content);
        if($mail->send()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 用户找回密码发送模板
     * @param string $name
     * @param string $url
     * @return string
     */
    public static function resetPwdTep($name,$url){
        $string = "";
        $string .= $name." 您好"."<br/>";
        $string .= "您已经进行了密码重置的操作,请点击以下链接(或复制到您的浏览器):"."<br/>";
        $string .= $url."<br/>";
        $string .= "已确认您的新密码重置操作"."<br/>";
        $string .= date("Y-m-d",time());
        return $string;
    }

}
