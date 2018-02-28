<?php
use backend\assets\MainAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\utils\CommonFun;

MainAsset::addPageScript($this, 'dist/js/rights/admin_group.js');
?>
<script>
    var ADMIN_GROUP_UPDATE = "<?=Url::toRoute('rights/admin-group/update')?>";
    var ADMIN_GROUP_CREATE = "<?=Url::toRoute('rights/admin-group/create')?>";
    var ADMIN_GROUP_VIEW = "<?=Url::toRoute('rights/admin-group/view')?>";
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
                                <?php ActiveForm::begin(['id' => 'admin-group-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => Url::toRoute('rights/admin-group/index')]); ?>
                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['group_id'] ?>:</label>
                                    <input type="text" class="form-control" id="query[group_id]" name="query[group_id]"
                                           value="<?= isset($query["group_id"]) ? $query["group_id"] : "" ?>">
                                </div>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['group_name'] ?>:</label>
                                    <input type="text" class="form-control" id="query[name]" name="query[group_name]"
                                           value="<?= isset($query["group_name"]) ? $query["group_name"] : "" ?>">
                                </div>

                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#">搜索</a>
                                </div>

                                <?php if(CommonFun::hasPriv('rights/admin-group/create')):?>
                                <div class="input-group input-group-sm"
                                     style="width: 70px;margin-top: 5px;float:right;margin-right: 30px;">
                                    <button id="create_btn" type="button" class="add-button">
                                        新&nbsp;&emsp;增
                                    </button>
                                </div>
                                <?php endif;?>
                                <input type="hidden" name="per_page" id="per_page" value="<?= $perPage; ?>">
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- row end search -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="data_table" style="width:inherit" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead id="head-scroll">
                                    <tr role="row">
                                        <?php
                                        echo '<th class="check-box-class"><input id="data_table_check" type="checkbox"></th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['group_id'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['group_name'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['des'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['code'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['status'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['create_user'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['create_date'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_user'] . '</th>'
                                            . '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_date'] . '</th>';
                                        ?>
                                        <th tabindex="0" style="width:280px;" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($list) {
                                        foreach ($list as $model) {
                                            echo '<tr id="rowid_' . $model['group_id'] . '">';
                                            echo '  <td><label><input type="checkbox" value="' . $model['group_id'] . '"></label></td>';
                                            echo '  <td>' . $model['group_id'] . '</td>';
                                            echo '  <td id="group_name_' . $model['group_id'] . '">' . $model['group_name'] . '</td>';
                                            echo '  <td>' . $model['des'] . '</td>';
                                            echo '  <td>' . (isset($model['code'])?$model['code']:'') . '</td>';
                                            echo '  <td>' .(!isset($model['status']) ? '' : $status[$model['status']]) . '</td>';
                                            echo '  <td>' . $model['create_user'] . '</td>';
                                            echo '  <td>' . $model['create_date'] . '</td>';
                                            echo '  <td>' . $model['update_user'] . '</td>';
                                            echo '  <td>' . $model['update_date'] . '</td>';
                                            echo '  <td class="center">';
                                            if(CommonFun::hasPriv('rights/admin-group/update')){
                                                echo '<a id="edit_btn" onclick="editAction(' . $model['group_id'] . ')" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>编辑</a>';
                                            }
                                            if(CommonFun::hasPriv('rights/admin-group/user')){
                                                echo '<a id="view_btn" class="btn-common-operate-4" href="' . Url::toRoute(["/rights/admin-group/user", "group_id" => $model['group_id']]) . '"><div class="user-common-image"></div>分配用户</a>';
                                            }
                                            if(CommonFun::hasPriv('rights/admin-priv/index')){
                                                echo '<a id="view_btn" class="btn-common-operate-4" href="' . Url::toRoute(["/rights/admin-priv/index", "group_id" => $model['group_id']]) . '"><div class="auth-common-image"></div>分配权限</a>';
                                            }
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr id="" align="center"> <td colspan="11">未查询到数据信息</td></tr>';
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
                                    <div class="infos"><?=$pageInfo['page']?></div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <?=$pageInfo['perpage']?>
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
                                    <?=$pageInfo['pagenumber'] ?>
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
                <h3 id="admin_group_one_title">Settings</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "admin-group-form", "class" => "form-horizontal", "action" => Url::toRoute("admin-group/save")]); ?>

                <input type="hidden" class="form-control" id="group_id" name="AdminGroup[group_id]"/>

                <div id="group_name_div" class="form-group">
                    <label for="code"
                           class="col-sm-2 control-label"><?=$labels["group_name"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="group_name" name="AdminGroup[group_name]" placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="group_name_div" class="form-group">
                    <label for="code"
                           class="col-sm-2 control-label"><?=$labels["code"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="group_code" name="AdminGroup[code]" placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="des_div" class="form-group">
                    <label for="des"
                           class="col-sm-2 control-label"><?=$labels["des"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="des" name="AdminGroup[des]" placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="des_div" class="form-group">
                    <label for="des" class="col-sm-2 control-label"><?=$labels["status"] ?></label>

                    <div class="col-sm-10">
                        <select name="AdminGroup[status]" id="status" >
                            <option value="0"><?=$status[0];?></option>
                            <option value="1"><?=$status[1];?></option>
                        </select>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_user_div" class="form-group">
                    <label for="create_user"
                           class="col-sm-2 control-label"><?= $labels["create_user"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_user" name="AdminGroup[create_user]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_date_div" class="form-group">
                    <label for="create_date"
                           class="col-sm-2 control-label"><?=$labels["create_date"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_date" name="AdminGroup[create_date]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="update_user_div" class="form-group">
                    <label for="update_user"
                           class="col-sm-2 control-label"><?=$labels["update_user"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="update_user" name="AdminGroup[update_user]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="update_date_div" class="form-group">
                    <label for="update_date"
                           class="col-sm-2 control-label"><?=$labels["update_date"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="update_date" name="AdminGroup[update_date]"
                               placeholder=""/>
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
<!-- 分配用户 -->
<style>
    #treeview{color:#fff}
    #treeview p{width: 14em;line-height: 20px;}
    #treeview p input{float:left;margin:3px 6px 3px 0;line-height: 20px;}
    #treeview p span{width:10em;word-wrap:break-word;float: left;line-height: 20px;}
</style>
<div class="modal fade" id="tree_dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 id="admin_group_title">分配用户</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "admin-group-user-form", "class" => "form-horizontal", "action" => Url::toRoute("admin-group/save-group-user")]); ?>
                <input type="hidden" id="select_group_id" name="group_id"/>
                <div id="treeview"></div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-button blue-common" data-dismiss="modal">关闭</a> <a id="group_dialog_ok" href="#" class="confirm-button">确定</a>
            </div>
        </div>
    </div>
</div>
<!-- 分配用户结束 -->
<?php $this->beginBlock('footer'); ?>
<!-- <body></body>后代码块 -->

<?php $this->endBlock(); ?>