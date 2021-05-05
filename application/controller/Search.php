<?php


namespace app\controller;
use app\controller\Base;
use app\model\Patients;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\facade\Session;

class Search extends Base
{
    //患者基本信息报表页面
    public function patient_list ()
    {
        $this->isLogin();
        $sql = "select * from user";
        $doctor = Db::query($sql);

        $this->assign(['doctor'=>$doctor]);
        return $this->view->fetch();
    }

}