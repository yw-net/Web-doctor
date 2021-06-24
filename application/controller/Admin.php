<?php


namespace app\controller;


class Admin extends Base
{
    //用户中心页面
    public function myinfo ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }

    //修改密码页面
    public function password(){
        $this->isLogin();
        return $this->view->fetch();
    }

    //后台管理页面
    public function manage ()
    {
        $this->isLogin();
        return $this->view->fetch();
    }
}