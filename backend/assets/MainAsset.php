<?php
/**
 * Created by PhpStorm.
 * User: xiaoda
 * Date: 2017/4/25
 * Time: 10:06
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //<!-- Bootstrap 3.3.6 -->
        "bootstrap/css/bootstrap.min.css",
        //<!-- Font Awesome -->
        "libs/font-awesome.min.css",
        //<!-- Ionicons  -->
        "libs/ionicons.min.css",
        //<!-- Theme style -->
        "dist/css/AdminLTE.min.css",
        //<!-- AdminLTE Skins. Choose a skin from the css/skins
        //     folder instead of downloading all of them to reduce the load. -->
        "dist/css/skins/_all-skins.min.css",
        //<!-- iCheck -->
        "plugins/iCheck/flat/blue.css",
        //<!-- Morris chart -->
        "plugins/morris/morris.css",
        //<!-- jvectormap -->
        "plugins/jvectormap/jquery-jvectormap-1.2.2.css",
        //<!-- Date Picker -->
        "plugins/datepicker/datepicker3.css",
        //<!-- Daterange picker -->
        "plugins/daterangepicker/daterangepicker.css",
        //<!-- bootstrap wysihtml5 - text editor -->
        "plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css",
        //<!-- DataTables -->
        //<!-- Elements UI -->
        "dist/css/element.min.css",
        "plugins/datatables/dataTables.bootstrap.css",
        "plugins/layui/css/layui.css",
        "dist/css/backend_common.css",
        "plugins/kindeditor-4.1.10/themes/default/default.css",
        "dist/css/site.css",
        "dist/css/main.css",
    ];
    public $js = [
        //<!-- jQuery 2.2.3 -->
        //"plugins/jQuery/jquery-2.2.3.min.js",
        "plugins/laydate/laydate.js",

        //<!--editor编辑器-->
        "plugins/kindeditor-4.1.10/kindeditor-min.js",
        "plugins/kindeditor-4.1.10/lang/zh_CN.js",

        ['https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js', 'condition' => 'lte IE9', 'position' => View::POS_HEAD],
        ['https://https://oss.maxcdn.com/respond/1.4.2/respond.min.js', 'condition' => 'lte IE9', 'position' => View::POS_HEAD],

        "plugins/form/jquery.form.min.js",
        //<!-- Bootstrap 3.3.6 -->
        "bootstrap/js/bootstrap.min.js",
        //<!-- Morris.js charts -->
        "libs/raphael-min.js",
        "plugins/morris/morris.min.js",
        //<!-- Sparkline -->
        "plugins/sparkline/jquery.sparkline.min.js",
        //<!-- jvectormap -->
        "plugins/jvectormap/jquery-jvectormap-1.2.2.min.js",
        "plugins/jvectormap/jquery-jvectormap-world-mill-en.js",
        //<!-- jQuery Knob Chart -->
        "plugins/knob/jquery.knob.js",

        //<!-- daterangepicker -->
        "libs/moment.min.js",
        "plugins/daterangepicker/daterangepicker.js",
        //<!-- datepicker -->
        "plugins/datepicker/bootstrap-datepicker.js",
        //<!-- Bootstrap WYSIHTML5 -->
        "plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js",
        //<!-- Slimscroll -->
        "plugins/slimScroll/jquery.slimscroll.min.js",
        //<!-- FastClick -->
        "plugins/fastclick/fastclick.js",
        //<!-- DataTables -->
        "plugins/datatables/jquery.dataTables.min.js",
        "plugins/datatables/dataTables.bootstrap.min.js",
        "plugins/treeview/bootstrap-treeview.min.js",
        "plugins/layui/layui.js",

        //<!-- AdminLTE App -->
        "dist/js/app.min.js",
        //<!-- AdminLTE for demo purposes -->
        "dist/js/demo.js",
        "js/PinYin.js",

        "dist/js/vue.js",
        "dist/js/element.min.js",
        "dist/js/core/helper.js",
        "dist/js/core/class.js",
        "dist/js/core/event.js",
        "dist/js/main.js",
        "dist/js/upload.js",
        "dist/js/bi_upload.js",
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    //导入当前页的功能css文件，注意加载顺序，这个应该最后调用
    public static function addPageCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [MainAsset::className(), 'depends' => 'backend\assets\MainAsset']);
    }

    //导入当前页的功能js文件，注意加载顺序，这个应该最后调用
    public static function addPageScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [MainAsset::className(), 'depends' => 'backend\assets\MainAsset']);
    }


}