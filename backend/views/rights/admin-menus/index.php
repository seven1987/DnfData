
<?php
use yii\widgets\LinkPager;
use yii\base\Object;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use backend\assets\MainAsset;
use common\utils\CommonFun;

MainAsset::addPageScript($this, 'dist/js/rights/admin_menus.js');
?>

<script>
    var ADMIN_MENU_VIEW   = "<?=Url::toRoute('rights/admin-menus/view')?>";
    var ADMIN_MENU_UPDATE = "<?=Url::toRoute('rights/admin-menus/update')?>";
    var ADMIN_MENU_CREATE = "<?=Url::toRoute('rights/admin-menus/create')?>";
    window.controllerData = <?php echo json_encode($controllerData); ?>;
</script>

<?php $this->beginBlock('header');  ?>
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

                <?php ActiveForm::begin(['id' => 'admin-menu-search-form', 'method'=>'get', 'options' => ['class' => 'form-inline'], 'action'=>Url::toRoute('rights/admin-menus/index')]); ?>
                  <input type="hidden" id="module_id" name="module_id" value="<?=$module_id?>" />
                <div class="form-group" style="margin: 5px;">
                    <a href="javascript:location.href = '<?=Url::toRoute('rights/admin-modules/index')?>';" type="button" class="add-button" >返&nbsp;&emsp;回</a>
                </div>
                  <div class="form-group" style="margin: 5px;">
                      <label><?=$labels['menu_id']?>:</label>
                      <input type="text" class="form-control" id="query[menu_id]" name="query[menu_id]"  value="<?=isset($query["menu_id"]) ? $query["menu_id"] : "" ?>">
                  </div>

                  <div class="form-group" style="margin: 5px;">
                      <label><?=$labels['priv_url']?>:</label>
                      <input type="text" class="form-control" id="query[priv_url]" name="query[priv_url]"  value="<?=isset($query["priv_url"]) ? $query["priv_url"] : "" ?>">
                  </div>
              <div class="form-group">
              	<a onclick="searchAction()" class="search-button" href="#">搜索</a>
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
          	<table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
            <thead>
            <tr role="row">
            
            <?php 
              echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['menu_id'].'</th>';
              echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['menu_name'].'</th>';
              echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['display_order'].'</th>';
			echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['priv_url'].'</th>';
			echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['status'].'</th>';
			  echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['update_user'].'</th>';
              echo '<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$labels['update_date'].'</th>';
         
			?>
	
            <th tabindex="0"  style="width:200px;" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >操作</th>
            </tr>
            </thead>
            <tbody>
            
            <?php
            $row = 0;
            if($models)
            {
                foreach ($models as $model) {
                    echo '<tr id="rowid_' . $model['menu_id'] . '">';
                    echo '  <td>' . $model['menu_id'] . '</td>';
                    echo '  <td>' . $model['menu_name'] . '</td>';
                    echo '  <td>' . $model['display_order'] . '</td>';
					echo '  <td>' . $model['priv_url'] . '</td>';
					echo '  <td>' . $model['status_des'] . '</td>';
					echo '  <td>' . $model['update_user'] . '</td>';
                    echo '  <td>' . $model['update_date'] . '</td>';
                    echo '  <td class="center">';
                    if( CommonFun::hasPriv('rights/admin-modules/view') ) {
                        echo '      <a id="view_btn" onclick="viewAction(' . $model['menu_id'] . ')" class="btn-common-operate-2" href="#"><div class="view-common-image"></div>查看</a>';
                    }
                    if( CommonFun::hasPriv('rights/admin-modules/update') ) {
                        echo '      <a id="edit_btn" onclick="editAction(' . $model['menu_id'] . ')" class="btn-common-operate-2" href="#"><div class="edit-common-image"></div>修改</a>';
                    }
                    echo '  </td>';
                    echo '</tr>';
                }
            }
            else
            {
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
				<h3>子菜单管理</h3>
			</div>
			<div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "admin-menu-form", "class"=>"form-horizontal", "action"=>Url::toRoute('admin-menu/save')]); ?>                      
                 <input type="hidden" class="form-control" id="menu_id" name="AdminMenus[menu_id]" />
                 <input type="hidden" class="form-control" id="module_id" name="AdminMenus[module_id]" value="<?=$module_id?>">                    
          



          <div id="menu_name_div" class="form-group">
              <label for="menu_name" class="col-sm-2 control-label"><?=$labels["menu_name"]?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="menu_name" name="AdminMenus[menu_name]" placeholder="必填" />
              </div>
              <div class="clearfix"></div>
          </div>

          <div id="display_order_div" class="form-group">
              <label for="display_order" class="col-sm-2 control-label"><?=$labels["display_order"]?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="display_order" name="AdminMenus[display_order]" placeholder="" />
              </div>
              <div class="clearfix"></div>
          </div>

            <div id="status_div" class="form-group">
                <label for="status" class="col-sm-2 control-label"><?=$labels["status"]?></label>
                <div class="col-sm-10">
                    <select name="AdminMenus[status]" id="status" >
                        <?php foreach ($status_des as $status => $des):?>
                            <option value="<?=$status;?>"><?=$des;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="clearfix"></div>
            </div>

          <div id="controller_div" class="form-group">
              <label for="controller" class="col-sm-2 control-label">控制器</label>
              <div class="col-sm-10">
              	<select class="form-control" name="controller" id="controller">
					<option>请选择</option>
    				<?php 	   
					  foreach($controllerData as $key=>$data){
					      echo "<option value='" . $data['text'] . "'>". $data['text']."</option>";
					  }
					?>
        	    </select>
              </div>
              <div class="clearfix"></div>
          </div>
          
          <div id="action_div" class="form-group">
              <label for="action" class="col-sm-2 control-label">方法</label>
              <div class="col-sm-10">
                  <select class="form-control" name="action" id="action">
                    <option>请选择</option>
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
