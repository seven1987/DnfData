<?php
namespace backend\widgets;


use yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;

class UploadWidget extends Widget{

    public $name;
    public $id;
    public $message;

    public function run(){
        $this->message = empty($this->message)?"相同图片会被替换":$this->message;
        $this->name    = empty($this->name)?"file_upload":$this->name;
        $this->id      = empty($this->id)?"file_upload":$this->id;

        $content = '';
        $content .= '<div id="queue"></div>';
        $content .='<input id="'.$this->id.'" name="'.$this->name.'" type="file" >';
        $content .='<span style="display: inline-block; color: rgb(181,190,210);">'.$this->message.'</span>';
        return $content;
    }

    public static function creatediv(){
        return "ddd";
    }

}