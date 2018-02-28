<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2017
 * @version   1.8.9
 */

namespace backend\widgets\kartik\base;

use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;

/**
 * WidgetTrait manages all methods used by Krajee widgets and input widgets.
 *
 * @property boolean $enablePopStateFix
 * @property string $pluginName
 * @property string $pluginDestroyJs
 * @property array  $options
 * @property array  $pluginOptions
 * @property array  $_encOptions
 * @property string $_hashVar
 * @property string $_dataVar
 *
 * @method View getView()
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.6.0
 */
trait WidgetTrait
{
    /**
     * Sets a HTML5 data variable.
     *
     * @param string $name the plugin name
     */
    protected function setDataVar($name)
    {
        /** @noinspection PhpUndefinedFieldInspection */
        $this->_dataVar = "data-krajee-{$name}";
    }


    /**
     * Generates the `pluginDestroyJs` script if it is not set.
     */
    protected function initDestroyJs()
    {
        if (isset($this->pluginDestroyJs)) {
            return;
        }
        if (empty($this->pluginName)) {
            $this->pluginDestroyJs = '';
            return;
        }
        $id = "jQuery('#" . $this->options['id'] . "')";
        $plugin = $this->pluginName;
        $this->pluginDestroyJs = "if ({$id}.data('{$this->pluginName}')) { {$id}.{$plugin}('destroy'); }";
    }

    /**
     * Adds an asset to the view.
     *
     * @param View   $view the View object
     * @param string $file the asset file name
     * @param string $type the asset file type (css or js)
     * @param string $class the class name of the AssetBundle
     */
    protected function addAsset($view, $file, $type, $class)
    {
        if ($type == 'css' || $type == 'js') {
            $asset = $view->getAssetManager();
            $bundle = $asset->bundles[$class];
            if ($type == 'css') {
                $bundle->css[] = $file;
            } else {
                $bundle->js[] = $file;
            }
            $asset->bundles[$class] = $bundle;
            $view->setAssetManager($asset);
        }
    }

    /**
     * Generates a hashed variable to store the pluginOptions. The following special data attributes will also be setup
     * for the input widget, that can be accessed through javascript :
     *
     * - 'data-krajee-{name}' will store the hashed variable storing the plugin options. The `{name}` token will be
     *   replaced with the plugin name (e.g. `select2`, ``typeahead etc.). This fixes
     *   [issue #6](https://github.com/kartik-v/yii2-krajee-base/issues/6).
     *
     * @param string $name the name of the plugin
     */
    protected function hashPluginOptions($name)
    {
        $this->_encOptions = empty($this->pluginOptions) ? '' : Json::htmlEncode($this->pluginOptions);
        $this->_hashVar = $name . '_' . hash('crc32', $this->_encOptions);
        $this->options['data-krajee-' . $name] = $this->_hashVar;
    }

    /**
     * Registers plugin options by storing within a uniquely generated javascript variable.
     *
     * @param string $name the plugin name
     */
    protected function registerPluginOptions($name)
    {
        $this->hashPluginOptions($name);
        $encOptions = empty($this->_encOptions) ? '{}' : $this->_encOptions;
        $this->registerWidgetJs("window.{$this->_hashVar} = {$encOptions};\n", $this->hashVarLoadPosition);
    }

    /**
     * Returns the plugin registration script.
     *
     * @param string $name the name of the plugin
     * @param string $element the plugin target element
     * @param string $callback the javascript callback function to be called after plugin loads
     * @param string $callbackCon the javascript callback function to be passed to the plugin constructor
     *
     * @return string the generated plugin script
     */
    protected function getPluginScript($name, $element = null, $callback = null, $callbackCon = null)
    {
        $id = $element ? $element : "jQuery('#" . $this->options['id'] . "')";
        $script = '';
        if ($this->pluginOptions !== false) {
            $this->registerPluginOptions($name);
            $script = "{$id}.{$name}({$this->_hashVar})";
            if ($callbackCon != null) {
                $script = "{$id}.{$name}({$this->_hashVar}, {$callbackCon})";
            }
            if ($callback != null) {
                $script = "jQuery.when({$script}).done({$callback})";
            }
            $script .= ";\n";
        }
        $script = $this->pluginDestroyJs . "\n"  . $script;
        if (!empty($this->pluginEvents)) {
            foreach ($this->pluginEvents as $event => $handler) {
                $function = $handler instanceof JsExpression ? $handler : new JsExpression($handler);
                $script .= "{$id}.on('{$event}', {$function});\n";
            }
        }
        return $script;
    }

    /**
     * Registers a specific plugin and the related events
     *
     * @param string $name the name of the plugin
     * @param string $element the plugin target element
     * @param string $callback the javascript callback function to be called after plugin loads
     * @param string $callbackCon the javascript callback function to be passed to the plugin constructor
     */
    protected function registerPlugin($name, $element = null, $callback = null, $callbackCon = null)
    {
        $script = $this->getPluginScript($name, $element, $callback, $callbackCon);
        $this->registerWidgetJs($script);
    }

    /**
     * Registers a JS code block for the widget.
     *
     * @param string  $js the JS code block to be registered
     * @param integer $pos the position at which the JS script tag should be inserted in a page. The possible values
     * are:
     * - [[View::POS_HEAD]]: in the head section
     * - [[View::POS_BEGIN]]: at the beginning of the body section
     * - [[View::POS_END]]: at the end of the body section
     * - [[View::POS_LOAD]]: enclosed within jQuery(window).load(). Note that by using this position, the method will
     *   automatically register the jQuery js file.
     * - [[View::POS_READY]]: enclosed within jQuery(document).ready(). This is the default value. Note that by using
     *   this position, the method will automatically register the jQuery js file.
     * @param string  $key the key that identifies the JS code block. If null, it will use `$js` as the key. If two JS
     * code blocks are registered with the same key, the latter will overwrite the former.
     */
    public function registerWidgetJs($js, $pos = View::POS_READY, $key = null)
    {
        if (empty($js)) {
            return;
        }
        $view = $this->getView();
        WidgetAsset::register($view);
        $view->registerJs($js, $pos, $key);
        if (!empty($this->pjaxContainerId) && ($pos === View::POS_LOAD || $pos === View::POS_READY)) {
            $pjax = 'jQuery("#' . $this->pjaxContainerId . '")';
            $evComplete = 'pjax:complete.' . hash('crc32', $js);
            $script = "setTimeout(function(){ {$js} }, 100);";
            $view->registerJs("{$pjax}.off('{$evComplete}').on('{$evComplete}',function(){ {$script} });");
            // hack fix for browser back and forward buttons
            if ($this->enablePopStateFix) {
                $view->registerJs("window.addEventListener('popstate',function(){window.location.reload();});");
            }
        }
    }
}
