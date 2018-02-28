<?php
use yii\bootstrap\ActiveForm;
use common\utils\CommonFun;
use yii\helpers\Url;
use backend\assets\MainAsset;

MainAsset::addPageScript($this, 'dist/js/logs/admin_logs.js');
?>

<!-- Main content -->
<div class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <!-- row start search-->
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'admin-log-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => Url::toRoute('logs/admin-logs/index')]); ?>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['log_id'] ?>:</label>
                                    <input type="text" class="form-control" id="query[log_id]" name="query[log_id]"
                                           value="<?= isset($query["log_id"]) ? $query["log_id"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['create_user'] ?>:</label>
                                    <input type="text" class="form-control" id="query[create_user]" name="query[create_user]"
                                           value="<?= isset($query["create_user"]) ? $query["create_user"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#">搜索</a>
                                </div>

                                <input type="hidden" name="per_page" id="per_page" value="<?= $per_page; ?>">
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- row end search -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                    <thead id="head-scroll">
                                        <tr role="row">
                                            <?php
                                            $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['log_id'] . '</th>';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['module_name'] . '</th>';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['menu_name'] . '</th>';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['priv_name'] . '</th>';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['priv_url'] . '</th>';
                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['client_ip'] . '</th>';
//                                            echo '<th  tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['request_data'] . '</th>';
                                            echo '<th onclick="orderby(\'create_user\', \'desc\')" ' . CommonFun::sortClass($orderby, 'create_user') . ' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['create_user'] . '</th>';
                                            echo '<th onclick="orderby(\'create_date\', \'desc\')" ' . CommonFun::sortClass($orderby, 'create_date') . ' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['create_date'] . '</th>';
                                            ?>
                                            <th tabindex="0" style="width:104px;" aria-controls="data_table" rowspan="1" colspan="1"
                                                aria-sort="ascending">操作
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if ($models) {
                                        foreach ($models as $model) {
                                            echo '<tr id="rowid_' . $model['log_id'] . '">';
                                            echo '<td>' . $model['log_id'] . '</td>';
                                            echo '<td>' . $model['module_name'] . '</td>';
                                            echo '<td>' . $model['menu_name'] . '</td>';
                                            echo '<td>' . $model['priv_name'] . '</td>';
                                            echo '<td>' . $model['priv_url'] . '</td>';
                                            echo '<td>' . $model['client_ip'] . '</td>';
                                            echo '<td>' . $model['create_user'] . '</td>';
                                            echo '<td>' . $model['create_date'] . '</td>';
                                            echo '<td class="center">';
                                            echo '<a id="view_btn" onclick="viewAction(' . $model['log_id'] . ",'" . Url::toRoute('logs/admin-logs/view') . "')\"" . ' class="btn-common-operate-2" href="#"> <div class="view-common-image"></div>查看</a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr id="" align="center"> <td colspan="12">未查询到数据信息</td></tr>';
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
                                    <div class="infos"><?=$pageinfo['page']?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <?=$pageinfo['per_page']?>
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
                                    <?=$pageinfo['page_number'] ?>
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
</div>
<!-- /.content -->

<div class="modal fade" id="edit_dialog" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Settings</h3>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "admin-log-form", "class" => "form-horizontal", "action" => "index.php?r=admin-log/save"]); ?>

                <input type="hidden" class="form-control" id="log_id" name="AdminLogs[log_id]"/>

                <div id="module_name_div" class="form-group">
                    <label for="module_name"
                           class="col-sm-2 control-label"><?=$labels["module_name"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="module_name" name="AdminLogs[module_name]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="menu_name_div" class="form-group">
                    <label for="menu_name"
                           class="col-sm-2 control-label"><?=$labels["menu_name"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="menu_name" name="AdminLogs[menu_name]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="priv_name_div" class="form-group">
                    <label for="priv_name"
                           class="col-sm-2 control-label"><?=$labels["priv_name"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="priv_name" name="AdminLogs[priv_name]" placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="priv_url_div" class="form-group">
                    <label for="priv_url"
                           class="col-sm-2 control-label"><?=$labels["priv_url"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="priv_url" name="AdminLogs[priv_url]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <div id="client_ip_div" class="form-group">
                    <label for="client_ip"
                           class="col-sm-2 control-label"><?=$labels["client_ip"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="client_ip" name="AdminLogs[client_ip]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="request_data_div" class="form-group">
                    <label for="request_data"
                           class="col-sm-2 control-label"><?=$labels["request_data"] ?></label>

                    <div class="col-sm-10">
                        <textarea class="form-control" id="request_data" name="AdminLogs[request_data]" cols="10" rows="10" placeholder=""></textarea>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_user_div" class="form-group">
                    <label for="create_user"
                           class="col-sm-2 control-label"><?=$labels["create_user"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_user" name="AdminLogs[create_user]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="create_date_div" class="form-group">
                    <label for="create_date"
                           class="col-sm-2 control-label"><?=$labels["create_date"] ?></label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="create_date" name="AdminLogs[create_date]"
                               placeholder=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>


                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-button blue-common" data-dismiss="modal">关闭</a> <a
                    id="edit_dialog_ok" href="#" class="btn btn-primary">确定</a>
            </div>
        </div>
    </div>
</div>