<?php


namespace app\controller;


use app\model\postoperative_complications_erci;
use app\model\postoperative_complications_icu;
use app\model\postoperative_complications_shuhou;
use app\model\postoperative_complications_shuzhong;

class PostoperativeComplicationsSave extends Base
{
    //更新、保存术中并发症
    public function shuZhongSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $shuzhong = new postoperative_complications_shuzhong();

        //id
        $shuzhong->patients_id=$patients_id;

        //判断数据库当前患者下form_id是否存在
        $shuzhong_find = postoperative_complications_shuzhong::where('patients_id',$patients_id)->find();
        //更新数据
        if ($shuzhong_find){
            $shuzhong_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['shuzhongdachuxue_chuxueliang'])){
                $shuzhong_find->shuzhongdachuxue_chuxueliang = $form_data['shuzhongdachuxue_chuxueliang'];
            }
            else{$shuzhong_find->shuzhongdachuxue_chuxueliang = null;}
            if (isset($form_data['shuzhongdachuxue_yuanyin'])){
                $shuzhong_find->shuzhongdachuxue_yuanyin = $form_data['shuzhongdachuxue_yuanyin'];
            }
            else{$shuzhong_find->shuzhongdachuxue_yuanyin = null;}
            if (isset($form_data['shuzhonggaoxueya_input1'])){
                $shuzhong_find->shuzhonggaoxueya_input1 = $form_data['shuzhonggaoxueya_input1'];
            }
            else{$shuzhong_find->shuzhonggaoxueya_input1 = null;}
            if (isset($form_data['shuzhonggaoxueya_input2'])){
                $shuzhong_find->shuzhonggaoxueya_input2 = $form_data['shuzhonggaoxueya_input2'];
            }
            else{$shuzhong_find->shuzhonggaoxueya_input2 = null;}
            if (isset($form_data['shuzhonggaoxueya_chixushijian'])){
                $shuzhong_find->shuzhonggaoxueya_chixushijian = $form_data['shuzhonggaoxueya_chixushijian'];
            }
            else{$shuzhong_find->shuzhonggaoxueya_chixushijian = null;}
            if (isset($form_data['shuzhongdixueya_input1'])){
                $shuzhong_find->shuzhongdixueya_input1 = $form_data['shuzhongdixueya_input1'];
            }
            else{$shuzhong_find->shuzhongdixueya_input1 = null;}
            if (isset($form_data['shuzhongdixueya_input2'])){
                $shuzhong_find->shuzhongdixueya_input2 = $form_data['shuzhongdixueya_input2'];
            }
            else{$shuzhong_find->shuzhongdixueya_input2 = null;}
            if (isset($form_data['shuzhongdixueya_chixushijian'])){
                $shuzhong_find->shuzhongdixueya_chixushijian = $form_data['shuzhongdixueya_chixushijian'];
            }
            else{$shuzhong_find->shuzhongdixueya_chixushijian = null;}
            $shuzhong_find->jixinghuxidaogenzu = $form_data['jixinghuxidaogenzu'];
            $shuzhong_find->diyangxuezheng = $form_data['diyangxuezheng'];
            $shuzhong_find->shuzhongdachuxue_check = $form_data['shuzhongdachuxue_check'];
            $shuzhong_find->gaotansuanxuezheng = $form_data['gaotansuanxuezheng'];
            $shuzhong_find->kongqisuansai = $form_data['kongqisuansai'];
            $shuzhong_find->shuzhongxiuke = $form_data['shuzhongxiuke'];
            $shuzhong_find->xinlvshichang_check = $form_data['xinlvshichang_check'];
            $shuzhong_find->xinlvshichang_select = $form_data['xinlvshichang_select'];
            $shuzhong_find->shuzhongsunshang_check = $form_data['shuzhongsunshang_check'];
            $shuzhong_find->shuzhongsunshang_select = $form_data['shuzhongsunshang_select'];
            $shuzhong_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $shuzhong->created_time = date('Y-m-d');
        if (isset($form_data['shuzhongdachuxue_chuxueliang'])){
            $shuzhong->shuzhongdachuxue_chuxueliang = $form_data['shuzhongdachuxue_chuxueliang'];
        }
        if (isset($form_data['shuzhongdachuxue_yuanyin'])){
            $shuzhong->shuzhongdachuxue_yuanyin = $form_data['shuzhongdachuxue_yuanyin'];
        }
        if (isset($form_data['shuzhonggaoxueya_input1'])){
            $shuzhong->shuzhonggaoxueya_input1 = $form_data['shuzhonggaoxueya_input1'];
        }
        if (isset($form_data['shuzhonggaoxueya_input2'])){
            $shuzhong->shuzhonggaoxueya_input2 = $form_data['shuzhonggaoxueya_input2'];
        }
        if (isset($form_data['shuzhonggaoxueya_chixushijian'])){
            $shuzhong->shuzhonggaoxueya_chixushijian = $form_data['shuzhonggaoxueya_chixushijian'];
        }
        if (isset($form_data['shuzhongdixueya_input1'])){
            $shuzhong->shuzhongdixueya_input1 = $form_data['shuzhongdixueya_input1'];
        }
        if (isset($form_data['shuzhongdixueya_input2'])){
            $shuzhong->shuzhongdixueya_input2 = $form_data['shuzhongdixueya_input2'];
        }
        if (isset($form_data['shuzhongdixueya_chixushijian'])){
            $shuzhong->shuzhongdixueya_chixushijian = $form_data['shuzhongdixueya_chixushijian'];
        }
        $shuzhong->jixinghuxidaogenzu = $form_data['jixinghuxidaogenzu'];
        $shuzhong->diyangxuezheng = $form_data['diyangxuezheng'];
        $shuzhong->shuzhongdachuxue_check = $form_data['shuzhongdachuxue_check'];
        $shuzhong->gaotansuanxuezheng = $form_data['gaotansuanxuezheng'];
        $shuzhong->kongqisuansai = $form_data['kongqisuansai'];
        $shuzhong->shuzhongxiuke = $form_data['shuzhongxiuke'];
        $shuzhong->xinlvshichang_check = $form_data['xinlvshichang_check'];
        $shuzhong->xinlvshichang_select = $form_data['xinlvshichang_select'];
        $shuzhong->shuzhongsunshang_check = $form_data['shuzhongsunshang_check'];
        $shuzhong->shuzhongsunshang_select = $form_data['shuzhongsunshang_select'];
        $shuzhong->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //更新、保存术后并发症
    public function shuHouSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $shuhou = new postoperative_complications_shuhou();

        //id
        $shuhou->patients_id=$patients_id;

        //判断数据库当前患者下form_id是否存在
        $shuhou_find = postoperative_complications_shuhou::where('patients_id',$patients_id)->find();
        //更新数据
        if ($shuhou_find){
            $shuhou_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['xinlvshichang_other'])){
                $shuhou_find->xinlvshichang_other = $form_data['xinlvshichang_other'];
            }
            else{$shuhou_find->xinlvshichang_other = null;}
            if (isset($form_data['shuhoubinfazheng_other'])){
                $shuhou_find->shuhoubinfazheng_other = $form_data['shuhoubinfazheng_other'];
            }
            else{$shuhou_find->shuhoubinfazheng_other = null;}
            $shuhou_find->weishoushuqisiwang = $form_data['weishoushuqisiwang'];
            $shuhou_find->weishoushuqisiwang_select = $form_data['weishoushuqisiwang_select'];
            $shuhou_find->xinxueguanyiwai = $form_data['xinxueguanyiwai'];
            $shuhou_find->xinxueguanyiwai_select = $form_data['xinxueguanyiwai_select'];
            $shuhou_find->xinlvshichang = $form_data['xinlvshichang'];
            $shuhou_find->xinlvshichang_select = $form_data['xinlvshichang_select'];
            $shuhou_find->feisuansai = $form_data['feisuansai'];
            $shuhou_find->xinbaoshan = $form_data['xinbaoshan'];
            $shuhou_find->xiongqiangchuxue = $form_data['xiongqiangchuxue'];
            $shuhou_find->feibuzhang = $form_data['feibuzhang'];
            $shuhou_find->jixingfeishuizhong = $form_data['jixingfeishuizhong'];
            $shuhou_find->huxisuaijie = $form_data['huxisuaijie'];
            $shuhou_find->feiyeniuzhuan = $form_data['feiyeniuzhuan'];
            $shuhou_find->zhiqiguanxiongmo = $form_data['zhiqiguanxiongmo'];
            $shuhou_find->nongxiong = $form_data['nongxiong'];
            $shuhou_find->xiongmocanqiang = $form_data['xiongmocanqiang'];
            $shuhou_find->feibuganran = $form_data['feibuganran'];
            $shuhou_find->qiekouganran = $form_data['qiekouganran'];
            $shuhou_find->louqi = $form_data['louqi'];
            $shuhou_find->qiguanjingxitan = $form_data['qiguanjingxitan'];
            $shuhou_find->pibanhuaisi = $form_data['pibanhuaisi'];
            $shuhou_find->zhiruwuganran = $form_data['zhiruwuganran'];
            $shuhou_find->rumixiong = $form_data['rumixiong'];
            $shuhou_find->zhongjizhengwuli = $form_data['zhongjizhengwuli'];
            $shuhou_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $shuhou->created_time = date('Y-m-d');
        if (isset($form_data['xinlvshichang_other'])){
            $shuhou->xinlvshichang_other = $form_data['xinlvshichang_other'];
        }
        if (isset($form_data['shuhoubinfazheng_other'])){
            $shuhou->shuhoubinfazheng_other = $form_data['shuhoubinfazheng_other'];
        }
        $shuhou->weishoushuqisiwang = $form_data['weishoushuqisiwang'];
        $shuhou->weishoushuqisiwang_select = $form_data['weishoushuqisiwang_select'];
        $shuhou->xinxueguanyiwai = $form_data['xinxueguanyiwai'];
        $shuhou->xinxueguanyiwai_select = $form_data['xinxueguanyiwai_select'];
        $shuhou->xinlvshichang = $form_data['xinlvshichang'];
        $shuhou->xinlvshichang_select = $form_data['xinlvshichang_select'];
        $shuhou->feisuansai = $form_data['feisuansai'];
        $shuhou->xinbaoshan = $form_data['xinbaoshan'];
        $shuhou->xiongqiangchuxue = $form_data['xiongqiangchuxue'];
        $shuhou->feibuzhang = $form_data['feibuzhang'];
        $shuhou->jixingfeishuizhong = $form_data['jixingfeishuizhong'];
        $shuhou->huxisuaijie = $form_data['huxisuaijie'];
        $shuhou->feiyeniuzhuan = $form_data['feiyeniuzhuan'];
        $shuhou->zhiqiguanxiongmo = $form_data['zhiqiguanxiongmo'];
        $shuhou->nongxiong = $form_data['nongxiong'];
        $shuhou->xiongmocanqiang = $form_data['xiongmocanqiang'];
        $shuhou->feibuganran = $form_data['feibuganran'];
        $shuhou->qiekouganran = $form_data['qiekouganran'];
        $shuhou->louqi = $form_data['louqi'];
        $shuhou->qiguanjingxitan = $form_data['qiguanjingxitan'];
        $shuhou->pibanhuaisi = $form_data['pibanhuaisi'];
        $shuhou->zhiruwuganran = $form_data['zhiruwuganran'];
        $shuhou->rumixiong = $form_data['rumixiong'];
        $shuhou->zhongjizhengwuli = $form_data['zhongjizhengwuli'];
        $shuhou->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //更新、保存二次手术
    public function erCiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $erci = new postoperative_complications_erci();

        //id
        $erci->patients_id=$patients_id;

        //判断数据库当前患者下form_id是否存在
        $erci_find = postoperative_complications_erci::where('patients_id',$patients_id)->find();
        //更新数据
        if ($erci_find){
            $erci_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['yuanyin_other'])){
                $erci_find->yuanyin_other = $form_data['yuanyin_other'];
            }
            else{$erci_find->yuanyin_other = null;}
            if (isset($form_data['erci_time'])){
                $erci_find->erci_time = $form_data['erci_time'];
            }
            else{$erci_find->erci_time = null;}
            $erci_find->ercishoushu_check = $form_data['ercishoushu_check'];
            $erci_find->yuanyin_select = $form_data['yuanyin_select'];
            $erci_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $erci->created_time = date('Y-m-d');
        if (isset($form_data['yuanyin_other'])){
            $erci->yuanyin_other = $form_data['yuanyin_other'];
        }
        if (isset($form_data['erci_time'])){
            $erci->erci_time = $form_data['erci_time'];
        }
        $erci->ercishoushu_check = $form_data['ercishoushu_check'];
        $erci->yuanyin_select = $form_data['yuanyin_select'];
        $erci->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //更新、保存SICU
    public function icuSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $icu = new postoperative_complications_icu();

        //id
        $icu->patients_id=$patients_id;

        //判断数据库当前患者下form_id是否存在
        $icu_find = postoperative_complications_icu::where('patients_id',$patients_id)->find();
        //更新数据
        if ($icu_find){
            $icu_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['in_time'])){
                $icu_find->in_time = $form_data['in_time'];
            }
            else{$icu_find->in_time = null;}
            if (isset($form_data['icu_yuanyin_other'])){
                $icu_find->icu_yuanyin_other = $form_data['icu_yuanyin_other'];
            }
            else{$icu_find->icu_yuanyin_other = null;}
            if (isset($form_data['huxiji'])){
                $icu_find->huxiji = $form_data['huxiji'];
            }
            else{$icu_find->huxiji = null;}
            if (isset($form_data['out_time'])){
                $icu_find->out_time = $form_data['out_time'];
            }
            else{$icu_find->out_time = null;}
            $icu_find->icu_check = $form_data['icu_check'];
            $icu_find->icu_yuanyin_select = $form_data['icu_yuanyin_select'];
            $icu_find->ercichaguan_check = $form_data['ercichaguan_check'];
            $icu_find->zhuangui = $form_data['zhuangui'];
            $icu_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $icu->created_time = date('Y-m-d');
        if (isset($form_data['in_time'])){
            $icu->in_time = $form_data['in_time'];
        }
        if (isset($form_data['icu_yuanyin_other'])){
            $icu->icu_yuanyin_other = $form_data['icu_yuanyin_other'];
        }
        if (isset($form_data['huxiji'])){
            $icu->huxiji = $form_data['huxiji'];
        }
        if (isset($form_data['out_time'])){
            $icu->out_time = $form_data['out_time'];
        }
        $icu->icu_check = $form_data['icu_check'];
        $icu->icu_yuanyin_select = $form_data['icu_yuanyin_select'];
        $icu->ercichaguan_check = $form_data['ercichaguan_check'];
        $icu->zhuangui = $form_data['zhuangui'];
        $icu->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

}