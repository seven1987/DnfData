<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/31 0031
 * Time: 上午 9:41
 */
namespace backend\controllers;
use yii\base\Behavior;
use yii\web\Controller;

class NoCsrf extends Behavior{
    public $actions = [];
    public $controller;
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if(in_array($action, $this->actions)){
            $this->controller->enableCsrfValidation = false;
        }
    }
}