$(function () {
    //初始化Bootstrap-checkbox
    $('.team-checkbox').checkboxpicker({});

    //表单提交前置操作
    $('#submitForm').on('click',function(){
        var player_id = $('#palerID').val();
        var player_no_id = $('#palerNoID').val();
        $('.team-checkbox').each(function(){
            var value = $(this).val();
            $(this).prop('checked', true);
            $(this).val(value);
        })
        $('#palerID').val(player_id);
        $('#palerNoID').val(player_no_id);
        $("#teamForm").submit();
    })

    //初始化一血队伍数据
    function bloodInit() {
        $('.playerBtn').hide();
        $('#team'+$('#first_blood').val()).show();
        //alert($("input[name='teamID[first_blood]']").data('offValue'))
        if($('#first_blood').val() == $("input[name='teamID[first_blood]']").data('offValue') ){
            $('#teamNo'+$("input[name='teamID[first_blood]']").data('onValue')).show();
        }else{
            $('#teamNo'+$("input[name='teamID[first_blood]']").data('offValue')).show();
        }
    }
    bloodInit();
    

    //一血队伍选择操作后续
    $('#first_blood').on('change',function(){
        $('.playerBtn').hide();
        $('#team'+$(this).val()).show();
        if($('#first_blood').val() == $("input[name='teamID[first_blood]']").data('offValue') ){
            $('#teamNo'+$("input[name='teamID[first_blood]']").data('onValue')).show();
        }else{
            $('#teamNo'+$("input[name='teamID[first_blood]']").data('offValue')).show();
        }
    })

    //选手获得按钮点击后续
    $('.player-btn').on('click',function(){
        $('.player-btn').removeClass('btn-info');
        $(this).addClass('btn-info');
        $('#palerID').val($(this).val());
    })
    //选手送出按钮点击后续
    $('.playerNo-btn').on('click',function(){
        $('.playerNo-btn').removeClass('btn-info');
        $(this).addClass('btn-info');
        $('#palerNoID').val($(this).val());
    })
});