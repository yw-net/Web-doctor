

//带选项卡【保存】按钮函数
function saveTab(saveTo,formName,liClass){
    let patients_id = $('#patients_id').val();
    let form_id = parseInt($(''+liClass+'.layui-this').attr('lay-id'));
    let form_data = decodeURIComponent($(""+formName + form_id +"").serialize());
    console.log(form_data);

    let DataDeal = {
        //将从form中通过$('#form').serialize()获取的值转成json字符串
        formToJson: function (data) {
            data=data.replace(/&/g,"\",\"");
            data=data.replace(/=/g,"\":\"");
            data="{\""+data+"\"}";
            return data;
        },
        //json对象相同key合并value值
        jsonData: function (s) {
            var kv = {}, m, reg = /"[^"]+":"[^"]+"/gi;
            var m = s.match(reg);
            if (m == null){
                return false;
            }

            var o={};
            for(var i=0;i<m.length;i++){
                kv=m[i].match(/"[^"]+"/gi);
                var _arr1=[];;
                for(var j=0;j<kv.length; j++){
                    _arr1.push(kv[j].replace(/"/g,''));
                }
                if(_arr1[0] in o){
                    if(typeof(o[_arr1[0]])=='string')
                    {
                        o[_arr1[0]]=[o[_arr1[0]]]
                    };
                    o[_arr1[0]].push(_arr1[1])
                }else{
                    o[_arr1[0]]=_arr1[1]
                }
            }
            return o;
        },
    };

    let toData=DataDeal.formToJson(form_data);//转化为json字符串

    let jsonData = DataDeal.jsonData(toData);//json字符串相同key合并value值并转化为json对象

    $.ajax({
        type:"POST",
        url:saveTo,
        dataType:'JSON',
        data: {
            patients_id:patients_id,
            form_data:jsonData,
            form_id:form_id
        },
        success:function (data) {
            layui.use('layer', function(){
                let layer = layui.layer;
                if(data.status == 1){
                    layer.msg(data.msg, {icon: 1});
                    setTimeout('location.reload()', 1500 )
                }
                else {
                    layer.msg(data.msg, {icon: 5});
                }
            });
        }
    });
}

//带选项卡【删除】按钮函数
function delBtn(delFn,formId,formName){
    layui.use('element', function(){

        let element = layui.element;
        let $ = layui.jquery;

        let patients_id = $('#patients_id').val();

        element.tabDelete(formName, formId);

        $.ajax({
            type:"POST",
            url:delFn,
            dataType:'JSON',
            data: {
                patients_id:patients_id,
                form_id:formId
            },
            success:function (data) {
                layui.use('layer', function(){
                    let layer = layui.layer;
                    if(data.status == 1){
                        layer.msg(data.msg, {icon: 1});
                        location.reload();
                    }
                    else {
                        layer.msg(data.msg, {icon: 5});
                    }
                });
            }
        });

    });

}

//常规【保存】按钮函数
