<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<script>
    var ADMIN_LOGIN_URL   = "<?=Url::toRoute('site/login')?>";
    var CSRF_TOKEN = "<?php echo strtolower(substr(Yii::$app->request->getCsrfToken(), 0, 16)) ?>";
    var IV = "<?php echo \Yii::$app->params['IV']; ?>";
</script>

<script src="<?= Url::base(); ?>/dist/js/common/aes.js"></script>
<script src="<?= Url::base(); ?>/dist/js/common/ZeroPadding.js"></script>
<link rel="stylesheet" href="<?= Url::base(); ?>/dist/css/login.css" type="text/css" />
<div id="login_box"></div>
<div class="login-box">
    <div class="login-logo">
        <a href="<?= Url::toRoute('site/login') ?>"><b>德玛西亚管理系统</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">登录</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => Url::toRoute('site/login')]); ?>
        <!-- <form action="../../index2.html" method="post">   -->
        <span class="username-title">用户名</span>
        <div class="form-group has-feedback">
            <input name="username" id="username" type="text" class="form-control" placeholder=""/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <span class="password-title">密码</span>
        <div class="form-group has-feedback">
            <input name="password" id="password" type="password" class="form-control" placeholder="">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="">
            <div class="col-xs-8 remenber-div">
                <div class="">
                    <label>
                        <input name="remember" id="remember" value="y" type="checkbox"/> &nbsp;记住密码
                    </label>
                    <label style="float:right;">&nbsp;
                        <a style="color:#ffffff;" href="<?= Url::toRoute('site/forgetpwd') ?>"
                           class="forget_pwd">找回密码</a>
                    </label>
                </div>


            </div>
            <!-- /.col -->
            <div class=" has-feedback login-button">
                <button id="login_btn" type="button" class="btn btn-primary btn-block btn-flat">登录</button>
            </div>
            <!-- /.col -->
        </div>
        <!-- </form>  -->
        <?php ActiveForm::end(); ?>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="<?= Url::base(); ?>/dist/js/site/login.js"></script>
<script src="<?= Url::base() ?>/plugins/jQuery/particles.min.js"></script>
<script src="<?= Url::base(); ?>/dist/js/site/particlesJS.js"></script>
