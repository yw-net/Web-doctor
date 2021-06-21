<?php


namespace app\controller;


use app\model\Follow_fufazhuanyi;
use app\model\Follow_lianxiren;

class FollowSave extends Base
{
    //更新、新增联系人信息
    public function lianXiRenSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');
        $lianxiren_id = $this->request->param('form_id');

        $lianxiren = new Follow_lianxiren();

        //id
        $lianxiren->patients_id=$patients_id;
        $lianxiren->lianxiren_id=$lianxiren_id;

        //判断数据库当前患者下form_id是否存在
        $lianxiren_find = Follow_lianxiren::where(['patients_id'=>$patients_id,'lianxiren_id'=>$lianxiren_id])->find();
        //更新数据
        if ($lianxiren_find){
            if (isset($form_data['lianxiren_name'])) {
                $lianxiren_find->lianxiren_name = $form_data['lianxiren_name'];
            }
            else{$lianxiren_find->lianxiren_name = null;}
            if (isset($form_data['lianxiren_guanxi'])){
                $lianxiren_find->lianxiren_guanxi = $form_data['lianxiren_guanxi'];
            }
            else{$lianxiren_find->lianxiren_guanxi = null;}
            if (isset($form_data['lianxiren_mobile'])){
                $lianxiren_find->lianxiren_mobile = $form_data['lianxiren_mobile'];
            }
            else{$lianxiren_find->lianxiren_mobile = null;}
            if (isset($form_data['lianxiren_zaidian'])){
                $lianxiren_find->lianxiren_zaidian = $form_data['lianxiren_zaidian'];
            }
            else{$lianxiren_find->lianxiren_zaidian = null;}
            if (isset($form_data['im'])){
                $lianxiren_find->im = $form_data['im'];
            }
            else{$lianxiren_find->im = null;}
            if (isset($form_data['email'])){
                $lianxiren_find->email = $form_data['email'];
            }
            else{$lianxiren_find->email = null;}
            $lianxiren_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        if (isset($form_data['lianxiren_name'])) {
            $lianxiren->lianxiren_name = $form_data['lianxiren_name'];
        }
        if (isset($form_data['lianxiren_guanxi'])){
            $lianxiren->lianxiren_guanxi = $form_data['lianxiren_guanxi'];
        }
        if (isset($form_data['lianxiren_mobile'])){
            $lianxiren->lianxiren_mobile = $form_data['lianxiren_mobile'];
        }
        if (isset($form_data['lianxiren_zaidian'])){
            $lianxiren->lianxiren_zaidian = $form_data['lianxiren_zaidian'];
        }
        if (isset($form_data['im'])){
            $lianxiren->im = $form_data['im'];
        }
        if (isset($form_data['email'])){
            $lianxiren->email = $form_data['email'];
        }
        $lianxiren->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除联系人信息
    public function delLianXiRenInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Follow_lianxiren::where(['lianxiren_id'=>$id,'patients_id'=>$patients_id])->delete();
        if ($del){
            $res['msg'] = '删除成功';
            $res['status'] = 1 ;
            return $res;
        }
        $res['msg'] = '数据库连接失败';
        $res['status'] = 0 ;
        return $res;
    }


    //更新、新增复发转移信息
    public function fuFaZhuanYiSaveNew(){
        //数据处理-获取表单数据
        $patients_id = $this->request->param('patients_id');
        $form_data = $this->request->param('form_data');

        $fufazhuanyi = new Follow_fufazhuanyi();

        //id
        $fufazhuanyi->patients_id=$patients_id;

        //判断数据库当前患者下form_id是否存在
        $fufazhuanyi_find = Follow_fufazhuanyi::where('patients_id',$patients_id)->find();
        //更新数据
        if ($fufazhuanyi_find){
            $fufazhuanyi_find->edit_time = date('Y-m-d H:i:s');
            if (isset($form_data['fufazhuanyi_checkbox'])) {
                //判断多选框值是否为数组（当选择两个以下强制转换为数组）
                if (!is_array($form_data['fufazhuanyi_checkbox'])) {
                    $form_data['fufazhuanyi_checkbox'] = json_encode($form_data['fufazhuanyi_checkbox']);
                }
                $fufazhuanyi_find->fufazhuanyi_checkbox = $form_data['fufazhuanyi_checkbox'];
            }
            else{$fufazhuanyi_find->fufazhuanyi_checkbox = null;}
            if (isset($form_data['fufazhuanyi_time'])) {
                $fufazhuanyi_find->fufazhuanyi_time = $form_data['fufazhuanyi_time'];
            }
            else{$fufazhuanyi_find->fufazhuanyi_time = null;}
            if (isset($form_data['fufazhuanyi_other'])){
                $fufazhuanyi_find->fufazhuanyi_other = $form_data['fufazhuanyi_other'];
            }
            else{$fufazhuanyi_find->fufazhuanyi_other = null;}
            $fufazhuanyi_find->fufazhuanyi_check = $form_data['fufazhuanyi_check'];
            $fufazhuanyi_find->save();
            $res['status'] = 1;
            $res['msg'] ='数据更新成功';
            return $res;
        }
        //新增数据
        $fufazhuanyi->created_time = date('Y-m-d');
        if (isset($form_data['fufazhuanyi_checkbox'])) {
            //判断多选框值是否为数组（当选择两个以下强制转换为数组）
            if (!is_array($form_data['fufazhuanyi_checkbox'])) {
                $form_data['fufazhuanyi_checkbox'] = json_encode($form_data['fufazhuanyi_checkbox']);
            }
            $fufazhuanyi->fufazhuanyi_checkbox = $form_data['fufazhuanyi_checkbox'];
        }
        if (isset($form_data['fufazhuanyi_time'])) {
            $fufazhuanyi->fufazhuanyi_time = $form_data['fufazhuanyi_time'];
        }
        if (isset($form_data['fufazhuanyi_other'])){
            $fufazhuanyi->fufazhuanyi_other = $form_data['fufazhuanyi_other'];
        }
        $fufazhuanyi->fufazhuanyi_check = $form_data['fufazhuanyi_check'];
        $fufazhuanyi->save();
        $res['status'] = 1;
        $res['msg'] ='数据保存成功';
        return $res;



    }

    //删除复发转移信息
    public function delFuFaZhuanYiInfo()
    {
        $patients_id = $this->request->param('patients_id');
        $id = $this->request->param('form_id');
        $del = Follow_lianxiren::where(['lianxiren_id'=>$id,'patients_id'=>$patients_id])->delete();
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