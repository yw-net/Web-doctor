<?php


namespace app\controller;
use app\model\Patients;
use think\Controller;
use think\facade\Session;
use think\Db;
use app\model\User;
use think\Request;
use think\db\Where;
use think\View;
use think\facade\Env;
class Base extends Controller
{

    //判断用户未登录
    protected function isLogin()
    {
        define('USER_NAME', Session::get('user_name'));
        if (empty(USER_NAME)) {
            $this->redirect('index/login');
        }
    }

    //判断用户已登录
    protected function alreadyLogin()
    {
        define('USER_NAME', Session::get('user_name'));
        if (!empty(USER_NAME)) {
            $this->redirect('index/index');
        }
    }

    //判断患者是否已经存在
    protected function isOld()
    {
        $patientsid = Session::get('patientsid');
        $is_old = Patients::where('patients_id',$patientsid)->find();
        $text = '请先保存患者基本信息';
        if (!$is_old){
            return $this->error($text);
        }
    }


}