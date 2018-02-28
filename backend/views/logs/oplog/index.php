
<?php
use yii\bootstrap\ActiveForm;
use common\utils\CommonFun;
use yii\helpers\Url;
use backend\assets\MainAsset;

MainAsset::addPageScript($this, 'dist/js/logs/oplog.js');
?>

<?php $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <!-- row start search-->
                        <div class="row">
                            <div class="col-sm-12">
                                <?php ActiveForm::begin(['id' => 'oplog-search-form', 'method'=>'get', 'options' => ['class' => 'form-inline'], 'action'=>Url::toRoute('logs/oplog/index')]); ?>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?=$labels['id']?>:</label>
                                    <input type="text" class="form-control" id="query[id]" name="query[id]"  value="<?=isset($query["id"]) ? $query["id"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label><?=$labels['user_id']?>:</label>
                                    <input type="text" class="form-control" id="query[user_id]" name="query[user_id]"  value="<?=isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <label><?=$labels['user_name']?>:</label>
                                    <input type="text" class="form-control" id="query[user_name]" name="query[user_name]"  value="<?=isset($query["user_name"]) ? $query["user_name"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#">搜索</a>
                                </div>
                                <input type="hidden" name="per_page" id="per_page" value="<?=$per_page;?>">
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
                                        echo '<th width="22" class="check-box-class"><input id="data_table_check" type="checkbox"></th>';
                                        echo '<th onclick="orderby(\'id\', \'desc\')" '.CommonFun::sortClass($orderby, 'id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['id'].'</th>';
                                        echo '<th onclick="orderby(\'user_id\', \'desc\')" '.CommonFun::sortClass($orderby, 'user_id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['user_id'].'</th>';
                                        echo '<th onclick="orderby(\'user_name\', \'desc\')" '.CommonFun::sortClass($orderby, 'user_name').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['user_name'].'</th>';
                                        echo '<th onclick="orderby(\'ip\', \'desc\')" '.CommonFun::sortClass($orderby, 'ip').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['ip'].'</th>';
                                        echo '<th onclick="orderby(\'logtype\', \'desc\')" '.CommonFun::sortClass($orderby, 'content').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['content'].'</th>';
                                        echo '<th onclick="orderby(\'logintoken\', \'desc\')" '.CommonFun::sortClass($orderby, 'operation').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['operation'].'</th>';
                                        echo '<th onclick="orderby(\'createtime\', \'desc\')" '.CommonFun::sortClass($orderby, 'createtime').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['createtime'].'</th>';
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    foreach ($models as $model) {
                                        echo '<tr id="rowid_' . $model['user_id'] . '">';
                                        echo '  <td><label><input type="checkbox" value="' . $model['user_id'] . '"></label></td>';
                                        echo '  <td id="dm_id_'.$model['user_id'].'">' . $model['id'] . '</td>';
                                        echo '  <td id="dm_user_id_'.$model['user_id'].'">' . $model['user_id'] . '</td>';
                                        echo '  <td id="dm_user_name_'.$model['user_id'].'">' . $model['user_name'] . '</td>';
                                        echo '  <td id="dm_ip_'.$model['user_id'].'">' . $model['ip'] . '</td>';
                                        echo '  <td id="dm_logtype_'.$model['user_id'].'">' . $model['content'] . '</td>';
                                        echo '  <td id="dm_logintoken_'.$model['user_id'].'">' . $model['operation'] . '</td>';
                                        echo '  <td id="dm_createtime_'.$model['user_id'].'">' . $model['createtime'] . '</td>';
                                        echo '</tr>';
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
                                    <div class="infos">
                                        <?=$pageinfo['page']?>
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
</section>
<!-- /.content -->

<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->
<?php $this->endBlock(); ?>