$(function () {

    $('.position').checkboxpicker({});

    $('.game_form').on('change',function(){


        var data = {
            name : $(this).attr('name'),
            value : $(this).val(),
            race_id : $('#race').val(),
            round_id : $('#round_id').val()
        };

        console.log(data);

        $.post(GAME_INPUT_URL,{aov:data,race_id:$('#race').val()},function(res){
            if(res.status == 0){
                console.log(res.message);
            }
        })
    })
})