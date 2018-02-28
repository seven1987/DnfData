<?php

use yii\helpers\Url;
use backend\assets\MainAsset;

$this->title = '德玛管理后台';
$system_menus = $this->context->system_menus;
$uploadToken = $this->context->uploadToken;
$route = $this->context->route;

MainAsset::register($this);
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $this->title ?></title>
    <?php
    $this->registerMetaTag(["name" => "viewport", "content" => "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"]);
    $this->registerMetaTag(["http-equiv" => "X-UA-Compatible", "content" => "IE=edge"]);
    $this->registerMetaTag(["charset" => "utf-8"]);
    $this->registerMetaTag(["name" => "csrf-token", "content" => Yii::$app->getRequest()->getCsrfToken()]);
    $this->head();

    $uploadService = isset(Yii::$app->params['uploadService']) && Yii::$app->params['uploadService'] ? Yii::$app->params['uploadService'] : Url::toRoute("matchs/upload/commonimg") ;    ?>
    <script>
        <?="var _csrf = '" . Yii::$app->getRequest()->getCsrfToken() . "';"?>
        <?="var UPLOAD = '" . $uploadService . "';"?>
        <?="var upload_key = '" . $uploadToken . "';"?>
    </script>
</head>
<body class="hold-transition skin-blue-light sidebar-mini" style="width:100%;height:100%;overflow:hidden">
<?php $this->beginBody(); ?>

<!--<link rel="stylesheet" href="--><?//= Url::base() ?><!--/dist/css/backend_common.css">-->
<div class="modal fade" id="confirm_dialog" tabindex="2" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog confirm-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>请确认</h3>
            </div>
            <div id="confirm_content" class="modal-body">

            </div>
            <div class="modal-footer">
                <a id="confirm_dialog_cancel" href="#" class="close-button blue-common" data-dismiss="modal">取消</a> <a
                        id="confirm_dialog_ok"  class="confirm-button">确定</a>
            </div>
        </div>
    </div>
</div>



<div class="wrapper">
    <input type="hidden" id="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken();?>" />
    <input type="hidden" id="upload_key" value="<?= $uploadToken;?>" />
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::toRoute('site/index') ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>D</b>M</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">数据录入系统管理后台</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div id="notice" class="notice">
                <marquee class="notice-marquee" scrollamount=8>
                    <strong>
                        <img src="<?=Url::base()?>/dist/img/global/icon_trumpet.png">
                        <span id="text_notice" class="notice-text"></span>
                    </strong>
                </marquee>
            </div>



            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user-menu notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">  -->
                            <span class="admin-min-icon" style="font-size: 16px" alt="User Image"><img class="admin-icon" src="/dist/img/admin-common/admin-user-min.png"/></span>
                            <span class="hidden-xs">&nbsp;&nbsp;<?php echo Yii::$app->user->identity->uname; ?>&nbsp;&nbsp;</span>
                            <span class="fa fa-caret-down"></span>
                        </a>

                        <ul class="dropdown-menu">
                            <!-- User image -->

                            <!-- Menu Body -->
                            <li class="user-body">
                                <ul class="menu">
                                    <li><a href="<?= Url::toRoute('site/psw') ?>"><i class="fa fa-cog"></i> 修改密码</a></li>
                                    <li><a href="<?= Url::toRoute('site/logout') ?>"><i class="fa fa-sign-out"></i> 退出</a></li>
                                </ul>

                        </ul>

                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar" class="admin-setting"><img  src="/dist/img/admin-common/setting.png"/></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header"></li>

                <li <?= $route == 'site/index' ? ' class="active" ' : '' ?>>
                    <a href="<?= Url::to(['site/index']) ?>">
                        <i class="module-class"></i>
                        <span class="site-text">首页</span>
                    </a>
                </li>
                <?php
                foreach ($system_menus as $key=>$module) {
                    $menuList = $module['menu_list'];
                    $isMenuActive = '';
                    $moudle=" module-class";
                    $isTreeView = count($menuList) > 0 ? "treeview" : "";
                    $menuHtml = '<li class="#isMenuActive#' . $isTreeView . '">'; // active
                    $menuHtml .= '   <a href="#"  >';
                    $menuHtml .= '   <i class="  '.$moudle.'-'.$key.'"></i> <span>' . $module['module_name'] . '</span>';
                    $menuHtml .= '   <span class="pull-right-container">';
                    $menuHtml .= '       <i class="fa fa-angle-left pull-right"></i>';
                    $menuHtml .= '   </span>';
                    $menuHtml .= '   </a>';

                    if ($isTreeView != "") {
                        $menuHtml .= '<ul class="treeview-menu">';

                        foreach ($menuList as $menu) {
							$isActive = '';
							if(!empty($menu['is_active']) && $menu['is_active'])
                            {
								$isActive =  'class="active"';
                            }
                            $menuHtml .= '<li ' . $isActive . '><a href="' . Url::to([$menu['priv_url']]) . '">' . $menu['menu_name'] . '</a></li>';
                            if (empty($isMenuActive) == true && $isActive != "") {
                                $isMenuActive = 'active ';
                            }
                        }
                        $menuHtml .= '</ul>';
                    }
                    $menuHtml .= '</li>';
                    $menuHtml = str_replace('#isMenuActive#', $isMenuActive, $menuHtml);
                    echo $menuHtml;
                }
                ?>


            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper" style="min-width:88%;max-height:880px;overflow-y: auto;overflow-x: auto;">




        <?= $content ?>

    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php

$this->registerJsFile("/dist/res/" . strtolower(Yii::$app->language) . "/syscode.js");
$this->endBody();
?>
</body>

</html>
<?php $this->endPage(); ?>