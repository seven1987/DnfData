<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>

<style>
  #login_box{
    position: absolute;
    width:100%;
    height: 100%;
    top:0;
    bottom:0;
    background: #000000 url(/dist/img/background.jpg);
  }
  .login-box-body{
    position: relative;
    background-color: transparent;
    color:#ffffff;
    font-family:Microsoft YaHei;
  }
  .login-box{
    background-color:blue;
  }
  .login-box-msg{
    font-size: 40px;
  }
  #username,#password,#login_btn,#reset{
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    -moz-box-shadow: 0 0 8px rgba(200,200,200,0.8);
    -webkit-box-shadow: 0 0 8px rgba(200,200,200,0.8);
    box-shadow: 0 0 8px rgba(200,200,200,0.8);
  }
  .username-title,.password-title{
    font-weight: bold;
    font-size: 15px;;
  }
  .login-box-body .button{
    background: #FF3F3F;
    color:#FFFFFF;
  }
  .button{
    padding:5px 15px 5px;
    margin:2px;
    border:none;
    cursor: pointer;
    border-radius: 3px;;
  }

  #login_btn{
    margin-right: 120px;;
  }

</style>
<div id="login_box"></div>
<div class="login-box" >
  <div class="login-logo">
    <a href="<?=Url::toRoute('site/login')?>"><b>德玛西亚管理系统</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">管理员密码找回</p>
	<?php $form = ActiveForm::begin(['id' => 'login-form', 'action'=>Url::toRoute('site/resetpwd')]); ?>
    <!-- <form action="../../index2.html" method="post">   -->
    <span class="username-title">用户名</span>
      <div class="form-group has-feedback">
        <input name="username" id="username" type="text" class="form-control" placeholder="" />
      </div>
    <span class="email-title">邮箱</span>
      <div class="form-group has-feedback">
        <input name="email" id="email" type="text" class="form-control" placeholder="">
      </div>

    <input type="button" value="确定" class="button " id="login_btn" />
    <input type="reset" value="重置" class="button"  id="reset"/>
    <!-- </form>  -->
    <?php ActiveForm::end(); ?>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<div class="modal fade" id="confirm_dialog" tabindex="2" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog confirm-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>操作提示</h3>
            </div>
            <div id="confirm_content" class="modal-body">

            </div>
            <div class="modal-footer">
                <a id="confirm_dialog_cancel" href="#" class="btn btn-default" data-dismiss="modal">取消</a> <a
                        id="confirm_dialog_ok" class="btn btn-primary">确定</a>
            </div>
        </div>
    </div>
</div>
<script src="<?=Url::base()?>/plugins/jQuery/particles.min.js"></script>
<script src="<?=Url::base()?>/plugins/laydate/laydate.js"></script>
<script src="<?=Url::base()?>/dist/js/main.js"></script>
<script type="text/javascript">
  particlesJS('login_box',
      {
        "particles": {
          "number": {
            "value": 110,
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "#ffffff"
          },
          "shape": {
            "type": "circle",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            },
            "image": {
              "src": "img/github.svg",
              "width": 100,
              "height": 100
            }
          },
          "opacity": {
            "value": 0.5,
            "random": false,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": 1,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 20,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": true,
            "distance": 40,
            "color": "#fff",
            "opacity": 1,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": 3,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": true,
              "mode": "grab"
            },
            "onclick": {
              "enable": true,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 120,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 300
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true,
        "config_demo": {
          "hide_card": false,
          "background_color": "#b61924",
          "background_image": "",
          "background_position": "50% 50%",
          "background_repeat": "no-repeat",
          "background_size": "cover"
        }
      }

  );

  $("#username").focus();
  document.onkeydown = function(e){
    if(!e) e = window.event;
    if((e.keyCode || e.which) == 13){
        $('#login_btn').trigger('click');
    }
  };

$('#login_btn').click(function () {
    $('.popover').hide();
    var username = $("#username").val();
    var email = $("#email").val();
    if (username.replace(/(^\s*)|(\s*$)/g,"")=="" || email.replace(/(^\s*)|(\s*$)/g,"")=="" ) {
        $('#username').attr({'data-placement':'top', 'data-content':'<span class="text-danger">用户名或邮箱不能为空</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (/\<|\>|\(|\)|=|\||"|\$|\%|\\|\//i.test(username)) {
        $('#username').attr({'data-placement':'top', 'data-content':'<span class="text-danger">用户名含有非法字符</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    if (!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i.test(email)) {
        $('#email').attr({'data-placement':'top', 'data-content':'<span class="text-danger">邮箱格式不正确</span>', 'data-toggle':'popover'}).addClass('popover-show').popover({html : true }).popover('show');
        return false;
    }
    $.ajax({
        type: "post",
        dataType:"json",
        url: "<?=Url::toRoute('site/resetpwd')?>",
        data: {"username": username, "email": email, "_csrf": $("input[name=_csrf]").val()},
        cache: false,
        beforeSend:function () {
            $('.popover').hide();
        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            admin_tool.confirm("出错了，" + textStatus, function () {});
        },
        success: function (data) {
            if(data.code == 0){
                admin_tool.confirm(data.msg, function () {
                    window.location="/";
                });
            } else {
                admin_tool.confirm(data.msg, function () {});
            }
        }
    });
});

</script>
