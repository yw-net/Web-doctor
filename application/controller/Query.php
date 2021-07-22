<?php


namespace app\controller;


use think\Db;

class Query extends Base
{
    //高级查询页面
    public function patientsQuery ()
    {
        $this->isLogin();

//        //患者基本信息表
//        $patients = Db::query('SHOW FULL COLUMNS FROM patients');
//        $patients_key= [];
//        $patients_val = [];
//        //提取有用字段组合成数组
//        foreach ($patients as $patients){
//            array_push($patients_val,$patients['Comment']);
//            array_push($patients_key,$patients['Field']);
//        }
//        //去除数组中空的字段
//        foreach ($patients_val as $k=>$v){
//            if($v == ''){
//                unset($patients_val[$k]);
//                unset($patients_key[$k]);
//            }
//        }
//        //两个一维数组合并成二维数组
//        foreach($patients_key as $key=>$val){
//            $res_patients[$val] = $patients_val[$key];
//        }
//
//        //患者家族肿瘤史
//        $history_family = Db::query('SHOW FULL COLUMNS FROM history_family');
//        $history_family_key = [];
//        $history_family_val = [];
//        //提取有用字段组合成数组
//        foreach ($history_family as $history_family){
//            array_push($history_family_val,$history_family['Comment']);
//            array_push($history_family_key,$history_family['Field']);
//        }
//        //去除数组中空的字段
//        foreach ($history_family_val as $k=>$v){
//            if($v == ''){
//                unset($history_family_val[$k]);
//                unset($history_family_key[$k]);
//            }
//        }
//        //两个一维数组合并成二维数组
//        foreach($history_family_key as $key=>$val){
//            $res_history_family[$val] = $history_family_val[$key];
//        }
//
//
//
//        $this->assign(['res_patients'=>$res_patients,'res_history_family'=>$res_history_family]);
        return $this->view->fetch();
    }

    //查询按钮
    public function resQuery()
    {
        $get_data = $this->request->param();
        //前端直接传入sql语句进行查询
        $res_t = [];
        $res = Db::query($get_data['data']);
        //组合查询结果
        for ($i=0;$i < count($res);$i++){
            $patients_id = $res[$i]['patients_id'];
            $res_patients = Db::table('patients')->where('patients_id',$patients_id)->find();
            array_push($res_t,$res_patients);
        }
        $count=count($res_t);
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$res_t];
//        foreach ($res as $k=>$v){
//            $patients_id = $v['patients_id'];
//            $res_patients = Db::table('patients')->where('patients_id',$patients_id)->find();
//            array_push($res_t,$res_patients);
//        }
//        $count=count($res_t);
//        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$res_t];


    }

    //返回前端二级联动下拉选择框显示的表名
    public function resTable()
    {
        $get = $this->request->param();
        $get_fields = $get['data'];
        $patients = Db::query($get_fields);
        $patients_key= [];
        $patients_val = [];
        //提取有用字段组合成数组
        foreach ($patients as $patients){
            array_push($patients_val,$patients['Comment']);
            array_push($patients_key,$patients['Field']);
        }
        //去除数组中空的字段
        foreach ($patients_val as $k=>$v){
            if($v == ''){
                unset($patients_val[$k]);
                unset($patients_key[$k]);
            }
        }
        //两个一维数组合并成二维数组
        foreach($patients_key as $key=>$val){
            $res_patients[$val] = $patients_val[$key];
        }
        return $res_patients;

    }

}