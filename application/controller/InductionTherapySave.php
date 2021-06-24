<?php


namespace app\controller;


use app\model\Induction_therapy_baxiang;
use app\model\Induction_therapy_fangliao;
use app\model\Induction_therapy_houct;
use app\model\Induction_therapy_houfenqi;
use app\model\Induction_therapy_houpet;
use app\model\Induction_therapy_hualiao;
use app\model\Induction_therapy_qianfenqi;

class InductionTherapySave extends Base
{
    //更新、保存前分期
    public function qianfenqiSave(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $qianfenqi = new Induction_therapy_qianfenqi();

        //id
        $qianfenqi->patients_id=$patients_id;

        //判断数据库当前患者是否存在
        $qianfenqi_find = Induction_therapy_qianfenqi::where('patients_id',$patients_id)->find();
        //更新数据
        if ($qianfenqi_find){
            $qianfenqi_find->edit_time = date('Y-m-d H:i:s');
            $qianfenqi_find->t = $form_data['qianfenqi_t'];
            $qianfenqi_find->m = $form_data['qianfenqi_m'];
            $qianfenqi_find->n = $form_data['qianfenqi_n'];
            $qianfenqi_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $qianfenqi->created_time = date('Y-m-d');
        $qianfenqi->t = $form_data['qianfenqi_t'];
        $qianfenqi->m = $form_data['qianfenqi_m'];
        $qianfenqi->n = $form_data['qianfenqi_n'];
        $qianfenqi->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }


    //更新、保存化疗
    public function huaLiaoSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $hualiao_id = $this->request->param('form_id');

        $hualiao = new Induction_therapy_hualiao();

        //id
        $hualiao->patients_id=$patients_id;
        $hualiao->hualiao_id=$hualiao_id;

        //判断数据库当前患者下form_id是否存在
        $hualiao_find = Induction_therapy_hualiao::where(['patients_id'=>$patients_id,'hualiao_id'=>$hualiao_id])->find();
        //更新数据
        if ($hualiao_find){
            $hualiao_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['hualiao_cycle'])){
                $hualiao_find->hualiao_cycle = $form_data['hualiao_cycle'];
            }
            if (isset($form_data['hualiao_plan'])){
                $hualiao_find->hualiao_plan = $form_data['hualiao_plan'];
            }
            if (isset($form_data['hualiao_more'])){
                $hualiao_find->hualiao_more = $form_data['hualiao_more'];
            }
            $hualiao_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $hualiao->created_time = date('Y-m-d');
        if (isset($form_data['hualiao_cycle'])){
            $hualiao->hualiao_cycle = $form_data['hualiao_cycle'];
        }
        if (isset($form_data['hualiao_plan'])){
            $hualiao->hualiao_plan = $form_data['hualiao_plan'];
        }
        if (isset($form_data['hualiao_more'])){
            $hualiao->hualiao_more = $form_data['hualiao_more'];
        }
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
        $del = Induction_therapy_hualiao::where(['hualiao_id'=>$id,'patients_id'=>$patients_id])->delete();
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

        $fangliao = new Induction_therapy_fangliao();

        //id
        $fangliao->patients_id=$patients_id;
        $fangliao->fangliao_id=$fangliao_id;

        //判断数据库当前患者下form_id是否存在
        $fangliao_find = Induction_therapy_fangliao::where(['patients_id'=>$patients_id,'fangliao_id'=>$fangliao_id])->find();
        //更新数据
        if ($fangliao_find){
            $fangliao_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['check_time'])){
                $fangliao_find->check_time = $form_data['check_time'];
            }
            if (isset($form_data['fangliao_plan'])){
                $fangliao_find->fangliao_plan = $form_data['fangliao_plan'];
            }
            if (isset($form_data['fangliao_more'])){
                $fangliao_find->fangliao_more = $form_data['fangliao_more'];
            }
            $fangliao_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $fangliao->created_time = date('Y-m-d');
        if (isset($form_data['check_time'])){
            $fangliao->check_time = $form_data['check_time'];
        }
        if (isset($form_data['fangliao_plan'])){
            $fangliao->fangliao_plan = $form_data['fangliao_plan'];
        }
        if (isset($form_data['fangliao_more'])){
            $fangliao->fangliao_more = $form_data['fangliao_more'];
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
        $del = Induction_therapy_fangliao::where(['fangliao_id'=>$id,'patients_id'=>$patients_id])->delete();
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

        $baxiang = new Induction_therapy_baxiang();

        //id
        $baxiang->patients_id=$patients_id;
        $baxiang->baxiang_id=$baxiang_id;

        //判断数据库当前患者下form_id是否存在
        $baxiang_find = Induction_therapy_baxiang::where(['patients_id'=>$patients_id,'baxiang_id'=>$baxiang_id])->find();
        //更新数据
        if ($baxiang_find){
            $baxiang_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['check_time'])){
                $baxiang_find->check_time = $form_data['check_time'];
            }
            if (isset($form_data['baxiang_yaowu'])){
                $baxiang_find->baxiang_yaowu = $form_data['baxiang_yaowu'];
            }
            if (isset($form_data['baxiang_more'])){
                $baxiang_find->baxiang_more = $form_data['baxiang_more'];
            }
            $baxiang_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $baxiang->created_time = date('Y-m-d');
        if (isset($form_data['check_time'])){
            $baxiang->check_time = $form_data['check_time'];
        }
        if (isset($form_data['baxiang_yaowu'])){
            $baxiang->baxiang_yaowu = $form_data['baxiang_yaowu'];
        }
        if (isset($form_data['baxiang_more'])){
            $baxiang->baxiang_more = $form_data['baxiang_more'];
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
        $del = Induction_therapy_baxiang::where(['baxiang_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存后分期
    public function houFenQiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $houfenqi_id = $this->request->param('form_id');

        $houfenqi = new Induction_therapy_houfenqi();

        //id
        $houfenqi->patients_id=$patients_id;
        $houfenqi->houfenqi_id=$houfenqi_id;

        //判断数据库当前患者下form_id是否存在
        $houfenqi_find = Induction_therapy_houfenqi::where(['patients_id'=>$patients_id,'houfenqi_id'=>$houfenqi_id])->find();
        //更新数据
        if ($houfenqi_find){
            $houfenqi_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['check_time'])){
                $houfenqi_find->check_time = $form_data['check_time'];
            }
            $houfenqi_find->stage = $form_data['stage'];
            $houfenqi_find->houfenqi_t = $form_data['houfenqi_t'];
            $houfenqi_find->houfenqi_m = $form_data['houfenqi_m'];
            $houfenqi_find->houfenqi_n = $form_data['houfenqi_n'];
            $houfenqi_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $houfenqi->created_time = date('Y-m-d');
        if (isset($form_data['check_time'])){
            $houfenqi->check_time = $form_data['check_time'];
        }
        $houfenqi->stage = $form_data['stage'];
        $houfenqi->houfenqi_t = $form_data['houfenqi_t'];
        $houfenqi->houfenqi_m = $form_data['houfenqi_m'];
        $houfenqi->houfenqi_n = $form_data['houfenqi_n'];
        $houfenqi->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除后分期
    public function delHouFenQiInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Induction_therapy_houfenqi::where(['houfenqi_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存后CT
    public function houctSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $houct_id = $this->request->param('form_id');

        $houct = new Induction_therapy_houct();

        //id
        $houct->patients_id=$patients_id;
        $houct->houct_id=$houct_id;

        //判断数据库当前患者下form_id是否存在
        $houct_find = Induction_therapy_houct::where(['patients_id'=>$patients_id,'houct_id'=>$houct_id])->find();
        //更新数据
        if ($houct_find){
            $houct_find->edit_time = date('Y-m-d H:i:s');
            $houct_find->resist = $form_data['resist'];
            $houct_find->n1 = $form_data['n1'];
            $houct_find->n2 = $form_data['n2'];
            $houct_find->n3 = $form_data['n3'];
            $houct_find->n4 = $form_data['n4'];
            $houct_find->n5 = $form_data['n5'];
            $houct_find->n6 = $form_data['n6'];
            $houct_find->n7 = $form_data['n7'];
            $houct_find->n8 = $form_data['n8'];
            $houct_find->n9 = $form_data['n3'];
            $houct_find->n10 = $form_data['n10'];
            $houct_find->n11 = $form_data['n11'];
            $houct_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $houct->created_time = date('Y-m-d');
        $houct->resist = $form_data['resist'];
        $houct->n1 = $form_data['n1'];
        $houct->n2 = $form_data['n2'];
        $houct->n3 = $form_data['n3'];
        $houct->n4 = $form_data['n4'];
        $houct->n5 = $form_data['n5'];
        $houct->n6 = $form_data['n6'];
        $houct->n7 = $form_data['n7'];
        $houct->n8 = $form_data['n8'];
        $houct->n9 = $form_data['n3'];
        $houct->n10 = $form_data['n10'];
        $houct->n11 = $form_data['n11'];
        $houct->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除后CT
    public function delHouctInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Induction_therapy_houct::where(['houct_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、保存后PET
    public function houpetSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $houpet_id = $this->request->param('form_id');

        $houpet = new Induction_therapy_houpet();

        //id
        $houpet->patients_id=$patients_id;
        $houpet->houpet_id=$houpet_id;

        //判断数据库当前患者下form_id是否存在
        $houpet_find = Induction_therapy_houpet::where(['patients_id'=>$patients_id,'houpet_id'=>$houpet_id])->find();
        //更新数据
        if ($houpet_find){
            $houpet_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['pet_num'])){
                $houpet_find->pet_num = $form_data['pet_num'];
            }
            if (isset($form_data['pet_more'])){
                $houpet_find->pet_more = $form_data['pet_more'];
            }
            $houpet_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $houpet->created_time = date('Y-m-d');
        if (isset($form_data['pet_num'])){
            $houpet->pet_num = $form_data['pet_num'];
        }
        if (isset($form_data['pet_more'])){
            $houpet->pet_more = $form_data['pet_more'];
        }
        $houpet->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;
    }

    //删除后PET
    public function delHoupetInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Induction_therapy_houpet::where(['houpet_id'=>$id,'patients_id'=>$patients_id])->delete();
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