<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/2/25
 * Time: 14:35
 */

namespace backend\widgets;

use yii\base\Widget;
use yii\bootstrap\Html;

class SelectorWidget extends Widget
{
    public $name;
    public $selectorName;
    public $placeHolder;
    public $optionList;
    public $defaultOption;
    public $type = 0;

    const WIDGET_TYPE_SELECTOR = 0;
    const WIDGET_TYPE_INPUT = 1;

    public function run()
    {
        return implode("\n", [
            Html::beginTag("div", ['class' => 'form-group', 'style' => 'margin: 5px']),

            Html::beginTag("label"),
            $this->name . ':',
            Html::endTag("label"),

            $this->renderWidget(),

            Html::endTag("div"),
        ]);
    }

    public function renderWidget()
    {
        if ($this->type == SelectorWidget::WIDGET_TYPE_SELECTOR)
            return $this->renderSelector();
        elseif ($this->type == SelectorWidget::WIDGET_TYPE_INPUT)
            return $this->renderInputLabel();
    }

    public function renderSelector()
    {
        $selector = array();
        $selector[] = Html::beginTag("select", [
            'type' => 'text',
            'class' => 'form-control',
            'onchange' => ($this->selectorName == "query[game_id]") ? 'changeGame(this.value, "handicap");' : 'submitform();',
            'name' => $this->selectorName,
        ]);

        $options = array();
        if (empty($this->defaultOption)) {
            $default = [
                Html::beginTag("option", ['value' => ""]),
                $this->placeHolder,
                Html::endTag("option"),
            ];
            $options[] = implode($default);
        }

        foreach ($this->optionList as $value) {
            $optionAttr = ['value' => $value['option']];
            if ($value['select'] != "") {
                $optionAttr['selected'] = $value['select'];
            }
            $result = [
                Html::beginTag("option", $optionAttr),
                $value['name'],
                Html::endTag("option"),
            ];
            $options[] = implode("\n", $result);
        }
        $selector[] = implode("\n", $options);
        $selector[] = Html::endTag("select");
        return implode("\n", $selector);
    }

    public function renderInputLabel()
    {
        $label = array();
        $label[] = Html::beginTag("input", [
            'type' => 'text',
            'class' => 'form-control searchname',
            'name' => $this->selectorName,
            'placeHolder' => $this->placeHolder,
            'value' => $this->optionList
        ]);

        $selector[] = Html::endTag("input");
        return implode("\n", $label);
    }
}