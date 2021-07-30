<?php


namespace app\controller;


use think\Db;

class Query extends Base
{
    //高级查询页面
    public function patientsQuery ()
    {
        $this->isLogin();

        return $this->view->fetch();
    }

    //查询按钮
    public function resQuery()
    {
        $get_data = $this->request->param();
        //前端直接传入sql语句进行查询
        $res_t = [];
        $res = Db::query($get_data['data']);
        //查询患者基本信息表
        for ($i=0;$i < count($res);$i++){
            $patients_id = $res[$i]['patients_id'];
            $res_patients = Db::table('patients')->where('patients_id',$patients_id)->find();
            array_push($res_t,$res_patients);
        }
        $count=count($res_t);
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$res_t];

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