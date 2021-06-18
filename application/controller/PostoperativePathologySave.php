<?php


namespace app\controller;


use app\model\Postoperative_pathology_bingli;
use app\model\Postoperative_pathology_jiyin;
use app\model\Postoperative_pathology_mianyi;
use app\model\Postoperative_pathology_zhongwu;

class PostoperativePathologySave extends Base
{

    //更新、保存肿物
    public function zhongWuSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $zhongwu_id = $this->request->param('form_id');

        $zhongwu = new Postoperative_pathology_zhongwu();

        //id
        $zhongwu->patients_id=$patients_id;
        $zhongwu->zhongwu_id=$zhongwu_id;

        //判断数据库当前患者下form_id是否存在
        $zhongwu_find = Postoperative_pathology_zhongwu::where(['patients_id'=>$patients_id,'zhongwu_id'=>$zhongwu_id])->find();
        //更新数据
        if ($zhongwu_find){
            $zhongwu_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['zhongwu_binglihao'])){
                $zhongwu_find->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
            }
            if (isset($form_data['zhongwu_dati_feiye_1'])){
                $zhongwu_find->zhongwu_dati_feiye_1 = $form_data['zhongwu_dati_feiye_1'];
            }
            if (isset($form_data['zhongwu_dati_feiye_2'])){
                $zhongwu_find->zhongwu_dati_feiye_2 = $form_data['zhongwu_dati_feiye_2'];
            }
            if (isset($form_data['zhongwu_dati_feiye_3'])){
                $zhongwu_find->zhongwu_dati_feiye_3 = $form_data['zhongwu_dati_feiye_3'];
            }
            if (isset($form_data['zhongwu_daxiao_1'])){
                $zhongwu_find->zhongwu_daxiao_1 = $form_data['zhongwu_daxiao_1'];
            }
            if (isset($form_data['zhongwu_daxiao_2'])){
                $zhongwu_find->zhongwu_daxiao_2 = $form_data['zhongwu_daxiao_2'];
            }
            if (isset($form_data['zhongwu_daxiao_3'])){
                $zhongwu_find->zhongwu_daxiao_3 = $form_data['zhongwu_daxiao_3'];
            }
            if (isset($form_data['zhongwu_leiji_other'])){
                $zhongwu_find->zhongwu_leiji_other = $form_data['zhongwu_leiji_other'];
            }
            if (isset($form_data['zhongwu_zhiqiguanjuli'])){
                $zhongwu_find->zhongwu_zhiqiguanjuli = $form_data['zhongwu_zhiqiguanjuli'];
            }
            if (isset($form_data['zhongwu_fenhuachengdu_other'])){
                $zhongwu_find->zhongwu_fenhuachengdu_other = $form_data['zhongwu_fenhuachengdu_other'];
            }
            $zhongwu_find->zhongwu_dati_xiongmo = $form_data['zhongwu_dati_xiongmo'];
            $zhongwu_find->zhongwu_weizhi = $form_data['zhongwu_weizhi'];
            $zhongwu_find->zhongwu_jiexian = $form_data['zhongwu_jiexian'];
            $zhongwu_find->zhongwu_yindu = $form_data['zhongwu_yindu'];
            $zhongwu_find->zhongwu_qiemian = $form_data['zhongwu_qiemian'];
            $zhongwu_find->zhongwu_zhidi = $form_data['zhongwu_zhidi'];
            $zhongwu_find->zhongwu_liuti = $form_data['zhongwu_liuti'];
            $zhongwu_find->zhongwu_leiji = $form_data['zhongwu_leiji'];
            $zhongwu_find->zhongwu_shengzhang = $form_data['zhongwu_shengzhang'];
            $zhongwu_find->zhongwu_yufeijiejie = $form_data['zhongwu_yufeijiejie'];
            $zhongwu_find->zhongwu_canduanyangxing = $form_data['zhongwu_canduanyangxing'];
            $zhongwu_find->zhongwu_maiguanaisuan = $form_data['zhongwu_maiguanaisuan'];
            $zhongwu_find->zhongwu_who = $form_data['zhongwu_who'];
            $zhongwu_find->zhongwu_fenhuachengdu = $form_data['zhongwu_fenhuachengdu'];
            $zhongwu_find->zhongwu_xiongmoqinfan = $form_data['zhongwu_xiongmoqinfan'];
            $zhongwu_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $zhongwu->created_time = date('Y-m-d');
        if (isset($form_data['zhongwu_binglihao'])){
            $zhongwu->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
        }
        if (isset($form_data['zhongwu_dati_feiye_1'])){
            $zhongwu->zhongwu_dati_feiye_1 = $form_data['zhongwu_dati_feiye_1'];
        }
        if (isset($form_data['zhongwu_dati_feiye_2'])){
            $zhongwu->zhongwu_dati_feiye_2 = $form_data['zhongwu_dati_feiye_2'];
        }
        if (isset($form_data['zhongwu_dati_feiye_3'])){
            $zhongwu->zhongwu_dati_feiye_3 = $form_data['zhongwu_dati_feiye_3'];
        }
        if (isset($form_data['zhongwu_daxiao_1'])){
            $zhongwu->zhongwu_daxiao_1 = $form_data['zhongwu_daxiao_1'];
        }
        if (isset($form_data['zhongwu_daxiao_2'])){
            $zhongwu->zhongwu_daxiao_2 = $form_data['zhongwu_daxiao_2'];
        }
        if (isset($form_data['zhongwu_daxiao_3'])){
            $zhongwu->zhongwu_daxiao_3 = $form_data['zhongwu_daxiao_3'];
        }
        if (isset($form_data['zhongwu_leiji_other'])){
            $zhongwu->zhongwu_leiji_other = $form_data['zhongwu_leiji_other'];
        }
        if (isset($form_data['zhongwu_zhiqiguanjuli'])){
            $zhongwu->zhongwu_zhiqiguanjuli = $form_data['zhongwu_zhiqiguanjuli'];
        }
        if (isset($form_data['zhongwu_fenhuachengdu_other'])){
            $zhongwu->zhongwu_fenhuachengdu_other = $form_data['zhongwu_fenhuachengdu_other'];
        }
        $zhongwu->zhongwu_dati_xiongmo = $form_data['zhongwu_dati_xiongmo'];
        $zhongwu->zhongwu_weizhi = $form_data['zhongwu_weizhi'];
        $zhongwu->zhongwu_jiexian = $form_data['zhongwu_jiexian'];
        $zhongwu->zhongwu_yindu = $form_data['zhongwu_yindu'];
        $zhongwu->zhongwu_qiemian = $form_data['zhongwu_qiemian'];
        $zhongwu->zhongwu_zhidi = $form_data['zhongwu_zhidi'];
        $zhongwu->zhongwu_liuti = $form_data['zhongwu_liuti'];
        $zhongwu->zhongwu_leiji = $form_data['zhongwu_leiji'];
        $zhongwu->zhongwu_shengzhang = $form_data['zhongwu_shengzhang'];
        $zhongwu->zhongwu_yufeijiejie = $form_data['zhongwu_yufeijiejie'];
        $zhongwu->zhongwu_canduanyangxing = $form_data['zhongwu_canduanyangxing'];
        $zhongwu->zhongwu_maiguanaisuan = $form_data['zhongwu_maiguanaisuan'];
        $zhongwu->zhongwu_who = $form_data['zhongwu_who'];
        $zhongwu->zhongwu_fenhuachengdu = $form_data['zhongwu_fenhuachengdu'];
        $zhongwu->zhongwu_xiongmoqinfan = $form_data['zhongwu_xiongmoqinfan'];
        $zhongwu->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除肿物
    public function delZhongWuInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Postoperative_pathology_zhongwu::where(['zhongwu_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存病理分期
    public function bingLiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $bingli_id = $this->request->param('form_id');

        $bingli = new Postoperative_pathology_bingli();

        //id
        $bingli->patients_id=$patients_id;
        $bingli->bingli_id=$bingli_id;

        //判断数据库当前患者下form_id是否存在
        $bingli_find = Postoperative_pathology_bingli::where(['patients_id'=>$patients_id,'bingli_id'=>$bingli_id])->find();
        //更新数据
        if ($bingli_find){
            $bingli_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['zhongwu_binglihao'])){
                $bingli_find->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
            }
            $bingli_find->zhongwu_stage_t = $form_data['zhongwu_stage_t'];
            $bingli_find->zhongwu_stage_n = $form_data['zhongwu_stage_n'];
            $bingli_find->zhongwu_stage_m = $form_data['zhongwu_stage_m'];
            $bingli_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $bingli->created_time = date('Y-m-d');
        if (isset($form_data['zhongwu_binglihao'])){
            $bingli->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
        }
        $bingli->zhongwu_stage_t = $form_data['zhongwu_stage_t'];
        $bingli->zhongwu_stage_n = $form_data['zhongwu_stage_n'];
        $bingli->zhongwu_stage_m = $form_data['zhongwu_stage_m'];
        $bingli->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除病理分期
    public function delBingLiInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Postoperative_pathology_bingli::where(['bingli_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存免疫组化
    public function mianYiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $mianyi_id = $this->request->param('form_id');

        $mianyi = new Postoperative_pathology_mianyi();

        //id
        $mianyi->patients_id=$patients_id;
        $mianyi->mianyi_id=$mianyi_id;

        //判断数据库当前患者下form_id是否存在
        $mianyi_find = Postoperative_pathology_mianyi::where(['patients_id'=>$patients_id,'mianyi_id'=>$mianyi_id])->find();
        //更新数据
        if ($mianyi_find){
            $mianyi_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['zhongwu_binglihao'])){
                $mianyi_find->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
            }
            if (isset($form_data['mianyizuhua_checkbox'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['mianyizuhua_checkbox'])) {
                    $form_data['mianyizuhua_checkbox'] = json_encode($form_data['mianyizuhua_checkbox']);
                }
                $mianyi_find->mianyizuhua_checkbox = $form_data['mianyizuhua_checkbox'];
            }
            $mianyi_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $mianyi->created_time = date('Y-m-d');
        if (isset($form_data['zhongwu_binglihao'])){
            $mianyi->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
        }
        if (isset($form_data['mianyizuhua_checkbox'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['mianyizuhua_checkbox'])) {
                $form_data['mianyizuhua_checkbox'] = json_encode($form_data['mianyizuhua_checkbox']);
            }
            $mianyi->mianyizuhua_checkbox = $form_data['mianyizuhua_checkbox'];
        }
        $mianyi->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除免疫组化
    public function delMianYiInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Postoperative_pathology_mianyi::where(['mianyi_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存基因检查
    public function jiYinSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $jiyin_id = $this->request->param('form_id');

        $jiyin = new Postoperative_pathology_jiyin();

        //id
        $jiyin->patients_id=$patients_id;
        $jiyin->jiyin_id=$jiyin_id;

        //判断数据库当前患者下form_id是否存在
        $jiyin_find = Postoperative_pathology_jiyin::where(['patients_id'=>$patients_id,'jiyin_id'=>$jiyin_id])->find();
        //更新数据
        if ($jiyin_find){
            $jiyin_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['zhongwu_binglihao'])){
                $jiyin_find->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
            }
            if (isset($form_data['jiyin_checkbox'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['jiyin_checkbox'])) {
                    $form_data['jiyin_checkbox'] = json_encode($form_data['jiyin_checkbox']);
                }
                $jiyin_find->jiyin_checkbox = $form_data['jiyin_checkbox'];
            }
            $jiyin_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $jiyin->created_time = date('Y-m-d');
        if (isset($form_data['zhongwu_binglihao'])){
            $jiyin->zhongwu_binglihao = $form_data['zhongwu_binglihao'];
        }
        if (isset($form_data['jiyin_checkbox'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['jiyin_checkbox'])) {
                $form_data['jiyin_checkbox'] = json_encode($form_data['jiyin_checkbox']);
            }
            $jiyin->jiyin_checkbox = $form_data['jiyin_checkbox'];
        }
        $jiyin->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除基因检查
    public function delJiYinInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Postoperative_pathology_jiyin::where(['jiyin_id'=>$id,'patients_id'=>$patients_id])->delete();
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