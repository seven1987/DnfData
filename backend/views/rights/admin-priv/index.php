
<?php
use yii\widgets\LinkPager;
use yii\base\Object;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use backend\assets\MainAsset;
use common\utils\CommonFun;

MainAsset::addPageScript($this, 'dist/js/rights/admin_priv.js');
?>

<script>
    var ADMIN_PRIV_CREATE = "<?=Url::toRoute('rights/admin-priv/create')?>";
    var ADMIN_PRIV_VIEW = "<?=Url::toRoute('rights/admin-priv/view')?>";
    var ADMIN_PRIV_UPDATE = "<?=Url::toRoute('rights/admin-priv/update')?>";
    var ADMIN_PRIV_DELETE = "<?=Url::toRoute('rights/admin-priv/delete')?>";
    var ADMIN_PRIV_GET_MENU = "<?=Url::toRoute('rights/admin-priv/getmenu')?>";
    var ADMIN_PRIV_GET_PRIV = "<?=Url::toRoute('rights/admin-priv/getpriv')?>";
    var ADMIN_PRIV_GROUP_SAVE_PRIV = "<?=Url::toRoute('rights/admin-priv/groupsavepriv')?>";
    var ADMIN_PRIV_CHANGE_NAME = "<?=Url::toRoute('rights/admin-priv/changename')?>";
</script>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body">
					<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<div class="col-sm-12">
                                <div class="form-group" style="margin: 5px;">
                                    <a href="<?=Url::toRoute("rights/admin-group/index")?>" type="button" class="add-button"  >返回分组</a>
                                </div>
                                <div class="form-group" style="margin: 5px;">
                                    <span >点击权限可编辑</span>
                                </div>
                                <?php if(CommonFun::hasPriv( 'rights/admin-priv/create')):?>
								<div class="input-group input-group-sm" style="width: 70px;margin-top: 5px;float:right;margin-right: 30px;">
									<button id="create_priv_btn" type="button" class="add-button"  >新增权限</button>
								</div>
                                <?php endif;?>
							</div>
						</div>

					</div>
				</div>
			</div>
			<!-- /.box -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->

	<!-- row start -->
	<div class="row">
		<div class="col-sm-12">
			<table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
				<thead>
<!--				<tr role="row">-->
<!--					<th tabindex="0" aria-controls="data_table" rowspan="1" colspan="2" aria-sort="ascending" >模块1</th>-->
<!--				</tr>-->
				</thead>
				<tbody id="priv_all_checkbox">
                <?php if($list):foreach ($list as $module)://遍历模块?>
					<tr role="row" class="priv_module_title">
						<td rowspan="1" colspan="2" ><b><?= $module['module_name']?></b><input  type="checkbox" name="menu_priv" onclick="selectAll(this, 'all_menus_<?=$module['module_id'];?>', 'checkbox')" ></td>
					</tr>
                    <?php if(!empty($module['menu_list'])):foreach ($module['menu_list'] as $menu)://遍历菜单?>
					<tr id="all_menus_<?=$module['module_id'];?>" class="priv_menu_list">
						<td width="150" class="priv_menu_title"><?= $menu['menu_name']?><input  type="checkbox" name="menu_priv" onclick="selectAll(this, 'priv_checkbox_<?=$menu['menu_id']?>', 'checkbox')" ></td>
						<td id="priv_checkbox_<?=$menu['menu_id']?>" class="priv_list">
						<?php if(!empty($menu['priv_list'])):foreach ($menu['priv_list'] as $priv)://遍历权限?>
							<div class="priv_item"><input  type="checkbox" name="priv[]" value="<?= $priv['priv_url']?>" <?php if($priv['is_valid'])echo 'checked';?>>
                                <?php if(CommonFun::hasPriv( 'rights/admin-priv/changename')):?>
                                    <span onclick="editPriv(<?= $priv['priv_id']?>);" class="edit_priv_span" title="点击编辑"><?= $priv['priv_name']?></span>
                                <?php else:?>
                                    <span class="edit_priv_span" title="点击编辑"><?= $priv['priv_name']?></span>
                                <?php endif;?>
                            </div>
						<?php endforeach;endif;?>
						</td>
					</tr>
					<?php endforeach;endif;?>

                <?php endforeach;endif;?>
                <tr>
                    <td width="150">全选<input  type="checkbox" name="menu_priv" onclick="selectAll(this, 'priv_all_checkbox', 'checkbox')" ></td>
                    <td id="priv_checkbox">
                        <input type="hidden" name="group_id" value="<?=$group_id?>">
                        <?php if(CommonFun::hasPriv( 'rights/admin-priv/update')):?>
                        <a id="save_group_priv_ok" href="#" class="confirm-button">保存权限</a>
                        <?php endif;?>
                        <a href="javascript:location.href = '<?=Url::toRoute('rights/admin-group/index')?>';" class="close-button blue-common" data-dismiss="modal">返回</a>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br></td>
                </tr>
				</tbody>
				<!-- <tfoot></tfoot> -->
			</table>
		</div>
	</div>
	<!-- row end -->

</section>
<!-- /.content -->

<div class="modal fade" id="edit_dialog" tabindex="-1" role="dialog"
	 aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3 id="admin_priv_title">新增权限</h3>
			</div>
			<div class="modal-body">
				<form action="<?= Url::toRoute('admin-priv/save')?>" id="admin-priv-form" class="form-horizontal">
                <input type="hidden" class="form-control" id="priv_id" name="AdminPriv[priv_id]"/>
				<div id="priv_name_div" class="form-group">
					<label for="priv_name" class="col-sm-2 control-label">权限名称</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="priv_name" name="AdminPriv[priv_name]" placeholder="权限名称" />
					</div>
					<div class="clearfix"></div>
				</div>
				<div id="module_id_div" class="form-group">
					<label for="module_id" class="col-sm-2 control-label">模块选择</label>
					<div class="col-sm-10">
						<select class="form-control" name="AdminPriv[module_id]" id="module_id">
							<option value="0">请选择</option>
							<?php
							foreach($module_list as $key => $module){
								echo "<option value='" . $module['module_id'] . "'>". $module['module_name']."</option>";
							}
							?>
						</select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div id="menu_id_div" class="form-group">
					<label for="controller" class="col-sm-2 control-label">菜单选择</label>
					<div class="col-sm-10">
						<select class="form-control" name="AdminPriv[menu_id]" id="menu_id">
							<option value="0">请选择</option>
						</select>
					</div>
					<div class="clearfix"></div>
				</div>
				<div id="priv_url_div" class="form-group">
					<label for="controller" class="col-sm-2 control-label">权限路径</label>
					<div class="col-sm-10">
						<select class="form-control" name="AdminPriv[priv_url]" id="priv_url">
                            <option value="0">请选择</option>
						</select>
					</div>
					<div class="clearfix"></div>
				</div>
                    <?php if(CommonFun::hasPriv( 'rights/admin-priv/delete')):?>
                <div class="" style="text-align: right;">
                    <a href="#" class="delete_priv_btn confirm-button red-common" >删除权限</a>
                </div>
                    <?php endif;?>
				</form>
			</div>
			<div class="modal-footer">
				<a href="#" class="close-button blue-common" data-dismiss="modal">关闭</a> <a
					id="edit_dialog_ok" href="#" class="confirm-button">确定</a>
			</div>
		</div>
	</div>
</div>
