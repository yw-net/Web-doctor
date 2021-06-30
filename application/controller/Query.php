<?php


namespace app\controller;


class Query extends Base
{
    //用户中心页面
    public function patientsQuery ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //查询按钮
    public function resQuery()
    {

    }

}