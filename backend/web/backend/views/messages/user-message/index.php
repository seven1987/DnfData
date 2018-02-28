
<?php
use yii\widgets\LinkPager;
use yii\base\Object;
use yii\bootstrap\ActiveForm;
use common\utils\CommonFun;
use yii\helpers\Url;

use common\models\UserMessage;

$modelLabel = new \common\models\UserMessage();

?>

<?php $this->beginBlock('header');  ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
      
        <div class="box-header">
          <h3 class="box-title"></h3>
          <div class="box-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
                <button id="create_btn" type="button" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</button>
        			|
        		<button id="delete_btn" type="button" class="btn btn-xs btn-danger">批量删除</button>
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        
        <div class="box-body">
          <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <!-- row start search-->
          	<div class="row">
          	<div class="col-sm-12">
                <?php ActiveForm::begin(['id' => 'user-message-search-form', 'method'=>'get', 'options' => ['class' => 'form-inline'], 'action'=>Url::toRoute('user-message/index')]); ?>     
                
                  <div class="form-group" style="margin: 5px;">
                      <label><?=$modelLabel->getAttributeLabel('msg_id')?>:</label>
                      <input type="text" class="form-control" id="query[msg_id]" name="query[msg_id]"  value="<?=isset($query["msg_id"]) ? $query["msg_id"] : "" ?>">
                  </div>

                  <div class="form-group" style="margin: 5px;">
                      <label><?=$modelLabel->getAttributeLabel('user_id')?>:</label>
                      <input type="text" class="form-control" id="query[user_id]" name="query[user_id]"  value="<?=isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                  </div>

                  <div class="form-group" style="margin: 5px;">
                      <label><?=$modelLabel->getAttributeLabel('fromuid')?>:</label>
                      <input type="text" class="form-control" id="query[fromuid]" name="query[fromuid]"  value="<?=isset($query["fromuid"]) ? $query["fromuid"] : "" ?>">
                  </div>
              <div class="form-group">
              	<a onclick="searchAction()" class="btn btn-primary btn-sm" href="#"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>搜索</a>
           	  </div>
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
              $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';
		      echo '<th width="10"><input id="data_table_check" type="checkbox"></th>';
              echo '<th onclick="orderby(\'msg_id\', \'desc\')" '.CommonFun::sortClass($orderby, 'msg_id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('msg_id').'</th>';
              echo '<th onclick="orderby(\'user_id\', \'desc\')" '.CommonFun::sortClass($orderby, 'user_id').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('user_id').'</th>';
              echo '<th onclick="orderby(\'title\', \'desc\')" '.CommonFun::sortClass($orderby, 'title').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('title').'</th>';
              echo '<th onclick="orderby(\'content\', \'desc\')" '.CommonFun::sortClass($orderby, 'content').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('content').'</th>';
              echo '<th onclick="orderby(\'status\', \'desc\')" '.CommonFun::sortClass($orderby, 'status').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('status').'</th>';
              echo '<th onclick="orderby(\'fromuid\', \'desc\')" '.CommonFun::sortClass($orderby, 'fromuid').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('fromuid').'</th>';
              echo '<th onclick="orderby(\'createtime\', \'desc\')" '.CommonFun::sortClass($orderby, 'createtime').' tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('createtime').'</th>';
         
			?>
	
            <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >操作</th>
            </tr>
            </thead>
            <tbody>
            
            <?php
            foreach ($models as $model) {
                echo '<tr id="rowid_' . $model->msg_id . '">';
                echo '  <td><label><input type="checkbox" value="' . $model->msg_id . '"></label></td>';
                echo '  <td id="dm_msg_id_'.$model->msg_id.'">' . $model->msg_id . '</td>';
                echo '  <td id="dm_user_id_'.$model->msg_id.'">' . $model->user_id . '</td>';
                echo '  <td id="dm_title_'.$model->msg_id.'">' . $model->title . '</td>';
                echo '  <td id="dm_content_'.$model->msg_id.'">' . $model->content . '</td>';
                echo '  <td id="dm_status_'.$model->msg_id.'">' . $model->status . '</td>';
                echo '  <td id="dm_fromuid_'.$model->msg_id.'">' . $model->fromuid . '</td>';
                echo '  <td id="dm_createtime_'.$model->msg_id.'">' . $model->createtime . '</td>';
                echo '  <td class="center">';
                echo '      <a id="view_btn" onclick="viewAction(' . $model->msg_id . ')" class="btn btn-primary btn-sm" href="#"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看</a>';
                echo '      <a id="edit_btn" onclick="editAction(' . $model->msg_id . ')" class="btn btn-primary btn-sm" href="#"> <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>';
                echo '      <a id="delete_btn" onclick="deleteAction(' . $model->msg_id . ')" class="btn btn-danger btn-sm" href="#"> <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>';
                echo '  </td>';
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
            		从<?= $pages->getPage() * $pages->getPageSize() + 1 ?>            		到 <?= ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ?  $pageCount : $pages->totalCount?>            		 共 <?= $pages->totalCount?> 条记录</div>
            	</div>
            </div>
          	<div class="col-sm-7">
              	<div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
              	<?= LinkPager::widget([
              	    'pagination' => $pages,
              	    'nextPageLabel' => '»',
              	    'prevPageLabel' => '«',
              	    'firstPageLabel' => '首页',
              	    'lastPageLabel' => '尾页',
              	]); ?>	
              	
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
				<h3>查看修改</h3>
			</div>
			<div class="modal-body">
                <?php $form = ActiveForm::begin(["id" => "user-message-form", "class"=>"form-horizontal", "action"=>Url::toRoute("user-message/save")]); ?>                      
                 
          <input type="hidden" class="form-control" id="msg_id" name="msg_id" />

          <input type="hidden" class="form-control" id="user_id" name="user_id" />

          <div id="title_div" class="form-group">
              <label for="title" class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title")?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" name="UserMessage[title]" placeholder="" />
              </div>
              <div class="clearfix"></div>
          </div>

          <div id="content_div" class="form-group">
              <label for="content" class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("content")?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="content" name="UserMessage[content]" placeholder="" />
              </div>
              <div class="clearfix"></div>
          </div>

          <div id="status_div" class="form-group">
              <label for="status" class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("status")?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="status" name="UserMessage[status]" placeholder="" />
              </div>
              <div class="clearfix"></div>
          </div>

          <input type="hidden" class="form-control" id="fromuid" name="fromuid" />

          <div id="createtime_div" class="form-group">
              <label for="createtime" class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("createtime")?></label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" id="createtime" name="UserMessage[createtime]" placeholder="" />
              </div>
              <div class="clearfix"></div>
          </div>
                    

			<?php ActiveForm::end(); ?>          
                </div>
			<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal">关闭</a> <a
					id="edit_dialog_ok" href="#" class="btn btn-primary">确定</a>
			</div>
		</div>
	</div>
</div>
<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->
 <script>
function orderby(field, op){
	 var url = window.location.search;
	 var optemp = field + " desc";
	 if(url.indexOf('orderby') != -1){
		 url = url.replace(/orderby=([^&?]*)/ig,  function($0, $1){ 
			 var optemp = field + " desc";
			 optemp = decodeURI($1) != optemp ? optemp : field + " asc";
			 return "orderby=" + optemp;
		   }); 
	 }
	 else{
		 if(url.indexOf('?') != -1){
			 url = url + "&orderby=" + encodeURI(optemp);
		 }
		 else{
			 url = url + "?orderby=" + encodeURI(optemp);
		 }
	 }
	 window.location.href=url; 
 }
 function searchAction(){
		$('#user-message-search-form').submit();
	}
 function viewAction(id){
		initModel(id, 'view', 'fun');
	}

 function initEditSystemModule(data, type){
	if(type == 'create'){
		$("#msg_id").val('');
		$("#user_id").val('');
		$("#title").val('');
		$("#content").val('');
		$("#status").val('');
		$("#fromuid").val('');
		$("#createtime").val('');
		
	}
	else{
		$("#msg_id").val(data.msg_id);
    	$("#user_id").val(data.user_id);
    	$("#title").val(data.title);
    	$("#content").val(data.content);
    	$("#status").val(data.status);
    	$("#fromuid").val(data.fromuid);
    	$("#createtime").val(data.createtime);
    	}
	if(type == "view"){
      $("#msg_id").attr({readonly:true,disabled:true});
      $("#user_id").attr({readonly:true,disabled:true});
      $("#title").attr({readonly:true,disabled:true});
      $("#content").attr({readonly:true,disabled:true});
      $("#status").attr({readonly:true,disabled:true});
      $("#fromuid").attr({readonly:true,disabled:true});
      $("#createtime").attr({readonly:true,disabled:true});
	$('#edit_dialog_ok').addClass('hidden');
	}
	else{
      $("#msg_id").attr({readonly:false,disabled:false});
      $("#user_id").attr({readonly:false,disabled:false});
      $("#title").attr({readonly:false,disabled:false});
      $("#content").attr({readonly:false,disabled:false});
      $("#status").attr({readonly:false,disabled:false});
      $("#fromuid").attr({readonly:false,disabled:false});
      $("#createtime").attr({readonly:false,disabled:false});
		$('#edit_dialog_ok').removeClass('hidden');
		}
		$('#edit_dialog').modal('show');
}

function initModel(id, type, fun){
	
	$.ajax({
		   type: "GET",
		   url: "<?=Url::toRoute('user-message/view')?>",
		   data: {"id":id},
		   cache: false,
		   dataType:"json",
		   error: function (xmlHttpRequest, textStatus, errorThrown) {
			    alert("出错了，" + textStatus);
			},
		   success: function(data){
			   initEditSystemModule(data, type);
		   }
		});
}
	
function editAction(id){
	initModel(id, 'edit');
}

function deleteAction(id){
	var ids = [];
	if(!!id == true){
		ids[0] = id;
	}
	else{
		var checkboxs = $('#data_table :checked');
	    if(checkboxs.size() > 0){
	        var c = 0;
	        for(i = 0; i < checkboxs.size(); i++){
	            var id = checkboxs.eq(i).val();
	            if(id != ""){
	            	ids[c++] = id;
	            }
	        }
	    }
	}
	if(ids.length > 0){
		admin_tool.confirm('请确认是否删除', function(){
		    $.ajax({
				   type: "GET",
				   url: "<?=Url::toRoute('user-message/delete')?>",
				   data: {"ids":ids},
				   cache: false,
				   dataType:"json",
				   error: function (xmlHttpRequest, textStatus, errorThrown) {
					    admin_tool.alert('msg_info', '出错了，' + textStatus, 'warning');
					},
				   success: function(data){
					   for(i = 0; i < ids.length; i++){
						   $('#rowid_' + ids[i]).remove();
					   }
					   admin_tool.alert('msg_info', '删除成功', 'success');
					   window.location.reload();
				   }
				});
		});
	}
	else{
		admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
	}
    
}

function getSelectedIdValues(formId)
{
	var value="";
	$( formId + " :checked").each(function(i)
	{
		if(!this.checked)
		{
			return true;
		}
		value += this.value;
		if(i != $("input[name='msg_id']").size()-1)
		{
			value += ",";
		}
	 });
	return value;
}

$('#edit_dialog_ok').click(function (e) {
    e.preventDefault();
	$('#user-message-form').submit();
});

$('#create_btn').click(function (e) {
    e.preventDefault();
    initEditSystemModule({}, 'create');
});

$('#delete_btn').click(function (e) {
    e.preventDefault();
    deleteAction('');
});

$('#user-message-form').bind('submit', function(e) {
	e.preventDefault();
	var id = $("#msg_id").val();
	var action = id == "" ? "<?=Url::toRoute('user-message/create')?>" : "<?=Url::toRoute('user-message/update')?>";
    $(this).ajaxSubmit({
    	type: "post",
    	dataType:"json",
    	url: action,
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert("出错了，" + textStatus);
        },
    	success: function(value)
    	{
        	if(value.errno == 0){
        		$('#edit_dialog').modal('hide');
                var optype = value.type;
                if (optype==1) {   //create
                    admin_tool.alert('msg_info', '添加成功', 'success');
                    window.location.reload();
                }else if (optype==2){       //update, 局部更新
                    var json = value.data;
                    for(var key in json){
                        var tagname = "dm_"+key+"_"+json["msg_id"];
                        var tagvalue = json[key];
                        $('#' + tagname).html(tagvalue);
                    }
                }
        	}
            else if(value.errno == 1){
                alert("数据保存出错: "+value.errors);
            }
        	else if(value.errno == 2){
                alert('提交数据有误: ');
            	var json = value.data;
        		for(var key in json){
        			$('#' + key).attr({'data-placement':'bottom', 'data-content':json[key], 'data-toggle':'popover'}).addClass('popover-show').popover('show');
        			
        		}
        	}

    	}
    });
});

 
</script>
<?php $this->endBlock(); ?>