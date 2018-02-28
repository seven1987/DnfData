<?php
use backend\services\BaseService;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use backend\models\AdminUser;
use yii\helpers\Url;

$modelLabel = new \backend\models\AdminUser();

use backend\assets\MainAsset;
use common\utils\CommonFun;

MainAsset::addPageScript($this, 'dist/js/rights/admin_user.js');
?>

<script>
    var ADMIN_USER_VIEW = "<?=Url::toRoute('rights/admin-user/view')?>";
    var ADMIN_USER_UPDATE = "<?=Url::toRoute('rights/admin-user/update')?>";
    var ADMIN_USER_CREATE = "<?=Url::toRoute('rights/admin-user/create')?>";
</script>

<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <!-- /.box-header -->

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <!-- row start search-->
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin([
                                    'id' => 'admin-user-search-form',
                                    'method' => 'get',
                                    'options' => ['class' => 'form-inline'],
                                    'action' => Url::toRoute('rights/admin-user/index')
                                ]); ?>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['id'] ?>:</label>
                                    <input type="text" class="form-control" id="query[id]" name="query[id]"
                                           value="<?= isset($query["id"]) ? $query["id"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['uname'] ?>:</label>
                                    <input type="text" class="form-control" id="query[uname]" name="query[uname]"
                                           value="<?= isset($query["uname"]) ? $query["uname"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#">搜索</a>
                                </div>

                                <?php if(CommonFun::hasPriv('rights/admin-user/create')) :?>
                                <div class="input-group input-group-sm"
                                     style="width: 70px;margin-top: 5px;float:right;margin-right: 30px;">
                                    <button id="create_btn" type="button" class="add-button">
                                        新&nbsp;&emsp;增
                                    </button>
                                </div>
                                <?php endif;?>
                                <input type="hidden" name="per_page" id="per_page" value="<?= $per_page; ?>">
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- row end search -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead id="head-scroll">
                                    <tr role="row">

                                        <?php
                                        echo '<th class="check-box-class"><input id="data_table_check" type="checkbox"></th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['id'] . '</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['uname'] . '</th>';
                                        //                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['admintype'] . '</th>';
                                        //                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['admin_role'] . '</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >所属用户组</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['last_ip'] . '</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['is_online'] . '</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['status'] . '</th>';
                                        echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_date'] . '</th>';
                                        ?>

                                        <th tabindex="0" aria-controls="data_table" style="width:70px;"  rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <input type="hidden" id="_csrf"
                                           value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
                                    <?php
                                    $row = 0;
                                    if ($models) {
                                        foreach ($models as $model) {
                                            echo '<tr id="rowid_' . $model['id'] . '">';
                                            echo '  <td><label><input type="checkbox" value="' . $model['id'] . '"></label></td>';
                                            echo '  <td>' . $model['id'] . '</td>';
                                            echo '  <td>' . $model['uname'] . '</td>';
                                            echo '  <td>' . $model['group_des'] . '</td>';
                                            echo '  <td>' . $model['last_ip'] . '</td>';
                                            echo '  <td>' . ($model['is_online'] == '1' ? '是' : '否') . '</td>';
                                            echo '  <td>' . \backend\services\AdminUserService::getStatusName()[$model['status']] . '</td>';
                                            echo '  <td>' . $model['update_date'] . '</td>';
                                            echo '  <td class="center">';
                                            if (CommonFun::hasPriv('rights/admin-user/update')) {
                                                echo '      <a id="edit_btn" onclick="editAction(' . $model['id'] . ')" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>修改</a>';
                                            }
                                            echo '  </td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr id="" align="center"> <td colspan="10">未查询到数据信息</td></tr>';
                                    }


                                    ?>


                                    </tbody>
                                    <!-- <tfoot></tfoot> -->
                                </table>
                            </div>
                        </div>
                        <!-- row end -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data_table_info" role="status" aria-live="polite">
                                    <div class="infos"><?= $pageinfo['page'] ?></div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <?= $pageinfo['perpage'] ?>
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
                                    <?= $pageinfo['pagenumber'] ?>
                                </div>
                            </div>
                        </div>
                        <!-- row end -->
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<div class="modal fade" id="edit_dialog" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 id="admin_user_one_title">Settings</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    "id" => "admin-user-form",
                    "class" => "form-horizontal",
                    "action" => Url::toRoute("admin-user/save")
                ]); ?>
                <input type="hidden" class="form-control" id="id" name="AdminUser[id]"/>

                <div id="uname_div" class="form-group">
                    <label for="uname"
                           class="col-sm-2 control-label"><?= $labels["uname"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="uname" name="AdminUser[uname]" placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="password_div" class="form-group">
                    <label for="password"
                           class="col-sm-2 control-label"><?= $labels["password"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="password" name="AdminUser[password]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="email_div" class="form-group">
                    <label for="email"
                           class="col-sm-2 control-label">邮箱</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="email" name="AdminUser[email]" placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="group_div" class="form-group">
                    <label for="email"
                           class="col-sm-2 control-label">默认用户组</label>

                    <div class="col-sm-10">
                        <input type="checkbox" class="subCheck" name="groupName" value="group_platform_admin"><label>平台管理员</label>
                        &nbsp;
                        <input type="checkbox" class="subCheck" name="groupName"
                               value="group_hander"><label>操盘手</label>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="auth_key_div" class="form-group">
                    <label for="auth_key"
                           class="col-sm-2 control-label"><?= $labels["auth_key"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="auth_key" name="AdminUser[auth_key]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="last_ip_div" class="form-group">
                    <label for="last_ip"
                           class="col-sm-2 control-label"><?= $labels["last_ip"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="last_ip" name="AdminUser[last_ip]" placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="is_online_div" class="form-group">
                    <label for="is_online"
                           class="col-sm-2 control-label"><?= $labels["is_online"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="is_online" name="AdminUser[is_online]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="domain_account_div" class="form-group">
                    <label for="domain_account"
                           class="col-sm-2 control-label"><?= $labels["domain_account"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="domain_account" name="AdminUser[domain_account]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="status_div" class="form-group">
                    <label for="status"
                           class="col-sm-2 control-label"><?= $labels["status"] ?></label>

                    <div class="col-sm-10">
                        <input type="radio" name="status"
                               value="-1"><label><?= \backend\services\AdminUserService::getStatusName()[-1] ?></label>
                        <input type="radio" name="status" value="0">
                        <label><?= \backend\services\AdminUserService::getStatusName()[0] ?></label>
                        <input type="radio" name="status"
                               value="10"><label><?= \backend\services\AdminUserService::getStatusName()[10] ?></label>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_user_div" class="form-group">
                    <label for="create_user"
                           class="col-sm-2 control-label"><?= $labels["create_user"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_user" name="AdminUser[create_user]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_date_div" class="form-group">
                    <label for="create_date"
                           class="col-sm-2 control-label"><?= $labels["create_date"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_date" name="AdminUser[create_date]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="update_user_div" class="form-group">
                    <label for="update_user"
                           class="col-sm-2 control-label"><?= $labels["update_user"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="update_user" name="AdminUser[update_user]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="update_date_div" class="form-group">
                    <label for="update_date"
                           class="col-sm-2 control-label"><?= $labels["update_date"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="update_date" name="AdminUser[update_date]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-button blue-common" data-dismiss="modal">关闭</a> <a
                        id="edit_dialog_ok" href="#" class="confirm-button">确定</a>
            </div>
        </div>
    </div>
</div>
