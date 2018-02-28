<?php
use backend\assets\MainAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$modelLabel = new \backend\models\AdminUser();

MainAsset::addPageScript($this, 'dist/js/rights/admin_user_group.js');
?>

<script>
    var RELATION_GROUP_USER   = "<?=Url::toRoute('rights/admin-user-group/relation')?>";
    var RELEASE_GROUP_USER = "<?=Url::toRoute('rights/admin-user-group/release')?>";
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
                                <?php ActiveForm::begin(['id' => 'admin-user-group-search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => Url::toRoute('rights/admin-user-group/index')]); ?>
                                <input type="hidden" name="group_id" id="group_id" value="<?= $groupId ?>">
                                <div class="form-group form-search" style="margin: 5px;">
                                    <label><?= ($groupName ? $groupName.' : ' : '')?> </label>
                                </div>
                                <div class="form-group form-search" style="margin: 5px;">
                                    <a onclick="selectType(1)" class="search-button <?= !(isset($query["type"]) && $query["type"] ==='0')?'select-buttone' :'' ?>" href="#">组内用户</a>
                                </div>

                                <div class="form-group form-search" style="margin: 5px;">
                                    <a onclick="selectType(0)" class="search-button  <?= isset($query["type"]) && $query["type"] ==='0'?'select-buttone' :'' ?>" href="#">组外用户</a>
                                </div>
                                <br>

                                <div class="form-group" style="margin: 5px;">
                                    <label><?= $labels['uname'] ?>:</label>
                                    <input type="text" class="form-control" id="query[uname]" name="query[uname]" value="<?= isset($query["uname"]) ? $query["uname"] : "" ?>">
                                </div>
                                <div class="form-group">
                                    <a onclick="searchAction()" class="search-button" href="#">搜索</a>
                                </div>
                                <?php if (isset($query["type"]) && $query["type"] ==='0') { ?>
                                <div class="input-group input-group-sm"
                                     style="width: 70px;margin-top: 5px;float:right;margin-right: 30px;">
                                    <button id="relation_btn" type="button" class="add-button">
                                        关&nbsp;&emsp;联
                                    </button>
                                </div>
                                <?php } else { ?>
                                <div class="input-group input-group-sm"
                                     style="width: 70px;margin-top: 5px;float:right;margin-right: 30px;">
                                    <button id="release_btn" type="button" class="add-button">
                                        解&nbsp;&emsp;除
                                    </button>
                                </div>
                                <?php }?>
                                <input type="hidden" name="query[type]" id="query_type" value="<?= isset($query["type"]) ? $query["type"] : "1" ?>">
                                <input type="hidden" name="per_page" id="per_page" value="<?=$perPage;?>">
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
                                        echo '<th class="check-box-class"><input id="data_table_check" type="checkbox"></th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['id'] . '</th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['uname'] . '</th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['last_ip'] . '</th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['is_online'] . '</th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['status'] . '</th>'
                                        . '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >' . $labels['update_date'] . '</th>';
                                        ?>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <input type="hidden" id="_csrf"
                                           value="<?= Yii::$app->getRequest()->getCsrfToken(); ?>"/>
                                    <?php
                                    if ($list) {
                                        $html= '';
                                        foreach ($list as $model) {
                                            $html .= '<tr id="rowid_' . $model['id'] . '">'
                                            . '  <td><label><input type="checkbox" value="' . $model['id'] . '"></label></td>'
                                            . '  <td>' . $model['id'] . '</td>'
                                            . '  <td>' . $model['uname'] . '</td>'
                                            . '  <td>' . $model['last_ip'] . '</td>'
                                            . '  <td>' . ($model['is_online'] == '1' ? '是' : '否') . '</td>'
                                            . '  <td>' . \backend\services\AdminUserService::getStatusName()[$model['status']] . '</td>'
                                            . '  <td>' . $model['update_date'] . '</td>';
                                            $html .= '  <td class="center">';
                                            if (isset($query["type"]) && $query["type"] ==='0') {
                                                $html .= '      <a id="edit_btn" onclick="editUserGroupAction(\'' . $model['id'] . '\', 0)" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>关联</a>';
                                            } else {
                                                $html .= '      <a id="edit_btn" onclick="editUserGroupAction(\'' . $model['id'] . '\', 1)" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>解除</a>';
                                            }

                                            $html .= '  </td>'
                                            . '<tr/>';
                                        }
                                        echo $html;
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
