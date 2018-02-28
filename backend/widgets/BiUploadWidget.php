<?php
namespace backend\widgets;


use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;

class BiUploadWidget extends Widget{

    public $name;
    public $id;
    public $message;

    public function run(){
        $this->message = empty($this->message)?"相同图片会被替换":$this->message;
        $this->name    = empty($this->name)?"file_upload":$this->name;

        $content = '<div style="width:100%;height:100%;position:relative;">';
        $content .= '<button style="border-radius: 10px 10px;padding:8px;background-color:#000;color:#fff;">点击上传</button>';
        $content .= '<input type="file" name="'.$this->name.'" id="'.$this->id.'" class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" style="width:75px;height:38px;opacity:0;position: absolute;top: 0px;left:0px;z-index:100;" />';
        $content .='<br><span style="display: inline-block; color: rgb(181,190,210);padding-top: 8px;">'.$this->message.'</span>';
        $content .= '</div>';

        return $content;
    }

}