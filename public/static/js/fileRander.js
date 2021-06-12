//【文件上传】控件渲染函数
function initFileInput(formName, uploadUrl ,delUrl ,patients_id ,form_id,preAddressArr,preInfo) {
    let formId = $('#' + formName);
    formId.fileinput({
        language: 'zh',                    //设置语言

        initialPreview: preAddressArr,     //预览地址
        initialPreviewAsData: true,        // 确定传入预览数据，而不是原生标记语言
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",//当检测到用于预览的不可读文件类型时，将在每个预览文件缩略图中显示的图标
        //预览文件信息
        initialPreviewConfig: preInfo,

        //自定义按钮样式
        fileActionSettings: {                               // 在预览窗口中为新选择的文件缩略图设置文件操作的对象配置
            showCancel:true,
            showDrag: false,
            showDownload: true,
        },
        showRemove: false,                 //不显示删除按钮
        showUpload: false,                 //不显示上传按钮
        browseClass: "btn btn-success",
        browseLabel: "选择文件",
        dropZoneEnabled: false,

        enctype: 'multipart/form-data',

        deleteUrl: delUrl,                 //删除操作的URL地址
        deleteExtraData:{patients_id: patients_id,form_id:form_id},

        overwriteInitial: false,           //不允许覆盖初始的预览，所以添加文件时不会覆盖
        maxFileSize: 1024000,               //文件最大不超过1000mb

        uploadAsync: true,                 //异步上传
        uploadUrl: uploadUrl, //服务器端上传处理程序
        uploadExtraData: {patients_id: patients_id,form_id:form_id}                //发送额外数据

    });
    //上传完成
    formId.on("fileuploaded", function (event, data,previewId) {
        //在上传成功事件中将服务器返回的所需数据，添加到该文件对应的div中
        console.log(data.response.fileid);
        $('#' + previewId).attr('fileid', data.response.fileid);
        var result = data.response; //后台返回的json
        layui.use('layer', function(){
            let layer = layui.layer;
            layer.msg(result.message);

        });

    });
    //错误提示
    formId.on("fileerror", function (event, data) {
        var result = data.response; //后台返回的json
        layui.use('layer', function(){
            let layer = layui.layer;
            layer.msg(result.error);

        });
    });
    //删除前提示
    formId.on('filepredelete', function(event, key, jqXHR, data) {
        if (!confirm("确定删除原文件？删除后不可恢复")) {
            return false;
        }
    });
    //成功删除
    formId.on('filedeleted', function(event, key,data) {
        layui.use('layer', function(){
            let layer = layui.layer;
            layer.msg(data.responseJSON.message);
        });
    });
    //删除预览
    formId.on('filepreremove', function (event, previewId, extra,data) {
        //在移除事件里取出所需数据，并执行相应的删除指令
        layui.use('layer', function(){
            let layer = layui.layer;
            layer.msg('如需删除服务器文件！请先刷新页面！');
        });
    });

}