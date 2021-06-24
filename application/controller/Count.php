<?php


namespace app\controller;


use think\Db;

class Count extends Base
{

    //统计报表报表页面
    public function baseList ()
    {
        $this->isLogin();
        $sql = "select * from user";
        $doctor = Db::query($sql);

        $this->assign(['doctor'=>$doctor]);
        return $this->view->fetch();
    }
}