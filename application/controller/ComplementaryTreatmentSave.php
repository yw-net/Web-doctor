<?php


namespace app\controller;


use app\model\Complementary_treatment_baxiang;
use app\model\Complementary_treatment_fangliao;
use app\model\Complementary_treatment_gamadao;
use app\model\Complementary_treatment_hualiao;

class ComplementaryTreatmentSave extends Base
{
    //更新、保存化疗
    public function huaLiaoSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $hualiao_id = $this->request->param('form_id');

        $hualiao = new Complementary_treatment_hualiao();

        //id
        $hualiao->patients_id=$patients_id;
        $hualiao->hualiao_id=$hualiao_id;

        //判断数据库当前患者下form_id是否存在
        $hualiao_find = Complementary_treatment_hualiao::where(['patients_id'=>$patients_id,'hualiao_id'=>$hualiao_id])->find();
        //更新数据
        if ($hualiao_find){
            $hualiao_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['hualiao_start_time'])){
                $hualiao_find->hualiao_start_time = $form_data['hualiao_start_time'];
            }
            if (isset($form_data['hualiao_end_time'])){
                $hualiao_find->hualiao_end_time = $form_data['hualiao_end_time'];
            }
            if (isset($form_data['hualiao_zhouqi'])){
                $hualiao_find->hualiao_zhouqi = $form_data['hualiao_zhouqi'];
            }
            if (isset($form_data['hualiao_liubiao_check_time'])){
                $hualiao_find->hualiao_liubiao_check_time = $form_data['hualiao_liubiao_check_time'];
            }
            if (isset($form_data['hualiao_cea'])){
                $hualiao_find->hualiao_cea = $form_data['hualiao_cea'];
            }
            if (isset($form_data['hualiao_cyfra211'])){
                $hualiao_find->hualiao_cyfra211 = $form_data['hualiao_cyfra211'];
            }
            if (isset($form_data['hualiao_ca199'])){
                $hualiao_find->hualiao_ca199 = $form_data['hualiao_ca199'];
            }
            if (isset($form_data['hualiao_cea1'])){
                $hualiao_find->hualiao_cea1 = $form_data['hualiao_cea1'];
            }
            if (isset($form_data['hualiao_nse'])){
                $hualiao_find->hualiao_nse = $form_data['hualiao_nse'];
            }
            if (isset($form_data['hualiao_ca125'])){
                $hualiao_find->hualiao_ca125 = $form_data['hualiao_ca125'];
            }
            $hualiao_find->hualiao_fangan = $form_data['hualiao_fangan'];
            $hualiao_find->hualiao_xuehongdanbai = $form_data['hualiao_xuehongdanbai'];
            $hualiao_find->hualiao_lixibao = $form_data['hualiao_lixibao'];
            $hualiao_find->hualiao_baixibao = $form_data['hualiao_baixibao'];
            $hualiao_find->hualiao_xuexiaoban = $form_data['hualiao_xuexiaoban'];
            $hualiao_find->hualiao_chuxue = $form_data['hualiao_chuxue'];
            $hualiao_find->hualiao_danhongsu = $form_data['hualiao_danhongsu'];
            $hualiao_find->hualiao_zhuananmei = $form_data['hualiao_zhuananmei'];
            $hualiao_find->hualiao_jianxinlinsuan = $form_data['hualiao_jianxinlinsuan'];
            $hualiao_find->hualiao_kouqiang = $form_data['hualiao_kouqiang'];
            $hualiao_find->hualiaog_exin = $form_data['hualiaog_exin'];
            $hualiao_find->hualiao_fuxie = $form_data['hualiao_fuxie'];
            $hualiao_find->hualiao_bun = $form_data['hualiao_bun'];
            $hualiao_find->hualiao_jigan = $form_data['hualiao_jigan'];
            $hualiao_find->hualiao_danbailiao = $form_data['hualiao_danbailiao'];
            $hualiao_find->hualiao_xueniao = $form_data['hualiao_xueniao'];
            $hualiao_find->hualiao_fei = $form_data['hualiao_fei'];
            $hualiao_find->hualiao_fare = $form_data['hualiao_fare'];
            $hualiao_find->hualiao_guomin = $form_data['hualiao_guomin'];
            $hualiao_find->hualiao_pifu = $form_data['hualiao_pifu'];
            $hualiao_find->hualiao_toufa = $form_data['hualiao_toufa'];
            $hualiao_find->hualiao_ganran = $form_data['hualiao_ganran'];
            $hualiao_find->hualiao_shenzhi = $form_data['hualiao_shenzhi'];
            $hualiao_find->hualiao_zhouweishenjing = $form_data['hualiao_zhouweishenjing'];
            $hualiao_find->hualiao_bianmi = $form_data['hualiao_bianmi'];
            $hualiao_find->hualiao_tengtong = $form_data['hualiao_tengtong'];
            $hualiao_find->hualiao_xinlv = $form_data['hualiao_xinlv'];
            $hualiao_find->hualiao_xingongneng = $form_data['hualiao_xingongneng'];
            $hualiao_find->hualiao_xinbaoyan = $form_data['hualiao_xinbaoyan'];
            $hualiao_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $hualiao->created_time = date('Y-m-d');
        if (isset($form_data['hualiao_start_time'])){
            $hualiao->hualiao_start_time = $form_data['hualiao_start_time'];
        }
        if (isset($form_data['hualiao_end_time'])){
            $hualiao->hualiao_end_time = $form_data['hualiao_end_time'];
        }
        if (isset($form_data['hualiao_zhouqi'])){
            $hualiao->hualiao_zhouqi = $form_data['hualiao_zhouqi'];
        }
        if (isset($form_data['hualiao_liubiao_check_time'])){
            $hualiao->hualiao_liubiao_check_time = $form_data['hualiao_liubiao_check_time'];
        }
        if (isset($form_data['hualiao_cea'])){
            $hualiao->hualiao_cea = $form_data['hualiao_cea'];
        }
        if (isset($form_data['hualiao_cyfra211'])){
            $hualiao->hualiao_cyfra211 = $form_data['hualiao_cyfra211'];
        }
        if (isset($form_data['hualiao_ca199'])){
            $hualiao->hualiao_ca199 = $form_data['hualiao_ca199'];
        }
        if (isset($form_data['hualiao_cea1'])){
            $hualiao->hualiao_cea1 = $form_data['hualiao_cea1'];
        }
        if (isset($form_data['hualiao_nse'])){
            $hualiao->hualiao_nse = $form_data['hualiao_nse'];
        }
        if (isset($form_data['hualiao_ca125'])){
            $hualiao->hualiao_ca125 = $form_data['hualiao_ca125'];
        }
        $hualiao->hualiao_fangan = $form_data['hualiao_fangan'];
        $hualiao->hualiao_xuehongdanbai = $form_data['hualiao_xuehongdanbai'];
        $hualiao->hualiao_lixibao = $form_data['hualiao_lixibao'];
        $hualiao->hualiao_baixibao = $form_data['hualiao_baixibao'];
        $hualiao->hualiao_xuexiaoban = $form_data['hualiao_xuexiaoban'];
        $hualiao->hualiao_chuxue = $form_data['hualiao_chuxue'];
        $hualiao->hualiao_danhongsu = $form_data['hualiao_danhongsu'];
        $hualiao->hualiao_zhuananmei = $form_data['hualiao_zhuananmei'];
        $hualiao->hualiao_jianxinlinsuan = $form_data['hualiao_jianxinlinsuan'];
        $hualiao->hualiao_kouqiang = $form_data['hualiao_kouqiang'];
        $hualiao->hualiaog_exin = $form_data['hualiaog_exin'];
        $hualiao->hualiao_fuxie = $form_data['hualiao_fuxie'];
        $hualiao->hualiao_bun = $form_data['hualiao_bun'];
        $hualiao->hualiao_jigan = $form_data['hualiao_jigan'];
        $hualiao->hualiao_danbailiao = $form_data['hualiao_danbailiao'];
        $hualiao->hualiao_xueniao = $form_data['hualiao_xueniao'];
        $hualiao->hualiao_fei = $form_data['hualiao_fei'];
        $hualiao->hualiao_fare = $form_data['hualiao_fare'];
        $hualiao->hualiao_guomin = $form_data['hualiao_guomin'];
        $hualiao->hualiao_pifu = $form_data['hualiao_pifu'];
        $hualiao->hualiao_toufa = $form_data['hualiao_toufa'];
        $hualiao->hualiao_ganran = $form_data['hualiao_ganran'];
        $hualiao->hualiao_shenzhi = $form_data['hualiao_shenzhi'];
        $hualiao->hualiao_zhouweishenjing = $form_data['hualiao_zhouweishenjing'];
        $hualiao->hualiao_bianmi = $form_data['hualiao_bianmi'];
        $hualiao->hualiao_tengtong = $form_data['hualiao_tengtong'];
        $hualiao->hualiao_xinlv = $form_data['hualiao_xinlv'];
        $hualiao->hualiao_xingongneng = $form_data['hualiao_xingongneng'];
        $hualiao->hualiao_xinbaoyan = $form_data['hualiao_xinbaoyan'];
        $hualiao->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除化疗
    public function delHuaLiaoInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Complementary_treatment_hualiao::where(['hualiao_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存放疗
    public function fangLiaoSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $fangliao_id = $this->request->param('form_id');

        $fangliao = new Complementary_treatment_fangliao();

        //id
        $fangliao->patients_id=$patients_id;
        $fangliao->fangliao_id=$fangliao_id;

        //判断数据库当前患者下form_id是否存在
        $fangliao_find = Complementary_treatment_fangliao::where(['patients_id'=>$patients_id,'fangliao_id'=>$fangliao_id])->find();
        //更新数据
        if ($fangliao_find){
            $fangliao_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['fangliao_time'])){
                $fangliao_find->fangliao_time = $form_data['fangliao_time'];
            }
            if (isset($form_data['fangliao_feibu1'])){
                $fangliao_find->fangliao_feibu1 = $form_data['fangliao_feibu1'];
            }
            if (isset($form_data['fangliao_feibu2'])){
                $fangliao_find->fangliao_feibu2 = $form_data['fangliao_feibu2'];
            }
            if (isset($form_data['fangliao_naobu1'])){
                $fangliao_find->fangliao_naobu1 = $form_data['fangliao_naobu1'];
            }
            if (isset($form_data['fangliao_naobu2'])){
                $fangliao_find->fangliao_naobu2 = $form_data['fangliao_naobu2'];
            }
            if (isset($form_data['fangliao_guge1'])){
                $fangliao_find->fangliao_guge1 = $form_data['fangliao_guge1'];
            }
            if (isset($form_data['fangliao_guge2'])){
                $fangliao_find->fangliao_guge2 = $form_data['fangliao_guge2'];
            }
            if (isset($form_data['fangliao_other1'])){
                $fangliao_find->fangliao_other1 = $form_data['fangliao_other1'];
            }
            if (isset($form_data['fangliao_other2'])){
                $fangliao_find->fangliao_other2 = $form_data['fangliao_other2'];
            }
            $fangliao_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $fangliao->created_time = date('Y-m-d');
        if (isset($form_data['fangliao_time'])){
            $fangliao->fangliao_time = $form_data['fangliao_time'];
        }
        if (isset($form_data['fangliao_feibu1'])){
            $fangliao->fangliao_feibu1 = $form_data['fangliao_feibu1'];
        }
        if (isset($form_data['fangliao_feibu2'])){
            $fangliao->fangliao_feibu2 = $form_data['fangliao_feibu2'];
        }
        if (isset($form_data['fangliao_naobu1'])){
            $fangliao->fangliao_naobu1 = $form_data['fangliao_naobu1'];
        }
        if (isset($form_data['fangliao_naobu2'])){
            $fangliao->fangliao_naobu2 = $form_data['fangliao_naobu2'];
        }
        if (isset($form_data['fangliao_guge1'])){
            $fangliao->fangliao_guge1 = $form_data['fangliao_guge1'];
        }
        if (isset($form_data['fangliao_guge2'])){
            $fangliao->fangliao_guge2 = $form_data['fangliao_guge2'];
        }
        if (isset($form_data['fangliao_other1'])){
            $fangliao->fangliao_other1 = $form_data['fangliao_other1'];
        }
        if (isset($form_data['fangliao_other2'])){
            $fangliao->fangliao_other2 = $form_data['fangliao_other2'];
        }
        $fangliao->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除放疗
    public function delFangLiaoInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Complementary_treatment_fangliao::where(['fangliao_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存伽玛刀
    public function gaMaDaoSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $gamadao_id = $this->request->param('form_id');

        $gamadao = new Complementary_treatment_gamadao();

        //id
        $gamadao->patients_id=$patients_id;
        $gamadao->gamadao_id=$gamadao_id;

        //判断数据库当前患者下form_id是否存在
        $gamadao_find = Complementary_treatment_gamadao::where(['patients_id'=>$patients_id,'gamadao_id'=>$gamadao_id])->find();
        //更新数据
        if ($gamadao_find){
            $gamadao_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['gamadao_time'])){
                $gamadao_find->gamadao_time = $form_data['gamadao_time'];
            }
            if (isset($form_data['gamadao_feibu1'])){
                $gamadao_find->gamadao_feibu1 = $form_data['gamadao_feibu1'];
            }
            if (isset($form_data['gamadao_feibu2'])){
                $gamadao_find->gamadao_feibu2 = $form_data['gamadao_feibu2'];
            }
            if (isset($form_data['gamadao_naobu1'])){
                $gamadao_find->gamadao_naobu1 = $form_data['gamadao_naobu1'];
            }
            if (isset($form_data['gamadao_naobu2'])){
                $gamadao_find->gamadao_naobu2 = $form_data['gamadao_naobu2'];
            }
            if (isset($form_data['gamadao_guge1'])){
                $gamadao_find->gamadao_guge1 = $form_data['gamadao_guge1'];
            }
            if (isset($form_data['gamadao_guge2'])){
                $gamadao_find->gamadao_guge2 = $form_data['gamadao_guge2'];
            }
            if (isset($form_data['gamadao_ganbu1'])){
                $gamadao_find->gamadao_ganbu1 = $form_data['gamadao_ganbu1'];
            }
            if (isset($form_data['gamadao_ganbu2'])){
                $gamadao_find->gamadao_ganbu2 = $form_data['gamadao_ganbu2'];
            }
            if (isset($form_data['gamadao_other1'])){
                $gamadao_find->gamadao_other1 = $form_data['gamadao_other1'];
            }
            if (isset($form_data['gamadao_other2'])){
                $gamadao_find->gamadao_other2 = $form_data['gamadao_other2'];
            }
            $gamadao_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $gamadao->created_time = date('Y-m-d');
        if (isset($form_data['gamadao_time'])){
            $gamadao->gamadao_time = $form_data['gamadao_time'];
        }
        if (isset($form_data['gamadao_feibu1'])){
            $gamadao->gamadao_feibu1 = $form_data['gamadao_feibu1'];
        }
        if (isset($form_data['gamadao_feibu2'])){
            $gamadao->gamadao_feibu2 = $form_data['gamadao_feibu2'];
        }
        if (isset($form_data['gamadao_naobu1'])){
            $gamadao->gamadao_naobu1 = $form_data['gamadao_naobu1'];
        }
        if (isset($form_data['gamadao_naobu2'])){
            $gamadao->gamadao_naobu2 = $form_data['gamadao_naobu2'];
        }
        if (isset($form_data['gamadao_guge1'])){
            $gamadao->gamadao_guge1 = $form_data['gamadao_guge1'];
        }
        if (isset($form_data['gamadao_guge2'])){
            $gamadao->gamadao_guge2 = $form_data['gamadao_guge2'];
        }
        if (isset($form_data['gamadao_ganbu1'])){
            $gamadao->gamadao_ganbu1 = $form_data['gamadao_ganbu1'];
        }
        if (isset($form_data['gamadao_ganbu2'])){
            $gamadao->gamadao_ganbu2 = $form_data['gamadao_ganbu2'];
        }
        if (isset($form_data['gamadao_other1'])){
            $gamadao->gamadao_other1 = $form_data['gamadao_other1'];
        }
        if (isset($form_data['gamadao_other2'])){
            $gamadao->gamadao_other2 = $form_data['gamadao_other2'];
        }
        $gamadao->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除伽玛刀
    public function delGaMaDaoInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Complementary_treatment_gamadao::where(['gamadao_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存靶向
    public function baXiangSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $baxiang_id = $this->request->param('form_id');

        $baxiang = new Complementary_treatment_baxiang();

        //id
        $baxiang->patients_id=$patients_id;
        $baxiang->baxiang_id=$baxiang_id;

        //判断数据库当前患者下form_id是否存在
        $baxiang_find = Complementary_treatment_baxiang::where(['patients_id'=>$patients_id,'baxiang_id'=>$baxiang_id])->find();
        //更新数据
        if ($baxiang_find){
            $baxiang_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['baxiang_yaowu'])){
                $baxiang_find->baxiang_yaowu = $form_data['baxiang_yaowu'];
            }
            if (isset($form_data['baxiang_start_time'])){
                $baxiang_find->baxiang_start_time = $form_data['baxiang_start_time'];
            }
            if (isset($form_data['baxiang_end_time'])){
                $baxiang_find->baxiang_end_time = $form_data['baxiang_end_time'];
            }
            $baxiang_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $baxiang->created_time = date('Y-m-d');
        if (isset($form_data['baxiang_yaowu'])){
            $baxiang->baxiang_yaowu = $form_data['baxiang_yaowu'];
        }
        if (isset($form_data['baxiang_start_time'])){
            $baxiang->baxiang_start_time = $form_data['baxiang_start_time'];
        }
        if (isset($form_data['baxiang_end_time'])){
            $baxiang->baxiang_end_time = $form_data['baxiang_end_time'];
        }
        $baxiang->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除靶向
    public function delBaXiangInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Complementary_treatment_baxiang::where(['baxiang_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }

}