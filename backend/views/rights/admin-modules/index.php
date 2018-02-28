<?php
use yii\widgets\LinkPager;
use yii\base\Object;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\utils\CommonFun;

use backend\assets\MainAsset;

MainAsset::addPageScript($this, 'dist/js/rights/admin_modules.js');
?>

<script>
        var ADMIN_MODULES_VIEW   = "<?=Url::toRoute('rights/admin-modules/view')?>";
        var ADMIN_MODULES_UPDATE = "<?=Url::toRoute('rights/admin-modules/update')?>";
        var ADMIN_MODULES_CREATE = "<?=Url::toRoute('rights/admin-modules/create')?>";
</script>

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
                                <?php ActiveForm::begin(['id' => 'admin-module-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => Url::toRoute('rights/admin-modules/index')]); ?>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['module_id'] ?>:</label>
                                    <input type="text" class="form-control" id="query[module_id]" name="query[module_id]"
                                           value="<?= isset($query["module_id"]) ? $query["module_id"] : "" ?>">
                                </div>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['module_name'] ?>:</label>
                                    <input type="text" class="form-control" id="query[module_name]"
                                           name="query[module_name]"
                                           value="<?= isset($query["module_name"]) ? $query["module_name"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#"> 搜索</a>
                                </div>
                                <?php if(CommonFun::hasPriv('rights/admin-modules/create')): ?>
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
                                        echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['module_id'] . '</th>';
                                        echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['module_name'] . '</th>';
										echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['display_order'] . '</th>';
										echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['status'] . '</th>';
                                        echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_user'] . '</th>';
                                        echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_date'] . '</th>';
                                        ?>
                                        <th tabindex="0" aria-controls="data_table" style="width:300px;" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $row = 0;
                                    if ($models) {
                                        foreach ($models as $model) {
                                            echo '<tr id="rowid_' . $model['module_id'] . '">';
                                            echo '  <td>' . $model['module_id'] . '</td>';
                                            echo '  <td>' . $model['module_name'] . '</td>';
											echo '  <td>' . $model['display_order'] . '</td>';
											echo '  <td>' . $model['status_des'] . '</td>';
                                            echo '  <td>' . $model['update_user'] . '</td>';
                                            echo '  <td>' . $model['update_date'] . '</td>';
                                            echo '  <td class="center">';
                                            echo '      <a id="view_btn" class="btn-common-operate-4" href="' . Url::toRoute(['rights/admin-menus/index', 'module_id' => $model['module_id']]) . '"><div class="sublevel-common-image"></div>二级菜单</a>';
                                            if( CommonFun::hasPriv('rights/admin-modules/view') ) {
                                                echo '      <a id="view_btn" onclick="viewAction(' . $model['module_id'] . ')" class="btn-common-operate-2" href="#"><div class="view-common-image"></div>查看</a>';
                                            }
                                            if( CommonFun::hasPriv('rights/admin-modules/update') ) {
                                                echo '      <a id="edit_btn" onclick="editAction(' . $model['module_id'] . ')" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>修改</a>';
                                            }
                                            echo '  </td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr id="" align="center"> <td colspan="9">未查询到数据信息</td></tr>';
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
                                <?=$pageInfo['per_page']?>
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
                                    <?=$pageInfo['page_number'] ?>
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
                <h3 id="admin_module_title">主菜单管理</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "admin-module-form", "class" => "form-horizontal", "action" => Url::toRoute("rights/admin-modules/save")]); ?>
                <input type="hidden" class="form-control" id="module_id" name="AdminModules[module_id]"/>

                <div id="module_name_div" class="form-group">
                    <label for="module_name"
                           class="col-sm-2 control-label"><?= $labels["module_name"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="module_name" name="AdminModules[module_name]"
                               placeholder="必填"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="display_order_div" class="form-group">
                    <label for="display_order"
                           class="col-sm-2 control-label"><?= $labels["display_order"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="display_order" name="AdminModules[display_order]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="status_div" class="form-group">
                    <label for="status" class="col-sm-2 control-label"><?=$labels["status"]?></label>
                    <div class="col-sm-10">
                        <select name="AdminModules[status]" id="status" >
							<?php foreach ($status_des as $status => $des):?>
                                <option value="<?=$status;?>"><?=$des;?></option>
							<?php endforeach;?>
                        </select>
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
