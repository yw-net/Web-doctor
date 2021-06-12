$(".form_datetime").datetimepicker({
    format: 'yyyy-mm-dd',
    language:'zh-CN',
    weekStart: 1,
    todayBtn:  1,   //今日日期按钮
    autoclose: 1,   //自动关闭
    todayHighlight: 1,   //高亮今日日期
    startView: 2,       //从日期视图开始
    minView: 2,
    bootcssVer:3,
});
function clearFormBir(){
    $('#inputDate').val('选择日期');
}
function clearFormIn(){
    $('#inputDateInHospital').val('选择日期');
}
function clearFormOut(){
    $('#inputDateOutHospital').val('选择日期');
}
