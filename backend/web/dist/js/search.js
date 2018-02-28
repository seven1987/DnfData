
var source=[];
var elemCSS={ focus:{'color':'black','background':'#ccc'}, blur:{'color':'black','background':'transparent'} };
var inputUse=document.getElementById("team_id");
var showUse=document.getElementById("showteam");
var  hiddenContent=document.getElementById("teamhidden");

var inputUses=document.getElementById("race_id");
var showUses=document.getElementById("showrace");
var hiddenContents=document.getElementById("racehidden");

function inputFocus(){
    showUse.style.display="";
    //inputUses.innerHTML='';
    //showUses.innerHTML='';
    var match_id =document.getElementById("match_id");
    //console.log(TeamArray);
    this.timer=setInterval(function(){
        //console.log("test");
        if(inputUse.value!=''){
            //console.log(2);
            //检查文本框的当前值与以前的值是否有变化
            if(inputUse.value!=inputValue){
                //如果文本框当前值与之前的值不同，记录当前值，已被下次调用时使用
                inputValue=inputUse.value;
                //清除上次调用显示的结果
                showUse.innerHTML='';
                if(inputValue!=''){
                      source=TeamArray;
                    quickExpr=RegExp('^.*'+inputValue,'i');
                    if(source){
                        match(quickExpr,inputValue,source);
                    }
                }
            }
        }
        else{
            inputValue=inputUse.value;
            showUse.innerHTML='';
            table=document.createElement('table');
            table.id='selector';
            table.style.width='424px';
            table.style.background="#FFFFFF";
            for(var i in TeamArray){
                    tr=document.createElement('tr');
                    td=document.createElement('td');
                    //在td中插入<a href="javascript:void(null);"><span>数据项</span></a>
                    td.innerHTML = '<a  style="display:block;width:260px;" href="javascript:void(null);" id ='+i+'>'+TeamArray[i]+'</a>';
                    tr.appendChild(td);
                    table.appendChild(tr);
                    showUse.appendChild(table);
            }
        }
    },200);

}




function match(quickExpr,value,source){
    var table=null;
    var tr=null;
    var td=null;
    table=document.createElement('table');
    table.id='selector';
    table.style.width='260px';
    table.style.background="#FFFFFF";
    for(var i in source){
        //再次检验数据是否为空并且用正则取数据
        if(value.length>0 && quickExpr.exec(source[i])!=null){
            tr=document.createElement('tr');
            td=document.createElement('td');

            //在td中插入<a href="javascript:void(null);"><span>数据项</span></a>
            td.innerHTML = '<a  style ="display:block;width:260px;" href="javascript:void(null);" id ='+i+'>'+source[i]+'</a>';
            tr.appendChild(td);
            table.appendChild(tr);
            showUse.appendChild(table);
        }
    }
    //检验table下面的a标签的数量，以此确定是否将“提示”列表显示
    if(showUse.getElementsByTagName('a').length){
        showUse.style.display="";
    }else{
        showUse.style.display="none";
    }
}
function inputKeydown(event){
    event = event || window.event;
    //如果按了down键
    if(event.keyCode==40){
        //如果“提示”列表已经显示,则把焦点切换到列表中的第一个数据项上
        if(showUse.style.display==""){
            showUse.getElementsByTagName('a')[0].focus();
        }else{   //如果“提示”列表未显示,则把焦点依旧留在文本框中
            inputUse.focus();
        }
    }

    //如果按了up键
    else if(event.keyCode==38){
        //如果“提示”列表已经显示,则把焦点切换到列表中的最后一个数据项上
        if(showUse.style.display==""){
            showUse.getElementsByTagName('a')[showUse.getElementsByTagName('a').length-1].focus();
        }else{     //如果“提示”列表未显示,则把焦点依旧留在文本框中
            inputUse.focus();
        }
    }
    //如果按了tab键，此时的情况与“百度首页”的处理情况一样
    else if(event.keyCode==9){
        showUse.innerHTML='';
        showUse.style.display="none";
    }
}

function inputBlur(){

    //由于焦点已经离开了文本框，则取消setInterval
    clearInterval(this.timer);
    //记住当前有焦点的选项
    var current=0;
    //当前table下面的a标签的个数
    var aArray=showUse.getElementsByTagName('a');
    var len=aArray.length-1;
    var select=document.getElementById("selector");

    //定义“选项”的onclick事件
    var aClick = function(){
        //由于“选项”上触发了click事件，this就是指a标签，则把a标签包含的数据赋值给文本框
        inputUse.value=this.childNodes[0].data;

        hiddenContent.value=this.id;

        //将文本框的当前值更新到记录以前值的变量中
        inputValue=inputUse.value;
        //由于上面已经选出合适的数据项，则清空table下的内容，并关闭“提示”列表
        showUse.innerHTML='';
        showUse.style.display='none';
        //将焦点移回文本框
        inputUse.focus();
    };

    //定义“选项”的onfocus事件
    var aFocus = function(){
        for(var i=len; i>=0; i--){
            //this是a，this.parentNode是td，select.children[i].children[0]是table.tr.td
            if(this.parentNode===select.childNodes[i].childNodes[0]){
                //如果是同一个td，则将current的值置为焦点所在位置的值
                current = i;
                break;
            }
        }
        //添加有焦点的效果
        for(var k in elemCSS.focus){
            this.style[k] = elemCSS.focus[k];
        }
    };
    //定义“选项”的onblur事件
    var aBlur= function(){
        //添加无焦点的效果
        for(var k in elemCSS.blur)
            this.style[k] = elemCSS.blur[k];
    };


    //定义“选项”的onKeydown是事件
    var aKeydown = function(event){
        //兼容IE
        event = event || window.event;
        //如果在选择数据项时按了tab键，此时的情况与“百度首页”的处理情况一样
        if(event.keyCode===9){
            showUse.innerHTML='';
            showUse.style.display = 'none';
            inputUse.focus();
        }
        //如果按了down键
        else if(event.keyCode==40){
            //向下移动，准备移动焦点
            current++;
            //如果当前焦点在最后一个数据项上，用户用按了down键，则循环向上，回到文本框上
            if(current>len){
                current=-1;
                inputUse.focus();
            }else{
                select.getElementsByTagName('a')[current].focus();
            }
        }
        //如果按了up键
        else if(event.keyCode==38){
            //向上移动，准备移动焦点
            current--;
            //如果当前焦点在文本框上，用户用按了up键，则循环向下，回到最后一个数据项上
            if(current<0){
                inputUse.focus();
            }else{
                select.getElementsByTagName('a')[current].focus();
            }
        }
    };


    //将“选项”的事件与相应的处理函数绑定
    for(var i=0; i<aArray.length; i++){
        aArray[i].onclick = aClick;
        aArray[i].onfocus = aFocus;
        aArray[i].onblur = aBlur;
        aArray[i].onkeydown = aKeydown;
    }
}




function inputFocusrace(){
    showUses.style.display="";
    var data =document.getElementById("teamhidden").value;
    changeType(data,'team');

    var team_id =document.getElementById("team_id");




    this.timer=setInterval(function(){
        if(inputUses.value!=''){
            //检查文本框的当前值与以前的值是否有变化
            if(inputUses.value!=inputValue){
                inputValues=inputUses.value;
                showUses.innerHTML='';
                if(inputValues!=''){
                    //定义JS的RegExp对象，查询以inputValue开头的数据
                    quickExpr=RegExp('^.*'+inputValues,'i');
                    //此处如果通过ajax取数据，则适当修改数据源即可
//                         source = getData(inputValue);
                    source=RaceArray;

                    if(source){matchrace(quickExpr,inputValues,source);}
                }
            }
        }
        else{
            inputValues=inputUse.value;
            showUses.innerHTML='';
            table=document.createElement('table');
            table.id='selector';
            table.style.width='424px';
            table.style.background="#FFFFFF";
            for(var i in RaceArray){
                tr=document.createElement('tr');
                td=document.createElement('td');
                //在td中插入<a href="javascript:void(null);"><span>数据项</span></a>
                td.innerHTML = '<a  style="display:block;width:360px;" href="javascript:void(null);" id ='+i+'>'+RaceArray[i]+'</a>';
                tr.appendChild(td);
                table.appendChild(tr);
                showUses.appendChild(table);
            }
        }
    },200)
}

function matchrace(quickExpr,value,source){
    var table=null;
    var tr=null;
    var td=null;
    table=document.createElement('table');
    table.id='selector';
    table.style.width='100%';
    table.style.background="#ECF0F5";
    for(var i in source){
        //再次检验数据是否为空并且用正则取数据
        if(value.length>0 && quickExpr.exec(source[i])!=null){
            tr=document.createElement('tr');
            td=document.createElement('td');

            //在td中插入<a href="javascript:void(null);"><span>数据项</span></a>
            td.innerHTML = '<a href="javascript:void(null);" id ='+i+'>'+source[i]+'</a>';
            tr.appendChild(td);
            table.appendChild(tr);
            showUses.appendChild(table);
        }
    }
    //检验table下面的a标签的数量，以此确定是否将“提示”列表显示
    if(showUses.getElementsByTagName('a').length){
        showUses.style.display="";
    }else{
        showUses.style.display="none";
    }
}
function inputKeydownrace(event){
    event = event || window.event;
    //如果按了down键
    if(event.keyCode==40){
        //如果“提示”列表已经显示,则把焦点切换到列表中的第一个数据项上
        if(showUses.style.display==""){
            showUses.getElementsByTagName('a')[0].focus();
        }else{   //如果“提示”列表未显示,则把焦点依旧留在文本框中
            inputUses.focus();
        }
    }

    //如果按了up键
    else if(event.keyCode==38){
        //如果“提示”列表已经显示,则把焦点切换到列表中的最后一个数据项上
        if(showUses.style.display==""){
            showUses.getElementsByTagName('a')[showUse.getElementsByTagName('a').length-1].focus();
        }else{     //如果“提示”列表未显示,则把焦点依旧留在文本框中
            inputUses.focus();
        }
    }
    //如果按了tab键，此时的情况与“百度首页”的处理情况一样
    else if(event.keyCode==9){
        showUses.innerHTML='';
        showUses.style.display="none";
    }
}

function inputBlurrace(){
    //由于焦点已经离开了文本框，则取消setInterval
    clearInterval(this.timer);
    //记住当前有焦点的选项
    var current=0;
    //当前table下面的a标签的个数
    var aArray=showUses.getElementsByTagName('a');
    var len=aArray.length-1;
    var select=document.getElementById("selector");

    //定义“选项”的onclick事件
    var aClick = function(){
        //由于“选项”上触发了click事件，this就是指a标签，则把a标签包含的数据赋值给文本框
        inputUses.value=this.childNodes[0].data;

        hiddenContents.value=this.id;

        //将文本框的当前值更新到记录以前值的变量中
        inputValues=inputUses.value;
        //由于上面已经选出合适的数据项，则清空table下的内容，并关闭“提示”列表
        showUses.innerHTML='';
        showUses.style.display='none';
        //将焦点移回文本框
        inputUses.focus();
    };

    //定义“选项”的onfocus事件
    var aFocus = function(){
        for(var i=len; i>=0; i--){
            //this是a，this.parentNode是td，select.children[i].children[0]是table.tr.td
            if(this.parentNode===select.childNodes[i].childNodes[0]){
                //如果是同一个td，则将current的值置为焦点所在位置的值
                current = i;
                break;
            }
        }
        //添加有焦点的效果
        for(var k in elemCSS.focus){
            this.style[k] = elemCSS.focus[k];
        }
    };
    //定义“选项”的onblur事件
    var aBlur= function(){
        //添加无焦点的效果
        for(var k in elemCSS.blur)
            this.style[k] = elemCSS.blur[k];
    };


    //定义“选项”的onKeydown是事件
    var aKeydown = function(event){
        //兼容IE
        event = event || window.event;
        //如果在选择数据项时按了tab键，此时的情况与“百度首页”的处理情况一样
        if(event.keyCode===9){
            showUses.innerHTML='';
            showUses.style.display = 'none';
            inputUses.focus();
        }
        //如果按了down键
        else if(event.keyCode==40){
            //向下移动，准备移动焦点
            current++;
            //如果当前焦点在最后一个数据项上，用户用按了down键，则循环向上，回到文本框上
            if(current>len){
                current=-1;
                inputUses.focus();
            }else{
                select.getElementsByTagName('a')[current].focus();
            }
        }
        //如果按了up键
        else if(event.keyCode==38){
            //向上移动，准备移动焦点
            current--;
            //如果当前焦点在文本框上，用户用按了up键，则循环向下，回到最后一个数据项上
            if(current<0){
                inputUses.focus();
            }else{
                select.getElementsByTagName('a')[current].focus();
            }
        }
    };

    //将“选项”的事件与相应的处理函数绑定
    for(var i=0; i<aArray.length; i++){
        aArray[i].onclick = aClick;
        aArray[i].onfocus = aFocus;
        aArray[i].onblur = aBlur;
        aArray[i].onkeydown = aKeydown;
    }
}


