//病人【既往病史】页面赋值控制js
$(document).ready(function () {
//术前合并症
    //循环系统
    if (resultshuqian[0].circular == 1){
        $('#xunhuan1').attr('checked','checked');
        $('#ixunhuan').css('display','block');
        $('#ixunhuan').val(resultshuqian[0].icircular);
    }else {$('#xunhuan2').attr('checked','checked')}
    //呼吸系统
    if (resultshuqian[0].respiratory == 1){
        $('#huxi1').attr('checked','checked');
        $('#ihuxi').css('display','block');
        $('#ihuxi').val(resultshuqian[0].irespiratory);
    }else {$('#huxi2').attr('checked','checked')}
    //消化系统
    if (resultshuqian[0].digestive == 1){
        $('#xiaohua1').attr('checked','checked');
        $('#ixiaohua').css('display','block');
        $('#ixiaohua').val(resultshuqian[0].idigestive);
    }else {$('#xiaohua2').attr('checked','checked')}
    //泌尿系统
    if (resultshuqian[0].urologic == 1){
        $('#miliao1').attr('checked','checked');
        $('#imiliao').css('display','block');
        $('#imiliao').val(resultshuqian[0].iurologic);
    }else {$('#miliao2').attr('checked','checked')}
    //内分泌系统
    if (resultshuqian[0].endocrinological == 1){
        $('#neifenmi1').attr('checked','checked');
        $('#ineifenmi').css('display','block');
        $('#ineifenmi').val(resultshuqian[0].iendocrinological);
    }else {$('#neifenmi2').attr('checked','checked')}
    //免疫系统
    if (resultshuqian[0].immune == 1){
        $('#mianyi1').attr('checked','checked');
        $('#imianyi').css('display','block');
        $('#imianyi').val(resultshuqian[0].iimmune);
    }else {$('#mianyi2').attr('checked','checked')}
    //血液系统
    if (resultshuqian[0].blood == 1){
        $('#xueye1').attr('checked','checked');
        $('#ixueye').css('display','block');
        $('#ixueye').val(resultshuqian[0].iblood);
    }else {$('#xueye2').attr('checked','checked')}
    //术前其他
    if (resultshuqian[0].others == 1){
        $('#shuqianother1').attr('checked','checked');
        $('#ishuqianother').css('display','block');
        $('#ishuqianother').val(resultshuqian[0].iothers);
    }else {$('#shuqianother2').attr('checked','checked')}
//个人史
    //吸烟
    if (resultgeren[0].smoking == 1){
        $('#xiyan1').attr('checked','checked');
        $('#ixiyan').css('display','block');
        $('#ixiyan').val(resultgeren[0].ismoking);
    }else {$('#xiyan2').attr('checked','checked')}
    //饮酒
    if (resultgeren[0].drinking == 1){
        $('#yingjiu1').attr('checked','checked');
        $('#iyingjiu').css('display','block');
        $('#iyingjiu').val(resultgeren[0].idrinking);
    }else {$('#yingjiu2').attr('checked','checked')}
    //手术史
    if (resultgeren[0].surgical == 1){
        $('#shoushu1').attr('checked','checked');
        $('#ishoushu').css('display','block');
        $('#ishoushu').val(resultgeren[0].isurgical);
    }else {$('#shoushu2').attr('checked','checked')}
    //肿瘤史
    if (resultgeren[0].tumor == 1){
        $('#zhongliu1').attr('checked','checked');
        $('#izhongliu').css('display','block');
        $('#izhongliu').val(resultgeren[0].itumor);
    }else {$('#zhongliu2').attr('checked','checked')}
    //致癌因素
    if (resultgeren[0].carcinogenic_factors == 1){
        $('#zhiai1').attr('checked','checked');
        $('#izhiai').css('display','block');
        $('#izhiai').val(resultgeren[0].icarcinogenic_factors);
    }else {$('#zhiai2').attr('checked','checked')}
    //月经史

    if (resultyuejing[0].regular == 1){
        $('#yuejing-guilv-radio1').attr('checked','checked');
    }else {$('#yuejing-guilv-radio2').attr('checked','checked')}
//家族史
    //是否有家族史
    if (resultjiazu[0].jiazu == 1){
        layui.jquery('input[name="radiojiazu"]').attr('checked', 'checked');
        $('#checkjiazu').css('display','block');
    }
    //头颈部
    if (resultjiazu[0].head_neck == 1){
        $('#inlineCheckbox1').attr('checked','checked');
    }
    //食管
    if (resultjiazu[0].esophageal == 1){
        $('#inlineCheckbox2').attr('checked','checked');
    }
    //肺
    if (resultjiazu[0].lung == 1){
        $('#inlineCheckbox3').attr('checked','checked');
    }
    //纵隔
    if (resultjiazu[0].mediastinal == 1){
        $('#inlineCheckbox4').attr('checked','checked');
    }
    //胃
    if (resultjiazu[0].stomach == 1){
        $('#inlineCheckbox5').attr('checked','checked');
    }
    //肝脏
    if (resultjiazu[0].hepatic == 1){
        $('#inlineCheckbox6').attr('checked','checked');
    }
    //胰腺
    if (resultjiazu[0].pancreatic == 1){
        $('#inlineCheckbox7').attr('checked','checked');
    }
    //小肠
    if (resultjiazu[0].small_intestina == 1){
        $('#inlineCheckbox8').attr('checked','checked');
    }
    //结肠
    if (resultjiazu[0].colonic == 1){
        $('#inlineCheckbox9').attr('checked','checked');
    }
    //直肠
    if (resultjiazu[0].transrectal == 1){
        $('#inlineCheckbox10').attr('checked','checked');
    }
    //肾
    if (resultjiazu[0].renal == 1){
        $('#inlineCheckbox11').attr('checked','checked');
    }
    //膀胱
    if (resultjiazu[0].vesical == 1){
        $('#inlineCheckbox12').attr('checked','checked');
    }
    //前列腺
    if (resultjiazu[0].prostatic == 1){
        $('#inlineCheckbox13').attr('checked','checked');
    }
    //生殖系统
    if (resultjiazu[0].reproductive_system == 1){
        $('#inlineCheckbox14').attr('checked','checked');
    }
    //淋巴系统
    if (resultjiazu[0].lymphatic_system == 1){
        $('#inlineCheckbox15').attr('checked','checked');
    }
    //乳腺
    if (resultjiazu[0].galactophore == 1){
        $('#inlineCheckbox16').attr('checked','checked');
    }
    //子宫
    if (resultjiazu[0].uterine == 1){
        $('#inlineCheckbox17').attr('checked','checked');
    }
    //黑色素瘤
    if (resultjiazu[0].melanoma == 1){
        $('#inlineCheckbox18').attr('checked','checked');
    }
    //肉瘤
    if (resultjiazu[0].sarcoma == 1){
        $('#inlineCheckbox19').attr('checked','checked');
    }
    //其他
    if (resultjiazu[0].others == 1){
        $('#inlineCheckbox20').attr('checked','checked');
        $('#jiazuother').css('display','block');
        $('#jiazuother').val(resultjiazu[0].iothercheck);
    }else {$('#jiazuother').val(null);}
    console.log()
})