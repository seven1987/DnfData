<!-- Main content -->
<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use backend\assets\MainAsset;

MainAsset::addPageScript($this, 'dist/js/site/index.js');
?>

<script>
    var SITE_VIEW   = "<?=Url::toRoute('site/save-notice')?>";
    var _CSRF = "<?= Yii::$app->getRequest()->getCsrfToken(); ?>";
</script>

<section class="content">
	<!-- Small boxes (Stat box) -->

    <?php  if($type==1){?>
    <div class="changetype">
        <span id="commontype" class="commontype typeselect">平台端首页</span>
        <span id="agenttype" class="agenttype">代理端首页</span>
    </div>
    <?php }?>
    <div id="anouncement" >

            <?php if($type==1 || $type==3){?>
        <div id="anouncecommon" class="anouncecommon anouceactive">
            <div id="titlecommon"><?php if(isset($anounce['common']['title'])){echo $anounce['common']['title'];}else{echo "公告";}?></div>
            <div id="anouncecontent" style="color:#FFFFFF;font-size:16px;"><div  id="anoucetitle" readonly="readonly" ><?php if(isset($anounce['common']['content'])){echo $anounce['common']['content'];}else{echo "点击编辑输入公告内容...";}?></div></div>
            <div class="anouncetime" >编辑于: <span id="anouncetime"><?php if(isset($anounce['common']['time'])){echo $anounce['common']['time'];}else{echo "";}?></span></div>
        </div>
        <?php }?>

        <?php if($type==1 || $type==2 ){?>
        <div id="anounceagent" class="anounceagent <?php if($type==2){echo 'anouceactive';}?>" >
            <div id="titlecagent"><?php if(isset($anounce['agent']['title'])){echo $anounce['agent']['title'];}else{echo "";}?></div>
            <div id="anouncecontentagent" style=""><div  id="anoucetitleagent" readonly="readonly" ><?php if(isset($anounce['agent']['content'])){echo $anounce['agent']['content'];}else{echo "";}?></div></div>
            <div class="anouncetimeagent" >编辑于: <span id="anouncetimeagent"><?php if(isset($anounce['agent']['time'])){echo $anounce['agent']['time'];}else{echo "";}?></span></div>
        </div>
        <?php }?>


        <?php  if($type==1){?>
            <a id="editanouce" class="btn-common-operate-2"><div class="edit-common-image"></div>编辑</a>
        <?php }?>

        <div id="editcontent" class="editcontent">
            <span id="closewindow">×</span><br/>
            <span class="edittitle">公告名称:</span><input type="text" class="titletext" name="title" value=""  placeholder="名称"><br/>
            <div class="contentbody">
                <span class="edittitle contenttile">公告内容:</span>
                <div class="editbody">
                    <textarea id="edittext" name="content" >点击编辑更新公告</textarea>
                </div>
            </div>

            <a id="saveanouce">保存</a>
        </div>
        <input type="hidden" name="contenttype" id="contenttype" value="anouncecommon">
        <input type="hidden" id="updatetime" name="updatetime" value="">
    </div>




	<div class="row">
		<div class="col-md-12">
		<div class="box">
            <div class="box-header">
              <h3 class="box-title site-title">系统信息</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body site-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 200px">名称</th>
                  <th>信息</th>
                  <th style="width: 200px">说明</th>
                </tr>
                <?php 
                    $count = 1;
                    if($sysInfo)
                    {
                        foreach($sysInfo as $info){
                            echo '<tr>';
                            echo '  <td>'. $count .'</td>';
                            echo '  <td>'.$info['name'].'</td>';
                            echo '  <td>'.$info['value'].'</td>';
                            echo '  <td></td>';
                            echo '</tr>';
                            $count++;
                        }
                    }

    			   ?>
              </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              
            </div>
          </div>
          <!-- /.box -->
		</div>
		
		
	</div>
	<!-- /.row -->
	<!-- Main row -->
	<div class="row">
		
	</div>
	<!-- /.row (main row) -->
    <?= "<script> var url = '".$url."'; </script>"?>
</section>
<!-- /.content -->