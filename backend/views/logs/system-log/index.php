
<?php
use yii\bootstrap\ActiveForm;
use common\utils\CommonFun;
use yii\helpers\Url;
use backend\assets\MainAsset;

MainAsset::addPageScript($this, 'dist/js/logs/system_log.js');
?>

<?php $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<style>
    .tr_error td{
        color:#f00;
    }
</style>
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
                <?php ActiveForm::begin(['id' => 'system-log-search-form', 'method'=>'get', 'options' => ['class' => 'form-inline'], 'action'=>Url::toRoute('logs/system-log/index')]); ?>
                
                  <div class="form-group" style="margin: 5px;">
                      <label><?=$labels['log_id']?>:</label>
                      <input type="text" class="form-control" id="query[log_id]" name="query[log_id]"  value="<?=isset($query["log_id"]) ? $query["log_id"] : "" ?>">
                  </div>
                    <div class="form-group" style="margin: 5px;">
                        <label><?=$labels['user_id']?>:</label>
                        <input type="text" class="form-control" id="query[user_id]" name="query[user_id]"  value="<?=isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                    </div>
                <!--用户类型-->
                <div class="form-group" style="margin: 5px;">
                    <label>用户类型:</label>
                    <select type="text" class="form-control" id="query[user_type]" name="query[user_type]" placeholder="必填">
                        <option value="">请选择用户类型</option>
                        <?php foreach ($user_type as $ky => $ve) { ?>
                            <option value="<?php echo $ky; ?>" <?php if(isset($query["user_type"]) && $query["user_type"] !== '' &&$query['user_type']==$ky){echo "selected";}?>> <?php echo $ve; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <!--分类-->
                <div class="form-group" style="margin: 5px;">
                    <label>分类:</label>
                    <select type="text" class="form-control" id="query[category]" name="query[category]" placeholder="必填">
                        <option value="">请选择分类</option>
                        <?php foreach ($category_list as  $ky => $ve) { ?>
                            <option value="<?php echo $ve; ?>" <?php if(isset($query["category"])&&$query['category']==$ve){echo "selected";}?>> <?php echo $ve; ?> </option>
                        <?php } ?>
                    </select>
                </div>
                <!--级别-->
                <div class="form-group" style="margin: 5px;">
                    <label>日志级别:</label>
                    <select type="text" class="form-control" id="query[level]" name="query[level]" placeholder="必填">
                        <option value="">请选择级别</option>
                        <?php foreach ($level_list as  $ky => $ve) { ?>
                            <option value="<?php echo $ve; ?>" <?php if(isset($query["level"])&&$query['level']==$ve){echo "selected";}?>> <?php echo $ve; ?> </option>
                        <?php } ?>
                    </select>
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
              echo '<th onclick="orderby(\'log_id\', \'desc\')" '.CommonFun::sortClass($orderby, 'log_id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['log_id'].'</th>';
              echo '<th onclick="orderby(\'logtime\', \'desc\')" '.CommonFun::sortClass($orderby, 'logtime').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['logtime'].'</th>';
              echo '<th onclick="orderby(\'user_id\', \'desc\')" '.CommonFun::sortClass($orderby, 'user_id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['user_id'].'</th>';
              echo '<th onclick="orderby(\'user_type\', \'desc\')" '.CommonFun::sortClass($orderby, 'user_type').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['user_type'].'</th>';
              echo '<th onclick="orderby(\'category\', \'desc\')" '.CommonFun::sortClass($orderby, 'category').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['category'].'</th>';
              echo '<th onclick="orderby(\'level\', \'desc\')" '.CommonFun::sortClass($orderby, 'level').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['level'].'</th>';
              echo '<th onclick="orderby(\'message\', \'desc\')" '.CommonFun::sortClass($orderby, 'message').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['message'].'</th>';
			?>
	
            </tr>
            </thead>
            <tbody>
            
            <?php
            if($list)
            {
                foreach ($list as $model) {
                    if($model['level'] == 'error')
                    {
                        echo '<tr id="rowid_' . $model['log_id'] . '" class="tr_error">';
                    }
                    else
                    {
                        echo '<tr id="rowid_' . $model['log_id'] . '">';
                    }
                    echo '  <td id="dm_log_id_'.$model['log_id'].'">' . $model['log_id'] . '</td>';
                    echo '  <td id="dm_logtime_'.$model['log_id'].'">' . $model['logtime'] . '</td>';
                    echo '  <td id="dm_user_id_'.$model['log_id'].'">' . $model['user_id'] . '</td>';
                    echo '  <td id="dm_user_type_'.$model['log_id'].'">' . $model['user_type'] . '</td>';
                    echo '  <td id="dm_category_'.$model['log_id'].'">' . $model['category'] . '</td>';
                    echo '  <td id="dm_level_'.$model['log_id'].'">' . $model['level'] . '</td>';
                    echo '  <td id="dm_message_'.$model['log_id'].'">' . htmlspecialchars($model['message']) . '</td>';
                    echo '</tr>';
                }
            }
            else
            {
                echo '<tr id="" align="center"> <td colspan="7">未查询到数据信息</td></tr>';
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